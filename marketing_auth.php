<?php
// marketing_auth.php
session_start();

// If not logged in at all
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Allowed roles for this panel
$role = $_SESSION['role'] ?? 'user';

// Allow marketing roles + admin (admin can also view this panel)
if (!in_array($role, ['marketing', 'marketing_manager', 'admin'], true)) {
    // Option 1: send them to normal user dashboard
    header('Location: user_dashboard.php');
    exit;

    // Option 2: or show forbidden (if you prefer)
    // header("HTTP/1.1 403 Forbidden");
    // echo "Access denied.";
    // exit;
}
