<?php
require_once __DIR__ . '/../smtp/PHPMailerAutoload.php';

class ErrorTracker {
    private $errors = [];
    private $missingFields = [];
    private $failedStocks = [];
    private $errorTypes = [];
    private $lastOkTimes = [];
    private $con;
    private $scriptName;
    
    public function __construct($dbConnection, $scriptName = 'default') {
        $this->con = $dbConnection;
        $this->scriptName = basename($scriptName, '.php');
    }
    
    /**
     * Check if this is a stock script (not crypto or indexes)
     * Stock scripts are those with "companies" or "company" in their name
     */
    private function isStockScript() {
        $name = strtolower($this->scriptName);
        return stripos($name, 'companies') !== false || stripos($name, 'company') !== false;
    }
    
    /**
     * Check if this is a crypto script
     * Crypto scripts are those with "crypto" in their name
     */
    private function isCryptoScript() {
        $name = strtolower($this->scriptName);
        return stripos($name, 'crypto') !== false;
    }
    
    /**
     * Check if this is an index script
     * Index scripts are those with "index" or "indexes" in their name
     */
    private function isIndexScript() {
        $name = strtolower($this->scriptName);
        return stripos($name, 'index') !== false;
    }
    
    /**
     * Record an error for a stock, crypto, or index
     * For stock, crypto, and index scripts, saves to database. For other scripts, keeps in memory for email sending.
     */
    public function recordError($symbol, $name, $error, $missingFields = []) {
        $this->failedStocks[$symbol] = [
            'name' => $name,
            'error' => $error,
            'missing' => $missingFields,
            'time' => date('Y-m-d H:i:s')
        ];
        
        // Track error types
        $errorType = $this->categorizeError($error);
        if (!isset($this->errorTypes[$errorType])) {
            $this->errorTypes[$errorType] = 0;
        }
        $this->errorTypes[$errorType]++;
        
        // Track missing fields
        foreach ($missingFields as $field) {
            if (!isset($this->missingFields[$field])) {
                $this->missingFields[$field] = 0;
            }
            $this->missingFields[$field]++;
        }
        
        // Save error to unified JSON table (works for stocks, crypto, and indexes)
        if ($this->isStockScript() || $this->isCryptoScript() || $this->isIndexScript()) {
            $this->saveErrorToUnifiedDatabase($symbol, $name, $error, $missingFields, $errorType);
        }
    }
    
    /**
     * Save error to unified database table as JSON
     * One entry per day with all errors stored as JSON
     */
    private function saveErrorToUnifiedDatabase($symbol, $name, $error, $missingFields = [], $errorType = null) {
        if (!$this->con) {
            error_log("ErrorTracker: No database connection available to save error");
            return;
        }
        
        if (!$errorType) {
            $errorType = $this->categorizeError($error);
        }
        
        $missingFieldsStr = !empty($missingFields) ? implode(', ', $missingFields) : null;
        $lastOkTime = $this->getLastOkTime($symbol);
        $today = date('Y-m-d');
        
        // Determine error category (stocks, crypto, or indexes)
        $category = 'stocks';
        if ($this->isCryptoScript()) {
            $category = 'crypto';
        } elseif ($this->isIndexScript()) {
            $category = 'indexes';
        }
        
        // Create error object
        $errorData = [
            'script_name' => $this->scriptName,
            'symbol' => $symbol,
            'name' => $name,
            'error_message' => $error,
            'missing_fields' => $missingFieldsStr,
            'error_type' => $errorType,
            'error_time' => date('Y-m-d H:i:s'),
            'last_ok_time' => $lastOkTime
        ];
        
        // Get or create today's entry
        $stmt = $this->con->prepare("SELECT errors_json FROM error_alerts WHERE date = ?");
        if ($stmt) {
            $stmt->bind_param("s", $today);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $errorsData = ['stocks' => [], 'crypto' => [], 'indexes' => []];
            
            if ($row = $result->fetch_assoc()) {
                // Parse existing JSON
                $existingJson = $row['errors_json'];
                if (!empty($existingJson)) {
                    $decoded = json_decode($existingJson, true);
                    if ($decoded && is_array($decoded)) {
                        $errorsData = $decoded;
                        // Ensure all categories exist
                        if (!isset($errorsData['stocks'])) $errorsData['stocks'] = [];
                        if (!isset($errorsData['crypto'])) $errorsData['crypto'] = [];
                        if (!isset($errorsData['indexes'])) $errorsData['indexes'] = [];
                    }
                }
            }
            $stmt->close();
            
            // Add new error to appropriate category (only keep latest per symbol per script)
            $found = false;
            if (isset($errorsData[$category])) {
                foreach ($errorsData[$category] as $idx => $existingError) {
                    if ($existingError['symbol'] === $symbol && $existingError['script_name'] === $this->scriptName) {
                        // Update existing error
                        $errorsData[$category][$idx] = $errorData;
                        $found = true;
                        break;
                    }
                }
            }
            
            if (!$found) {
                // Add new error
                $errorsData[$category][] = $errorData;
            }
            
            // Save back to database
            $errorsJson = json_encode($errorsData, JSON_PRETTY_PRINT);
            
            $updateStmt = $this->con->prepare("
                INSERT INTO error_alerts (date, errors_json, alert_sent)
                VALUES (?, ?, 0)
                ON DUPLICATE KEY UPDATE errors_json = ?, last_updated = NOW()
            ");
            
            if ($updateStmt) {
                $updateStmt->bind_param("sss", $today, $errorsJson, $errorsJson);
                if (!$updateStmt->execute()) {
                    error_log("ErrorTracker: Failed to save error to unified database: " . $updateStmt->error);
                }
                $updateStmt->close();
            } else {
                error_log("ErrorTracker: Failed to prepare update statement: " . $this->con->error);
            }
        } else {
            error_log("ErrorTracker: Failed to prepare select statement: " . $this->con->error);
        }
    }
    
    /**
     * Record successful processing of a stock
     */
    public function recordSuccess($symbol) {
        $this->lastOkTimes[$symbol] = date('Y-m-d H:i:s');
    }
    
    /**
     * Get last OK time for a stock/crypto from database
     */
    public function getLastOkTime($symbol) {
        // First check if we recorded success in this run
        if (isset($this->lastOkTimes[$symbol])) {
            return $this->lastOkTimes[$symbol];
        }
        
        // Otherwise, get from database
        if ($this->con) {
            // Detect if this is crypto, index, or stock based on script name
            $isCrypto = stripos($this->scriptName, 'crypto') !== false;
            $isIndex = stripos($this->scriptName, 'index') !== false;
            
            if ($isCrypto) {
                // For crypto, check if there's a date_time column, otherwise just return current time if record exists
                $stmt = $this->con->prepare("SELECT symbol FROM crypto WHERE symbol = ? LIMIT 1");
            } elseif ($isIndex) {
                // For indexes, get date_time from indexes table
                $stmt = $this->con->prepare("SELECT date_time FROM indexes WHERE symbl = ? ORDER BY date_time DESC LIMIT 1");
            } else {
                // For stocks, get date_time from companies table
                $stmt = $this->con->prepare("SELECT date_time FROM companies WHERE symbl = ? ORDER BY date_time DESC LIMIT 1");
            }
            
            if ($stmt) {
                $stmt->bind_param("s", $symbol);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    if ($isCrypto) {
                        // Crypto table might not have date_time, so return a generic message
                        return 'Recently';
                    } elseif ($isIndex) {
                        // Indexes table has date_time column
                        return !empty($row['date_time']) ? $row['date_time'] : 'Never';
                    } else {
                        // Stocks table has date_time column
                        return $row['date_time'];
                    }
                }
                $stmt->close();
            }
        }
        
        return 'Never';
    }
    
    /**
     * Categorize error type
     */
    private function categorizeError($error) {
        $error = strtolower($error);
        if (strpos($error, 'api') !== false || strpos($error, 'fetch') !== false) {
            return 'API Error';
        } elseif (strpos($error, 'database') !== false || strpos($error, 'sql') !== false) {
            return 'Database Error';
        } elseif (strpos($error, 'null') !== false || strpos($error, 'missing') !== false) {
            return 'Data Missing';
        } else {
            return 'Other Error';
        }
    }
    
    /**
     * Check if we should send alert based on database bit flag
     * Returns true if bit is 0 (can send) and there are errors
     */
    private function shouldSendAlert() {
        // If no errors, don't send alert
        if (count($this->failedStocks) === 0) {
            return false;
        }
        
        // Check database bit flag
        if (!$this->con) {
            return false;
        }
        
        $stmt = $this->con->prepare("SELECT alert_sent FROM error_alerts WHERE script_name = ?");
        if (!$stmt) {
            error_log("Error preparing statement: " . $this->con->error);
            return false;
        }
        
        $stmt->bind_param("s", $this->scriptName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // If bit is 0, we can send alert. If bit is 1, don't send.
            $alertSent = (int)$row['alert_sent'];
            $stmt->close();
            return $alertSent === 0;
        } else {
            // No record exists, create one with bit = 0 (can send)
            $stmt->close();
            $insertStmt = $this->con->prepare("INSERT INTO error_alerts (script_name, alert_sent) VALUES (?, 0)");
            if ($insertStmt) {
                $insertStmt->bind_param("s", $this->scriptName);
                $insertStmt->execute();
                $insertStmt->close();
            }
            return true; // First time, can send alert
        }
    }
    
    /**
     * Set the alert_sent bit to 1 after sending email
     */
    private function markAlertSent() {
        if (!$this->con) {
            return;
        }
        
        $stmt = $this->con->prepare("INSERT INTO error_alerts (script_name, alert_sent) VALUES (?, 1) ON DUPLICATE KEY UPDATE alert_sent = 1");
        if ($stmt) {
            $stmt->bind_param("s", $this->scriptName);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    /**
     * Send email alert if bit is 0 and there are errors
     * Note: Stock, crypto, and index scripts should NOT call this method - they only save to database
     */
    public function sendAlertIfNeeded($totalStocks) {
        // Stock scripts should not send emails - they only save to database
        if ($this->isStockScript()) {
            return false;
        }
        
        // Crypto scripts should not send emails - they only save to database
        if ($this->isCryptoScript()) {
            return false;
        }
        
        // Index scripts should not send emails - they only save to database
        if ($this->isIndexScript()) {
            return false;
        }
        
        if (!$this->shouldSendAlert()) {
            return false;
        }
        
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $failedCount = count($this->failedStocks);
        $missingFieldsCount = array_sum($this->missingFields);
        $errorTypesStr = implode(', ', array_keys($this->errorTypes));
        
        // Detect if this is crypto, indexes, or stock based on script name
        $isCrypto = stripos($this->scriptName, 'crypto') !== false;
        $isIndexes = stripos($this->scriptName, 'indexes') !== false;
        
        if ($isCrypto) {
            $subject = "Massive API Crypto Data Pull Failed — {$date} — {$time}";
            $body = "Missing crypto: {$failedCount}/{$totalStocks} | Missing fields: {$missingFieldsCount}/" . ($totalStocks * 4) . " | Errors: {$errorTypesStr}\n\n";
        } elseif ($isIndexes) {
            $subject = "Massive API Index Data Pull Failed — {$date} — {$time}";
            $body = "Missing indexes: {$failedCount}/{$totalStocks} | Missing fields: {$missingFieldsCount}/" . ($totalStocks * 4) . " | Errors: {$errorTypesStr}\n\n";
        } else {
            $subject = "Massive API Stock Data Pull Failed — {$date} — {$time}";
            $body = "Missing stocks: {$failedCount}/{$totalStocks} | Missing fields: {$missingFieldsCount}/" . ($totalStocks * 4) . " | Errors: {$errorTypesStr}\n\n";
        }
        
        foreach ($this->failedStocks as $symbol => $data) {
            $missingStr = !empty($data['missing']) ? implode(', ', $data['missing']) : 'None';
            $lastOk = $this->getLastOkTime($symbol);
            $body .= "{$data['name']} ({$symbol}) — Missing: {$missingStr} | Last OK: {$lastOk} | Error: {$data['error']}\n";
        }
        
        $body .= "\n\nThe data pull runs every hour. (cron)\n";
        $body .= "To stop receiving alerts, set alert_sent = 1 in the error_alerts table for this script.\n";
        $body .= "To resume alerts, set alert_sent = 0 after fixing the issues.\n";
        
        $emailSent = $this->sendEmail($subject, $body);
        
        // If email sent successfully, mark bit as 1
        if ($emailSent) {
            $this->markAlertSent();
        }
        
        return $emailSent;
    }
    
    /**
     * Send email using PHPMailer or PHP mail() function
     */
    private function sendEmail($subject, $body) {
        try {
            $mail = new PHPMailer;
            
            // Try using mail() function first (works better with cPanel)
            $mail->isMail();
            
            // Alternative SMTP settings (uncomment if isMail() doesn't work)
            /*
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->Port = 25;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            */
            
            $mail->setFrom('info@correctionterritory.com', 'Correction Territory');
            $mail->addAddress('chen.ben.ari@gmail.com', 'Chen Ben Ari');
            $mail->addAddress('rvlirshad@gmail.com', 'Ravail Irshad');
            
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->isHTML(false);
            
            if ($mail->send()) {
                return true;
            } else {
                error_log("PHPMailer send failed: " . $mail->ErrorInfo);
                
                // Fallback to PHP mail() function
                $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
                $headers = "From: info@correctionterritory.com\r\n";
                $headers .= "Reply-To: info@correctionterritory.com\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                
                if (mail($to, $subject, $body, $headers)) {
                    return true;
                } else {
                    error_log("PHP mail() function also failed");
                    return false;
                }
            }
        } catch (Exception $e) {
            error_log("Email exception: " . $e->getMessage());
            
            // Fallback to PHP mail() function
            $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
            $headers = "From: info@correctionterritory.com\r\n";
            $headers .= "Reply-To: info@correctionterritory.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            return mail($to, $subject, $body, $headers);
        }
    }
    
    /**
     * Get summary statistics
     */
    public function getSummary() {
        return [
            'failed' => count($this->failedStocks),
            'missing_fields' => array_sum($this->missingFields),
            'error_types' => $this->errorTypes
        ];
    }
}

