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
  <title>Discount Management | TravelEase Finance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Discount management dashboard for TravelEase luxury travel company.">
  
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
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="advanced-loader mx-auto mb-6"></div>
      <div class="premium-font text-2xl font-black text-gradient">TravelEase Finance</div>
      <p class="text-gray-600 mt-2">Loading Discount Management...</p>
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
          <a href="Finance_Discounts.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="overview.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase Finance
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Discount Management Dashboard
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
          <a href="Finance_Discounts.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-percentage mr-2"></i>
            Discounts
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500 transition-all duration-300"></span>
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

  <!-- Discount Management Section -->
  <section id="discounts" class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-8 animate-fade-in-down">
        <div>
          <h1 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
            <span class="text-gray-900">Discount</span>
            <span class="text-gradient block">Management</span>
          </h1>
          <p class="text-lg text-gray-700">
            Monitor and manage all discount programs and their financial impact
          </p>
        </div>
        <button onclick="openDiscountModal()" class="px-4 py-2 rounded-xl gold-gradient text-sm font-semibold text-white">
          <i class="fas fa-plus mr-2"></i>Add New Discount
        </button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Active Discounts -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold animate-fade-in-up">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Active Discounts</h3>
          <div class="space-y-4">
            <?php 
            $activeDiscounts = [
              ['code' => 'SPRING24', 'type' => 'Seasonal', 'value' => '15%', 'cost' => 42000, 'usage' => 156, 'limit' => 200],
              ['code' => 'EARLYBIRD', 'type' => 'Early Booking', 'value' => '10%', 'cost' => 28000, 'usage' => 89, 'limit' => 150],
              ['code' => 'GROUP10', 'type' => 'Group Discount', 'value' => '10%', 'cost' => 35000, 'usage' => 42, 'limit' => 100],
              ['code' => 'LOYALTY', 'type' => 'Loyalty Program', 'value' => '5%', 'cost' => 12000, 'usage' => 245, 'limit' => '∞'],
            ];
            foreach ($activeDiscounts as $discount): 
              $usagePercentage = $discount['limit'] == '∞' ? 100 : ($discount['usage'] / $discount['limit']) * 100;
            ?>
            <div class="p-4 rounded-xl bg-white border border-amber-100 hover-lift">
              <div class="flex items-center justify-between mb-3">
                <div>
                  <span class="font-mono font-bold text-gray-900"><?php echo $discount['code']; ?></span>
                  <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800 ml-2">
                    <?php echo $discount['type']; ?>
                  </span>
                </div>
                <span class="font-bold text-purple-600"><?php echo $discount['value']; ?></span>
              </div>
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Cost: <span class="font-semibold text-gray-900">$<?php echo number_format($discount['cost']); ?></span></span>
                <span class="text-gray-600">Usage: <?php echo $discount['usage']; ?>/<?php echo $discount['limit']; ?></span>
              </div>
              <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-300 rounded-full" 
                     style="width: <?php echo min($usagePercentage, 100); ?>%"></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Discount Performance -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold animate-fade-in-up">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Discount Performance Metrics</h3>
          <div class="space-y-6">
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-700">Total Discount Cost This Month</span>
                <span class="font-bold text-purple-600">$42,800</span>
              </div>
              <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-300 rounded-full" style="width: 72%"></div>
              </div>
              <p class="text-xs text-gray-600 mt-1">6.7% of monthly revenue</p>
            </div>
            
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-700">Discount Redemption Rate</span>
                <span class="font-bold text-green-600">18.4%</span>
              </div>
              <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-green-500 to-green-300 rounded-full" style="width: 65%"></div>
              </div>
              <p class="text-xs text-gray-600 mt-1">Above industry average</p>
            </div>
            
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-700">Revenue Impact</span>
                <span class="font-bold text-amber-600">+24%</span>
              </div>
              <div class="h-2 bg-amber-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-500 to-amber-300 rounded-full" style="width: 80%"></div>
              </div>
              <p class="text-xs text-gray-600 mt-1">Additional revenue from discount-driven sales</p>
            </div>
          </div>
          
          <div class="mt-6 p-4 rounded-xl bg-amber-50/50 border border-amber-200">
            <h4 class="font-semibold text-gray-900 mb-2">Recommendations</h4>
            <ul class="text-sm text-gray-700 space-y-1">
              <li class="flex items-start gap-2">
                <i class="fas fa-check text-green-500 mt-0.5"></i>
                <span>Consider increasing EARLYBIRD discount to 12% during peak season</span>
              </li>
              <li class="flex items-start gap-2">
                <i class="fas fa-check text-green-500 mt-0.5"></i>
                <span>GROUP10 nearing capacity - consider increasing limit</span>
              </li>
              <li class="flex items-start gap-2">
                <i class="fas fa-check text-green-500 mt-0.5"></i>
                <span>SPRING24 performing well - extend through June</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Discount History -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold animate-fade-in-up">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Discount Activity</h3>
        <div class="overflow-x-auto">
          <table class="finance-table">
            <thead>
              <tr>
                <th>Discount Code</th>
                <th>Type</th>
                <th>Created</th>
                <th>Expires</th>
                <th>Total Usage</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $discountHistory = [
                ['code' => 'WINTER23', 'type' => 'Seasonal', 'created' => '2023-12-01', 'expires' => '2024-02-28', 'usage' => 189, 'cost' => 45200, 'status' => 'Expired'],
                ['code' => 'NY2024', 'type' => 'Promotional', 'created' => '2023-12-15', 'expires' => '2024-01-15', 'usage' => 78, 'cost' => 23400, 'status' => 'Expired'],
                ['code' => 'VALENTINE', 'type' => 'Seasonal', 'created' => '2024-01-20', 'expires' => '2024-02-14', 'usage' => 92, 'cost' => 27600, 'status' => 'Expired'],
                ['code' => 'FEB24', 'type' => 'Promotional', 'created' => '2024-02-01', 'expires' => '2024-02-29', 'usage' => 121, 'cost' => 36300, 'status' => 'Expired'],
                ['code' => 'EARLYBIRD', 'type' => 'Early Booking', 'created' => '2024-01-15', 'expires' => '2024-12-31', 'usage' => 89, 'cost' => 28000, 'status' => 'Active'],
              ];
              foreach ($discountHistory as $discount): 
                $statusClass = $discount['status'] == 'Active' ? 'status-active' : 'status-expired';
              ?>
              <tr>
                <td class="font-mono font-bold"><?php echo $discount['code']; ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800"><?php echo $discount['type']; ?></span></td>
                <td><?php echo $discount['created']; ?></td>
                <td><?php echo $discount['expires']; ?></td>
                <td class="font-semibold"><?php echo $discount['usage']; ?></td>
                <td class="font-semibold text-purple-600">$<?php echo number_format($discount['cost']); ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $statusClass; ?>"><?php echo $discount['status']; ?></span></td>
                <td>
                  <button onclick="editDiscount('<?php echo $discount['code']; ?>')" class="text-amber-600 hover:text-amber-700">
                    <i class="fas fa-edit"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>


  <!-- Discount Modal -->
  <div id="discountModal" class="modal-overlay">
    <div class="modal-content">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Create New Discount</h3>
          <button onclick="closeModal('discountModal')" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <form id="discountForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Discount Code</label>
              <input type="text" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="SPRING24" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
              <select class="w-full px-4 py-2 rounded-xl border border-amber-200">
                <option>Seasonal</option>
                <option>Early Booking</option>
                <option>Group Discount</option>
                <option>Loyalty Program</option>
                <option>Promotional</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value</label>
              <div class="flex gap-2">
                <input type="number" class="flex-1 px-4 py-2 rounded-xl border border-amber-200" placeholder="15">
                <select class="px-4 py-2 rounded-xl border border-amber-200">
                  <option>%</option>
                  <option>$</option>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
              <input type="number" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="Leave empty for unlimited">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost</label>
              <input type="number" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="5000">
            </div>
          </div>
          <div class="mt-8 flex justify-end gap-3">
            <button type="button" onclick="closeModal('discountModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
              Create Discount
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

      // Add hover effects to cards
      document.querySelectorAll('.hover-lift').forEach(card => {
        card.addEventListener('mouseenter', () => {
          card.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', () => {
          card.style.transform = 'translateY(0)';
        });
      });
    });

    // Modal functions
    function openDiscountModal() {
      document.getElementById('discountModal').style.display = 'flex';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    function editDiscount(discountCode) {
      alert('Editing discount: ' + discountCode);
      openDiscountModal();
    }

    // Form submission handlers
    document.getElementById('discountForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Discount created successfully!');
      closeModal('discountModal');
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
      }
    };

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