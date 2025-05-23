<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$success_message = '';
$error_message = '';

// Helper functions for settings
function get_mail_settings($pdo) {
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE category = 'mail'");
    $stmt->execute();
    $settings = [];
    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}
function update_mail_settings($pdo, $data) {
    foreach ($data as $key => $value) {
        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ? AND category = 'mail'");
        $stmt->execute([$value, $key]);
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $full_name = $_POST['full_name'] ?? '';
                $username = $_POST['username'] ?? '';
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                
                // Verify current password
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['admin_id']]);
                $user = $stmt->fetch();
                
                if ($user && $user['password'] === $current_password) {
                    // Update profile
                    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ? WHERE id = ?");
                    $stmt->execute([$full_name, $username, $_SESSION['admin_id']]);
                    
                    // Update password if provided
                    if ($new_password) {
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$new_password, $_SESSION['admin_id']]);
                    }
                    
                    $_SESSION['admin_name'] = $full_name;
                    $success_message = 'Profile updated successfully!';
                } else {
                    $error_message = 'Current password is incorrect!';
                }
                break;
                
            case 'update_email':
                $mail_data = [
                    'smtp_host' => $_POST['smtp_host'] ?? '',
                    'smtp_port' => $_POST['smtp_port'] ?? '',
                    'smtp_username' => $_POST['smtp_username'] ?? '',
                    'smtp_password' => $_POST['smtp_password'] ?? '',
                    'from_email' => $_POST['from_email'] ?? '',
                    'from_name' => $_POST['from_name'] ?? '',
                    'use_hosting_mail' => isset($_POST['use_hosting_mail']) ? 'true' : 'false',
                    'hosting_smtp_host' => $_POST['hosting_smtp_host'] ?? '',
                    'hosting_smtp_port' => $_POST['hosting_smtp_port'] ?? '',
                    'hosting_smtp_username' => $_POST['hosting_smtp_username'] ?? '',
                    'hosting_smtp_password' => $_POST['hosting_smtp_password'] ?? '',
                ];
                update_mail_settings($pdo, $mail_data);
                $success_message = 'Email settings updated successfully!';
                break;
        }
    }
}

// Get current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$user = $stmt->fetch();

// Get current email settings from DB
$mail_config = get_mail_settings($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Dashboard</title>
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
            margin-bottom: 1.5rem;
        }

        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #1d052f;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1d052f;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #e40976;
            outline: none;
            box-shadow: 0 0 0 3px rgba(228, 9, 118, 0.1);
        }

        .btn {
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #f1d91e, #e40976);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(228, 9, 118, 0.2);
        }

        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .message.success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .message.error {
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
                    <a href="contacts.php" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        Contacts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link active">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1 class="page-title">Settings</h1>
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
                <div class="section-header">
                    <h2 class="section-title">Profile Settings</h2>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password (leave blank to keep current)</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Email Settings</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="update_email">
                    <label style="display:block;margin-bottom:10px;">
                        <input type="checkbox" name="use_hosting_mail" id="use_hosting_mail" value="1" <?php if (!empty($mail_config['use_hosting_mail'])) echo 'checked'; ?>>
                        Use Hosting SMTP (e.g. WhoGoHost)
                    </label>
                    <div id="gmail-settings" style="display:<?php echo (!empty($mail_config['use_hosting_mail'])) ? 'none' : 'block'; ?>;">
                        <h4>Gmail SMTP Settings</h4>
                        <label>SMTP Host: <input type="text" name="smtp_host" value="<?php echo htmlspecialchars($mail_config['smtp_host'] ?? ''); ?>" required></label><br>
                        <label>SMTP Port: <input type="number" name="smtp_port" value="<?php echo htmlspecialchars($mail_config['smtp_port'] ?? ''); ?>" required></label><br>
                        <label>SMTP Username: <input type="text" name="smtp_username" value="<?php echo htmlspecialchars($mail_config['smtp_username'] ?? ''); ?>" required></label><br>
                        <label>SMTP Password: <input type="password" name="smtp_password" value="<?php echo htmlspecialchars($mail_config['smtp_password'] ?? ''); ?>" required></label><br>
                        <label>From Email: <input type="email" name="from_email" value="<?php echo htmlspecialchars($mail_config['from_email'] ?? ''); ?>" required></label><br>
                        <label>From Name: <input type="text" name="from_name" value="<?php echo htmlspecialchars($mail_config['from_name'] ?? ''); ?>" required></label><br>
                    </div>
                    <div id="hosting-settings" style="display:<?php echo (!empty($mail_config['use_hosting_mail'])) ? 'block' : 'none'; ?>;">
                        <h4>Hosting SMTP Settings</h4>
                        <label>SMTP Host: <input type="text" name="hosting_smtp_host" value="<?php echo htmlspecialchars($mail_config['hosting_smtp_host'] ?? ''); ?>"></label><br>
                        <label>SMTP Port: <input type="number" name="hosting_smtp_port" value="<?php echo htmlspecialchars($mail_config['hosting_smtp_port'] ?? ''); ?>"></label><br>
                        <label>SMTP Username: <input type="text" name="hosting_smtp_username" value="<?php echo htmlspecialchars($mail_config['hosting_smtp_username'] ?? ''); ?>"></label><br>
                        <label>SMTP Password: <input type="password" name="hosting_smtp_password" value="<?php echo htmlspecialchars($mail_config['hosting_smtp_password'] ?? ''); ?>"></label><br>
                    </div>
                    <button type="submit" class="btn" style="margin-top:10px;">Update Email Settings</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    // Toggle SMTP settings
    const useHostingMail = document.getElementById('use_hosting_mail');
    const gmailSettings = document.getElementById('gmail-settings');
    const hostingSettings = document.getElementById('hosting-settings');
    if (useHostingMail) {
        useHostingMail.addEventListener('change', function() {
            if (this.checked) {
                gmailSettings.style.display = 'none';
                hostingSettings.style.display = 'block';
            } else {
                gmailSettings.style.display = 'block';
                hostingSettings.style.display = 'none';
            }
        });
    }
    </script>
</body>
</html> 