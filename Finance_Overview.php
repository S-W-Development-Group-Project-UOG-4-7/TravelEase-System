<?php
session_start();
if (!isset($_SESSION['finance_logged_in'])) {
    // For now: auto-login (use real login later)
    $_SESSION['finance_logged_in'] = true;
    $_SESSION['finance_full_name'] = 'Finance Manager';
    $_SESSION['finance_role'] = 'Finance Manager';
}

// Safe variables for UI
$financeName = $_SESSION['finance_full_name'] ?? 'Finance Manager';

$financeProfileImage = 'https://ui-avatars.com/api/?name=' 
    . urlencode($financeName) 
    . '&background=0ea5e9&color=fff&bold=true';

$currentYear = date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Financial Overview | TravelEase Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Financial overview dashboard for TravelEase luxury travel company.">
  
  <!-- PWA Meta Tags -->
  <meta name="theme-color" content="#f59e0b"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="manifest" href="manifest.json">

  <!-- Tailwind CSS CDN -->
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
            finance: {
              green: '#10b981',
              red: '#ef4444',
              blue: '#3b82f6',
              purple: '#8b5cf6'
            },
            premium: {
              gold: '#f59e0b',
              light: '#ffffff',
              cream: '#fef7e5',
              sand: '#fef3c7'
            },
            gradient: {
              start: '#f59e0b',
              end: '#fbbf24'
            }
          },
          animation: {
            'fade-in-up': 'fadeInUp 0.6s ease-out',
            'fade-in-down': 'fadeInDown 0.6s ease-out',
            'slide-in-right': 'slideInRight 0.6s ease-out',
            'zoom-in': 'zoomIn 0.8s ease-out',
            'float': 'float 6s ease-in-out infinite',
            'gradient-shift': 'gradientShift 3s ease infinite',
            'pulse-slow': 'pulse 3s ease-in-out infinite',
            'spin-slow': 'spin 3s linear infinite',
            'count-up': 'countUp 2s ease-out forwards',
            'chart-load': 'chartLoad 1.5s ease-out forwards'
          },
          keyframes: {
            fadeInUp: {
              '0%': { opacity: '0', transform: 'translateY(30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
            fadeInDown: {
              '0%': { opacity: '0', transform: 'translateY(-30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
            slideInRight: {
              '0%': { opacity: '0', transform: 'translateX(30px)' },
              '100%': { opacity: '1', transform: 'translateX(0)' }
            },
            zoomIn: {
              '0%': { opacity: '0', transform: 'scale(0.9)' },
              '100%': { opacity: '1', transform: 'scale(1)' }
            },
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-10px)' }
            },
            gradientShift: {
              '0%, 100%': { backgroundPosition: '0% 50%' },
              '50%': { backgroundPosition: '100% 50%' }
            },
            countUp: {
              '0%': { content: '"0"' },
              '100%': { content: 'attr(data-target)' }
            },
            chartLoad: {
              '0%': { transform: 'scaleY(0)' },
              '100%': { transform: 'scaleY(1)' }
            }
          },
          backgroundSize: {
            '200%': '200% 200%',
            '300%': '300% 300%'
          },
          screens: {
            'xs': '475px',
            '3xl': '1600px',
            '4xl': '1920px'
          },
          backdropBlur: {
            'xs': '2px',
          }
        }
      }
    };
  </script>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-gold: #f59e0b;
      --gradient-start: #f59e0b;
      --gradient-end: #fbbf24;
    }
    
    body { 
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
      color: #1f2937;
      overflow-x: hidden;
    }
    .premium-font { font-family: "Playfair Display", serif; }
    .glass-effect {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .premium-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
      background-size: 200% 200%;
      animation: gradientShift 3s ease infinite;
    }
    .finance-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
    }
    .light-gradient {
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 100%);
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
    .text-shimmer {
      background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b, #fbbf24);
      background-size: 300% 100%;
      animation: textShimmer 3s ease infinite;
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
    
    /* Finance specific styles */
    .profit-badge {
      background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
      color: white;
    }
    .loss-badge {
      background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
      color: white;
    }
    .revenue-badge {
      background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
      color: white;
    }
    .discount-badge {
      background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
      color: white;
    }
    
    /* Chart styles */
    .chart-bar {
      transform-origin: bottom;
      animation: chartLoad 1.5s ease-out forwards;
    }
    
    /* Dashboard grid */
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }
    
    @media (max-width: 768px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      .hero-headline {
        font-size: 2.5rem;
        line-height: 1.2;
      }
    }
    
    /* Custom scrollbar */
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
    
    /* Loading animations */
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
    
    /* Financial data tables */
    .finance-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .finance-table th {
      background: #fef3c7;
      color: #92400e;
      font-weight: 600;
      padding: 1rem;
      text-align: left;
      border-bottom: 2px solid #fcd34d;
    }
    
    .finance-table td {
      padding: 1rem;
      border-bottom: 1px solid #fde68a;
    }
    
    .finance-table tr:hover {
      background: #fef7e5;
    }
    
    /* Modal styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    
    .modal-content {
      background: white;
      border-radius: 1.5rem;
      max-width: 500px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      animation: zoomIn 0.3s ease-out;
    }
    
    /* Status colors */
    .status-active {
      color: #10b981;
      background: rgba(16, 185, 129, 0.1);
    }
    
    .status-expired {
      color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
    }
    
    .status-pending {
      color: #f59e0b;
      background: rgba(245, 158, 11, 0.1);
    }
    
    /* Custom chart styles */
    .chart-container {
      position: relative;
    }
    
    .chart-grid-line {
      position: absolute;
      width: 100%;
      height: 1px;
      background: rgba(245, 158, 11, 0.1);
    }
    
    .revenue-line {
      stroke: #f59e0b;
      stroke-width: 3;
      fill: none;
    }
    
    .booking-line {
      stroke: #8b5cf6;
      stroke-width: 3;
      fill: none;
    }
    
    .chart-tooltip {
      position: absolute;
      background: white;
      border: 1px solid #fcd34d;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      z-index: 10;
    }
    
    /* Pie chart colors */
    .pie-color-1 { background-color: #f59e0b; }
    .pie-color-2 { background-color: #10b981; }
    .pie-color-3 { background-color: #3b82f6; }
    .pie-color-4 { background-color: #8b5cf6; }
    .pie-color-5 { background-color: #ef4444; }
    .pie-color-6 { background-color: #fbbf24; }
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="advanced-loader mx-auto mb-6"></div>
      <div class="premium-font text-2xl font-black text-gradient">TravelEase Finance</div>
      <p class="text-gray-600 mt-2">Loading Financial Overview...</p>
    </div>
  </div>

  <!-- Enhanced Mobile Menu -->
  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center">
              <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center font-black text-amber-600 text-xs">TE</div>
            </div>
            <span class="premium-font font-black text-xl text-gray-900">TravelEase Finance</span>
          </div>
          <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="space-y-4">
          <a href="Finance_Overview.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
          <a href="Finance_Discounts.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-percentage w-6 text-center"></i>
            Discount Management
          </a>
          <a href="Finance_Pricing.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-tags w-6 text-center"></i>
            Pricing Strategy
          </a>
          <a href="Finance_Packages.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-box w-6 text-center"></i>
            Package Pricing
          </a>
          <a href="Finance_Statements.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-invoice w-6 text-center"></i>
            Financial Statements
          </a>
          <a href="Finance_Payments.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-credit-card w-6 text-center"></i>
            Payment Matching
          </a>
        </nav>

        <!-- Mobile User Info -->
        <div class="mt-8 pt-8 border-t border-amber-100">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-full gold-gradient flex items-center justify-center">
              <span class="font-bold text-white">FM</span>
            </div>
            <div>
              <p class="font-semibold text-gray-900">Finance Manager</p>
              <p class="text-sm text-gray-600">admin@travelease.com</p>
            </div>
          </div>
          <a href="logout.php" class="block w-full text-center px-6 py-3 rounded-2xl glass-effect text-gray-700 hover:bg-amber-50 transition-all font-semibold border border-amber-100">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Finance Manager Navigation -->
  <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <a href="Overview.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase Finance
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Financial Overview Dashboard
              </span>
            </div>
          </a>
        </div>

        <!-- Center Navigation -->
        <div class="hidden lg:flex items-center gap-6 text-sm font-semibold">
          <a href="Finance_Overview.php" class="text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-chart-line text-xs text-amber-500"></i>
              Overview
            </span>
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500 transition-all duration-300"></span>
          </a>
          <a href="Finance_Discounts.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-percentage mr-2"></i>
            Discounts
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Pricing.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-tags mr-2"></i>
            Pricing
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Packages.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-box mr-2"></i>
            Packages
            <span class="absolute -bottom-1 left 0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Statements.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-invoice mr-2"></i>
            Statements
            <span class="absolute -bottom-1 left:0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Payments.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-credit-card mr-2"></i>
            Payments
            <span class="absolute -bottom-1 left:0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>

        <!-- User Profile & Actions -->
        <div class="hidden lg:flex items-center gap-4">
          <div class="flex items-center gap-3 px-4 py-2 rounded-xl glass-effect border border-amber-100">
            <div class="h-8 w-8 rounded-full gold-gradient flex items-center justify-center">
              <span class="font-bold text-white text-sm">FM</span>
            </div>
            <div class="text-sm">
              <p class="font-semibold text-gray-900">Finance Manager</p>
              <p class="text-xs text-gray-600">Administrator</p>
            </div>
          </div>
          <a href="logout.php" class="px-4 py-2 rounded-xl glass-effect text-sm font-semibold text-gray-700 hover:text-amber-600 hover:bg-amber-50 transition-all duration-300 border border-amber-100">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-amber-50 transition-colors">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>
    </nav>
  </header>

  <!-- Financial Overview Dashboard -->
  <section id="overview" class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Welcome Header -->
      <div class="mb-8 animate-fade-in-down">
        <h1 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
          <span class="text-gray-900">Financial</span>
          <span class="text-gradient block">Overview</span>
        </h1>
        <p class="text-lg text-gray-700">
          Welcome back! Here's your financial overview for <?php echo date('F Y'); ?>
        </p>
      </div>

      <!-- Key Financial Metrics -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue Card -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-money-bill-wave text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge">+12.5%</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$2.8M</h3>
          <p class="text-gray-600 text-sm">Total Revenue</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-arrow-up mr-1"></i>Increased from last month
          </p>
        </div>

        <!-- Net Profit Card -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-chart-pie text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge">+8.3%</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$842K</h3>
          <p class="text-gray-600 text-sm">Net Profit</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-arrow-up mr-1"></i>Quarterly growth positive
          </p>
        </div>

        <!-- Discount Costs Card -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-percentage text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full discount-badge">$42K</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$187K</h3>
          <p class="text-gray-600 text-sm">Discount Costs</p>
          <p class="text-xs text-purple-600 mt-2">
            <i class="fas fa-chart-line mr-1"></i>6.7% of revenue
          </p>
        </div>

        <!-- Unmatched Payments Card -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-credit-card text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">24</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$324K</h3>
          <p class="text-gray-600 text-sm">Unmatched Payments</p>
          <p class="text-xs text-blue-600 mt-2">
            <i class="fas fa-exclamation-circle mr-1"></i>Requires attention
          </p>
        </div>
      </div>

      <!-- Revenue and Bookings Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Trend Chart -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Revenue Trend</h3>
            <div class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">Aug - Feb</div>
          </div>
          <div class="chart-container relative h-64">
            <!-- Y-axis labels -->
            <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-gray-500">
              <span>$240,000</span>
              <span>$180,000</span>
              <span>$120,000</span>
              <span>$60,000</span>
              <span>$0</span>
            </div>
            
            <!-- Grid lines -->
            <div class="absolute left-8 right-0 top-0 h-full">
              <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="chart-grid-line" style="top: <?php echo $i * 25; ?>%"></div>
              <?php endfor; ?>
            </div>
            
            <!-- Chart bars -->
            <div class="absolute left-8 right-0 bottom-8 h-48 flex items-end justify-between px-4">
              <?php
              $revenueData = [180000, 120000, 150000, 210000, 180000, 190000, 220000];
              $months = ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'];
              $maxRevenue = max($revenueData);
              ?>
              <?php foreach ($revenueData as $index => $revenue): ?>
                <div class="flex flex-col items-center flex-1 mx-1">
                  <div class="chart-bar w-3/4 bg-gradient-to-t from-amber-500 to-amber-300 rounded-t-lg" 
                       style="height: <?php echo ($revenue / $maxRevenue) * 100; ?>%"
                       data-value="$<?php echo number_format($revenue); ?>">
                  </div>
                  <span class="text-xs text-gray-600 mt-2"><?php echo $months[$index]; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            
            <!-- X-axis label -->
            <div class="absolute left-8 right-0 bottom-0 h-8 text-center text-xs text-gray-600">
              Revenue ($)
            </div>
          </div>
        </div>

        <!-- Monthly Bookings Chart -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Monthly Bookings</h3>
            <div class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">Aug - Feb</div>
          </div>
          <div class="chart-container relative h-64">
            <!-- Y-axis labels -->
            <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-gray-500">
              <span>180</span>
              <span>135</span>
              <span>90</span>
              <span>45</span>
              <span>0</span>
            </div>
            
            <!-- Grid lines -->
            <div class="absolute left-8 right-0 top-0 h-full">
              <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="chart-grid-line" style="top: <?php echo $i * 25; ?>%"></div>
              <?php endfor; ?>
            </div>
            
            <!-- Chart bars -->
            <div class="absolute left-8 right-0 bottom-8 h-48 flex items-end justify-between px-4">
              <?php
              $bookingData = [120, 90, 110, 150, 130, 140, 160];
              $months = ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'];
              $maxBookings = max($bookingData);
              ?>
              <?php foreach ($bookingData as $index => $bookings): ?>
                <div class="flex flex-col items-center flex-1 mx-1">
                  <div class="chart-bar w-3/4 bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg" 
                       style="height: <?php echo ($bookings / $maxBookings) * 100; ?>%"
                       data-value="<?php echo $bookings; ?> bookings">
                  </div>
                  <span class="text-xs text-gray-600 mt-2"><?php echo $months[$index]; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
            
            <!-- X-axis label -->
            <div class="absolute left-8 right-0 bottom-0 h-8 text-center text-xs text-gray-600">
              Bookings
            </div>
          </div>
        </div>
      </div>

      <!-- Destination Distribution and Top Packages -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Destination Distribution -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Destination Distribution</h3>
          <div class="flex flex-col lg:flex-row items-center gap-8">
            <!-- Pie Chart -->
            <div class="relative w-48 h-48">
              <svg viewBox="0 0 100 100" class="w-full h-full">
                <?php
                $destinations = [
                  ['name' => 'Japan', 'percentage' => 35, 'color' => '#f59e0b'],
                  ['name' => 'Thailand', 'percentage' => 25, 'color' => '#10b981'],
                  ['name' => 'Singapore', 'percentage' => 15, 'color' => '#3b82f6'],
                  ['name' => 'Vietnam', 'percentage' => 12, 'color' => '#8b5cf6'],
                  ['name' => 'South Korea', 'percentage' => 8, 'color' => '#ef4444'],
                  ['name' => 'Others', 'percentage' => 5, 'color' => '#fbbf24']
                ];
                
                $total = 0;
                foreach ($destinations as $index => $dest) {
                  $startAngle = $total * 3.6;
                  $endAngle = ($total + $dest['percentage']) * 3.6;
                  $total += $dest['percentage'];
                  
                  $x1 = 50 + 40 * cos(deg2rad($startAngle - 90));
                  $y1 = 50 + 40 * sin(deg2rad($startAngle - 90));
                  $x2 = 50 + 40 * cos(deg2rad($endAngle - 90));
                  $y2 = 50 + 40 * sin(deg2rad($endAngle - 90));
                  
                  $largeArc = $dest['percentage'] > 50 ? 1 : 0;
                  
                  echo "<path d='M50,50 L$x1,$y1 A40,40 0 $largeArc,1 $x2,$y2 Z' 
                        fill='{$dest['color']}' 
                        class='transition-all duration-300 hover:opacity-80'
                        data-name='{$dest['name']}'
                        data-percentage='{$dest['percentage']}%'></path>";
                }
                ?>
                <circle cx="50" cy="50" r="15" fill="white"></circle>
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                  <div class="text-2xl font-black text-gray-900">100%</div>
                  <div class="text-xs text-gray-600">Total</div>
                </div>
              </div>
            </div>
            
            <!-- Legend -->
            <div class="flex-1">
              <div class="space-y-3">
                <?php foreach ($destinations as $index => $dest): ?>
                  <div class="flex items-center justify-between p-2 rounded-lg hover:bg-amber-50 transition-colors">
                    <div class="flex items-center gap-3">
                      <div class="w-4 h-4 rounded" style="background-color: <?php echo $dest['color']; ?>"></div>
                      <span class="font-medium text-gray-900"><?php echo $dest['name']; ?></span>
                    </div>
                    <span class="font-bold text-amber-700"><?php echo $dest['percentage']; ?>%</span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Performing Packages -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Top Performing Packages</h3>
          <div class="space-y-4">
            <?php
            $packages = [
              ['name' => 'Tokyo Adventure - 7 Days', 'bookings' => 45, 'revenue' => 67500],
              ['name' => 'Bangkok & Phuket Combo', 'bookings' => 38, 'revenue' => 45600],
              ['name' => 'Singapore City Escape', 'bookings' => 32, 'revenue' => 38400],
              ['name' => 'Vietnam Heritage Tour', 'bookings' => 28, 'revenue' => 33900]
            ];
            
            foreach ($packages as $index => $package):
            ?>
            <div class="flex items-center justify-between p-4 rounded-xl bg-amber-50/50 hover:bg-amber-50 transition-colors">
              <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-lg gold-gradient flex items-center justify-center font-bold text-white">
                  <?php echo $index + 1; ?>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900"><?php echo $package['name']; ?></h4>
                  <div class="flex items-center gap-4 mt-1">
                    <span class="text-xs text-gray-600">
                      <i class="fas fa-calendar-alt mr-1"></i>
                      <?php echo $package['bookings']; ?> bookings
                    </span>
                    <span class="text-xs text-amber-600 font-semibold">
                      <i class="fas fa-dollar-sign mr-1"></i>
                      $<?php echo number_format($package['revenue']); ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm font-bold text-amber-700">
                  $<?php echo number_format($package['revenue'] / $package['bookings']); ?>/booking
                </div>
                <div class="text-xs text-gray-600">Avg. revenue</div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          
          <!-- Performance Summary -->
          <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-semibold text-gray-900">Total Performance</h4>
                <p class="text-sm text-gray-600">All top packages combined</p>
              </div>
              <div class="text-right">
                <div class="text-lg font-black text-amber-700">$185,400</div>
                <div class="text-sm text-gray-600">Total Revenue</div>
              </div>
            </div>
            <div class="mt-2 text-xs text-gray-600 flex items-center gap-4">
              <span><i class="fas fa-check-circle text-green-500 mr-1"></i> 143 total bookings</span>
              <span><i class="fas fa-chart-line text-blue-500 mr-1"></i> +18% from last period</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-amber-200 bg-gradient-to-b from-white to-amber-40">
  <div class="max-w-6xl mx-auto px-4 sm:px-4 lg:px-8 py-8"> 
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Marketing Tools Column -->
      <div class="space-y-4">
        <h3 class="premium-font font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-chart-bar text-amber-500"></i>
          Marketing Tools
        </h3>
        <ul class="space-y-3 text-sm text-gray-700">
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-tachometer-alt w-5 text-amber-400"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-bullhorn w-5 text-amber-400"></i>
              Campaigns
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-users w-5 text-amber-400"></i>
              Lead Management
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-chart-pie w-5 text-amber-400"></i>
              Report Generator
            </a>
          </li>
        </ul>
      </div>

      <!-- Resources Column -->
      <div class="space-y-4">
        <h3 class="premium-font font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-book-open text-amber-500"></i>
          Resources
        </h3>
        <ul class="space-y-3 text-sm text-gray-700">
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-question-circle w-5 text-amber-400"></i>
              Help Center
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-code w-5 text-amber-400"></i>
              API Documentation
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-graduation-cap w-5 text-amber-400"></i>
              Tutorials
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-headset w-5 text-amber-400"></i>
              Support Center
            </a>
          </li>
        </ul>
      </div>

      <!-- Account Column -->
      <div class="space-y-4">
        <h3 class="premium-font font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-user-circle text-amber-500"></i>
          Account
        </h3>
        <ul class="space-y-3 text-sm text-gray-700">
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-cog w-5 text-amber-400"></i>
              Profile Settings
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-bell w-5 text-amber-400"></i>
              Notification Preferences
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-users-cog w-5 text-amber-400"></i>
              Team Management
            </a>
          </li>
          <li>
            <a href="logout.php" class="flex items-center gap-2 hover:text-amber-600 transition-colors">
              <i class="fas fa-sign-out-alt w-5 text-amber-400"></i>
              Logout
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-amber-40 my-2"></div>

    <!-- Bottom Footer -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
      <!-- Logo and Copyright -->
      <div class="flex items-center gap-3">
        <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-300 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
        <div>
          <p class="premium-font font-black text-gray-900">TravelEase Marketing Dashboard</p>
          <p class="text-xs mt-1">© <?php echo date('Y'); ?> TravelEase Marketing Platform. All rights reserved.</p>
        </div>
      </div>

      <!-- Status Badge -->
      <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">
          <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
          <span class="text-xs font-semibold">All Systems Operational</span>
        </div>
        <span class="text-xs px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-semibold">
          Premium Marketing Platform
        </span>
      </div>

      <!-- Version and Social -->
      <div class="flex items-center gap-4">
        <span class="text-xs">v2.5.1 • Last sync: Today, <?php echo date('H:i'); ?></span>
        <div class="flex items-center gap-2">
          <a href="#" class="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 hover:bg-amber-100">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 hover:bg-amber-100">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="#" class="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 hover:bg-amber-100">
            <i class="fas fa-envelope"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</footer>

  <!-- JavaScript -->
  <script>
    // Loading screen
    window.addEventListener('load', function() {
      const advancedLoader = document.getElementById('advanced-loader');
      if (advancedLoader) {
        setTimeout(() => {
          advancedLoader.style.opacity = '0';
          setTimeout(() => {
            advancedLoader.style.display = 'none';
          }, 500);
        }, 800);
      }
    });

    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      const mobileMenuClose = document.getElementById('mobile-menu-close');
      const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

      function toggleMobileMenu() {
        mobileMenu.classList.toggle('open');
        document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
      }

      if (menuButton) menuButton.addEventListener('click', toggleMobileMenu);
      if (mobileMenuClose) mobileMenuClose.addEventListener('click', toggleMobileMenu);
      if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', toggleMobileMenu);

      // Smooth scrolling for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          const href = this.getAttribute('href');
          if (!href || href === '#') return;
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      // Animate chart bars on scroll
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const chartObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.animation = 'chartLoad 1.5s ease-out forwards';
          }
        });
      }, observerOptions);

      document.querySelectorAll('.chart-bar').forEach(bar => {
        chartObserver.observe(bar);
      });

      // Add hover effects to cards
      document.querySelectorAll('.hover-lift').forEach(card => {
        card.addEventListener('mouseenter', () => {
          card.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', () => {
          card.style.transform = 'translateY(0)';
        });
      });

      // Add tooltips to chart bars
      document.querySelectorAll('.chart-bar[data-value]').forEach(bar => {
        bar.addEventListener('mouseenter', (e) => {
          const tooltip = document.createElement('div');
          tooltip.className = 'chart-tooltip';
          tooltip.textContent = e.target.getAttribute('data-value');
          tooltip.style.left = e.target.getBoundingClientRect().left + 'px';
          tooltip.style.top = e.target.getBoundingClientRect().top - 40 + 'px';
          document.body.appendChild(tooltip);
          
          e.target.addEventListener('mouseleave', () => {
            if (tooltip.parentNode) {
              tooltip.parentNode.removeChild(tooltip);
            }
          });
        });
      });

      // Pie chart interaction
      document.querySelectorAll('svg path[data-name]').forEach(path => {
        path.addEventListener('mouseenter', (e) => {
          const name = e.target.getAttribute('data-name');
          const percentage = e.target.getAttribute('data-percentage');
          
          const tooltip = document.createElement('div');
          tooltip.className = 'chart-tooltip';
          tooltip.innerHTML = `<strong>${name}</strong><br>${percentage}`;
          tooltip.style.left = e.clientX + 10 + 'px';
          tooltip.style.top = e.clientY - 10 + 'px';
          document.body.appendChild(tooltip);
          
          e.target.addEventListener('mouseleave', () => {
            if (tooltip.parentNode) {
              tooltip.parentNode.removeChild(tooltip);
            }
          });
          
          e.target.addEventListener('mousemove', (moveEvent) => {
            tooltip.style.left = moveEvent.clientX + 10 + 'px';
            tooltip.style.top = moveEvent.clientY - 10 + 'px';
          });
        });
      });
    });

    // Service Worker Registration for PWA
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
          .then(function(registration) {
            console.log('ServiceWorker registration successful');
          })
          .catch(function(err) {
            console.log('ServiceWorker registration failed: ', err);
          });
      });
    }
  </script>
</body>
</html>