<?php
// customer_experience_results.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Footer links
$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard1.php'],
        ['text' => 'Packages', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Customer Feedback', 'link' => 'customer_experience_results.php'],
        ['text' => 'Report Generator', 'link' => 'marketing_report.php'],
        ['text' => 'Partnerships', 'link' => 'partnership.php']
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Experience Results Dashboard | TravelEase Marketing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body { 
            background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
            color: #1f2937;
            overflow-x: hidden;
            line-height: 1.6;
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* REMOVED THE BACKGROUND BOX - Changed header to be part of the flow */
        .page-header {
            margin-bottom: 30px;
            padding: 25px 30px;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.15);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
        }

        .subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .header-controls {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .date-filter {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .date-filter option {
            color: #1f2937;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .stat-change {
            font-size: 0.8rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .positive {
            color: rgba(255, 255, 255, 0.9);
        }

        .negative {
            color: rgba(255, 255, 255, 0.7);
        }

        .dashboard {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        @media (max-width: 1100px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -10px rgba(245, 158, 11, 0.15);
        }

        .card-title {
            color: #78350f;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #fef3c7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
        }

        .card-title i {
            color: #b45309;
        }

        /* ADDED: Mobile view icon for customer feedback header */
        .mobile-icon-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-icon-header i {
            font-size: 1.5rem;
            color: #b45309;
        }

        @media (min-width: 769px) {
            .mobile-icon-header i {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .card-title h2 {
                font-size: 1.2rem;
            }
            
            .mobile-icon-header i {
                display: block;
            }
        }

        .feedback-list {
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .feedback-item {
            padding: 18px;
            background: #fef3c7;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #b45309;
            transition: all 0.3s ease;
        }

        .feedback-item:hover {
            background: #fde68a;
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #b45309;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .customer-details h4 {
            color: #78350f;
            margin-bottom: 3px;
        }

        .customer-type {
            font-size: 0.8rem;
            color: #92400e;
            background: #fde68a;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .feedback-date {
            color: #b45309;
            font-size: 0.85rem;
        }

        .feedback-rating {
            color: #f59e0b;
            margin-bottom: 10px;
        }

        .feedback-text {
            color: #92400e;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .feedback-category {
            display: inline-block;
            background: #fcd34d;
            color: #78350f;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn {
            background: #b45309;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: #92400e;
            transform: translateY(-2px);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .btn-approve {
            background: #10b981;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            background: #ef4444;
        }

        .btn-reject:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #d97706;
        }

        .btn-secondary:hover {
            background: #b45309;
        }

        .loyalty-programs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .program-card {
            background: #fef3c7;
            border-radius: 10px;
            padding: 20px;
            border-top: 4px solid #b45309;
        }

        .program-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .program-title {
            font-size: 1.1rem;
            color: #78350f;
            font-weight: 600;
        }

        .program-status {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .program-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #fde68a;
        }

        .stat {
            text-align: center;
        }

        .stat-value-sm {
            font-size: 1.2rem;
            font-weight: 700;
            color: #78350f;
            display: block;
        }

        .stat-label-sm {
            font-size: 0.75rem;
            color: #b45309;
        }

        .engagement-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .metric-card {
            background: #fef3c7;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .metric-icon {
            font-size: 2rem;
            color: #b45309;
            margin-bottom: 10px;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #78350f;
            display: block;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #92400e;
            margin-top: 5px;
        }

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #92400e;
        }

        select {
            padding: 8px 15px;
            border: 1px solid #fde68a;
            border-radius: 6px;
            background: white;
            font-size: 0.9rem;
            color: #78350f;
        }

        .pending-badge {
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            margin-left: 5px;
        }

        .tab-container {
            margin-top: 30px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #fef3c7;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        .tab {
            padding: 12px 25px;
            cursor: pointer;
            font-weight: 600;
            color: #92400e;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab i {
            font-size: 1rem;
        }

        .tab:hover {
            color: #78350f;
        }

        .tab.active {
            color: #78350f;
            border-bottom: 3px solid #b45309;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            padding: 30px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            color: #78350f;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #b45309;
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .card-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .filters {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
            
            .feedback-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .feedback-date {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="loading-bar fixed top-0 left-0 z-50"></div>

    <!-- Mobile Menu -->
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
                    <a href="marketing_dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
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

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Main Header Logo -->
                <div class="flex items-center gap-3">
                    <a href="marketing_dashboard.php" class="flex items-center gap-3 group">
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

                     <a href="marketing_feedback.php" class="text-amber-600 transition-all duration-300 relative group">
                        <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
                        Customer Feedback
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
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

    <!-- Main Content -->
    <div class="container" style="margin-top: 100px;">
        <!-- REMOVED THE BACKGROUND BOX - Using page-header class instead -->
        <div class="page-header">
            <div class="header-top">
                <div>
                    <h1>Customer Experience Results</h1>
                    <p class="subtitle">Approved feedback, loyalty program performance, and engagement metrics</p>
                </div>
                <div class="header-controls">
                    <select class="date-filter" id="periodFilter">
                        <option value="month">Last 30 Days</option>
                        <option value="quarter">Last Quarter</option>
                        <option value="year">Last Year</option>
                        <option value="all">All Time</option>
                    </select>
                    <button class="btn" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <span class="stat-value">94.2%</span>
                    <span class="stat-label">Customer Satisfaction</span>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 2.1% from last month
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-value">8.7</span>
                    <span class="stat-label">Average Rating</span>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 0.3 from last month
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-value">12,548</span>
                    <span class="stat-label">Loyalty Members</span>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 845 new this month
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-value">89%</span>
                    <span class="stat-label">Retention Rate</span>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 3% from last quarter
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard">
            <main class="card">
                <!-- ADDED: Mobile view icon for customer feedback header -->
                <div class="card-title">
                    <div class="mobile-icon-header">
                        <i class="fas fa-comment-check"></i>
                        <h2>Approved Customer Feedback</h2>
                    </div>
                    <div class="filters">
                        <div class="filter-group">
                            <span class="filter-label">Filter:</span>
                            <select id="feedbackFilter">
                                <option value="all">All Feedback</option>
                                <option value="positive">Positive (4-5 stars)</option>
                                <option value="negative">Critical (1-3 stars)</option>
                                <option value="service">Service Related</option>
                                <option value="booking">Booking Experience</option>
                            </select>
                        </div>
                        <button class="btn btn-secondary btn-small" onclick="openPendingFeedback()">
                            <i class="fas fa-clock"></i> Pending Approval
                            <span class="pending-badge" id="pendingCount">12</span>
                        </button>
                    </div>
                </div>
                
                <div class="feedback-list" id="approvedFeedbackList">
                    <!-- Approved feedback items will be loaded here -->
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn" onclick="loadMoreFeedback()">
                        <i class="fas fa-plus"></i> Load More Feedback
                    </button>
                </div>
            </main>
            
            <aside class="card">
                <div class="card-title">
                    <div class="mobile-icon-header">
                        <i class="fas fa-chart-line"></i>
                        <h2>Key Metrics</h2>
                    </div>
                </div>
                
                <div class="engagement-metrics">
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-mail-bulk"></i>
                        </div>
                        <span class="metric-value">78%</span>
                        <span class="metric-label">Email Open Rate</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <span class="metric-value">3.2K</span>
                        <span class="metric-label">Referrals This Month</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-redo"></i>
                        </div>
                        <span class="metric-value">42%</span>
                        <span class="metric-label">Repeat Customers</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span class="metric-value">92%</span>
                        <span class="metric-label">Issue Resolution Rate</span>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="satisfactionChart"></canvas>
                </div>
            </aside>
        </div>

        <div class="tab-container">
            <div class="tabs">
                <div class="tab active" data-tab="loyalty">
                    <i class="fas fa-crown"></i> Loyalty Programs
                </div>
                <div class="tab" data-tab="engagement">
                    <i class="fas fa-handshake"></i> Engagement Metrics
                </div>
                <div class="tab" data-tab="referrals">
                    <i class="fas fa-user-friends"></i> Referral Campaigns
                </div>
            </div>
            
            <div class="tab-content active" id="loyalty-tab">
                <div class="loyalty-programs">
                    <div class="program-card">
                        <div class="program-header">
                            <div class="program-title">Frequent Flyer Elite</div>
                            <div class="program-status status-active">Active</div>
                        </div>
                        <p>Members earn points for every booking, redeemable for upgrades, lounge access, and exclusive discounts.</p>
                        <div class="program-stats">
                            <div class="stat">
                                <span class="stat-value-sm">8,452</span>
                                <span class="stat-label-sm">Members</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">72%</span>
                                <span class="stat-label-sm">Active</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">$142K</span>
                                <span class="stat-label-sm">Revenue</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card">
                        <div class="program-header">
                            <div class="program-title">Refer & Earn</div>
                            <div class="program-status status-active">Active</div>
                        </div>
                        <p>Customers earn travel credits for referring friends and family. Both referrer and referee receive benefits.</p>
                        <div class="program-stats">
                            <div class="stat">
                                <span class="stat-value-sm">4,231</span>
                                <span class="stat-label-sm">Referrers</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">2,845</span>
                                <span class="stat-label-sm">Referrals</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">$89K</span>
                                <span class="stat-label-sm">Revenue</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-card">
                        <div class="program-header">
                            <div class="program-title">Business Traveler Plus</div>
                            <div class="program-status status-pending">Launching Soon</div>
                        </div>
                        <p>Exclusive benefits for corporate clients and frequent business travelers including priority support.</p>
                        <div class="program-stats">
                            <div class="stat">
                                <span class="stat-value-sm">1,245</span>
                                <span class="stat-label-sm">Pre-signups</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">-</span>
                                <span class="stat-label-sm">Active</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value-sm">-</span>
                                <span class="stat-label-sm">Revenue</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-content" id="engagement-tab">
                <div class="card">
                    <div class="card-title">
                        <div class="mobile-icon-header">
                            <i class="fas fa-handshake"></i>
                            <h2>Customer Engagement Performance</h2>
                        </div>
                    </div>
                    <p style="margin-bottom: 20px;">Effectiveness of engagement strategies before, during, and after travel.</p>
                    
                    <div class="chart-container">
                        <canvas id="engagementChart"></canvas>
                    </div>
                    
                    <div class="engagement-metrics" style="margin-top: 30px;">
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-plane-departure"></i>
                            </div>
                            <span class="metric-value">94%</span>
                            <span class="metric-label">Pre-Travel Engagement</span>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <span class="metric-value">88%</span>
                            <span class="metric-label">During Travel Support</span>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-plane-arrival"></i>
                            </div>
                            <span class="metric-value">76%</span>
                            <span class="metric-label">Post-Travel Follow-up</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-content" id="referrals-tab">
                <div class="card">
                    <div class="card-title">
                        <div class="mobile-icon-header">
                            <i class="fas fa-user-friends"></i>
                            <h2>Referral Campaign Performance</h2>
                        </div>
                    </div>
                    <p style="margin-bottom: 20px;">Top performing referral campaigns and their conversion rates.</p>
                    
                    <div class="chart-container">
                        <canvas id="referralChart"></canvas>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <h3 style="color: #78350f; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-trophy text-amber-600"></i> Top Referrers This Month
                        </h3>
                        <div class="feedback-list" style="max-height: 300px;">
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <div class="customer-info">
                                        <div class="customer-avatar">MJ</div>
                                        <div>
                                            <h4>Michael Johnson</h4>
                                            <span class="customer-type">Platinum Member</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span style="font-weight: bold; color: #78350f;">12 Referrals</span>
                                    </div>
                                </div>
                                <p style="color: #92400e; font-size: 0.9rem;">Earned $480 in travel credits this month through referrals.</p>
                            </div>
                            
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <div class="customer-info">
                                        <div class="customer-avatar">SR</div>
                                        <div>
                                            <h4>Sarah Roberts</h4>
                                            <span class="customer-type">Gold Member</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span style="font-weight: bold; color: #78350f;">8 Referrals</span>
                                    </div>
                                </div>
                                <p style="color: #92400e; font-size: 0.9rem;">Earned $320 in travel credits this month through referrals.</p>
                            </div>
                            
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <div class="customer-info">
                                        <div class="customer-avatar">DC</div>
                                        <div>
                                            <h4>David Chen</h4>
                                            <span class="customer-type">Business Account</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span style="font-weight: bold; color: #78350f;">7 Referrals</span>
                                    </div>
                                </div>
                                <p style="color: #92400e; font-size: 0.9rem;">Earned $280 in travel credits this month through referrals.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Feedback Modal -->
        <div class="modal" id="pendingModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-clock text-amber-600"></i> Feedback Pending Approval
                    </h3>
                    <button class="close-modal" onclick="closePendingModal()">&times;</button>
                </div>
                
                <div class="feedback-list" id="pendingFeedbackList" style="max-height: 400px;">
                    <!-- Pending feedback items will be loaded here -->
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-secondary" onclick="closePendingModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="border-t border-amber-100 bg-amber-50 mt-8">
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
                            Customer Experience Dashboard for TravelEase luxury travel platform.
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
                    <p>© <?= $currentYear ?> TravelEase Customer Experience Dashboard. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <span>Customer Experience Platform</span>
                        <span class="flex items-center">
                            <i class="fas fa-circle text-green-500 text-xs mr-1"></i> System Operational
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample approved feedback data (what marketing has approved)
        const approvedFeedback = [
            {
                id: 1,
                name: "Sarah Johnson",
                initials: "SJ",
                rating: 5,
                text: "Excellent service from start to finish. The travel recommendations were perfect for our family vacation to Hawaii. The personalized itinerary made our trip stress-free.",
                date: "2023-10-15",
                category: "service",
                type: "Repeat Customer"
            },
            {
                id: 2,
                name: "Michael Chen",
                initials: "MC",
                rating: 4,
                text: "Great booking experience with helpful customer support. The flight options presented were exactly what we needed. Would definitely book again.",
                date: "2023-10-10",
                category: "booking",
                type: "Business Traveler"
            },
            {
                id: 3,
                name: "Emma Rodriguez",
                initials: "ER",
                rating: 5,
                text: "Loyalty program benefits are outstanding. The room upgrade made our anniversary special. The concierge service was exceptional.",
                date: "2023-10-05",
                category: "loyalty",
                type: "Loyalty Member"
            },
            {
                id: 4,
                name: "James Wilson",
                initials: "JW",
                rating: 3,
                text: "The hotel was good but not as described on the website. However, customer service was responsive to our concerns and offered a partial refund.",
                date: "2023-09-28",
                category: "accommodation",
                type: "First-time Customer"
            },
            {
                id: 5,
                name: "Lisa Park",
                initials: "LP",
                rating: 5,
                text: "The referral program is fantastic! I've earned enough points for a free weekend trip. Shared with all my friends who love to travel.",
                date: "2023-09-20",
                category: "referral",
                type: "Loyalty Member"
            },
            {
                id: 6,
                name: "Robert Kim",
                initials: "RK",
                rating: 4,
                text: "Smooth booking process and good value for money. The travel insurance option gave us peace of mind during our international trip.",
                date: "2023-09-15",
                category: "booking",
                type: "Business Traveler"
            }
        ];

        // Sample pending feedback (awaiting marketing approval)
        const pendingFeedback = [
            {
                id: 101,
                name: "David Miller",
                initials: "DM",
                rating: 2,
                text: "Customer service was unhelpful when we had to change our booking last minute. Had to wait on hold for over 30 minutes.",
                date: "2023-10-12",
                category: "service",
                type: "First-time Customer"
            },
            {
                id: 102,
                name: "Amanda Smith",
                initials: "AS",
                rating: 5,
                text: "Best travel experience ever! The personalized recommendations for restaurants in Rome were spot on. Will be a customer for life.",
                date: "2023-10-08",
                category: "service",
                type: "Repeat Customer"
            }
        ];

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            renderApprovedFeedback();
            initCharts();
            setupTabs();
            updateLastUpdated();
            setupMobileMenu();
            
            // Add event listeners
            document.getElementById('periodFilter').addEventListener('change', filterByPeriod);
            document.getElementById('feedbackFilter').addEventListener('change', renderApprovedFeedback);
        });

        // Render approved feedback based on filter
        function renderApprovedFeedback() {
            const filter = document.getElementById('feedbackFilter').value;
            const feedbackList = document.getElementById('approvedFeedbackList');
            
            // Filter feedback
            let filteredFeedback = approvedFeedback;
            if (filter === 'positive') {
                filteredFeedback = approvedFeedback.filter(f => f.rating >= 4);
            } else if (filter === 'negative') {
                filteredFeedback = approvedFeedback.filter(f => f.rating <= 3);
            } else if (filter === 'service') {
                filteredFeedback = approvedFeedback.filter(f => f.category === 'service');
            } else if (filter === 'booking') {
                filteredFeedback = approvedFeedback.filter(f => f.category === 'booking');
            }
            
            // Update pending count badge
            document.getElementById('pendingCount').textContent = pendingFeedback.length;
            
            // Generate HTML for feedback items
            let feedbackHTML = '';
            filteredFeedback.forEach(feedback => {
                feedbackHTML += `
                    <div class="feedback-item">
                        <div class="feedback-header">
                            <div class="customer-info">
                                <div class="customer-avatar">${feedback.initials}</div>
                                <div>
                                    <h4>${feedback.name}</h4>
                                    <span class="customer-type">${feedback.type}</span>
                                </div>
                            </div>
                            <span class="feedback-date">${formatDate(feedback.date)}</span>
                        </div>
                        <div class="feedback-rating">
                            ${generateStarRating(feedback.rating)}
                            <span style="margin-left: 10px; font-size: 0.9rem; color: #92400e;">${feedback.rating}/5</span>
                        </div>
                        <div class="feedback-text">
                            ${feedback.text}
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="feedback-category">${formatCategory(feedback.category)}</span>
                            <span style="float: right; font-size: 0.8rem; color: #10b981;">
                                <i class="fas fa-check-circle"></i> Approved by Marketing
                            </span>
                        </div>
                    </div>
                `;
            });
            
            feedbackList.innerHTML = feedbackHTML;
        }

        // Format date for display
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Generate star rating HTML
        function generateStarRating(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star"></i>';
                } else {
                    stars += '<i class="far fa-star"></i>';
                }
            }
            return stars;
        }

        // Format category for display
        function formatCategory(category) {
            const categoryMap = {
                'service': 'Customer Service',
                'booking': 'Booking Experience',
                'loyalty': 'Loyalty Program',
                'accommodation': 'Accommodation',
                'referral': 'Referral Program',
                'general': 'General Feedback'
            };
            return categoryMap[category] || category;
        }

        // Initialize charts
        function initCharts() {
            // Satisfaction Trend Chart
            const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            new Chart(satisfactionCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [{
                        label: 'Customer Satisfaction %',
                        data: [89, 90, 91, 92, 92, 93, 93, 94, 94, 94.2],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 85,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Satisfaction %'
                            }
                        }
                    }
                }
            });

            // Engagement Chart
            const engagementCtx = document.getElementById('engagementChart').getContext('2d');
            new Chart(engagementCtx, {
                type: 'bar',
                data: {
                    labels: ['Pre-Travel', 'During Travel', 'Post-Travel'],
                    datasets: [
                        {
                            label: 'Email Open Rate %',
                            data: [94, 88, 76],
                            backgroundColor: '#d97706',
                            borderColor: '#b45309',
                            borderWidth: 1
                        },
                        {
                            label: 'Response Rate %',
                            data: [89, 92, 68],
                            backgroundColor: '#f59e0b',
                            borderColor: '#d97706',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Percentage %'
                            }
                        }
                    }
                }
            });

            // Referral Chart
            const referralCtx = document.getElementById('referralChart').getContext('2d');
            new Chart(referralCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Email Campaign', 'Social Media', 'In-App Promo', 'Word of Mouth'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: [
                            '#b45309',
                            '#d97706',
                            '#f59e0b',
                            '#fcd34d'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }

        // Tab functionality
        function setupTabs() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and contents
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        }

        // Mobile menu functionality
        function setupMobileMenu() {
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

            // Initialize event listeners for mobile menu
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
        }

        // Open pending feedback modal
        function openPendingFeedback() {
            const modal = document.getElementById('pendingModal');
            const feedbackList = document.getElementById('pendingFeedbackList');
            
            // Generate HTML for pending feedback
            let feedbackHTML = '';
            pendingFeedback.forEach(feedback => {
                feedbackHTML += `
                    <div class="feedback-item">
                        <div class="feedback-header">
                            <div class="customer-info">
                                <div class="customer-avatar">${feedback.initials}</div>
                                <div>
                                    <h4>${feedback.name}</h4>
                                    <span class="customer-type">${feedback.type}</span>
                                </div>
                            </div>
                            <span class="feedback-date">${formatDate(feedback.date)}</span>
                        </div>
                        <div class="feedback-rating">
                            ${generateStarRating(feedback.rating)}
                        </div>
                        <div class="feedback-text">
                            ${feedback.text}
                        </div>
                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button class="btn btn-approve btn-small" onclick="approveFeedback(${feedback.id})">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-reject btn-small" onclick="rejectFeedback(${feedback.id})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                            <button class="btn btn-secondary btn-small" onclick="editFeedback(${feedback.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                `;
            });
            
            if (pendingFeedback.length === 0) {
                feedbackHTML = '<p style="text-align: center; color: #92400e; padding: 30px;">No feedback pending approval</p>';
            }
            
            feedbackList.innerHTML = feedbackHTML;
            modal.style.display = 'flex';
        }

        // Close pending feedback modal
        function closePendingModal() {
            document.getElementById('pendingModal').style.display = 'none';
        }

        // Approve feedback (simulate moving from pending to approved)
        function approveFeedback(id) {
            const feedbackIndex = pendingFeedback.findIndex(f => f.id === id);
            if (feedbackIndex !== -1) {
                const approvedItem = pendingFeedback[feedbackIndex];
                approvedItem.id = approvedFeedback.length + 1;
                approvedFeedback.unshift(approvedItem);
                pendingFeedback.splice(feedbackIndex, 1);
                
                // Update UI
                renderApprovedFeedback();
                openPendingFeedback(); // Refresh modal
                
                // Show success message
                alert(`Feedback from ${approvedItem.name} approved and published!`);
            }
        }

        // Reject feedback (remove from pending)
        function rejectFeedback(id) {
            const feedbackIndex = pendingFeedback.findIndex(f => f.id === id);
            if (feedbackIndex !== -1) {
                const rejectedItem = pendingFeedback[feedbackIndex];
                if (confirm(`Are you sure you want to reject feedback from ${rejectedItem.name}?`)) {
                    pendingFeedback.splice(feedbackIndex, 1);
                    openPendingFeedback(); // Refresh modal
                    alert('Feedback rejected and removed from pending list.');
                }
            }
        }

        // Edit feedback (simulate editing before approval)
        function editFeedback(id) {
            alert('In a real application, this would open an editor to modify the feedback before approval.');
        }

        // Other interactive functions
        function filterByPeriod() {
            const period = document.getElementById('periodFilter').value;
            alert(`Filtering data for: ${period}. In a real application, this would fetch data for the selected period.`);
        }

        function refreshData() {
            // Simulate data refresh
            updateLastUpdated();
            alert('Data refreshed! In a real application, this would fetch the latest data from the server.');
        }

        function loadMoreFeedback() {
            alert('Loading more feedback... In a real application, this would load additional feedback items.');
        }

        function updateLastUpdated() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            // Create a footer element to display last updated
            const footer = document.querySelector('footer');
            if (footer) {
                const updateSpan = document.createElement('div');
                updateSpan.style.textAlign = 'center';
                updateSpan.style.marginTop = '10px';
                updateSpan.style.fontSize = '0.8rem';
                updateSpan.style.color = '#92400e';
                updateSpan.innerHTML = `Last updated: ${now.toLocaleDateString('en-US', options)}`;
                footer.querySelector('.pt-8').before(updateSpan);
            }
        }

        // Remove loading bar
        window.addEventListener('load', () => {
            const loadingBar = document.querySelector('.loading-bar');
            if (loadingBar) {
                loadingBar.style.opacity = '0';
                setTimeout(() => {
                    loadingBar.remove();
                }, 500);
            }
        });
    </script>
</body>
</html>