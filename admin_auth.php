<?php
// admin_auth.php

// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect admin-only pages
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header('Location: login.php');
    exit;
}
