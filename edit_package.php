<?php
// edit_package.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    header('Location: login.php');
    exit();
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Database connection
require_once __DIR__ . '/db.php';

// Get package ID from URL
$packageId = $_GET['id'] ?? 0;

// Fetch package data
$package = null;
$inclusions = [];
$highlights = [];

if ($packageId) {
    try {
        // Get package data
        $stmt = $pdo->prepare("
            SELECT * FROM travel_packages WHERE id = ?
        ");
        $stmt->execute([$packageId]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$package) {
            $_SESSION['error'] = 'Package not found!';
            header('Location: marketing_campaigns.php');
            exit();
        }
        
        // Get inclusions if stored as JSON or separate table
        if (!empty($package['inclusions'])) {
            $inclusions = json_decode($package['inclusions'], true) ?? [];
        }
        
        // Get highlights if stored separately
        $stmt = $pdo->prepare("
            SELECT * FROM package_highlights WHERE package_id = ? ORDER BY sort_order
        ");
        $stmt->execute([$packageId]);
        $highlights = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get gallery images if stored separately
        $stmt = $pdo->prepare("
            SELECT * FROM package_images WHERE package_id = ? ORDER BY sort_order
        ");
        $stmt->execute([$packageId]);
        $galleryImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error'] = 'Database error occurred!';
        header('Location: marketing_campaigns.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid package ID!';
    header('Location: marketing_campaigns.php');
    exit();
}

// Package categories
$packageCategories = [
    'luxury' => 'Luxury Getaway',
    'adventure' => 'Adventure Tour',
    'wellness' => 'Wellness Retreat',
    'family' => 'Family Vacation',
    'cultural' => 'Cultural Experience',
    'beach' => 'Beach Holiday',
    'honeymoon' => 'Honeymoon Package',
    'business' => 'Business Travel'
];

// Destinations
$destinations = [
    'asia' => 'Asia Pacific',
    'europe' => 'Europe',
    'americas' => 'Americas',
    'africa' => 'Africa',
    'middle_east' => 'Middle East',
    'caribbean' => 'Caribbean'
];

// Countries
$countries = [
    'Indonesia' => 'Indonesia (Bali)',
    'Japan' => 'Japan',
    'Thailand' => 'Thailand',
    'Vietnam' => 'Vietnam',
    'Malaysia' => 'Malaysia',
    'Singapore' => 'Singapore',
    'France' => 'France',
    'Italy' => 'Italy',
    'Spain' => 'Spain',
    'Greece' => 'Greece',
    'USA' => 'United States',
    'Canada' => 'Canada',
    'Australia' => 'Australia',
    'New Zealand' => 'New Zealand',
    'Maldives' => 'Maldives',
    'UAE' => 'United Arab Emirates'
];

// Accommodation types
$accommodationTypes = [
    '5_star' => '5-Star Hotel',
    '4_star' => '4-Star Hotel',
    'boutique' => 'Boutique Hotel',
    'resort' => 'Luxury Resort',
    'villa' => 'Private Villa',
    'apartment' => 'Serviced Apartment',
    'eco_lodge' => 'Eco Lodge',
    'homestay' => 'Homestay'
];

// Package inclusions
$allInclusions = [
    'accommodation' => 'Accommodation',
    'meals' => 'Meals',
    'flights' => 'Flights',
    'transfers' => 'Airport Transfers',
    'tours' => 'Guided Tours',
    'activities' => 'Activities',
    'insurance' => 'Travel Insurance',
    'visa' => 'Visa Assistance',
    'breakfast' => 'Daily Breakfast',
    'wifi' => 'WiFi Access',
    'spa' => 'Spa Access',
    'gym' => 'Gym Access'
];

// Difficulty levels
$difficultyLevels = [
    'easy' => 'Easy (Relaxing)',
    'moderate' => 'Moderate (Some Activity)',
    'active' => 'Active (Physical Activity)',
    'challenging' => 'Challenging (Adventure)'
];

// Footer links
$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard.php'],
        ['text' => 'Create Package', 'link' => 'create_package.php'],
        ['text' => 'Package Descriptions', 'link' => 'package_descriptions.php'],
        ['text' => 'Promotions', 'link' => 'promotional_offers.php']
    ],
    'Resources' => [
        ['text' => 'Package Templates', 'link' => '#'],
        ['text' => 'Destination Guides', 'link' => '#'],
        ['text' => 'Pricing Calculator', 'link' => '#'],
        ['text' => 'Support Center', 'link' => '#']
    ],
    'Account' => [
        ['text' => 'Profile Settings', 'link' => 'marketing_profile.php'],
        ['text' => 'Notification Preferences', 'link' => '#'],
        ['text' => 'Team Management', 'link' => '#'],
        ['text' => 'Logout', 'link' => 'login.php']
    ]
];

// Success/Error messages
$successMsg = $_SESSION['success'] ?? '';
$errorMsg = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Package | TravelEase Marketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
        #description-editor {
            min-height: 300px;
        }
        .ql-editor {
            min-height: 250px;
            font-size: 16px;
            line-height: 1.6;
        }
        .image-upload-area {
            border: 2px dashed #fbbf24;
            transition: all 0.3s ease;
        }
        .image-upload-area:hover {
            border-color: #f59e0b;
            background-color: #fef3c7;
        }
        .image-upload-area.dragover {
            border-color: #f59e0b;
            background-color: #fde68a;
        }
        .step-indicator {
            transition: all 0.3s ease;
        }
        .step-indicator.active {
            background-color: #f59e0b;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);
        }
        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
            background-color: #fef3c7;
        }
        .progress-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            transition: width 0.5s ease;
        }
        .success-message {
            animation: slideDown 0.5s ease-out;
        }
        .error-message {
            animation: slideDown 0.5s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Loading Bar -->
    <div class="loading-bar fixed top-0 left-0 z-50"></div>

    <!-- Mobile Menu (Copy from create_package.php) -->
    <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden hidden">
        <!-- Mobile menu content same as create_package.php -->
        <!-- For brevity, using same structure as create_package.php -->
    </div>

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="marketing_campaigns.php" class="flex items-center gap-3 group">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
                            <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain bg-white p-2">
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-black text-xl tracking-tight text-gray-900">
                                TravelEase
                            </span>
                            <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                                Marketing Dashboard
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
                    <a href="marketing_dashboard.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
                        <i class="fas fa-chart-line text-xs text-amber-500 mr-2"></i>
                        Overview
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="marketing_campaigns.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
                        <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
                        Packages
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="create_package.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
                        <i class="fas fa-plus text-xs text-amber-500 mr-2"></i>
                        Create Package
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
                    </a>
                </div>

                <!-- Profile Section -->
                <div class="hidden lg:flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
                            <div class="text-xs text-gray-600">Marketing Manager</div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-amber-50 transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="pt-24 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-black mb-2">
                    <span class="text-gradient">Edit Package</span>
                </h1>
                <p class="text-lg text-gray-700">Update package details for "<?= htmlspecialchars($package['package_name'] ?? 'Package') ?>"</p>
                
                <!-- Success/Error Messages -->
                <?php if ($successMsg): ?>
                <div class="success-message mt-4 p-4 bg-green-50 border border-green-200 rounded-2xl text-green-700">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span><?= htmlspecialchars($successMsg) ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($errorMsg): ?>
                <div class="error-message mt-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?= htmlspecialchars($errorMsg) ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator h-10 w-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-semibold mb-2 active">1</div>
                            <span class="text-sm font-medium text-gray-900">Basic Info</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator h-10 w-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">2</div>
                            <span class="text-sm font-medium text-gray-700">Details</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator h-10 w-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">3</div>
                            <span class="text-sm font-medium text-gray-700">Pricing</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator h-10 w-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">4</div>
                            <span class="text-sm font-medium text-gray-700">Media</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator h-10 w-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">5</div>
                            <span class="text-sm font-medium text-gray-700">Review</span>
                        </div>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 20%"></div>
                </div>
            </div>

            <!-- Package Edit Form -->
            <div class="glass-effect rounded-2xl border border-amber-100 shadow-lg">
                <form id="packageForm" action="update_package.php" method="POST" class="space-y-0" enctype="multipart/form-data">
                    <input type="hidden" name="package_id" value="<?= $packageId ?>">
                    
                    <!-- Step 1: Basic Information -->
                    <div id="step1" class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">1. Basic Package Information</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                                <input type="text" name="package_name" value="<?= htmlspecialchars($package['package_name'] ?? '') ?>" placeholder="e.g., Bali Wellness Retreat 2024" 
                                       class="w-full p-4 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Category *</label>
                                    <select name="category" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                        <option value="">Select category</option>
                                        <?php foreach ($packageCategories as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= ($package['category'] ?? '') === $value ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination Region *</label>
                                    <select name="region" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                        <option value="">Select region</option>
                                        <?php foreach ($destinations as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= ($package['region'] ?? '') === $value ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                                <select name="country" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                    <option value="">Select country</option>
                                    <?php foreach ($countries as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= ($package['country'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                                <textarea name="short_description" rows="3" placeholder="Brief overview of the package (appears in listings)" 
                                          class="w-full p-4 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" 
                                          maxlength="200" required><?= htmlspecialchars($package['short_description'] ?? '') ?></textarea>
                                <div class="text-right text-sm text-gray-500 mt-1">
                                    <span id="charCount"><?= strlen($package['short_description'] ?? '') ?></span>/200 characters
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" id="nextStep1" 
                                    class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all text-lg">
                                Next: Package Details <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Package Details -->
                    <div id="step2" class="p-8 hidden">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">2. Package Details & Itinerary</h3>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) *</label>
                                    <input type="number" name="duration_days" min="1" max="30" value="<?= $package['duration_days'] ?? 7 ?>" 
                                           class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                                    <select name="difficulty_level" class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                        <option value="">Select level</option>
                                        <?php foreach ($difficultyLevels as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= ($package['difficulty_level'] ?? '') === $value ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Accommodation Type *</label>
                                <select name="accommodation_type" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                    <option value="">Select accommodation type</option>
                                    <?php foreach ($accommodationTypes as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= ($package['accommodation_type'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Inclusions</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <?php foreach ($allInclusions as $value => $label): ?>
                                    <label class="flex items-center p-3 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 cursor-pointer">
                                        <input type="checkbox" name="inclusions[]" value="<?= htmlspecialchars($value) ?>" 
                                               class="rounded text-amber-600 focus:ring-amber-500 inclusion-checkbox"
                                               <?= in_array($value, $inclusions) ? 'checked' : '' ?>>
                                        <span class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($label) ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Description</label>
                                <div id="description-editor" class="rounded-xl border border-amber-200 bg-white">
                                    <div id="editor"></div>
                                    <textarea name="detailed_description" id="detailedDescription" class="hidden"><?= htmlspecialchars($package['detailed_description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Itinerary Highlights</label>
                                <div id="highlightsContainer" class="space-y-3">
                                    <?php if (!empty($highlights)): ?>
                                        <?php foreach ($highlights as $highlight): ?>
                                        <div class="flex items-center gap-3 highlight-item">
                                            <input type="hidden" name="highlight_ids[]" value="<?= $highlight['id'] ?>">
                                            <input type="text" name="highlights[]" placeholder="Day 1: Arrival & Welcome Dinner" 
                                                   value="<?= htmlspecialchars($highlight['title']) ?>"
                                                   class="flex-1 p-3 rounded-lg border border-amber-200 bg-white">
                                            <button type="button" class="remove-highlight p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="flex items-center gap-3 highlight-item">
                                            <input type="text" name="highlights[]" placeholder="Day 1: Arrival & Welcome Dinner" 
                                                   class="flex-1 p-3 rounded-lg border border-amber-200 bg-white">
                                            <button type="button" class="remove-highlight p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" id="addHighlightBtn" 
                                        class="mt-3 px-4 py-2 rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50">
                                    <i class="fas fa-plus mr-2"></i> Add Highlight
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" id="prevStep2" 
                                    class="px-6 py-3 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="button" id="nextStep2" 
                                    class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                                Next: Pricing & Availability <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Pricing & Availability -->
                    <div id="step3" class="p-8 hidden">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">3. Pricing & Availability</h3>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Base Price (per person) *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" name="base_price" min="0" step="10" value="<?= $package['base_price'] ?? '' ?>" placeholder="1999" 
                                               class="w-full pl-10 p-4 rounded-xl border border-amber-200 bg-white" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Single Supplement</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" name="single_supplement" min="0" step="10" value="<?= $package['single_supplement'] ?? '' ?>" placeholder="500" 
                                               class="w-full pl-10 p-4 rounded-xl border border-amber-200 bg-white">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Size (Min)</label>
                                    <input type="number" name="group_min" min="1" value="<?= $package['group_min'] ?? 2 ?>" 
                                           class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Size (Max)</label>
                                    <input type="number" name="group_max" min="1" value="<?= $package['group_max'] ?? 12 ?>" 
                                           class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Availability Schedule</label>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 mb-1">Start Date</label>
                                            <input type="date" name="availability_start" value="<?= $package['availability_start'] ?? '' ?>"
                                                   class="w-full p-3 rounded-lg border border-amber-200 bg-white">
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 mb-1">End Date</label>
                                            <input type="date" name="availability_end" value="<?= $package['availability_end'] ?? '' ?>"
                                                   class="w-full p-3 rounded-lg border border-amber-200 bg-white">
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_year_round" id="yearRound" class="rounded text-amber-600" value="1" <?= ($package['is_year_round'] ?? false) ? 'checked' : '' ?>>
                                        <label for="yearRound" class="ml-2 text-sm text-gray-700">Available year-round</label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Early Bird Discount</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <div class="flex items-center">
                                            <input type="number" name="early_bird_discount" min="0" max="100" value="<?= $package['early_bird_discount'] ?? '' ?>" placeholder="15" 
                                                   class="flex-1 p-3 rounded-lg border border-amber-200 bg-white">
                                            <span class="px-4 py-3 bg-amber-50 border border-amber-200 border-l-0 rounded-r-lg text-amber-700">%</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Discount for bookings made in advance</div>
                                    </div>
                                    <div>
                                        <input type="number" name="early_bird_days" min="0" value="<?= $package['early_bird_days'] ?? '' ?>" placeholder="60" 
                                               class="w-full p-3 rounded-lg border border-amber-200 bg-white">
                                        <div class="text-xs text-gray-500 mt-1">Days in advance</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" id="prevStep3" 
                                    class="px-6 py-3 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="button" id="nextStep3" 
                                    class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                                Next: Media & Images <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Media & Images -->
                    <div id="step4" class="p-8 hidden">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">4. Media & Visual Content</h3>
                        
                        <div class="space-y-6">
                            <!-- Cover Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                                <p class="text-sm text-gray-600 mb-4">Update the main image displayed for your package</p>
                                <?php if (!empty($package['cover_image'])): ?>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 mb-2">Current cover image:</p>
                                    <img src="<?= htmlspecialchars($package['cover_image']) ?>" alt="Current cover" class="w-full h-64 object-cover rounded-2xl mb-2">
                                    <label class="flex items-center mt-2">
                                        <input type="checkbox" name="remove_cover_image" value="1" class="mr-2">
                                        <span class="text-sm text-gray-600">Remove current cover image</span>
                                    </label>
                                </div>
                                <?php endif; ?>
                                <div id="coverImageUpload" class="image-upload-area rounded-2xl p-8 text-center cursor-pointer">
                                    <div class="h-20 w-20 rounded-xl gold-gradient mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-image text-white text-2xl"></i>
                                    </div>
                                    <p class="text-gray-700 mb-2">Click to upload new cover image</p>
                                    <p class="text-sm text-gray-500">or drag and drop (Recommended: 1200x800 pixels)</p>
                                    <input type="file" id="coverImageInput" name="cover_image" class="hidden" accept="image/*">
                                </div>
                                <div id="coverImagePreview" class="mt-4 hidden">
                                    <p class="text-sm text-gray-500 mb-2">New cover image preview:</p>
                                    <img id="coverImagePreviewImg" class="w-full h-64 object-cover rounded-2xl" src="" alt="Cover preview">
                                </div>
                            </div>

                            <!-- Video URL -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Video Tour (Optional)</label>
                                <input type="url" name="video_url" value="<?= htmlspecialchars($package['video_url'] ?? '') ?>" placeholder="https://youtube.com/watch?v=..." 
                                       class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                <div class="text-sm text-gray-500 mt-1">YouTube or Vimeo URL for package video tour</div>
                            </div>

                            <!-- Virtual Tour -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">360° Virtual Tour (Optional)</label>
                                <input type="url" name="virtual_tour_url" value="<?= htmlspecialchars($package['virtual_tour_url'] ?? '') ?>" placeholder="https://..." 
                                       class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                <div class="text-sm text-gray-500 mt-1">Link to 360° virtual tour experience</div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" id="prevStep4" 
                                    class="px-6 py-3 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="button" id="nextStep4" 
                                    class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                                Next: Review & Update <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Review & Update -->
                    <div id="step5" class="p-8 hidden">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">5. Review & Update Package</h3>
                        
                        <div class="space-y-6">
                            <!-- Package Preview -->
                            <div class="bg-amber-50 rounded-2xl p-6 mb-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="h-16 w-16 rounded-xl gold-gradient flex items-center justify-center">
                                        <i class="fas fa-suitcase-rolling text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 id="previewPackageName" class="text-xl font-bold text-gray-900">Package Preview</h4>
                                        <p class="text-sm text-gray-600">Review all changes before updating</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-sm text-gray-600">Package Name:</span>
                                            <div id="previewName" class="font-medium text-gray-900"><?= htmlspecialchars($package['package_name'] ?? '-') ?></div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Category:</span>
                                            <div id="previewCategory" class="font-medium text-gray-900">
                                                <?= htmlspecialchars($packageCategories[$package['category'] ?? ''] ?? '-') ?>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Destination:</span>
                                            <div id="previewDestination" class="font-medium text-gray-900">
                                                <?= htmlspecialchars($package['country'] ?? '') ?>, <?= htmlspecialchars($destinations[$package['region'] ?? ''] ?? '') ?>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Duration:</span>
                                            <div id="previewDuration" class="font-medium text-gray-900"><?= $package['duration_days'] ?? '-' ?> days</div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-sm text-gray-600">Price:</span>
                                            <div id="previewPrice" class="text-2xl font-bold text-amber-600">$<?= number_format($package['base_price'] ?? 0) ?></div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Accommodation:</span>
                                            <div id="previewAccommodation" class="font-medium text-gray-900">
                                                <?= htmlspecialchars($accommodationTypes[$package['accommodation_type'] ?? ''] ?? '-') ?>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Group Size:</span>
                                            <div id="previewGroupSize" class="font-medium text-gray-900">
                                                <?= $package['group_min'] ?? 2 ?>-<?= $package['group_max'] ?? 12 ?> people
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Difficulty:</span>
                                            <div id="previewDifficulty" class="font-medium text-gray-900">
                                                <?= htmlspecialchars($difficultyLevels[$package['difficulty_level'] ?? ''] ?? 'Not specified') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Settings -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Status</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="flex items-center p-4 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                                            <input type="radio" name="package_status" value="draft" class="text-amber-600" <?= ($package['package_status'] ?? 'draft') === 'draft' ? 'checked' : '' ?>>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Draft</div>
                                                <div class="text-xs text-gray-600">Save as draft</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                                            <input type="radio" name="package_status" value="published" class="text-amber-600" <?= ($package['package_status'] ?? '') === 'published' ? 'checked' : '' ?>>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Publish</div>
                                                <div class="text-xs text-gray-600">Make package public</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                                            <input type="radio" name="package_status" value="archived" class="text-amber-600" <?= ($package['package_status'] ?? '') === 'archived' ? 'checked' : '' ?>>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Archive</div>
                                                <div class="text-xs text-gray-600">Archive package</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center p-4 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                                            <input type="radio" name="visibility" value="public" class="text-amber-600" <?= ($package['visibility'] ?? 'public') === 'public' ? 'checked' : '' ?>>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Public</div>
                                                <div class="text-xs text-gray-600">Visible to all customers</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                                            <input type="radio" name="visibility" value="private" class="text-amber-600" <?= ($package['visibility'] ?? '') === 'private' ? 'checked' : '' ?>>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">Private</div>
                                                <div class="text-xs text-gray-600">Visible only to selected customers</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="notify_team" id="notifyTeam" class="rounded text-amber-600" value="1" <?= ($package['notify_team'] ?? true) ? 'checked' : '' ?>>
                                    <label for="notifyTeam" class="ml-2 text-sm text-gray-700">Notify team members when package is updated</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" id="prevStep5" 
                                    class="px-6 py-3 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="submit" id="updatePackageBtn" 
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold hover:shadow-lg transition-all">
                                <i class="fas fa-save mr-2"></i> Update Package
                            </button>
                        </div>
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
                            <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain">
                        </div>
                        <span class="font-black text-lg text-gray-900">TravelEase</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">
                        Create and manage amazing travel packages for luxury travelers worldwide.
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
                <p>© <?= $currentYear ?> TravelEase Package Manager. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Multi-step form functionality
        let currentStep = 1;
        let quill = null;

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor with existing content
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, 4, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            // Set existing content if available
            const detailedDesc = document.getElementById('detailedDescription').value;
            if (detailedDesc) {
                try {
                    quill.setContents(quill.clipboard.convert(detailedDesc));
                } catch (e) {
                    quill.setText(detailedDesc);
                }
            } else {
                // Set default content
                quill.setContents([
                    { insert: 'Package Overview\n', attributes: { header: 2 } },
                    { insert: '\nExperience the ultimate travel adventure with our carefully curated package.\n\n' },
                    { insert: 'What Makes This Package Special:\n', attributes: { bold: true } },
                    { insert: '\n• Expertly designed itinerary\n' },
                    { insert: '• Luxury accommodation\n' },
                    { insert: '• Local expert guides\n' },
                    { insert: '• Unique cultural experiences\n' },
                    { insert: '• Personalized service\n\n' }
                ]);
            }

            // Update hidden textarea with Quill content
            quill.on('text-change', function() {
                document.getElementById('detailedDescription').value = quill.root.innerHTML;
            });

            // Character counter for short description
            const shortDesc = document.querySelector('textarea[name="short_description"]');
            const charCount = document.getElementById('charCount');
            
            shortDesc.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });

            // Image upload handling
            setupImageUpload('coverImageUpload', 'coverImageInput', 'coverImagePreview', 'coverImagePreviewImg');

            // Add event listeners for step navigation
            setupStepNavigation();
            
            // Add event listener for highlight buttons
            setupHighlightButtons();
            
            // Update progress bar
            updateProgressBar();
            
            // Setup form preview updates
            setupFormPreview();
        });

        function setupStepNavigation() {
            // Step 1 to 2
            document.getElementById('nextStep1').addEventListener('click', function() {
                if (validateStep(1)) {
                    nextStep();
                }
            });

            // Step 2 navigation
            document.getElementById('prevStep2').addEventListener('click', prevStep);
            document.getElementById('nextStep2').addEventListener('click', function() {
                if (validateStep(2)) {
                    nextStep();
                }
            });

            // Step 3 navigation
            document.getElementById('prevStep3').addEventListener('click', prevStep);
            document.getElementById('nextStep3').addEventListener('click', function() {
                if (validateStep(3)) {
                    nextStep();
                }
            });

            // Step 4 navigation
            document.getElementById('prevStep4').addEventListener('click', prevStep);
            document.getElementById('nextStep4').addEventListener('click', function() {
                if (validateStep(4)) {
                    nextStep();
                }
            });

            // Step 5 navigation
            document.getElementById('prevStep5').addEventListener('click', prevStep);
        }

        function setupHighlightButtons() {
            document.getElementById('addHighlightBtn').addEventListener('click', addHighlight);
            
            // Event delegation for remove highlight buttons
            document.getElementById('highlightsContainer').addEventListener('click', function(e) {
                if (e.target.closest('.remove-highlight')) {
                    removeHighlight(e.target.closest('.remove-highlight'));
                }
            });
        }

        function setupImageUpload(dropAreaId, inputId, previewId, imgId) {
            const dropArea = document.getElementById(dropAreaId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropArea.classList.add('dragover');
            }

            function unhighlight() {
                dropArea.classList.remove('dragover');
            }

            dropArea.addEventListener('click', function() {
                input.click();
            });

            dropArea.addEventListener('drop', handleDrop, false);
            input.addEventListener('change', handleFiles, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles({ target: { files } });
            }

            function handleFiles(e) {
                const files = e.target.files;
                if (files && files[0]) {
                    handleSingleFile(files[0]);
                }
            }

            function handleSingleFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imgId) {
                        document.getElementById(imgId).src = e.target.result;
                    }
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function updateProgressBar() {
            const progressFill = document.querySelector('.progress-fill');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            
            // Update progress bar width
            progressFill.style.width = `${(currentStep / 5) * 100}%`;
            
            // Update step indicators
            stepIndicators.forEach((indicator, index) => {
                const stepNumber = index + 1;
                indicator.classList.remove('active', 'completed');
                
                if (stepNumber < currentStep) {
                    indicator.classList.add('completed');
                } else if (stepNumber === currentStep) {
                    indicator.classList.add('active');
                }
            });
        }

        function nextStep() {
            if (currentStep < 5) {
                document.getElementById(`step${currentStep}`).classList.add('hidden');
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.remove('hidden');
                updateProgressBar();
                
                if (currentStep === 5) {
                    updatePreview();
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`step${currentStep}`).classList.add('hidden');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.remove('hidden');
                updateProgressBar();
            }
        }

        function validateStep(step) {
            switch(step) {
                case 1:
                    const packageName = document.querySelector('input[name="package_name"]').value;
                    const category = document.querySelector('select[name="category"]').value;
                    const country = document.querySelector('select[name="country"]').value;
                    const shortDesc = document.querySelector('textarea[name="short_description"]').value;
                    
                    if (!packageName) {
                        alert('Please enter a package name.');
                        document.querySelector('input[name="package_name"]').focus();
                        return false;
                    }
                    
                    if (!category) {
                        alert('Please select a package category.');
                        document.querySelector('select[name="category"]').focus();
                        return false;
                    }
                    
                    if (!country) {
                        alert('Please select a country.');
                        document.querySelector('select[name="country"]').focus();
                        return false;
                    }
                    
                    if (!shortDesc || shortDesc.length < 50) {
                        alert('Please enter a short description (minimum 50 characters).');
                        document.querySelector('textarea[name="short_description"]').focus();
                        return false;
                    }
                    
                    return true;
                
                case 2:
                    const duration = document.querySelector('input[name="duration_days"]').value;
                    const accommodation = document.querySelector('select[name="accommodation_type"]').value;
                    
                    if (!duration || duration < 1) {
                        alert('Please enter a valid duration (minimum 1 day).');
                        document.querySelector('input[name="duration_days"]').focus();
                        return false;
                    }
                    
                    if (!accommodation) {
                        alert('Please select an accommodation type.');
                        document.querySelector('select[name="accommodation_type"]').focus();
                        return false;
                    }
                    
                    return true;
                
                case 3:
                    const basePrice = document.querySelector('input[name="base_price"]').value;
                    
                    if (!basePrice || basePrice <= 0) {
                        alert('Please enter a valid base price.');
                        document.querySelector('input[name="base_price"]').focus();
                        return false;
                    }
                    
                    return true;
                
                default:
                    return true;
            }
        }

        function addHighlight() {
            const container = document.getElementById('highlightsContainer');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 highlight-item';
            div.innerHTML = `
                <input type="text" name="highlights[]" placeholder="Add itinerary highlight" 
                       class="flex-1 p-3 rounded-lg border border-amber-200 bg-white">
                <button type="button" class="remove-highlight p-2 text-red-500 hover:bg-red-50 rounded-lg">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function removeHighlight(button) {
            const container = document.getElementById('highlightsContainer');
            if (container.children.length > 1) {
                button.closest('.highlight-item').remove();
            } else {
                alert('At least one highlight is required.');
            }
        }

        function setupFormPreview() {
            // Update preview when form fields change
            const form = document.getElementById('packageForm');
            const previewFields = ['package_name', 'category', 'region', 'country', 'duration_days', 
                                  'accommodation_type', 'difficulty_level', 'base_price', 'group_min', 'group_max'];
            
            previewFields.forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('input', updatePreview);
                    field.addEventListener('change', updatePreview);
                }
            });
        }

        function updatePreview() {
            const form = document.getElementById('packageForm');
            
            // Package Information
            document.getElementById('previewName').textContent = form.querySelector('[name="package_name"]').value || '-';
            
            const categorySelect = form.querySelector('[name="category"]');
            const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text;
            document.getElementById('previewCategory').textContent = categoryText || '-';
            
            const countrySelect = form.querySelector('[name="country"]');
            const countryText = countrySelect.options[countrySelect.selectedIndex]?.text;
            const regionSelect = form.querySelector('[name="region"]');
            const regionText = regionSelect.options[regionSelect.selectedIndex]?.text;
            document.getElementById('previewDestination').textContent = `${countryText} (${regionText})` || '-';
            
            // Package Details
            const duration = form.querySelector('[name="duration_days"]').value;
            document.getElementById('previewDuration').textContent = duration ? `${duration} days` : '-';
            
            const accommodationSelect = form.querySelector('[name="accommodation_type"]');
            const accommodationText = accommodationSelect.options[accommodationSelect.selectedIndex]?.text;
            document.getElementById('previewAccommodation').textContent = accommodationText || '-';
            
            const difficultySelect = form.querySelector('[name="difficulty_level"]');
            const difficultyText = difficultySelect.options[difficultySelect.selectedIndex]?.text;
            document.getElementById('previewDifficulty').textContent = difficultyText || 'Not specified';
            
            // Pricing
            const basePrice = form.querySelector('[name="base_price"]').value;
            document.getElementById('previewPrice').textContent = basePrice ? `$${parseInt(basePrice).toLocaleString()}` : '-';
            
            // Group Size
            const minGroup = form.querySelector('[name="group_min"]').value || '2';
            const maxGroup = form.querySelector('[name="group_max"]').value || '12';
            document.getElementById('previewGroupSize').textContent = `${minGroup}-${maxGroup} people`;
        }

        // Mobile menu functionality (same as before)
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        
        let isMenuOpen = false;

        if (menuButton) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.add('open');
                }, 10);
                document.body.style.overflow = 'hidden';
                isMenuOpen = true;
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isMenuOpen = false;
            });
        }

        if (mobileMenuBackdrop) {
            mobileMenuBackdrop.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isMenuOpen = false;
            });
        }

        // Close menu on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isMenuOpen) {
                mobileMenu.classList.remove('open');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
                isMenuOpen = false;
            }
        });

        // Form submission handling
        document.getElementById('packageForm').addEventListener('submit', function(e) {
            // Update the detailed description textarea with Quill content
            document.getElementById('detailedDescription').value = quill.root.innerHTML;
            
            // Show loading state
            const updateBtn = document.getElementById('updatePackageBtn');
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
            updateBtn.disabled = true;
            
            // Allow form to submit
            return true;
        });
    </script>
</body>
</html>