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
  <title>Payment Matching | TravelEase - Financial Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Payment matching module for TravelEase luxury travel company.">
  
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
      max-width: 600px;
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
    
    /* Payment matching specific */
    .payment-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .payment-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    
    .payment-matched {
      border-left: 4px solid #10b981;
    }
    
    .payment-unmatched {
      border-left: 4px solid #f59e0b;
    }
    
    .payment-partial {
      border-left: 4px solid #f59e0b;
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="advanced-loader mx-auto mb-6"></div>
      <div class="premium-font text-2xl font-black text-gradient">TravelEase Finance</div>
      <p class="text-gray-600 mt-2">Loading Payment Matching...</p>
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
          <a href="Finance_Overview.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
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
          <a href="Finance_Payments.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="finance_manager_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase Finance
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Payment Matching System
              </span>
            </div>
          </a>
        </div>

        <!-- Center Navigation -->
        <div class="hidden lg:flex items-center gap-6 text-sm font-semibold">
          <a href="Finance_Overview.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-chart-line text-xs text-amber-500"></i>
              Overview
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
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
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Statements.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-invoice mr-2"></i>
            Statements
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="Finance_Payments.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-credit-card mr-2"></i>
            Payments
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
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

  <!-- Main Content -->
  <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 animate-fade-in-down">
        <h1 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
          <span class="text-gray-900">Payment</span>
          <span class="text-gradient block">Matching System</span>
        </h1>
        <p class="text-lg text-gray-700">
          Match incoming payments with bookings and manage payment reconciliation
        </p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-unlink text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">New</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$324K</h3>
          <p class="text-gray-600 text-sm">Unmatched Payments</p>
          <p class="text-xs text-blue-600 mt-2">
            <i class="fas fa-clock mr-1"></i>24 pending
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-calendar-times text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full status-pending">Unpaid</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$214K</h3>
          <p class="text-gray-600 text-sm">Unmatched Bookings</p>
          <p class="text-xs text-amber-600 mt-2">
            <i class="fas fa-exclamation-circle mr-1"></i>18 bookings
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-check-circle text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge">+156</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$1.8M</h3>
          <p class="text-gray-600 text-sm">Matched This Month</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-check-circle mr-1"></i>98% accuracy
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-robot text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full discount-badge">78.4%</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">78.4%</h3>
          <p class="text-gray-600 text-sm">Auto-Match Success Rate</p>
          <p class="text-xs text-purple-600 mt-2">
            <i class="fas fa-arrow-up mr-1"></i>+12% this month
          </p>
        </div>
      </div>

      <!-- Action Bar -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
          <button onclick="runAutoMatch()" class="px-4 py-2 rounded-xl gold-gradient text-sm font-semibold text-white">
            <i class="fas fa-robot mr-2"></i>Run Auto-Match
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <button onclick="openPaymentModal()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fas fa-link text-green-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Manual Match</h4>
                <p class="text-xs text-gray-600">Link payment to booking</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Manually match payments with bookings</p>
          </button>
          
          <button onclick="exportPayments()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-file-export text-blue-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Export Report</h4>
                <p class="text-xs text-gray-600">Generate payment reports</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Export matching data to Excel or PDF</p>
          </button>
          
          <button onclick="showReconciliation()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fas fa-balance-scale text-purple-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Reconcile</h4>
                <p class="text-xs text-gray-600">Account reconciliation</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Reconcile payment accounts</p>
          </button>
          
          <button onclick="viewRecentMatches()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fas fa-history text-amber-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Match History</h4>
                <p class="text-xs text-gray-600">Recent matches</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">View recently matched payments</p>
          </button>
        </div>
        
        <div class="mt-6 p-4 rounded-xl bg-amber-50/50 border border-amber-200">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Quick Filter</h4>
              <p class="text-sm text-gray-600">Filter unmatched payments by criteria</p>
            </div>
            <div class="flex items-center gap-3">
              <select class="px-4 py-2 rounded-lg border border-amber-200 bg-white">
                <option>All Payment Methods</option>
                <option>Credit Card Only</option>
                <option>Bank Transfer Only</option>
                <option>PayPal Only</option>
              </select>
              <select class="px-4 py-2 rounded-lg border border-amber-200 bg-white">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
              </select>
              <button onclick="applyFilters()" class="px-4 py-2 rounded-lg gold-gradient text-white font-semibold">
                Apply Filters
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Tabs -->
      <div class="mb-8">
        <div class="border-b border-amber-100">
          <nav class="-mb-px flex space-x-8">
            <button id="unmatched-tab" class="py-4 px-1 border-b-2 border-amber-500 font-semibold text-amber-600">
              <i class="fas fa-unlink mr-2"></i>Unmatched Payments
            </button>
            <button id="recent-tab" class="py-4 px-1 border-b-2 border-transparent font-semibold text-gray-500 hover:text-amber-600 hover:border-amber-300">
              <i class="fas fa-history mr-2"></i>Recent Matches
            </button>
            <button id="reports-tab" class="py-4 px-1 border-b-2 border-transparent font-semibold text-gray-500 hover:text-amber-600 hover:border-amber-300">
              <i class="fas fa-chart-bar mr-2"></i>Payment Reports
            </button>
          </nav>
        </div>
      </div>

      <!-- Tab Content -->
      <div id="unmatched-content" class="tab-content">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Unmatched Payments -->
          <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900">Unmatched Payments</h3>
              <span class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-800">24 Pending</span>
            </div>
            <div class="space-y-4">
              <?php 
              $unmatchedPayments = [
                ['id' => 'PMT-7842', 'date' => '2024-04-15', 'amount' => 12400, 'method' => 'Credit Card', 'details' => 'Last 4: 4321', 'status' => 'new'],
                ['id' => 'PMT-7841', 'date' => '2024-04-14', 'amount' => 8900, 'method' => 'Bank Transfer', 'details' => 'Ref: SPRING24', 'status' => 'new'],
                ['id' => 'PMT-7840', 'date' => '2024-04-13', 'amount' => 15600, 'method' => 'Credit Card', 'details' => 'Last 4: 8765', 'status' => 'review'],
                ['id' => 'PMT-7839', 'date' => '2024-04-12', 'amount' => 7400, 'method' => 'PayPal', 'details' => 'Email: client@example.com', 'status' => 'new'],
                ['id' => 'PMT-7838', 'date' => '2024-04-11', 'amount' => 21800, 'method' => 'Bank Transfer', 'details' => 'Ref: MALDIVES', 'status' => 'review'],
                ['id' => 'PMT-7832', 'date' => '2024-04-10', 'amount' => 9200, 'method' => 'Credit Card', 'details' => 'Last 4: 1234', 'status' => 'new'],
                ['id' => 'PMT-7831', 'date' => '2024-04-09', 'amount' => 11800, 'method' => 'Bank Transfer', 'details' => 'Ref: EARLYBIRD', 'status' => 'review'],
              ];
              foreach ($unmatchedPayments as $payment): 
                $statusColor = $payment['status'] == 'new' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800';
              ?>
              <div class="p-4 rounded-xl border border-amber-100 bg-white hover-lift payment-card payment-unmatched">
                <div class="flex items-center justify-between mb-2">
                  <span class="font-mono font-bold text-gray-900"><?php echo $payment['id']; ?></span>
                  <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full <?php echo $statusColor; ?>"><?php echo $payment['status']; ?></span>
                    <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800"><?php echo $payment['method']; ?></span>
                  </div>
                </div>
                <div class="flex justify-between items-center">
                  <div>
                    <p class="text-sm text-gray-600"><?php echo $payment['date']; ?></p>
                    <p class="text-xs text-gray-500"><?php echo $payment['details']; ?></p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-amber-700">$<?php echo number_format($payment['amount']); ?></p>
                    <div class="flex gap-2 mt-1">
                      <button onclick="matchPayment('<?php echo $payment['id']; ?>')" class="text-xs px-3 py-1 rounded-lg gold-gradient text-white">
                        Match Now
                      </button>
                      <button onclick="viewPaymentDetails('<?php echo $payment['id']; ?>')" class="text-xs px-3 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Details
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="mt-6">
              <button onclick="loadMorePayments()" class="w-full px-4 py-2 rounded-xl border border-amber-200 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fas fa-chevron-down mr-2"></i>Load More Payments
              </button>
            </div>
          </div>

          <!-- Unmatched Bookings -->
          <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900">Unmatched Bookings</h3>
              <span class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-800">18 Pending</span>
            </div>
            <div class="space-y-4">
              <?php 
              $unmatchedBookings = [
                ['id' => 'BK-4562', 'client' => 'Sarah Johnson', 'package' => 'Japan Luxury', 'amount' => 12400, 'date' => '2024-04-15', 'status' => 'unpaid'],
                ['id' => 'BK-4561', 'client' => 'Michael Chen', 'package' => 'Bali Retreat', 'amount' => 8900, 'date' => '2024-04-14', 'status' => 'unpaid'],
                ['id' => 'BK-4560', 'client' => 'Robert Williams', 'package' => 'Thailand Elite', 'amount' => 15600, 'date' => '2024-04-13', 'status' => 'partial'],
                ['id' => 'BK-4559', 'client' => 'James Wilson', 'package' => 'Vietnam Culture', 'amount' => 7400, 'date' => '2024-04-11', 'status' => 'unpaid'],
                ['id' => 'BK-4558', 'client' => 'Emma Davis', 'package' => 'Maldives Exclusive', 'amount' => 21800, 'date' => '2024-04-12', 'status' => 'unpaid'],
                ['id' => 'BK-4557', 'client' => 'Lisa Brown', 'package' => 'Sri Lanka Explorer', 'amount' => 9200, 'date' => '2024-04-10', 'status' => 'partial'],
                ['id' => 'BK-4556', 'client' => 'David Miller', 'package' => 'South Korea Tour', 'amount' => 11800, 'date' => '2024-04-09', 'status' => 'unpaid'],
              ];
              foreach ($unmatchedBookings as $booking): 
                $statusColor = $booking['status'] == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800';
              ?>
              <div class="p-4 rounded-xl border border-amber-100 bg-white hover-lift payment-card payment-unmatched">
                <div class="flex items-center justify-between mb-2">
                  <span class="font-mono font-bold text-gray-900"><?php echo $booking['id']; ?></span>
                  <span class="text-xs px-2 py-1 rounded-full <?php echo $statusColor; ?>"><?php echo $booking['status']; ?></span>
                </div>
                <div class="mb-2">
                  <p class="font-medium text-gray-900"><?php echo $booking['client']; ?></p>
                  <p class="text-sm text-gray-600"><?php echo $booking['package']; ?></p>
                </div>
                <div class="flex justify-between items-center">
                  <p class="text-sm text-gray-600">Booked: <?php echo $booking['date']; ?></p>
                  <div class="text-right">
                    <p class="font-bold text-amber-700">$<?php echo number_format($booking['amount']); ?></p>
                    <div class="flex gap-2 mt-1">
                      <button onclick="matchBooking('<?php echo $booking['id']; ?>')" class="text-xs px-3 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200">
                        Find Payment
                      </button>
                      <button onclick="viewBooking('<?php echo $booking['id']; ?>')" class="text-xs px-3 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                        View
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="mt-6">
              <button onclick="loadMoreBookings()" class="w-full px-4 py-2 rounded-xl border border-amber-200 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fas fa-chevron-down mr-2"></i>Load More Bookings
              </button>
            </div>
          </div>
        </div>
      </div>

      <div id="recent-content" class="tab-content hidden">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Recently Matched Payments</h3>
            <div class="flex items-center gap-2">
              <select class="text-sm border border-amber-200 rounded-lg px-3 py-1 bg-amber-50">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
              </select>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="finance-table">
              <thead>
                <tr>
                  <th>Payment ID</th>
                  <th>Booking ID</th>
                  <th>Client</th>
                  <th>Amount</th>
                  <th>Matched By</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $matchedPayments = [
                  ['payment' => 'PMT-7837', 'booking' => 'BK-4557', 'client' => 'Lisa Brown', 'amount' => 9200, 'by' => 'Auto-Match', 'date' => '2024-04-10', 'status' => 'Confirmed', 'notes' => 'Exact match'],
                  ['payment' => 'PMT-7836', 'booking' => 'BK-4556', 'client' => 'David Miller', 'amount' => 11800, 'by' => 'Admin', 'date' => '2024-04-09', 'status' => 'Confirmed', 'notes' => 'Manual match'],
                  ['payment' => 'PMT-7835', 'booking' => 'BK-4555', 'client' => 'Maria Garcia', 'amount' => 15600, 'by' => 'Auto-Match', 'date' => '2024-04-08', 'status' => 'Confirmed', 'notes' => 'Exact match'],
                  ['payment' => 'PMT-7834', 'booking' => 'BK-4554', 'client' => 'John Smith', 'amount' => 7400, 'by' => 'Admin', 'date' => '2024-04-07', 'status' => 'Confirmed', 'notes' => 'Partial match'],
                  ['payment' => 'PMT-7833', 'booking' => 'BK-4553', 'client' => 'Anna Taylor', 'amount' => 21800, 'by' => 'Auto-Match', 'date' => '2024-04-06', 'status' => 'Confirmed', 'notes' => 'Exact match'],
                  ['payment' => 'PMT-7830', 'booking' => 'BK-4552', 'client' => 'Thomas Lee', 'amount' => 14500, 'by' => 'Admin', 'date' => '2024-04-05', 'status' => 'Confirmed', 'notes' => 'Manual match'],
                  ['payment' => 'PMT-7829', 'booking' => 'BK-4551', 'client' => 'Susan Wilson', 'amount' => 8900, 'by' => 'Auto-Match', 'date' => '2024-04-04', 'status' => 'Confirmed', 'notes' => 'Exact match'],
                ];
                foreach ($matchedPayments as $match): 
                ?>
                <tr class="hover-lift">
                  <td class="font-mono"><?php echo $match['payment']; ?></td>
                  <td class="font-mono"><?php echo $match['booking']; ?></td>
                  <td class="font-medium"><?php echo $match['client']; ?></td>
                  <td class="font-bold text-amber-700">$<?php echo number_format($match['amount']); ?></td>
                  <td><span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800"><?php echo $match['by']; ?></span></td>
                  <td class="text-sm text-gray-600"><?php echo $match['date']; ?></td>
                  <td><span class="text-xs px-2 py-1 rounded-full status-active"><?php echo $match['status']; ?></span></td>
                  <td>
                    <div class="flex gap-2">
                      <button onclick="viewMatchDetails('<?php echo $match['payment']; ?>', '<?php echo $match['booking']; ?>')" class="text-blue-600 hover:text-blue-700 transition-colors" title="View">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button onclick="exportMatch('<?php echo $match['payment']; ?>')" class="text-amber-600 hover:text-amber-700 transition-colors" title="Export">
                        <i class="fas fa-download"></i>
                      </button>
                      <button onclick="unmatchPayment('<?php echo $match['payment']; ?>')" class="text-gray-400 hover:text-red-600 transition-colors" title="Unmatch">
                        <i class="fas fa-unlink"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="mt-6">
            <button onclick="loadMoreMatches()" class="w-full px-4 py-2 rounded-xl border border-amber-200 text-amber-700 hover:bg-amber-50 transition-colors">
              <i class="fas fa-chevron-down mr-2"></i>Load More Matches
            </button>
          </div>
        </div>
      </div>

      <div id="reports-content" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Match Statistics -->
          <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Match Statistics</h3>
            <div class="space-y-6">
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-700">Auto-Match Success Rate</span>
                  <span class="font-bold text-green-600">78.4%</span>
                </div>
                <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-green-500 to-green-300 rounded-full" style="width: 78.4%"></div>
                </div>
              </div>
              
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-700">Average Match Time</span>
                  <span class="font-bold text-blue-600">2.4 hours</span>
                </div>
                <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-blue-500 to-blue-300 rounded-full" style="width: 60%"></div>
                </div>
              </div>
              
              <div>
                <div class="flex justify-between text-sm mb-1">
                  <span class="text-gray-700">Manual Intervention Required</span>
                  <span class="font-bold text-amber-600">21.6%</span>
                </div>
                <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-amber-500 to-amber-300 rounded-full" style="width: 21.6%"></div>
                </div>
              </div>
            </div>
            
            <div class="mt-6 p-4 rounded-xl bg-amber-50/50 border border-amber-200">
              <h4 class="font-semibold text-gray-900 mb-2">Performance Insights</h4>
              <ul class="text-sm text-gray-700 space-y-1">
                <li class="flex items-start gap-2">
                  <i class="fas fa-check text-green-500 mt-0.5"></i>
                  <span>Auto-match performance improved by 12% this month</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fas fa-check text-green-500 mt-0.5"></i>
                  <span>Bank transfers have highest match success rate (94%)</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                  <span>PayPal payments require manual review 38% of the time</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Monthly Report -->
          <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900">Monthly Report - April 2024</h3>
              <button onclick="downloadReport()" class="px-3 py-1 rounded-lg gold-gradient text-xs text-white">
                <i class="fas fa-download mr-1"></i>PDF
              </button>
            </div>
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div class="p-3 rounded-xl bg-amber-50">
                  <p class="text-sm text-gray-600">Total Payments</p>
                  <p class="text-2xl font-black text-amber-700">184</p>
                </div>
                <div class="p-3 rounded-xl bg-green-50">
                  <p class="text-sm text-gray-600">Successfully Matched</p>
                  <p class="text-2xl font-black text-green-700">156</p>
                </div>
              </div>
              
              <div class="p-4 rounded-xl border border-amber-100">
                <h4 class="font-semibold text-gray-900 mb-3">By Payment Method</h4>
                <div class="space-y-3">
                  <?php 
                  $paymentMethods = [
                    ['method' => 'Credit Card', 'count' => 84, 'matched' => 72, 'percentage' => 85.7],
                    ['method' => 'Bank Transfer', 'count' => 56, 'matched' => 52, 'percentage' => 92.9],
                    ['method' => 'PayPal', 'count' => 44, 'matched' => 32, 'percentage' => 72.7],
                  ];
                  foreach ($paymentMethods as $method): 
                  ?>
                  <div>
                    <div class="flex justify-between text-sm mb-1">
                      <span class="text-gray-700"><?php echo $method['method']; ?></span>
                      <span class="font-semibold"><?php echo $method['matched']; ?>/<?php echo $method['count']; ?></span>
                    </div>
                    <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                      <div class="h-full bg-gradient-to-r from-amber-500 to-amber-300 rounded-full" style="width: <?php echo $method['percentage']; ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1"><?php echo $method['percentage']; ?>% match rate</p>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <div class="p-4 rounded-xl border border-amber-100">
                <h4 class="font-semibold text-gray-900 mb-3">Weekly Performance</h4>
                <div class="flex items-end h-24 gap-2">
                  <?php 
                  $weeklyData = [18, 22, 24, 20, 26, 30, 16];
                  $maxValue = max($weeklyData);
                  $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                  foreach ($weeklyData as $index => $value):
                    $height = ($value / $maxValue) * 100;
                  ?>
                  <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-amber-500 to-amber-300 rounded-t-lg" style="height: <?php echo $height; ?>%"></div>
                    <span class="text-xs text-gray-600 mt-2"><?php echo $days[$index]; ?></span>
                    <span class="text-xs font-semibold text-amber-700"><?php echo $value; ?></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>


  <!-- Modals -->
  <div id="paymentModal" class="modal-overlay">
    <div class="modal-content">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Match Payment to Booking</h3>
          <button onclick="closeModal('paymentModal')" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <form id="paymentForm" onsubmit="processPaymentMatch(event)">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment</label>
              <select id="paymentSelect" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
                <option value="">Select a payment...</option>
                <option value="PMT-7842">PMT-7842 - $12,400 (Credit Card) - Apr 15</option>
                <option value="PMT-7841">PMT-7841 - $8,900 (Bank Transfer) - Apr 14</option>
                <option value="PMT-7840">PMT-7840 - $15,600 (Credit Card) - Apr 13</option>
                <option value="PMT-7839">PMT-7839 - $7,400 (PayPal) - Apr 12</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Booking</label>
              <select id="bookingSelect" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
                <option value="">Select a booking...</option>
                <option value="BK-4562">BK-4562 - Sarah Johnson - Japan Luxury - $12,400</option>
                <option value="BK-4561">BK-4561 - Michael Chen - Bali Retreat - $8,900</option>
                <option value="BK-4560">BK-4560 - Robert Williams - Thailand Elite - $15,600</option>
                <option value="BK-4559">BK-4559 - James Wilson - Vietnam Culture - $7,400</option>
              </select>
            </div>
            
            <div id="matchPreview" class="p-4 rounded-xl bg-amber-50/50 border border-amber-200 hidden">
              <h4 class="font-semibold text-gray-900 mb-2">Match Preview</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Payment Amount:</span>
                  <span id="previewPaymentAmount" class="font-semibold text-amber-700"></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Booking Amount:</span>
                  <span id="previewBookingAmount" class="font-semibold text-amber-700"></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Difference:</span>
                  <span id="previewDifference" class="font-semibold"></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Match Status:</span>
                  <span id="previewStatus" class="font-semibold"></span>
                </div>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
              <textarea class="w-full px-4 py-2 rounded-xl border border-amber-200" rows="3" placeholder="Add any notes about this match..."></textarea>
            </div>
            
            <div>
              <label class="flex items-center">
                <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500" checked>
                <span class="ml-2 text-sm text-gray-700">Send confirmation email to client</span>
              </label>
              <label class="flex items-center mt-2">
                <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                <span class="ml-2 text-sm text-gray-700">Flag for accountant review</span>
              </label>
            </div>
          </div>
          <div class="mt-8 flex justify-end gap-3">
            <button type="button" onclick="closeModal('paymentModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
              Confirm Match
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="detailsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900" id="detailsTitle">Payment Details</h3>
          <button onclick="closeModal('detailsModal')" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div id="detailsContent">
          <!-- Details will be loaded here -->
        </div>
      </div>
    </div>
  </div>

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

      // Payment card hover effects
      document.querySelectorAll('.payment-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-4px)';
          this.style.boxShadow = '0 10px 25px -5px rgba(245, 158, 11, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
          this.style.boxShadow = '';
        });
      });
    });

    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
      const tabs = {
        'unmatched-tab': 'unmatched-content',
        'recent-tab': 'recent-content',
        'reports-tab': 'reports-content'
      };
      
      Object.keys(tabs).forEach(tabId => {
        const tab = document.getElementById(tabId);
        if (tab) {
          tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('[id$="-tab"]').forEach(t => {
              t.classList.remove('border-amber-500', 'text-amber-600');
              t.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-amber-500', 'text-amber-600');
            
            // Show selected content
            document.querySelectorAll('.tab-content').forEach(content => {
              content.classList.add('hidden');
            });
            document.getElementById(tabs[tabId]).classList.remove('hidden');
          });
        }
      });
      
      // Payment form preview
      const paymentSelect = document.getElementById('paymentSelect');
      const bookingSelect = document.getElementById('bookingSelect');
      const matchPreview = document.getElementById('matchPreview');
      
      function updateMatchPreview() {
        const paymentText = paymentSelect.options[paymentSelect.selectedIndex]?.text || '';
        const bookingText = bookingSelect.options[bookingSelect.selectedIndex]?.text || '';
        
        if (paymentText && bookingText) {
          // Extract amounts (simplified parsing - in real app, get from data attributes)
          const paymentAmount = parseFloat(paymentText.match(/\$([\d,]+)/)?.[1].replace(',', '') || 0);
          const bookingAmount = parseFloat(bookingText.match(/\$([\d,]+)/)?.[1].replace(',', '') || 0);
          
          document.getElementById('previewPaymentAmount').textContent = '$' + paymentAmount.toLocaleString();
          document.getElementById('previewBookingAmount').textContent = '$' + bookingAmount.toLocaleString();
          
          const difference = paymentAmount - bookingAmount;
          const differenceElem = document.getElementById('previewDifference');
          const statusElem = document.getElementById('previewStatus');
          
          if (difference === 0) {
            differenceElem.textContent = '$0';
            differenceElem.className = 'font-semibold text-green-600';
            statusElem.textContent = 'Exact Match ✓';
            statusElem.className = 'font-semibold text-green-600';
          } else if (Math.abs(difference) < 100) {
            differenceElem.textContent = '$' + Math.abs(difference).toLocaleString() + ' ' + (difference > 0 ? 'over' : 'under');
            differenceElem.className = 'font-semibold text-yellow-600';
            statusElem.textContent = 'Minor Difference';
            statusElem.className = 'font-semibold text-yellow-600';
          } else {
            differenceElem.textContent = '$' + Math.abs(difference).toLocaleString() + ' ' + (difference > 0 ? 'over' : 'under');
            differenceElem.className = 'font-semibold text-red-600';
            statusElem.textContent = 'Significant Difference ⚠';
            statusElem.className = 'font-semibold text-red-600';
          }
          
          matchPreview.classList.remove('hidden');
        } else {
          matchPreview.classList.add('hidden');
        }
      }
      
      if (paymentSelect) paymentSelect.addEventListener('change', updateMatchPreview);
      if (bookingSelect) bookingSelect.addEventListener('change', updateMatchPreview);
    });

    // Modal functions
    function openPaymentModal() {
      document.getElementById('paymentModal').style.display = 'flex';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    function processPaymentMatch(e) {
      e.preventDefault();
      const paymentId = document.getElementById('paymentSelect').value;
      const bookingId = document.getElementById('bookingSelect').value;
      
      // Simulate API call
      setTimeout(() => {
        alert(`Successfully matched ${paymentId} to ${bookingId}`);
        closeModal('paymentModal');
        // In real app, refresh the data
        location.reload();
      }, 500);
    }

    // Action functions
    function matchPayment(paymentId) {
      openPaymentModal();
      // Pre-select the payment
      const select = document.getElementById('paymentSelect');
      for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === paymentId) {
          select.selectedIndex = i;
          break;
        }
      }
      updateMatchPreview();
    }

    function matchBooking(bookingId) {
      openPaymentModal();
      // Pre-select the booking
      const select = document.getElementById('bookingSelect');
      for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === bookingId) {
          select.selectedIndex = i;
          break;
        }
      }
      updateMatchPreview();
    }

    function viewPaymentDetails(paymentId) {
      document.getElementById('detailsTitle').textContent = 'Payment Details - ' + paymentId;
      document.getElementById('detailsContent').innerHTML = `
        <div class="space-y-4">
          <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
            <h4 class="font-semibold text-gray-900 mb-2">Payment Information</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Payment ID:</span>
                <span class="font-mono font-semibold">${paymentId}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Amount:</span>
                <span class="font-bold text-amber-700">$12,400</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Date Received:</span>
                <span>2024-04-15</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Payment Method:</span>
                <span>Credit Card (Last 4: 4321)</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800 text-xs">Unmatched</span>
              </div>
            </div>
          </div>
          
          <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-semibold text-gray-900 mb-2">Suggested Matches</h4>
            <div class="space-y-2">
              <div class="flex items-center justify-between p-2 rounded-lg bg-white">
                <div>
                  <p class="font-medium">BK-4562 - Sarah Johnson</p>
                  <p class="text-xs text-gray-600">Japan Luxury • $12,400</p>
                </div>
                <button onclick="matchBooking('BK-4562')" class="text-xs px-3 py-1 rounded-lg gold-gradient text-white">
                  Match Now
                </button>
              </div>
            </div>
          </div>
          
          <div class="p-4 rounded-xl border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">Raw Payment Data</h4>
            <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-auto">{
  "payment_id": "${paymentId}",
  "amount": 12400,
  "currency": "USD",
  "method": "credit_card",
  "card_last4": "4321",
  "received_at": "2024-04-15T10:30:00Z",
  "processor": "Stripe",
  "processor_id": "ch_3P1..."
}</pre>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button onclick="closeModal('detailsModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
            Close
          </button>
          <button onclick="matchPayment('${paymentId}')" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
            Match This Payment
          </button>
        </div>
      `;
      document.getElementById('detailsModal').style.display = 'flex';
    }

    function viewBooking(bookingId) {
      document.getElementById('detailsTitle').textContent = 'Booking Details - ' + bookingId;
      document.getElementById('detailsContent').innerHTML = `
        <div class="space-y-4">
          <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
            <h4 class="font-semibold text-gray-900 mb-2">Booking Information</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Booking ID:</span>
                <span class="font-mono font-semibold">${bookingId}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Client:</span>
                <span class="font-medium">Sarah Johnson</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Package:</span>
                <span>Japan Luxury Experience</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Amount Due:</span>
                <span class="font-bold text-amber-700">$12,400</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Booked Date:</span>
                <span>2024-04-15</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Travel Dates:</span>
                <span>2024-06-10 to 2024-06-20</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Payment Status:</span>
                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs">Unpaid</span>
              </div>
            </div>
          </div>
          
          <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-semibold text-gray-900 mb-2">Suggested Payments</h4>
            <div class="space-y-2">
              <div class="flex items-center justify-between p-2 rounded-lg bg-white">
                <div>
                  <p class="font-medium">PMT-7842 - Credit Card</p>
                  <p class="text-xs text-gray-600">$12,400 • Received Apr 15</p>
                </div>
                <button onclick="matchPayment('PMT-7842')" class="text-xs px-3 py-1 rounded-lg gold-gradient text-white">
                  Match Now
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button onclick="closeModal('detailsModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
            Close
          </button>
          <button onclick="matchBooking('${bookingId}')" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
            Find Payment
          </button>
        </div>
      `;
      document.getElementById('detailsModal').style.display = 'flex';
    }

    function viewMatchDetails(paymentId, bookingId) {
      document.getElementById('detailsTitle').textContent = 'Match Details - ' + paymentId + ' to ' + bookingId;
      document.getElementById('detailsContent').innerHTML = `
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
              <h4 class="font-semibold text-gray-900 mb-2">Payment</h4>
              <div class="space-y-1 text-sm">
                <p><span class="text-gray-600">ID:</span> <span class="font-mono">${paymentId}</span></p>
                <p><span class="text-gray-600">Amount:</span> <span class="font-bold text-amber-700">$12,400</span></p>
                <p><span class="text-gray-600">Method:</span> Credit Card</p>
                <p><span class="text-gray-600">Date:</span> 2024-04-10</p>
              </div>
            </div>
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
              <h4 class="font-semibold text-gray-900 mb-2">Booking</h4>
              <div class="space-y-1 text-sm">
                <p><span class="text-gray-600">ID:</span> <span class="font-mono">${bookingId}</span></p>
                <p><span class="text-gray-600">Client:</span> Lisa Brown</p>
                <p><span class="text-gray-600">Package:</span> Sri Lanka Explorer</p>
                <p><span class="text-gray-600">Amount:</span> <span class="font-bold text-amber-700">$9,200</span></p>
              </div>
            </div>
          </div>
          
          <div class="p-4 rounded-xl bg-green-50 border border-green-200">
            <h4 class="font-semibold text-gray-900 mb-2">Match Information</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Matched By:</span>
                <span>Auto-Match System</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Match Date:</span>
                <span>2024-04-10 14:32:15</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Match Type:</span>
                <span>Exact Amount Match</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Confidence Score:</span>
                <span class="font-bold text-green-600">98%</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800">Confirmed</span>
              </div>
            </div>
          </div>
          
          <div class="p-4 rounded-xl border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-2">Audit Trail</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Created:</span>
                <span>2024-04-10 14:32:15 by System</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Verified:</span>
                <span>2024-04-10 15:45:22 by Admin</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Last Modified:</span>
                <span>2024-04-10 15:45:22</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
          <button onclick="closeModal('detailsModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
            Close
          </button>
          <button onclick="unmatchPayment('${paymentId}')" class="px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 font-semibold">
            <i class="fas fa-unlink mr-1"></i> Unmatch
          </button>
        </div>
      `;
      document.getElementById('detailsModal').style.display = 'flex';
    }

    function unmatchPayment(paymentId) {
      if (confirm(`Are you sure you want to unmatch payment ${paymentId}? This will return it to the unmatched list.`)) {
        // Simulate API call
        setTimeout(() => {
          alert(`Payment ${paymentId} has been unmatched`);
          closeModal('detailsModal');
          // In real app, refresh the data
          location.reload();
        }, 500);
      }
    }

    // Other action functions
    function runAutoMatch() {
      document.getElementById('advanced-loader').style.display = 'flex';
      setTimeout(() => {
        document.getElementById('advanced-loader').style.display = 'none';
        alert('Auto-match completed! Found 8 new matches.');
        // In real app, refresh the data
        location.reload();
      }, 2000);
    }

    function exportPayments() {
      alert('Exporting payment report... Download will start shortly.');
      // In real app, trigger file download
    }

    function showReconciliation() {
      alert('Opening account reconciliation tool...');
      // In real app, open reconciliation interface
    }

    function viewRecentMatches() {
      // Switch to recent matches tab
      document.getElementById('recent-tab').click();
    }

    function downloadReport() {
      alert('Downloading monthly report PDF...');
      // In real app, trigger PDF download
    }

    function exportMatch(matchId) {
      alert(`Exporting match details for ${matchId}...`);
      // In real app, trigger export
    }

    function applyFilters() {
      alert('Applying filters...');
      // In real app, apply filters and reload data
    }

    function loadMorePayments() {
      alert('Loading more payments...');
      // In real app, make AJAX request
    }

    function loadMoreBookings() {
      alert('Loading more bookings...');
      // In real app, make AJAX request
    }

    function loadMoreMatches() {
      alert('Loading more matches...');
      // In real app, make AJAX request
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
      }
    };
  </script>
</body>
</html>