<?php
// admin_auth.php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Not logged in or not admin
    header('Location: login.php');
    exit();
}
