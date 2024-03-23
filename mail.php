<?php
// Set the recipient email address
$to = "iannjoroge05@gmail.com";

// Set the subject of the email
$subject = "Test Email";

// Set the message body
$message = "This is a test email sent from XAMPP.";

// Set additional headers
$headers = "From: iannjoroge05@gmail.com\r\n";
$headers .= "Reply-To: sender@example.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Attempt to send the email
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}
?>
