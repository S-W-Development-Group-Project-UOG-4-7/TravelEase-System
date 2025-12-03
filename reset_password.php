<?php
// reset_password.php
session_start();
date_default_timezone_set('Asia/Colombo');

require_once 'db.php'; // PDO $pdo

$token      = $_GET['token'] ?? '';
$error      = '';
$success    = '';
$showForm   = false;

/**
 * Find a valid (not used, not expired) reset record for the given token
 */
function findValidReset($pdo, $token) {
    if ($token === '') {
        return null;
    }

    $stmt = $pdo->prepare("
        SELECT pr.id, pr.user_id, pr.token, pr.expires_at, pr.used, u.email
        FROM password_resets pr
        JOIN users u ON u.id = pr.user_id
        WHERE pr.token = :token
        LIMIT 1
    ");
    $stmt->execute([':token' => $token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset) {
        return null;
    }

    // Already used?
    if (!empty($reset['used'])) {
        return null;
    }

    // Expired?
    if (strtotime($reset['expires_at']) < time()) {
        return null;
    }

    return $reset;
}

// ---------- Handle POST (user submits new password) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token        = $_POST['token'] ?? '';
    $password     = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $reset = findValidReset($pdo, $token);

    if (!$reset) {
        $error    = 'This reset link is invalid, used, or has expired.';
        $showForm = false;
    } else {
        if ($password === '' || $confirmPassword === '') {
            $error    = 'Please enter and confirm your new password.';
            $showForm = true;
        } elseif ($password !== $confirmPassword) {
            $error    = 'Passwords do not match.';
            $showForm = true;
        } elseif (strlen($password) < 8) {
            $error    = 'Password must be at least 8 characters long.';
            $showForm = true;
        } else {
            // All good – update password
            try {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $pdo->beginTransaction();

                // 1) Update user's password
                $updateUser = $pdo->prepare("
                    UPDATE users
                    SET password_hash = :hash
                    WHERE id = :user_id
                ");
                $updateUser->execute([
                    ':hash'    => $hashed,
                    ':user_id' => $reset['user_id'],
                ]);

                // 2) Mark this reset token as used
                $updateReset = $pdo->prepare("
                    UPDATE password_resets
                    SET used = TRUE
                    WHERE id = :id
                ");
                $updateReset->execute([':id' => $reset['id']]);

                $pdo->commit();

                $success  = 'Your password has been updated successfully. You can now log in with your new password.';
                $showForm = false;
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error    = 'Database error: ' . $e->getMessage();
                $showForm = true;
            }
        }
    }

// ---------- Handle GET (user clicks the link) ----------
} else {
    $reset = findValidReset($pdo, $token);

    if (!$reset) {
        $error    = 'This reset link is invalid, used, or has expired.';
        $showForm = false;
    } else {
        $showForm = true; // show form so user can set new password
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#facc15",
            primaryDark: "#eab308",
            primarySoft: "#fef9c3",
            ink: "#111827",
            inkMuted: "#6b7280",
            card: "#ffffff",
            bgSoft: "#f9fafb",
          },
          boxShadow: {
            'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
            'large': '0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)'
          }
        },
      },
    };
  </script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: Inter, sans-serif;
      background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
      min-height: 100vh;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-large border border-yellow-100 overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-primarySoft to-yellow-50 px-6 py-4 border-b border-yellow-100 flex items-center justify-between">
      <a href="login.php" class="flex items-center text-xs font-medium text-inkMuted hover:text-ink transition-colors group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Login
      </a>

      <div class="flex items-center">
        <img src="img/Logo.png" alt="TravelEase Logo" class="h-8">
        <span class="ml-2 text-sm font-bold text-ink">TravelEase</span>
      </div>
    </div>

    <!-- Content -->
    <div class="px-6 sm:px-8 py-6">
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primarySoft mb-3">
          <!-- Lock icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 11c1.657 0 3-1.343 3-3V7a3 3 0 10-6 0v1c0 1.657 1.343 3 3 3zM5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
          </svg>
        </div>
        <h1 class="text-xl font-bold text-ink">Reset your password</h1>
        <p class="text-xs text-inkMuted mt-2">
          Choose a strong new password for your TravelEase account.
        </p>
      </div>

      <!-- Error -->
      <?php if ($error): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-xs px-4 py-3 rounded-lg">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <!-- Success -->
      <?php if ($success): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-xs px-4 py-3 rounded-lg">
          <?= htmlspecialchars($success) ?>
        </div>

        <a href="login.php"
           class="mt-2 inline-flex items-center justify-center w-full text-center py-3 rounded-lg border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition-all">
          Back to Login
        </a>

      <?php elseif ($showForm): ?>
        <!-- Reset form -->
        <form method="POST" action="" class="space-y-5">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <div>
            <label class="block text-sm font-semibold text-ink mb-2">New Password</label>
            <input type="password" name="password" id="password" required
                   placeholder="Enter new password"
                   class="w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
          </div>

          <div>
            <label class="block text-sm font-semibold text-ink mb-2">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required
                   placeholder="Re-type new password"
                   class="w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
          </div>

          <button type="submit"
                  class="w-full py-3.5 rounded-lg bg-primary font-semibold text-ink text-sm hover:bg-primaryDark flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Update Password
          </button>
        </form>

      <?php else: ?>
        <!-- Invalid token message with CTA -->
        <div class="mt-4 text-xs text-inkMuted">
          If you reached this page from an old link, please request a new password reset.
        </div>
        <a href="forgot_password.php"
           class="mt-3 inline-flex items-center justify-center w-full text-center py-3 rounded-lg border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition-all">
          Request New Reset Link
        </a>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
