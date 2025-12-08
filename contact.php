<?php
/**
 * PHP Contact Form Handler
 * * NOTE: This script uses PHP's built-in mail() function. For this to work, 
 * the web server (where this PHP file is hosted) must be properly configured 
 * to send emails (e.g., using Sendmail or an SMTP service).
 * * Configuration: Replace the placeholder email address with your actual email.
 */

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// --- CONFIGURATION ---
$receiving_email = 'drinfinito24@gmail.com'; // <<< CHANGE THIS to your actual email address
$redirect_to = 'contact.html'; // File to redirect to after processing
// ---------------------

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Redirect back to the contact page if accessed directly
    header("Location: {$redirect_to}");
    exit;
}

// 1. Sanitize and Validate Input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Set status to error and an error type for user feedback
    header("Location: {$redirect_to}?status=error&error=fields");
    exit;
}

// 2. Prepare Email Content
$subject = "New Portfolio Message from {$name}";
$body = "You have received a new message from your portfolio contact form.\n\n"
      . "Name: {$name}\n"
      . "Email: {$email}\n\n"
      . "Message:\n{$message}";

// 3. Prepare Email Headers
$headers = "From: {$name} <noreply@yourdomain.com>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// 4. Send Email
if (mail($receiving_email, $subject, $body, $headers)) {
    // Success: Redirect with a success status
    header("Location: {$redirect_to}?status=success");
} else {
    // Failure: Redirect with a general error status
    header("Location: {$redirect_to}?status=error&error=send_fail");
}

exit;
?>
