<?php
// book_trip.php
session_start();
date_default_timezone_set('Asia/Colombo');
require_once 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId   = $_SESSION['user_id'];
$userName = $_SESSION['full_name'] ?? 'Traveler';

$errors   = [];
$success  = '';
$package  = null;

// Get package_id if coming from "Book now" button
$packageId = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;

// If POST, handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = isset($_POST['package_id']) ? (int)$_POST['package_id'] : 0;
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate   = trim($_POST['end_date'] ?? '');

    // Basic validation
    if ($packageId <= 0) {
        $errors[] = 'Please select a valid package.';
    }
    if ($startDate === '' || $endDate === '') {
        $errors[] = 'Please select both start and end dates.';
    } else {
        $startTs = strtotime($startDate);
        $endTs   = strtotime($endDate);
        $todayTs = strtotime(date('Y-m-d'));

        if ($startTs === false || $endTs === false) {
            $errors[] = 'Invalid date format.';
        } elseif ($startTs < $todayTs) {
            $errors[] = 'Start date cannot be in the past.';
        } elseif ($endTs < $startTs) {
            $errors[] = 'End date cannot be before start date.';
        }
    }

    // Check package exists
    if ($packageId > 0) {
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.price, c.name AS country_name
            FROM packages p
            JOIN countries c ON p.country_id = c.id
            WHERE p.id = :pid
            LIMIT 1
        ");
        $stmt->execute([':pid' => $packageId]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$package) {
            $errors[] = 'Selected package does not exist.';
        }
    }

    // If no errors, insert into trips
    if (empty($errors) && $package) {
        try {
            $stmt = $pdo->prepare("
            INSERT INTO trips (user_id, package_id, start_date, end_date, status)
            VALUES (:uid, :pid, :start_date, :end_date, :status)
        ");

            $stmt->execute([
                ':uid'        => $userId,
                ':pid'        => $packageId,
                ':start_date' => $startDate,
                ':end_date'   => $endDate,
                ':status'     => 'Pending', // or 'Confirmed' based on your logic
            ]);

            // Redirect to payment step
            $tripId = (int)$pdo->lastInsertId();
            $_SESSION['flash_success'] = 'Trip created! Please complete payment to confirm your booking.';
            header('Location: payment.php?trip_id=' . $tripId);
            exit();

        } catch (PDOException $e) {
            $errors[] = 'Database error while booking: ' . $e->getMessage();
        }
    }
} else {
    // GET request: if package_id passed, load that package for display
    if ($packageId > 0) {
        $stmt = $pdo->prepare("
            SELECT p.id, p.title, p.price, p.duration_days, p.duration_nights,
                   c.name AS country_name
            FROM packages p
            JOIN countries c ON p.country_id = c.id
            WHERE p.id = :pid
            LIMIT 1
        ");
        $stmt->execute([':pid' => $packageId]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Fallback: if no specific package, fetch a list for dropdown
$packageList = [];
if (!$package) {
    $stmt = $pdo->query("
        SELECT p.id, p.title, c.name AS country_name
        FROM packages p
        JOIN countries c ON p.country_id = c.id
        ORDER BY c.name, p.title
        LIMIT 100
    ");
    $packageList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Trip | TravelEase</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
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
        .btn-ghost:hover {
            border-color: #fbbf24;
            color: #d97706;
        }
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
            <a href="my_trips.php" class="text-primary-600 font-bold flex items-center gap-2">
                <i class="fas fa-suitcase-rolling"></i>
                <span>My Trips</span>
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm font-bold text-gray-900">
                <?= htmlspecialchars($userName); ?>
            </span>
            <a href="logout.php" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="glass-card rounded-3xl shadow-xl p-6 sm:p-8 animate-fade-in">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Book a New Trip</h1>
                <p class="text-sm text-gray-600 mt-2">
                    Choose a package and dates. You'll confirm the booking with payment on the next step.
                </p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-gray-600 bg-white/70 border border-gray-100 rounded-full px-3 py-2">
                <i class="fas fa-lock text-primary-600"></i>
                <span>Secure checkout</span>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5 mt-6" novalidate>
            <!-- Package selection -->
            <?php if ($package): ?>
                <input type="hidden" name="package_id" value="<?= (int)$package['id']; ?>">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        Selected Package
                    </label>
                    <div class="border border-primary-100 rounded-2xl p-4 bg-primary-50/60">
                        <p class="font-semibold text-gray-900 text-sm">
                            <?= htmlspecialchars($package['title']); ?>
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            <?= htmlspecialchars($package['country_name']); ?>
                            <?php if (isset($package['duration_days'], $package['duration_nights'])): ?>
                                • <?= (int)$package['duration_days']; ?>D /
                                  <?= (int)$package['duration_nights']; ?>N
                            <?php endif; ?>
                        </p>
                        <?php if (isset($package['price'])): ?>
                            <p class="text-xs text-gray-700 mt-2">
                                From <span class="font-extrabold text-gray-900">$<?= htmlspecialchars($package['price']); ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div>
                    <label for="package_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        Choose a Package
                    </label>
                    <select id="package_id" name="package_id"
                            class="w-full rounded-2xl border-gray-200 bg-white/90 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">-- Select a package --</option>
                        <?php foreach ($packageList as $p): ?>
                            <option value="<?= (int)$p['id']; ?>" <?= $packageId === (int)$p['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($p['country_name'] . ' - ' . $p['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        Start Date
                    </label>
                    <input type="date" id="start_date" name="start_date"
                           value="<?= htmlspecialchars($_POST['start_date'] ?? ''); ?>"
                           class="w-full rounded-2xl border-gray-200 bg-white/90 text-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        End Date
                    </label>
                    <input type="date" id="end_date" name="end_date"
                           value="<?= htmlspecialchars($_POST['end_date'] ?? ''); ?>"
                           class="w-full rounded-2xl border-gray-200 bg-white/90 text-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>

            <div class="pt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="user_dashboard.php" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to dashboard
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm btn-primary">
                    <i class="fas fa-credit-card mr-2"></i>Continue to payment
                </button>
            </div>
        </form>
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
