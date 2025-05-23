<?php
require_once 'config/database.php';
require_once 'includes/MailHandler.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate and sanitize input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

if (!$name || !$email || !$message || !$service) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

try {
    // Save to database (use service as subject)
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $email, $phone, $service, $message]);
    $messageId = $pdo->lastInsertId();

    $mailHandler = new MailHandler();
    $adminSubject = "New Contact Form Submission from $name";
    $adminMessage = "
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Service:</strong> $service</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
        <p>Message ID: $messageId</p>
    ";
    $adminMailSent = $mailHandler->sendMail('admin@flexceetech.world', $adminSubject, $adminMessage);

    // Auto-reply to user
    $userSubject = "Thank you for contacting FLEXCEE Tech";
    $userMessage = "
        <h2>Thank you for contacting us!</h2>
        <p>Dear $name,</p>
        <p>We have received your message and will get back to you shortly.</p>
        <p>Here's a summary of your message:</p>
        <p><strong>Service:</strong> $service</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
        <p>Best regards,<br>FLEXCEE Tech Team</p>
    ";
    $userMailSent = $mailHandler->sendMail($email, $userSubject, $userMessage);

    if ($adminMailSent && $userMailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message. We have received it and will get back to you soon!'
        ]);
    } else if (!$adminMailSent && !$userMailSent) {
        error_log("Contact Form Error: Both admin and user email failed.");
        echo json_encode([
            'success' => true,
            'message' => 'Your message was received, but we could not send confirmation emails. We will contact you soon.'
        ]);
    } else if (!$adminMailSent) {
        error_log("Contact Form Error: Admin email failed.");
        echo json_encode([
            'success' => true,
            'message' => 'Your message was received, but we could not notify the admin by email. We will contact you soon.'
        ]);
    } else if (!$userMailSent) {
        error_log("Contact Form Error: User auto-reply email failed.");
        echo json_encode([
            'success' => true,
            'message' => 'Your message was received, but we could not send a confirmation email to you. We will contact you soon.'
        ]);
    }

} catch (Exception $e) {
    error_log("Contact Form Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error sending your message. Please try again later.'
    ]);
} 