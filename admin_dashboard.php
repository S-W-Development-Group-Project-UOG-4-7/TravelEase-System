<?php
// admin_dashboard.php
require_once 'admin_auth.php'; // protects this page
require_once 'db.php';

$adminId   = $_SESSION['user_id'] ?? null;
$adminName = 'Admin';
$adminImage = null;

// Dashboard stats defaults
$totalUsers      = 0;
$totalPackages   = 0;
$totalBookings   = 0;
$totalCountries  = 0;

try {
    // Fetch admin info (name + profile image)
    if ($adminId) {
        $stmtAdmin = $pdo->prepare("SELECT full_name, profile_image FROM users WHERE id = :id LIMIT 1");
        $stmtAdmin->execute([':id' => $adminId]);
        if ($row = $stmtAdmin->fetch(PDO::FETCH_ASSOC)) {
            $adminName  = $row['full_name'] ?: 'Admin';
            $adminImage = $row['profile_image'] ?: null;

            // Keep session name in sync
            $_SESSION['full_name'] = $adminName;
        }
    }

    // Total users
    $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // Total packages
    $totalPackages = (int)$pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();

    // Total bookings
    $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

    // Total countries (if you have a countries table)
    $totalCountries = (int)$pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();

} catch (PDOException $e) {
    // In real project you might log this instead
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard | TravelEase</title>
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
<body class="min-h-screen bg-gray-100">
  <!-- Top Navbar -->
  <header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <div class="flex items-center space-x-3">
        <img src="img/Logo.png" alt="TravelEase Logo" class="h-9 w-9 object-contain">
        <div>
          <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-gray-800">TravelEase</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
              Admin Panel
            </span>
          </div>
          <p class="text-xs text-gray-500">Manage users, packages & bookings across Asia</p>
        </div>
      </div>

      <!-- Admin profile area (UPDATED) -->
      <div class="flex items-center space-x-4">
        <!-- Avatar -->
        <?php if (!empty($adminImage)) : ?>
          <img
            src="<?= htmlspecialchars($adminImage) ?>"
            alt="Admin profile"
            class="h-10 w-10 rounded-full object-cover border border-primary-200"
          >
        <?php else : ?>
          <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-sm font-semibold text-primary-700">
            <?= strtoupper(substr($adminName, 0, 1)) ?>
          </div>
        <?php endif; ?>

        <!-- Name + actions -->
        <div class="flex flex-col items-start">
          <span class="text-sm text-gray-600">
            Hello, <span class="font-semibold"><?= htmlspecialchars($adminName) ?></span>
          </span>
          <div class="flex items-center space-x-2 mt-1">
            <a href="admin_profile.php"
               class="text-xs font-medium text-primary-700 hover:text-primary-900 hover:underline">
              My Profile
            </a>
            <span class="text-gray-300 text-xs">|</span>
            <a href="logout.php"
               class="text-xs font-medium text-gray-700 hover:text-red-600 border px-2 py-1 rounded-lg">
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
        <p class="text-sm text-gray-500">Quick snapshot of your TravelEase platform activity.</p>
      </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $totalUsers ?></p>
        <p class="mt-1 text-xs text-gray-500">Registered on TravelEase</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Travel Packages</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $totalPackages ?></p>
        <p class="mt-1 text-xs text-gray-500">Across Asia</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Bookings</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $totalBookings ?></p>
        <p class="mt-1 text-xs text-gray-500">Completed & upcoming</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Countries</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $totalCountries ?></p>
        <p class="mt-1 text-xs text-gray-500">In your Asia catalog</p>
      </div>
    </div>

    <!-- Main grid: management sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Users management -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">User Management</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Admin</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          View, search and manage registered travelers & admins.
        </p>
        <div class="mt-auto flex space-x-3">
          <a href="admin_users.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            Manage Users
          </a>
          <a href="create_admin.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            Add Admin
          </a>
        </div>
      </div>

      <!-- Packages management -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Travel Packages</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Asia Trips</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          Create and update curated travel packages for Asian destinations.
        </p>
        <div class="mt-auto flex space-x-3">
          <a href="admin_packages.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            Manage Packages
          </a>
          <a href="create_package.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            New Package
          </a>
        </div>
      </div>

      <!-- Bookings & reports -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Bookings & Reports</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Analytics</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          Track bookings, monitor performance, and export basic reports.
        </p>
        <div class="mt-auto flex space-x-3">
          <a href="admin_bookings.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            View Bookings
          </a>
          <a href="admin_reports.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            View Reports
          </a>
        </div>
      </div>
    </div>

  </div>
</body>
</html>
