<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get statistics
$stats = [
    'total_contacts' => $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn(),
    'unread_contacts' => $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn(),
    'total_emails' => $pdo->query("SELECT COUNT(*) FROM email_logs")->fetchColumn(),
    'failed_emails' => $pdo->query("SELECT COUNT(*) FROM email_logs WHERE status = 'failed'")->fetchColumn()
];

// Get recent contacts
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
$recent_contacts = $stmt->fetchAll();

// Get recent email logs
$stmt = $pdo->query("SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 5");
$recent_emails = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FLEXCEE Tech</title>
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

        .welcome-text {
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .stat-card h3 {
            color: #1d052f;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 600;
            background: linear-gradient(45deg, #f1d91e, #e40976);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-title {
            color: #1d052f;
            font-size: 1.2rem;
        }

        .view-all {
            color: #e40976;
            text-decoration: none;
            font-size: 0.9rem;
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

        .status.failed {
            background: #ffebee;
            color: #c62828;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }
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
                    <a href="index.php" class="nav-link active">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="contacts.php" class="nav-link">
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
                <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Contacts</h3>
                    <div class="number"><?php echo $stats['total_contacts']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Unread Messages</h3>
                    <div class="number"><?php echo $stats['unread_contacts']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Emails Sent</h3>
                    <div class="number"><?php echo $stats['total_emails']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Failed Emails</h3>
                    <div class="number"><?php echo $stats['failed_emails']; ?></div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Recent Contacts</h2>
                    <a href="contacts.php" class="view-all">View All</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_contacts as $contact): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contact['name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($contact['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($contact['subject'] ?? 'No Subject'); ?></td>
                            <td>
                                <span class="status <?php echo $contact['status'] ?? ''; ?>">
                                    <?php echo ucfirst($contact['status'] ?? ''); ?>
                                </span>
                            </td>
                            <td><?php echo !empty($contact['created_at']) ? date('M d, Y', strtotime($contact['created_at'])) : ''; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Recent Email Logs</h2>
                    <a href="email_logs.php" class="view-all">View All</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_emails as $email): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($email['recipient_email']); ?></td>
                            <td><?php echo htmlspecialchars($email['subject']); ?></td>
                            <td>
                                <span class="status <?php echo $email['status']; ?>">
                                    <?php echo ucfirst($email['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($email['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 