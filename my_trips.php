<?php
session_start();
date_default_timezone_set('Asia/Colombo');
require_once 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId   = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['full_name'] ?? 'Traveler';

// Flash messages
$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$trips   = [];
$dbError = '';

try {
    $stmt = $pdo->prepare("
        SELECT
            t.id AS trip_id,
            t.start_date,
            t.end_date,
            t.status,
            t.created_at,
            p.title AS package_title,
            p.price,
            p.duration_days,
            p.duration_nights,
            c.name AS country_name
        FROM trips t
        JOIN packages p  ON t.package_id = p.id
        JOIN countries c ON p.country_id = c.id
        WHERE t.user_id = :uid
        ORDER BY t.id DESC
    ");
    $stmt->execute([':uid' => $userId]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $dbError = $e->getMessage();
}

function badgeClasses(string $status): array {
    $s = strtolower(trim($status));
    if ($s === 'confirmed') return ['bg-green-100 text-green-700 border-green-200', 'Confirmed'];
    if ($s === 'completed') return ['bg-blue-100 text-blue-700 border-blue-200', 'Completed'];
    if ($s === 'cancelled' || $s === 'canceled') return ['bg-red-100 text-red-700 border-red-200', 'Cancelled'];
    return ['bg-amber-100 text-amber-800 border-amber-200', 'Pending'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Trips | TravelEase</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d', 400: '#fbbf24',
                            500: '#f59e0b', 600: '#d97706', 700: '#b45309', 800: '#92400e', 900: '#78350f'
                        },
                        secondary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8',
                            500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { transform: 'translateY(10px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
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
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            font-weight: 700;
            transition: all 0.25s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.25);
        }
        .btn-ghost:hover { border-color: #fbbf24; color: #d97706; }
    </style>
</head>
<body class="min-h-screen">

<header class="glass-card shadow-lg sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="img/Logo.png" alt="TravelEase Logo" class="h-11 w-auto">
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
            <a href="user_dashboard.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="packages.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                <i class="fas fa-suitcase"></i><span>Packages</span>
            </a>
            <a href="countries.php" class="text-gray-700 hover:text-primary-500 transition flex items-center gap-2">
                <i class="fas fa-globe-asia"></i><span>Countries</span>
            </a>
            <a href="my_trips.php" class="text-primary-600 font-bold flex items-center gap-2">
                <i class="fas fa-suitcase-rolling"></i><span>My Trips</span>
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm font-bold text-gray-900"><?= htmlspecialchars($userName); ?></span>
            <a href="logout.php" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 animate-fade-in">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">My Trips</h1>
            <p class="text-sm text-gray-600 mt-1">View your bookings and complete payment for pending trips.</p>
        </div>
        <a href="book_trip.php" class="inline-flex items-center justify-center px-5 py-3 rounded-full text-sm btn-primary">
            <i class="fas fa-plus mr-2"></i>Book a trip
        </a>
    </div>

    <?php if ($dbError): ?>
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-4">
            <i class="fas fa-exclamation-circle mr-2"></i>
            Database error: <?= htmlspecialchars($dbError); ?>
        </div>
    <?php endif; ?>

    <?php if ($flashSuccess): ?>
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm p-4">
            <i class="fas fa-check-circle mr-2"></i>
            <?= htmlspecialchars($flashSuccess); ?>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-4">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= htmlspecialchars($flashError); ?>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <?php if (empty($trips)): ?>
            <div class="glass-card rounded-3xl shadow-xl p-8 text-center">
                <div class="w-14 h-14 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-suitcase-rolling text-xl"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-900">No trips yet</h2>
                <p class="text-sm text-gray-600 mt-1">When you book a package, it will appear here.</p>
                <a href="book_trip.php" class="inline-flex mt-5 items-center justify-center px-6 py-3 rounded-full text-sm btn-primary">
                    <i class="fas fa-plus mr-2"></i>Book your first trip
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($trips as $t): ?>
                <?php [$badgeCls, $badgeLabel] = badgeClasses((string)($t['status'] ?? 'Pending')); ?>
                <div class="glass-card rounded-3xl shadow-xl p-5 sm:p-6 animate-slide-up">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-lg font-extrabold text-gray-900">
                                    <?= htmlspecialchars($t['package_title'] ?? 'Trip'); ?>
                                </h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?= htmlspecialchars($badgeCls); ?>">
                                    <?= htmlspecialchars($badgeLabel); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-location-dot text-primary-600 mr-1"></i>
                                <?= htmlspecialchars($t['country_name'] ?? ''); ?>
                                <?php if (!empty($t['duration_days']) && !empty($t['duration_nights'])): ?>
                                    <span class="text-gray-400">•</span>
                                    <?= (int)$t['duration_days']; ?>D / <?= (int)$t['duration_nights']; ?>N
                                <?php endif; ?>
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
                                <div class="rounded-2xl bg-white/70 border border-gray-100 p-3">
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Start</p>
                                    <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($t['start_date'] ?? ''); ?></p>
                                </div>
                                <div class="rounded-2xl bg-white/70 border border-gray-100 p-3">
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">End</p>
                                    <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($t['end_date'] ?? ''); ?></p>
                                </div>
                                <div class="rounded-2xl bg-white/70 border border-gray-100 p-3">
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Amount</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        <?php if ($t['price'] !== null && $t['price'] !== ''): ?>
                                            $<?= htmlspecialchars($t['price']); ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex sm:flex-col gap-2 sm:min-w-[180px]">
                            <?php if (strtolower((string)($t['status'] ?? '')) === 'pending'): ?>
                                <a href="payment.php?trip_id=<?= (int)$t['trip_id']; ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm btn-primary w-full">
                                    <i class="fas fa-credit-card mr-2"></i>Pay now
                                </a>
                            <?php endif; ?>
                            <a href="book_trip.php" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition w-full">
                                <i class="fas fa-plus mr-2"></i>Book again
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<footer class="mt-10 border-t border-gray-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
        <p>© <?= date('Y'); ?> TravelEase · Full Asia Travel Experience</p>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-primary-600">Support</a>
            <a href="#" class="hover:text-primary-600">Terms</a>
            <a href="#" class="hover:text-primary-600">Privacy</a>
        </div>
    </div>
</footer>

</body>
</html>
