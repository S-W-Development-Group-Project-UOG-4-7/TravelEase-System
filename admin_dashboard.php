<?php
// admin_dashboard.php
require_once 'admin_auth.php'; // protects this page
require_once 'db.php';

$adminId    = $_SESSION['user_id'] ?? null;
$adminName  = 'Admin';
$adminImage = null;

// Dashboard stats defaults
$totalUsers     = 0;
$totalPackages  = 0;
$totalBookings  = 0;
$totalCountries = 0;

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
    try {
        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    } catch (PDOException $e) {}

    // Total packages
    try {
        $totalPackages = (int)$pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    } catch (PDOException $e) {}

    // Total bookings
    try {
        $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    } catch (PDOException $e) {}

    // Total countries (optional table)
    try {
        $totalCountries = (int)$pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();
    } catch (PDOException $e) {
        $totalCountries = 0; // if table doesn't exist
    }
} catch (PDOException $e) {
    // In real project, log error
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
            brand: {
              50:  '#fffbeb',
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
          },
          boxShadow: {
            soft: '0 10px 25px -10px rgba(0,0,0,0.10)',
            card: '0 12px 30px -18px rgba(0,0,0,0.18)',
          }
        }
      }
    }
  </script>

  <style>
    /* Smooth font rendering */
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-b from-brand-50 to-white text-gray-800">
  <!-- Mobile Topbar -->
  <header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="img/Logo.png" alt="TravelEase Logo" class="h-9 w-9 object-contain">
        <div class="leading-tight">
          <div class="flex items-center gap-2">
            <span class="text-lg sm:text-xl font-bold">TravelEase</span>
            <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-800">
              Admin Panel
            </span>
          </div>
          <p class="text-xs text-gray-500 hidden sm:block">Manage users, packages & bookings</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="admin_profile.php"
           class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 text-sm font-medium hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          My Profile
        </a>

        <a href="logout.php"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium hover:bg-black">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
          </svg>
          Logout
        </a>

        <!-- Avatar -->
        <?php if (!empty($adminImage)) : ?>
          <img
            src="<?= htmlspecialchars($adminImage) ?>"
            alt="Admin profile"
            class="h-10 w-10 rounded-full object-cover ring-2 ring-brand-200"
          >
        <?php else : ?>
          <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center text-sm font-semibold text-brand-800 ring-2 ring-brand-200">
            <?= strtoupper(substr($adminName, 0, 1)) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome + quick actions -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-7">
      <div>
        <p class="text-sm text-gray-500">Welcome back</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
          Hello, <span class="text-brand-800"><?= htmlspecialchars($adminName) ?></span>
        </h1>
        <p class="text-sm text-gray-600 mt-1">
          Here’s a quick snapshot of your platform activity.
        </p>
      </div>

      <div class="flex flex-col sm:flex-row gap-3">
        <a href="admin_users.php"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 shadow-soft">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a3 3 0 10-6 0 3 3 0 006 0z"/>
          </svg>
          Manage Users
        </a>

        <a href="create_package.php"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50 shadow-soft">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          New Package
        </a>

        <a href="admin_reports.php"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50 shadow-soft">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
          Reports
        </a>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <!-- Card: Users -->
      <div class="bg-white rounded-2xl p-5 shadow-card border border-gray-100 hover:-translate-y-0.5 hover:shadow-soft transition">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Users</p>
            <p class="mt-3 text-3xl font-extrabold"><?= (int)$totalUsers ?></p>
            <p class="mt-1 text-xs text-gray-500">Registered on TravelEase</p>
          </div>
          <div class="h-11 w-11 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <a href="admin_users.php" class="text-sm font-semibold text-brand-700 hover:text-brand-900 hover:underline">
            View users →
          </a>
        </div>
      </div>

      <!-- Card: Packages -->
      <div class="bg-white rounded-2xl p-5 shadow-card border border-gray-100 hover:-translate-y-0.5 hover:shadow-soft transition">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Packages</p>
            <p class="mt-3 text-3xl font-extrabold"><?= (int)$totalPackages ?></p>
            <p class="mt-1 text-xs text-gray-500">Curated destinations</p>
          </div>
          <div class="h-11 w-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l2 14h14l2-14M5 7l1-3h12l1 3"/>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex gap-3">
          <a href="admin_packages.php" class="text-sm font-semibold text-brand-700 hover:text-brand-900 hover:underline">
            Manage →
          </a>
          <a href="create_package.php" class="text-sm font-semibold text-gray-700 hover:text-gray-900 hover:underline">
            Create →
          </a>
        </div>
      </div>

      <!-- Card: Bookings -->
      <div class="bg-white rounded-2xl p-5 shadow-card border border-gray-100 hover:-translate-y-0.5 hover:shadow-soft transition">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Bookings</p>
            <p class="mt-3 text-3xl font-extrabold"><?= (int)$totalBookings ?></p>
            <p class="mt-1 text-xs text-gray-500">Completed & upcoming</p>
          </div>
          <div class="h-11 w-11 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <a href="admin_bookings.php" class="text-sm font-semibold text-brand-700 hover:text-brand-900 hover:underline">
            View bookings →
          </a>
        </div>
      </div>

      <!-- Card: Countries -->
      <div class="bg-white rounded-2xl p-5 shadow-card border border-gray-100 hover:-translate-y-0.5 hover:shadow-soft transition">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Countries</p>
            <p class="mt-3 text-3xl font-extrabold"><?= (int)$totalCountries ?></p>
            <p class="mt-1 text-xs text-gray-500">Catalog coverage</p>
          </div>
          <div class="h-11 w-11 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c4.418 0 8-4.03 8-9s-3.582-9-8-9-8 4.03-8 9 3.582 9 8 9z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h20"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2c2.5 2.4 4 5.9 4 10s-1.5 7.6-4 10c-2.5-2.4-4-5.9-4-10S9.5 4.4 12 2z"/>
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <span class="text-xs text-gray-500">
            <?php if ($totalCountries === 0): ?>
              Countries table optional
            <?php else: ?>
              Active destinations
            <?php endif; ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- User Management -->
      <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
          <div>
            <h2 class="text-lg font-bold">User Management</h2>
            <p class="text-sm text-gray-500 mt-1">
              View, search and manage registered travelers & staff roles.
            </p>
          </div>
          <div class="h-10 w-10 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/>
            </svg>
          </div>
        </div>
        <div class="mt-auto flex gap-3">
          <a href="admin_users.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl bg-gray-900 text-white hover:bg-black transition">
            Manage Users
          </a>
          <a href="create_admin.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl border border-gray-200 hover:bg-gray-50 transition">
            Add Admin
          </a>
        </div>
      </div>

      <!-- Packages -->
      <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
          <div>
            <h2 class="text-lg font-bold">Travel Packages</h2>
            <p class="text-sm text-gray-500 mt-1">
              Create and update curated travel packages for destinations.
            </p>
          </div>
          <div class="h-10 w-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7l2 14h14l2-14M5 7l1-3h12l1 3"/>
            </svg>
          </div>
        </div>
        <div class="mt-auto flex gap-3">
          <a href="admin_packages.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl bg-brand-600 text-white hover:bg-brand-700 transition">
            Manage Packages
          </a>
          <a href="create_package.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl border border-gray-200 hover:bg-gray-50 transition">
            New Package
          </a>
        </div>
      </div>

      <!-- Bookings & Reports -->
      <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
          <div>
            <h2 class="text-lg font-bold">Bookings & Reports</h2>
            <p class="text-sm text-gray-500 mt-1">
              Track bookings, monitor performance, and export reports.
            </p>
          </div>
          <div class="h-10 w-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
          </div>
        </div>
        <div class="mt-auto flex gap-3">
          <a href="admin_bookings.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl bg-gray-900 text-white hover:bg-black transition">
            View Bookings
          </a>
          <a href="admin_reports.php"
             class="flex-1 text-center text-sm font-semibold py-2.5 rounded-2xl border border-gray-200 hover:bg-gray-50 transition">
            Reports
          </a>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-10 text-center text-xs text-gray-500">
      © <?= date('Y') ?> TravelEase · Admin Dashboard
    </div>
  </div>
</body>
</html>
