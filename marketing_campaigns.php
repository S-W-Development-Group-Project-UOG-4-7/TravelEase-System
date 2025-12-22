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

// Campaign data
$campaigns = [
    [
        'id' => 1,
        'name' => 'Summer Asia Promotion',
        'status' => 'Active',
        'type' => 'Multi-Channel',
        'budget' => 25000,
        'spent' => 18450,
        'leads' => 1248,
        'conversions' => 156,
        'roi' => '4.2x',
        'start_date' => '2024-05-01',
        'end_date' => '2024-08-31',
        'channels' => ['Social Media', 'Email', 'PPC']
    ],
    [
        'id' => 2,
        'name' => 'Luxury Japan Getaway',
        'status' => 'Active',
        'type' => 'Influencer',
        'budget' => 15000,
        'spent' => 12800,
        'leads' => 892,
        'conversions' => 112,
        'roi' => '3.8x',
        'start_date' => '2024-06-15',
        'end_date' => '2024-09-30',
        'channels' => ['Instagram', 'YouTube', 'Blog']
    ],
    [
        'id' => 3,
        'name' => 'Bali Wellness Retreat',
        'status' => 'Paused',
        'type' => 'Content',
        'budget' => 12000,
        'spent' => 8200,
        'leads' => 567,
        'conversions' => 68,
        'roi' => '2.9x',
        'start_date' => '2024-04-01',
        'end_date' => '2024-07-31',
        'channels' => ['Blog', 'Email', 'Social']
    ],
    [
        'id' => 4,
        'name' => 'Thailand Island Hopping',
        'status' => 'Completed',
        'type' => 'PPC',
        'budget' => 18000,
        'spent' => 17500,
        'leads' => 1102,
        'conversions' => 198,
        'roi' => '3.5x',
        'start_date' => '2024-03-01',
        'end_date' => '2024-06-30',
        'channels' => ['Google Ads', 'Facebook Ads']
    ],
    [
        'id' => 5,
        'name' => 'Winter Luxury Escapes',
        'status' => 'Planned',
        'type' => 'Seasonal',
        'budget' => 20000,
        'spent' => 0,
        'leads' => 0,
        'conversions' => 0,
        'roi' => '0.0x',
        'start_date' => '2024-11-15',
        'end_date' => '2025-02-28',
        'channels' => ['All Channels']
    ],
    [
        'id' => 6,
        'name' => 'Spring Festival Tours',
        'status' => 'Draft',
        'type' => 'Cultural',
        'budget' => 22000,
        'spent' => 0,
        'leads' => 0,
        'conversions' => 0,
        'roi' => '0.0x',
        'start_date' => '2025-02-01',
        'end_date' => '2025-05-31',
        'channels' => ['TBD']
    ]
];

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
        'Cultural' => 'bg-green-100 text-green-800'
    ];
    return $classes[$type] ?? 'bg-gray-100 text-gray-800';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Campaigns Management | TravelEase Marketing</title>
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
            Campaigns
          </a>
          <a href="marketing_leads.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Leads
          </a>
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
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
            Campaigns
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_leads.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-amber-500 mr-2"></i>
            Leads
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_report.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-alt text-xs text-amber-500 mr-2"></i>
            Reports
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
              <span class="text-gradient">Campaign Management</span>
            </h1>
            <p class="text-lg text-gray-700">Manage all your marketing campaigns in one place.</p>
          </div>
          <div class="mt-4 md:mt-0">
            <a href="create_campaign.php" class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> Create New Campaign
            </a>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Campaigns</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-bullhorn text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= count($campaigns) ?></div>
          <p class="text-xs text-gray-500">All campaigns</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Active Campaigns</h3>
            <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
              <i class="fas fa-play-circle text-green-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= count(array_filter($campaigns, fn($c) => $c['status'] === 'Active')) ?>
          </div>
          <p class="text-xs text-gray-500">Currently running</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Budget</h3>
            <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
              <i class="fas fa-dollar-sign text-blue-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            $<?= number_format(array_sum(array_column($campaigns, 'budget'))) ?>
          </div>
          <p class="text-xs text-gray-500">Allocated budget</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Leads</h3>
            <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
              <i class="fas fa-users text-purple-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= number_format(array_sum(array_column($campaigns, 'leads'))) ?>
          </div>
          <p class="text-xs text-gray-500">Generated leads</p>
        </div>
      </div>

      <!-- Campaigns Table -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">All Campaigns</h3>
          <div class="flex items-center gap-3">
            <select class="p-2 rounded-xl border border-amber-200 bg-white text-sm">
              <option>Filter by Status</option>
              <option>Active</option>
              <option>Paused</option>
              <option>Completed</option>
              <option>Planned</option>
            </select>
            <input type="text" placeholder="Search campaigns..." class="p-2 rounded-xl border border-amber-200 bg-white text-sm w-48">
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-amber-100">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Campaign Name</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Type</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Budget</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Leads</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">ROI</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($campaigns as $campaign): ?>
              <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors">
                <td class="py-3 px-4">
                  <div class="font-medium text-gray-900"><?= htmlspecialchars($campaign['name']) ?></div>
                  <div class="text-xs text-gray-500">
                    <?= date('M d, Y', strtotime($campaign['start_date'])) ?> - <?= date('M d, Y', strtotime($campaign['end_date'])) ?>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getTypeClasses($campaign['type']) ?>">
                    <?= htmlspecialchars($campaign['type']) ?>
                  </span>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($campaign['status']) ?>">
                    <?= htmlspecialchars($campaign['status']) ?>
                  </span>
                </td>
                <td class="py-3 px-4">
                  <div class="text-sm text-gray-700">$<?= number_format($campaign['budget']) ?></div>
                  <div class="text-xs text-gray-500">Spent: $<?= number_format($campaign['spent']) ?></div>
                </td>
                <td class="py-3 px-4">
                  <div class="text-sm text-gray-700"><?= number_format($campaign['leads']) ?></div>
                  <div class="text-xs text-gray-500">Conversions: <?= number_format($campaign['conversions']) ?></div>
                </td>
                <td class="py-3 px-4">
                  <span class="font-semibold <?= $campaign['roi'] === '0.0x' ? 'text-gray-600' : 'text-green-600' ?>">
                    <?= htmlspecialchars($campaign['roi']) ?>
                  </span>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-2">
                    <button class="p-1 text-gray-600 hover:text-amber-600" title="View">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="p-1 text-gray-600 hover:text-amber-600" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="p-1 text-gray-600 hover:text-red-600" title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Campaign Types Distribution -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
          <div class="grid grid-cols-2 gap-3">
            <a href="#" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-chart-line text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Performance</span>
            </a>
            <a href="#" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-download text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Export Data</span>
            </a>
            <a href="#" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-copy text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Duplicate</span>
            </a>
            <a href="#" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
              <i class="fas fa-calendar text-amber-500 text-xl mb-2 block"></i>
              <span class="text-sm font-medium text-gray-700">Schedule</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-amber-100 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
        <p>© <?= $currentYear ?> TravelEase Marketing Dashboard. All rights reserved.</p>
        <div class="flex items-center gap-4">
          <a href="marketing_dashboard1.php" class="hover:text-amber-600">Dashboard</a>
          <a href="marketing_campaigns.php" class="text-amber-600 font-semibold">Campaigns</a>
          <a href="marketing_leads.php" class="hover:text-amber-600">Leads</a>
          <a href="marketing_report.php" class="hover:text-amber-600">Reports</a>
        </div>
      </div>
    </div>
  </footer>
    <script>
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

    function toggleMobileMenu() {
      if (isMenuOpen) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
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

    window.addEventListener('load', () => {
      const loadingBar = document.querySelector('.loading-bar');
      if (loadingBar) {
        loadingBar.style.opacity = '0';
        setTimeout(() => {
          loadingBar.remove();
        }, 500);
      }
      
      initializeCharts();
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        // Don't prevent default for mobile menu links
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

    function initializeCharts() {
      const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
      if (revenueCtx) {
        new Chart(revenueCtx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
              label: 'Revenue ($)',
              data: [185000, 210000, 195000, 245000, 265000, 284000, 275000],
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: false, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
              x: { grid: { display: false } }
            }
          }
        });
      }

      const trafficCtx = document.getElementById('trafficChart')?.getContext('2d');
      if (trafficCtx) {
        new Chart(trafficCtx, {
          type: 'doughnut',
          data: {
            labels: ['Organic Search', 'Social Media', 'Email', 'Direct', 'Referral'],
            datasets: [{
              data: [35, 25, 15, 12, 13],
              backgroundColor: ['#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#fef3c7'],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
          }
        });
      }

      const funnelCtx = document.getElementById('funnelChart')?.getContext('2d');
      if (funnelCtx) {
        new Chart(funnelCtx, {
          type: 'bar',
          data: {
            labels: ['Awareness', 'Interest', 'Consideration', 'Intent', 'Conversion'],
            datasets: [{
              data: [5000, 3500, 2000, 800, 240],
              backgroundColor: ['#fef3c7', '#fde68a', '#fcd34d', '#fbbf24', '#f59e0b'],
              borderWidth: 0,
              borderRadius: 4
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
              y: { grid: { display: false } }
            }
          }
        });
      }
    }

    // Feature card click handling
    document.querySelectorAll('.feature-card').forEach(card => {
      card.addEventListener('click', function() {
        const link = this.getAttribute('onclick')?.match(/href='([^']+)'/)?.[1];
        if (link) {
          window.location.href = link;
        }
      });
    });
  </script>
</body>
</html>