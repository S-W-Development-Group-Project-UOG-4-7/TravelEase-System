<?php
// marketing_campaigns.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Database connection
require_once __DIR__ . '/db.php';

// Fetch packages from database
$campaigns = [];
try {
    $stmt = $pdo->query("
        SELECT 
            id,
            package_name as name,
            category,
            region,
            country,
            short_description,
            detailed_description,
            duration_days,
            difficulty_level,
            accommodation_type,
            inclusions,
            base_price as budget,
            group_min,
            group_max,
            availability_start as start_date,
            availability_end as end_date,
            early_bird_discount,
            early_bird_days,
            video_url,
            virtual_tour_url,
            cover_image,
            gallery_images,
            created_at,
            -- Calculate dynamic values for display
            COALESCE(group_min, 0) as leads,
            COALESCE(CAST(base_price * 0.1 as integer), 0) as conversions,
            CASE 
                WHEN availability_end < CURRENT_DATE THEN 'Completed'
                WHEN availability_start > CURRENT_DATE THEN 'Planned'
                WHEN availability_start <= CURRENT_DATE AND availability_end >= CURRENT_DATE THEN 'Active'
                ELSE 'Draft'
            END as status,
            CASE 
                WHEN availability_end < CURRENT_DATE THEN 'Completed'
                WHEN availability_start > CURRENT_DATE THEN 'Planned'
                WHEN availability_start <= CURRENT_DATE AND availability_end >= CURRENT_DATE THEN 'Active'
                ELSE 'Draft'
            END as display_status,
            -- Mock spent amount (you can calculate actual spent from orders if available)
            CAST(base_price * 0.7 as integer) as spent,
            -- Mock ROI calculation
            CASE 
                WHEN availability_end < CURRENT_DATE THEN '3.5x'
                WHEN availability_start > CURRENT_DATE THEN '0.0x'
                ELSE '2.8x'
            END as roi,
            category as type
        FROM travel_packages 
        ORDER BY created_at DESC
    ");
    
    $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no packages found, show sample data
    if (empty($campaigns)) {
        // You can keep some sample data or show empty state
        $campaigns = [
            [
                'id' => 1,
                'name' => 'Summer Asia Promotion',
                'status' => 'Active',
                'type' => 'Adventure',
                'budget' => 25000,
                'spent' => 18450,
                'leads' => 1248,
                'conversions' => 156,
                'roi' => '4.2x',
                'start_date' => '2024-05-01',
                'end_date' => '2024-08-31'
            ]
        ];
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    // Fallback to empty array
    $campaigns = [];
}

// Calculate statistics
$totalCampaigns = count($campaigns);
$activeCampaigns = count(array_filter($campaigns, fn($c) => ($c['display_status'] ?? $c['status']) === 'Active'));
$totalBudget = array_sum(array_column($campaigns, 'budget'));
$totalLeads = array_sum(array_column($campaigns, 'leads'));

// Status and type styling functions
function getStatusClasses($status) {
    $classes = [
        'Active' => 'bg-green-100 text-green-800',
        'On Track' => 'bg-green-100 text-green-800',
        'Paused' => 'bg-yellow-100 text-yellow-800',
        'Completed' => 'bg-blue-100 text-blue-800',
        'Planned' => 'bg-blue-100 text-blue-800',
        'Draft' => 'bg-gray-100 text-gray-800',
        'Starting Soon' => 'bg-yellow-100 text-yellow-800'
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800';
}

function getTypeClasses($type) {
    $classes = [
        'Multi-Channel' => 'bg-purple-100 text-purple-800',
        'Influencer' => 'bg-pink-100 text-pink-800',
        'Content' => 'bg-blue-100 text-blue-800',
        'PPC' => 'bg-red-100 text-red-800',
        'Seasonal' => 'bg-amber-100 text-amber-800',
        'Cultural' => 'bg-green-100 text-green-800',
        'Adventure' => 'bg-purple-100 text-purple-800',
        'Wellness & Spa' => 'bg-pink-100 text-pink-800',
        'Cultural' => 'bg-blue-100 text-blue-800',
        'Luxury' => 'bg-red-100 text-red-800',
        'Family' => 'bg-amber-100 text-amber-800',
        'Honeymoon' => 'bg-green-100 text-green-800',
        'Cruise' => 'bg-indigo-100 text-indigo-800',
        'Eco-Tourism' => 'bg-teal-100 text-teal-800'
    ];
    return $classes[$type] ?? 'bg-gray-100 text-gray-800';
}

// Footer links
$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard1.php'],
        ['text' => 'Campaigns', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Lead Management', 'link' => 'marketing_leads.php'],
        ['text' => 'Report Generator', 'link' => 'marketing_report.php']
    ],
    'Resources' => [
        ['text' => 'Help Center', 'link' => '#'],
        ['text' => 'API Documentation', 'link' => '#'],
        ['text' => 'Tutorials', 'link' => '#'],
        ['text' => 'Support Center', 'link' => '#']
    ],
    'Account' => [
        ['text' => 'Profile Settings', 'link' => 'marketing_profile.php'],
        ['text' => 'Notification Preferences', 'link' => '#'],
        ['text' => 'Team Management', 'link' => '#'],
        ['text' => 'Logout', 'link' => 'login.php']
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Packages Management | TravelEase Marketing</title>
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
              900: '#78350f'
            }
          }
        }
      }
    };
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { 
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
      color: #1f2937;
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .gold-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fcd34d 100%);
    }
    .text-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .mobile-menu {
  display: none;
    }

.mobile-menu.open {
  display: block;
    }

@keyframes slideIn {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
    }

@keyframes slideOut {
  from {
    transform: translateX(0);
  }
  to {
    transform: translateX(-100%);
  }
    }

.mobile-menu.open > div:last-child {
  animation: slideIn 0.3s ease-out forwards;
    }

.mobile-menu.closing > div:last-child {
  animation: slideOut 0.3s ease-in forwards;
    }
    
    /* Loading skeleton */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
    
    .empty-state {
      min-height: 300px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #6b7280;
    }
  </style>
</head>
<body class="min-h-screen">

<div class="loading-bar fixed top-0 left-0 z-50"></div>

  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Updated Mobile Menu Logo -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl overflow-hidden">
              <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain">
            </div>
            <span class="font-black text-xl text-gray-900">TravelEase</span>
          </div>
          <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <nav class="space-y-4">
          <a href="marketing_dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
         <a href="marketing_campaigns.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-bullhorn w-6 text-center"></i>
            Packages
          </a>
          <!--<a href="marketing_leads.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Leads
          </a>-->
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
          </a>
           <a href="partnership.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-handshake w-6 text-center"></i>
            Partnerships
          </a>
          <a href="marketing_profile.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-user w-6 text-center"></i>
            My Profile
          </a>
        </nav>

        <div class="mt-8 pt-8 border-t border-amber-100">
          <div class="flex items-center gap-3 mb-4">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
            <div>
              <div class="font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
              <div class="text-sm text-gray-600">Marketing Manager</div>
            </div>
          </div>
          <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-amber-50 transition-all">
            <i class="fas fa-sign-out-alt text-amber-500"></i>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Updated Main Header Logo -->
        <div class="flex items-center gap-3">
          <a href="#" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain bg-white p-2">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="font-black text-xl tracking-tight text-gray-900">
                TravelEase
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Marketing Dashboard
              </span>
            </div>
          </a>
        </div>

        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
          <a href="marketing_dashboard.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-chart-line text-xs text-amber-500"></i>
              Overview
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>

          <a href="marketing_campaigns.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
            Packages
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <!--<a href="marketing_leads.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-amber-500 mr-2"></i>
            Leads
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>-->
          <a href="marketing_report.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-alt text-xs text-amber-500 mr-2"></i>
            Reports
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
           <a href="partnership.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-handshake text-xs text-amber-500 mr-2"></i>
            Partnerships
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>

    <div class="hidden lg:flex items-center gap-4">
          <div class="flex items-center gap-3">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
            <div class="text-right">
              <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
              <div class="text-xs text-gray-600">Marketing Manager</div>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <a href="marketing_profile.php" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-colors" title="My Profile">
              <i class="fas fa-user"></i>
            </a>
            <a href="login.php" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-colors" title="Logout">
              <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </div>

        <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-amber-50 transition-colors">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>
    </nav>
  </header>

  <main class="pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black mb-2">
              <span class="text-gradient">Package Management</span>
            </h1>
            <p class="text-lg text-gray-700">Manage all your travel packages in one place.</p>
          </div>
          <div class="mt-4 md:mt-0">
            <a href="create_campaign.php" class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> Create New Package
            </a>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Packages</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-suitcase text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $totalCampaigns ?></div>
          <p class="text-xs text-gray-500">All packages</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Active Packages</h3>
            <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
              <i class="fas fa-play-circle text-green-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= $activeCampaigns ?>
          </div>
          <p class="text-xs text-gray-500">Currently available</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Value</h3>
            <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
              <i class="fas fa-dollar-sign text-blue-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            $<?= number_format($totalBudget) ?>
          </div>
          <p class="text-xs text-gray-500">Total package value</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Leads</h3>
            <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
              <i class="fas fa-users text-purple-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= number_format($totalLeads) ?>
          </div>
          <p class="text-xs text-gray-500">Estimated leads</p>
        </div>
      </div>

      <!-- Packages Table -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">All Packages</h3>
          <div class="flex items-center gap-3">
            <select id="statusFilter" class="p-2 rounded-xl border border-amber-200 bg-white text-sm">
              <option value="">All Status</option>
              <option value="Active">Active</option>
              <option value="Planned">Planned</option>
              <option value="Completed">Completed</option>
              <option value="Draft">Draft</option>
            </select>
            <input type="text" id="searchInput" placeholder="Search packages..." class="p-2 rounded-xl border border-amber-200 bg-white text-sm w-48">
          </div>
        </div>
        
        <?php if (empty($campaigns)): ?>
          <div class="empty-state p-8 text-center">
            <div class="h-20 w-20 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-suitcase text-amber-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Packages Found</h3>
            <p class="text-gray-600 mb-4">You haven't created any travel packages yet.</p>
            <a href="create_campaign.php" class="inline-flex items-center px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> Create Your First Package
            </a>
          </div>
        <?php else: ?>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-amber-100">
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Package Name</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Category</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Price</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Duration</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Availability</th>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody id="packagesTableBody">
                <?php foreach ($campaigns as $package): 
                  $status = $package['display_status'] ?? $package['status'];
                  $type = $package['type'] ?? $package['category'] ?? 'Other';
                ?>
                <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors package-row" 
                    data-status="<?= htmlspecialchars($status) ?>"
                    data-name="<?= htmlspecialchars(strtolower($package['name'])) ?>">
                  <td class="py-3 px-4">
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($package['name']) ?></div>
                    <div class="text-xs text-gray-500">
                      <?= htmlspecialchars($package['region'] ?? '') ?>, <?= htmlspecialchars($package['country'] ?? '') ?>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getTypeClasses($type) ?>">
                      <?= htmlspecialchars($type) ?>
                    </span>
                  </td>
                  <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($status) ?>">
                      <?= htmlspecialchars($status) ?>
                    </span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="text-sm text-gray-700">$<?= number_format($package['budget']) ?></div>
                    <?php if (isset($package['spent']) && $package['spent'] > 0): ?>
                    <div class="text-xs text-gray-500">Spent: $<?= number_format($package['spent']) ?></div>
                    <?php endif; ?>
                  </td>
                  <td class="py-3 px-4">
                    <div class="text-sm text-gray-700"><?= htmlspecialchars($package['duration_days'] ?? 0) ?> days</div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="text-sm text-gray-700">
                      <?php if (!empty($package['start_date']) && !empty($package['end_date'])): ?>
                        <?= date('M d, Y', strtotime($package['start_date'])) ?> - <?= date('M d, Y', strtotime($package['end_date'])) ?>
                      <?php else: ?>
                        TBD
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                      <a href="view_package.php?id=<?= $package['id'] ?>" class="p-1 text-gray-600 hover:text-amber-600" title="View">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="edit_package.php?id=<?= $package['id'] ?>" class="p-1 text-gray-600 hover:text-amber-600" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button onclick="deletePackage(<?= $package['id'] ?>, '<?= htmlspecialchars(addslashes($package['name'])) ?>')" class="p-1 text-gray-600 hover:text-red-600" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
          <div class="grid grid-cols-2 gap-3">
            <a href="marketing_report.php" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-chart-line text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Performance</span>
            </a>
            <a href="export_packages.php" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-download text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Export Data</span>
            </a>
            <a href="create_campaign.php" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-copy text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">New Package</span>
            </a>
            <a href="#" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-calendar text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Schedule</span>
            </a>
          </div>
        </div>
        
        <!-- Package Statistics -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Package Statistics</h3>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Active Packages</span>
                <span class="font-semibold"><?= $activeCampaigns ?> / <?= $totalCampaigns ?></span>
              </div>
              <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full" style="width: <?= $totalCampaigns > 0 ? ($activeCampaigns / $totalCampaigns * 100) : 0 ?>%"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Average Price</span>
                <span class="font-semibold">$<?= $totalCampaigns > 0 ? number_format($totalBudget / $totalCampaigns) : 0 ?></span>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Average Duration</span>
                <span class="font-semibold">
                  <?php 
                  if ($totalCampaigns > 0) {
                    $totalDays = 0;
                    $count = 0;
                    foreach ($campaigns as $pkg) {
                      if (isset($pkg['duration_days']) && $pkg['duration_days'] > 0) {
                        $totalDays += $pkg['duration_days'];
                        $count++;
                      }
                    }
                    echo $count > 0 ? number_format($totalDays / $count, 1) . ' days' : 'N/A';
                  } else {
                    echo 'N/A';
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-amber-100 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid gap-8 md:grid-cols-4 mb-8">
        <!-- Updated Footer Logo -->
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-xl overflow-hidden bg-white p-1">
              <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain">
            </div>
            <span class="font-black text-lg text-gray-900">TravelEase</span>
          </div>
          <p class="text-sm text-gray-700 mb-4">
            Marketing Dashboard for TravelEase luxury travel platform.
          </p>
          <div class="flex gap-3">
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>

        <?php foreach ($footerLinks as $title => $links): ?>
        <div>
          <h3 class="font-semibold text-gray-900 mb-4"><?= htmlspecialchars($title) ?></h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <?php foreach ($links as $link): ?>
            <li><a href="<?= htmlspecialchars($link['link']) ?>" class="hover:text-amber-600 transition-colors"><?= htmlspecialchars($link['text']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="pt-8 border-t border-amber-100 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
        <p>© <?= $currentYear ?> TravelEase Marketing Dashboard. All rights reserved.</p>
        <div class="flex items-center gap-4">
          <span>Premium Marketing Platform</span>
          <span class="flex items-center">
            <i class="fas fa-circle text-green-500 text-xs mr-1"></i> All Systems Operational
          </span>
        </div>
      </div>
    </div>
  </footer>
  
    <script>
    // Mobile menu functionality
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    
    let isMenuOpen = false;

    function openMobileMenu() {
      mobileMenu.classList.remove('hidden');
      mobileMenu.classList.remove('closing');
      setTimeout(() => {
        mobileMenu.classList.add('open');
      }, 10);
      document.body.style.overflow = 'hidden';
      isMenuOpen = true;
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove('open');
      mobileMenu.classList.add('closing');
      setTimeout(() => {
        mobileMenu.classList.add('hidden');
        mobileMenu.classList.remove('closing');
      }, 300);
      document.body.style.overflow = '';
      isMenuOpen = false;
    }

    if (menuButton) {
      menuButton.addEventListener('click', openMobileMenu);
    }

    if (mobileMenuClose) {
      mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileMenuBackdrop) {
      mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
    }

    // Close menu when clicking on menu links
    document.querySelectorAll('#mobile-menu a').forEach(link => {
      link.addEventListener('click', closeMobileMenu);
    });

    // Close menu on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && isMenuOpen) {
        closeMobileMenu();
      }
    });

    // Loading bar removal
    window.addEventListener('load', () => {
      const loadingBar = document.querySelector('.loading-bar');
      if (loadingBar) {
        loadingBar.style.opacity = '0';
        setTimeout(() => {
          loadingBar.remove();
        }, 500);
      }
    });

    // Filter and search functionality
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const packageRows = document.querySelectorAll('.package-row');

    function filterPackages() {
      const status = statusFilter.value;
      const search = searchInput.value.toLowerCase();
      
      packageRows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowName = row.getAttribute('data-name');
        const matchesStatus = !status || rowStatus === status;
        const matchesSearch = !search || rowName.includes(search);
        
        if (matchesStatus && matchesSearch) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    if (statusFilter) {
      statusFilter.addEventListener('change', filterPackages);
    }

    if (searchInput) {
      searchInput.addEventListener('input', filterPackages);
    }

    // Delete package function
    function deletePackage(id, name) {
      if (confirm(`Are you sure you want to delete the package "${name}"? This action cannot be undone.`)) {
        // Create a form to submit the delete request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_package.php';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
      }
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        if (this.getAttribute('href') === '#') return;
        
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          
          // Close mobile menu if open
          if (isMenuOpen) {
            closeMobileMenu();
          }
        }
      });
    });
  </script>
</body>
</html>