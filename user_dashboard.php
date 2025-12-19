<?php
session_start();
date_default_timezone_set('Asia/Colombo'); // ensure consistent display timezone
require_once 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId   = $_SESSION['user_id'];
$userName = $_SESSION['full_name'] ?? 'Traveler';

// ---------- Server-side fallback greeting (will be overridden by JS with local time) ----------
$hour = (int)date('H');
if ($hour >= 5 && $hour < 12) {
    $timeGreeting = 'Good morning';
} elseif ($hour >= 12 && $hour < 18) {
    $timeGreeting = 'Good afternoon';
} else {
    $timeGreeting = 'Good evening';
}

// Last login display (from DB, not session)
$lastLoginDisplay = null;

// Profile image URL (default)
$profileImageUrl = 'img/default-avatar.png';

// Default badge (will adjust after counts)
$badgeLabel = 'Traveler';

// ---------- Fetch data from database ----------

$savedCount            = 0;
$tripsCount            = 0;
$wishlistCount         = 0;
$upcomingTrips         = [];
$recommendedPkgs       = [];
$savedTours            = [];
$countriesVisitedCount = 0;   // distinct completed-trip countries
$recentTrips           = [];  // recent trip history
$dbError               = '';

try {
    // Get last_login + profile_image from users table
    $stmt = $pdo->prepare("SELECT last_login, profile_image FROM users WHERE id = :uid");
    $stmt->execute([':uid' => $userId]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($userRow) {
        // last_login
        if (!empty($userRow['last_login'])) {
            $ts = strtotime($userRow['last_login']);
            if ($ts !== false) {
                $lastLoginDisplay = date('d M Y, g:i A', $ts);
            } else {
                $lastLoginDisplay = $userRow['last_login'];
            }
        }

        // profile_image
        if (!empty($userRow['profile_image'])) {
            $profileImageUrl = 'uploads/profile_pics/' . $userRow['profile_image'];
        }
    }

    // Saved tours count (wishlist)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM saved_tours WHERE user_id = :uid");
    $stmt->execute([':uid' => $userId]);
    $savedCount = (int)($stmt->fetch()['cnt'] ?? 0);

    // Trips count (all trips)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM trips WHERE user_id = :uid");
    $stmt->execute([':uid' => $userId]);
    $tripsCount = (int)($stmt->fetch()['cnt'] ?? 0);

    // For now, wishlist = saved tours
    $wishlistCount = $savedCount;

    // Upcoming trips (next 3)
    $stmt = $pdo->prepare("
        SELECT t.start_date, t.end_date, t.status,
               p.title AS package_title,
               c.name AS country_name,
               p.id AS package_id
        FROM trips t
        JOIN packages p   ON t.package_id = p.id
        JOIN countries c  ON p.country_id = c.id
        WHERE t.user_id = :uid
          AND t.start_date >= CURRENT_DATE
        ORDER BY t.start_date ASC
        LIMIT 3
    ");
    $stmt->execute([':uid' => $userId]);
    $upcomingTrips = $stmt->fetchAll();

    // Recommended packages - REMOVED rating column
    $stmt = $pdo->query("
        SELECT p.id,
               p.title,
               p.price,
               p.duration_days,
               p.duration_nights,
               COALESCE(p.short_description, '') AS short_description,
               c.name AS country_name
        FROM packages p
        JOIN countries c ON p.country_id = c.id
        WHERE p.is_recommended = TRUE
        ORDER BY p.id
        LIMIT 6
    ");
    $recommendedPkgs = $stmt->fetchAll();

    // Saved tours list
    $stmt = $pdo->prepare("
        SELECT p.title,
               c.name AS country_name,
               p.duration_days,
               p.duration_nights,
               p.id AS package_id
        FROM saved_tours s
        JOIN packages p  ON s.package_id = p.id
        JOIN countries c ON p.country_id = c.id
        WHERE s.user_id = :uid
        ORDER BY s.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([':uid' => $userId]);
    $savedTours = $stmt->fetchAll();

    // Distinct countries visited (completed trips)
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT c.id) AS cnt
        FROM trips t
        JOIN packages p ON t.package_id = p.id
        JOIN countries c ON p.country_id = c.id
        WHERE t.user_id = :uid
          AND t.status = 'Completed'
    ");
    $stmt->execute([':uid' => $userId]);
    $countriesVisitedCount = (int)($stmt->fetch()['cnt'] ?? 0);

    // Recent trip history (last 5, any status)
    $stmt = $pdo->prepare("
        SELECT t.start_date,
               t.end_date,
               t.status,
               p.title AS package_title,
               c.name AS country_name
        FROM trips t
        JOIN packages p ON t.package_id = p.id
        JOIN countries c ON p.country_id = c.id
        WHERE t.user_id = :uid
        ORDER BY t.start_date DESC
        LIMIT 5
    ");
    $stmt->execute([':uid' => $userId]);
    $recentTrips = $stmt->fetchAll();

    // Badge logic
    if ($tripsCount >= 5 || $savedCount >= 10 || $countriesVisitedCount >= 5) {
        $badgeLabel = 'VIP Explorer';
    } elseif ($tripsCount >= 2 || $savedCount >= 5 || $countriesVisitedCount >= 2) {
        $badgeLabel = 'Asia Explorer';
    } else {
        $badgeLabel = 'Traveler';
    }

} catch (PDOException $e) {
    $dbError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TravelEase | User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind configuration -->
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
                            900: '#78350f',
                        },
                        secondary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-down': 'slideDown 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'shimmer': 'shimmer 2s infinite',
                        'gradient': 'gradient 3s ease infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .country-region.active {
            outline: 3px solid #fbbf24;
            outline-offset: 2px;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #0369a1;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #bae6fd 0%, #7dd3fc 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(2, 132, 199, 0.2);
        }
        
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.7s ease;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            animation: pulse-ring 2s infinite;
        }
        
        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }
        
        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .dashboard-card {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        
        .dashboard-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b, #d97706);
        }
        
        .quick-action-btn {
            position: relative;
            overflow: hidden;
        }
        
        .quick-action-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .quick-action-btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .map-country {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .map-country:hover {
            filter: brightness(1.1);
            transform: scale(1.02);
        }
        
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse 2s infinite;
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        .theme-toggle {
            position: relative;
            width: 50px;
            height: 26px;
            border-radius: 13px;
            background: #e5e7eb;
            transition: background 0.3s ease;
        }
        
        .theme-toggle.active {
            background: #fbbf24;
        }
        
        .theme-toggle-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            transition: transform 0.3s ease;
        }
        
        .theme-toggle.active .theme-toggle-slider {
            transform: translateX(24px);
        }
        
        .dark-mode {
            background: #111827;
            color: #f9fafb;
        }
        
        .dark-mode .glass-card {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(71, 85, 105, 0.3);
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark-mode .custom-scrollbar::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .dark-mode .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
        
        .dark-mode .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen transition-colors duration-300" id="body">
    <!-- Top Navbar -->
    <header class="glass-card shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="img/Logo.png" alt="TravelEase Logo" class="h-12 w-auto animate-float">
                    <div class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center">
                        <span class="text-xs font-bold text-white">✓</span>
                    </div>
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-xl font-bold text-primary-600">TravelEase</span>
                    <span class="text-xs text-gray-500">Full Asia Travel Experience</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="user_dashboard.php" class="text-primary-600 font-bold flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="packages.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                    <i class="fas fa-suitcase"></i>
                    <span>Packages</span>
                </a>
                <a href="countries.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                    <i class="fas fa-globe-asia"></i>
                    <span>Countries</span>
                </a>
                <a href="user_profile.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
                
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notification-btn" class="text-gray-700 hover:text-primary-500 transition">
                        <i class="fas fa-bell text-lg"></i>
                        <div class="notification-dot hidden" id="notification-dot"></div>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 p-4 hidden z-50">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-gray-800">Notifications</h3>
                            <button class="text-xs text-primary-600 hover:text-primary-800">Mark all read</button>
                        </div>
                        <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar">
                            <div class="flex gap-3 p-2 rounded-lg bg-primary-50">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600">
                                    <i class="fas fa-gift text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Special offer available!</p>
                                    <p class="text-xs text-gray-500">20% off on Thailand packages</p>
                                    <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex gap-3 p-2 rounded-lg hover:bg-gray-50">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Trip confirmed</p>
                                    <p class="text-xs text-gray-500">Japan adventure package</p>
                                    <p class="text-xs text-gray-400 mt-1">Yesterday</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="block text-center mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium">
                            View all notifications
                        </a>
                    </div>
                </div>
                
                <!-- Theme Toggle -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Light</span>
                    <button id="theme-toggle" class="theme-toggle">
                        <span class="theme-toggle-slider"></span>
                    </button>
                    <span class="text-xs text-gray-500">Dark</span>
                </div>
            </nav>

            <div class="flex items-center gap-3">
                <!-- Quick Action Menu -->
                <div class="hidden md:flex items-center gap-2">
                    <button onclick="window.location.href='book_trip.php'" 
                            class="quick-action-btn btn-primary px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Book Trip</span>
                    </button>
                    <div class="relative">
                        <button id="quick-menu-btn" 
                                class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-700 hover:text-primary-500 hover:border-primary-300">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="quick-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 p-2 hidden z-40">
                            <a href="travel_tips.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-lightbulb text-primary-500"></i>
                                <span>Travel Tips</span>
                            </a>
                            <a href="currency_converter.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-exchange-alt text-primary-500"></i>
                                <span>Currency Converter</span>
                            </a>
                            <a href="weather_check.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-cloud-sun text-primary-500"></i>
                                <span>Weather Check</span>
                            </a>
                            <hr class="my-1">
                            <a href="help_center.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-question-circle text-primary-500"></i>
                                <span>Help Center</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User avatar with dropdown -->
                <div class="relative">
                    <button id="user-menu-btn" class="flex items-center gap-2">
                        <div class="relative">
                            <img
                                src="<?= htmlspecialchars($profileImageUrl); ?>"
                                alt="Profile Picture"
                                class="w-10 h-10 rounded-full object-cover border-2 border-primary-300"
                            >
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>
                        <span class="hidden sm:inline text-sm font-bold text-gray-900">
                            <?= htmlspecialchars($userName); ?>
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    
                    <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 p-2 hidden z-40">
                        <div class="p-3 border-b">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($userName); ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($badgeLabel); ?></p>
                        </div>
                        <a href="user_profile.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-user text-primary-500"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="my_trips.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-suitcase-rolling text-primary-500"></i>
                            <span>My Trips</span>
                        </a>
                        <a href="settings.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-cog text-primary-500"></i>
                            <span>Settings</span>
                        </a>
                        <hr class="my-1">
                        <a href="logout.php" class="flex items-center gap-2 p-3 rounded-lg hover:bg-red-50 text-sm text-red-600">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t px-4 py-3">
            <div class="space-y-2">
                <a href="user_dashboard.php" class="flex items-center gap-3 p-3 rounded-lg bg-primary-50 text-primary-600 font-medium">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="packages.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-suitcase"></i>
                    <span>Packages</span>
                </a>
                <a href="countries.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-globe-asia"></i>
                    <span>Countries</span>
                </a>
                <a href="user_profile.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
                <hr>
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="window.location.href='book_trip.php'" 
                            class="btn-primary py-2 rounded-lg text-sm font-semibold">
                        Book Trip
                    </button>
                    <button onclick="window.location.href='travel_tips.php'"
                            class="btn-secondary py-2 rounded-lg text-sm font-semibold">
                        Travel Tips
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <?php if (!empty($dbError)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <span>Database error: <?= htmlspecialchars($dbError); ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Welcome Banner + Quick Stats -->
        <section class="grid gap-6 lg:grid-cols-3 animate-fade-in">
            <!-- Welcome Banner -->
            <div class="lg:col-span-2 dashboard-card glass-card p-6 relative overflow-hidden">
                <!-- Background decorative elements -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary-100 to-transparent rounded-full -translate-y-20 translate-x-20"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-secondary-100 to-transparent rounded-full translate-y-16 -translate-x-16"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <!-- Time-based greeting -->
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-2 rounded-full bg-primary-100">
                                    <i class="fas fa-sun text-primary-600"></i>
                                </div>
                                <p id="time-greeting-text"
                                   class="text-sm font-semibold text-primary-600 uppercase tracking-wide"
                                   data-php-greeting="<?= htmlspecialchars($timeGreeting); ?>">
                                   <span class="greeting-text"><?= htmlspecialchars($timeGreeting); ?></span>,
                                   <span class="font-bold text-gray-900"><?= htmlspecialchars($userName); ?></span>
                                </p>
                            </div>

                            <div class="mb-4">
                                <h1 id="main-welcome-text"
                                    class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"
                                    data-full-text="Welcome back! Ready to explore Asia again?">
                                    Welcome back! Ready to explore Asia again?
                                </h1>
                                <p class="text-sm text-gray-600">
                                    Discover new destinations, plan your next trip, and manage everything right here in your TravelEase dashboard.
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <!-- Badge with icon -->
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-primary-50 to-yellow-50 border border-primary-200">
                                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-crown text-primary-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-primary-800">
                                        <?= htmlspecialchars($badgeLabel); ?>
                                    </span>
                                </div>

                                <!-- Last login info -->
                                <?php if (!empty($lastLoginDisplay)): ?>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <i class="fas fa-clock"></i>
                                        <span>Last login: <?= htmlspecialchars($lastLoginDisplay); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-2 text-sm text-primary-600">
                                        <i class="fas fa-gift"></i>
                                        <span>First time here? Welcome aboard! 🎒</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:items-end">
                            <button onclick="window.location.href='packages.php'"
                                   class="btn-primary px-5 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 group">
                                <i class="fas fa-search"></i>
                                <span>Find New Packages</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <button onclick="window.location.href='user_profile.php'"
                                   class="btn-secondary px-5 py-3 rounded-xl text-sm font-semibold flex items-center gap-3">
                                <i class="fas fa-user-edit"></i>
                                <span>Edit Profile</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                <!-- Saved Tours Card -->
                <div class="stat-card glass-card rounded-2xl p-5 hover-lift cursor-pointer" onclick="window.location.href='saved_tours.php'">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saved Tours</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $savedCount; ?></p>
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-heart text-red-500 text-xs"></i>
                                <p class="text-xs text-green-600">Your wishlist</p>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-red-50">
                            <i class="fas fa-heart text-red-500"></i>
                        </div>
                    </div>
                    <!-- Progress indicator -->
                    <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 rounded-full" style="width: <?= min($savedCount * 10, 100); ?>%"></div>
                    </div>
                </div>

                <!-- Booked Trips Card -->
                <div class="stat-card glass-card rounded-2xl p-5 hover-lift cursor-pointer" onclick="window.location.href='my_trips.php'">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Booked Trips</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $tripsCount; ?></p>
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-calendar-check text-blue-500 text-xs"></i>
                                <p class="text-xs text-blue-600">Plan & manage</p>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-blue-50">
                            <i class="fas fa-suitcase-rolling text-blue-500"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: <?= min($tripsCount * 20, 100); ?>%"></div>
                    </div>
                </div>

                <!-- Countries Visited Card -->
                <div class="stat-card glass-card rounded-2xl p-5 hover-lift col-span-2 sm:col-span-1 lg:col-span-2 xl:col-span-2 cursor-pointer" onclick="window.location.href='my_trips.php'">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Countries Visited</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $countriesVisitedCount; ?></p>
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-passport text-purple-500 text-xs"></i>
                                <p class="text-xs text-purple-600">Completed destinations</p>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50">
                            <i class="fas fa-globe-asia text-purple-500"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <div class="flex-1 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width: <?= min($countriesVisitedCount * 20, 100); ?>%"></div>
                        </div>
                        <span class="text-xs font-medium text-purple-600"><?= min($countriesVisitedCount * 20, 100); ?>%</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive Map + Upcoming Trips -->
        <section class="grid gap-6 lg:grid-cols-3 animate-fade-in">
            <!-- Interactive Asia Map Panel -->
            <div class="lg:col-span-2 dashboard-card glass-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-primary-50">
                            <i class="fas fa-map-marked-alt text-primary-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Explore Asia</h2>
                            <p class="text-xs text-gray-500 mt-1">
                                Click a country on the map to filter recommended packages.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="clear-map-filter"
                                class="text-xs font-semibold text-primary-600 hover:text-primary-800 flex items-center gap-1">
                            <i class="fas fa-times"></i>
                            Clear filter
                        </button>
                        <button id="zoom-in" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center">
                            <i class="fas fa-search-plus text-gray-600"></i>
                        </button>
                        <button id="zoom-out" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center">
                            <i class="fas fa-search-minus text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <div class="relative w-full h-64 sm:h-80 rounded-2xl border-2 border-primary-100 bg-gradient-to-br from-primary-50 to-white overflow-hidden">
                    <!-- Interactive Map SVG -->
                    <svg id="asia-map" viewBox="0 0 800 400" class="w-full h-full" style="transform-origin: center; transition: transform 0.3s ease;">
                        <!-- India -->
                        <g class="country-region map-country cursor-pointer" data-country-region="India">
                            <rect x="320" y="200" width="90" height="60" fill="#FEF3C7" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="330" y="235" font-size="12" fill="#92400E" font-weight="600">India</text>
                            <circle cx="355" cy="210" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Sri Lanka -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Sri Lanka">
                            <rect x="360" y="270" width="40" height="40" fill="#FFFBEB" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="362" y="295" font-size="11" fill="#92400E" font-weight="600">Sri Lanka</text>
                            <circle cx="380" cy="280" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Japan -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Japan">
                            <rect x="550" y="130" width="60" height="60" fill="#FEF3C7" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="560" y="160" font-size="12" fill="#92400E" font-weight="600">Japan</text>
                            <circle cx="580" cy="140" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Thailand -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Thailand">
                            <rect x="400" y="220" width="70" height="50" fill="#FFFBEB" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="405" y="250" font-size="11" fill="#92400E" font-weight="600">Thailand</text>
                            <circle cx="435" cy="230" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Maldives -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Maldives">
                            <rect x="340" y="310" width="25" height="25" fill="#FEF3C7" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="305" y="306" font-size="10" fill="#92400E" font-weight="600">Maldives</text>
                            <circle cx="353" cy="323" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Nepal -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Nepal">
                            <rect x="335" y="170" width="70" height="25" fill="#FFFBEB" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="345" y="187" font-size="11" fill="#92400E" font-weight="600">Nepal</text>
                            <circle cx="370" cy="183" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Bangladesh -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Bangladesh">
                            <rect x="410" y="190" width="55" height="35" fill="#FEF3C7" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="412" y="210" font-size="10" fill="#92400E" font-weight="600">Bangladesh</text>
                            <circle cx="438" cy="208" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Pakistan -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Pakistan">
                            <rect x="260" y="190" width="55" height="45" fill="#FFFBEB" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="262" y="210" font-size="10" fill="#92400E" font-weight="600">Pakistan</text>
                            <circle cx="288" cy="213" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>

                        <!-- Bhutan -->
                        <g class="country-region map-country cursor-pointer" data-country-region="Bhutan">
                            <rect x="380" y="175" width="40" height="20" fill="#FEF3C7" stroke="#FBBF24" stroke-width="2"></rect>
                            <text x="382" y="190" font-size="10" fill="#92400E" font-weight="600">Bhutan</text>
                            <circle cx="400" cy="185" r="3" fill="#F59E0B" class="pulse-ring"></circle>
                        </g>
                    </svg>

                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-primary-500"></div>
                        <div class="text-sm">
                            Filter: <span id="active-country-label" class="font-semibold text-primary-700">All Asia</span>
                        </div>
                    </div>
                    
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-2 rounded-lg shadow-sm text-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-mouse-pointer text-primary-600"></i>
                            <span>Click to explore</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trips -->
            <div class="dashboard-card glass-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-blue-50">
                            <i class="fas fa-plane-departure text-blue-600"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Your Upcoming Trips</h2>
                    </div>
                    <a href="my_trips.php" class="text-sm font-semibold text-primary-600 hover:text-primary-800 flex items-center gap-1">
                        View all
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="space-y-4 text-sm flex-1 overflow-y-auto custom-scrollbar max-h-64">
                    <?php if (empty($upcomingTrips)): ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-calendar-plus text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">
                                You don't have any upcoming trips yet.
                            </p>
                            <button onclick="window.location.href='packages.php'"
                                    class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                                Book Your First Trip
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($upcomingTrips as $trip): 
                            $daysToTrip = floor((strtotime($trip['start_date']) - time()) / (60 * 60 * 24));
                            $statusColor = $trip['status'] === 'Confirmed' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-yellow-100 text-yellow-800 border-yellow-200';
                        ?>
                            <div class="border border-gray-100 rounded-xl p-4 flex flex-col gap-2 hover:border-primary-200 hover-lift cursor-pointer" 
                                 onclick="window.location.href='package_details.php?id=<?= (int)$trip['package_id']; ?>'">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-900 truncate">
                                        <?= htmlspecialchars($trip['package_title']); ?>
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full font-medium <?= $statusColor; ?>">
                                        <?= htmlspecialchars($trip['status']); ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-globe-asia"></i>
                                    <span><?= htmlspecialchars($trip['country_name']); ?></span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <div class="flex items-center gap-1 text-gray-600">
                                            <i class="fas fa-calendar-day"></i>
                                            <span><?= htmlspecialchars($trip['start_date']); ?></span>
                                        </div>
                                        <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                        <div class="flex items-center gap-1 text-gray-600">
                                            <i class="fas fa-calendar-day"></i>
                                            <span><?= htmlspecialchars($trip['end_date']); ?></span>
                                        </div>
                                    </div>
                                    <div class="text-xs px-2 py-1 rounded-full bg-primary-50 text-primary-700 font-medium">
                                        <?= $daysToTrip > 0 ? "In $daysToTrip days" : "Soon"; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button onclick="window.location.href='book_trip.php'"
                   class="mt-6 btn-primary py-3 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    Book a New Trip
                </button>
            </div>
        </section>

        <!-- Recommended Packages + Saved Tours -->
        <section class="grid gap-6 lg:grid-cols-3 animate-fade-in">
            <!-- Recommended Packages -->
            <div class="lg:col-span-2 dashboard-card glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-green-50">
                            <i class="fas fa-star text-green-600"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Recommended for You</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="prev-packages" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-left text-gray-600"></i>
                        </button>
                        <button id="next-packages" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <i class="fas fa-chevron-right text-gray-600"></i>
                        </button>
                        <a href="packages.php" class="text-sm font-semibold text-primary-600 hover:text-primary-800 flex items-center gap-1">
                            View all
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <div id="packages-carousel" class="relative overflow-hidden">
                    <div id="packages-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm transition-transform duration-300">
                        <?php if (empty($recommendedPkgs)): ?>
                            <div class="col-span-3 text-center py-8">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-compass text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-500">
                                    No recommended packages available yet. Please check back later.
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recommendedPkgs as $pkg): 
                                // Generate a random rating between 4.0 and 5.0 for demo purposes
                                // In a real app, this would come from a reviews table
                                $rating = mt_rand(40, 50) / 10; // Random rating 4.0-5.0
                                $ratingWidth = ($rating / 5) * 100;
                            ?>
                                <div class="border border-gray-100 rounded-2xl p-5 flex flex-col gap-3 hover-lift cursor-pointer group"
                                     data-package-country="<?= htmlspecialchars($pkg['country_name']); ?>"
                                     onclick="window.location.href='package_details.php?id=<?= (int)$pkg['id']; ?>'">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold text-primary-600 uppercase tracking-wide">
                                                <?= htmlspecialchars($pkg['country_name']); ?>
                                            </span>
                                        </div>
                                        <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-red-50 hover:border-red-200 hover:text-red-500">
                                            <i class="fas fa-heart text-sm"></i>
                                        </button>
                                    </div>
                                    
                                    <h3 class="font-bold text-gray-900 text-base group-hover:text-primary-700">
                                        <?= htmlspecialchars($pkg['title']); ?>
                                    </h3>
                                    
                                    <p class="text-xs text-gray-500 line-clamp-2">
                                        <?= htmlspecialchars($pkg['short_description']); ?>
                                    </p>
                                    
                                    <!-- Rating - Using demo data since rating column doesn't exist -->
                                    <div class="flex items-center gap-2">
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star text-<?= $i <= floor($rating) ? 'yellow-400' : 'gray-300'; ?> text-xs"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-xs text-gray-500"><?= number_format($rating, 1); ?></span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">
                                                $<?= number_format($pkg['price'], 2); ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= (int)$pkg['duration_days'] . 'D / ' . (int)$pkg['duration_nights'] . 'N'; ?>
                                            </p>
                                        </div>
                                        <button class="btn-primary px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 group-hover:scale-105 transition-transform">
                                            <span>View Details</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Saved Tours -->
            <div class="dashboard-card glass-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-pink-50">
                            <i class="fas fa-bookmark text-pink-600"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Saved Tours</h2>
                    </div>
                    <a href="saved_tours.php" class="text-sm font-semibold text-primary-600 hover:text-primary-800 flex items-center gap-1">
                        Manage
                        <i class="fas fa-cog text-xs"></i>
                    </a>
                </div>

                <div class="space-y-3 text-sm flex-1 overflow-y-auto custom-scrollbar max-h-64">
                    <?php if (empty($savedTours)): ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class="far fa-heart text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">
                                You haven't saved any tours yet.
                            </p>
                            <p class="text-xs text-gray-400">
                                Click the heart icon on a package to add it here.
                            </p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($savedTours as $tour): ?>
                            <div class="flex items-center justify-between gap-3 p-3 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift cursor-pointer" 
                                 onclick="window.location.href='package_details.php?id=<?= (int)$tour['package_id']; ?>'">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-primary-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            <?= htmlspecialchars($tour['title']); ?>
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-globe-asia"></i>
                                                <?= htmlspecialchars($tour['country_name']); ?>
                                            </p>
                                            <span class="text-xs text-gray-400">•</span>
                                            <p class="text-xs text-gray-500">
                                                <i class="far fa-clock"></i>
                                                <?= (int)$tour['duration_days'] . 'D / ' . (int)$tour['duration_nights'] . 'N'; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mt-6 flex gap-3">
                    <button onclick="window.location.href='saved_tours.php'"
                           class="flex-1 btn-secondary py-3 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-list"></i>
                        See All Saved
                    </button>
                    <button onclick="window.location.href='packages.php'"
                           class="flex-1 btn-primary py-3 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add More
                    </button>
                </div>
            </div>
        </section>

        <!-- Recent Trip History -->
        <section class="dashboard-card glass-card p-6 animate-fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-indigo-50">
                        <i class="fas fa-history text-indigo-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Recent Trip History</h2>
                </div>
                <a href="my_trips.php" class="text-sm font-semibold text-primary-600 hover:text-primary-800 flex items-center gap-1">
                    Manage all trips
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="space-y-3 text-sm">
                <?php if (empty($recentTrips)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-plane text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-500">
                            Once you book and complete trips, your latest journeys will appear here.
                        </p>
                        <button onclick="window.location.href='packages.php'"
                                class="mt-4 btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                            Book Your First Trip
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentTrips as $trip): 
                        $statusColor = '';
                        $statusIcon = '';
                        switch ($trip['status']) {
                            case 'Completed':
                                $statusColor = 'bg-green-100 text-green-800 border-green-200';
                                $statusIcon = 'fa-check-circle';
                                break;
                            case 'Cancelled':
                                $statusColor = 'bg-red-100 text-red-800 border-red-200';
                                $statusIcon = 'fa-times-circle';
                                break;
                            case 'Confirmed':
                                $statusColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                $statusIcon = 'fa-check-circle';
                                break;
                            default:
                                $statusColor = 'bg-gray-100 text-gray-800 border-gray-200';
                                $statusIcon = 'fa-clock';
                                break;
                        }
                    ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-50 to-secondary-50 flex items-center justify-center">
                                    <i class="fas <?= $statusIcon; ?> text-primary-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        <?= htmlspecialchars($trip['package_title']); ?>
                                    </p>
                                    <div class="flex flex-wrap items-center gap-3 mt-1">
                                        <p class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas fa-globe-asia"></i>
                                            <?= htmlspecialchars($trip['country_name']); ?>
                                        </p>
                                        <span class="text-xs text-gray-400">•</span>
                                        <p class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?= htmlspecialchars($trip['start_date']); ?>
                                            –
                                            <?= htmlspecialchars($trip['end_date']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor; ?> gap-2">
                                    <i class="fas <?= $statusIcon; ?>"></i>
                                    <?= htmlspecialchars($trip['status']); ?>
                                </span>
                                <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-primary-50 hover:border-primary-300 hover:text-primary-600">
                                    <i class="fas fa-redo text-xs"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Quick Tips Section -->
        <section class="dashboard-card glass-card p-6 animate-fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-amber-50">
                        <i class="fas fa-lightbulb text-amber-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Travel Tips & Quick Actions</h2>
                </div>
                <a href="travel_tips.php" class="text-sm font-semibold text-primary-600 hover:text-primary-800">
                    More Tips →
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <button onclick="window.location.href='currency_converter.php'" 
                        class="group p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift bg-white flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-exchange-alt text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Currency Converter</p>
                        <p class="text-xs text-gray-500 mt-1">Check exchange rates</p>
                    </div>
                </button>
                
                <button onclick="window.location.href='weather_check.php'"
                        class="group p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift bg-white flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cloud-sun text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Weather Check</p>
                        <p class="text-xs text-gray-500 mt-1">Plan with weather info</p>
                    </div>
                </button>
                
                <button onclick="window.location.href='travel_checklist.php'"
                        class="group p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift bg-white flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-clipboard-check text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Travel Checklist</p>
                        <p class="text-xs text-gray-500 mt-1">Don't forget anything</p>
                    </div>
                </button>
                
                <button onclick="window.location.href='local_guides.php'"
                        class="group p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover-lift bg-white flex flex-col items-center text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-map-signs text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Local Guides</p>
                        <p class="text-xs text-gray-500 mt-1">Find expert guides</p>
                    </div>
                </button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="mt-8 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="img/Logo.png" alt="TravelEase Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold text-primary-600">TravelEase</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Your gateway to unforgettable Asian adventures. Quality travel experiences since 2010.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="packages.php" class="text-gray-600 hover:text-primary-600">All Packages</a></li>
                        <li><a href="countries.php" class="text-gray-600 hover:text-primary-600">Destinations</a></li>
                        <li><a href="my_trips.php" class="text-gray-600 hover:text-primary-600">My Trips</a></li>
                        <li><a href="saved_tours.php" class="text-gray-600 hover:text-primary-600">Saved Tours</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="help_center.php" class="text-gray-600 hover:text-primary-600">Help Center</a></li>
                        <li><a href="faq.php" class="text-gray-600 hover:text-primary-600">FAQ</a></li>
                        <li><a href="contact.php" class="text-gray-600 hover:text-primary-600">Contact Us</a></li>
                        <li><a href="privacy.php" class="text-gray-600 hover:text-primary-600">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Stay Connected</h3>
                    <p class="text-sm text-gray-600 mb-4">Subscribe for travel deals and tips</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <button class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-6 text-center text-sm text-gray-500">
                <p>© <?= date('Y'); ?> TravelEase · Full Asia Travel Experience. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Interactive map + greeting / typing script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ----- Map filter -----
            const regions = document.querySelectorAll('.country-region');
            const packageCards = document.querySelectorAll('[data-package-country]');
            const activeLabel = document.getElementById('active-country-label');
            const clearBtn = document.getElementById('clear-map-filter');
            const zoomInBtn = document.getElementById('zoom-in');
            const zoomOutBtn = document.getElementById('zoom-out');
            const mapSvg = document.getElementById('asia-map');
            
            let activeCountry = '';
            let currentScale = 1;

            function applyFilter(country) {
                packageCards.forEach(card => {
                    const cardCountry = card.getAttribute('data-package-country');
                    if (!country || cardCountry === country) {
                        card.style.display = '';
                        card.classList.remove('hidden');
                    } else {
                        card.style.display = 'none';
                        card.classList.add('hidden');
                    }
                });

                if (!country) {
                    activeLabel.textContent = 'All Asia';
                } else {
                    activeLabel.textContent = country;
                }
            }

            regions.forEach(region => {
                region.addEventListener('click', () => {
                    const country = region.getAttribute('data-country-region');

                    // Toggle selection
                    if (activeCountry === country) {
                        activeCountry = '';
                    } else {
                        activeCountry = country;
                    }

                    // Update active styles
                    regions.forEach(r => r.classList.remove('active'));
                    if (activeCountry) {
                        region.classList.add('active');
                    }

                    applyFilter(activeCountry);
                });
            });

            clearBtn.addEventListener('click', () => {
                activeCountry = '';
                regions.forEach(r => r.classList.remove('active'));
                applyFilter('');
            });
            
            // Zoom functionality
            zoomInBtn.addEventListener('click', () => {
                currentScale = Math.min(currentScale + 0.1, 1.5);
                mapSvg.style.transform = `scale(${currentScale})`;
            });
            
            zoomOutBtn.addEventListener('click', () => {
                currentScale = Math.max(currentScale - 0.1, 0.8);
                mapSvg.style.transform = `scale(${currentScale})`;
            });

            // ----- Local-time based greeting (browser time) -----
            const greetingEl = document.getElementById('time-greeting-text');
            if (greetingEl) {
                const date = new Date();
                const hour = date.getHours();
                let greeting;
                let greetingIcon;
                
                if (hour >= 5 && hour < 12) {
                    greeting = 'Good morning';
                    greetingIcon = 'fa-sun';
                } else if (hour >= 12 && hour < 18) {
                    greeting = 'Good afternoon';
                    greetingIcon = 'fa-cloud-sun';
                } else {
                    greeting = 'Good evening';
                    greetingIcon = 'fa-moon';
                }
                
                // Update the greeting text
                const greetingText = greetingEl.querySelector('.greeting-text');
                if (greetingText) {
                    greetingText.textContent = greeting;
                }
                
                // Update icon
                const iconContainer = greetingEl.closest('.flex.items-center').querySelector('.p-2');
                if (iconContainer) {
                    iconContainer.innerHTML = `<i class="fas ${greetingIcon} text-primary-600"></i>`;
                }
            }

            // ----- Typing effect for welcome text -----
            const mainWelcomeEl = document.getElementById('main-welcome-text');
            if (mainWelcomeEl) {
                const fullText = mainWelcomeEl.getAttribute('data-full-text') || mainWelcomeEl.textContent;
                mainWelcomeEl.textContent = '';
                let index = 0;

                function typeNext() {
                    if (index <= fullText.length) {
                        mainWelcomeEl.textContent = fullText.slice(0, index);
                        index++;
                        setTimeout(typeNext, 40); // typing speed (ms)
                    }
                }

                // Start typing after a short delay
                setTimeout(typeNext, 500);
            }
            
            // ----- Carousel navigation for packages -----
            const prevBtn = document.getElementById('prev-packages');
            const nextBtn = document.getElementById('next-packages');
            const packagesGrid = document.getElementById('packages-grid');
            
            if (prevBtn && nextBtn && packagesGrid) {
                let currentSlide = 0;
                const totalSlides = Math.ceil(packageCards.length / 3); // Assuming 3 per slide
                
                prevBtn.addEventListener('click', () => {
                    if (currentSlide > 0) {
                        currentSlide--;
                        updateCarousel();
                    }
                });
                
                nextBtn.addEventListener('click', () => {
                    if (currentSlide < totalSlides - 1) {
                        currentSlide++;
                        updateCarousel();
                    }
                });
                
                function updateCarousel() {
                    const translateX = -currentSlide * 100;
                    packagesGrid.style.transform = `translateX(${translateX}%)`;
                    
                    // Update button states
                    prevBtn.disabled = currentSlide === 0;
                    nextBtn.disabled = currentSlide === totalSlides - 1;
                }
            }
            
            // ----- Theme toggle -----
            const themeToggle = document.getElementById('theme-toggle');
            const body = document.getElementById('body');
            
            if (themeToggle) {
                // Check for saved theme preference
                const savedTheme = localStorage.getItem('theme') || 'light';
                if (savedTheme === 'dark') {
                    body.classList.add('dark-mode');
                    themeToggle.classList.add('active');
                }
                
                themeToggle.addEventListener('click', () => {
                    themeToggle.classList.toggle('active');
                    body.classList.toggle('dark-mode');
                    
                    // Save preference
                    const theme = body.classList.contains('dark-mode') ? 'dark' : 'light';
                    localStorage.setItem('theme', theme);
                });
            }
            
            // ----- Dropdown menus -----
            const notificationBtn = document.getElementById('notification-btn');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationDot = document.getElementById('notification-dot');
            const userMenuBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-dropdown');
            const quickMenuBtn = document.getElementById('quick-menu-btn');
            const quickMenu = document.getElementById('quick-menu');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            // Show notification dot (simulate new notifications)
            if (notificationDot) {
                setTimeout(() => {
                    notificationDot.classList.remove('hidden');
                }, 1000);
            }
            
            // Toggle dropdowns
            function toggleDropdown(button, dropdown, closeOthers = true) {
                if (!button || !dropdown) return;
                
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    if (closeOthers) {
                        // Close all other dropdowns
                        [notificationDropdown, userDropdown, quickMenu, mobileMenu].forEach(d => {
                            if (d && d !== dropdown) d.classList.add('hidden');
                        });
                    }
                    
                    dropdown.classList.toggle('hidden');
                });
            }
            
            toggleDropdown(notificationBtn, notificationDropdown);
            toggleDropdown(userMenuBtn, userDropdown);
            toggleDropdown(quickMenuBtn, quickMenu);
            toggleDropdown(mobileMenuBtn, mobileMenu, false);
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationBtn?.contains(e.target) && !notificationDropdown?.contains(e.target)) {
                    notificationDropdown?.classList.add('hidden');
                }
                
                if (!userMenuBtn?.contains(e.target) && !userDropdown?.contains(e.target)) {
                    userDropdown?.classList.add('hidden');
                }
                
                if (!quickMenuBtn?.contains(e.target) && !quickMenu?.contains(e.target)) {
                    quickMenu?.classList.add('hidden');
                }
                
                if (!mobileMenuBtn?.contains(e.target) && !mobileMenu?.contains(e.target)) {
                    mobileMenu?.classList.add('hidden');
                }
            });
            
            // Add ripple effect to buttons
            document.querySelectorAll('.quick-action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Animate stat cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-slide-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.stat-card, .dashboard-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>