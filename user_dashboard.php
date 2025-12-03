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
               c.name AS country_name
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

    // Recommended packages
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
               p.duration_nights
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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .country-region.active {
            outline: 3px solid #fbbf24;
            outline-offset: 2px;
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .welcome-animate {
            animation: fadeInUp 0.6s ease-out;
        }
        /* Glow effect for welcome heading */
        @keyframes glowText {
            0% {
                text-shadow: 0 0 0 rgba(250, 174, 50, 0.0);
            }
            50% {
                text-shadow: 0 0 12px rgba(250, 174, 50, 0.8);
            }
            100% {
                text-shadow: 0 0 0 rgba(250, 174, 50, 0.0);
            }
        }
        .glow-welcome {
            animation: glowText 2.5s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Top Navbar -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-2">
                <img src="img/Logo.png" alt="TravelEase Logo" class="h-10 w-auto">
                <div class="flex flex-col leading-tight">
                    <span class="text-xl font-bold text-yellow-500">TravelEase</span>
                    <span class="text-xs text-gray-500">Full Asia Travel Experience</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="user_dashboard.php" class="text-yellow-500">Dashboard</a>
                <a href="packages.php" class="text-gray-700 hover:text-yellow-500 transition">Packages</a>
                <a href="countries.php" class="text-gray-700 hover:text-yellow-500 transition">Countries</a>
                <a href="user_profile.php" class="text-gray-700 hover:text-yellow-500 transition">Profile</a>
            </nav>

            <div class="flex items-center gap-3">
                <!-- User avatar -->
                <a href="user_profile.php" class="flex items-center gap-2">
                    <img
                        src="<?= htmlspecialchars($profileImageUrl); ?>"
                        alt="Profile Picture"
                        class="w-9 h-9 rounded-full object-cover border border-gray-200"
                    >
                    <span class="hidden sm:inline text-sm font-bold text-gray-900">
                        <?= htmlspecialchars($userName); ?>
                    </span>
                </a>

                <a href="logout.php"
                   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white transition">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <?php if (!empty($dbError)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3">
                Database error: <?= htmlspecialchars($dbError); ?>
            </div>
        <?php endif; ?>

        <!-- Welcome + Quick stats -->
        <section class="grid gap-6 lg:grid-cols-3">
            <!-- Welcome card -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <!-- Time-based greeting (JS will adjust using local time) -->
                        <p id="time-greeting-text"
                           class="text-sm font-semibold text-yellow-500 uppercase tracking-wide welcome-animate"
                           data-php-greeting="<?= htmlspecialchars($timeGreeting); ?>">
                            <?= htmlspecialchars($timeGreeting); ?>,
                            <span class="text-gray-900"><?= htmlspecialchars($userName); ?></span>
                        </p>

                        <div class="mt-1 welcome-animate">
                            <h1 id="main-welcome-text"
                                class="text-2xl sm:text-3xl font-bold text-gray-900 glow-welcome"
                                data-full-text="Welcome back! Ready to explore Asia again?">
                                Welcome back! Ready to explore Asia again?
                            </h1>
                            <p class="text-sm text-gray-600 mt-2">
                                Discover new destinations, plan your next trip, and manage everything right here in your TravelEase dashboard.
                            </p>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-3 welcome-animate">
                            <!-- Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-300">
                                ✨ <?= htmlspecialchars($badgeLabel); ?>
                            </span>

                            <!-- Last login info -->
                            <?php if (!empty($lastLoginDisplay)): ?>
                                <span class="text-xs text-gray-500">
                                    Last login:
                                    <?= htmlspecialchars($lastLoginDisplay); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-xs text-gray-500">
                                    First time here? Welcome aboard! 🎒
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 sm:items-end">
                        <a href="packages.php"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-yellow-500 text-white text-sm font-semibold hover:bg-yellow-600 transition">
                            🔍 Find New Packages
                        </a>
                        <a href="user_profile.php"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-gray-200 text-xs text-gray-700 hover:border-yellow-500 hover:text-yellow-600 transition">
                            ⚙️ Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick stats -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-2 gap-3">
                <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col justify-between">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saved Tours</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?= $savedCount; ?></p>
                    <p class="text-xs text-green-600 mt-1">Your wishlist</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col justify-between">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Booked Trips</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?= $tripsCount; ?></p>
                    <p class="text-xs text-blue-600 mt-1">Plan & manage</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col justify-between col-span-2 sm:col-span-1 lg:col-span-2 xl:col-span-2">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Countries Visited</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?= $countriesVisitedCount; ?></p>
                    <p class="text-xs text-purple-600 mt-1">Completed trip destinations</p>
                </div>
            </div>
        </section>

        <!-- Map + Upcoming Trips -->
        <section class="grid gap-6 lg:grid-cols-3">
            <!-- Interactive Asia Map Panel -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Explore Asia</h2>
                        <p class="text-xs text-gray-500 mt-1">
                            Click a country on the map to filter recommended packages.
                        </p>
                    </div>
                    <button id="clear-map-filter"
                            class="text-xs font-semibold text-yellow-600 hover:text-yellow-700">
                        Clear filter
                    </button>
                </div>

                <div class="relative w-full h-64 sm:h-80 rounded-2xl border border-yellow-200 bg-gradient-to-br from-yellow-50 to-white overflow-hidden flex items-center justify-center">
                    <!-- Simple abstract Asia map using SVG blocks -->
                    <svg viewBox="0 0 800 400" class="w-full h-full">
                        <!-- India -->
                        <g class="country-region cursor-pointer" data-country-region="India">
                            <rect x="320" y="200" width="90" height="60" fill="#FEF3C7" stroke="#FBBF24"></rect>
                            <text x="330" y="235" font-size="12" fill="#92400E">India</text>
                        </g>

                        <!-- Sri Lanka -->
                        <g class="country-region cursor-pointer" data-country-region="Sri Lanka">
                            <rect x="360" y="270" width="40" height="40" fill="#FFFBEB" stroke="#FBBF24"></rect>
                            <text x="362" y="295" font-size="11" fill="#92400E">Sri Lanka</text>
                        </g>

                        <!-- Japan -->
                        <g class="country-region cursor-pointer" data-country-region="Japan">
                            <rect x="550" y="130" width="60" height="60" fill="#FEF3C7" stroke="#FBBF24"></rect>
                            <text x="560" y="160" font-size="12" fill="#92400E">Japan</text>
                        </g>

                        <!-- Thailand -->
                        <g class="country-region cursor-pointer" data-country-region="Thailand">
                            <rect x="400" y="220" width="70" height="50" fill="#FFFBEB" stroke="#FBBF24"></rect>
                            <text x="405" y="250" font-size="11" fill="#92400E">Thailand</text>
                        </g>

                        <!-- Maldives -->
                        <g class="country-region cursor-pointer" data-country-region="Maldives">
                            <rect x="340" y="310" width="25" height="25" fill="#FEF3C7" stroke="#FBBF24"></rect>
                            <text x="305" y="306" font-size="10" fill="#92400E">Maldives</text>
                        </g>

                        <!-- Nepal -->
                        <g class="country-region cursor-pointer" data-country-region="Nepal">
                            <rect x="335" y="170" width="70" height="25" fill="#FFFBEB" stroke="#FBBF24"></rect>
                            <text x="345" y="187" font-size="11" fill="#92400E">Nepal</text>
                        </g>

                        <!-- Bangladesh -->
                        <g class="country-region cursor-pointer" data-country-region="Bangladesh">
                            <rect x="410" y="190" width="55" height="35" fill="#FEF3C7" stroke="#FBBF24"></rect>
                            <text x="412" y="210" font-size="10" fill="#92400E">Bangladesh</text>
                        </g>

                        <!-- Pakistan -->
                        <g class="country-region cursor-pointer" data-country-region="Pakistan">
                            <rect x="260" y="190" width="55" height="45" fill="#FFFBEB" stroke="#FBBF24"></rect>
                            <text x="262" y="210" font-size="10" fill="#92400E">Pakistan</text>
                        </g>

                        <!-- Bhutan -->
                        <g class="country-region cursor-pointer" data-country-region="Bhutan">
                            <rect x="380" y="175" width="40" height="20" fill="#FEF3C7" stroke="#FBBF24"></rect>
                            <text x="382" y="190" font-size="10" fill="#92400E">Bhutan</text>
                        </g>
                    </svg>

                    <div class="absolute bottom-3 left-4 bg-white/80 backdrop-blur px-3 py-1 rounded-full text-xs text-gray-700">
                        Filter: <span id="active-country-label" class="font-semibold">All Asia</span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trips -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Your Upcoming Trips</h2>
                    <a href="my_trips.php" class="text-xs font-semibold text-yellow-600 hover:text-yellow-700">
                        View all
                    </a>
                </div>

                <div class="space-y-4 text-sm">
                    <?php if (empty($upcomingTrips)): ?>
                        <p class="text-xs text-gray-500">
                            You don’t have any upcoming trips yet. Start by booking a package!
                        </p>
                    <?php else: ?>
                        <?php foreach ($upcomingTrips as $trip): ?>
                            <div class="border rounded-xl p-3 flex flex-col gap-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-900">
                                        <?= htmlspecialchars($trip['package_title']); ?>
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        <?= $trip['status'] === 'Confirmed'
                                            ? 'bg-yellow-50 text-yellow-700 border border-yellow-200'
                                            : 'bg-gray-50 text-gray-700 border-gray-200 border'; ?>">
                                        <?= htmlspecialchars($trip['status']); ?>
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <?= htmlspecialchars($trip['country_name']); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    📅
                                    <?= htmlspecialchars($trip['start_date']); ?> –
                                    <?= htmlspecialchars($trip['end_date']); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <a href="packages.php"
                   class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-semibold border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white transition">
                    + Book a new trip
                </a>
            </div>
        </section>

        <!-- Recommended Packages + Saved Tours -->
        <section class="grid gap-6 lg:grid-cols-3">
            <!-- Recommended Packages -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recommended for You</h2>
                    <a href="packages.php" class="text-xs font-semibold text-yellow-600 hover:text-yellow-700">
                        View all packages →</a>
                </div>

                <div id="packages-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <?php if (empty($recommendedPkgs)): ?>
                        <p class="text-xs text-gray-500">
                            No recommended packages available yet. Please check back later.
                        </p>
                    <?php else: ?>
                        <?php foreach ($recommendedPkgs as $pkg): ?>
                            <div class="border rounded-2xl p-4 flex flex-col gap-2"
                                 data-package-country="<?= htmlspecialchars($pkg['country_name']); ?>">
                                <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wide">
                                    <?= htmlspecialchars($pkg['country_name']); ?>
                                </p>
                                <h3 class="font-semibold text-gray-900">
                                    <?= htmlspecialchars($pkg['title']); ?>
                                </h3>
                                <p class="text-xs text-gray-500">
                                    <?= htmlspecialchars($pkg['short_description']); ?>
                                </p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-sm font-bold text-gray-900">
                                        $<?= htmlspecialchars($pkg['price']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= (int)$pkg['duration_days'] . 'D / ' . (int)$pkg['duration_nights'] . 'N'; ?>
                                    </p>
                                </div>
                                <a href="package_details.php?id=<?= (int)$pkg['id']; ?>"
                                   class="mt-2 inline-flex items-center justify-center px-3 py-2 rounded-full text-xs font-semibold bg-yellow-500 text-white hover:bg-yellow-600 transition">
                                    View details
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Saved Tours -->
            <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Saved Tours</h2>
                    <a href="saved_tours.php" class="text-xs font-semibold text-yellow-600 hover:text-yellow-700">
                        Manage
                    </a>
                </div>

                <div class="space-y-3 text-sm">
                    <?php if (empty($savedTours)): ?>
                        <p class="text-xs text-gray-500">
                            You haven’t saved any tours yet. Click the heart icon on a package to add it here.
                        </p>
                    <?php else: ?>
                        <?php foreach ($savedTours as $tour): ?>
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">
                                        <?= htmlspecialchars($tour['title']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= htmlspecialchars($tour['country_name']); ?>
                                        •
                                        <?= (int)$tour['duration_days'] . 'D / ' . (int)$tour['duration_nights'] . 'N'; ?>
                                    </p>
                                </div>
                                <a href="packages.php"
                                   class="text-xs text-yellow-600 hover:text-yellow-700">
                                    View
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <a href="saved_tours.php"
                   class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-semibold border border-gray-200 text-gray-700 hover:border-yellow-500 hover:text-yellow-600 transition">
                    See all saved tours
                </a>
            </div>
        </section>

        <!-- Recent Trip History -->
        <section class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Trip History</h2>
                <a href="my_trips.php" class="text-xs font-semibold text-yellow-600 hover:text-yellow-700">
                    Manage all trips →</a>
            </div>

            <div class="space-y-3 text-sm">
                <?php if (empty($recentTrips)): ?>
                    <p class="text-xs text-gray-500">
                        Once you book and complete trips, your latest journeys will appear here.
                    </p>
                <?php else: ?>
                    <?php foreach ($recentTrips as $trip): ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border rounded-xl p-3">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    <?= htmlspecialchars($trip['package_title']); ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?= htmlspecialchars($trip['country_name']); ?>
                                    •
                                    📅 <?= htmlspecialchars($trip['start_date']); ?>
                                    – <?= htmlspecialchars($trip['end_date']); ?>
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                <?php
                                switch ($trip['status']) {
                                    case 'Completed':
                                        echo 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                                        break;
                                    case 'Cancelled':
                                        echo 'bg-red-50 text-red-700 border border-red-200';
                                        break;
                                    default:
                                        echo 'bg-gray-50 text-gray-700 border-gray-200';
                                        break;
                                }
                                ?>">
                                <?= htmlspecialchars($trip['status']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="mt-8 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <p>© <?= date('Y'); ?> TravelEase · Full Asia Travel Experience</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-yellow-600">Support</a>
                <a href="#" class="hover:text-yellow-600">Terms</a>
                <a href="#" class="hover:text-yellow-600">Privacy</a>
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

            let activeCountry = '';

            function applyFilter(country) {
                packageCards.forEach(card => {
                    const cardCountry = card.getAttribute('data-package-country');
                    if (!country || cardCountry === country) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
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

            // ----- Local-time based greeting (browser time) -----
            const greetingEl = document.getElementById('time-greeting-text');
            if (greetingEl) {
                const date = new Date();
                const hour = date.getHours();
                let greeting;
                if (hour >= 5 && hour < 12) {
                    greeting = 'Good morning';
                } else if (hour >= 12 && hour < 18) {
                    greeting = 'Good afternoon';
                } else {
                    greeting = 'Good evening';
                }

                // Update only the greeting text, keep username span
                const span = greetingEl.querySelector('span');
                if (span) {
                    greetingEl.innerHTML = greeting + ', ' + span.outerHTML;
                } else {
                    greetingEl.textContent = greeting;
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

                typeNext();
            }
        });
    </script>
</body>
</html>
