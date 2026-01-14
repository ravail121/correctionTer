<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../smtp/PHPMailerAutoload.php';

try {
    $mail = new PHPMailer;
    
    // Try using mail() function first (simpler for cPanel)
    $mail->isMail();
    
    // Alternative: Try SMTP with different settings
    // Uncomment these if isMail() doesn't work:
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
    $mail->addAddress('rvlirshad@gmail.com', 'Ravi Irshad');
    
    $mail->Subject = 'Test Email - ' . date('Y-m-d H:i:s');
    $mail->Body = "This is a test email to verify email functionality is working.\n\n";
    $mail->Body .= "Sent at: " . date('Y-m-d H:i:s') . "\n";
    $mail->Body .= "If you receive this, email is working correctly!";
    $mail->isHTML(false);
    
    if ($mail->send()) {
        echo "SUCCESS: Test email sent successfully to rvlirshad@gmail.com<br>";
        echo "Method used: mail() function<br>";
    } else {
        echo "ERROR: Email send failed: " . $mail->ErrorInfo . "<br>";
        echo "<br>Trying alternative method...<br>";
        
        // Try alternative: Use PHP mail() directly
        $to = 'rvlirshad@gmail.com';
        $subject = 'Test Email (Direct) - ' . date('Y-m-d H:i:s');
        $message = "This is a test email to verify email functionality is working.\n\n";
        $message .= "Sent at: " . date('Y-m-d H:i:s') . "\n";
        $message .= "If you receive this, email is working correctly!";
        $headers = "From: info@correctionterritory.com\r\n";
        $headers .= "Reply-To: info@correctionterritory.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            echo "SUCCESS: Test email sent using PHP mail() function directly!<br>";
        } else {
            echo "ERROR: Both methods failed. Please check your server's mail configuration.<br>";
        }
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString();
}
?>

