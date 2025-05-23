<?php
require_once 'config/database.php';
require_once 'includes/MailHandler.php';

// Set environment
define('ENVIRONMENT', 'development');

try {
    // Initialize MailHandler
    $mailHandler = new MailHandler();
    
    // Test email
    $to = 'test@example.com'; // Replace with your test email
    $subject = 'Test Email from FLEXCEE Tech';
    $message = "
        <h2>Test Email</h2>
        <p>This is a test email to verify the email system is working correctly.</p>
        <p>If you receive this email, please reply to test the conversation threading.</p>
    ";
    
    // Send test email
    $result = $mailHandler->sendMail($to, $subject, $message, 'Test User');
    
    if ($result) {
        echo "Test email sent successfully!<br>";
        echo "Please check your email and reply to test the conversation threading.";
    } else {
        echo "Failed to send test email. Please check the error logs.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} 