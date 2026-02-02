<?php
// admin_profile.php
session_start();
require_once 'admin_auth.php'; // ensures only admins
require_once 'db.php';

$adminId = $_SESSION['user_id'] ?? null;

if (!$adminId) {
    header('Location: login.php');
    exit;
}

$success = '';
$error   = '';

// Fetch current admin data
try {
    $stmt = $pdo->prepare("SELECT full_name, email, profile_image FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $error = "Admin account not found.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $fullName       = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $newPassword    = trim($_POST['new_password'] ?? '');
    $confirmPassword= trim($_POST['confirm_password'] ?? '');
    $currentImage   = $admin['profile_image'] ?? null;
    $newImagePath   = null;

    // Basic validation
    if ($fullName === '' || $email === '') {
        $error = "Full name and email are required.";
    }

    // Password validation (optional)
    $passwordHash = null;
    if (!$error && $newPassword !== '') {
        if ($newPassword !== $confirmPassword) {
            $error = "New password and confirm password do not match.";
        } elseif (strlen($newPassword) < 6) {
            $error = "Password should be at least 6 characters.";
        } else {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    // Handle profile image upload
    if (!$error && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file      = $_FILES['profile_image'];
        $allowed   = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize   = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = "Error uploading image.";
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowed)) {
            $error = "Only JPG, PNG, GIF or WEBP images are allowed.";
        } elseif ($file['size'] > $maxSize) {
            $error = "Image size should not exceed 2MB.";
        } else {
            // Ensure upload directory exists
            $uploadDir = __DIR__ . '/uploads/profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName   = 'admin_' . $adminId . '_' . time() . '.' . $ext;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Save relative path for HTML
                $newImagePath = 'uploads/profiles/' . $fileName;
            } else {
                $error = "Failed to save uploaded image.";
            }
        }
    }

    // If no validation errors, update DB
    if (!$error) {
        try {
            $fields = [
                'full_name = :full_name',
                'email = :email'
            ];
            $params = [
                ':full_name' => $fullName,
                ':email'     => $email,
                ':id'        => $adminId
            ];

            if ($passwordHash) {
                $fields[]              = 'password_hash = :password_hash';
                $params[':password_hash'] = $passwordHash;
            }

            if ($newImagePath) {
                $fields[]                   = 'profile_image = :profile_image';
                $params[':profile_image']   = $newImagePath;
                $admin['profile_image']     = $newImagePath;
            }

            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sql);
            $stmtUpdate->execute($params);

            // Update local/admin data for re-rendering
            $admin['full_name'] = $fullName;
            $admin['email']     = $email;

            // Update session name
            $_SESSION['full_name'] = $fullName;

            $success = "Profile updated successfully.";
        } catch (PDOException $e) {
            $error = "Failed to update profile: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | TravelEase Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fffbeb',
              100: '#fef3c7',
              200: '#fde68a',
              300: '#fcd34d',
              400: '#fbbf24',
              500: '#f59e0b',
              600: '#d97706',
              700: '#b45309',
              800: '#92400e',
              900: '#78350f',
            }
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gray-100">
  <!-- Top Navbar (simple) -->
  <header class="bg-white shadow-sm">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <div class="flex items-center space-x-3">
        <img src="img/Logo.png" alt="TravelEase Logo" class="h-9 w-9 object-contain">
        <div>
          <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-gray-800">TravelEase</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
              Admin Profile
            </span>
          </div>
          <p class="text-xs text-gray-500">Manage your admin account details.</p>
        </div>
      </div>
      <a href="admin_dashboard.php"
         class="text-sm font-medium text-gray-700 hover:text-primary-700">
        ← Back to Dashboard
      </a>
    </div>
  </header>

  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">My Profile</h1>
      <p class="text-sm text-gray-500 mb-6">
        Update your personal details, change password and manage your profile image.
      </p>

      <!-- Alerts -->
      <?php if ($error): ?>
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
        <!-- Profile image -->
        <div class="flex items-center space-x-4">
          <?php if (!empty($admin['profile_image'])): ?>
            <img src="<?= htmlspecialchars($admin['profile_image']) ?>"
                 alt="Profile image"
                 class="h-16 w-16 rounded-full object-cover border border-primary-200">
          <?php else: ?>
            <div class="h-16 w-16 rounded-full bg-primary-100 flex items-center justify-center text-lg font-semibold text-primary-700">
              <?= strtoupper(substr($admin['full_name'] ?? 'A', 0, 1)) ?>
            </div>
          <?php endif; ?>

          <div>
            <p class="text-sm font-medium text-gray-800">Profile Image</p>
            <p class="text-xs text-gray-500 mb-2">Upload a JPG, PNG, GIF or WEBP image (max 2MB).</p>
            <input type="file" name="profile_image" accept="image/*"
                   class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- Full name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="full_name">Full Name</label>
            <input
              type="text"
              id="full_name"
              name="full_name"
              value="<?= htmlspecialchars($admin['full_name'] ?? '') ?>"
              class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm"
              required
            >
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($admin['email'] ?? '') ?>"
              class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm"
              required
            >
          </div>
        </div>

        <hr class="my-4">

        <!-- Password change -->
        <div>
          <p class="text-sm font-medium text-gray-800 mb-1">Change Password</p>
          <p class="text-xs text-gray-500 mb-3">
            Leave these fields empty if you do not want to change your password.
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1" for="new_password">New Password</label>
              <input
                type="password"
                id="new_password"
                name="new_password"
                class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1" for="confirm_password">Confirm Password</label>
              <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm"
              >
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
          <a href="admin_dashboard.php"
             class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
            Cancel
          </a>
          <button
            type="submit"
            class="px-5 py-2 rounded-xl bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600 shadow-sm">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
