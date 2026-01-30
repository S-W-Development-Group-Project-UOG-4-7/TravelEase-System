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
  <title>Financial Statements | TravelEase - Financial Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Financial statements management for TravelEase luxury travel company.">
  
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
    
    /* Statement specific */
    .statement-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .statement-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    
    .statement-finalized {
      border-left: 4px solid #10b981;
    }
    
    .statement-draft {
      border-left: 4px solid #f59e0b;
    }
    
    .statement-archived {
      border-left: 4px solid #6b7280;
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="advanced-loader mx-auto mb-6"></div>
      <div class="premium-font text-2xl font-black text-gradient">TravelEase Finance</div>
      <p class="text-gray-600 mt-2">Loading Financial Statements...</p>
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
          <a href="Finance_Statements.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="finance_manager_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase Finance
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Financial Statements Management
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
          <a href="Finance_Statements.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-invoice mr-2"></i>
            Statements
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
          </a>
          <a href="Finance_Payments.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-credit-card mr-2"></i>
            Payments
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
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

  <!-- Financial Statements Management -->
  <section class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Welcome Header -->
      <div class="mb-8 animate-fade-in-down">
        <h1 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
          <span class="text-gray-900">Financial</span>
          <span class="text-gradient block">Statements</span>
        </h1>
        <p class="text-lg text-gray-700">
          Generate, review, and manage financial statements and reports
        </p>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-file-invoice-dollar text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge">Q1 2024</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">18</h3>
          <p class="text-gray-600 text-sm">Statements Generated</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-check-circle mr-1"></i>All finalized
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-balance-scale text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">Pending</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">3</h3>
          <p class="text-gray-600 text-sm">Statements in Draft</p>
          <p class="text-xs text-blue-600 mt-2">
            <i class="fas fa-clock mr-1"></i>Require review
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-chart-bar text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full discount-badge">+24.5%</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$2.8M</h3>
          <p class="text-gray-600 text-sm">Q1 Total Revenue</p>
          <p class="text-xs text-purple-600 mt-2">
            <i class="fas fa-arrow-up mr-1"></i>YoY growth
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-percentage text-white"></i>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">22.6%</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">22.6%</h3>
          <p class="text-gray-600 text-sm">Net Profit Margin</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-trophy mr-1"></i>Industry leading
          </p>
        </div>
      </div>

      <!-- Statement Quick Actions -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Generate New Statement</h3>
          <button onclick="openStatementModal()" class="px-4 py-2 rounded-xl gold-gradient text-sm font-semibold text-white">
            <i class="fas fa-plus mr-2"></i>Advanced Options
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button onclick="generateIncomeStatement()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-green-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Income Statement</h4>
                <p class="text-xs text-gray-600">Profit & Loss Report</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Generate monthly or quarterly income statement</p>
          </button>
          
          <button onclick="generateBalanceSheet()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-balance-scale text-blue-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Balance Sheet</h4>
                <p class="text-xs text-gray-600">Assets & Liabilities</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Create balance sheet for specific date</p>
          </button>
          
          <button onclick="generateCashFlow()" class="p-4 rounded-xl border border-amber-100 hover:bg-amber-50 transition-all text-left">
            <div class="flex items-center gap-3 mb-3">
              <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-purple-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Cash Flow</h4>
                <p class="text-xs text-gray-600">Cash Movement Report</p>
              </div>
            </div>
            <p class="text-sm text-gray-700">Analyze cash inflows and outflows</p>
          </button>
        </div>
        
        <div class="mt-6 p-4 rounded-xl bg-amber-50/50 border border-amber-200">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-semibold text-gray-900 mb-1">Quick Generate</h4>
              <p class="text-sm text-gray-600">Select period and generate statement instantly</p>
            </div>
            <div class="flex items-center gap-3">
              <select class="px-4 py-2 rounded-lg border border-amber-200 bg-white">
                <option>March 2024</option>
                <option>Q1 2024</option>
                <option>April 2024 (Current)</option>
                <option>Custom Range</option>
              </select>
              <button onclick="quickGenerate()" class="px-4 py-2 rounded-lg gold-gradient text-white font-semibold">
                Generate
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Statements -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Income Statement -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Income Statement - Q1 2024</h3>
            <div class="flex items-center gap-2">
              <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge">Finalized</span>
              <button onclick="exportStatement('income')" class="text-amber-600 hover:text-amber-700">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
          
          <div class="space-y-4">
            <?php 
            $incomeItems = [
              ['label' => 'Total Revenue', 'amount' => 2800000, 'type' => 'revenue', 'percentage' => '100%'],
              ['label' => 'Cost of Sales', 'amount' => 1250000, 'type' => 'expense', 'percentage' => '44.6%'],
              ['label' => 'Gross Profit', 'amount' => 1550000, 'type' => 'profit', 'percentage' => '55.4%'],
              ['label' => 'Operating Expenses', 'amount' => 708000, 'type' => 'expense', 'percentage' => '25.3%'],
              ['label' => 'Operating Income', 'amount' => 842000, 'type' => 'profit', 'percentage' => '30.1%'],
              ['label' => 'Tax Expense', 'amount' => 210500, 'type' => 'expense', 'percentage' => '7.5%'],
              ['label' => 'Net Income', 'amount' => 631500, 'type' => 'net', 'percentage' => '22.6%'],
            ];
            foreach ($incomeItems as $item): 
              $textColor = $item['type'] == 'net' ? 'text-green-700' : 
                          ($item['type'] == 'profit' ? 'text-amber-700' : 'text-gray-700');
              $bgColor = $item['type'] == 'net' ? 'bg-green-50' : 
                        ($item['type'] == 'profit' ? 'bg-amber-50' : 'bg-white');
              $borderColor = $item['type'] == 'net' ? 'border-green-200' : 
                           ($item['type'] == 'profit' ? 'border-amber-200' : 'border-gray-200');
            ?>
            <div class="flex justify-between items-center p-3 rounded-lg <?php echo $bgColor; ?> border <?php echo $borderColor; ?>">
              <div>
                <span class="font-medium <?php echo $textColor; ?>"><?php echo $item['label']; ?></span>
                <span class="text-xs text-gray-500 ml-2"><?php echo $item['percentage']; ?></span>
              </div>
              <span class="font-bold <?php echo $textColor; ?>">$<?php echo number_format($item['amount']); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          
          <div class="mt-6 pt-6 border-t border-amber-200">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Gross Margin:</span>
              <span class="font-semibold text-green-600">55.4%</span>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-sm text-gray-600">Operating Margin:</span>
              <span class="font-semibold text-green-600">30.1%</span>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-sm text-gray-600">Net Margin:</span>
              <span class="font-semibold text-green-600">22.6%</span>
            </div>
          </div>
          
          <div class="mt-6 flex justify-between">
            <button onclick="printStatement('income')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              <i class="fas fa-print mr-2"></i>Print
            </button>
            <button onclick="shareStatement('income')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              <i class="fas fa-share mr-2"></i>Share
            </button>
            <button onclick="editStatement('income')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              <i class="fas fa-edit mr-2"></i>Edit
            </button>
          </div>
        </div>

        <!-- Balance Sheet -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Balance Sheet - Mar 31, 2024</h3>
            <div class="flex items-center gap-2">
              <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">Draft</span>
              <button onclick="exportStatement('balance')" class="text-amber-600 hover:text-amber-700">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
          
          <div class="space-y-6">
            <!-- Assets -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Assets</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Cash & Equivalents</span>
                  <span class="text-sm font-semibold text-blue-600">$1,240,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Accounts Receivable</span>
                  <span class="text-sm font-semibold text-blue-600">$420,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Inventory</span>
                  <span class="text-sm font-semibold text-blue-600">$180,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Property & Equipment</span>
                  <span class="text-sm font-semibold text-blue-600">$850,000</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200">
                  <span class="font-medium text-gray-900">Total Assets</span>
                  <span class="font-bold text-blue-700">$2,690,000</span>
                </div>
              </div>
            </div>
            
            <!-- Liabilities & Equity -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Liabilities & Equity</h4>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Accounts Payable</span>
                  <span class="text-sm font-semibold text-amber-600">$320,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Accrued Expenses</span>
                  <span class="text-sm font-semibold text-amber-600">$180,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Long-term Debt</span>
                  <span class="text-sm font-semibold text-amber-600">$850,000</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-700">Retained Earnings</span>
                  <span class="text-sm font-semibold text-green-600">$1,340,000</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200">
                  <span class="font-medium text-gray-900">Total L&E</span>
                  <span class="font-bold text-blue-700">$2,690,000</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-6 border-t border-amber-200">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Current Ratio:</span>
              <span class="font-semibold text-green-600">2.3:1</span>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-sm text-gray-600">Debt to Equity:</span>
              <span class="font-semibold text-amber-600">0.8:1</span>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-sm text-gray-600">Working Capital:</span>
              <span class="font-semibold text-green-600">$1,160,000</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Statement History -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Statement History & Archive</h3>
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Filter:</span>
            <select class="text-sm border border-amber-200 rounded-lg px-3 py-1 bg-amber-50">
              <option>All Statements</option>
              <option>Income Statements</option>
              <option>Balance Sheets</option>
              <option>Cash Flow Statements</option>
              <option>Finalized Only</option>
              <option>Drafts</option>
            </select>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="finance-table">
            <thead>
              <tr>
                <th>Statement ID</th>
                <th>Type</th>
                <th>Period</th>
                <th>Generated</th>
                <th>Revenue</th>
                <th>Net Income</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $statements = [
                ['id' => 'INC-2024-Q1', 'type' => 'Income Statement', 'period' => 'Q1 2024', 'generated' => '2024-04-05', 'revenue' => 2800000, 'net' => 631500, 'status' => 'Finalized'],
                ['id' => 'BAL-2024-Q1', 'type' => 'Balance Sheet', 'period' => 'Mar 31, 2024', 'generated' => '2024-04-06', 'revenue' => 0, 'net' => 0, 'status' => 'Draft'],
                ['id' => 'INC-2024-03', 'type' => 'Income Statement', 'period' => 'Mar 2024', 'generated' => '2024-04-01', 'revenue' => 950000, 'net' => 214000, 'status' => 'Finalized'],
                ['id' => 'CF-2024-Q1', 'type' => 'Cash Flow', 'period' => 'Q1 2024', 'generated' => '2024-04-07', 'revenue' => 0, 'net' => 485000, 'status' => 'Finalized'],
                ['id' => 'INC-2024-02', 'type' => 'Income Statement', 'period' => 'Feb 2024', 'generated' => '2024-03-01', 'revenue' => 920000, 'net' => 208000, 'status' => 'Finalized'],
                ['id' => 'INC-2024-01', 'type' => 'Income Statement', 'period' => 'Jan 2024', 'generated' => '2024-02-01', 'revenue' => 930000, 'net' => 210000, 'status' => 'Finalized'],
                ['id' => 'BAL-2023-12', 'type' => 'Balance Sheet', 'period' => 'Dec 31, 2023', 'generated' => '2024-01-05', 'revenue' => 0, 'net' => 0, 'status' => 'Finalized'],
                ['id' => 'INC-2023-Q4', 'type' => 'Income Statement', 'period' => 'Q4 2023', 'generated' => '2024-01-10', 'revenue' => 2650000, 'net' => 598000, 'status' => 'Finalized'],
              ];
              foreach ($statements as $stmt): 
                $statusColor = $stmt['status'] == 'Finalized' ? 'status-active' : 'status-pending';
                $typeColor = $stmt['type'] == 'Income Statement' ? 'bg-green-100 text-green-800' : 
                            ($stmt['type'] == 'Balance Sheet' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800');
              ?>
              <tr>
                <td class="font-mono"><?php echo $stmt['id']; ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $typeColor; ?>"><?php echo $stmt['type']; ?></span></td>
                <td class="font-medium"><?php echo $stmt['period']; ?></td>
                <td class="text-sm text-gray-600"><?php echo $stmt['generated']; ?></td>
                <td class="font-bold text-amber-700"><?php echo $stmt['revenue'] > 0 ? '$' . number_format($stmt['revenue']) : '—'; ?></td>
                <td class="font-bold text-green-600"><?php echo $stmt['net'] > 0 ? '$' . number_format($stmt['net']) : '—'; ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $statusColor; ?>"><?php echo $stmt['status']; ?></span></td>
                <td>
                  <div class="flex gap-2">
                    <button onclick="viewStatement('<?php echo $stmt['id']; ?>')" class="text-blue-600 hover:text-blue-700 transition-colors" title="View">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="exportStatement('<?php echo $stmt['id']; ?>')" class="text-amber-600 hover:text-amber-700 transition-colors" title="Export">
                      <i class="fas fa-download"></i>
                    </button>
                    <?php if ($stmt['status'] == 'Draft'): ?>
                    <button onclick="editStatement('<?php echo $stmt['id']; ?>')" class="text-green-600 hover:text-green-700 transition-colors" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- Statement Templates -->
  <section class="py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-amber-50/50">
    <div class="max-w-7xl mx-auto">
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Statement Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Template 1 -->
          <div class="statement-card p-6 rounded-xl border border-amber-100 bg-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-white"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Standard Income Statement</h4>
                <p class="text-xs text-gray-600">Monthly P&L Report</p>
              </div>
            </div>
            <p class="text-sm text-gray-700 mb-4">Complete income statement template with all standard revenue and expense categories.</p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Last used: 5 days ago</span>
              <button onclick="useTemplate('income_standard')" class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 text-sm font-semibold hover:bg-amber-200">
                Use Template
              </button>
            </div>
          </div>
          
          <!-- Template 2 -->
          <div class="statement-card p-6 rounded-xl border border-amber-100 bg-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                <i class="fas fa-balance-scale text-white"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Detailed Balance Sheet</h4>
                <p class="text-xs text-gray-600">Quarter-End Assets & Liabilities</p>
              </div>
            </div>
            <p class="text-sm text-gray-700 mb-4">Comprehensive balance sheet with detailed asset and liability breakdowns.</p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Last used: 2 weeks ago</span>
              <button onclick="useTemplate('balance_detailed')" class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold hover:bg-blue-200">
                Use Template
              </button>
            </div>
          </div>
          
          <!-- Template 3 -->
          <div class="statement-card p-6 rounded-xl border border-amber-100 bg-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-white"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Cash Flow Statement</h4>
                <p class="text-xs text-gray-600">Operating, Investing, Financing</p>
              </div>
            </div>
            <p class="text-sm text-gray-700 mb-4">Standard cash flow statement template with all three activity categories.</p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">Last used: 3 days ago</span>
              <button onclick="useTemplate('cashflow_standard')" class="px-3 py-1 rounded-lg bg-purple-100 text-purple-700 text-sm font-semibold hover:bg-purple-200">
                Use Template
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Statement Generation Modal -->
  <div id="statementModal" class="modal-overlay">
    <div class="modal-content">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Generate Financial Statement</h3>
          <button onclick="closeModal('statementModal')" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <form id="statementForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Statement Type</label>
              <select class="w-full px-4 py-2 rounded-xl border border-amber-200">
                <option>Income Statement (Profit & Loss)</option>
                <option>Balance Sheet</option>
                <option>Cash Flow Statement</option>
                <option>Statement of Retained Earnings</option>
                <option>Budget vs Actual</option>
              </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" class="w-full px-4 py-2 rounded-xl border border-amber-200" value="2024-01-01" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" class="w-full px-4 py-2 rounded-xl border border-amber-200" value="2024-03-31" required>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
              <select class="w-full px-4 py-2 rounded-xl border border-amber-200">
                <option>USD - US Dollar ($)</option>
                <option>EUR - Euro (€)</option>
                <option>GBP - British Pound (£)</option>
                <option>JPY - Japanese Yen (¥)</option>
                <option>AUD - Australian Dollar (A$)</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
              <div class="grid grid-cols-3 gap-2">
                <label class="flex items-center p-3 rounded-lg border border-amber-200 cursor-pointer hover:bg-amber-50">
                  <input type="radio" name="format" class="mr-2" checked>
                  <span class="text-sm">Detailed</span>
                </label>
                <label class="flex items-center p-3 rounded-lg border border-amber-200 cursor-pointer hover:bg-amber-50">
                  <input type="radio" name="format" class="mr-2">
                  <span class="text-sm">Summary</span>
                </label>
                <label class="flex items-center p-3 rounded-lg border border-amber-200 cursor-pointer hover:bg-amber-50">
                  <input type="radio" name="format" class="mr-2">
                  <span class="text-sm">Comparison</span>
                </label>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Include Sections</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500" checked>
                  <span class="ml-2 text-sm text-gray-700">Revenue Breakdown</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500" checked>
                  <span class="ml-2 text-sm text-gray-700">Expense Details</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                  <span class="ml-2 text-sm text-gray-700">Departmental Breakdown</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500" checked>
                  <span class="ml-2 text-sm text-gray-700">Year-over-Year Comparison</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                  <span class="ml-2 text-sm text-gray-700">Budget Variance Analysis</span>
                </label>
              </div>
            </div>
            
            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200">
              <h4 class="font-semibold text-gray-900 mb-2">Export Options</h4>
              <div class="grid grid-cols-3 gap-2">
                <label class="flex items-center p-2 rounded-lg border border-amber-200 bg-white cursor-pointer">
                  <input type="checkbox" class="mr-2">
                  <span class="text-sm">PDF</span>
                </label>
                <label class="flex items-center p-2 rounded-lg border border-amber-200 bg-white cursor-pointer">
                  <input type="checkbox" class="mr-2" checked>
                  <span class="text-sm">Excel</span>
                </label>
                <label class="flex items-center p-2 rounded-lg border border-amber-200 bg-white cursor-pointer">
                  <input type="checkbox" class="mr-2">
                  <span class="text-sm">CSV</span>
                </label>
              </div>
            </div>
          </div>
          
          <div class="mt-8 flex justify-end gap-3">
            <button type="button" onclick="closeModal('statementModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
              Generate Statement
            </button>
          </div>
        </form>
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

      // Statement card hover effects
      document.querySelectorAll('.statement-card').forEach(card => {
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

    // Modal functions
    function openStatementModal() {
      document.getElementById('statementModal').style.display = 'flex';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    // Statement functions
    function generateIncomeStatement() {
      alert('Generating Income Statement...\n\nThis would open a wizard to configure and generate the statement.');
      openStatementModal();
    }

    function generateBalanceSheet() {
      alert('Generating Balance Sheet...\n\nThis would open a wizard to configure and generate the balance sheet.');
      openStatementModal();
    }

    function generateCashFlow() {
      alert('Generating Cash Flow Statement...\n\nThis would open a wizard to configure and generate the cash flow statement.');
      openStatementModal();
    }

    function quickGenerate() {
      const period = document.querySelector('select').value;
      alert(`Quick generating statement for: ${period}\n\nStatement will be generated in the background. You'll be notified when it's ready.`);
      
      // Simulate generation
      setTimeout(() => {
        if (confirm('Statement generated successfully! Would you like to view it now?')) {
          viewStatement('INC-2024-04');
        }
      }, 1500);
    }

    function viewStatement(statementId) {
      alert(`Viewing statement: ${statementId}\n\nThis would open a detailed view of the statement with interactive charts and export options.`);
      
      // In a real application, this would navigate to a statement detail page
      // window.location.href = `statement_detail.php?id=${statementId}`;
    }

    function exportStatement(statementId) {
      alert(`Exporting statement: ${statementId}\n\nSelect format:\n1. PDF\n2. Excel\n3. CSV\n4. Print`);
      
      // Simulate export
      setTimeout(() => {
        alert('Statement exported successfully! The file will download automatically.');
      }, 1000);
    }

    function printStatement(statementId) {
      alert(`Printing statement: ${statementId}\n\nOpening print dialog...`);
      // In a real application, this would trigger the browser's print functionality
      // window.print();
    }

    function shareStatement(statementId) {
      alert(`Sharing statement: ${statementId}\n\nSharing options:\n1. Email\n2. Share link\n3. Export to accounting software`);
    }

    function editStatement(statementId) {
      alert(`Editing statement: ${statementId}\n\nOpening statement editor...`);
      openStatementModal();
    }

    function useTemplate(templateId) {
      alert(`Using template: ${templateId}\n\nTemplate loaded. You can now customize and generate your statement.`);
      openStatementModal();
    }

    // Form submission handler
    document.getElementById('statementForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Statement generation started! You will be notified when it is complete.');
      closeModal('statementModal');
      
      // Simulate background generation
      setTimeout(() => {
        alert('Statement generated successfully! You can view it in the Statement History table.');
      }, 2000);
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
      }
    };
  </script>
</body>
</html>