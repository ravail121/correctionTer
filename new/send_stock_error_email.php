<?php
/**
 * Nightly Stock, Crypto, and Index Error Email Script
 * 
 * This script runs once at night to collect all stock, crypto, and index errors from the database
 * and send one combined email with error summaries and details.
 * 
 * Cron example (run at 11:00 PM daily):
 * 0 23 * * * /usr/bin/php /path/to/send_stock_error_email.php
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../smtp/PHPMailerAutoload.php';

// Get database connection
echo "Starting nightly error email script...\n";
echo "Loading required files...\n";
echo "SUCCESS: Required files loaded (config.php, functions.php, PHPMailerAutoload.php).\n";

echo "Connecting to database...\n";
echo "Database host: " . DB_HOST . "\n";
echo "Database name: " . DB_NAME . "\n";
echo "Database user: " . DB_USER . "\n";
try {
    if (!function_exists('getDbConnection')) {
        echo "ERROR: getDbConnection() function is not defined. Check if functions.php is loaded correctly.\n";
        exit(1);
    }
    echo "SUCCESS: getDbConnection() function is available.\n";
    
    echo "Calling getDbConnection()...\n";
    $con = getDbConnection();
    echo "SUCCESS: getDbConnection() returned successfully.\n";
    
    if ($con->connect_error) {
        echo "ERROR: Failed to connect to database: " . $con->connect_error . "\n";
        error_log("Failed to connect to database: " . $con->connect_error);
        exit(1);
    }
    echo "SUCCESS: Database connection established.\n";
} catch (Exception $e) {
    echo "ERROR: Exception occurred while connecting to database: " . $e->getMessage() . "\n";
    error_log("Database connection exception: " . $e->getMessage());
    exit(1);
} catch (Error $e) {
    echo "ERROR: Fatal error occurred while connecting to database: " . $e->getMessage() . "\n";
    error_log("Database connection fatal error: " . $e->getMessage());
    exit(1);
}

// Get today's date
$today = date('Y-m-d');

// Check if we should send alert (check email sent bit for today)
echo "Checking alert status for date: {$today}...\n";
$alertCheckStmt = $con->prepare("SELECT alert_sent, errors_json FROM error_alerts WHERE date = ?");
if (!$alertCheckStmt) {
    echo "ERROR: Failed to prepare alert check statement: " . $con->error . "\n";
    error_log("Failed to prepare alert check statement: " . $con->error);
    $con->close();
    exit(1);
}

$alertCheckStmt->bind_param("s", $today);
if (!$alertCheckStmt->execute()) {
    echo "ERROR: Failed to execute alert check query: " . $alertCheckStmt->error . "\n";
    $alertCheckStmt->close();
    $con->close();
    exit(1);
}
$alertResult = $alertCheckStmt->get_result();
$alertRow = $alertResult->fetch_assoc();
$alertCheckStmt->close();
echo "SUCCESS: Alert status checked.\n";

// If email already sent and bit is still 1, don't send new email
$alertSent = isset($alertRow['alert_sent']) ? (int)$alertRow['alert_sent'] : 0;
if ($alertSent === 1) {
    echo "Email already sent for today (alert_sent = 1). No new email will be sent until manually reset.\n";
    $con->close();
    exit(0);
}

// Get errors JSON for today (or empty if no entry exists)
echo "Retrieving errors JSON from database...\n";
$errorsJson = isset($alertRow['errors_json']) ? $alertRow['errors_json'] : '{"stocks":[],"crypto":[],"indexes":[]}';
$allErrors = json_decode($errorsJson, true);
if (!$allErrors || !is_array($allErrors)) {
    echo "WARNING: Invalid JSON format, initializing empty error arrays.\n";
    $allErrors = ['stocks' => [], 'crypto' => [], 'indexes' => []];
}

// Ensure all categories exist
if (!isset($allErrors['stocks'])) $allErrors['stocks'] = [];
if (!isset($allErrors['crypto'])) $allErrors['crypto'] = [];
if (!isset($allErrors['indexes'])) $allErrors['indexes'] = [];
echo "SUCCESS: Errors JSON retrieved and parsed.\n";

// ============================================================================
// PROCESS ERRORS FROM JSON
// ============================================================================

// Helper function to process errors array
function processErrors($errorsArray, &$errors, &$scriptErrors, &$missingSymbols, &$missingFieldsCount, &$errorTypesCount) {
    foreach ($errorsArray as $error) {
        $errors[] = $error;
        
        $scriptName = $error['script_name'] ?? '';
        $symbol = $error['symbol'] ?? '';
        
        // Count missing symbols (unique)
        if (!in_array($symbol, $missingSymbols)) {
            $missingSymbols[] = $symbol;
        }
        
        // Group errors by script
        if (!isset($scriptErrors[$scriptName])) {
            $scriptErrors[$scriptName] = [];
        }
        $scriptErrors[$scriptName][] = $error;
        
        // Count missing fields
        if (!empty($error['missing_fields'])) {
            $fields = explode(', ', $error['missing_fields']);
            foreach ($fields as $field) {
                $field = trim($field);
                if (!empty($field)) {
                    if (!isset($missingFieldsCount[$field])) {
                        $missingFieldsCount[$field] = 0;
                    }
                    $missingFieldsCount[$field]++;
                }
            }
        }
        
        // Count error types
        $errorType = $error['error_type'] ?? 'Other Error';
        if (!isset($errorTypesCount[$errorType])) {
            $errorTypesCount[$errorType] = 0;
        }
        $errorTypesCount[$errorType]++;
    }
}

// Process stock errors
echo "Processing stock errors...\n";
$stockErrors = [];
$stockScriptErrors = [];
$missingStocks = [];
$stockMissingFieldsCount = [];
$stockErrorTypesCount = [];
processErrors($allErrors['stocks'], $stockErrors, $stockScriptErrors, $missingStocks, $stockMissingFieldsCount, $stockErrorTypesCount);
echo "SUCCESS: Processed " . count($stockErrors) . " stock error(s).\n";

// Process crypto errors
echo "Processing crypto errors...\n";
$cryptoErrors = [];
$cryptoScriptErrors = [];
$missingCryptos = [];
$cryptoMissingFieldsCount = [];
$cryptoErrorTypesCount = [];
processErrors($allErrors['crypto'], $cryptoErrors, $cryptoScriptErrors, $missingCryptos, $cryptoMissingFieldsCount, $cryptoErrorTypesCount);
echo "SUCCESS: Processed " . count($cryptoErrors) . " crypto error(s).\n";

// Process index errors
echo "Processing index errors...\n";
$indexErrors = [];
$indexScriptErrors = [];
$missingIndexes = [];
$indexMissingFieldsCount = [];
$indexErrorTypesCount = [];
processErrors($allErrors['indexes'], $indexErrors, $indexScriptErrors, $missingIndexes, $indexMissingFieldsCount, $indexErrorTypesCount);
echo "SUCCESS: Processed " . count($indexErrors) . " index error(s).\n";

// ============================================================================
// CHECK IF THERE ARE ANY ERRORS
// ============================================================================
$totalErrors = count($stockErrors) + count($cryptoErrors) + count($indexErrors);

if ($totalErrors === 0) {
    echo "No stock, crypto, or index errors found for today. No email will be sent.\n";
    $con->close();
    exit(0);
}

// ============================================================================
// BUILD EMAIL CONTENT
// ============================================================================
echo "Building email content...\n";
$date = $today;
$time = date('H:i:s');
$missingStocksCount = count($missingStocks);
$missingCryptosCount = count($missingCryptos);
$missingIndexesCount = isset($missingIndexes) ? count($missingIndexes) : 0;
$totalStockMissingFields = array_sum($stockMissingFieldsCount);
$totalCryptoMissingFields = array_sum($cryptoMissingFieldsCount);
$stockErrorTypesStr = implode(', ', array_keys($stockErrorTypesCount));
$cryptoErrorTypesStr = implode(', ', array_keys($cryptoErrorTypesCount));

$subject = "Massive API Data Pulled Failed. {$date}";
echo "SUCCESS: Email content built. Subject: {$subject}\n";

$body = "STOCK, CRYPTO, AND INDEX ERROR SUMMARY\n";
$body .= "======================================\n\n";
$body .= "Date: {$date}\n";
$body .= "Time: {$time}\n\n";

// ============================================================================
// STOCK SECTION
// ============================================================================
if (!empty($stockErrors)) {
    $body .= "STOCKS\n";
    $body .= "======\n\n";
    
    $body .= "COUNTS:\n";
    $body .= "-------\n";
    $body .= "Missing Stocks: {$missingStocksCount}\n";
    $body .= "\n";
    
    if (!empty($stockMissingFieldsCount)) {
        $body .= "MISSING FIELDS BREAKDOWN:\n";
        $body .= "-------------------------\n";
        foreach ($stockMissingFieldsCount as $field => $count) {
            $body .= "  {$field}: {$count}\n";
        }
        $body .= "\n";
    }
    
    $body .= "DETAILED ERROR LIST:\n";
    $body .= "====================\n\n";
    
    // List all errors without script grouping
    foreach ($stockErrors as $error) {
        $missingFieldsStr = !empty($error['missing_fields']) ? $error['missing_fields'] : 'None';
        $lastOkTime = !empty($error['last_ok_time']) ? $error['last_ok_time'] : 'Never';
        $errorTime = $error['error_time'];
        
        $body .= "  {$error['name']} ({$error['symbol']})\n";
        $body .= "    Last OK Time: {$lastOkTime}\n";
        $body .= "    Error: {$error['error_message']}\n";
        $body .= "    Error Time: {$errorTime}\n";
        $body .= "\n";
    }
    
    $body .= "\n";
}

// ============================================================================
// CRYPTO SECTION
// ============================================================================
if (!empty($cryptoErrors)) {
    $body .= "CRYPTO\n";
    $body .= "======\n\n";
    
    $body .= "COUNTS:\n";
    $body .= "-------\n";
    $body .= "Missing Cryptos: {$missingCryptosCount}\n";
    $body .= "\n";
    
    if (!empty($cryptoMissingFieldsCount)) {
        $body .= "MISSING FIELDS BREAKDOWN:\n";
        $body .= "-------------------------\n";
        foreach ($cryptoMissingFieldsCount as $field => $count) {
            $body .= "  {$field}: {$count}\n";
        }
        $body .= "\n";
    }
    
    $body .= "DETAILED ERROR LIST:\n";
    $body .= "====================\n\n";
    
    // List all errors without script grouping
    foreach ($cryptoErrors as $error) {
        $missingFieldsStr = !empty($error['missing_fields']) ? $error['missing_fields'] : 'None';
        $lastOkTime = !empty($error['last_ok_time']) ? $error['last_ok_time'] : 'Never';
        $errorTime = $error['error_time'];
        
        $body .= "  {$error['name']} ({$error['symbol']})\n";
        $body .= "    Last OK Time: {$lastOkTime}\n";
        $body .= "    Error: {$error['error_message']}\n";
        $body .= "    Error Time: {$errorTime}\n";
        $body .= "\n";
    }
    
    $body .= "\n";
}

// ============================================================================
// INDEX SECTION
// ============================================================================
if (!empty($indexErrors)) {
    $totalIndexMissingFields = array_sum($indexMissingFieldsCount);
    $indexErrorTypesStr = implode(', ', array_keys($indexErrorTypesCount));
    
    $body .= "INDEXES\n";
    $body .= "=======\n\n";
    
    $body .= "COUNTS:\n";
    $body .= "-------\n";
    $body .= "Missing Indexes: {$missingIndexesCount}\n";
    $body .= "\n";
    
    if (!empty($indexMissingFieldsCount)) {
        $body .= "MISSING FIELDS BREAKDOWN:\n";
        $body .= "-------------------------\n";
        foreach ($indexMissingFieldsCount as $field => $count) {
            $body .= "  {$field}: {$count}\n";
        }
        $body .= "\n";
    }
    
    $body .= "DETAILED ERROR LIST:\n";
    $body .= "====================\n\n";
    
    // List all errors without script grouping
    foreach ($indexErrors as $error) {
        $missingFieldsStr = !empty($error['missing_fields']) ? $error['missing_fields'] : 'None';
        $lastOkTime = !empty($error['last_ok_time']) ? $error['last_ok_time'] : 'Never';
        $errorTime = $error['error_time'];
        
        $body .= "  {$error['name']} ({$error['symbol']})\n";
        $body .= "    Last OK Time: {$lastOkTime}\n";
        $body .= "    Error: {$error['error_message']}\n";
        $body .= "    Error Time: {$errorTime}\n";
        $body .= "\n";
    }
    
    $body .= "\n";
}

// ============================================================================
// FOOTER
// ============================================================================
$body .= "\n";
$body .= "This report is generated nightly. Stock, crypto, and index errors are saved throughout the day.\n";
$body .= "To stop receiving alerts, set alert_sent = 1 in the error_alerts table for today.\n";
$body .= "UPDATE error_alerts SET alert_sent = 1 WHERE date = CURDATE();\n";
$body .= "To resume alerts, set alert_sent = 0 after fixing the issues.\n";

// ============================================================================
// SEND EMAIL
// ============================================================================
echo "Preparing to send email...\n";
$emailSent = false;
try {
    $mail = new PHPMailer;
    echo "SUCCESS: PHPMailer object created.\n";
    
    // Try using mail() function first (works better with cPanel)
    $mail->isMail();
    echo "Attempting to send email using PHPMailer (mail() method)...\n";
    
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
        $emailSent = true;
        echo "SUCCESS: Email sent successfully with {$missingStocksCount} stock error(s), {$missingCryptosCount} crypto error(s), and {$missingIndexesCount} index error(s).\n";
    } else {
        echo "FAILURE: PHPMailer send failed: " . $mail->ErrorInfo . "\n";
        error_log("PHPMailer send failed: " . $mail->ErrorInfo);
        
        // Fallback to PHP mail() function
        echo "Attempting fallback: PHP mail() function...\n";
        $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
        $headers = "From: info@correctionterritory.com\r\n";
        $headers .= "Reply-To: info@correctionterritory.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $body, $headers)) {
            $emailSent = true;
            echo "SUCCESS: Email sent successfully using PHP mail() function with {$missingStocksCount} stock error(s), {$missingCryptosCount} crypto error(s), and {$missingIndexesCount} index error(s).\n";
        } else {
            echo "ERROR: Failed to send email using both PHPMailer and PHP mail() methods.\n";
        }
    }
} catch (Exception $e) {
    echo "EXCEPTION: Email sending exception occurred: " . $e->getMessage() . "\n";
    error_log("Email exception: " . $e->getMessage());
    
    // Fallback to PHP mail() function
    echo "Attempting fallback: PHP mail() function after exception...\n";
    $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
    $headers = "From: info@correctionterritory.com\r\n";
    $headers .= "Reply-To: info@correctionterritory.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    if (mail($to, $subject, $body, $headers)) {
        $emailSent = true;
        echo "SUCCESS: Email sent successfully using fallback method with {$missingStocksCount} stock error(s), {$missingCryptosCount} crypto error(s), and {$missingIndexesCount} index error(s).\n";
    } else {
        echo "ERROR: Failed to send email using fallback method after exception.\n";
    }
}

// If email sent successfully, update the email sent bit to 1
if ($emailSent) {
    echo "Updating database: Setting alert_sent = 1 for date {$today}...\n";
    $updateStmt = $con->prepare("UPDATE error_alerts SET alert_sent = 1, last_sent_time = NOW() WHERE date = ?");
    if ($updateStmt) {
        $updateStmt->bind_param("s", $today);
        if ($updateStmt->execute()) {
            if ($updateStmt->affected_rows > 0) {
                echo "SUCCESS: Email sent bit updated to 1. Affected rows: " . $updateStmt->affected_rows . "\n";
            } else {
                echo "WARNING: Update executed but no rows affected. Record may not exist for date {$today}.\n";
            }
        } else {
            echo "ERROR: Failed to execute update statement: " . $updateStmt->error . "\n";
            error_log("Failed to update email sent bit: " . $updateStmt->error);
        }
        $updateStmt->close();
    } else {
        echo "ERROR: Failed to prepare update statement: " . $con->error . "\n";
        error_log("Failed to prepare update statement: " . $con->error);
    }
} else {
    echo "WARNING: Email was not sent, so alert_sent bit will not be updated.\n";
}

echo "Closing database connection...\n";
$con->close();
echo "SUCCESS: Database connection closed.\n";

if ($emailSent) {
    echo "\n========================================\n";
    echo "FINAL STATUS: SUCCESS\n";
    echo "Email sent successfully!\n";
    echo "========================================\n";
    exit(0);
} else {
    echo "\n========================================\n";
    echo "FINAL STATUS: FAILURE\n";
    echo "Email was not sent.\n";
    echo "========================================\n";
    exit(1);
}
?>
