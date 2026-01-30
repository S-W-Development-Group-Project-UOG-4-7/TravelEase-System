<?php
// marketing_reports.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Updated Reports data - Travel specific
$reports = [
    [
        'id' => 1,
        'title' => 'Package Performance Analysis',
        'type' => 'Package',
        'period' => 'October 2024',
        'status' => 'Generated',
        'size' => '3.2 MB',
        'date' => 'Nov 1, 2024',
        'downloads' => 28
    ],
    [
        'id' => 2,
        'title' => 'Campaign ROI Report',
        'type' => 'Campaign',
        'period' => 'Q3 2024',
        'status' => 'Generated',
        'size' => '2.8 MB',
        'date' => 'Oct 15, 2024',
        'downloads' => 22
    ],
    [
        'id' => 3,
        'title' => 'Partnership Performance',
        'type' => 'Partnership',
        'period' => 'September 2024',
        'status' => 'Generated',
        'size' => '2.1 MB',
        'date' => 'Oct 5, 2024',
        'downloads' => 19
    ],
    [
        'id' => 4,
        'title' => 'Customer Feedback Analysis',
        'type' => 'Feedback',
        'period' => 'October 2024',
        'status' => 'Generated',
        'size' => '1.9 MB',
        'date' => 'Nov 3, 2024',
        'downloads' => 15
    ],
    [
        'id' => 5,
        'title' => 'Email Campaign Performance',
        'type' => 'Email',
        'period' => 'October 2024',
        'status' => 'Generated',
        'size' => '2.5 MB',
        'date' => 'Oct 25, 2024',
        'downloads' => 17
    ],
    [
        'id' => 6,
        'title' => 'Seasonal Package Forecast',
        'type' => 'Forecast',
        'period' => 'Q4 2024',
        'status' => 'In Progress',
        'size' => 'N/A',
        'date' => 'Processing',
        'downloads' => 0
    ],
    [
        'id' => 7,
        'title' => 'Social Media Engagement',
        'type' => 'Social Media',
        'period' => 'October 2024',
        'status' => 'Generated',
        'size' => '2.3 MB',
        'date' => 'Nov 2, 2024',
        'downloads' => 24
    ],
    [
        'id' => 8,
        'title' => 'Annual Marketing Review',
        'type' => 'Annual',
        'period' => '2024',
        'status' => 'Scheduled',
        'size' => 'N/A',
        'date' => 'Scheduled: Dec 15',
        'downloads' => 0
    ]
];

// Report templates with detailed structure
$templates = [
    [
        'name' => 'Package Performance',
        'description' => 'Travel package bookings, revenue, and conversion',
        'icon' => 'suitcase-rolling',
        'sections' => [
            'Executive Summary',
            'Booking Trends by Package Type',
            'Revenue Analysis',
            'Customer Demographics',
            'Seasonality Insights',
            'Recommendations'
        ],
        'metrics' => ['revenue', 'bookings', 'conversion_rate', 'avg_booking_value']
    ],
    [
        'name' => 'Campaign ROI',
        'description' => 'Marketing campaign ROI and effectiveness',
        'icon' => 'bullhorn',
        'sections' => [
            'Campaign Overview',
            'Cost Analysis',
            'Conversion Metrics',
            'ROI Calculation',
            'Channel Performance',
            'Learnings & Next Steps'
        ],
        'metrics' => ['roi', 'cpc', 'ctr', 'conversion_rate', 'cpa']
    ],
    [
        'name' => 'Partnership Analysis',
        'description' => 'Partner performance and revenue sharing',
        'icon' => 'handshake',
        'sections' => [
            'Partnership Overview',
            'Revenue Sharing Report',
            'Referral Performance',
            'Customer Feedback',
            'Partnership Health Score',
            'Renewal Recommendations'
        ],
        'metrics' => ['revenue_share', 'referral_rate', 'customer_satisfaction', 'partner_score']
    ],
    [
        'name' => 'Feedback Insights',
        'description' => 'Customer satisfaction and NPS analysis',
        'icon' => 'comments',
        'sections' => [
            'NPS Score & Trends',
            'Sentiment Analysis',
            'Common Themes',
            'Service Improvement Areas',
            'Positive Feedback Highlights',
            'Action Plan'
        ],
        'metrics' => ['nps_score', 'csat_score', 'sentiment_score', 'response_rate']
    ],
    [
        'name' => 'Seasonal Forecast',
        'description' => 'Upcoming season demand prediction',
        'icon' => 'calendar-alt',
        'sections' => [
            'Market Trends Analysis',
            'Historical Performance',
            'Demand Forecasting',
            'Competitor Analysis',
            'Pricing Strategy',
            'Marketing Recommendations'
        ],
        'metrics' => ['demand_forecast', 'price_sensitivity', 'market_share', 'growth_rate']
    ],
    [
        'name' => 'Channel Performance',
        'description' => 'Marketing channel effectiveness',
        'icon' => 'chart-bar',
        'sections' => [
            'Channel Overview',
            'Performance Metrics',
            'Cost Analysis',
            'ROI by Channel',
            'Audience Insights',
            'Optimization Strategy'
        ],
        'metrics' => ['channel_roi', 'engagement_rate', 'traffic_share', 'conversion_rate']
    ]
];

function getStatusClasses($status) {
    $classes = [
        'Generated' => 'bg-green-100 text-green-800',
        'Pending' => 'bg-yellow-100 text-yellow-800',
        'In Progress' => 'bg-blue-100 text-blue-800',
        'Scheduled' => 'bg-purple-100 text-purple-800'
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800';
}

function getTypeClasses($type) {
    $classes = [
        'Package' => 'bg-blue-100 text-blue-800',
        'Campaign' => 'bg-purple-100 text-purple-800',
        'Partnership' => 'bg-green-100 text-green-800',
        'Feedback' => 'bg-amber-100 text-amber-800',
        'Email' => 'bg-indigo-100 text-indigo-800',
        'Forecast' => 'bg-teal-100 text-teal-800',
        'Social Media' => 'bg-pink-100 text-pink-800',
        'Annual' => 'bg-red-100 text-red-800'
    ];
    return $classes[$type] ?? 'bg-gray-100 text-gray-800';
}

// Handle template selection
$selectedTemplate = null;
if (isset($_GET['template'])) {
    $templateIndex = intval($_GET['template']);
    if (isset($templates[$templateIndex])) {
        $selectedTemplate = $templates[$templateIndex];
    }
}

// Handle form submission for generating report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'generate_report') {
        // Process report generation
        $templateName = $_POST['template_name'] ?? '';
        $reportTitle = $_POST['report_title'] ?? 'Custom Report';
        $timePeriod = $_POST['time_period'] ?? 'last_month';
        $includeMetrics = $_POST['metrics'] ?? [];
        $additionalNotes = $_POST['notes'] ?? '';
        
        // Here you would generate the actual report
        // For now, we'll just show a success message
        $_SESSION['report_message'] = "Report '$reportTitle' generated successfully!";
        header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
        exit;
    }
}

$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard.php'],
        ['text' => 'Packages', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Partnerships', 'link' => 'partnership.php'],
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
  <title>Reports Management | TravelEase Marketing</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      from { transform: translateX(-100%); }
      to { transform: translateX(0); }
    }
    @keyframes slideOut {
      from { transform: translateX(0); }
      to { transform: translateX(-100%); }
    }
    .mobile-menu.open > div:last-child {
      animation: slideIn 0.3s ease-out forwards;
    }
    .mobile-menu.closing > div:last-child {
      animation: slideOut 0.3s ease-in forwards;
    }
    .chart-container {
      position: relative;
      height: 250px;
    }
    .template-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    .template-modal.active {
      display: flex;
    }
    .template-content {
      background: white;
      border-radius: 1rem;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
    }
  </style>
</head>
<body class="min-h-screen">

  <div class="loading-bar fixed top-0 left-0 z-50"></div>

  <!-- Mobile Menu (unchanged) -->
  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Mobile Menu Logo -->
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
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
          </a>
          <a href="partnership.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-handshake w-6 text-center"></i>
            Partnerships
          </a>
          <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
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
          <a href="login.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-amber-50 transition-all">
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
        <!-- Main Header Logo -->
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
          <a href="marketing_feedback.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
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
      <!-- Success Message -->
      <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['report_message'] ?? 'Report generated successfully!' ?></span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
            <i class="fas fa-times"></i>
          </button>
        </div>
      <?php endif; ?>

      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black mb-2">
              <span class="text-gradient">Reports & Analytics</span>
            </h1>
            <p class="text-lg text-gray-700">Generate and analyze travel marketing performance reports.</p>
          </div>
          <div class="mt-4 md:mt-0">
            <a href="generate_report.php" class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> Generate Report
            </a>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Reports</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-file-alt text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= count($reports) ?></div>
          <p class="text-xs text-gray-500">All reports</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Ready</h3>
            <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
              <i class="fas fa-check-circle text-green-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= count(array_filter($reports, fn($r) => $r['status'] === 'Generated')) ?>
          </div>
          <p class="text-xs text-gray-500">Available for download</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Downloads</h3>
            <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
              <i class="fas fa-download text-blue-600"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2">
            <?= number_format(array_sum(array_column($reports, 'downloads'))) ?>
          </div>
          <p class="text-xs text-gray-500">All time</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Avg. Size</h3>
            <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
              <i class="fas fa-weight text-purple-600"></i>
            </div>
          </div>
          <?php
          $sizes = array_filter(array_column($reports, 'size'), fn($s) => $s !== 'N/A');
          $avgSize = count($sizes) > 0 ? array_sum(array_map(fn($s) => (float) str_replace(' MB', '', $s), $sizes)) / count($sizes) : 0;
          ?>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= number_format($avgSize, 1) ?> MB</div>
          <p class="text-xs text-gray-500">Per report</p>
        </div>
      </div>

      <!-- Charts and Templates -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Report Types Chart -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Types Distribution</h3>
          <div class="chart-container">
            <canvas id="reportsChart"></canvas>
          </div>
        </div>

        <!-- Report Templates -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Templates</h3>
          <div class="grid grid-cols-2 gap-3">
            <?php foreach ($templates as $index => $template): ?>
            <div class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors">
              <div class="flex items-center gap-3 mb-2">
                <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-<?= htmlspecialchars($template['icon']) ?> text-white"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($template['name']) ?></h4>
                </div>
              </div>
              <p class="text-xs text-gray-600 mb-3"><?= htmlspecialchars($template['description']) ?></p>
              <button onclick="previewTemplate(<?= $index ?>)" class="w-full py-1.5 text-xs rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                Preview & Use Template
              </button>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Reports Table -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">All Reports</h3>
          <div class="flex items-center gap-3">
            <select id="reportFilter" class="p-2 rounded-xl border border-amber-200 bg-white text-sm">
              <option value="">All Report Types</option>
              <option value="Package">Package</option>
              <option value="Campaign">Campaign</option>
              <option value="Partnership">Partnership</option>
              <option value="Feedback">Feedback</option>
              <option value="Email">Email</option>
              <option value="Forecast">Forecast</option>
              <option value="Social Media">Social Media</option>
            </select>
            <input type="text" placeholder="Search reports..." class="p-2 rounded-xl border border-amber-200 bg-white text-sm w-48">
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-amber-100">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Report Title</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Type</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Period</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Size</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Date</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Downloads</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
              </tr>
            </thead>
            <tbody id="reportsTableBody">
              <?php foreach ($reports as $report): ?>
              <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors report-row" data-type="<?= htmlspecialchars($report['type']) ?>">
                <td class="py-3 px-4">
                  <div class="font-medium text-gray-900"><?= htmlspecialchars($report['title']) ?></div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getTypeClasses($report['type']) ?>">
                    <?= htmlspecialchars($report['type']) ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($report['period']) ?></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($report['status']) ?>">
                    <?= htmlspecialchars($report['status']) ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($report['size']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($report['date']) ?></td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-download text-gray-400 text-xs"></i>
                    <span class="text-sm text-gray-700"><?= $report['downloads'] ?></span>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-2">
                    <?php if ($report['status'] === 'Generated'): ?>
                    <button class="p-1 text-gray-600 hover:text-amber-600" title="Download">
                      <i class="fas fa-download"></i>
                    </button>
                    <?php endif; ?>
                    <button class="p-1 text-gray-600 hover:text-blue-600" title="Preview">
                      <i class="fas fa-eye"></i>
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
        </form>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-amber-100 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid gap-8 md:grid-cols-4 mb-8">
        <!-- Footer Logo -->
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

    function closeTemplateModal() {
      document.getElementById('templateModal').classList.remove('active');
    }

    // Scroll to report generator
    function scrollToGenerator() {
      document.getElementById('reportGenerator').scrollIntoView({ behavior: 'smooth' });
    }

    // Time period toggle
    document.addEventListener('DOMContentLoaded', function() {
      const timePeriodSelect = document.querySelector('select[name="time_period"]');
      const customDateRange = document.getElementById('customDateRange');
      
      if (timePeriodSelect && customDateRange) {
        timePeriodSelect.addEventListener('change', function() {
          if (this.value === 'custom') {
            customDateRange.classList.remove('hidden');
          } else {
            customDateRange.classList.add('hidden');
          }
        });
      }

      // Report filtering
      const reportFilter = document.getElementById('reportFilter');
      if (reportFilter) {
        reportFilter.addEventListener('change', function() {
          const filterValue = this.value.toLowerCase();
          const rows = document.querySelectorAll('.report-row');
          
          rows.forEach(row => {
            const type = row.getAttribute('data-type').toLowerCase();
            if (!filterValue || type === filterValue.toLowerCase()) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      }

      // Check if we need to open modal
      if (window.location.hash === '#templateModal') {
        setTimeout(() => {
          document.getElementById('templateModal').classList.add('active');
        }, 100);
      }
    });

    // Reports Chart
    window.addEventListener('load', function() {
      const reportsCtx = document.getElementById('reportsChart')?.getContext('2d');
      if (reportsCtx) {
        new Chart(reportsCtx, {
          type: 'doughnut',
          data: {
            labels: ['Package', 'Campaign', 'Partnership', 'Feedback', 'Email', 'Social Media'],
            datasets: [{
              data: [2, 1, 1, 1, 1, 1],
              backgroundColor: [
                '#3b82f6', // Blue for Package
                '#8b5cf6', // Purple for Campaign
                '#10b981', // Green for Partnership
                '#f59e0b', // Amber for Feedback
                '#8b5cf6', // Indigo for Email
                '#ec4899'  // Pink for Social Media
              ],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });
      }

      // Mobile menu functionality (from your original code)
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
    });
  </script>
</body>
</html>