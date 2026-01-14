<?php
/**
 * Check for companies where current price is higher than all-time high
 * and send email notification with the list
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../smtp/PHPMailerAutoload.php';

// Get database connection
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all companies from database
$query = "SELECT symbl, name, last_close, all_time_high FROM companies WHERE last_close > 0 AND all_time_high > 0";
$result = $con->query($query);

if (!$result) {
    die("Error fetching companies: " . $con->error);
}

// Array to store companies where current price > all-time high
$athBreakers = [];

// Check each company
while ($row = $result->fetch_assoc()) {
    $symbol = $row['symbl'];
    $name = $row['name'];
    $currentPrice = floatval($row['last_close']);
    $allTimeHigh = floatval($row['all_time_high']);
    
    // Check if current price is higher than all-time high
    if ($currentPrice > $allTimeHigh) {
        $athBreakers[] = [
            'symbol' => $symbol,
            'name' => $name,
            'current_price' => $currentPrice,
            'all_time_high' => $allTimeHigh,
            'difference' => $currentPrice - $allTimeHigh,
            'difference_percent' => (($currentPrice - $allTimeHigh) / $allTimeHigh) * 100
        ];
    }
}

// Close database connection
$con->close();

// Send email if there are any ATH breakers
if (count($athBreakers) > 0) {
    $date = date('Y-m-d');
    $subject = "Illogical Values on the Website {$date}";
    
    $body = "The following " . count($athBreakers) . " symbol(s) have current price higher than their all-time high:\n\n";
    $body .= str_repeat("=", 80) . "\n\n";
    
    foreach ($athBreakers as $company) {
        $body .= sprintf(
            "Symbol: %s\nName: %s\nCurrent Price: $%.2f\nAll-Time High: $%.2f\nDifference: $%.2f (%.2f%%)\n",
            $company['symbol'],
            $company['name'],
            $company['current_price'],
            $company['all_time_high'],
            $company['difference'],
            $company['difference_percent']
        );
        $body .= str_repeat("-", 80) . "\n\n";
    }
    
    $body .= "\nGenerated at: " . date('Y-m-d H:i:s') . "\n";
    
    // Send email using PHPMailer
    try {
        $mail = new PHPMailer;
        
        // Use mail() function (works better with cPanel)
        $mail->isMail();
        
        $mail->setFrom('info@correctionterritory.com', 'Correction Territory');
        $mail->addAddress('chen.ben.ari@gmail.com', 'Chen Ben Ari');
        $mail->addAddress('rvlirshad@gmail.com', 'Ravail Irshad');
        
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);
        
        if ($mail->send()) {
            echo "SUCCESS: Email sent successfully with " . count($athBreakers) . " ATH breaker(s).\n";
        } else {
            error_log("PHPMailer send failed: " . $mail->ErrorInfo);
            
            // Fallback to PHP mail() function
            $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
            $headers = "From: info@correctionterritory.com\r\n";
            $headers .= "Reply-To: info@correctionterritory.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            if (mail($to, $subject, $body, $headers)) {
                echo "SUCCESS: Email sent successfully using PHP mail() function with " . count($athBreakers) . " ATH breaker(s).\n";
            } else {
                echo "ERROR: Failed to send email.\n";
            }
        }
    } catch (Exception $e) {
        error_log("Email exception: " . $e->getMessage());
        
        // Fallback to PHP mail() function
        $to = 'chen.ben.ari@gmail.com, rvlirshad@gmail.com';
        $headers = "From: info@correctionterritory.com\r\n";
        $headers .= "Reply-To: info@correctionterritory.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $body, $headers)) {
            echo "SUCCESS: Email sent successfully using fallback method with " . count($athBreakers) . " ATH breaker(s).\n";
        } else {
            echo "ERROR: Failed to send email.\n";
        }
    }
} else {
    echo "No companies found with current price higher than all-time high.\n";
}

?>

