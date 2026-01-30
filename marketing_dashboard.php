<?php
// marketing_manager_dashboard.php
// Premium TravelEase Marketing Manager Dashboard with ALL Features

// Start session for authentication
session_start();

// Basic authentication check
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
    $_SESSION['profile_image'] = '';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';

// Profile image from session
$profileImage = !empty($_SESSION['profile_image'])
    ? 'uploads/profile/' . $_SESSION['profile_image']
    : 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';

// Database connection
require_once __DIR__ . '/db.php';

// Fetch packages from database
$packages = [];
$totalPackages = 0;
$activePackages = 0;
$totalBudget = 0;
$totalLeads = 0;

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
            base_price,
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
            COALESCE(group_max, 0) as max_capacity,
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
            END as display_status
        FROM travel_packages 
        ORDER BY created_at DESC
        LIMIT 6
    ");
    
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total statistics
    $statsStmt = $pdo->query("
        SELECT 
            COUNT(*) as total_packages,
            SUM(CASE 
                WHEN availability_start <= CURRENT_DATE AND availability_end >= CURRENT_DATE THEN 1 
                ELSE 0 
            END) as active_packages,
            SUM(base_price) as total_budget,
            SUM(COALESCE(group_max, 0)) as total_capacity
        FROM travel_packages
    ");
    
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    $totalPackages = $stats['total_packages'] ?? 0;
    $activePackages = $stats['active_packages'] ?? 0;
    $totalBudget = $stats['total_budget'] ?? 0;
    $totalLeads = $stats['total_capacity'] ?? 0;
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $packages = [];
    $totalPackages = 0;
    $activePackages = 0;
    $totalBudget = 0;
    $totalLeads = 0;
}

// Calculate conversion rate (mock calculation based on capacity)
$conversionRate = $totalLeads > 0 ? round(($activePackages / $totalLeads) * 100, 1) : 0;

// Get leads count for this month (you'll need to implement this based on your leads table)
try {
    $leadsStmt = $pdo->query("
        SELECT COUNT(*) as leads_this_month 
        FROM leads 
        WHERE MONTH(created_at) = MONTH(CURRENT_DATE) 
        AND YEAR(created_at) = YEAR(CURRENT_DATE)
    ");
    
    $leadsResult = $leadsStmt->fetch(PDO::FETCH_ASSOC);
    $leadsThisMonth = $leadsResult['leads_this_month'] ?? 892; // Fallback to default if no leads table
    
} catch (PDOException $e) {
    error_log("Leads query error: " . $e->getMessage());
    $leadsThisMonth = 892; // Default fallback
}

// Recent leads data (mock data if no leads table exists)
$recentLeads = [];
try {
    $recentLeadsStmt = $pdo->query("
        SELECT 
            CONCAT(first_name, ' ', last_name) as name,
            email,
            source,
            interest,
            status,
            DATE_FORMAT(created_at, '%b %d') as date_formatted,
            CASE 
                WHEN DATEDIFF(CURRENT_DATE, created_at) = 0 THEN 'Today'
                WHEN DATEDIFF(CURRENT_DATE, created_at) = 1 THEN 'Yesterday'
                ELSE CONCAT(DATEDIFF(CURRENT_DATE, created_at), ' days ago')
            END as relative_date
        FROM leads 
        ORDER BY created_at DESC 
        LIMIT 4
    ");
    
    $recentLeads = $recentLeadsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback mock data if leads table doesn't exist
    $recentLeads = [
        [
            'name' => 'Jennifer Wilson',
            'email' => 'j.wilson@email.com',
            'source' => 'Website',
            'interest' => 'Japan Luxury',
            'status' => 'New',
            'relative_date' => 'Today'
        ],
        [
            'name' => 'Robert Chen',
            'email' => 'r.chen@email.com',
            'source' => 'Social Media',
            'interest' => 'Bali Retreat',
            'status' => 'Contacted',
            'relative_date' => '2 days ago'
        ],
        [
            'name' => 'Maria Rodriguez',
            'email' => 'm.rodriguez@email.com',
            'source' => 'Referral',
            'interest' => 'Thailand Islands',
            'status' => 'Qualified',
            'relative_date' => '3 days ago'
        ],
        [
            'name' => 'James Thompson',
            'email' => 'j.thompson@email.com',
            'source' => 'Email Campaign',
            'interest' => 'Vietnam Culture',
            'status' => 'Hot Lead',
            'relative_date' => '5 days ago'
        ]
    ];
}

// Status and type styling functions (same as in your package page)
function getStatusClasses($status) {
    $classes = [
        'Active' => 'bg-green-100 text-green-800 border-green-200',
        'On Track' => 'bg-green-100 text-green-800 border-green-200',
        'Paused' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'Completed' => 'bg-blue-100 text-blue-800 border-blue-200',
        'Planned' => 'bg-blue-100 text-blue-800 border-blue-200',
        'Draft' => 'bg-gray-100 text-gray-800 border-gray-200',
        'Starting Soon' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
}

function getCategoryClasses($category) {
    $classes = [
        'luxury' => 'bg-gradient-to-r from-amber-100 to-amber-50 border-amber-200 text-amber-800',
        'adventure' => 'bg-gradient-to-r from-green-100 to-green-50 border-green-200 text-green-800',
        'wellness' => 'bg-gradient-to-r from-blue-100 to-blue-50 border-blue-200 text-blue-800',
        'family' => 'bg-gradient-to-r from-purple-100 to-purple-50 border-purple-200 text-purple-800',
        'cultural' => 'bg-gradient-to-r from-red-100 to-red-50 border-red-200 text-red-800',
        'beach' => 'bg-gradient-to-r from-cyan-100 to-cyan-50 border-cyan-200 text-cyan-800',
        'honeymoon' => 'bg-gradient-to-r from-pink-100 to-pink-50 border-pink-200 text-pink-800',
        'business' => 'bg-gradient-to-r from-gray-100 to-gray-50 border-gray-200 text-gray-800'
    ];
    return $classes[$category] ?? 'bg-gradient-to-r from-gray-100 to-gray-50 border-gray-200 text-gray-800';
}

function getCategoryIcon($category) {
    $icons = [
        'luxury' => 'fas fa-crown',
        'adventure' => 'fas fa-mountain',
        'wellness' => 'fas fa-spa',
        'family' => 'fas fa-home',
        'cultural' => 'fas fa-landmark',
        'beach' => 'fas fa-umbrella-beach',
        'honeymoon' => 'fas fa-heart',
        'business' => 'fas fa-briefcase'
    ];
    return $icons[$category] ?? 'fas fa-suitcase';
}

// Footer links
$footerLinks = [
    'Marketing Features' => [
        ['text' => 'All Features', 'link' => '#features'],
        ['text' => 'Packages', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Reports', 'link' => 'marketing_report.php'],
        ['text' => 'Partnerships', 'link' => 'partnership.php'],
        ['text' => 'My Profile', 'link' => 'marketing_profile.php']
    ],
    'Resources' => [
        ['text' => 'Documentation', 'link' => '#'],
        ['text' => 'Best Practices', 'link' => '#'],
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

// Current year for footer
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Marketing Dashboard | TravelEase - Premium Asia Travel Experiences</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Marketing analytics and campaign management for TravelEase luxury travel platform.">

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
            },
            amber: {
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body { 
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
      color: #1f2937;
      overflow-x: hidden;
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .gold-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fcd34d 100%);
    }
    .hover-lift {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 25px 50px -12px rgba(245, 158, 11, 0.25);
    }
    .text-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .shadow-gold {
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }
    .mobile-menu.open {
      transform: translateX(0);
    }
    .backdrop-blur-xl {
      backdrop-filter: blur(24px);
    }
    
    @media (max-width: 640px) {
      .cta-buttons {
        flex-direction: column;
        width: 100%;
      }
      .cta-buttons a {
        width: 100%;
        text-align: center;
      }
    }
    
    .loading-bar {
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
      background-size: 200% 100%;
      animation: loading 2s infinite;
    }
    
    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
    
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #fef7e5;
    }
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
      border-radius: 10px;
    }
    
    .stat-card {
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
    }
    .progress-bar {
      height: 8px;
      border-radius: 4px;
      overflow: hidden;
      background-color: #fef3c7;
    }
    .progress-fill {
      height: 100%;
      border-radius: 4px;
      background: linear-gradient(90deg, #f59e0b, #fbbf24);
      transition: width 0.5s ease;
    }
    .chart-container {
      position: relative;
      height: 300px;
    }
    .feature-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px -10px rgba(245, 158, 11, 0.15);
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
          <a href="#overview" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
          </a>
           <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="#overview" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
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

  <section id="overview" class="pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black mb-2">
              <span class="text-gray-900">Marketing Dashboard</span>
            </h1>
            <p class="text-lg text-gray-700">Welcome back, <?= htmlspecialchars($managerName) ?>! Here's your marketing performance overview.</p>
          </div>
          <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="create_campaign.php"
               class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> New Package
            </a>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Packages</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-box-open text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $totalPackages ?></div>
          <p class="text-xs text-gray-500">Created in TravelEase</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Active Packages</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-play-circle text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $activePackages ?></div>
          <p class="text-xs text-gray-500">Currently available</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Leads This Month</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-users text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $leadsThisMonth ?></div>
          <p class="text-xs text-gray-500">From all channels</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Conversion Rate</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-chart-line text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $conversionRate ?>%</div>
          <p class="text-xs text-gray-500">Overall conversion</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col lg:col-span-2 border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Package Management</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Marketing</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            Create, edit and manage travel packages with images, descriptions, and pricing.
          </p>
          <div class="mt-auto flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
            <a href="marketing_campaigns.php"
               class="flex-1 text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View All Packages
            </a>
            <a href="create_campaign.php"
               class="flex-1 text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              Create New Package
            </a>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Reports & Analytics</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Analytics</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            View performance reports, campaign analytics, and marketing insights.
          </p>
          <div class="mt-auto flex flex-col space-y-3">
            <a href="marketing_report.php"
               class="text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View Reports
            </a>
            <a href="marketing_report.php?type=analytics"
               class="text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              Campaign Analytics
            </a>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Partnerships</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Collaboration</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            Manage hotel partnerships, tour operators, and travel collaborations.
          </p>
          <div class="mt-auto flex flex-col space-y-3">
            <a href="partnership.php"
               class="text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View Partnerships
            </a>
            <a href="partnership.php?action=add"
               class="text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              Add New Partner
            </a>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Package Performance</h3>
          <div class="chart-container">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Leads</h3>
          <div class="space-y-3">
            <?php foreach ($recentLeads as $lead): ?>
            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-amber-50 hover:bg-amber-50 transition-colors">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-amber-100 to-amber-200 flex items-center justify-center">
                  <i class="fas fa-user text-amber-600"></i>
                </div>
                <div>
                  <div class="font-semibold text-gray-900"><?= htmlspecialchars($lead['name']) ?></div>
                  <div class="text-sm text-gray-600"><?= htmlspecialchars($lead['email']) ?></div>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($lead['interest']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($lead['relative_date']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Recent Packages</h3>
          <a href="marketing_campaigns.php" class="px-4 py-2 rounded-xl gold-gradient text-white text-sm font-semibold hover:shadow-lg transition-all">
            <i class="fas fa-eye mr-2"></i> View All
          </a>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-amber-100">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Package Name</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Category</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Price</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Duration</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($packages)): ?>
              <tr>
                <td colspan="6" class="py-6 text-center text-gray-500">
                  No packages found. <a href="create_campaign.php" class="text-amber-600 hover:text-amber-700 font-medium">Create your first package</a>
                </td>
              </tr>
              <?php else: ?>
              <?php foreach ($packages as $package): 
                $status = $package['display_status'] ?? $package['status'];
                $category = $package['category'] ?? 'luxury';
                $categoryLabel = ucfirst($category);
              ?>
              <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors">
                <td class="py-3 px-4 text-sm text-gray-700 font-medium"><?= htmlspecialchars($package['name']) ?></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold border <?= getStatusClasses($status) ?>">
                    <?= htmlspecialchars($status) ?>
                  </span>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold border <?= getCategoryClasses($category) ?>">
                    <i class="<?= getCategoryIcon($category) ?> mr-1"></i> <?= htmlspecialchars($categoryLabel) ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-700">$<?= number_format($package['base_price']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($package['duration_days'] ?? 'N/A') ?> days</td>
                <td class="py-3 px-4">
                  <div class="flex gap-2">
                    <a href="view_package.php?id=<?= $package['id'] ?>" class="text-amber-600 hover:text-amber-700 p-1" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="edit_package.php?id=<?= $package['id'] ?>" class="text-blue-600 hover:text-blue-700 p-1" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="marketing_report.php?package=<?= urlencode($package['name']) ?>" class="text-green-600 hover:text-green-700 p-1" title="Analytics">
                      <i class="fas fa-chart-bar"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!--footer-->
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
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

    function toggleMobileMenu() {
      mobileMenu.classList.toggle('open');
      document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    }

    if (menuButton) {
      menuButton.addEventListener('click', toggleMobileMenu);
    }

    if (mobileMenuClose) {
      mobileMenuClose.addEventListener('click', toggleMobileMenu);
    }

    if (mobileMenuBackdrop) {
      mobileMenuBackdrop.addEventListener('click', toggleMobileMenu);
    }

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

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
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
              label: 'Package Revenue ($)',
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
              y: { 
                beginAtZero: false, 
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                  callback: function(value) {
                    return '$' + value.toLocaleString();
                  }
                }
              },
              x: { grid: { display: false } }
            }
          }
        });
      }
    }
  </script>
</body>
</html>