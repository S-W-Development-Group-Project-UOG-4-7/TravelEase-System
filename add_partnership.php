<?php
// add_partnership.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Partnership types and other data (same as main page)
$partnershipTypes = [
    'hotel' => 'Hotel Partnerships',
    'airline' => 'Airline Partnerships',
    'tour_operator' => 'Tour Operators',
    'influencer' => 'Influencers & Ambassadors',
    'tourism_board' => 'Tourism Boards',
    'travel_agent' => 'Travel Agents',
];

$partnershipTiers = [
    'platinum' => 'Platinum Partner',
    'gold' => 'Gold Partner',
    'silver' => 'Silver Partner',
    'bronze' => 'Bronze Partner'
];

$industries = [
    'hospitality' => 'Hospitality',
    'aviation' => 'Aviation',
    'tour_operator' => 'Tour Operators',
    'transportation' => 'Transportation',
    'entertainment' => 'Entertainment',
    'retail' => 'Retail',
    'media' => 'Media & Publishing',
    'technology' => 'Technology'
];


$footerLinks = [
    'Partnership Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard.php'],
        ['text' => 'Partnerships', 'link' => 'partnership_collaboration.php'],
        ['text' => 'Add Partnership', 'link' => 'add_partnership.php'],
        ['text' => 'Affiliate Portal', 'link' => 'affiliate_portal.php']
    ],
    'Resources' => [
        ['text' => 'Partner Portal', 'link' => '#'],
        ['text' => 'Agreement Templates', 'link' => '#'],
        ['text' => 'Commission Calculator', 'link' => '#'],
        ['text' => 'Support Center', 'link' => '#']
    ],
    'Account' => [
        ['text' => 'Profile Settings', 'link' => 'marketing_profile.php'],
        ['text' => 'Notification Preferences', 'link' => '#'],
        ['text' => 'Team Management', 'link' => '#'],
        ['text' => 'Logout', 'link' => 'login.php']
    ]
];

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $stmt = $pdo->prepare("
            INSERT INTO partnerships (
                company_name,
                partnership_type,
                contact_person,
                contact_email,
                contact_phone,
                website,
                commission_rate,
                partnership_tier,
                industry,
                notes,
                created_by
            ) VALUES (
                :company_name,
                :partnership_type,
                :contact_person,
                :contact_email,
                :contact_phone,
                :website,
                :commission_rate,
                :partnership_tier,
                :industry,
                :notes,
                :created_by
            )
        ");

        $stmt->execute([
            ':company_name'      => $_POST['company_name'],
            ':partnership_type'  => $_POST['partnership_type'],
            ':contact_person'   => $_POST['contact_person'],
            ':contact_email'    => $_POST['contact_email'],
            ':contact_phone'    => $_POST['contact_phone'] ?? null,
            ':website'          => $_POST['website'] ?? null,
            ':commission_rate'  => $_POST['commission_rate'],
            ':partnership_tier' => $_POST['partnership_tier'] ?? null,
            ':industry'         => $_POST['industry'] ?? null,
            ':notes'            => $_POST['notes'] ?? null,
            ':created_by'       => $managerName
        ]);

        $success = true;
        $message = "Partnership created successfully!";
        $_POST = []; // clear form

    } catch (PDOException $e) {
        $error = "Failed to create partnership. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add New Partnership | TravelEase Marketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                        }
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
            color: #1f2937;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }
        .gold-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fcd34d 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header (Same as main page) -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="partnership_collaboration.php" class="flex items-center gap-3 group">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
                            <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain bg-white p-2">
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-black text-xl tracking-tight text-gray-900">
                                TravelEase
                            </span>
                            <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                                Add Partnership
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Back Button -->
                <div class="flex items-center gap-4">
                    <a href="partnership.php" class="px-4 py-2 rounded-xl border border-amber-300 text-amber-700 hover:bg-amber-50 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Partnerships
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="pt-24 pb-12">
        <div class="form-container px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <?php if (isset($success) && $success): ?>
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="font-semibold"><?= $message ?></span>
                </div>
            </div>
            <?php elseif (isset($error)): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="font-semibold"><?= $error ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black mb-2">
                    <span class="text-gradient">Add New Partnership</span>
                </h1>
                <p class="text-lg text-gray-700">Fill in the details below to add a new partnership</p>
            </div>

            <!-- Partnership Form -->
            <div class="glass-effect rounded-2xl border border-amber-100 shadow p-6">
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                            <input type="text" name="company_name" required 
                                   value="<?= $_POST['company_name'] ?? '' ?>"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partnership Type *</label>
                            <select name="partnership_type" class="w-full p-3 rounded-xl border border-amber-200 bg-white" required>
                                <option value="">Select type</option>
                                <?php foreach ($partnershipTypes as $value => $label): ?>
                                <option value="<?= $value ?>" <?= (($_POST['partnership_type'] ?? '') === $value) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                            <input type="text" name="contact_person" required 
                                   value="<?= $_POST['contact_person'] ?? '' ?>"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="contact_email" required 
                                   value="<?= $_POST['contact_email'] ?? '' ?>"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="contact_phone" 
                                   value="<?= $_POST['contact_phone'] ?? '' ?>"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" placeholder="https://"
                                   value="<?= $_POST['website'] ?? '' ?>"
                                   class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate *</label>
                            <div class="flex items-center">
                                <input type="number" name="commission_rate" step="0.1" min="0" max="100" required 
                                       value="<?= $_POST['commission_rate'] ?? '15' ?>"
                                       class="flex-1 p-3 rounded-l-xl border border-amber-200 bg-white">
                                <span class="px-4 py-3 bg-amber-50 border border-amber-200 border-l-0 rounded-r-xl text-amber-700">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Partnership Tier</label>
                            <select name="partnership_tier" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                                <option value="">Select tier</option>
                                <?php foreach ($partnershipTiers as $value => $label): ?>
                                <option value="<?= $value ?>" <?= (($_POST['partnership_tier'] ?? '') === $value) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Industry</label>
                        <select name="industry" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                            <option value="">Select industry</option>
                            <?php foreach ($industries as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (($_POST['industry'] ?? '') === $value) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes & Collaboration Ideas</label>
                        <textarea name="notes" rows="4" 
                                  placeholder="Describe potential collaboration opportunities, joint promotions, or special arrangements..."
                                  class="w-full p-3 rounded-xl border border-amber-200 bg-white"><?= $_POST['notes'] ?? '' ?></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="partnership_collaboration.php" 
                           class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg">
                            <i class="fas fa-handshake mr-2"></i> Create Partnership
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-amber-100 bg-amber-50 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-8 md:grid-cols-4 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl overflow-hidden bg-white p-1">
                            <div class="h-full w-full gold-gradient rounded-lg"></div>
                        </div>
                        <span class="font-black text-lg text-gray-900">TravelEase</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">
                        Building strong partnerships with hotels, airlines, tour operators, and influencers worldwide.
                    </p>
                </div>

                <?php foreach ($footerLinks as $title => $links): ?>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4"><?= htmlspecialchars($title) ?></h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <?php foreach ($links as $link): ?>
                        <li><a href="<?= htmlspecialchars($link['link']) ?>" class="hover:text-amber-600 transition-colors"><?= htmlspecialchars($link['text']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="pt-8 border-t border-amber-100 text-center text-sm text-gray-600">
                <p>© <?= $currentYear ?> TravelEase Partnership Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>