<?php
session_start();
require_once '../config/database.php';
require_once '../includes/MailHandler.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$success_message = '';
$error_message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['contact_id'])) {
        $contact_id = $_POST['contact_id'];
        
        switch ($_POST['action']) {
            case 'reply':
                $reply_message = $_POST['reply_message'] ?? '';
                if ($reply_message) {
                    // Add reply to message thread
                    $stmt = $pdo->prepare("INSERT INTO message_threads (message_id, sender_type, sender_id, message) VALUES (?, 'admin', ?, ?)");
                    $stmt->execute([$contact_id, $_SESSION['admin_id'], $reply_message]);
                    
                    // Update message status
                    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'in_progress' WHERE id = ?");
                    $stmt->execute([$contact_id]);
                    
                    // Get contact details
                    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
                    $stmt->execute([$contact_id]);
                    $contact = $stmt->fetch();
                    
                    // Send email to user
                    $mailHandler = new MailHandler();
                    $subject = "Re: " . $contact['subject'];
                    $email_sent = $mailHandler->sendMail(
                        $contact['email'],
                        $subject,
                        $reply_message,
                        $contact['name']
                    );
                    
                    // Log email
                    $stmt = $pdo->prepare("INSERT INTO email_logs (recipient_email, subject, message, status, error_message) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $contact['email'],
                        $subject,
                        $reply_message,
                        $email_sent ? 'sent' : 'failed',
                        $email_sent ? null : 'Failed to send email'
                    ]);
                    
                    $success_message = 'Reply sent successfully!';
                }
                break;
                
            case 'mark_resolved':
                $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'resolved' WHERE id = ?");
                $stmt->execute([$contact_id]);
                $success_message = 'Message marked as resolved!';
                break;
                
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
                $stmt->execute([$contact_id]);
                $success_message = 'Message deleted successfully!';
                break;
        }
        
        header('Location: contacts.php' . ($success_message ? '?success=' . urlencode($success_message) : ''));
        exit;
    }
}

// Get success message from URL
if (isset($_GET['success'])) {
    $success_message = $_GET['success'];
}

// Get all contacts (no pagination for simplicity)
$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$contacts = $stmt->fetchAll();

// Get message threads for each contact
foreach ($contacts as &$contact) {
    $stmt = $pdo->prepare("SELECT * FROM message_threads WHERE message_id = ? ORDER BY created_at ASC");
    $stmt->execute([$contact['id']]);
    $contact['threads'] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: white;
            padding: 2rem;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .sidebar-header img {
            width: 120px;
            margin-bottom: 1rem;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            color: #1d052f;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: linear-gradient(45deg, #f1d91e, #e40976);
            color: white;
        }

        .nav-link i {
            margin-right: 0.8rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            color: #1d052f;
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background: #fee;
            color: #e40976;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .section {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            color: #1d052f;
            font-weight: 500;
        }

        .status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .status.unread {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status.read {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .action-btn {
            padding: 0.3rem 0.8rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .action-btn.read {
            background: #e3f2fd;
            color: #1976d2;
        }

        .action-btn.delete {
            background: #ffebee;
            color: #c62828;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            background: white;
            color: #1d052f;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .pagination a:hover, .pagination a.active {
            background: linear-gradient(45deg, #f1d91e, #e40976);
            color: white;
        }

        .message-content {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .table {
                display: block;
                overflow-x: auto;
            }
        }

        .message-thread {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .thread-message {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .thread-message.admin {
            background: #e3f2fd;
        }
        
        .thread-message.user {
            background: #f5f5f5;
        }
        
        .thread-message .meta {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .reply-form {
            margin-top: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .reply-form textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #eee;
            border-radius: 10px;
            margin-bottom: 1rem;
            resize: vertical;
            min-height: 100px;
        }
        
        .status.new {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .status.in_progress {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .status.resolved {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status.closed {
            background: #f5f5f5;
            color: #616161;
        }
        
        .action-btn.resolve {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="../image/logo.png" alt="FLEXCEE Tech Logo">
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="contacts.php" class="nav-link active">
                        <i class="fas fa-envelope"></i>
                        Contacts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1 class="page-title">Contact Messages</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

            <?php if ($success_message): ?>
            <div class="message success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="message error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>

            <div class="section">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($contact['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($contact['subject'] ?? 'No Subject'); ?></td>
                            <td class="message-content"><?php echo htmlspecialchars($contact['message'] ?? ''); ?></td>
                            <td>
                                <span class="status <?php echo $contact['status'] ?? ''; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $contact['status'] ?? '')); ?>
                                </span>
                            </td>
                            <td><?php echo !empty($contact['created_at']) ? date('M d, Y', strtotime($contact['created_at'])) : ''; ?></td>
                            <td>
                                <?php if (($contact['status'] ?? '') !== 'resolved' && ($contact['status'] ?? '') !== 'closed'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="contact_id" value="<?php echo $contact['id'] ?? ''; ?>">
                                    <input type="hidden" name="action" value="mark_resolved">
                                    <button type="submit" class="action-btn resolve">
                                        <i class="fas fa-check"></i> Mark Resolved
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    <input type="hidden" name="contact_id" value="<?php echo $contact['id'] ?? ''; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div class="message-thread">
                                    <!-- Show initial visitor message -->
                                    <div class="thread-message user" style="background:#f5f5f5;">
                                        <div class="meta">
                                            <strong><?php echo htmlspecialchars($contact['name']); ?> (Visitor)</strong>
                                            - <?php echo date('M d, Y H:i', strtotime($contact['created_at'])); ?>
                                        </div>
                                        <div class="content">
                                            <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                        </div>
                                    </div>
                                    <!-- Show all replies -->
                                    <?php foreach ($contact['threads'] as $thread): ?>
                                    <div class="thread-message <?php echo $thread['sender_type']; ?>" style="background:<?php echo $thread['sender_type']==='admin' ? '#e3f2fd' : '#f5f5f5'; ?>;">
                                        <div class="meta">
                                            <strong><?php echo $thread['sender_type'] === 'admin' ? 'Admin' : htmlspecialchars($contact['name']); ?></strong>
                                            - <?php echo date('M d, Y H:i', strtotime($thread['created_at'])); ?>
                                        </div>
                                        <div class="content">
                                            <?php echo nl2br(htmlspecialchars($thread['message'])); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <!-- Restore reply form for admin -->
                                    <?php if (($contact['status'] ?? '') !== 'resolved' && ($contact['status'] ?? '') !== 'closed'): ?>
                                    <form method="POST" class="reply-form">
                                        <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                        <input type="hidden" name="action" value="reply">
                                        <textarea name="reply_message" placeholder="Type your reply..." required></textarea>
                                        <button type="submit" class="btn">
                                            <i class="fas fa-reply"></i> Send Reply
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 