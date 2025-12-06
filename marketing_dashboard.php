<?php
// marketing_dashboard.php
require_once 'marketing_auth.php'; // protects this page
require_once 'db.php';

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';

// Profile image from session (set this in marketing_profile.php after upload)
$profileImage = !empty($_SESSION['profile_image'])
    ? 'uploads/profile/' . $_SESSION['profile_image']
    : 'img/default_user.png';

// Placeholder metrics (0 for now)
$totalCampaigns        = 0;
$activeCampaigns       = 0;
$leadsThisMonth        = 0;
$revenueFromCampaigns  = 0.0;

// OPTIONAL REAL QUERIES (uncomment after your tables are ready)
/*
try {
    $totalCampaigns = (int)$pdo->query("SELECT COUNT(*) FROM marketing_campaigns")->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM marketing_campaigns WHERE status = 'active'");
    $stmt->execute();
    $activeCampaigns = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM leads 
        WHERE DATE_TRUNC('month', created_at) = DATE_TRUNC('month', CURRENT_DATE)
    ");
    $stmt->execute();
    $leadsThisMonth = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(total_amount), 0) 
        FROM bookings 
        WHERE campaign_id IS NOT NULL
          AND DATE_TRUNC('month', booking_date) = DATE_TRUNC('month', CURRENT_DATE)
    ");
    $stmt->execute();
    $revenueFromCampaigns = (float)$stmt->fetchColumn();

} catch (PDOException $e) {
    $dbError = $e->getMessage();
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Marketing Dashboard | TravelEase</title>
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
      <!-- Left: Logo + Title -->
      <div class="flex items-center space-x-3">
        <img src="img/Logo.png" alt="TravelEase Logo" class="h-9 w-9 object-contain">
        <div>
          <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-gray-800">TravelEase</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
              Marketing Panel
            </span>
          </div>
          <p class="text-xs text-gray-500">Monitor campaigns, leads & bookings across Asia</p>
        </div>
      </div>

      <!-- Right: Profile picture + greeting + buttons -->
      <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-3">
          <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>"
               alt="Profile Picture"
               class="h-9 w-9 rounded-full object-cover border border-gray-200">
          <span class="text-sm text-gray-600">
            Hello, <span class="font-semibold"><?= htmlspecialchars($managerName) ?></span>
          </span>
        </div>

        <a href="marketing_profile.php"
           class="text-xs sm:text-sm font-medium text-primary-700 hover:text-primary-800 border px-3 py-1.5 rounded-lg bg-primary-50">
          My Profile
        </a>
        <a href="logout.php"
           class="text-sm font-medium text-gray-700 hover:text-red-600 border px-3 py-1.5 rounded-lg">
          Logout
        </a>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Marketing Overview</h1>
        <p class="text-sm text-gray-500">Quick snapshot of your campaigns and marketing performance.</p>
      </div>
      <div class="mt-3 sm:mt-0">
        <a href="create_campaign.php"
           class="inline-flex items-center text-sm font-medium px-4 py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
          + New Campaign
        </a>
      </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Campaigns</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $totalCampaigns ?></p>
        <p class="mt-1 text-xs text-gray-500">Created in TravelEase</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Campaigns</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $activeCampaigns ?></p>
        <p class="mt-1 text-xs text-gray-500">Currently running</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Leads This Month</p>
        <p class="mt-3 text-3xl font-bold text-gray-800"><?= $leadsThisMonth ?></p>
        <p class="mt-1 text-xs text-gray-500">From all channels</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenue (Campaigns)</p>
        <p class="mt-3 text-3xl font-bold text-gray-800">$<?= number_format($revenueFromCampaigns, 2) ?></p>
        <p class="mt-1 text-xs text-gray-500">This month via campaigns</p>
      </div>
    </div>

    <!-- Main grid: management sections -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Campaign management -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col lg:col-span-2">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Campaign Management</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Marketing</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          Create, edit and monitor your marketing campaigns across digital & offline channels.
        </p>
        <div class="mt-auto flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
          <a href="marketing_campaigns.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            View Campaigns
          </a>
          <a href="create_campaign.php"
             class="flex-1 text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            New Campaign
          </a>
        </div>
      </div>

      <!-- Leads & conversions -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Leads & Conversions</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Leads</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          Track inquiries, follow up potential travelers, and see which campaigns convert into bookings.
        </p>
        <div class="mt-auto flex flex-col space-y-3">
          <a href="marketing_leads.php"
             class="text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            View Leads
          </a>
          <a href="marketing_bookings.php"
             class="text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            Campaign Bookings
          </a>
        </div>
      </div>

      <!-- Performance & reports -->
      <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-lg font-semibold text-gray-800">Performance & Reports</h2>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Analytics</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">
          Compare channels, measure ROI, and export basic marketing performance reports.
        </p>
        <div class="mt-auto flex flex-col space-y-3">
          <a href="marketing_reports.php"
             class="text-center text-sm font-medium py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
            View Reports
          </a>
          <a href="marketing_insights.php"
             class="text-center text-sm font-medium py-2 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
            Campaign Insights
          </a>
        </div>
      </div>
    </div>

    <!-- Profile info teaser (optional) -->
    <div class="mt-8 bg-white rounded-2xl shadow-sm p-5 flex flex-col md:flex-row md:items-center md:justify-between">
      <div>
        <h2 class="text-sm font-semibold text-gray-800 mb-1">My Profile & Account</h2>
        <p class="text-sm text-gray-500">
          Update your name, email, password and profile picture on the profile settings page.
        </p>
      </div>
      <div class="mt-3 md:mt-0">
        <a href="marketing_profile.php"
           class="inline-flex items-center text-sm font-medium px-4 py-2 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
          Go to Profile Settings
        </a>
      </div>
    </div>

    <?php if (!empty($dbError ?? '')): ?>
      <div class="mt-6 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl p-3">
        Database error: <?= htmlspecialchars($dbError) ?>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
