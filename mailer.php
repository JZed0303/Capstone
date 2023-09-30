<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__."/vendor/autoload.php";

$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Set SMTP debug mode
$mail->Debugoutput = 'error_log'; // Log errors to the PHP error log

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.example.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Username = "johnzedrickiglesia@gmail.com";
    $mail->Password = "mastercj030302"; // Replace with the actual email password

   
    // Email content
    $mail->isHTML(true);
   
    return $mail;

    
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>
