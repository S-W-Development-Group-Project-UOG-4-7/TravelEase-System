<?php
// login.php
session_start();
// If already logged in, send to user dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: user_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
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
        },
      },
    };
  </script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: Inter, sans-serif; }
  </style>
</head>

<body class="bg-bgSoft min-h-screen flex items-center justify-center px-4">

  <div class="w-full max-w-md bg-white rounded-3xl shadow-lg border border-yellow-100 p-6 sm:p-8">
    
    <!-- Top bar -->
    <div class="flex items-center justify-between mb-4">
      <a href="guest_dashboard.php" class="text-xs text-inkMuted hover:text-ink">&larr; Back</a>
      <img src="img/Logo.png" alt="TravelEase Logo" class="h-10">
    </div>

    <div class="text-center mb-6">
      <h1 class="text-xl font-extrabold text-ink">Welcome back to TravelEase</h1>
      <p class="text-sm text-inkMuted mt-1">
        Sign in to access your saved trips and bookings.
      </p>
    </div>

    <!-- Login form -->
    <form method="POST" action="login_action.php" class="space-y-4">

      <div>
        <label class="block text-sm font-semibold text-ink mb-1">Email Address</label>
        <input type="email" name="email" required
               class="w-full border border-yellow-200 bg-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
      </div>

      <div>
        <label class="block text-sm font-semibold text-ink mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full border border-yellow-200 bg-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none">
      </div>

      <div class="flex items-center justify-between text-xs text-inkMuted">
        <div class="flex items-center gap-2">
          <input type="checkbox" id="remember" name="remember" class="rounded border-yellow-300 text-primary focus:ring-primary">
          <label for="remember">Remember me</label>
        </div>
        <span class="text-[11px] italic">Forgot password? (coming soon)</span>
      </div>

      <button type="submit"
              class="w-full py-3 rounded-xl bg-primary font-semibold text-ink text-sm hover:bg-primaryDark hover:text-white transition">
        Sign In
      </button>

      <div class="text-center text-xs text-inkMuted mt-2">
        Don’t have a TravelEase account?
      </div>

      <a href="create_account.php"
         class="block w-full text-center py-2 rounded-xl border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition">
        Create Account
      </a>

      <a href="guest_dashboard.php"
         class="block w-full text-center py-2 rounded-xl border border-yellow-100 text-inkMuted text-sm hover:bg-bgSoft hover:text-ink transition">
        Continue as Guest
      </a>
    </form>
  </div>

</body>
</html>
