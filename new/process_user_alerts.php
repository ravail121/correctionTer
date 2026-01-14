<?php


require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../smtp/PHPMailerAutoload.php';

// Get database connection
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error . "\n");
}

echo "========================================\n";
echo "User Alert Processing Script\n";
echo "Started at: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n\n";

$alertsSent = 0;
$alertsFailed = 0;

// ============================================================================
// PROCESS "DOWN MORE THAN X%" ALERTS
// ============================================================================
echo "Processing 'Down More Than X%' alerts...\n";

$downValueQuery = "SELECT dv.id, dv.email, dv.symbol, dv.down_more, c.name, c.correction_range, c.last_close, c.all_time_high 
                   FROM down_value dv 
                   LEFT JOIN companies c ON dv.symbol = c.symbl 
                   WHERE dv.status = 'not sent' 
                   AND c.symbl IS NOT NULL";

$result = $con->query($downValueQuery);

if (!$result) {
    echo "ERROR: Failed to query down_value table: " . $con->error . "\n";
} else {
    $downAlerts = [];
    
    while ($row = $result->fetch_assoc()) {
        $alertId = $row['id'];
        $email = $row['email'];
        $symbol = $row['symbol'];
        $name = $row['name'];
        $downMore = floatval($row['down_more']);
        $correctionRange = floatval($row['correction_range']);
        $lastClose = floatval($row['last_close']);
        $allTimeHigh = floatval($row['all_time_high']);
        
        // Check if condition is met: correction_range should be <= -down_more
        // (e.g., if user wants alert when down 10%, correction_range should be <= -10)
        if ($correctionRange <= -$downMore) {
            $downAlerts[] = [
                'id' => $alertId,
                'email' => $email,
                'symbol' => $symbol,
                'name' => $name,
                'down_more' => $downMore,
                'correction_range' => $correctionRange,
                'last_close' => $lastClose,
                'all_time_high' => $allTimeHigh
            ];
        }
    }
    
    echo "Found " . count($downAlerts) . " 'Down More Than X%' alert(s) that meet conditions.\n";
    
    // Send emails for each alert
    foreach ($downAlerts as $alert) {
        $subject = "Stock Alert: {$alert['symbol']} is Down More Than {$alert['down_more']}%";
        
        $body = "Hello,\n\n";
        $body .= "You requested an alert when {$alert['name']} ({$alert['symbol']}) is down more than {$alert['down_more']}%.\n\n";
        $body .= "Current Status:\n";
        $body .= "Symbol: {$alert['symbol']}\n";
        $body .= "Company: {$alert['name']}\n";
        $body .= "Current Price: $" . number_format($alert['last_close'], 2) . "\n";
        $body .= "All-Time High: $" . number_format($alert['all_time_high'], 2) . "\n";
        $body .= "Correction Range: " . number_format($alert['correction_range'], 2) . "%\n";
        $body .= "Down From ATH: " . number_format(abs($alert['correction_range']), 2) . "%\n\n";
        $body .= "This alert was triggered because the stock is currently down " . number_format(abs($alert['correction_range']), 2) . "% from its all-time high, which exceeds your threshold of {$alert['down_more']}%.\n\n";
        $body .= "View the stock: https://correctionterritory.com/?filterCompany={$alert['symbol']}\n\n";
        $body .= "Best regards,\n";
        $body .= "Correction Territory\n";
        $body .= "Generated at: " . date('Y-m-d H:i:s') . "\n";
        
        // Send email
        $emailSent = sendEmail($alert['email'], $subject, $body);
        
        if ($emailSent) {
            // Update status to 'sent'
            $updateQuery = "UPDATE down_value SET status = 'sent' WHERE id = ?";
            $stmt = $con->prepare($updateQuery);
            if ($stmt) {
                $stmt->bind_param("i", $alert['id']);
                if ($stmt->execute()) {
                    echo "SUCCESS: Alert #{$alert['id']} sent to {$alert['email']} for {$alert['symbol']}\n";
                    $alertsSent++;
                } else {
                    echo "WARNING: Alert sent but failed to update status for #{$alert['id']}: " . $stmt->error . "\n";
                }
                $stmt->close();
            } else {
                echo "ERROR: Failed to prepare update statement for alert #{$alert['id']}: " . $con->error . "\n";
            }
        } else {
            echo "ERROR: Failed to send alert #{$alert['id']} to {$alert['email']} for {$alert['symbol']}\n";
            $alertsFailed++;
        }
    }
}

// ============================================================================
// PROCESS "NEW ALL-TIME HIGH" ALERTS
// ============================================================================
echo "\nProcessing 'New All-Time High' alerts...\n";

// Get all pending new_ath alerts
$newAthQuery = "SELECT na.id, na.email, na.symbol, c.name, c.all_time_high, c.all_time_high_count, c.last_close, c.correction_range 
                FROM new_ath na 
                LEFT JOIN companies c ON na.symbol = c.symbl 
                WHERE na.status = 'not sent' 
                AND c.symbl IS NOT NULL";

$result = $con->query($newAthQuery);

if (!$result) {
    echo "ERROR: Failed to query new_ath table: " . $con->error . "\n";
} else {
    $athAlerts = [];
    
    while ($row = $result->fetch_assoc()) {
        $alertId = $row['id'];
        $email = $row['email'];
        $symbol = $row['symbol'];
        $name = $row['name'];
        $allTimeHigh = floatval($row['all_time_high']);
        $allTimeHighCount = intval($row['all_time_high_count']);
        $lastClose = floatval($row['last_close']);
        $correctionRange = floatval($row['correction_range']);
        
        // Check if condition is met: 
        // correction_range >= 0 means current price is at or above ATH
        // This indicates the stock is currently at or has exceeded its all-time high
        // Note: This will trigger when stock is at ATH, which is what we want for "new ATH" alerts
        if ($correctionRange >= 0 && $allTimeHighCount > 0) {
            $athAlerts[] = [
                'id' => $alertId,
                'email' => $email,
                'symbol' => $symbol,
                'name' => $name,
                'all_time_high' => $allTimeHigh,
                'all_time_high_count' => $allTimeHighCount,
                'last_close' => $lastClose,
                'correction_range' => $correctionRange
            ];
        }
    }
    
    echo "Found " . count($athAlerts) . " 'New All-Time High' alert(s) that meet conditions.\n";
    
    // Send emails for each alert
    foreach ($athAlerts as $alert) {
        $subject = "Stock Alert: {$alert['symbol']} Hit a New All-Time High!";
        
        $body = "Hello,\n\n";
        $body .= "You requested an alert when {$alert['name']} ({$alert['symbol']}) hits a new all-time high.\n\n";
        $body .= "🎉 Congratulations! The stock has reached a new all-time high!\n\n";
        $body .= "Current Status:\n";
        $body .= "Symbol: {$alert['symbol']}\n";
        $body .= "Company: {$alert['name']}\n";
        $body .= "Current Price: $" . number_format($alert['last_close'], 2) . "\n";
        $body .= "All-Time High: $" . number_format($alert['all_time_high'], 2) . "\n";
        $body .= "All-Time High Count: {$alert['all_time_high_count']} time(s)\n";
        if ($alert['correction_range'] > 0) {
            $body .= "Above Previous ATH: +" . number_format($alert['correction_range'], 2) . "%\n";
        } else {
            $body .= "At All-Time High: 0.00%\n";
        }
        $body .= "\n";
        $body .= "Best regards,\n";
        $body .= "Correction Territory\n";

        
        // Send email
        $emailSent = sendEmail($alert['email'], $subject, $body);
        
        if ($emailSent) {
            // Update status to 'sent'
            $updateQuery = "UPDATE new_ath SET status = 'sent' WHERE id = ?";
            $stmt = $con->prepare($updateQuery);
            if ($stmt) {
                $stmt->bind_param("i", $alert['id']);
                if ($stmt->execute()) {
                    echo "SUCCESS: Alert #{$alert['id']} sent to {$alert['email']} for {$alert['symbol']}\n";
                    $alertsSent++;
                } else {
                    echo "WARNING: Alert sent but failed to update status for #{$alert['id']}: " . $stmt->error . "\n";
                }
                $stmt->close();
            } else {
                echo "ERROR: Failed to prepare update statement for alert #{$alert['id']}: " . $con->error . "\n";
            }
        } else {
            echo "ERROR: Failed to send alert #{$alert['id']} to {$alert['email']} for {$alert['symbol']}\n";
            $alertsFailed++;
        }
    }
}

// ============================================================================
// SUMMARY
// ============================================================================
echo "\n========================================\n";
echo "Processing Complete\n";
echo "Alerts sent successfully: {$alertsSent}\n";
echo "Alerts failed: {$alertsFailed}\n";
echo "Finished at: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n";

$con->close();

/**
 * Send email using PHPMailer with fallback to PHP mail()
 */
function sendEmail($to, $subject, $body) {
    try {
        $mail = new PHPMailer;
        
        // Use mail() function (works better with cPanel)
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
        $mail->addAddress($to);
        
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);
        
        if ($mail->send()) {
            return true;
        } else {
            error_log("PHPMailer send failed for {$to}: " . $mail->ErrorInfo);
            
            // Fallback to PHP mail() function
            $headers = "From: info@correctionterritory.com\r\n";
            $headers .= "Reply-To: info@correctionterritory.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            if (mail($to, $subject, $body, $headers)) {
                return true;
            } else {
                error_log("PHP mail() function also failed for {$to}");
                return false;
            }
        }
    } catch (Exception $e) {
        error_log("Email exception for {$to}: " . $e->getMessage());
        
        // Fallback to PHP mail() function
        $headers = "From: info@correctionterritory.com\r\n";
        $headers .= "Reply-To: info@correctionterritory.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $body, $headers)) {
            return true;
        } else {
            error_log("Fallback mail() also failed for {$to}");
            return false;
        }
    }
}

?>

