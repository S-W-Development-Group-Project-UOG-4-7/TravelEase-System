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
                INSERT INTO trips (user_id, package_id, start_date, end_date, status, created_at)
                VALUES (:uid, :pid, :start_date, :end_date, :status, NOW())
            ");

            $stmt->execute([
                ':uid'        => $userId,
                ':pid'        => $packageId,
                ':start_date' => $startDate,
                ':end_date'   => $endDate,
                ':status'     => 'Pending', // or 'Confirmed' based on your logic
            ]);

            // Optionally redirect to my_trips.php
            $_SESSION['flash_success'] = 'Your trip has been booked successfully!';
            header('Location: my_trips.php');
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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

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
            <a href="user_dashboard.php" class="text-gray-700 hover:text-yellow-500 transition">Dashboard</a>
            <a href="packages.php" class="text-gray-700 hover:text-yellow-500 transition">Packages</a>
            <a href="countries.php" class="text-gray-700 hover:text-yellow-500 transition">Countries</a>
            <a href="my_trips.php" class="text-yellow-500">My Trips</a>
        </nav>

        <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm font-bold text-gray-900">
                <?= htmlspecialchars($userName); ?>
            </span>
            <a href="logout.php"
               class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white transition">
                Logout
            </a>
        </div>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Book a New Trip</h1>
        <p class="text-sm text-gray-600 mb-4">
            Choose your package and travel dates to create a new trip in your TravelEase account.
        </p>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <!-- Package selection -->
            <?php if ($package): ?>
                <!-- Fixed package (came from "Book now") -->
                <input type="hidden" name="package_id" value="<?= (int)$package['id']; ?>">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        Selected Package
                    </label>
                    <div class="border rounded-xl p-3 bg-yellow-50">
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
                            <p class="text-xs text-gray-700 mt-1">
                                From <span class="font-bold">$<?= htmlspecialchars($package['price']); ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Dropdown of packages -->
                <div>
                    <label for="package_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        Choose a Package
                    </label>
                    <select id="package_id" name="package_id"
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-yellow-500 focus:ring-yellow-500">
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
                           class="w-full rounded-xl border-gray-300 text-sm focus:border-yellow-500 focus:ring-yellow-500">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                        End Date
                    </label>
                    <input type="date" id="end_date" name="end_date"
                           value="<?= htmlspecialchars($_POST['end_date'] ?? ''); ?>"
                           class="w-full rounded-xl border-gray-300 text-sm focus:border-yellow-500 focus:ring-yellow-500">
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between gap-3">
                <a href="user_dashboard.php"
                   class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold border border-gray-200 text-gray-700 hover:border-yellow-500 hover:text-yellow-600 transition">
                    ← Back to dashboard
                </a>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2 rounded-full text-xs font-semibold bg-yellow-500 text-white hover:bg-yellow-600 transition">
                    ✅ Confirm Booking
                </button>
            </div>
        </form>
    </div>
</main>

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

</body>
</html>
