<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Optional: also kill session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to guest dashboard or login
header('Location: guest_dashboard.php');
exit;
