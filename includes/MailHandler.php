<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

class MailHandler {
    private $mailer;
    private $config;
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->loadConfigFromDb();
        $this->initializeMailer();
    }

    private function loadConfigFromDb() {
        $stmt = $this->pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE category = 'mail'");
        $stmt->execute();
        $settings = [];
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        $this->config = $settings;
    }

    private function initializeMailer() {
        try {
            $this->mailer = new PHPMailer(true);
            $this->mailer->isSMTP();
            if (!empty($this->config['use_hosting_mail']) && $this->config['use_hosting_mail'] === 'true') {
                $this->mailer->Host = $this->config['hosting_smtp_host'];
                $this->mailer->Port = $this->config['hosting_smtp_port'];
                $this->mailer->Username = $this->config['hosting_smtp_username'];
                $this->mailer->Password = $this->config['hosting_smtp_password'];
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mailer->Host = $this->config['smtp_host'];
                $this->mailer->Port = $this->config['smtp_port'];
                $this->mailer->Username = $this->config['smtp_username'];
                $this->mailer->Password = $this->config['smtp_password'];
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $this->mailer->SMTPAuth = true;
            $fromEmail = (!empty($this->config['use_hosting_mail']) && $this->config['use_hosting_mail'] === 'true') ?
                $this->config['hosting_smtp_username'] :
                $this->config['from_email'];
            $this->mailer->setFrom($fromEmail, $this->config['from_name']);
            $this->mailer->isHTML(true);
            $this->mailer->SMTPDebug = 0;
        } catch (Exception $e) {
            error_log("PHPMailer initialization failed: " . $e->getMessage());
            $this->mailer = null;
        }
    }

    public function sendMail($to, $subject, $message, $recipientName = '') {
        if (!preg_match('/\[CONV:(\d+)\]/', $subject)) {
            $conversationId = uniqid('conv_', true);
            $subject = "[CONV:{$conversationId}] " . $subject;
        }
        $replyTo = (!empty($this->config['use_hosting_mail']) && $this->config['use_hosting_mail'] === 'true') ?
            $this->config['hosting_smtp_username'] :
            $this->config['from_email'];
        $footer = "<br><br><hr style='border: 1px solid #eee; margin: 20px 0;'><p style='color: #666; font-size: 12px;'>To reply to this message, simply reply to this email. Your response will be automatically added to the conversation.</p>";
        $message .= $footer;
        try {
            if ($this->mailer) {
                $this->mailer->clearAddresses();
                $this->mailer->addAddress($to, $recipientName);
                $this->mailer->addReplyTo($replyTo);
                $this->mailer->Subject = $subject;
                $this->mailer->Body = $message;
                $result = $this->mailer->send();
                $this->logEmail($to, $subject, $message, $conversationId ?? null, 'sent');
                return $result;
            } else {
                $headers = [
                    'MIME-Version: 1.0',
                    'Content-type: text/html; charset=UTF-8',
                    'From: ' . $this->config['from_name'] . ' <' . $replyTo . '>',
                    'Reply-To: ' . $replyTo
                ];
                $result = mail($to, $subject, $message, implode("\r\n", $headers));
                $this->logEmail($to, $subject, $message, $conversationId ?? null, $result ? 'sent' : 'failed');
                return $result;
            }
        } catch (Exception $e) {
            error_log("Failed to send email: " . $e->getMessage());
            $this->logEmail($to, $subject, $message, $conversationId ?? null, 'failed', $e->getMessage());
            return false;
        }
    }
    
    private function logEmail($to, $subject, $message, $conversationId = null, $status = 'sent', $error = null) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO email_logs (recipient_email, subject, message, conversation_id, status, error_message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$to, $subject, $message, $conversationId, $status, $error]);
        } catch (Exception $e) {
            error_log("Failed to log email: " . $e->getMessage());
        }
    }
    
    public function processIncomingEmail($from, $subject, $message) {
        if (preg_match('/\[CONV:(\d+)\]/', $subject, $matches)) {
            $conversationId = $matches[1];
            $stmt = $this->pdo->prepare("SELECT cm.id FROM contact_messages cm WHERE cm.conversation_id = ?");
            $stmt->execute([$conversationId]);
            $messageId = $stmt->fetchColumn();
            if ($messageId) {
                $stmt = $this->pdo->prepare("INSERT INTO message_threads (message_id, sender_type, message) VALUES (?, 'user', ?)");
                $stmt->execute([$messageId, $message]);
                $stmt = $this->pdo->prepare("UPDATE contact_messages SET status = 'in_progress' WHERE id = ?");
                $stmt->execute([$messageId]);
                return true;
            }
        }
        return false;
    }
} 