<?php
// partnership_collaboration.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Partnership types
$partnershipTypes = [
    'hotel' => 'Hotel Partnerships',
    'airline' => 'Airline Partnerships',
    'tour_operator' => 'Tour Operators',
    'influencer' => 'Influencers & Ambassadors',
    'tourism_board' => 'Tourism Boards',
    'travel_agent' => 'Travel Agents',
];

// Partnership statuses
$partnershipStatuses = [
    'active' => 'Active',
    'pending' => 'Pending Approval',
    'negotiation' => 'In Negotiation',
    'expired' => 'Expired',
    'terminated' => 'Terminated'
];

// Partnership tiers
$partnershipTiers = [
    'platinum' => 'Platinum Partner',
    'gold' => 'Gold Partner',
    'silver' => 'Silver Partner',
    'bronze' => 'Bronze Partner'
];

// Industries
$industries = [
    'hospitality' => 'Hospitality',
    'aviation' => 'Aviation',
    'tour_operator' => 'Tour Operators',
    'transportation' => 'Transportation',
    'entertainment' => 'Entertainment',
    'retail' => 'Retail',
    'media' => 'Media & Publishing',
    'technology' => 'Technology'
];

// Current partnerships data
$partnerships = [
    [
        'id' => 1,
        'name' => 'Luxury Hotels International',
        'type' => 'hotel',
        'tier' => 'platinum',
        'status' => 'active',
        'contact' => 'Sarah Johnson',
        'email' => 'sarah@luxuryhotels.com',
        'since' => '2022-03-15',
        'revenue_share' => '15%',
        'joint_promotions' => 8,
        'upcoming_campaigns' => 3
    ],
    [
        'id' => 2,
        'name' => 'Global Airlines',
        'type' => 'airline',
        'tier' => 'gold',
        'status' => 'active',
        'contact' => 'Michael Chen',
        'email' => 'michael@globalair.com',
        'since' => '2021-11-10',
        'revenue_share' => '12%',
        'joint_promotions' => 12,
        'upcoming_campaigns' => 2
    ],
    [
        'id' => 3,
        'name' => 'Adventure Tours Co.',
        'type' => 'tour_operator',
        'tier' => 'silver',
        'status' => 'negotiation',
        'contact' => 'Emma Wilson',
        'email' => 'emma@adventuretours.com',
        'since' => '2024-01-20',
        'revenue_share' => '20%',
        'joint_promotions' => 0,
        'upcoming_campaigns' => 1
    ],
    [
        'id' => 4,
        'name' => 'Bali Tourism Board',
        'type' => 'tourism_board',
        'tier' => 'gold',
        'status' => 'active',
        'contact' => 'David Brown',
        'email' => 'david@balitourism.org',
        'since' => '2023-06-05',
        'revenue_share' => 'N/A',
        'joint_promotions' => 5,
        'upcoming_campaigns' => 2
    ],
    [
        'id' => 5,
        'name' => 'Travel Influencer Network',
        'type' => 'influencer',
        'tier' => 'bronze',
        'status' => 'active',
        'contact' => 'Jessica Lee',
        'email' => 'jessica@travelinfluencers.com',
        'since' => '2023-09-12',
        'revenue_share' => '18%',
        'joint_promotions' => 6,
        'upcoming_campaigns' => 4
    ],
    [
        'id' => 6,
        'name' => 'European Cruise Lines',
        'type' => 'tour_operator',
        'tier' => 'platinum',
        'status' => 'pending',
        'contact' => 'Robert Garcia',
        'email' => 'robert@europeancruises.com',
        'since' => '2024-02-28',
        'revenue_share' => '22%',
        'joint_promotions' => 0,
        'upcoming_campaigns' => 0
    ]
];

// Upcoming campaigns
$upcomingCampaigns = [
    [
        'id' => 1,
        'name' => 'Summer Beach Promotion',
        'partners' => ['Luxury Hotels International', 'Global Airlines'],
        'start_date' => '2024-06-01',
        'end_date' => '2024-08-31',
        'budget' => 50000,
        'status' => 'planned'
    ],
    [
        'id' => 2,
        'name' => 'Winter Ski Package',
        'partners' => ['Adventure Tours Co.', 'Bali Tourism Board'],
        'start_date' => '2024-11-15',
        'end_date' => '2025-02-28',
        'budget' => 35000,
        'status' => 'draft'
    ],
    [
        'id' => 3,
        'name' => 'Luxury City Breaks',
        'partners' => ['Travel Influencer Network', 'European Cruise Lines'],
        'start_date' => '2024-09-01',
        'end_date' => '2024-12-31',
        'budget' => 42000,
        'status' => 'active'
    ]
];

$footerLinks = [
    'Partnership Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard.php'],
        ['text' => 'Partnerships', 'link' => 'partnership_collaboration.php'],
        ['text' => 'Joint Campaigns', 'link' => 'joint_campaigns.php'],
        ['text' => 'Affiliate Portal', 'link' => 'affiliate_portal.php']
    ],
    'Resources' => [
        ['text' => 'Partner Portal', 'link' => '#'],
        ['text' => 'Agreement Templates', 'link' => '#'],
        ['text' => 'Commission Calculator', 'link' => '#'],
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
    <title>Partnership & Collaboration Manager | TravelEase Marketing</title>
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
        .partnership-card {
            transition: all 0.3s ease;
        }
        .partnership-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #10b98120;
            color: #10b981;
        }
        .status-pending {
            background-color: #f59e0b20;
            color: #f59e0b;
        }
        .status-negotiation {
            background-color: #3b82f620;
            color: #3b82f6;
        }
        .status-expired {
            background-color: #6b728020;
            color: #6b7280;
        }
        .status-terminated {
            background-color: #ef444420;
            color: #ef4444;
        }
        .tier-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .tier-platinum {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
            color: #1f2937;
        }
        .tier-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            color: white;
        }
        .tier-silver {
            background: linear-gradient(135deg, #d1d5db 0%, #6b7280 100%);
            color: white;
        }
        .tier-bronze {
            background: linear-gradient(135deg, #92400e 0%, #78350f 100%);
            color: white;
        }
        .campaign-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .campaign-planned {
            background-color: #fef3c7;
            color: #92400e;
        }
        .campaign-draft {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .campaign-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .type-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .type-hotel {
            background-color: #fef3c7;
            color: #d97706;
        }
        .type-airline {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .type-tour_operator {
            background-color: #dcfce7;
            color: #15803d;
        }
        .type-influencer {
            background-color: #fae8ff;
            color: #a21caf;
        }
        .type-tourism_board {
            background-color: #f0f9ff;
            color: #0369a1;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            width: 90%;
            animation: modalSlideIn 0.3s ease-out;
        }
        /* Add to existing <style> section */
        .mobile-menu {
            transition: opacity 0.3s ease;
        }
        .mobile-menu:not(.hidden) {
            display: flex !important;
        }
        .mobile-menu .absolute.inset-y-0.right-0 {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.open .absolute.inset-y-0.right-0 {
            transform: translateX(0);
       }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Loading Bar -->
    <div class="loading-bar fixed top-0 left-0 z-50"></div>

<!-- Mobile Menu -->
<div id="mobile-menu" class="lg:hidden fixed inset-0 z-50 hidden">
    <div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/20 backdrop-blur-sm"></div>
    <div class="absolute inset-y-0 left-0 w-80 bg-white shadow-xl">
        <div class="p-6 h-full overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl overflow-hidden">
                        <div class="h-full w-full gold-gradient"></div>
                    </div>
                    <span class="font-black text-xl text-gray-900">TravelEase</span>
                </div>
                <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="space-y-4 mb-8">
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
                 <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
                        <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
                        Customer Feedback
                    </a>
                <a href="marketing_profile.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
                    <i class="fas fa-user w-6 text-center"></i>
                    My Profile
                </a>
            </nav>

            <div class="pt-8 border-t border-amber-100">
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

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
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

                <!-- Desktop Navigation -->
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
                    <a href="marketing_feedback.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
                </div>

                <!-- Profile Section -->
                <div class="hidden lg:flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
                            <div class="text-xs text-gray-600">Marketing Manager</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="login.php" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-colors" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
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
                <h1 class="text-3xl sm:text-4xl font-black mb-2">
                    <span class="text-gradient">Partnership & Collaboration Manager</span>
                </h1>
                <p class="text-lg text-gray-700">Manage relationships with hotels, airlines, tour operators, and influencers</p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Active Partnerships</p>
                            <p class="text-2xl font-bold text-gray-900">24</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                            <i class="fas fa-handshake text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-green-600">+3 this month</span>
                    </div>
                </div>
                <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Joint Promotions</p>
                            <p class="text-2xl font-bold text-gray-900">46</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-blue-600">+8 this quarter</span>
                    </div>
                </div>
                <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Revenue Generated</p>
                            <p class="text-2xl font-bold text-gray-900">$284K</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-green-600">+18% growth</span>
                    </div>
                </div>
                <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Avg. Commission</p>
                            <p class="text-2xl font-bold text-gray-900">16.5%</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">
                            <i class="fas fa-percentage text-amber-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-green-600">+2.3% increase</span>
                    </div>
                </div>
            </div>

            <!-- Partnership Categories -->
            <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Partnership Categories</h3>
                    <button onclick="showNewPartnershipModal()" class="px-4 py-2 rounded-lg gold-gradient text-white font-semibold hover:shadow-lg text-sm">
                        <i class="fas fa-plus mr-1"></i> Add Partnership
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php foreach ($partnershipTypes as $key => $type): 
                        $icons = [
                            'hotel' => 'fa-hotel',
                            'airline' => 'fa-plane',
                            'tour_operator' => 'fa-map-marked-alt',
                            'influencer' => 'fa-users',
                            'tourism_board' => 'fa-landmark',
                            'travel_agent' => 'fa-suitcase',
                        ];
                        $counts = [
                            'hotel' => 8,
                            'airline' => 6,
                            'tour_operator' => 5,
                            'influencer' => 12,
                            'tourism_board' => 3,
                            'travel_agent' => 15,
                        ];
                    ?>
                    <div class="text-center cursor-pointer hover:scale-105 transition-transform" onclick="filterPartnerships('<?= $key ?>')">
                        <div class="type-icon <?= 'type-' . $key ?> mx-auto mb-2">
                            <i class="fas <?= $icons[$key] ?>"></i>
                        </div>
                        <div class="text-xs font-medium text-gray-900"><?= $type ?></div>
                        <div class="text-xs text-gray-600"><?= $counts[$key] ?> partners</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Partnerships Table -->
            <div class="glass-effect rounded-2xl border border-amber-100 shadow mb-8">
                <div class="p-6 border-b border-amber-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900">Current Partnerships</h3>
                        <div class="flex items-center gap-4">
                            <select id="filterStatus" class="px-3 py-2 rounded-lg border border-amber-200 bg-white text-sm">
                                <option value="">All Statuses</option>
                                <?php foreach ($partnershipStatuses as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterTier" class="px-3 py-2 rounded-lg border border-amber-200 bg-white text-sm">
                                <option value="">All Tiers</option>
                                <?php foreach ($partnershipTiers as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" placeholder="Search partners..." class="px-3 py-2 rounded-lg border border-amber-200 bg-white text-sm w-48">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Partner</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Type</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Tier</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Commission</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Joint Promotions</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100">
                            <?php foreach ($partnerships as $partner): 
                                $typeIcons = [
                                    'hotel' => 'fa-hotel',
                                    'airline' => 'fa-plane',
                                    'tour_operator' => 'fa-map-marked-alt',
                                    'influencer' => 'fa-users',
                                    'tourism_board' => 'fa-landmark'
                                ];
                            ?>
                            <tr class="partnership-card hover:bg-amber-50/50">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                                            <i class="fas <?= $typeIcons[$partner['type']] ?? 'fa-building' ?> text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900"><?= $partner['name'] ?></div>
                                            <div class="text-sm text-gray-600"><?= $partner['contact'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs">
                                        <?= $partnershipTypes[$partner['type']] ?? $partner['type'] ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="tier-badge tier-<?= $partner['tier'] ?>">
                                        <?= $partner['tier'] ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="status-badge status-<?= $partner['status'] ?>">
                                        <?= $partner['status'] ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium text-gray-900"><?= $partner['revenue_share'] ?></div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900"><?= $partner['joint_promotions'] ?></span>
                                        <?php if ($partner['upcoming_campaigns'] > 0): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            +<?= $partner['upcoming_campaigns'] ?> upcoming
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="viewPartner(<?= $partner['id'] ?>)" 
                                                class="p-2 rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editPartner(<?= $partner['id'] ?>)" 
                                                class="p-2 rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="contactPartner('<?= $partner['email'] ?>')" 
                                                class="p-2 rounded-lg border border-green-300 text-green-700 hover:bg-green-50" title="Contact">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-amber-100 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <?= count($partnerships) ?> of 24 partnerships
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 rounded-lg bg-amber-500 text-white">1</button>
                        <button class="px-3 py-1 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50">2</button>
                        <button class="px-3 py-1 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50">3</button>
                        <button class="px-3 py-1 rounded-lg border border-amber-200 text-amber-700 hover:bg-amber-50">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Joint Promotions & Campaigns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Upcoming Joint Campaigns -->
                <div class="glass-effect rounded-2xl border border-amber-100 shadow">
                    <div class="p-6 border-b border-amber-100">
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Joint Campaigns</h3>
                        <p class="text-sm text-gray-600 mt-1">Coordinate joint promotions with partners</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($upcomingCampaigns as $campaign): ?>
                            <div class="p-4 rounded-xl border border-amber-200 bg-white">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900"><?= $campaign['name'] ?></h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="campaign-status campaign-<?= $campaign['status'] ?>">
                                                <?= ucfirst($campaign['status']) ?>
                                            </span>
                                            <span class="text-xs text-gray-600">
                                                <?= date('M d', strtotime($campaign['start_date'])) ?> - <?= date('M d, Y', strtotime($campaign['end_date'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-amber-600">$<?= number_format($campaign['budget']) ?></div>
                                        <div class="text-xs text-gray-600">Budget</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="text-xs text-gray-600 mb-1">Partners Involved:</div>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($campaign['partners'] as $partner): ?>
                                        <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full text-xs">
                                            <?= $partner ?>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button onclick="viewCampaign(<?= $campaign['id'] ?>)" 
                                            class="px-3 py-1 rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 text-sm">
                                        View Details
                                    </button>
                                    <button onclick="editCampaign(<?= $campaign['id'] ?>)" 
                                            class="px-3 py-1 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 text-sm">
                                        Edit Campaign
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button onclick="createNewCampaign()" class="w-full mt-4 p-3 rounded-xl border-2 border-dashed border-amber-300 text-amber-700 hover:bg-amber-50 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Create New Joint Campaign
                        </button>
                    </div>
                </div>

                <!-- Partnership Performance -->
                <div class="glass-effect rounded-2xl border border-amber-100 shadow">
                    <div class="p-6 border-b border-amber-100">
                        <h3 class="text-lg font-semibold text-gray-900">Partnership Performance</h3>
                        <p class="text-sm text-gray-600 mt-1">Revenue generated by partnership type</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="h-64 mb-6">
                            <canvas id="partnershipChart"></canvas>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-3 w-3 rounded-full bg-amber-500"></div>
                                    <span class="text-sm text-gray-700">Hotel Partnerships</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">$124,500</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-3 w-3 rounded-full bg-amber-400"></div>
                                    <span class="text-sm text-gray-700">Airline Partnerships</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">$89,200</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-3 w-3 rounded-full bg-amber-300"></div>
                                    <span class="text-sm text-gray-700">Tour Operators</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">$42,800</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-3 w-3 rounded-full bg-amber-200"></div>
                                    <span class="text-sm text-gray-700">Influencers</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">$27,500</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button onclick="sendBulkEmail()" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
                        <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center mb-3">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div class="font-medium text-gray-900 mb-1">Send Bulk Email</div>
                        <div class="text-sm text-gray-600">Email multiple partners at once</div>
                    </button>
                    <button onclick="generateReport()" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
                        <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center mb-3">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div class="font-medium text-gray-900 mb-1">Generate Report</div>
                        <div class="text-sm text-gray-600">Create partnership performance report</div>
                    </button>
                    <button onclick="scheduleMeeting()" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
                        <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center mb-3">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="font-medium text-gray-900 mb-1">Schedule Meeting</div>
                        <div class="text-sm text-gray-600">Set up partner meetings</div>
                    </button>
                    <button onclick="viewContractTemplates()" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
                        <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center mb-3">
                            <i class="fas fa-file-contract text-white"></i>
                        </div>
                        <div class="font-medium text-gray-900 mb-1">Contract Templates</div>
                        <div class="text-sm text-gray-600">Access partnership agreements</div>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- New Partnership Modal -->
    <div id="newPartnershipModal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Add New Partnership</h3>
                    <button onclick="closeModal()" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="partnershipForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" id="companyName" required
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partnership Type *</label>
                            <select id="partnershipType" class="w-full p-3 rounded-xl border border-amber-200 bg-white" required>
                                <option value="">Select type</option>
                                <?php foreach ($partnershipTypes as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                            <input type="text" id="contactPerson" required
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="contactEmail" required
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="contactPhone"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" id="companyWebsite" placeholder="https://"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate *</label>
                            <div class="flex items-center">
                                <input type="number" id="commissionRate" step="0.1" min="0" max="100" required
                                       class="flex-1 p-3 rounded-l-xl border border-amber-200 bg-white">
                                <span class="px-4 py-3 bg-amber-50 border border-amber-200 border-l-0 rounded-r-xl text-amber-700">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partnership Tier</label>
                            <select id="partnershipTier" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                                <option value="">Select tier</option>
                                <?php foreach ($partnershipTiers as $value => $label): ?>
                                <option value="<?= $value ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Industry</label>
                        <select id="industry" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                            <option value="">Select industry</option>
                            <?php foreach ($industries as $value => $label): ?>
                            <option value="<?= $value ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes & Collaboration Ideas</label>
                        <textarea id="partnerNotes" rows="3" 
                                  placeholder="Describe potential collaboration opportunities, joint promotions, or special arrangements..."
                                  class="w-full p-3 rounded-xl border border-amber-200 bg-white"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" 
                                class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50">
                            Cancel
                        </button>
                        <button type="button" onclick="savePartnership()" 
                                class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg">
                            <i class="fas fa-handshake mr-2"></i> Create Partnership
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-amber-100 bg-amber-50 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-8 md:grid-cols-4 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl overflow-hidden bg-white p-1">
                            <div class="h-full w-full gold-gradient rounded-lg"></div>
                        </div>
                        <span class="font-black text-lg text-gray-900">TravelEase</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">
                        Building strong partnerships with hotels, airlines, tour operators, and influencers worldwide.
                    </p>
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

            <div class="pt-8 border-t border-amber-100 text-center text-sm text-gray-600">
                <p>© <?= $currentYear ?> TravelEase Partnership Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('partnershipChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hotels', 'Airlines', 'Tour Operators', 'Influencers', 'Tourism Boards'],
                    datasets: [{
                        data: [124.5, 89.2, 42.8, 27.5, 18.3],
                        backgroundColor: [
                            '#f59e0b',
                            '#fbbf24',
                            '#fcd34d',
                            '#fde68a',
                            '#fef3c7'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `$${context.raw}K`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Set default commission rate
            document.getElementById('commissionRate').value = '15';
        });

        // Modal functions
        function showNewPartnershipModal() {
            document.getElementById('newPartnershipModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('newPartnershipModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('newPartnershipModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('newPartnershipModal').style.display === 'flex') {
                closeModal();
            }
        });

        // Mobile menu functionality
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        
        let isMenuOpen = false;

        if (menuButton) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.add('open');
                }, 10);
                document.body.style.overflow = 'hidden';
                isMenuOpen = true;
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isMenuOpen = false;
            });
        }

        if (mobileMenuBackdrop) {
            mobileMenuBackdrop.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isMenuOpen = false;
            });
        }

        // Filter functions
        function filterPartnerships(type) {
            alert(`Filtering partnerships by type: ${type}`);
            // In a real app, this would filter the partnerships table
        }

        // Partner actions
        function viewPartner(partnerId) {
            alert(`Viewing partner details for ID: ${partnerId}`);
            // In a real app, this would show partner details in a modal
        }

        function editPartner(partnerId) {
            alert(`Editing partner with ID: ${partnerId}`);
            // In a real app, this would open edit form
        }

        function contactPartner(email) {
            window.location.href = `mailto:${email}?subject=Partnership Discussion - TravelEase`;
        }

        // Campaign actions
        function viewCampaign(campaignId) {
            alert(`Viewing campaign details for ID: ${campaignId}`);
        }

        function editCampaign(campaignId) {
            alert(`Editing campaign with ID: ${campaignId}`);
        }

        function createNewCampaign() {
            alert('Creating new joint campaign...');
            // In a real app, this would open campaign creation form
        }

        // Quick actions
        function sendBulkEmail() {
            alert('Opening bulk email composer...');
        }

        function generateReport() {
            alert('Generating partnership performance report...');
        }

        function scheduleMeeting() {
            alert('Opening meeting scheduler...');
        }

        function viewContractTemplates() {
            alert('Opening contract templates library...');
        }

        // Save partnership
        function savePartnership() {
            const companyName = document.getElementById('companyName').value;
            const contactPerson = document.getElementById('contactPerson').value;
            const contactEmail = document.getElementById('contactEmail').value;
            const commissionRate = document.getElementById('commissionRate').value;
            
            if (!companyName || !contactPerson || !contactEmail || !commissionRate) {
                alert('Please fill in all required fields.');
                return;
            }

            // Show loading
            const btn = document.querySelector('#partnershipForm button[onclick="savePartnership()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
            btn.disabled = true;

            setTimeout(() => {
                alert(`✅ Partnership with "${companyName}" created successfully!\n\nAn invitation has been sent to ${contactEmail}`);
                closeModal();
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                // In a real app, this would refresh the partnerships list
                // location.reload();
            }, 1500);
        }

        // New partnership button event listener
        document.getElementById('new-partnership-btn')?.addEventListener('click', showNewPartnershipModal);
    </script>
</body>
</html>