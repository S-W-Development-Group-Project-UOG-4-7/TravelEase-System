<?php
session_start();
date_default_timezone_set('Asia/Colombo'); // Use your local timezone
require_once 'db.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId   = $_SESSION['user_id'];
$fullName = '';
$email    = '';
$phone    = '';
$created  = '';
$role     = '';
$profileImage = '';
$success  = '';
$error    = '';
$lastLoginDisplay = 'Not available';

// --------- Handle DELETE ACCOUNT ---------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    try {
        // Optional: delete profile image file too
        $stmtImg = $pdo->prepare("SELECT profile_image FROM users WHERE id = :id");
        $stmtImg->execute([':id' => $userId]);
        $imgRow = $stmtImg->fetch(PDO::FETCH_ASSOC);
        if ($imgRow && !empty($imgRow['profile_image'])) {
            $filePath = __DIR__ . '/uploads/profile_pics/' . $imgRow['profile_image'];
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        // Delete user row
        $delStmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $delStmt->execute([':id' => $userId]);

        // Destroy session and redirect to guest or home
        session_unset();
        session_destroy();
        header("Location: guest_dashboard.php"); // or login.php
        exit();
    } catch (PDOException $e) {
        $error = "Error deleting account: " . $e->getMessage();
    }
}

// --------- Fetch current user data ---------
try {
    $stmt = $pdo->prepare("
        SELECT full_name, email, phone, created_at, role, profile_image, last_login
        FROM users
        WHERE id = :id
    ");
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $fullName     = $user['full_name'];
        $email        = $user['email'];
        $phone        = $user['phone'] ?? '';
        $created      = $user['created_at'];
        $role         = $user['role'];
        $profileImage = $user['profile_image'] ?? '';
        $lastLoginRaw = $user['last_login'] ?? null;

        // Format last login for display (from DB value)
        if (!empty($lastLoginRaw)) {
            $ts = strtotime($lastLoginRaw);
            if ($ts !== false) {
                $lastLoginDisplay = date('Y-m-d h:i A', $ts);
            } else {
                // Fallback: show raw if parsing failed
                $lastLoginDisplay = $lastLoginRaw;
            }
        }
    } else {
        $error = "User not found.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Build profile image URL
if (!empty($profileImage)) {
    $profileImageUrl = "uploads/profile_pics/" . $profileImage;
} else {
    // Fallback default avatar (create this file or change the path)
    $profileImageUrl = "img/default-avatar.png";
}

// --------- Handle UPDATE PROFILE ---------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $newFullName   = trim($_POST['full_name'] ?? '');
    $newEmail      = trim($_POST['email'] ?? '');
    $newPhone      = trim($_POST['phone'] ?? '');
    $newPassword   = $_POST['new_password'] ?? '';
    $confirmPass   = $_POST['confirm_password'] ?? '';

    // Basic validation
    if ($newFullName === '' || $newEmail === '') {
        $error = "Full name and email are required.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($newPassword !== '' && $newPassword !== $confirmPass) {
        $error = "New passwords do not match.";
    } else {
        // Handle profile image upload (if any)
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $tmpName      = $_FILES['profile_image']['tmp_name'];
            $originalName = basename($_FILES['profile_image']['name']);
            $ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed      = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $allowed, true)) {
                $newFilename = "user_" . $userId . "_" . time() . "." . $ext;
                $uploadDir   = __DIR__ . "/uploads/profile_pics";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }

                if (move_uploaded_file($tmpName, $uploadDir . "/" . $newFilename)) {
                    // Optional: delete old profile image file
                    if (!empty($profileImage)) {
                        $oldPath = $uploadDir . "/" . $profileImage;
                        if (is_file($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    $profileImage = $newFilename;
                    $profileImageUrl = "uploads/profile_pics/" . $profileImage;
                } else {
                    $error = "Failed to upload profile image.";
                }
            } else {
                $error = "Invalid image type. Please upload JPG, PNG, GIF, or WEBP.";
            }
        }

        if ($error === '') {
            try {
                // Check if email already used by another user
                $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id <> :id");
                $checkStmt->execute([
                    ':email' => $newEmail,
                    ':id'    => $userId
                ]);

                if ($checkStmt->fetch()) {
                    $error = "This email is already registered.";
                } else {
                    // Build update query
                    if ($newPassword !== '') {
                        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updateSql = "
                            UPDATE users
                            SET full_name     = :full_name,
                                email         = :email,
                                phone         = :phone,
                                profile_image = :profile_image,
                                password_hash = :password_hash
                            WHERE id = :id
                        ";
                        $params = [
                            ':full_name'     => $newFullName,
                            ':email'         => $newEmail,
                            ':phone'         => $newPhone,
                            ':profile_image' => $profileImage,
                            ':password_hash' => $passwordHash,
                            ':id'            => $userId
                        ];
                    } else {
                        $updateSql = "
                            UPDATE users
                            SET full_name     = :full_name,
                                email         = :email,
                                phone         = :phone,
                                profile_image = :profile_image
                            WHERE id = :id
                        ";
                        $params = [
                            ':full_name'     => $newFullName,
                            ':email'         => $newEmail,
                            ':phone'         => $newPhone,
                            ':profile_image' => $profileImage,
                            ':id'            => $userId
                        ];
                    }

                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute($params);

                    // Update session name so dashboard greeting changes
                    $_SESSION['full_name'] = $newFullName;

                    $success   = "Profile updated successfully.";
                    $fullName  = $newFullName;
                    $email     = $newEmail;
                    $phone     = $newPhone;
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50">
  <div class="max-w-3xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
      <a href="user_dashboard.php"
         class="text-sm px-3 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700">
        ⬅ Back to Dashboard
      </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 space-y-6">

      <!-- Alerts -->
      <?php if ($success): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
          <?= htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
          <?= htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <!-- Profile Update Form -->
      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="action" value="update">

        <!-- Profile picture -->
        <div class="flex items-center gap-4">
          <img
            src="<?= htmlspecialchars($profileImageUrl); ?>"
            alt="Profile Picture"
            class="w-20 h-20 rounded-full object-cover border border-slate-200"
          >
          <div>
            <p class="text-sm font-medium text-slate-700 mb-1">Profile Picture</p>
            <p class="text-xs text-slate-500 mb-2">
              Upload a JPG, PNG, GIF, or WEBP image.
            </p>
            <input
              type="file"
              name="profile_image"
              accept="image/*"
              class="text-xs"
            >
          </div>
        </div>

        <!-- Full Name -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
          <input
            type="text"
            name="full_name"
            value="<?= htmlspecialchars($fullName); ?>"
            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
            required
          >
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($email); ?>"
            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
            required
          >
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
          <input
            type="text"
            name="phone"
            value="<?= htmlspecialchars($phone); ?>"
            class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
          >
        </div>

        <hr class="border-slate-200">

        <!-- Account Info (Read-only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Account Created</label>
            <input
              type="text"
              value="<?= htmlspecialchars($created); ?>"
              class="w-full rounded-xl border border-slate-200 px-3 py-2 bg-slate-100 text-slate-500 text-sm"
              readonly
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
            <input
              type="text"
              value="<?= htmlspecialchars(ucfirst($role)); ?>"
              class="w-full rounded-xl border border-slate-200 px-3 py-2 bg-slate-100 text-slate-500 text-sm"
              readonly
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Last Login</label>
            <input
              type="text"
              value="<?= htmlspecialchars($lastLoginDisplay); ?>"
              class="w-full rounded-xl border border-slate-200 px-3 py-2 bg-slate-100 text-slate-500 text-sm"
              readonly
            >
          </div>
        </div>

        <hr class="border-slate-200">

        <!-- Password Section -->
        <div>
          <h2 class="text-sm font-semibold text-slate-800 mb-2">
            Change Password <span class="font-normal text-slate-500">(optional)</span>
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
              <input
                type="password"
                name="new_password"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                minlength="8"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
              <input
                type="password"
                name="confirm_password"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"
                minlength="8"
              >
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="pt-2">
          <button
            type="submit"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-amber-500 hover:bg-amber-600 text-white shadow">
            Save Changes
          </button>
        </div>
      </form>

      <hr class="border-slate-200">

      <!-- Delete Account -->
      <div class="pt-2">
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
          <input type="hidden" name="action" value="delete">
          <button
            type="submit"
            class="px-4 py-2 rounded-xl text-sm font-semibold bg-red-500 hover:bg-red-600 text-white shadow">
            Delete My Account
          </button>
        </form>
        <p class="mt-2 text-xs text-slate-500">
          Deleting your account is permanent and cannot be undone.
        </p>
      </div>

    </div>
  </div>
</body>
</html>
