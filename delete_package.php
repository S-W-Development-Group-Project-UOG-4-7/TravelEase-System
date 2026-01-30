<?php
// delete_package.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['marketing_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database connection
require_once __DIR__ . '/db.php';

// Get package ID from URL (GET method) OR from POST
$id = $_GET['id'] ?? $_POST['id'] ?? 0;

// Check if ID is valid
if ($id) {
    try {
        // Get package name before deleting for message
        $stmt = $pdo->prepare("SELECT package_name FROM travel_packages WHERE id = ?");
        $stmt->execute([$id]);
        $package = $stmt->fetch();
        
        if ($package) {
            // Delete the package
            $deleteStmt = $pdo->prepare("DELETE FROM travel_packages WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            // Check if delete was successful
            if ($deleteStmt->rowCount() > 0) {
                $_SESSION['delete_message'] = 'Package "' . htmlspecialchars($package['package_name']) . '" deleted successfully!';
            } else {
                $_SESSION['delete_error'] = 'Failed to delete package. Package may not exist.';
            }
        } else {
            $_SESSION['delete_error'] = 'Package not found.';
        }
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        $_SESSION['delete_error'] = 'Failed to delete package. Please try again.';
    }
} else {
    $_SESSION['delete_error'] = 'No package ID provided.';
}

// Redirect back to packages page
header('Location: marketing_campaigns.php');
exit();
?>