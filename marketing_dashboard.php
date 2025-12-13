<?php
// marketing_manager_dashboard.php
// Premium TravelEase Marketing Manager Dashboard with ALL Features

// Start session for authentication
session_start();

// Basic authentication check (you can remove or modify this)
if (!isset($_SESSION['marketing_logged_in'])) {
    // For demo purposes, auto-login
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
    $_SESSION['profile_image'] = '';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';

// Profile image from session
$profileImage = !empty($_SESSION['profile_image'])
    ? 'uploads/profile/' . $_SESSION['profile_image']
    : 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';

// Placeholder metrics
$totalCampaigns = 12;
$activeCampaigns = 4;
$leadsThisMonth = 892;

// Campaign data for tables
$campaigns = [
    [
        'name' => 'Summer Asia Promotion',
        'status' => 'Active',
        'budget' => 25000,
        'spent' => 18450,
        'leads' => 1248,
        'roi' => '4.2x'
    ],
    [
        'name' => 'Luxury Japan Getaway',
        'status' => 'Active',
        'budget' => 15000,
        'spent' => 12800,
        'leads' => 892,
        'roi' => '3.8x'
    ],
    [
        'name' => 'Bali Wellness Retreat',
        'status' => 'Paused',
        'budget' => 12000,
        'spent' => 8200,
        'leads' => 567,
        'roi' => '2.9x'
    ],
    [
        'name' => 'Thailand Island Hopping',
        'status' => 'Completed',
        'budget' => 18000,
        'spent' => 17500,
        'leads' => 1102,
        'roi' => '3.5x'
    ]
];

// Active campaigns data
$activeCampaignsData = [
    [
        'name' => 'Summer Asia Promotion',
        'status' => 'On Track',
        'description' => 'Multi-channel campaign targeting luxury travelers',
        'progress' => 74,
        'spent' => 18450,
        'budget' => 25000
    ],
    [
        'name' => 'Luxury Japan Getaway',
        'status' => 'On Track',
        'description' => 'Social media and influencer campaign',
        'progress' => 85,
        'spent' => 12800,
        'budget' => 15000
    ],
    [
        'name' => 'Autumn Retreats',
        'status' => 'Starting Soon',
        'description' => 'Email and content marketing campaign',
        'progress' => 15,
        'spent' => 1500,
        'budget' => 10000
    ]
];

// Upcoming campaigns
$upcomingCampaigns = [
    [
        'name' => 'Winter Luxury Escapes',
        'start_date' => 'Nov 15, 2024',
        'status' => 'Planned'
    ],
    [
        'name' => 'Spring Festival Tours',
        'start_date' => 'Feb 1, 2025',
        'status' => 'Planned'
    ]
];

// Recent leads data
$recentLeads = [
    [
        'name' => 'Jennifer Wilson',
        'email' => 'j.wilson@email.com',
        'source' => 'Website',
        'interest' => 'Japan Luxury',
        'status' => 'New',
        'date' => 'Today'
    ],
    [
        'name' => 'Robert Chen',
        'email' => 'r.chen@email.com',
        'source' => 'Social Media',
        'interest' => 'Bali Retreat',
        'status' => 'Contacted',
        'date' => '2 days ago'
    ],
    [
        'name' => 'Maria Rodriguez',
        'email' => 'm.rodriguez@email.com',
        'source' => 'Referral',
        'interest' => 'Thailand Islands',
        'status' => 'Qualified',
        'date' => '3 days ago'
    ],
    [
        'name' => 'James Thompson',
        'email' => 'j.thompson@email.com',
        'source' => 'Email Campaign',
        'interest' => 'Vietnam Culture',
        'status' => 'Hot Lead',
        'date' => '5 days ago'
    ]
];

// Top performing content
$topContent = [
    [
        'title' => 'Japan Luxury Guide',
        'type' => 'Blog Post',
        'views' => 2458
    ],
    [
        'title' => 'Bali Retreat Video',
        'type' => 'Video',
        'views' => 1892
    ],
    [
        'title' => 'Thailand Islands',
        'type' => 'Instagram Post',
        'engagements' => 1567
    ],
    [
        'title' => 'Vietnam Culture Guide',
        'type' => 'Blog Post',
        'views' => 1234
    ]
];

// Geographic performance
$geoPerformance = [
    ['region' => 'North America', 'percentage' => 42],
    ['region' => 'Europe', 'percentage' => 28],
    ['region' => 'Asia Pacific', 'percentage' => 18],
    ['region' => 'Other Regions', 'percentage' => 12]
];

// Report templates
$reportTemplates = [
    [
        'title' => 'Monthly Performance',
        'description' => 'Comprehensive monthly marketing report',
        'icon' => 'chart-line'
    ],
    [
        'title' => 'Campaign ROI Analysis',
        'description' => 'Detailed campaign performance and ROI',
        'icon' => 'bullseye'
    ],
    [
        'title' => 'Lead Generation Report',
        'description' => 'Lead sources and conversion metrics',
        'icon' => 'users'
    ]
];

// Marketing features
$marketingFeatures = [
    [
        'title' => 'Manage Promotional Content',
        'description' => 'Create and manage promotional banners, ads, and marketing materials.',
        'icon' => 'megaphone',
        'link' => '#',
        'action_text' => 'Access Tool'
    ],
    [
        'title' => 'Write Package Descriptions',
        'description' => 'Create compelling descriptions for travel packages and experiences.',
        'icon' => 'edit',
        'link' => '#',
        'action_text' => 'Access Tool'
    ],
    [
        'title' => 'Add New Packages',
        'description' => 'Add new travel packages with images, pricing, and inclusions.',
        'icon' => 'box-open',
        'link' => '#',
        'action_text' => 'Add Package'
    ],
    [
        'title' => 'Promotional Offers',
        'description' => 'Set up special promotions, discounts, and limited-time offers.',
        'icon' => 'tags',
        'link' => '#',
        'action_text' => 'Manage Offers'
    ],
    [
        'title' => 'Marketing Campaigns',
        'description' => 'Create and schedule multi-channel marketing campaigns.',
        'icon' => 'bullhorn',
        'link' => '#',
        'action_text' => 'Create Campaign'
    ],
    [
        'title' => 'Discount Codes',
        'description' => 'Generate and manage discount codes for promotions.',
        'icon' => 'percentage',
        'link' => '#',
        'action_text' => 'Create Codes'
    ],
    [
        'title' => 'Email Newsletters',
        'description' => 'Design and send beautiful email newsletters to your subscribers.',
        'icon' => 'envelope',
        'link' => '#',
        'action_text' => 'Design Newsletter',
        'span_cols' => true,
        'extra_info' => '12,450 subscribers'
    ]
];

// Quick actions
$quickActions = [
    ['text' => 'Add New Package', 'icon' => 'plus', 'link' => '#'],
    ['text' => 'Launch Campaign', 'icon' => 'rocket', 'link' => '#'],
    ['text' => 'Generate Discount Code', 'icon' => 'tag', 'link' => '#'],
    ['text' => 'Send Newsletter', 'icon' => 'paper-plane', 'link' => '#']
];

// Campaign planning quick actions
$campaignQuickActions = [
    ['text' => 'New Campaign', 'icon' => 'plus', 'link' => '#'],
    ['text' => 'Generate Report', 'icon' => 'chart-bar', 'link' => '#'],
    ['text' => 'Audience Insights', 'icon' => 'users', 'link' => '#'],
    ['text' => 'Promote Content', 'icon' => 'bullhorn', 'link' => '#']
];

// Lead statistics
$leadStats = [
    ['label' => 'Total Leads', 'value' => '3,842', 'class' => 'text-gray-900'],
    ['label' => 'New This Month', 'value' => $leadsThisMonth, 'class' => 'text-amber-600'],
    ['label' => 'Conversion Rate', 'value' => '4.8%', 'class' => 'text-green-600'],
    ['label' => 'Avg. Response Time', 'value' => '2.4 hrs', 'class' => 'text-gray-900'],
    ['label' => 'Hot Leads', 'value' => '124', 'class' => 'text-red-600']
];

// Footer links
$footerLinks = [
    'Marketing Features' => [
        ['text' => 'All Features', 'link' => '#features'],
        ['text' => 'Add Packages', 'link' => '#'],
        ['text' => 'Campaigns', 'link' => '#'],
        ['text' => 'Discount Codes', 'link' => '#'],
        ['text' => 'Email Newsletters', 'link' => '#']
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

// Helper function to get status classes
function getStatusClasses($status) {
    $classes = [
        'Active' => 'bg-green-100 text-green-800',
        'On Track' => 'bg-green-100 text-green-800',
        'Paused' => 'bg-yellow-100 text-yellow-800',
        'Completed' => 'bg-blue-100 text-blue-800',
        'Planned' => 'bg-blue-100 text-blue-800',
        'Starting Soon' => 'bg-yellow-100 text-yellow-800',
        'New' => 'bg-green-100 text-green-800',
        'Contacted' => 'bg-blue-100 text-blue-800',
        'Qualified' => 'bg-purple-100 text-purple-800',
        'Hot Lead' => 'bg-red-100 text-red-800'
    ];
    
    return $classes[$status] ?? 'bg-gray-100 text-gray-800';
}

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
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center">
              <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center font-black text-amber-600 text-xs">TE</div>
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
          <a href="#features" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-cogs w-6 text-center"></i>
            Marketing Features
          </a>
          <a href="#campaigns" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-bullhorn w-6 text-center"></i>
            Campaigns
          </a>
          <a href="#leads" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Leads
          </a>
          <a href="#reports" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
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
        <div class="flex items-center gap-3">
          <a href="#" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <div class="w-full h-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <span class="text-white font-bold text-xl">TE</span>
              </div>
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
          <a href="#features" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-cogs text-xs text-amber-500 mr-2"></i>
            Features
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#campaigns" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
            Campaigns
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#leads" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-amber-500 mr-2"></i>
            Leads
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#reports" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
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
            <a href="#"
               class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
              <i class="fas fa-plus mr-2"></i> New Campaign
            </a>
            <a href="#"
               class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl border-2 border-amber-500 text-amber-700 hover:bg-amber-50 transition-all">
              <i class="fas fa-box-open mr-2"></i> New Package
            </a>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Total Campaigns</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-bullhorn text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $totalCampaigns ?></div>
          <p class="text-xs text-gray-500">Created in TravelEase</p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold stat-card">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600">Active Campaigns</h3>
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-play-circle text-white"></i>
            </div>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-2"><?= $activeCampaigns ?></div>
          <p class="text-xs text-gray-500">Currently running</p>
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
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col lg:col-span-2 border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Campaign Management</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Marketing</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            Create, edit and monitor your marketing campaigns across digital & offline channels.
          </p>
          <div class="mt-auto flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
            <a href="#"
               class="flex-1 text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View Campaigns
            </a>
            <a href="#"
               class="flex-1 text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              New Campaign
            </a>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Leads & Conversions</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Leads</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            Track inquiries, follow up potential travelers, and see which campaigns convert into bookings.
          </p>
          <div class="mt-auto flex flex-col space-y-3">
            <a href="#"
               class="text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View Leads
            </a>
            <a href="#"
               class="text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              Campaign Bookings
            </a>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col border border-amber-100 hover-lift">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Performance & Reports</h2>
            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Analytics</span>
          </div>
          <p class="text-sm text-gray-500 mb-4">
            Compare channels, measure ROI, and export basic marketing performance reports.
          </p>
          <div class="mt-auto flex flex-col space-y-3">
            <a href="#"
               class="text-center text-sm font-medium py-2.5 rounded-xl bg-primary-500 text-white hover:bg-primary-600 transition">
              View Reports
            </a>
            <a href="#"
               class="text-center text-sm font-medium py-2.5 rounded-xl border border-primary-300 text-primary-700 hover:bg-primary-50 transition">
              Campaign Insights
            </a>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trends</h3>
          <div class="chart-container">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Sources</h3>
          <div class="chart-container">
            <canvas id="trafficChart"></canvas>
          </div>
        </div>
      </div>

      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Campaign Performance</h3>
          <a href="#" class="px-4 py-2 rounded-xl gold-gradient text-white text-sm font-semibold hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i> New Campaign
          </a>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-amber-100">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Campaign</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Budget</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Spent</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Leads</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">ROI</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($campaigns as $campaign): ?>
              <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors">
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($campaign['name']) ?></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($campaign['status']) ?>">
                    <?= htmlspecialchars($campaign['status']) ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-700">$<?= number_format($campaign['budget']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700">$<?= number_format($campaign['spent']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= number_format($campaign['leads']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700 font-semibold text-green-600"><?= htmlspecialchars($campaign['roi']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <section id="features" class="py-12 bg-gradient-to-b from-amber-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-black mb-6">
          <span class="text-gray-900">Marketing</span>
          <span class="text-gradient block">Features</span>
        </h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
          All your marketing tools in one place - manage campaigns, packages, promotions and more.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($marketingFeatures as $index => $feature): ?>
        <?php if ($index === 6): ?>
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold feature-card lg:col-span-2" onclick="window.location.href='<?= htmlspecialchars($feature['link']) ?>'">
          <div class="flex items-start gap-4 mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
              <i class="fas fa-<?= htmlspecialchars($feature['icon']) ?> text-white text-lg"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-1"><?= htmlspecialchars($feature['title']) ?></h3>
              <p class="text-sm text-gray-600"><?= htmlspecialchars($feature['description']) ?></p>
            </div>
          </div>
          <div class="flex justify-between items-center">
            <?php if (isset($feature['extra_info'])): ?>
            <div class="text-sm text-gray-500">
              <i class="fas fa-users mr-1"></i> <?= htmlspecialchars($feature['extra_info']) ?>
            </div>
            <?php endif; ?>
            <span class="inline-flex items-center text-sm font-medium text-amber-600">
              <?= htmlspecialchars($feature['action_text']) ?> <i class="fas fa-arrow-right ml-2"></i>
            </span>
          </div>
        </div>
        <?php else: ?>
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold feature-card" onclick="window.location.href='<?= htmlspecialchars($feature['link']) ?>'">
          <div class="flex items-start gap-4 mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
              <i class="fas fa-<?= htmlspecialchars($feature['icon']) ?> text-white text-lg"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-1"><?= htmlspecialchars($feature['title']) ?></h3>
              <p class="text-sm text-gray-600"><?= htmlspecialchars($feature['description']) ?></p>
            </div>
          </div>
          <div class="flex justify-end">
            <span class="inline-flex items-center text-sm font-medium text-amber-600">
              <?= htmlspecialchars($feature['action_text']) ?> <i class="fas fa-arrow-right ml-2"></i>
            </span>
          </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
          <div class="space-y-3">
            <?php foreach ($quickActions as $action): ?>
            <a href="<?= htmlspecialchars($action['link']) ?>" class="flex items-center justify-between p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors">
              <span class="font-medium text-gray-900"><?= htmlspecialchars($action['text']) ?></span>
              <i class="fas fa-<?= htmlspecialchars($action['icon']) ?> text-amber-600"></i>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="campaigns" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-black mb-6">
          <span class="text-gray-900">Campaign</span>
          <span class="text-gradient block">Management</span>
        </h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
          Manage and track all your marketing campaigns in one place.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Active Campaigns</h3>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-semibold"><?= count($activeCampaignsData) ?> Active</span>
          </div>
          
          <div class="space-y-4">
            <?php foreach ($activeCampaignsData as $campaign): ?>
            <div class="p-4 rounded-xl border border-amber-200 bg-white">
              <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($campaign['name']) ?></h4>
                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($campaign['status']) ?>">
                  <?= htmlspecialchars($campaign['status']) ?>
                </span>
              </div>
              <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($campaign['description']) ?></p>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Progress: <?= $campaign['progress'] ?>%</span>
                <span class="font-semibold text-amber-600">$<?= number_format($campaign['spent']) ?> / $<?= number_format($campaign['budget']) ?></span>
              </div>
              <div class="progress-bar mt-2">
                <div class="progress-fill" style="width: <?= $campaign['progress'] ?>%"></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Campaign Planning</h3>
          
          <div class="space-y-6">
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Upcoming Campaigns</h4>
              <div class="space-y-3">
                <?php foreach ($upcomingCampaigns as $campaign): ?>
                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50">
                  <div>
                    <h5 class="font-medium text-gray-900"><?= htmlspecialchars($campaign['name']) ?></h5>
                    <p class="text-xs text-gray-600">Starts: <?= htmlspecialchars($campaign['start_date']) ?></p>
                  </div>
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($campaign['status']) ?>">
                    <?= htmlspecialchars($campaign['status']) ?>
                  </span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Quick Actions</h4>
              <div class="grid grid-cols-2 gap-3">
                <?php foreach ($campaignQuickActions as $action): ?>
                <a href="<?= htmlspecialchars($action['link']) ?>" class="p-3 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-center">
                  <i class="fas fa-<?= htmlspecialchars($action['icon']) ?> text-amber-500 mb-1 block text-lg"></i>
                  <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($action['text']) ?></span>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="leads" class="py-12 bg-gradient-to-b from-white to-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-black mb-6">
          <span class="text-gray-900">Lead</span>
          <span class="text-gradient block">Management</span>
        </h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
          Track and manage your marketing leads through the conversion funnel.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold lg:col-span-2">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Lead Conversion Funnel</h3>
          <div class="chart-container">
            <canvas id="funnelChart"></canvas>
          </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Lead Statistics</h3>
          <div class="space-y-4">
            <?php foreach ($leadStats as $stat): ?>
            <div class="flex items-center justify-between">
              <span class="text-gray-700"><?= htmlspecialchars($stat['label']) ?></span>
              <span class="font-semibold <?= htmlspecialchars($stat['class']) ?>"><?= htmlspecialchars($stat['value']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Recent Leads</h3>
          <a href="#" class="px-4 py-2 rounded-xl gold-gradient text-white text-sm font-semibold hover:shadow-lg transition-all">
            <i class="fas fa-eye mr-2"></i> View All Leads
          </a>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-amber-100">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Name</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Email</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Source</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Interest</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentLeads as $lead): ?>
              <tr class="border-b border-amber-50 hover:bg-amber-50 transition-colors">
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($lead['name']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($lead['email']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($lead['source']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($lead['interest']) ?></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 rounded-full text-xs font-semibold <?= getStatusClasses($lead['status']) ?>">
                    <?= htmlspecialchars($lead['status']) ?>
                  </span>
                </td>
                <td class="py-3 px-4 text-sm text-gray-700"><?= htmlspecialchars($lead['date']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <section id="reports" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl font-black mb-6">
          <span class="text-gray-900">Marketing</span>
          <span class="text-gradient block">Reports</span>
        </h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
          Generate and access comprehensive marketing performance reports.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Report Templates</h3>
          
          <div class="space-y-4">
            <?php foreach ($reportTemplates as $report): ?>
            <div class="flex items-center justify-between p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-<?= htmlspecialchars($report['icon']) ?> text-white"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($report['title']) ?></h4>
                  <p class="text-sm text-gray-600"><?= htmlspecialchars($report['description']) ?></p>
                </div>
              </div>
              <button class="p-2 rounded-lg text-amber-600 hover:bg-amber-100 transition-colors">
                <i class="fas fa-download"></i>
              </button>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Generate Custom Report</h3>
          
          <form class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
              <select class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option>Performance Overview</option>
                <option>Campaign Analysis</option>
                <option>Lead Generation</option>
                <option>Package Performance</option>
                <option>Promotional ROI</option>
              </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Metrics to Include</label>
              <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                  <label class="ml-2 text-sm text-gray-700">Revenue</label>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                  <label class="ml-2 text-sm text-gray-700">Leads</label>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                  <label class="ml-2 text-sm text-gray-700">Website Traffic</label>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                  <label class="ml-2 text-sm text-gray-700">Conversion Rates</label>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                  <label class="ml-2 text-sm text-gray-700">Package Sales</label>
                </div>
                <div class="flex items-center">
                  <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                  <label class="ml-2 text-sm text-gray-700">Promo Usage</label>
                </div>
              </div>
            </div>
            
            <button type="submit" class="w-full py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
              <i class="fas fa-file-export mr-2"></i> Generate Report
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col md:flex-row md:items-center md:justify-between border border-amber-100">
      <div>
        <h2 class="text-sm font-semibold text-gray-800 mb-1">My Profile & Account</h2>
        <p class="text-sm text-gray-500">
          Update your name, email, password and profile picture on the profile settings page.
        </p>
      </div>
      <div class="mt-3 md:mt-0">
        <a href="marketing_profile.php"
           class="inline-flex items-center text-sm font-medium px-4 py-2 rounded-xl gold-gradient text-white hover:shadow-lg transition-all">
          Go to Profile Settings
        </a>
      </div>
    </div>
  </div>

  <footer class="border-t border-amber-100 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid gap-8 md:grid-cols-4 mb-8">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <div class="h-6 w-6 rounded-lg bg-white flex items-center justify-center font-black text-amber-600 text-xs">TE</div>
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