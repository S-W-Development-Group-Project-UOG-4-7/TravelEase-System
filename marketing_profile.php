<?php
// marketing_profile.php
require_once 'marketing_auth.php';
require_once 'db.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: login.php');
    exit;
}

$success = '';
$error   = '';

// Fetch current user
try {
    $stmt = $pdo->prepare("SELECT full_name, email, profile_image FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {

    $fullName       = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $newPassword    = trim($_POST['new_password'] ?? '');
    $confirmPassword= trim($_POST['confirm_password'] ?? '');

    // Validation
    if ($fullName === '' || $email === '') {
        $error = "Name and email are required.";
    }

    // Handle image upload if provided
    $profileImageSql  = "";
    $profileImageFile = $user['profile_image']; // keep existing by default

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext     = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Only JPG, JPEG, PNG files are allowed.";
        } else {
            // Generate new file name
            $newFilename = "user_" . $userId . "." . $ext;
            $uploadDir   = "uploads/profile/";
            $uploadPath  = $uploadDir . $newFilename;

            // Create folder if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Remove old image
            if (!empty($user['profile_image']) && file_exists($uploadDir . $user['profile_image'])) {
                @unlink($uploadDir . $user['profile_image']);
            }

            // Upload new image
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                $profileImageFile = $newFilename;
                $profileImageSql  = ", profile_image = :profile_image";
            } else {
                $error = "Failed to upload profile picture.";
            }
        }
    }

    // Handle password update (optional)
    $passwordSql = "";
    if ($newPassword !== '') {
        if (strlen($newPassword) < 6) {
            $error = "Password must be at least 6 characters.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match.";
        } else {
            $passwordSql = ", password_hash = :password_hash";
        }
    }

    if ($error === '') {
        try {
            $params = [
                ':full_name' => $fullName,
                ':email'     => $email,
                ':id'        => $userId
            ];

            if ($passwordSql !== "") {
                $params[':password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            if ($profileImageSql !== "") {
                $params[':profile_image'] = $profileImageFile;
            }

            // Update user info
            $sql = "
                UPDATE users 
                SET full_name = :full_name,
                    email     = :email
                    $passwordSql
                    $profileImageSql
                WHERE id = :id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Update session data
            $_SESSION['full_name'] = $fullName;
            if (!empty($profileImageFile)) {
                $_SESSION['profile_image'] = $profileImageFile;
            }

            // Refresh local user array for display
            $user['full_name']      = $fullName;
            $user['email']          = $email;
            $user['profile_image']  = $profileImageFile;

            $success = "Profile updated successfully.";

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$fullNameValue = htmlspecialchars($user['full_name'] ?? '', ENT_QUOTES, 'UTF-8');
$emailValue    = htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8');
$profileImage  = !empty($user['profile_image'])
    ? "uploads/profile/" . $user['profile_image']
    : "img/default_user.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Marketing Profile | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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

<body class="bg-gray-100 min-h-screen">
    
<header class="bg-white shadow-sm">
  <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
    <div class="flex items-center space-x-3">
      <img src="img/Logo.png" class="h-9" alt="TravelEase Logo">
      <span class="text-lg font-bold text-gray-800">Marketing Profile</span>
    </div>
    <a href="marketing_dashboard.php"
       class="text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 border px-3 py-1.5 rounded-lg">
      ← Back to Dashboard
    </a>
  </div>
</header>

<main class="max-w-5xl mx-auto px-4 py-8">

  <h1 class="text-2xl font-bold mb-4 text-gray-800">My Profile Settings</h1>

  <?php if ($success): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded mb-4 text-sm">
      <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4 text-sm">
      <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded-xl shadow-sm space-y-5">

    <!-- Profile Picture -->
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-700">Profile Picture</label>
      <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>"
           class="h-24 w-24 rounded-full object-cover mb-3 border border-gray-200"
           alt="Profile Picture">
      <input type="file" name="profile_image" accept="image/*"
             class="block text-sm border border-gray-200 rounded-lg p-2 bg-gray-50 w-full max-w-xs">
      <p class="text-xs text-gray-500 mt-1">Allowed types: JPG, JPEG, PNG.</p>
    </div>

    <!-- Name -->
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-700">Full Name</label>
      <input type="text" name="full_name" value="<?= $fullNameValue ?>" required
             class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-300">
    </div>

    <!-- Email -->
    <div>
      <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
      <input type="email" name="email" value="<?= $emailValue ?>" required
             class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-300">
    </div>

    <hr class="border-gray-200">

    <!-- Password Change -->
    <div>
      <label class="block text-sm font-semibold text-gray-800">Change Password (optional)</label>
      <input type="password" name="new_password" placeholder="New password"
             class="w-full border border-gray-200 rounded-lg p-2 text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-300">
      <input type="password" name="confirm_password" placeholder="Confirm password"
             class="w-full border border-gray-200 rounded-lg p-2 text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-300">
      <p class="text-xs text-gray-500 mt-1">Leave blank if you don't want to change your password.</p>
    </div>

    <!-- Buttons -->
    <div class="pt-4 flex items-center justify-end space-x-3">
      <a href="marketing_dashboard.php"
         class="text-sm font-medium px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">
        Cancel
      </a>
      <button type="submit"
              class="text-sm font-medium px-5 py-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600">
        Save Changes
      </button>
    </div>

  </form>
</main>

</body>
</html>
