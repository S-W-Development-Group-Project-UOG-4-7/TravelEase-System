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

$tripId = isset($_GET['trip_id']) ? (int)$_GET['trip_id'] : (int)($_POST['trip_id'] ?? 0);

$errors = [];
$trip   = null;

// Load trip + package (must belong to this user)
if ($tripId > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT
                t.id AS trip_id,
                t.start_date,
                t.end_date,
                t.status,
                t.created_at,
                p.id AS package_id,
                p.title AS package_title,
                p.price,
                p.duration_days,
                p.duration_nights,
                c.name AS country_name
            FROM trips t
            JOIN packages p  ON t.package_id = p.id
            JOIN countries c ON p.country_id = c.id
            WHERE t.id = :tid AND t.user_id = :uid
            LIMIT 1
        ");
        $stmt->execute([':tid' => $tripId, ':uid' => $userId]);
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    }
} else {
    $errors[] = 'Invalid trip. Please select a trip to pay for.';
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = trim($_POST['payment_method'] ?? '');

    if (!$trip) {
        $errors[] = 'Trip not found.';
    }

    if ($method === '') {
        $errors[] = 'Please choose a payment method.';
    }

    // Basic, non-sensitive validation (we do not store or process real card data here)
    if ($method === 'card') {
        $cardName = trim($_POST['card_name'] ?? '');
        $cardLast4 = preg_replace('/\D+/', '', (string)($_POST['card_last4'] ?? ''));
        if ($cardName === '') $errors[] = 'Cardholder name is required.';
        if (strlen($cardLast4) !== 4) $errors[] = 'Enter the last 4 digits of your card.';
    }

    if ($method === 'bank') {
        $reference = trim($_POST['bank_reference'] ?? '');
        if ($reference === '') $errors[] = 'Bank transfer reference is required.';
    }

    if (empty($errors) && $trip) {
        // If already confirmed/completed, don't re-process
        $statusNow = strtolower((string)($trip['status'] ?? ''));
        if ($statusNow === 'confirmed' || $statusNow === 'completed') {
            $_SESSION['flash_success'] = 'This trip is already confirmed.';
            header('Location: my_trips.php');
            exit();
        }

        try {
            $stmt = $pdo->prepare("UPDATE trips SET status = :status WHERE id = :tid AND user_id = :uid");
            $stmt->execute([
                ':status' => 'Confirmed',
                ':tid'    => $tripId,
                ':uid'    => $userId,
            ]);

            $_SESSION['flash_success'] = 'Payment received. Your trip is confirmed!';
            header('Location: my_trips.php');
            exit();

        } catch (PDOException $e) {
            $errors[] = 'Could not confirm payment: ' . $e->getMessage();
        }
    }
}

function statusBadge(string $status): array {
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
    <title>Payment | TravelEase</title>
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
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Summary -->
        <section class="lg:col-span-2">
            <div class="glass-card rounded-3xl shadow-xl p-6">
                <div class="flex items-center justify-between gap-3">
                    <h1 class="text-xl font-extrabold text-gray-900">Payment</h1>
                    <?php if ($trip): ?>
                        <?php [$sCls, $sLbl] = statusBadge((string)($trip['status'] ?? 'Pending')); ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?= htmlspecialchars($sCls); ?>">
                            <?= htmlspecialchars($sLbl); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <p class="text-sm text-gray-600 mt-2">Review your trip and choose a payment method.</p>

                <?php if (!empty($errors)): ?>
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                        <ul class="list-disc list-inside space-y-1">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($trip): ?>
                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl bg-white/70 border border-gray-100 p-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Package</p>
                            <p class="text-sm font-extrabold text-gray-900 mt-1"><?= htmlspecialchars($trip['package_title']); ?></p>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-location-dot text-primary-600 mr-1"></i>
                                <?= htmlspecialchars($trip['country_name']); ?>
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-white/70 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Start</p>
                                <p class="text-sm font-bold text-gray-900 mt-1"><?= htmlspecialchars($trip['start_date']); ?></p>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">End</p>
                                <p class="text-sm font-bold text-gray-900 mt-1"><?= htmlspecialchars($trip['end_date']); ?></p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-primary-50/60 border border-primary-100 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-700">Total</p>
                                <p class="text-lg font-extrabold text-gray-900">
                                    <?php if ($trip['price'] !== null && $trip['price'] !== ''): ?>
                                        $<?= htmlspecialchars($trip['price']); ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">Taxes/fees depend on the package details.</p>
                        </div>

                        <a href="my_trips.php" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition w-full">
                            <i class="fas fa-arrow-left mr-2"></i>Back to My Trips
                        </a>
                    </div>
                <?php else: ?>
                    <a href="my_trips.php" class="inline-flex mt-5 items-center justify-center px-4 py-2 rounded-full text-sm btn-primary w-full">
                        <i class="fas fa-suitcase-rolling mr-2"></i>Go to My Trips
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Payment form -->
        <section class="lg:col-span-3">
            <div class="glass-card rounded-3xl shadow-xl p-6">
                <h2 class="text-lg font-extrabold text-gray-900">Choose a payment method</h2>
                <p class="text-sm text-gray-600 mt-1">This is a demo flow. For real payments, connect a payment gateway.</p>

                <form method="post" class="mt-6 space-y-5" novalidate>
                    <input type="hidden" name="trip_id" value="<?= (int)$tripId; ?>">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="rounded-2xl border border-gray-200 bg-white/70 p-4 cursor-pointer hover:border-primary-300 transition">
                            <input type="radio" name="payment_method" value="card" class="mr-2" <?= (($_POST['payment_method'] ?? '') === 'card') ? 'checked' : '' ?>>
                            <span class="font-bold text-gray-900"><i class="fas fa-credit-card text-primary-600 mr-1"></i> Card</span>
                            <p class="text-xs text-gray-600 mt-1">Visa / MasterCard</p>
                        </label>
                        <label class="rounded-2xl border border-gray-200 bg-white/70 p-4 cursor-pointer hover:border-primary-300 transition">
                            <input type="radio" name="payment_method" value="bank" class="mr-2" <?= (($_POST['payment_method'] ?? '') === 'bank') ? 'checked' : '' ?>>
                            <span class="font-bold text-gray-900"><i class="fas fa-building-columns text-primary-600 mr-1"></i> Bank</span>
                            <p class="text-xs text-gray-600 mt-1">Online transfer</p>
                        </label>
                        <label class="rounded-2xl border border-gray-200 bg-white/70 p-4 cursor-pointer hover:border-primary-300 transition">
                            <input type="radio" name="payment_method" value="cash" class="mr-2" <?= (($_POST['payment_method'] ?? '') === 'cash') ? 'checked' : '' ?>>
                            <span class="font-bold text-gray-900"><i class="fas fa-money-bill-wave text-primary-600 mr-1"></i> Cash</span>
                            <p class="text-xs text-gray-600 mt-1">Pay at office</p>
                        </label>
                    </div>

                    <div id="card-fields" class="rounded-2xl border border-gray-100 bg-white/60 p-4 space-y-4 hidden">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Cardholder name</label>
                            <input type="text" name="card_name" value="<?= htmlspecialchars($_POST['card_name'] ?? ''); ?>"
                                   class="w-full rounded-2xl border-gray-200 bg-white text-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Name on card">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Last 4 digits</label>
                            <input type="text" name="card_last4" inputmode="numeric" maxlength="4" value="<?= htmlspecialchars($_POST['card_last4'] ?? ''); ?>"
                                   class="w-full rounded-2xl border-gray-200 bg-white text-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="1234">
                            <p class="text-xs text-gray-500 mt-2">We only ask for the last 4 digits in this demo.</p>
                        </div>
                    </div>

                    <div id="bank-fields" class="rounded-2xl border border-gray-100 bg-white/60 p-4 space-y-4 hidden">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Transfer reference</label>
                            <input type="text" name="bank_reference" value="<?= htmlspecialchars($_POST['bank_reference'] ?? ''); ?>"
                                   class="w-full rounded-2xl border-gray-200 bg-white text-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="e.g., TRV-12345">
                        </div>
                        <div class="text-xs text-gray-600">
                            <p><span class="font-semibold">Bank:</span> TravelEase Holdings</p>
                            <p><span class="font-semibold">Account:</span> 000-123-4567</p>
                            <p><span class="font-semibold">Branch:</span> Colombo</p>
                        </div>
                    </div>

                    <div id="cash-fields" class="rounded-2xl border border-gray-100 bg-white/60 p-4 hidden">
                        <p class="text-sm text-gray-700 font-semibold">Pay at our office</p>
                        <p class="text-xs text-gray-600 mt-1">You can visit our TravelEase office with your booking ID and pay in cash. We'll confirm the trip immediately after payment.</p>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <a href="my_trips.php" class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 text-gray-700 btn-ghost transition">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm btn-primary">
                            <i class="fas fa-lock mr-2"></i>Confirm payment
                        </button>
                    </div>
                </form>
            </div>
        </section>
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

<script>
    function toggleFields() {
        const method = (document.querySelector('input[name="payment_method"]:checked') || {}).value || '';
        document.getElementById('card-fields').classList.toggle('hidden', method !== 'card');
        document.getElementById('bank-fields').classList.toggle('hidden', method !== 'bank');
        document.getElementById('cash-fields').classList.toggle('hidden', method !== 'cash');
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(r => {
        r.addEventListener('change', toggleFields);
    });
    toggleFields();
</script>

</body>
</html>
