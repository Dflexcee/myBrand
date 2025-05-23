<?php
require_once 'config/database.php';
require_once 'includes/MailHandler.php';

// This script should be called by your email server's pipe or webhook
// For example, with Postfix: |/usr/bin/php /path/to/process_email.php

// Read email from stdin
$email = file_get_contents('php://input');

if (empty($email)) {
    die("No email content received\n");
}

// Parse email
$parser = new \PhpMimeMailParser\Parser();
$parser->setText($email);

// Get email details
$from = $parser->getHeader('from');
$subject = $parser->getHeader('subject');
$message = $parser->getMessageBody('html') ?: $parser->getMessageBody('text');

// Extract conversation ID from subject
if (preg_match('/\[CONV:(.+?)\]/', $subject, $matches)) {
    $conversationId = $matches[1];
    // Find the original message
    $stmt = $pdo->prepare("SELECT id FROM contact_messages WHERE conversation_id = ?");
    $stmt->execute([$conversationId]);
    $messageId = $stmt->fetchColumn();
    if ($messageId) {
        // Save the reply to message_threads
        $stmt = $pdo->prepare("INSERT INTO message_threads (message_id, sender_type, message) VALUES (?, 'user', ?)");
        $stmt->execute([$messageId, $message]);
        // Update status
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'in_progress' WHERE id = ?");
        $stmt->execute([$messageId]);
        echo "Reply saved to conversation.\n";
        exit;
    }
}
echo "No matching conversation found.\n"; 