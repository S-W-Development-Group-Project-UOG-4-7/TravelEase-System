<?php
session_start();
if (!isset($_SESSION['finance_logged_in'])) {
    $_SESSION['finance_logged_in'] = true;
    $_SESSION['finance_full_name'] = 'Finance Manager';
    $_SESSION['finance_role'] = 'Finance Manager';
}

// Database connection
$host = "localhost";
$dbname = "finance_db"; // Change to your database name
$username = "postgres"; // Change to your PostgreSQL username
$password = "12345"; // Change to your PostgreSQL password

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_package'])) {
    // Sanitize and validate input
    $package_name = filter_var($_POST['package_name'], FILTER_SANITIZE_STRING);
    $tier = filter_var($_POST['tier'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $base_cost = filter_var($_POST['base_cost'], FILTER_VALIDATE_FLOAT);
    $base_price = filter_var($_POST['base_price'], FILTER_VALIDATE_FLOAT);
    $spring_price = filter_var($_POST['spring_price'], FILTER_VALIDATE_FLOAT);
    $summer_price = filter_var($_POST['summer_price'], FILTER_VALIDATE_FLOAT);
    $fall_price = filter_var($_POST['fall_price'], FILTER_VALIDATE_FLOAT);
    $winter_price = filter_var($_POST['winter_price'], FILTER_VALIDATE_FLOAT);
    $target_margin = filter_var($_POST['target_margin'], FILTER_VALIDATE_FLOAT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    
    // Calculate actual margin
    if ($base_cost > 0 && $base_price > 0) {
        $actual_margin = (($base_price - $base_cost) / $base_price) * 100;
    } else {
        $actual_margin = null;
    }
    
    // Prepare and execute SQL query
    try {
        $sql = "INSERT INTO addpackages (
            package_name, tier, category, base_cost, base_price, 
            spring_price, summer_price, fall_price, winter_price, 
            target_margin, actual_margin, status
        ) VALUES (
            :package_name, :tier, :category, :base_cost, :base_price,
            :spring_price, :summer_price, :fall_price, :winter_price,
            :target_margin, :actual_margin, :status
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':package_name' => $package_name,
            ':tier' => $tier,
            ':category' => $category,
            ':base_cost' => $base_cost,
            ':base_price' => $base_price,
            ':spring_price' => $spring_price,
            ':summer_price' => $summer_price,
            ':fall_price' => $fall_price,
            ':winter_price' => $winter_price,
            ':target_margin' => $target_margin,
            ':actual_margin' => $actual_margin,
            ':status' => $status
        ]);
        
        $success_message = "Package added successfully!";
        
        // Clear form or redirect
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
        
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch packages from database for display
try {
    $stmt = $pdo->query("SELECT * FROM addpackages ORDER BY package_id DESC");
    $database_packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $database_packages = [];
    $error_message = "Error fetching packages: " . $e->getMessage();
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
  <title>Package Pricing | TravelEase - Financial Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Package pricing management for TravelEase luxury travel company.">
  
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
    
    /* Success/Error messages */
    .alert-success {
      background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
      color: white;
    }
    
    .alert-error {
      background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
      color: white;
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="advanced-loader mx-auto mb-6"></div>
      <div class="premium-font text-2xl font-black text-gradient">TravelEase Finance</div>
      <p class="text-gray-600 mt-2">Loading Package Pricing...</p>
    </div>
  </div>

  <!-- Success/Error Messages -->
  <?php if(isset($_GET['success'])): ?>
  <div class="fixed top-20 right-4 z-50 animate-fade-in-down">
    <div class="alert-success px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
      <i class="fas fa-check-circle text-white"></i>
      <span class="font-semibold">Package added successfully!</span>
    </div>
  </div>
  <?php endif; ?>
  
  <?php if(isset($error_message)): ?>
  <div class="fixed top-20 right-4 z-50 animate-fade-in-down">
    <div class="alert-error px-6 py-4 rounded-2xl shadow-lg flex items-center gap-3">
      <i class="fas fa-exclamation-circle"></i>
      <span class="font-semibold"><?php echo htmlspecialchars($error_message); ?></span>
    </div>
  </div>
  <?php endif; ?>

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
          <a href="Finance_Packages.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="Finance_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Finance Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase Finance
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Package Pricing Management
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
          <a href="Finance_Packages.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-box mr-2"></i>
            Packages
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
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

  <!-- Package Pricing Management -->
  <section class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-amber-50/50 to-white">
    <div class="max-w-7xl mx-auto">
      <!-- Welcome Header -->
      <div class="mb-8 animate-fade-in-down">
        <h1 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
          <span class="text-gray-900">Package</span>
          <span class="text-gradient block">Pricing Management</span>
        </h1>
        <p class="text-lg text-gray-700">
          Manage and optimize package pricing for maximum profitability
        </p>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-box text-white"></i>
            </div>
            <?php 
            $total_packages = count($database_packages);
            $active_packages = 0;
            foreach($database_packages as $pkg) {
                if($pkg['status'] === 'Active') $active_packages++;
            }
            ?>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge">Active</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2"><?php echo $total_packages; ?></h3>
          <p class="text-gray-600 text-sm">Total Packages</p>
          <p class="text-xs text-green-600 mt-2">
            <i class="fas fa-arrow-up mr-1"></i><?php echo $active_packages; ?> active
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-chart-line text-white"></i>
            </div>
            <?php 
            $avg_margin = 0;
            $margin_count = 0;
            foreach($database_packages as $pkg) {
                if($pkg['actual_margin'] !== null) {
                    $avg_margin += $pkg['actual_margin'];
                    $margin_count++;
                }
            }
            $avg_margin = $margin_count > 0 ? round($avg_margin / $margin_count, 1) : 0;
            ?>
            <span class="text-xs font-semibold px-3 py-1 rounded-full profit-badge"><?php echo $avg_margin > 40 ? '+'.($avg_margin - 40).'%' : 'On Target'; ?></span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2"><?php echo $avg_margin; ?>%</h3>
          <p class="text-gray-600 text-sm">Avg Profit Margin</p>
          <p class="text-xs <?php echo $avg_margin > 40 ? 'text-green-600' : 'text-amber-600'; ?> mt-2">
            <i class="fas <?php echo $avg_margin > 40 ? 'fa-arrow-up' : 'fa-equals'; ?> mr-1"></i>
            <?php echo $avg_margin > 40 ? 'Above target' : 'On target'; ?>
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-money-bill-wave text-white"></i>
            </div>
            <?php 
            $total_revenue = 0;
            foreach($database_packages as $pkg) {
                $total_revenue += $pkg['base_price'] * 100; // Assuming 100 bookings per package for demo
            }
            $revenue_millions = round($total_revenue / 1000000, 1);
            ?>
            <span class="text-xs font-semibold px-3 py-1 rounded-full discount-badge">$<?php echo $revenue_millions; ?>M</span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2">$<?php echo number_format($total_revenue); ?></h3>
          <p class="text-gray-600 text-sm">Estimated Revenue</p>
          <p class="text-xs text-purple-600 mt-2">
            <i class="fas fa-chart-line mr-1"></i><?php echo round(($total_packages > 0 ? $total_revenue / ($total_packages * 10000) : 0) * 100); ?>% of target
          </p>
        </div>

        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold hover-lift">
          <div class="flex items-center justify-between mb-4">
            <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
              <i class="fas fa-edit text-white"></i>
            </div>
            <?php 
            $updated_this_month = 0;
            $current_month = date('Y-m');
            foreach($database_packages as $pkg) {
                // Assuming package_id is sequential and newer packages have higher ids
                if($pkg['package_id'] > $total_packages - 10) $updated_this_month++;
            }
            ?>
            <span class="text-xs font-semibold px-3 py-1 rounded-full revenue-badge"><?php echo $updated_this_month; ?></span>
          </div>
          <h3 class="text-2xl font-black text-gray-900 mb-2"><?php echo $total_packages; ?></h3>
          <p class="text-gray-600 text-sm">Total in Database</p>
          <p class="text-xs text-blue-600 mt-2">
            <i class="fas fa-exclamation-circle mr-1"></i><?php echo count(array_filter($database_packages, function($pkg) { 
                return $pkg['status'] === 'Pending'; 
            })); ?> pending
          </p>
        </div>
      </div>

      <!-- Main Package Pricing Table -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Package Price Overview</h3>
          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Filter:</span>
              <select id="packageFilter" class="text-sm border border-amber-200 rounded-lg px-3 py-1 bg-amber-50">
                <option value="all">All Packages</option>
                <option value="luxury">Luxury Tier</option>
                <option value="premium">Premium Tier</option>
                <option value="standard">Standard Tier</option>
              </select>
            </div>
            <button onclick="openPackageModal()" class="px-4 py-2 rounded-xl gold-gradient text-sm font-semibold text-white">
              <i class="fas fa-plus mr-2"></i>Add New Package
            </button>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="finance-table">
            <thead>
              <tr>
                <th>Package Name</th>
                <th>Tier</th>
                <th>Category</th>
                <th>Base Cost</th>
                <th>Base Price</th>
                <th>Spring Price</th>
                <th>Summer Price</th>
                <th>Profit Margin</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              // Combine database packages with sample data for display
              $all_packages = array_merge($database_packages, [
                ['package_name' => 'Japan Luxury Experience', 'tier' => 'Luxury', 'category' => 'Cultural', 'base_cost' => 7200, 'base_price' => 12400, 'spring_price' => 14500, 'summer_price' => 16800, 'actual_margin' => 42, 'status' => 'Active'],
                ['package_name' => 'Bali Premium Retreat', 'tier' => 'Premium', 'category' => 'Wellness', 'base_cost' => 5500, 'base_price' => 8900, 'spring_price' => 10400, 'summer_price' => 12100, 'actual_margin' => 38, 'status' => 'Active'],
                ['package_name' => 'Maldives Exclusive Escape', 'tier' => 'Luxury', 'category' => 'Beach', 'base_cost' => 12000, 'base_price' => 21800, 'spring_price' => 25400, 'summer_price' => 29600, 'actual_margin' => 45, 'status' => 'Active'],
                ['package_name' => 'Thailand Elite Adventure', 'tier' => 'Premium', 'category' => 'Adventure', 'base_cost' => 9360, 'base_price' => 15600, 'spring_price' => 18200, 'summer_price' => 21200, 'actual_margin' => 40, 'status' => 'Active'],
                ['package_name' => 'Vietnam Cultural Journey', 'tier' => 'Standard', 'category' => 'Cultural', 'base_cost' => 4810, 'base_price' => 7400, 'spring_price' => 8600, 'summer_price' => 10000, 'actual_margin' => 35, 'status' => 'Active'],
                ['package_name' => 'Sri Lanka Nature Explorer', 'tier' => 'Standard', 'category' => 'Adventure', 'base_cost' => 5120, 'base_price' => 9200, 'spring_price' => 10700, 'summer_price' => 12400, 'actual_margin' => 44, 'status' => 'Active'],
                ['package_name' => 'South Korea Modern Tour', 'tier' => 'Premium', 'category' => 'Cultural', 'base_cost' => 7200, 'base_price' => 11800, 'spring_price' => 13800, 'summer_price' => 16000, 'actual_margin' => 39, 'status' => 'Pending'],
                ['package_name' => 'Singapore Urban Experience', 'tier' => 'Standard', 'category' => 'City', 'base_cost' => 4200, 'base_price' => 6800, 'spring_price' => 7900, 'summer_price' => 9200, 'actual_margin' => 38, 'status' => 'Active'],
              ]);
              
              foreach ($all_packages as $pkg): 
                $tierColor = $pkg['tier'] == 'Luxury' ? 'bg-purple-100 text-purple-800' : 
                            ($pkg['tier'] == 'Premium' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800');
                $margin = $pkg['actual_margin'] ?? (($pkg['base_price'] - $pkg['base_cost']) / $pkg['base_price'] * 100);
                $marginColor = $margin > 40 ? 'text-green-600' : 
                              ($margin > 35 ? 'text-amber-600' : 'text-blue-600');
                $statusColor = $pkg['status'] == 'Active' ? 'status-active' : 
                              ($pkg['status'] == 'Pending' ? 'status-pending' : 'status-expired');
              ?>
              <tr class="package-row" data-tier="<?php echo strtolower($pkg['tier']); ?>">
                <td class="font-medium"><?php echo htmlspecialchars($pkg['package_name']); ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $tierColor; ?>"><?php echo $pkg['tier']; ?></span></td>
                <td><span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800"><?php echo $pkg['category']; ?></span></td>
                <td class="text-gray-600">$<?php echo number_format($pkg['base_cost']); ?></td>
                <td class="font-bold text-amber-700">$<?php echo number_format($pkg['base_price']); ?></td>
                <td class="font-bold text-green-600">$<?php echo isset($pkg['spring_price']) ? number_format($pkg['spring_price']) : number_format($pkg['base_price'] * 1.1); ?></td>
                <td class="font-bold text-blue-600">$<?php echo isset($pkg['summer_price']) ? number_format($pkg['summer_price']) : number_format($pkg['base_price'] * 1.3); ?></td>
                <td class="font-semibold <?php echo $marginColor; ?>"><?php echo round($margin, 1); ?>%</td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $statusColor; ?>"><?php echo $pkg['status']; ?></span></td>
                <td>
                  <div class="flex gap-2">
                    <button onclick="editPackagePrice('<?php echo htmlspecialchars($pkg['package_name']); ?>')" class="text-amber-600 hover:text-amber-700 transition-colors" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="viewPackageDetails('<?php echo htmlspecialchars($pkg['package_name']); ?>')" class="text-blue-600 hover:text-blue-700 transition-colors" title="View">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="duplicatePackage('<?php echo htmlspecialchars($pkg['package_name']); ?>')" class="text-green-600 hover:text-green-700 transition-colors" title="Duplicate">
                      <i class="fas fa-copy"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Price Calculator and Tools -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Price Calculator -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Package Price Calculator</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
              <input type="text" id="calcPackageName" class="w-full px-4 py-2 rounded-xl border border-amber-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Enter package name">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Base Cost</label>
                <input type="number" id="baseCost" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="Enter base cost" value="5000">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Margin %</label>
                <input type="range" id="marginSlider" min="20" max="60" step="1" value="40" class="w-full">
                <div class="flex justify-between text-xs text-gray-600 mt-1">
                  <span>20%</span>
                  <span id="marginValue">40%</span>
                  <span>60%</span>
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Season Multiplier</label>
              <select id="seasonMultiplier" class="w-full px-4 py-2 rounded-xl border border-amber-200">
                <option value="0.9">Shoulder Season (0.9x)</option>
                <option value="1.0">Regular Season (1.0x)</option>
                <option value="1.1" selected>Spring Season (1.1x)</option>
                <option value="1.2">Spring Peak (1.2x)</option>
                <option value="1.3">Summer Season (1.3x)</option>
                <option value="1.5">Peak Season (1.5x)</option>
              </select>
            </div>
            
            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200 mt-4">
              <h4 class="font-semibold text-gray-900 mb-4">Calculated Prices</h4>
              <div class="space-y-3">
                <div class="flex justify-between items-center">
                  <span class="text-gray-700">Base Selling Price:</span>
                  <span id="basePrice" class="font-bold text-amber-700">$8,333</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-700">Seasonal Price:</span>
                  <span id="seasonalPrice" class="font-bold text-amber-800">$9,166</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-700">Profit per Sale:</span>
                  <span id="profitAmount" class="font-bold text-green-600">$3,333</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-amber-200">
                  <span class="text-gray-900 font-semibold">Final Recommended Price:</span>
                  <span id="finalPrice" class="text-xl font-black text-amber-900">$9,166</span>
                </div>
              </div>
              <button onclick="applyCalculatedPrice()" class="w-full mt-6 px-4 py-3 rounded-xl gold-gradient font-semibold text-white">
                Apply Calculated Price
              </button>
            </div>
          </div>
        </div>

        <!-- Bulk Price Update -->
        <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Bulk Price Update</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Packages</label>
              <div class="space-y-2 max-h-48 overflow-y-auto p-2 border border-amber-200 rounded-xl">
                <?php foreach (array_slice($all_packages, 0, 6) as $index => $pkg): ?>
                <label class="flex items-center gap-3 p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                  <input type="checkbox" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500" value="<?php echo htmlspecialchars($pkg['package_name']); ?>">
                  <span class="text-sm"><?php echo htmlspecialchars($pkg['package_name']); ?></span>
                  <span class="ml-auto text-xs px-2 py-1 rounded-full <?php echo $pkg['tier'] == 'Luxury' ? 'bg-purple-100 text-purple-800' : ($pkg['tier'] == 'Premium' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'); ?>">
                    <?php echo $pkg['tier']; ?>
                  </span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type</label>
              <select id="adjustmentType" class="w-full px-4 py-2 rounded-xl border border-amber-200">
                <option value="percentage_increase">Percentage Increase</option>
                <option value="percentage_decrease">Percentage Decrease</option>
                <option value="fixed_increase">Fixed Amount Increase</option>
                <option value="fixed_decrease">Fixed Amount Decrease</option>
                <option value="set_amount">Set to Specific Amount</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Value</label>
              <div class="flex gap-2">
                <input type="number" id="adjustmentValue" class="flex-1 px-4 py-2 rounded-xl border border-amber-200" placeholder="10" value="10">
                <select id="adjustmentUnit" class="px-4 py-2 rounded-xl border border-amber-200">
                  <option value="percent">%</option>
                  <option value="dollar">$</option>
                </select>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Effective Date</label>
              <input type="date" id="effectiveDate" class="w-full px-4 py-2 rounded-xl border border-amber-200" value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-200">
              <h4 class="font-semibold text-gray-900 mb-2">Update Summary</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Packages Selected:</span>
                  <span id="selectedCount" class="font-semibold text-blue-600">0</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Total Revenue Impact:</span>
                  <span id="revenueImpact" class="font-semibold text-green-600">$0</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Avg Price Change:</span>
                  <span id="avgPriceChange" class="font-semibold text-amber-600">0%</span>
                </div>
              </div>
            </div>
            
            <button onclick="applyBulkUpdate()" class="w-full px-4 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold hover:from-blue-600 hover:to-blue-700 transition-all">
              Apply Bulk Update
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Recent Price Adjustments -->
  <section class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-gold">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Recent Price Adjustments</h3>
          <a href="pricing_history.php" class="text-sm text-amber-600 hover:text-amber-700 font-semibold">
            View Full History <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>
        
        <div class="overflow-x-auto">
          <table class="finance-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Package</th>
                <th>Tier</th>
                <th>Adjustment Type</th>
                <th>Old Price</th>
                <th>New Price</th>
                <th>Change %</th>
                <th>By</th>
                <th>Reason</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $adjustments = [
                ['date' => '2024-04-15', 'package' => 'Japan Luxury', 'tier' => 'Luxury', 'type' => 'Seasonal', 'old' => 12400, 'new' => 14500, 'change' => '+16.9%', 'by' => 'System', 'reason' => 'Spring Premium'],
                ['date' => '2024-04-14', 'package' => 'Bali Retreat', 'tier' => 'Premium', 'type' => 'Cost Update', 'old' => 8200, 'new' => 8900, 'change' => '+8.5%', 'by' => 'Admin', 'reason' => 'Vendor Increase'],
                ['date' => '2024-04-13', 'package' => 'Maldives Exclusive', 'tier' => 'Luxury', 'type' => 'Seasonal', 'old' => 19800, 'new' => 21800, 'change' => '+10.1%', 'by' => 'System', 'reason' => 'High Demand'],
                ['date' => '2024-04-12', 'package' => 'Thailand Elite', 'tier' => 'Premium', 'type' => 'Competitor', 'old' => 14200, 'new' => 15600, 'change' => '+9.9%', 'by' => 'Admin', 'reason' => 'Market Rate'],
                ['date' => '2024-04-11', 'package' => 'Vietnam Culture', 'tier' => 'Standard', 'type' => 'Promotion', 'old' => 6800, 'new' => 7400, 'change' => '+8.8%', 'by' => 'Admin', 'reason' => 'Value Add'],
                ['date' => '2024-04-10', 'package' => 'Sri Lanka Explorer', 'tier' => 'Standard', 'type' => 'Seasonal', 'old' => 8000, 'new' => 9200, 'change' => '+15.0%', 'by' => 'System', 'reason' => 'Spring Season'],
                ['date' => '2024-04-09', 'package' => 'South Korea Tour', 'tier' => 'Premium', 'type' => 'Cost Update', 'old' => 9500, 'new' => 11800, 'change' => '+24.2%', 'by' => 'Admin', 'reason' => 'New Features'],
              ];
              foreach ($adjustments as $adj): 
                $changeClass = strpos($adj['change'], '+') !== false ? 'text-green-600' : 'text-red-600';
                $typeColor = $adj['type'] == 'Seasonal' ? 'bg-green-100 text-green-800' : 
                            ($adj['type'] == 'Cost Update' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
              ?>
              <tr>
                <td><?php echo $adj['date']; ?></td>
                <td class="font-medium"><?php echo $adj['package']; ?></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $adj['tier'] == 'Luxury' ? 'bg-purple-100 text-purple-800' : ($adj['tier'] == 'Premium' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'); ?>"><?php echo $adj['tier']; ?></span></td>
                <td><span class="text-xs px-2 py-1 rounded-full <?php echo $typeColor; ?>"><?php echo $adj['type']; ?></span></td>
                <td class="text-gray-600">$<?php echo number_format($adj['old']); ?></td>
                <td class="font-bold text-amber-700">$<?php echo number_format($adj['new']); ?></td>
                <td class="font-semibold <?php echo $changeClass; ?>"><?php echo $adj['change']; ?></td>
                <td class="text-sm"><?php echo $adj['by']; ?></td>
                <td class="text-sm text-gray-600"><?php echo $adj['reason']; ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- Package Detail Modal -->
  <div id="packageModal" class="modal-overlay">
    <div class="modal-content">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900">Add New Package</h3>
          <button onclick="closeModal('packageModal')" class="text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <form id="packageForm" method="POST" action="">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
              <input type="text" name="package_name" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="Japan Luxury Experience" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tier *</label>
                <select name="tier" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
                  <option value="">Select Tier</option>
                  <option value="Luxury">Luxury</option>
                  <option value="Premium">Premium</option>
                  <option value="Standard">Standard</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
                  <option value="">Select Category</option>
                  <option value="Cultural">Cultural</option>
                  <option value="Beach">Beach</option>
                  <option value="Adventure">Adventure</option>
                  <option value="Wellness">Wellness</option>
                  <option value="City">City</option>
                  <option value="Nature">Nature</option>
                </select>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Base Cost ($) *</label>
                <input type="number" name="base_cost" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="5000.00" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Base Price ($) *</label>
                <input type="number" name="base_price" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="9166.67" required>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Spring Price ($)</label>
                <input type="number" name="spring_price" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="10083.34">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Summer Price ($)</label>
                <input type="number" name="summer_price" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="11916.67">
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fall Price ($)</label>
                <input type="number" name="fall_price" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="10083.34">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Winter Price ($)</label>
                <input type="number" name="winter_price" step="0.01" min="0" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="11458.34">
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Margin (%)</label>
                <input type="number" name="target_margin" step="0.01" min="0" max="100" class="w-full px-4 py-2 rounded-xl border border-amber-200" placeholder="40.00">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-amber-200" required>
                  <option value="Active" selected>Active</option>
                  <option value="Inactive">Inactive</option>
                  <option value="Coming Soon">Coming Soon</option>
                  <option value="Discontinued">Discontinued</option>
                  <option value="Pending">Pending</option>
                </select>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
              <textarea name="notes" class="w-full px-4 py-2 rounded-xl border border-amber-200" rows="3" placeholder="Any additional notes about this package..."></textarea>
            </div>
          </div>
          
          <div class="mt-8 flex justify-end gap-3">
            <button type="button" onclick="closeModal('packageModal')" class="px-4 py-2 rounded-xl border border-amber-200 text-gray-700 hover:bg-amber-50">
              Cancel
            </button>
            <button type="submit" name="submit_package" class="px-4 py-2 rounded-xl gold-gradient text-white font-semibold">
              Save Package
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
      
      // Auto-hide success message
      const successMessage = document.querySelector('.alert-success');
      if (successMessage) {
        setTimeout(() => {
          successMessage.style.display = 'none';
        }, 5000);
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

      // Package filter functionality
      const packageFilter = document.getElementById('packageFilter');
      const packageRows = document.querySelectorAll('.package-row');
      
      if (packageFilter) {
        packageFilter.addEventListener('change', function() {
          const filterValue = this.value;
          
          packageRows.forEach(row => {
            if (filterValue === 'all' || row.dataset.tier === filterValue) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      }

      // Price calculator functionality
      const marginSlider = document.getElementById('marginSlider');
      const marginValue = document.getElementById('marginValue');
      const baseCost = document.getElementById('baseCost');
      const seasonMultiplier = document.getElementById('seasonMultiplier');
      
      function updatePriceCalculator() {
        const cost = parseFloat(baseCost.value) || 5000;
        const margin = parseInt(marginSlider.value);
        const multiplier = parseFloat(seasonMultiplier.value);
        
        const basePrice = cost / (1 - margin/100);
        const seasonalPrice = basePrice * multiplier;
        const profit = basePrice - cost;
        
        document.getElementById('basePrice').textContent = '$' + basePrice.toLocaleString(undefined, {maximumFractionDigits: 0});
        document.getElementById('seasonalPrice').textContent = '$' + seasonalPrice.toLocaleString(undefined, {maximumFractionDigits: 0});
        document.getElementById('profitAmount').textContent = '$' + profit.toLocaleString(undefined, {maximumFractionDigits: 0});
        document.getElementById('finalPrice').textContent = '$' + seasonalPrice.toLocaleString(undefined, {maximumFractionDigits: 0});
      }
      
      if (marginSlider && marginValue) {
        marginSlider.addEventListener('input', function() {
          marginValue.textContent = this.value + '%';
          updatePriceCalculator();
        });
      }
      
      if (baseCost) baseCost.addEventListener('input', updatePriceCalculator);
      if (seasonMultiplier) seasonMultiplier.addEventListener('change', updatePriceCalculator);
      
      // Initialize calculator
      updatePriceCalculator();
      
      // Bulk update checkbox functionality
      const bulkCheckboxes = document.querySelectorAll('input[type="checkbox"]');
      const selectedCount = document.getElementById('selectedCount');
      
      function updateBulkSummary() {
        const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const count = checkedBoxes.length;
        selectedCount.textContent = count;
        
        // Calculate estimated impact (simplified)
        const avgPrice = 10000; // Average package price
        const impact = count * avgPrice * 0.1; // Assume 10% change
        document.getElementById('revenueImpact').textContent = '$' + impact.toLocaleString(undefined, {maximumFractionDigits: 0});
        document.getElementById('avgPriceChange').textContent = '10%';
      }
      
      bulkCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkSummary);
      });
      
      // Auto-fill seasonal prices based on base price
      const packageForm = document.getElementById('packageForm');
      if (packageForm) {
        const basePriceInput = packageForm.querySelector('input[name="base_price"]');
        const springPriceInput = packageForm.querySelector('input[name="spring_price"]');
        const summerPriceInput = packageForm.querySelector('input[name="summer_price"]');
        const fallPriceInput = packageForm.querySelector('input[name="fall_price"]');
        const winterPriceInput = packageForm.querySelector('input[name="winter_price"]');
        
        if (basePriceInput) {
          basePriceInput.addEventListener('change', function() {
            const basePrice = parseFloat(this.value);
            if (!isNaN(basePrice)) {
              if (springPriceInput && !springPriceInput.value) {
                springPriceInput.value = (basePrice * 1.1).toFixed(2);
              }
              if (summerPriceInput && !summerPriceInput.value) {
                summerPriceInput.value = (basePrice * 1.3).toFixed(2);
              }
              if (fallPriceInput && !fallPriceInput.value) {
                fallPriceInput.value = (basePrice * 1.1).toFixed(2);
              }
              if (winterPriceInput && !winterPriceInput.value) {
                winterPriceInput.value = (basePrice * 1.25).toFixed(2);
              }
            }
          });
        }
      }
    });

    // Modal functions
    function openPackageModal() {
      document.getElementById('packageModal').style.display = 'flex';
      // Reset form when opening modal
      document.getElementById('packageForm').reset();
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    // Utility functions
    function editPackagePrice(packageName) {
      alert('Editing package: ' + packageName + '\n\nThis feature would populate the form with existing data for editing.');
      openPackageModal();
      
      // In a real implementation, you would fetch the package data and populate the form
      // Example:
      // fetch(`get_package.php?name=${encodeURIComponent(packageName)}`)
      //   .then(response => response.json())
      //   .then(data => populateForm(data));
    }

    function viewPackageDetails(packageName) {
      alert('Viewing details for: ' + packageName + '\n\nThis would open a detailed view modal with booking history, reviews, and performance metrics.');
    }

    function duplicatePackage(packageName) {
      if (confirm('Duplicate package: ' + packageName + '?\n\nThis will create a copy with "- Copy" appended to the name.')) {
        alert('Package duplicated successfully! You can now edit the copy.');
        openPackageModal();
        
        // In a real implementation, you would fetch the package data and populate the form
        // with the duplicated data, changing the name
      }
    }

    function applyCalculatedPrice() {
      const finalPrice = document.getElementById('finalPrice').textContent;
      const packageName = document.getElementById('calcPackageName')?.value || 'New Package';
      const baseCost = document.getElementById('baseCost')?.value || 5000;
      const margin = document.getElementById('marginSlider')?.value || 40;
      
      if (!packageName || packageName === 'Enter package name') {
        alert('Please enter a package name first.');
        return;
      }
      
      if (confirm(`Apply calculated price ${finalPrice} to package: ${packageName}?\n\nBase Cost: $${baseCost}\nTarget Margin: ${margin}%`)) {
        alert('Calculated price applied! Please fill in the remaining details in the form.');
        openPackageModal();
        
        // Populate the form with calculated values
        const form = document.getElementById('packageForm');
        if (form) {
          form.querySelector('input[name="package_name"]').value = packageName;
          form.querySelector('input[name="base_cost"]').value = baseCost;
          form.querySelector('input[name="target_margin"]').value = margin;
          form.querySelector('input[name="base_price"]').value = finalPrice.replace('$', '').replace(',', '');
          
          // Trigger change event to auto-fill seasonal prices
          const basePriceInput = form.querySelector('input[name="base_price"]');
          if (basePriceInput) {
            const event = new Event('change');
            basePriceInput.dispatchEvent(event);
          }
        }
      }
    }

    function applyBulkUpdate() {
      const selectedCount = parseInt(document.getElementById('selectedCount').textContent);
      if (selectedCount === 0) {
        alert('Please select at least one package for bulk update.');
        return;
      }
      
      const adjustmentType = document.getElementById('adjustmentType').value;
      const adjustmentValue = document.getElementById('adjustmentValue').value;
      const adjustmentUnit = document.getElementById('adjustmentUnit').value;
      const effectiveDate = document.getElementById('effectiveDate').value;
      
      if (!adjustmentValue || isNaN(adjustmentValue)) {
        alert('Please enter a valid adjustment value.');
        return;
      }
      
      const actionText = adjustmentType.replace('_', ' ').toLowerCase();
      
      if (confirm(`Apply ${actionText} of ${adjustmentValue}${adjustmentUnit} to ${selectedCount} selected packages?\n\nEffective Date: ${effectiveDate}`)) {
        // In a real implementation, you would send this data to the server
        // Example:
        // fetch('bulk_update.php', {
        //   method: 'POST',
        //   headers: {'Content-Type': 'application/json'},
        //   body: JSON.stringify({
        //     packages: selectedPackages,
        //     adjustmentType: adjustmentType,
        //     adjustmentValue: adjustmentValue,
        //     adjustmentUnit: adjustmentUnit,
        //     effectiveDate: effectiveDate
        //   })
        // })
        
        alert('Bulk price update applied successfully! The changes will take effect immediately.');
        
        // Simulate page refresh to show updated data
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      }
    }

    // Form submission handler
    document.getElementById('packageForm')?.addEventListener('submit', function(e) {
      // Validation is handled by HTML5 required attributes
      // Additional validation can be added here
      
      const baseCost = parseFloat(this.querySelector('input[name="base_cost"]').value);
      const basePrice = parseFloat(this.querySelector('input[name="base_price"]').value);
      
      if (basePrice <= baseCost) {
        e.preventDefault();
        alert('Base price must be greater than base cost for profitability.');
        return;
      }
      
      // Show loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;
      }
      
      // Form will submit normally via POST
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal-overlay')) {
        event.target.style.display = 'none';
      }
    };
    
    // Auto-hide error messages after 5 seconds
    setTimeout(() => {
      const errorMessage = document.querySelector('.alert-error');
      if (errorMessage) {
        errorMessage.style.display = 'none';
      }
    }, 5000);
  </script>
</body>
</html>