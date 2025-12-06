<?php
// forgot_password.php
session_start();
date_default_timezone_set('Asia/Colombo');

require_once 'db.php'; // PDO $pdo

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $error = 'Please enter your email address.';
    } else {
        try {
            // Look up user
            $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Generic success message
            $success = 'If that email is registered, a reset link has been generated.';

            if ($user) {
                // Generate secure token
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

                // Store reset token
                $insert = $pdo->prepare("
                    INSERT INTO password_resets (user_id, token, expires_at, used)
                    VALUES (:user_id, :token, :expires_at, FALSE)
                ");
                $insert->execute([
                    ':user_id'    => $user['id'],
                    ':token'      => $token,
                    ':expires_at' => $expiresAt
                ]);

                // Reset link
                $resetLink = 'http://localhost/travelease/reset_password.php?token=' . urlencode($token);

                // ---- UPDATED: Dev button instead of plain URL ----
                $success .= '
                    <br><br>
                    <a href="' . htmlspecialchars($resetLink) . '"
                       class="inline-block px-4 py-2 bg-primary text-ink font-semibold text-xs rounded-lg
                              hover:bg-primaryDark shadow-soft transition-all">
                        Click Here to Reset Password
                    </a>
                ';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

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
            'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05)',
            'large': '0 20px 25px -5px rgba(0, 0, 0, 0.05)'
          }
        },
      },
    };
  </script>

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
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.105.895-2 2-2s2 .895 2 2-2 3-2 3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h1 class="text-xl font-bold text-ink">Forgot your password?</h1>
        <p class="text-xs text-inkMuted mt-2">
          Enter your registered email address and we'll generate a password reset link.
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
          <?= $success ?>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form method="POST" action="" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-ink mb-2">Email Address</label>
          <input type="email" name="email" required placeholder="your.email@example.com"
                 class="w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
        </div>

        <button type="submit"
                class="w-full py-3.5 rounded-lg bg-primary font-semibold text-ink text-sm hover:bg-primaryDark flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-3-3m3 3l-3 3M4 6h16" />
          </svg>
          Send Reset Instructions
        </button>
      </form>

    </div>
  </div>

</body>
</html>
