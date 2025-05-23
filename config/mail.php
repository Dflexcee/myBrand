<?php
return [
    // SMTP Settings
    'smtp_host' => 'smtp.gmail.com',  // For Gmail
    'smtp_port' => 587,
    'smtp_username' => 'your-email@gmail.com', // Replace with your Gmail
    'smtp_password' => 'your-app-password',    // Replace with your Gmail App Password
    
    // Email Settings
    'from_email' => 'your-email@gmail.com',    // Same as smtp_username
    'from_name' => 'FLEXCEE Tech',
    
    // For hosting company (WhoGoHost) settings
    'use_hosting_mail' => false,  // Set to true when on hosting
    'hosting_smtp_host' => 'mail.yourdomain.com',  // Replace with your hosting SMTP
    'hosting_smtp_port' => 465,
    'hosting_smtp_username' => 'contact@yourdomain.com',  // Your hosting email
    'hosting_smtp_password' => 'your-hosting-email-password'  // Your hosting email password
]; 