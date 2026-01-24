<?php
// edit_package.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

require_once __DIR__ . '/db.php';

$managerName  = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear  = date('Y');

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// -------------------- DROPDOWN OPTIONS --------------------
$packageCategories = [
    'adventure' => 'Adventure',
    'wellness' => 'Wellness & Spa',
    'cultural' => 'Cultural',
    'luxury' => 'Luxury',
    'family' => 'Family',
    'honeymoon' => 'Honeymoon',
    'cruise' => 'Cruise',
    'eco' => 'Eco-Tourism'
];

$destinations = [
    'asia' => 'Asia',
    'europe' => 'Europe',
    'north_america' => 'North America',
    'south_america' => 'South America',
    'africa' => 'Africa',
    'oceania' => 'Oceania',
    'middle_east' => 'Middle East',
    'caribbean' => 'Caribbean'
];

$countries = [
    'indonesia' => 'Indonesia',
    'thailand' => 'Thailand',
    'japan' => 'Japan',
    'france' => 'France',
    'italy' => 'Italy',
    'usa' => 'United States',
    'canada' => 'Canada',
    'australia' => 'Australia',
    'new_zealand' => 'New Zealand',
    'south_africa' => 'South Africa',
    'egypt' => 'Egypt',
    'uae' => 'United Arab Emirates'
];

$difficultyLevels = [
    'easy' => 'Easy',
    'moderate' => 'Moderate',
    'challenging' => 'Challenging',
    'difficult' => 'Difficult'
];

$accommodationTypes = [
    'hotel' => 'Hotel',
    'resort' => 'Resort',
    'villa' => 'Private Villa',
    'camp' => 'Camp/Lodge',
    'cruise_ship' => 'Cruise Ship',
    'hostel' => 'Hostel',
    'apartment' => 'Apartment',
    'homestay' => 'Homestay'
];

$inclusions = [
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

// -------------------- HELPERS ------------------
function pg_text_array_literal(array $arr): string {
    $items = [];
    foreach ($arr as $v) {
        $v = (string)$v;
        $v = str_replace(['\\', '"'], ['\\\\', '\\"'], $v);
        $items[] = '"' . $v . '"';
    }
    return '{' . implode(',', $items) . '}';
}

function pg_array_to_php($pgArray): array {
    if (empty($pgArray) || $pgArray[0] !== '{') return [];
    
    // Remove the curly braces
    $str = substr($pgArray, 1, -1);
    
    // Handle empty array
    if ($str === '') return [];
    
    // Split by commas, but respect quoted strings
    $result = [];
    $inQuotes = false;
    $current = '';
    
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        
        if ($char === '"' && ($i === 0 || $str[$i-1] !== '\\')) {
            $inQuotes = !$inQuotes;
        } elseif ($char === ',' && !$inQuotes) {
            $result[] = stripslashes($current);
            $current = '';
        } else {
            $current .= $char;
        }
    }
    
    if ($current !== '') {
        $result[] = stripslashes($current);
    }
    
    return $result;
}

function ensure_upload_dir(string $dir): void {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

function save_uploaded_file(array $file, string $uploadDir, array $allowedExt = ['jpg','jpeg','png','webp']): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException("Invalid image type.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (!in_array($finfo->file($file['tmp_name']), ['image/jpeg','image/png','image/webp'], true)) {
        throw new RuntimeException("Invalid image MIME.");
    }

    ensure_upload_dir($uploadDir);

    $name = bin2hex(random_bytes(12)) . '.' . $ext;
    $path = rtrim($uploadDir, '/') . '/' . $name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        throw new RuntimeException("File upload failed.");
    }

    return 'uploads/packages/' . $name;
}

function save_multiple_uploaded_files(array $files, string $uploadDir): array {
    $saved = [];
    if (!isset($files['name']) || !is_array($files['name'])) return $saved;

    for ($i = 0; $i < count($files['name']); $i++) {
        if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;

        $fileInfo = [
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i] ?? '',
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i] ?? 0
        ];
        
        try {
            $result = save_uploaded_file($fileInfo, $uploadDir);
            if ($result) {
                $saved[] = $result;
            }
        } catch (RuntimeException $e) {
            // Skip invalid files but continue with others
            continue;
        }
    }
    return array_values(array_filter($saved));
}

// -------------------- GET PACKAGE DATA --------------------
$packageId = $_GET['id'] ?? null;
$package = null;
$existingImages = ['cover_image' => null, 'gallery_images' => []];

if (!$packageId) {
    header('Location: marketing_campaigns.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM travel_packages WHERE id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$package) {
        header('Location: marketing_campaigns.php');
        exit;
    }
    
    // Convert PostgreSQL arrays to PHP arrays
    $package['inclusions'] = !empty($package['inclusions']) ? pg_array_to_php($package['inclusions']) : [];
    $package['gallery_images'] = !empty($package['gallery_images']) ? pg_array_to_php($package['gallery_images']) : [];
    
    $existingImages['cover_image'] = $package['cover_image'] ?? null;
    $existingImages['gallery_images'] = $package['gallery_images'] ?? [];
    
} catch (PDOException $e) {
    die("Error loading package: " . $e->getMessage());
}

// -------------------- FORM DEFAULTS --------------------
$form = [
    'package_name' => $package['package_name'] ?? '',
    'category' => $package['category'] ?? '',
    'region' => $package['region'] ?? '',
    'country' => $package['country'] ?? '',
    'short_description' => $package['short_description'] ?? '',
    'detailed_description' => $package['detailed_description'] ?? '',
    'duration_days' => $package['duration_days'] ?? 7,
    'difficulty_level' => $package['difficulty_level'] ?? null,
    'accommodation_type' => $package['accommodation_type'] ?? '',
    'inclusions' => $package['inclusions'] ?? [],
    'base_price' => $package['base_price'] ?? '',
    'group_min' => $package['group_min'] ?? null,
    'group_max' => $package['group_max'] ?? null,
    'availability_start' => $package['availability_start'] ?? null,
    'availability_end' => $package['availability_end'] ?? null,
    'early_bird_discount' => $package['early_bird_discount'] ?? null,
    'early_bird_days' => $package['early_bird_days'] ?? null,
    'video_url' => $package['video_url'] ?? null,
    'virtual_tour_url' => $package['virtual_tour_url'] ?? null,
];

$successMessage = '';
$errorMessage   = '';

// -------------------- HANDLE POST (UPDATE) --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $form['package_name'] = trim($_POST['package_name'] ?? '');
    $form['category'] = trim($_POST['category'] ?? '');
    $form['region'] = trim($_POST['region'] ?? '');
    $form['country'] = trim($_POST['country'] ?? '');
    $form['short_description'] = trim($_POST['short_description'] ?? '');
    $form['detailed_description'] = trim($_POST['detailed_description'] ?? '') ?: null;
    $form['duration_days'] = (int)($_POST['duration_days'] ?? 0);
    $form['difficulty_level'] = trim($_POST['difficulty_level'] ?? '') ?: null;
    $form['accommodation_type'] = trim($_POST['accommodation_type'] ?? '');
    $form['inclusions'] = is_array($_POST['inclusions'] ?? null) ? $_POST['inclusions'] : [];
    $form['base_price'] = $_POST['base_price'] ?? '';
    $form['group_min'] = ($_POST['group_min'] ?? '') !== '' ? (int)$_POST['group_min'] : null;
    $form['group_max'] = ($_POST['group_max'] ?? '') !== '' ? (int)$_POST['group_max'] : null;
    $form['availability_start'] = trim($_POST['availability_start'] ?? '') ?: null;
    $form['availability_end'] = trim($_POST['availability_end'] ?? '') ?: null;
    $form['early_bird_discount'] = ($_POST['early_bird_discount'] ?? '') !== '' ? (int)$_POST['early_bird_discount'] : null;
    $form['early_bird_days'] = ($_POST['early_bird_days'] ?? '') !== '' ? (int)$_POST['early_bird_days'] : null;
    $form['video_url'] = trim($_POST['video_url'] ?? '') ?: null;
    $form['virtual_tour_url'] = trim($_POST['virtual_tour_url'] ?? '') ?: null;

    // Handle image deletions
    $keepCoverImage = isset($_POST['keep_cover_image']) && $_POST['keep_cover_image'] === '1';
    $keepGalleryImages = isset($_POST['keep_gallery_images']) ? explode(',', $_POST['keep_gallery_images']) : [];

    // -------------------- VALIDATION --------------------
    $errors = [];
    if ($form['package_name'] === '') $errors[] = "Package name required.";
    if ($form['category'] === '') $errors[] = "Category required.";
    if ($form['region'] === '') $errors[] = "Region required.";
    if ($form['country'] === '') $errors[] = "Country required.";
    if ($form['short_description'] === '') $errors[] = "Short description required.";
    if ($form['duration_days'] < 1) $errors[] = "Duration must be at least 1 day.";
    if (!is_numeric($form['base_price']) || $form['base_price'] <= 0) $errors[] = "Invalid base price.";

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $uploadDir = __DIR__ . '/uploads/packages';
            
            // Handle cover image
            $coverPath = $existingImages['cover_image'];
            if (!$keepCoverImage && !empty($_FILES['cover_image']['name'])) {
                // New cover image uploaded
                $coverPath = save_uploaded_file($_FILES['cover_image'], $uploadDir);
            } elseif (!$keepCoverImage) {
                // Remove cover image
                $coverPath = null;
            }
            
            // Handle gallery images
            $gallery = $existingImages['gallery_images'];
            
            // Remove deleted images
            $gallery = array_filter($gallery, function($image) use ($keepGalleryImages) {
                return in_array($image, $keepGalleryImages);
            });
            
            // Add new gallery images
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $newGalleryImages = save_multiple_uploaded_files($_FILES['gallery_images'], $uploadDir);
                $gallery = array_merge($gallery, $newGalleryImages);
            }
            
            $gallery = array_values(array_unique($gallery));

            // Update package in database
           $stmt = $pdo->prepare("
    UPDATE travel_packages SET
        package_name = :package_name,
        category = :category,
        region = :region,
        country = :country,
        short_description = :short_description,
        detailed_description = :detailed_description,
        duration_days = :duration_days,
        difficulty_level = :difficulty_level,
        accommodation_type = :accommodation_type,
        inclusions = :inclusions,
        base_price = :base_price,
        group_min = :group_min,
        group_max = :group_max,
        availability_start = :availability_start,
        availability_end = :availability_end,
        early_bird_discount = :early_bird_discount,
        early_bird_days = :early_bird_days,
        video_url = :video_url,
        virtual_tour_url = :virtual_tour_url,
        cover_image = :cover_image,
        gallery_images = :gallery_images
    WHERE id = :id
");

            $stmt->execute([
                ':id' => $packageId,
                ':package_name' => $form['package_name'],
                ':category' => $form['category'],
                ':region' => $form['region'],
                ':country' => $form['country'],
                ':short_description' => $form['short_description'],
                ':detailed_description' => $form['detailed_description'],
                ':duration_days' => $form['duration_days'],
                ':difficulty_level' => $form['difficulty_level'],
                ':accommodation_type' => $form['accommodation_type'],
                ':inclusions' => $form['inclusions'] ? pg_text_array_literal($form['inclusions']) : null,
                ':base_price' => (float)$form['base_price'],
                ':group_min' => $form['group_min'],
                ':group_max' => $form['group_max'],
                ':availability_start' => $form['availability_start'],
                ':availability_end' => $form['availability_end'],
                ':early_bird_discount' => $form['early_bird_discount'],
                ':early_bird_days' => $form['early_bird_days'],
                ':video_url' => $form['video_url'],
                ':virtual_tour_url' => $form['virtual_tour_url'],
                ':cover_image' => $coverPath,
                ':gallery_images' => $gallery ? pg_text_array_literal($gallery) : null,
            ]);

            $pdo->commit();

            $successMessage = "✅ Package updated successfully!";
            
            // Refresh package data
            $stmt = $pdo->prepare("SELECT * FROM travel_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($package) {
                $package['inclusions'] = !empty($package['inclusions']) ? pg_array_to_php($package['inclusions']) : [];
                $package['gallery_images'] = !empty($package['gallery_images']) ? pg_array_to_php($package['gallery_images']) : [];
                
                $existingImages['cover_image'] = $package['cover_image'] ?? null;
                $existingImages['gallery_images'] = $package['gallery_images'] ?? [];
            }

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $errorMessage = "❌ Failed to update package: " . h($e->getMessage());
        }
    } else {
        $errorMessage = "❌ " . implode("<br>• ", array_map('h', $errors));
    }
}

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
        #description-editor .ql-toolbar {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #fde68a;
        }
        #description-editor .ql-container {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #fde68a;
            min-height: 200px;
        }
        .progress-bar {
            height: 6px;
            background: rgba(245, 158, 11, 0.2);
            border-radius: 9999px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 9999px;
            transition: width 0.3s ease;
        }
        .image-preview {
            transition: all 0.3s ease;
        }
        .image-preview:hover {
            transform: scale(1.05);
        }
        .delete-image-btn {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .image-container:hover .delete-image-btn {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen">

<div class="loading-bar fixed top-0 left-0 z-50"></div>

  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Updated Mobile Menu Logo -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl overflow-hidden">
              <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain">
            </div>
            <span class="font-black text-xl text-gray-900">TravelEase</span>
          </div>
          <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <nav class="space-y-4">
          <a href="marketing_dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
          <a href="marketing_campaigns.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-bullhorn w-6 text-center"></i>
            Packages
          </a>
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
          </a>
          <a href="partnership.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-handshake w-6 text-center"></i>
            Partnerships
          </a>
          <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-user-check w-6 text-center"></i>
            Feedback
          </a>
          <a href="marketing_profile.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-user w-6 text-center"></i>
            My Profile
          </a>
        </nav>

        <div class="mt-8 pt-8 border-t border-amber-100">
          <div class="flex items-center gap-3 mb-4">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
            <div>
              <div class="font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
              <div class="text-sm text-gray-600">Marketing Manager</div>
            </div>
          </div>
          <a href="login.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-amber-50 transition-all">
            <i class="fas fa-sign-out-alt text-amber-500"></i>
            <span>Logout</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Updated Main Header Logo -->
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

        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
          <a href="marketing_dashboard.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-chart-line text-xs text-amber-500"></i>
              Overview
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>

          <a href="marketing_campaigns.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
            Packages
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
          </a>
          <a href="marketing_report.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-alt text-xs text-amber-500 mr-2"></i>
            Reports
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="partnership.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-handshake text-xs text-amber-500 mr-2"></i>
            Partnerships
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_feedback.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Feedback
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>

        <div class="hidden lg:flex items-center gap-4">
          <div class="flex items-center gap-3">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover border-2 border-amber-500">
            <div class="text-right">
              <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($managerName) ?></div>
              <div class="text-xs text-gray-600">Marketing Manager</div>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <a href="marketing_profile.php" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-colors" title="My Profile">
              <i class="fas fa-user"></i>
            </a>
            <a href="login.php" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-colors" title="Logout">
              <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </div>

        <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-amber-50 transition-colors">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>
    </nav>
  </header>

<main class="pt-24 pb-12">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="mb-8 text-center">
      <h1 class="text-3xl sm:text-4xl font-black mb-2">
        Edit <span class="text-gradient">Travel Package</span>
      </h1>
      <p class="text-lg text-gray-700">
        Update package details and save changes to the database
      </p>

      <!-- Package ID and Back Button -->
      <div class="mt-6 flex items-center justify-center gap-4">
        <a href="marketing_campaigns.php" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-amber-200 bg-white text-gray-700 hover:bg-amber-50 transition-all">
          <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
        <div class="glass-effect rounded-2xl px-5 py-4 border border-amber-100 shadow">
          <div class="text-sm text-gray-500">Package ID</div>
          <div class="text-lg font-bold text-gray-900">#<?= h($packageId) ?></div>
        </div>
      </div>
    </div>

    <?php if ($successMessage): ?>
        <div class="mb-6 p-4 rounded-2xl border border-green-200 bg-green-50 text-green-800">
            <?= $successMessage ?>
            <div class="mt-2">
                <a href="marketing_campaigns.php" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                    <i class="fas fa-suitcase mr-2"></i>Back to Packages
                </a>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="mb-6 p-4 rounded-2xl border border-red-200 bg-red-50 text-red-800">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <!-- Progress / Steps -->
    <div class="mb-6 glass-effect rounded-2xl border border-amber-100 shadow-lg p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="font-bold text-gray-900">Editing Package: <?= h($form['package_name']) ?></div>
            <div class="text-sm text-gray-500"><span id="currentStepLabel">Step 1</span> of 5</div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 20%"></div>
        </div>
    </div>

    <!-- FORM (IMPORTANT: method POST + enctype for file upload) -->
    <div class="glass-effect rounded-2xl border border-amber-100 shadow-lg">
        <form id="packageForm" class="space-y-0" method="POST" enctype="multipart/form-data" action="">
            <!-- Hidden fields for image management -->
            <input type="hidden" name="keep_cover_image" id="keepCoverImage" value="1">
            <input type="hidden" name="keep_gallery_images" id="keepGalleryImages" value="<?= implode(',', $existingImages['gallery_images']) ?>">
            
            <!-- Step 1 -->
            <div id="step1" class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">1. Basic Package Information</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                        <input type="text" id="packageName" name="package_name" value="<?= h($form['package_name']) ?>"
                               placeholder="e.g., Bali Wellness Retreat 2024"
                               class="w-full p-4 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Package Category *</label>
                            <select id="packageCategory" name="category" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                <option value="">Select category</option>
                                <?php foreach ($packageCategories as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $form['category']===$value?'selected':''; ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Destination Region *</label>
                            <select id="destinationRegion" name="region" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                                <option value="">Select region</option>
                                <?php foreach ($destinations as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $form['region']===$value?'selected':''; ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <select id="packageCountry" name="country" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                            <option value="">Select country</option>
                            <?php foreach ($countries as $value => $label): ?>
                                <option value="<?= h($value) ?>" <?= $form['country']===$value?'selected':''; ?>><?= h($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                        <textarea id="shortDescription" name="short_description" rows="3"
                                  placeholder="Brief overview of the package (appears in listings)"
                                  class="w-full p-4 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                  maxlength="200" required><?= h($form['short_description']) ?></textarea>
                        <div class="text-right text-sm text-gray-500 mt-1">
                            <span id="charCount"><?= strlen($form['short_description']) ?></span>/200 characters
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

            <!-- Step 2 -->
            <div id="step2" class="p-8 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">2. Package Details & Itinerary</h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) *</label>
                            <input type="number" id="packageDuration" name="duration_days" min="1" max="30"
                                   value="<?= h($form['duration_days']) ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                            <select id="difficultyLevel" name="difficulty_level" class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                                <option value="">Select level</option>
                                <?php foreach ($difficultyLevels as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $form['difficulty_level']===$value?'selected':''; ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accommodation Type *</label>
                        <select id="accommodationType" name="accommodation_type" class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                            <option value="">Select accommodation type</option>
                            <?php foreach ($accommodationTypes as $value => $label): ?>
                                <option value="<?= h($value) ?>" <?= $form['accommodation_type']===$value?'selected':''; ?>><?= h($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Inclusions</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php foreach ($inclusions as $value => $label): ?>
                                <label class="flex items-center p-3 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 cursor-pointer">
                                    <input type="checkbox" name="inclusions[]" value="<?= h($value) ?>"
                                           class="rounded text-amber-600 focus:ring-amber-500 inclusion-checkbox"
                                           <?= in_array($value, $form['inclusions'], true) ? 'checked' : '' ?>>
                                    <span class="ml-2 text-sm text-gray-700"><?= h($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Description</label>
                        <div id="description-editor" class="rounded-xl border border-amber-200 bg-white">
                            <div id="editor"></div>
                        </div>
                        <input type="hidden" id="detailed_description" name="detailed_description" value="<?= h($form['detailed_description']) ?>">
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prevStep2"
                            class="px-6 py-3 rounded-xl border border-amber-200 bg-white text-gray-700 font-semibold hover:bg-amber-50 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button type="button" id="nextStep2"
                            class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                        Next: Pricing <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3 -->
            <div id="step3" class="p-8 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">3. Pricing & Group Size</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Base Price (USD) *</label>
                        <input type="number" step="0.01" min="0" id="basePrice" name="base_price"
                               value="<?= h($form['base_price']) ?>"
                               class="w-full p-4 rounded-xl border border-amber-200 bg-white" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Group Min</label>
                            <input type="number" min="1" id="groupSizeMin" name="group_min"
                                   value="<?= $form['group_min'] !== null ? h($form['group_min']) : '' ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Group Max</label>
                            <input type="number" min="1" id="groupSizeMax" name="group_max"
                                   value="<?= $form['group_max'] !== null ? h($form['group_max']) : '' ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Early Bird Discount (%)</label>
                            <input type="number" min="0" max="100" id="earlyBirdDiscount" name="early_bird_discount"
                                   value="<?= $form['early_bird_discount'] !== null ? h($form['early_bird_discount']) : '' ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Early Bird Days</label>
                            <input type="number" min="0" id="earlyBirdDays" name="early_bird_days"
                                   value="<?= $form['early_bird_days'] !== null ? h($form['early_bird_days']) : '' ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prevStep3"
                            class="px-6 py-3 rounded-xl border border-amber-200 bg-white text-gray-700 font-semibold hover:bg-amber-50 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button type="button" id="nextStep3"
                            class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                        Next: Availability & Media <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 4 -->
            <div id="step4" class="p-8 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">4. Availability & Media</h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Availability Start</label>
                            <input type="date" id="availabilityStart" name="availability_start"
                                   value="<?= h($form['availability_start']) ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Availability End</label>
                            <input type="date" id="availabilityEnd" name="availability_end"
                                   value="<?= h($form['availability_end']) ?>"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                            <input type="url" id="videoUrl" name="video_url"
                                   value="<?= h($form['video_url']) ?>"
                                   placeholder="https://..."
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Virtual Tour URL</label>
                            <input type="url" id="virtualTourUrl" name="virtual_tour_url"
                                   value="<?= h($form['virtual_tour_url']) ?>"
                                   placeholder="https://..."
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Existing Cover Image -->
                        <?php if ($existingImages['cover_image']): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Cover Image</label>
                            <div class="flex items-center gap-4">
                                <div class="image-container relative">
                                    <img src="<?= h($existingImages['cover_image']) ?>" 
                                         alt="Current cover" 
                                         class="image-preview w-32 h-32 object-cover rounded-xl border border-amber-200">
                                    <button type="button" 
                                            onclick="removeCoverImage()"
                                            class="delete-image-btn absolute -top-2 -right-2 h-8 w-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Click the X to remove this image. Upload a new image below to replace it.
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- New Cover Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= $existingImages['cover_image'] ? 'Replace Cover Image' : 'Upload Cover Image' ?>
                            </label>
                            <input type="file" id="coverImageInput" name="cover_image" accept="image/*"
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                            <p class="text-xs text-gray-500 mt-1">Allowed: JPG, PNG, WEBP</p>
                        </div>

                        <!-- Existing Gallery Images -->
                        <?php if (!empty($existingImages['gallery_images'])): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Gallery Images</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="existingGallery">
                                <?php foreach ($existingImages['gallery_images'] as $image): ?>
                                <div class="image-container relative">
                                    <img src="<?= h($image) ?>" 
                                         alt="Gallery image" 
                                         class="image-preview w-full h-32 object-cover rounded-xl border border-amber-200">
                                    <button type="button" 
                                            onclick="removeGalleryImage('<?= h($image) ?>')"
                                            class="delete-image-btn absolute -top-2 -right-2 h-8 w-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- New Gallery Images Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Add More Gallery Images
                            </label>
                            <input type="file" id="galleryInput" name="gallery_images[]" accept="image/*" multiple
                                   class="w-full p-4 rounded-xl border border-amber-200 bg-white">
                            <p class="text-xs text-gray-500 mt-1">You can select multiple images</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prevStep4"
                            class="px-6 py-3 rounded-xl border border-amber-200 bg-white text-gray-700 font-semibold hover:bg-amber-50 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button type="button" id="nextStep4"
                            class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                        Next: Review & Update <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 5 -->
            <div id="step5" class="p-8 hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-6">5. Review & Update Package</h3>

                <div class="p-6 rounded-2xl border border-amber-200 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">Package:</span> <span class="font-semibold" id="previewPackageName"></span></div>
                        <div><span class="text-gray-500">Category:</span> <span class="font-semibold" id="previewCategory"></span></div>
                        <div><span class="text-gray-500">Region:</span> <span class="font-semibold" id="previewDestination"></span></div>
                        <div><span class="text-gray-500">Duration:</span> <span class="font-semibold" id="previewDuration"></span></div>
                        <div><span class="text-gray-500">Difficulty:</span> <span class="font-semibold" id="previewDifficulty"></span></div>
                        <div><span class="text-gray-500">Accommodation:</span> <span class="font-semibold" id="previewAccommodation"></span></div>
                        <div><span class="text-gray-500">Group Size:</span> <span class="font-semibold" id="previewGroupSize"></span></div>
                        <div><span class="text-gray-500">Base Price:</span> <span class="font-semibold" id="previewPrice"></span></div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prevStep5"
                            class="px-6 py-3 rounded-xl border border-amber-200 bg-white text-gray-700 font-semibold hover:bg-amber-50 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>

                    <div class="flex gap-4">
                        <a href="marketing_campaigns.php" 
                           class="px-6 py-3 rounded-xl border border-amber-200 bg-white text-gray-700 font-semibold hover:bg-amber-50 transition-all">
                            Cancel
                        </a>
                        
                        <button type="submit" id="updatePackageBtn"
                                class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all text-lg">
                            <i class="fas fa-save mr-2"></i> Update Package
                        </button>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-3">
                    When you click "Update Package", the changes will be saved to the database.
                </p>
            </div>

        </form>
    </div>

    <footer class="mt-10 text-center text-sm text-gray-500">
        © <?= (int)$currentYear ?> TravelEase. All rights reserved.
    </footer>

</main>

<script>
    // Mobile menu
    const mBtn = document.getElementById('mobile-menu-button');
    const mClose = document.getElementById('mobile-menu-close');
    const mMenu = document.getElementById('mobile-menu');
    const mBackdrop = document.getElementById('mobile-menu-backdrop');
    function openMenu(){ mMenu.classList.remove('hidden'); mBackdrop.classList.remove('hidden'); }
    function closeMenu(){ mMenu.classList.add('hidden'); mBackdrop.classList.add('hidden'); }
    mBtn?.addEventListener('click', openMenu);
    mClose?.addEventListener('click', closeMenu);
    mBackdrop?.addEventListener('click', closeMenu);

    // Steps
    const steps = ['step1','step2','step3','step4','step5'].map(id => document.getElementById(id));
    const progressFill = document.getElementById('progressFill');
    const currentStepLabel = document.getElementById('currentStepLabel');
    let current = 0;

    function showStep(i){
        steps.forEach((s, idx) => s.classList.toggle('hidden', idx !== i));
        current = i;
        const percent = [20,40,60,80,100][i] || 20;
        progressFill.style.width = percent + '%';
        currentStepLabel.textContent = 'Step ' + (i+1);
        if(i === 4) fillPreview();
    }

    document.getElementById('nextStep1').addEventListener('click', () => showStep(1));
    document.getElementById('prevStep2').addEventListener('click', () => showStep(0));
    document.getElementById('nextStep2').addEventListener('click', () => showStep(2));
    document.getElementById('prevStep3').addEventListener('click', () => showStep(1));
    document.getElementById('nextStep3').addEventListener('click', () => showStep(3));
    document.getElementById('prevStep4').addEventListener('click', () => showStep(2));
    document.getElementById('nextStep4').addEventListener('click', () => showStep(4));
    document.getElementById('prevStep5').addEventListener('click', () => showStep(3));

    // Quill
    const quill = new Quill('#editor', { theme: 'snow' });

    // Load existing detailed_description into editor
    const existingHtml = document.getElementById('detailed_description').value;
    if (existingHtml && existingHtml.trim() !== '') {
        quill.root.innerHTML = existingHtml;
    }

    // Short description counter
    const shortDesc = document.getElementById('shortDescription');
    const charCount = document.getElementById('charCount');
    function updateCount(){
        charCount.textContent = (shortDesc.value || '').length;
    }
    shortDesc.addEventListener('input', updateCount);
    updateCount();

    // Preview
    function fillPreview(){
        document.getElementById('previewPackageName').textContent = document.getElementById('packageName').value || '-';
        const catSel = document.getElementById('packageCategory');
        document.getElementById('previewCategory').textContent = catSel.options[catSel.selectedIndex]?.text || '-';
        const regSel = document.getElementById('destinationRegion');
        document.getElementById('previewDestination').textContent = regSel.options[regSel.selectedIndex]?.text || '-';
        document.getElementById('previewDuration').textContent = (document.getElementById('packageDuration').value || '-') + ' days';
        const diffSel = document.getElementById('difficultyLevel');
        document.getElementById('previewDifficulty').textContent = diffSel.options[diffSel.selectedIndex]?.text || '-';
        const accSel = document.getElementById('accommodationType');
        document.getElementById('previewAccommodation').textContent = accSel.options[accSel.selectedIndex]?.text || '-';

        const gmin = document.getElementById('groupSizeMin').value;
        const gmax = document.getElementById('groupSizeMax').value;
        document.getElementById('previewGroupSize').textContent = (gmin || '-') + ' to ' + (gmax || '-') ;

        const price = document.getElementById('basePrice').value;
        document.getElementById('previewPrice').textContent = price ? ('$' + Number(price).toFixed(2)) : '-';
    }

    // Before submit: write Quill HTML into hidden input
    const form = document.getElementById('packageForm');
    form.addEventListener('submit', function(){
        document.getElementById('detailed_description').value = quill.root.innerHTML || '';
    });

    // Image removal functions
    function removeCoverImage() {
        document.getElementById('keepCoverImage').value = '0';
        const imageContainer = document.querySelector('.image-container');
        if (imageContainer) {
            imageContainer.style.display = 'none';
        }
        document.getElementById('coverImageInput').value = '';
    }

    function removeGalleryImage(imageUrl) {
        const keepGalleryInput = document.getElementById('keepGalleryImages');
        let currentImages = keepGalleryInput.value ? keepGalleryInput.value.split(',') : [];
        currentImages = currentImages.filter(img => img !== imageUrl);
        keepGalleryInput.value = currentImages.join(',');
        
        // Hide the removed image
        const imageContainers = document.querySelectorAll('#existingGallery .image-container');
        imageContainers.forEach(container => {
            const img = container.querySelector('img');
            if (img && img.src.includes(imageUrl)) {
                container.style.display = 'none';
            }
        });
    }

    // Start at step 1
    showStep(0);
</script>

</body>
</html>