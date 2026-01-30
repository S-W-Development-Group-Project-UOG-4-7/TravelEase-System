<?php
// view_package.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database connection
require_once __DIR__ . '/db.php';

$id = $_GET['id'] ?? 0;
$package = null;

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM travel_packages WHERE id = ?");
        $stmt->execute([$id]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$package) {
            header('Location: marketing_campaigns.php');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
} else {
    header('Location: marketing_campaigns.php');
    exit();
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Categories for display
$categories = [
    'luxury' => 'Luxury',
    'adventure' => 'Adventure',
    'wellness' => 'Wellness',
    'family' => 'Family',
    'cultural' => 'Cultural',
    'beach' => 'Beach',
    'honeymoon' => 'Honeymoon',
    'business' => 'Business'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Package | TravelEase Marketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        .text-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="marketing_campaigns.php" class="flex items-center gap-3 group">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200">
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

                <div class="flex items-center gap-4">
                    <a href="marketing_campaigns.php" class="text-sm font-semibold text-amber-600 hover:text-amber-700">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Packages
                    </a>
                    <div class="flex items-center gap-3">
                        <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="pt-24 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-black mb-2">
                            <span class="text-gradient"><?= htmlspecialchars($package['package_name']) ?></span>
                        </h1>
                        <p class="text-lg text-gray-700">Package Details and Information</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center gap-4">
                        <a href="edit_package.php?id=<?= $id ?>" class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all border border-amber-200">
                            <i class="fas fa-edit mr-2"></i> Edit Package
                        </a>
                        <a href="marketing_campaigns.php" class="inline-flex items-center text-sm font-medium px-5 py-2.5 rounded-xl bg-white text-gray-700 hover:bg-gray-50 transition-all border border-gray-200">
                            <i class="fas fa-list mr-2"></i> All Packages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Package Overview -->
            <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-lg mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Package Details -->
                    <div class="lg:col-span-2">
                        <!-- Cover Image -->
                        <?php if (!empty($package['cover_image'])): ?>
                            <div class="mb-6">
                                <img src="<?= htmlspecialchars($package['cover_image']) ?>" 
                                     alt="<?= htmlspecialchars($package['package_name']) ?>" 
                                     class="w-full h-64 object-cover rounded-xl border border-amber-200 shadow-lg">
                            </div>
                        <?php endif; ?>

                        <!-- Package Details -->
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Package Description</h2>
                                <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($package['short_description'])) ?></p>
                                <?php if (!empty($package['detailed_description'])): ?>
                                    <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                        <h3 class="font-semibold text-gray-900 mb-2">Detailed Itinerary</h3>
                                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($package['detailed_description'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Inclusions -->
                            <?php if (!empty($package['inclusions'])): ?>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-4">What's Included</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php 
                                        $inclusions = explode(',', $package['inclusions']);
                                        foreach ($inclusions as $inclusion):
                                            $inclusion = trim($inclusion);
                                            if (!empty($inclusion)):
                                        ?>
                                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-amber-100">
                                            <div class="h-8 w-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                                <i class="fas fa-check text-amber-600 text-sm"></i>
                                            </div>
                                            <span class="text-gray-700"><?= htmlspecialchars($inclusion) ?></span>
                                        </div>
                                        <?php endif; endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Column: Quick Info -->
                    <div>
                        <div class="sticky top-24">
                            <div class="bg-white rounded-xl border border-amber-200 p-6 shadow-lg">
                                <!-- Package Info -->
                                <div class="space-y-4">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg mb-4">Package Information</h3>
                                        
                                        <!-- Price -->
                                        <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200">
                                            <div class="text-sm text-gray-600 mb-1">Package Price</div>
                                            <div class="text-3xl font-bold text-amber-600">$<?= number_format($package['base_price'], 2) ?></div>
                                            <?php if ($package['early_bird_discount'] > 0): ?>
                                                <div class="text-sm text-green-600 mt-2">
                                                    <i class="fas fa-percentage mr-1"></i>
                                                    <?= $package['early_bird_discount'] ?>% early bird discount available
                                                    <?php if ($package['early_bird_days'] > 0): ?>
                                                        (<?= $package['early_bird_days'] ?> days before departure)
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Details List -->
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Category:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= $categories[$package['category']] ?? ucfirst($package['category']) ?>
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Duration:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= $package['duration_days'] ?> days
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Destination:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= htmlspecialchars($package['country']) ?>
                                                    <?php if (!empty($package['region'])): ?>
                                                        , <?= htmlspecialchars($package['region']) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            
                                            <?php if (!empty($package['difficulty_level'])): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Difficulty:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= ucfirst($package['difficulty_level']) ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($package['accommodation_type'])): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Accommodation:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= ucfirst($package['accommodation_type']) ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($package['group_min'] || $package['group_max']): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Group Size:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?php if ($package['group_min'] && $package['group_max']): ?>
                                                        <?= $package['group_min'] ?>-<?= $package['group_max'] ?> people
                                                    <?php elseif ($package['group_min']): ?>
                                                        Min <?= $package['group_min'] ?> people
                                                    <?php elseif ($package['group_max']): ?>
                                                        Max <?= $package['group_max'] ?> people
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($package['availability_start'] && $package['availability_end']): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                                <span class="text-gray-600">Availability:</span>
                                                <span class="font-medium text-gray-900">
                                                    <?= date('M d, Y', strtotime($package['availability_start'])) ?> 
                                                    to 
                                                    <?= date('M d, Y', strtotime($package['availability_end'])) ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Media Links -->
                                    <?php if (!empty($package['video_url']) || !empty($package['virtual_tour_url'])): ?>
                                    <div class="pt-4 border-t border-gray-100">
                                        <h4 class="font-semibold text-gray-900 mb-3">Media Links</h4>
                                        <div class="space-y-2">
                                            <?php if (!empty($package['video_url'])): ?>
                                            <a href="<?= htmlspecialchars($package['video_url']) ?>" 
                                               target="_blank"
                                               class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                                <i class="fas fa-video text-blue-600"></i>
                                                <span class="text-sm">Watch Video</span>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($package['virtual_tour_url'])): ?>
                                            <a href="<?= htmlspecialchars($package['virtual_tour_url']) ?>" 
                                               target="_blank"
                                               class="flex items-center gap-3 p-3 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                                <i class="fas fa-street-view text-green-600"></i>
                                                <span class="text-sm">Virtual Tour</span>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Package Meta -->
                                    <div class="pt-4 border-t border-gray-100">
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span>Created: <?= date('M d, Y', strtotime($package['created_at'])) ?></span>
                                            <span>ID: <?= $package['id'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            <?php if (!empty($package['gallery_images'])): 
                $galleryImages = explode(',', $package['gallery_images']);
                $galleryImages = array_filter(array_map('trim', $galleryImages));
                
                if (!empty($galleryImages)):
            ?>
                <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow-lg mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Gallery</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($galleryImages as $imageUrl): ?>
                            <div class="overflow-hidden rounded-xl border border-amber-200 group">
                                <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                     alt="Gallery Image" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-amber-100 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-gray-600">
                <p>© <?= $currentYear ?> TravelEase Marketing Dashboard. Package ID: <?= $id ?></p>
            </div>
        </div>
    </footer>
</body>
</html>