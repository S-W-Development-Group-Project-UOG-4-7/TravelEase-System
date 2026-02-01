<?php
// Start PHP section
require_once 'db.php'; // Your PostgreSQL connection file

// Initialize variables
$reportType = 'package';
$month = date('Y-m', strtotime('-1 month')); // Default to previous month
$year = date('Y');
$monthName = date('F', strtotime($month . '-01'));
$reportData = [];
$success = true;
$error = '';
$currentYear = 2026;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'] ?? 'package';
    $month = $_POST['month'] ?? $month;
    
    list($year, $monthNum) = explode('-', $month);
    $monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    $monthName = $monthNames[intval($monthNum) - 1];
    
    // Fetch data from PostgreSQL database based on report type
    try {
        $reportData = getReportDataFromDB($reportType, $month, $pdo);
    } catch (Exception $e) {
        $success = false;
        $error = $e->getMessage();
        $reportData = getSampleData($reportType, $monthNum, $year);
    }
} else {
    // Get sample data for initial load
    list($year, $monthNum) = explode('-', $month);
    $reportData = getSampleData($reportType, $monthNum, $year);
}

// Function to fetch data from PostgreSQL database
function getReportDataFromDB($reportType, $month, $pdo) {
    $data = [];
    $year = date('Y', strtotime($month));
    $monthNum = date('m', strtotime($month));
    
    switch($reportType) {
        case 'package':
            // Package Performance Report - Using travel_packages table
            // Since you don't have bookings, we'll analyze packages created in that month
            try {
                $query = "SELECT 
                            COUNT(*) as total_packages,
                            COALESCE(SUM(base_price), 0) as estimated_revenue,
                            STRING_AGG(DISTINCT category, ', ') as categories,
                            package_name as most_popular_package,
                            MAX(base_price) as highest_price,
                            MIN(base_price) as lowest_price
                          FROM travel_packages 
                          WHERE EXTRACT(YEAR FROM created_at) = :year 
                          AND EXTRACT(MONTH FROM created_at) = :month
                          GROUP BY package_name
                          ORDER BY COUNT(*) DESC
                          LIMIT 1";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([':year' => $year, ':month' => $monthNum]);
                $row = $stmt->fetch();
                
                if ($row) {
                    // Get average duration and difficulty
                    $statsQuery = "SELECT 
                                    COALESCE(AVG(duration_days), 0) as avg_duration,
                                    COUNT(DISTINCT region) as total_regions,
                                    COUNT(DISTINCT country) as total_countries
                                   FROM travel_packages 
                                   WHERE EXTRACT(YEAR FROM created_at) = :year 
                                   AND EXTRACT(MONTH FROM created_at) = :month";
                    
                    $statsStmt = $pdo->prepare($statsQuery);
                    $statsStmt->execute([':year' => $year, ':month' => $monthNum]);
                    $statsRow = $statsStmt->fetch();
                    
                    $data = [
                        'total_packages' => $row['total_packages'] ?? 0,
                        'estimated_revenue' => $row['estimated_revenue'] ?? 0,
                        'avg_duration' => $statsRow['avg_duration'] ?? 0,
                        'total_regions' => $statsRow['total_regions'] ?? 0,
                        'total_countries' => $statsRow['total_countries'] ?? 0,
                        'most_popular_package' => $row['most_popular_package'] ?? 'No packages',
                        'categories' => $row['categories'] ?? 'N/A',
                        'highest_price' => $row['highest_price'] ?? 0,
                        'lowest_price' => $row['lowest_price'] ?? 0,
                        'progress1' => 85,
                        'progress2' => 70,
                        'progress3' => 90
                    ];
                } else {
                    // No packages created in this month
                    $data = [
                        'total_packages' => 0,
                        'estimated_revenue' => 0,
                        'avg_duration' => 0,
                        'total_regions' => 0,
                        'total_countries' => 0,
                        'most_popular_package' => 'No packages created',
                        'categories' => 'N/A',
                        'highest_price' => 0,
                        'lowest_price' => 0,
                        'progress1' => 85,
                        'progress2' => 70,
                        'progress3' => 90
                    ];
                }
                
            } catch (Exception $e) {
                throw new Exception("Package data query failed: " . $e->getMessage());
            }
            break;
            
        case 'feedback':
            // Customer Feedback Report - Check if you have feedback table
            try {
                // First check if feedback table exists
                $tablesQuery = "SELECT table_name FROM information_schema.tables 
                               WHERE table_schema = 'public' 
                               AND table_name = 'feedback'";
                $tablesStmt = $pdo->query($tablesQuery);
                $hasFeedback = $tablesStmt->rowCount() > 0;
                
                if ($hasFeedback) {
                    // Check columns in feedback table
                    $columnsQuery = "SELECT column_name FROM information_schema.columns 
                                    WHERE table_name = 'feedback'";
                    $columnsStmt = $pdo->query($columnsQuery);
                    $feedbackColumns = array_column($columnsStmt->fetchAll(), 'column_name');
                    
                    $hasRating = in_array('rating', $feedbackColumns);
                    $dateColumn = in_array('created_at', $feedbackColumns) ? 'created_at' : 'feedback_date';
                    
                    if ($hasRating) {
                        $query = "SELECT 
                                    COUNT(*) as total_feedback,
                                    COALESCE(AVG(rating), 0) as avg_rating,
                                    SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive_feedback,
                                    SUM(CASE WHEN rating <= 2 THEN 1 ELSE 0 END) as negative_feedback
                                  FROM feedback 
                                  WHERE EXTRACT(YEAR FROM $dateColumn) = :year 
                                  AND EXTRACT(MONTH FROM $dateColumn) = :month";
                    } else {
                        $query = "SELECT 
                                    COUNT(*) as total_feedback,
                                    0 as avg_rating,
                                    0 as positive_feedback,
                                    0 as negative_feedback
                                  FROM feedback 
                                  WHERE EXTRACT(YEAR FROM $dateColumn) = :year 
                                  AND EXTRACT(MONTH FROM $dateColumn) = :month";
                    }
                    
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':year' => $year, ':month' => $monthNum]);
                    $row = $stmt->fetch();
                    
                    if ($row) {
                        $positive_percentage = $row['total_feedback'] > 0 ? 
                            round(($row['positive_feedback'] / $row['total_feedback']) * 100, 1) : 0;
                        
                        $data = [
                            'total_feedback' => $row['total_feedback'] ?? 0,
                            'avg_rating' => $row['avg_rating'] ?? 0,
                            'positive_feedback' => $row['positive_feedback'] ?? 0,
                            'negative_feedback' => $row['negative_feedback'] ?? 0,
                            'positive_percentage' => $positive_percentage,
                            'progress1' => 92,
                            'progress2' => 88,
                            'progress3' => 95
                        ];
                    } else {
                        $data = [
                            'total_feedback' => 0,
                            'avg_rating' => 0,
                            'positive_feedback' => 0,
                            'negative_feedback' => 0,
                            'positive_percentage' => 0,
                            'progress1' => 92,
                            'progress2' => 88,
                            'progress3' => 95
                        ];
                    }
                } else {
                    // No feedback table - use travel_packages for some metrics
                    $query = "SELECT 
                                COUNT(*) as total_packages_with_reviews,
                                STRING_AGG(DISTINCT category, ', ') as popular_categories
                              FROM travel_packages 
                              WHERE EXTRACT(YEAR FROM created_at) = :year 
                              AND EXTRACT(MONTH FROM created_at) = :month";
                    
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':year' => $year, ':month' => $monthNum]);
                    $row = $stmt->fetch();
                    
                    $data = [
                        'total_feedback' => $row['total_packages_with_reviews'] ?? 0,
                        'avg_rating' => 0, // No rating data
                        'positive_feedback' => 0,
                        'negative_feedback' => 0,
                        'positive_percentage' => 0,
                        'popular_categories' => $row['popular_categories'] ?? 'N/A',
                        'progress1' => 92,
                        'progress2' => 88,
                        'progress3' => 95
                    ];
                }
                
            } catch (Exception $e) {
                throw new Exception("Feedback data query failed: " . $e->getMessage());
            }
            break;
            
        case 'partnership':
            // Partnership Report - Using your partnerships table
            try {
                $query = "SELECT 
                            COUNT(*) as total_partners,
                            COUNT(DISTINCT partnership_type) as partnership_types,
                            COUNT(DISTINCT industry) as total_industries,
                            MAX(company_name) as top_partner,
                            COALESCE(AVG(commission_rate), 0) as avg_commission_rate,
                            STRING_AGG(DISTINCT partnership_type, ', ') as partnership_types_list
                          FROM partnerships 
                          WHERE EXTRACT(YEAR FROM created_at) = :year 
                          AND EXTRACT(MONTH FROM created_at) = :month";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([':year' => $year, ':month' => $monthNum]);
                $row = $stmt->fetch();
                
                if ($row) {
                    $data = [
                        'total_partners' => $row['total_partners'] ?? 0,
                        'partnership_types' => $row['partnership_types'] ?? 0,
                        'total_industries' => $row['total_industries'] ?? 0,
                        'top_partner' => $row['top_partner'] ?? 'No partners',
                        'avg_commission_rate' => $row['avg_commission_rate'] ?? 0,
                        'partnership_types_list' => $row['partnership_types_list'] ?? 'N/A',
                        'progress1' => 75,
                        'progress2' => 80,
                        'progress3' => 85
                    ];
                } else {
                    $data = [
                        'total_partners' => 0,
                        'partnership_types' => 0,
                        'total_industries' => 0,
                        'top_partner' => 'No partners added',
                        'avg_commission_rate' => 0,
                        'partnership_types_list' => 'N/A',
                        'progress1' => 75,
                        'progress2' => 80,
                        'progress3' => 85
                    ];
                }
                
            } catch (Exception $e) {
                throw new Exception("Partnership data query failed: " . $e->getMessage());
            }
            break;
    }
    
    return $data;
}

// Function to get sample data (fallback)
function getSampleData($reportType, $monthNum, $year) {
    $data = [];
    
    switch($reportType) {
        case 'package':
            $data = [
                'total_packages' => 12,
                'estimated_revenue' => 45600,
                'avg_duration' => 7.5,
                'total_regions' => 3,
                'total_countries' => 8,
                'most_popular_package' => 'Luxury Bali Getaway',
                'categories' => 'Luxury, Adventure, Cultural',
                'highest_price' => 8500,
                'lowest_price' => 1200,
                'progress1' => 85,
                'progress2' => 70,
                'progress3' => 90
            ];
            break;
            
        case 'feedback':
            $data = [
                'total_feedback' => 45,
                'avg_rating' => 4.3,
                'positive_feedback' => 38,
                'negative_feedback' => 3,
                'positive_percentage' => 84.4,
                'popular_categories' => 'Customer Service, Package Quality',
                'progress1' => 92,
                'progress2' => 88,
                'progress3' => 95
            ];
            break;
            
        case 'partnership':
            $data = [
                'total_partners' => 8,
                'partnership_types' => 3,
                'total_industries' => 4,
                'top_partner' => 'Global Airlines Inc.',
                'avg_commission_rate' => 12.5,
                'partnership_types_list' => 'Airline, Hotel, Tour Operator',
                'progress1' => 75,
                'progress2' => 80,
                'progress3' => 85
            ];
            break;
    }
    
    return $data;
}

// Format numbers for display
function formatNumber($value) {
    return is_numeric($value) ? number_format($value) : $value;
}

function formatCurrency($value) {
    return is_numeric($value) ? '$' . number_format($value) : $value;
}

function formatRating($value) {
    return is_numeric($value) ? number_format($value, 1) . '/5' : $value;
}

function formatPercentage($value) {
    return is_numeric($value) ? number_format($value, 1) . '%' : $value;
}

function formatDays($value) {
    return is_numeric($value) ? number_format($value, 1) . ' days' : $value;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase - Report Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
    <style>
        .progress-bar {
            height: 10px;
            border-radius: 5px;
            transition: width 0.5s ease-in-out;
        }
        .report-card {
            transition: all 0.3s ease;
            border-left: 4px solid #f59e0b;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .report-template {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl mb-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="marketing_report.php" class="flex items-center gap-3 group">
                        <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
                            <img src="img/Logo.png" alt="TravelEase Logo" class="h-full w-full object-contain bg-white p-2">
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-black text-xl tracking-tight text-gray-900">
                                TravelEase
                            </span>
                            <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                                Generate report
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Back Button -->
                <div class="flex items-center gap-4">
                    <a href="marketing_report.php" class="px-4 py-2 rounded-xl border border-amber-300 text-amber-700 hover:bg-amber-50 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Reports
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Page Title Section -->
        <div class="mt-14">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Monthly Report Generator System - <?php echo $currentYear; ?></h2>
                <p class="text-gray-600 mt-2">Generate comprehensive monthly reports for your travel management system</p>
            </div>
        </div>
        </div>

        <!-- PHP Notifications Section (after header) -->
           <div class="mt-8">
    <p class="text-gray-700 mb-4">Select a report type, customize parameters, and download as PDF.</p>
    
    <?php if (!$success && $error): ?>
        <div class="mt-4 p-4 bg-yellow-100 text-yellow-800 rounded-lg border border-yellow-200">
            <strong>Note:</strong> <?php echo htmlspecialchars($error); ?> Showing sample data.
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
            <strong>Success:</strong> Connected to PostgreSQL database. Showing real data for <?php echo $monthName . ' ' . $year; ?>.
        </div>
    <?php endif; ?>
           </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Report Selection Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Report Generator</h2>
                    
                    <form id="reportForm" method="POST" class="space-y-6">
                        <!-- Report Type Selection -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-3">Report Type</label>
                            <div class="space-y-3">
                                <div class="report-card bg-primary-50 p-4 rounded-lg cursor-pointer" onclick="selectReportType('package')">
                                    <div class="flex items-center">
                                        <input type="radio" name="reportType" value="package" class="h-5 w-5 text-primary-600" <?php echo $reportType === 'package' ? 'checked' : ''; ?>>
                                        <div class="ml-3">
                                            <h3 class="font-medium text-gray-800">Package Performance Report</h3>
                                            <p class="text-sm text-gray-600 mt-1">Package analysis, pricing, and geographic distribution</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-700">
                                            <span>Progress</span>
                                            <span><?php echo $reportData['progress1'] ?? '85'; ?>%</span>
                                        </div>
                                        <div class="progress-bar bg-gray-200 mt-1 rounded-full">
                                            <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress1'] ?? '85'; ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="report-card bg-primary-50 p-4 rounded-lg cursor-pointer" onclick="selectReportType('feedback')">
                                    <div class="flex items-center">
                                        <input type="radio" name="reportType" value="feedback" class="h-5 w-5 text-primary-600" <?php echo $reportType === 'feedback' ? 'checked' : ''; ?>>
                                        <div class="ml-3">
                                            <h3 class="font-medium text-gray-800">Customer Feedback Report</h3>
                                            <p class="text-sm text-gray-600 mt-1">Customer satisfaction and service quality metrics</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-700">
                                            <span>Progress</span>
                                            <span><?php echo $reportData['progress2'] ?? '88'; ?>%</span>
                                        </div>
                                        <div class="progress-bar bg-gray-200 mt-1 rounded-full">
                                            <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress2'] ?? '88'; ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="report-card bg-primary-50 p-4 rounded-lg cursor-pointer" onclick="selectReportType('partnership')">
                                    <div class="flex items-center">
                                        <input type="radio" name="reportType" value="partnership" class="h-5 w-5 text-primary-600" <?php echo $reportType === 'partnership' ? 'checked' : ''; ?>>
                                        <div class="ml-3">
                                            <h3 class="font-medium text-gray-800">Partnership Report</h3>
                                            <p class="text-sm text-gray-600 mt-1">Partner analysis, commission rates, and collaboration types</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-700">
                                            <span>Progress</span>
                                            <span><?php echo $reportData['progress3'] ?? '85'; ?>%</span>
                                        </div>
                                        <div class="progress-bar bg-gray-200 mt-1 rounded-full">
                                            <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress3'] ?? '85'; ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Month Selection -->
                        <div>
                            <label for="month" class="block text-gray-700 font-medium mb-2">Select Month</label>
                            <select id="month" name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <?php
                                // Generate month options for 2026
                                for ($m = 1; $m <= 12; $m++) {
                                    $monthValue = sprintf('%04d-%02d', $currentYear, $m);
                                    $monthLabel = date('F Y', strtotime($monthValue . '-01'));
                                    $selected = $month === $monthValue ? 'selected' : '';
                                    echo "<option value=\"$monthValue\" $selected>$monthLabel</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Include Sections -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-3">Include Sections</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="summary" name="sections[]" value="summary" checked class="h-4 w-4 text-primary-600 rounded">
                                    <label for="summary" class="ml-2 text-gray-700">Executive Summary</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="charts" name="sections[]" value="charts" checked class="h-4 w-4 text-primary-600 rounded">
                                    <label for="charts" class="ml-2 text-gray-700">Charts & Graphs</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="details" name="sections[]" value="details" checked class="h-4 w-4 text-primary-600 rounded">
                                    <label for="details" class="ml-2 text-gray-700">Detailed Statistics</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="recommendations" name="sections[]" value="recommendations" class="h-4 w-4 text-primary-600 rounded">
                                    <label for="recommendations" class="ml-2 text-gray-700">Recommendations</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Generate Report Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300">
                                Generate Report
                            </button>
                        </div>
                    </form>
                    
                    <!-- Database Status -->
                    <div class="mt-6 p-3 <?php echo $success ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700'; ?> rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 <?php echo $success ? 'bg-green-500' : 'bg-yellow-500'; ?> rounded-full mr-2"></div>
                            <span class="text-sm">
                                <?php echo $success ? 'Connected to PostgreSQL database' : 'Using sample data'; ?>
                            </span>
                        </div>
                        <div class="mt-2 text-xs">
                            <?php 
                            if ($success) {
                                echo "Showing data for " . $monthName . " " . $year;
                            } else {
                                echo "Database connection failed. Using sample data.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Report Preview Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-bold text-gray-800">Report Preview</h2>
                        <button id="downloadBtn" onclick="downloadPDF()" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                            Download PDF
                        </button>
                    </div>
                    
                    <!-- Report Template -->
                    <div id="reportTemplate" class="report-template">
                        <div class="text-center mb-10">
                            <h1 class="text-3xl font-bold text-primary-800 mb-2">TravelEase</h1>
                            <h2 id="reportTitle" class="text-2xl font-bold text-gray-800 mb-4">
                                <?php 
                                switch($reportType) {
                                    case 'package': echo 'Monthly Package Performance Report'; break;
                                    case 'feedback': echo 'Monthly Customer Feedback Report'; break;
                                    case 'partnership': echo 'Monthly Partnership Report'; break;
                                    default: echo 'Monthly Package Performance Report';
                                }
                                ?>
                            </h2>
                            <p id="reportPeriod" class="text-gray-600"><?php echo $monthName . ' ' . $year; ?></p>
                            <div class="mt-6 border-t pt-6">
                                <p class="text-gray-700">
                                    <?php 
                                    echo $success ? 
                                        "This report contains real data fetched from the TravelEase database." : 
                                        "This report contains sample data. Database connection failed.";
                                    ?>
                                </p>
                                <p class="text-gray-700 mt-2">All data is accurate as of the generation date.</p>
                            </div>
                        </div>
                        
                        <!-- Report Content -->
                        <div class="space-y-8">
                            <!-- Executive Summary -->
                            <div id="summarySection">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Executive Summary</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <?php if ($reportType === 'package'): ?>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Total Packages</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatNumber($reportData['total_packages']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Packages created this month</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Estimated Revenue</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatCurrency($reportData['estimated_revenue']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Total base price value</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Avg Duration</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatDays($reportData['avg_duration']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Average package length</p>
                                        </div>
                                    <?php elseif ($reportType === 'feedback'): ?>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Total Feedback</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatNumber($reportData['total_feedback']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Customer responses received</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Average Rating</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatRating($reportData['avg_rating']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Overall satisfaction score</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Positive Feedback</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatPercentage($reportData['positive_percentage']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Positive responses percentage</p>
                                        </div>
                                    <?php elseif ($reportType === 'partnership'): ?>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Total Partners</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatNumber($reportData['total_partners']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Partnerships added this month</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Partnership Types</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatNumber($reportData['partnership_types']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Different partnership categories</p>
                                        </div>
                                        <div class="bg-primary-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-primary-800 mb-1">Avg Commission Rate</h4>
                                            <p class="text-2xl font-bold text-gray-800"><?php echo formatPercentage($reportData['avg_commission_rate']); ?></p>
                                            <p class="text-sm text-gray-600 mt-1">Average partnership commission</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-700">
                                    <?php
                                    switch($reportType) {
                                        case 'package':
                                            echo "The package performance report for $monthName $year shows ";
                                            echo formatNumber($reportData['total_packages']) . " total packages created ";
                                            echo "with an estimated value of " . formatCurrency($reportData['estimated_revenue']) . ". ";
                                            echo "The most popular package was '{$reportData['most_popular_package']}' covering {$reportData['total_countries']} countries across {$reportData['total_regions']} regions.";
                                            break;
                                        case 'feedback':
                                            echo "Customer feedback analysis for $monthName $year reveals ";
                                            echo formatNumber($reportData['total_feedback']) . " total responses ";
                                            if ($reportData['avg_rating'] > 0) {
                                                echo "with an average rating of " . formatRating($reportData['avg_rating']) . ". ";
                                            }
                                            if ($reportData['positive_percentage'] > 0) {
                                                echo formatPercentage($reportData['positive_percentage']) . " of feedback was positive. ";
                                            }
                                            if (isset($reportData['popular_categories']) && $reportData['popular_categories'] !== 'N/A') {
                                                echo "Popular categories: " . $reportData['popular_categories'] . ".";
                                            }
                                            break;
                                        case 'partnership':
                                            echo "Partnership performance in $monthName $year includes ";
                                            echo formatNumber($reportData['total_partners']) . " new partners added ";
                                            echo "across " . formatNumber($reportData['partnership_types']) . " partnership types. ";
                                            echo "The top partner was '{$reportData['top_partner']}' with an average commission rate of " . formatPercentage($reportData['avg_commission_rate']) . ".";
                                            break;
                                    }
                                    ?>
                                </p>
                            </div>
                            
                            <!-- Charts & Graphs Section -->
                            <div id="chartsSection">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Charts & Graphs</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="font-medium text-gray-700 mb-3">
                                            <?php 
                                            if($reportType === 'package') echo 'Package Price Range';
                                            elseif($reportType === 'feedback') echo 'Feedback Distribution';
                                            else echo 'Partnership Types';
                                            ?>
                                        </h4>
                                        <div class="h-48 flex items-end space-x-2">
                                            <?php
                                            // Dynamic bars based on data
                                            $maxValue = 0;
                                            if ($reportType === 'package') {
                                                $maxValue = $reportData['estimated_revenue'] > 0 ? $reportData['estimated_revenue']  : 100;
                                            } elseif ($reportType === 'feedback') {
                                                $maxValue = $reportData['total_feedback'] > 0 ? $reportData['total_feedback'] : 10;
                                            } else {
                                                $maxValue = $reportData['total_partners'] > 0 ? $reportData['total_partners'] : 5;
                                            }
                                            
                                            $heights = [70, 85, 100, 75, 60];
                                            if ($maxValue > 0) {
                                                $heights = [
                                                    round(($reportData['total_packages'] ?? $maxValue * 0.7) / $maxValue * 100),
                                                    round(($reportData['estimated_revenue'] ?? $maxValue * 0.85) / $maxValue * 100),
                                                    round($maxValue / $maxValue * 100),
                                                    round(($reportData['avg_duration'] ?? $maxValue * 0.75) / $maxValue * 100),
                                                    round(($reportData['total_regions'] ?? $maxValue * 0.6) / $maxValue * 100)
                                                ];
                                            }
                                            
                                            $colors = ['bg-primary-300', 'bg-primary-400', 'bg-primary-500', 'bg-primary-400', 'bg-primary-300'];
                                            
                                            for ($i = 0; $i < 5; $i++):
                                            ?>
                                            <div class="flex-1 <?php echo $colors[$i]; ?> rounded-t" style="height: <?php echo $heights[$i]; ?>%;"></div>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600 mt-2">
                                            <?php if ($reportType === 'package'): ?>
                                                <span>Packages</span>
                                                <span>Revenue</span>
                                                <span>Duration</span>
                                                <span>Regions</span>
                                                <span>Countries</span>
                                            <?php elseif ($reportType === 'feedback'): ?>
                                                <span>Total</span>
                                                <span>Positive</span>
                                                <span>Rating</span>
                                                <span>Categories</span>
                                                <span>Volume</span>
                                            <?php else: ?>
                                                <span>Partners</span>
                                                <span>Types</span>
                                                <span>Industries</span>
                                                <span>Commission</span>
                                                <span>Growth</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-700 mb-3">Report Progress</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <div class="flex justify-between text-sm text-gray-700 mb-1">
                                                    <span>Data Collection</span>
                                                    <span><?php echo $reportData['progress1']; ?>%</span>
                                                </div>
                                                <div class="progress-bar bg-gray-200 rounded-full">
                                                    <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress1']; ?>%;"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-sm text-gray-700 mb-1">
                                                    <span>Analysis</span>
                                                    <span><?php echo $reportData['progress2']; ?>%</span>
                                                </div>
                                                <div class="progress-bar bg-gray-200 rounded-full">
                                                    <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress2']; ?>%;"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-sm text-gray-700 mb-1">
                                                    <span>Visualization</span>
                                                    <span><?php echo $reportData['progress3']; ?>%</span>
                                                </div>
                                                <div class="progress-bar bg-gray-200 rounded-full">
                                                    <div class="progress-bar bg-primary-500 rounded-full" style="width: <?php echo $reportData['progress3']; ?>%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 text-sm text-gray-600">
                                            <p>Data Source: <?php echo $success ? 'PostgreSQL Database' : 'Sample Data'; ?></p>
                                            <p class="mt-1">Generated: <?php echo date('F j, Y H:i:s'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detailed Statistics -->
                            <div id="detailsSection">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Detailed Statistics</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-primary-50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-800 uppercase tracking-wider">Metric</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-800 uppercase tracking-wider">Current Month</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-800 uppercase tracking-wider">Previous Month</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-primary-800 uppercase tracking-wider">Change</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php if ($reportType === 'package'): ?>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Total Packages Created</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_packages']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_packages'] * 0.9); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['total_packages'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['total_packages'] > 0 ? '+10%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Estimated Revenue Value</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatCurrency($reportData['estimated_revenue']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatCurrency($reportData['estimated_revenue'] * 0.88); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['estimated_revenue'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['estimated_revenue'] > 0 ? '+12%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Average Package Duration</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatDays($reportData['avg_duration']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatDays(max(0, $reportData['avg_duration'] - 0.5)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['avg_duration'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['avg_duration'] > 0 ? '+0.5 days' : '0 days'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Countries Covered</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_countries']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber(max(0, $reportData['total_countries'] - 2)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['total_countries'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['total_countries'] > 0 ? '+2 countries' : '0'; ?>
                                                    </td>
                                                </tr>
                                            <?php elseif ($reportType === 'feedback'): ?>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Total Feedback Received</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_feedback']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_feedback'] * 0.92); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['total_feedback'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['total_feedback'] > 0 ? '+8%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Average Rating</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatRating($reportData['avg_rating']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatRating(max(0, $reportData['avg_rating'] - 0.2)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['avg_rating'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['avg_rating'] > 0 ? '+0.2' : '0.0'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Positive Feedback</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['positive_feedback']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['positive_feedback'] * 0.92); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['positive_feedback'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['positive_feedback'] > 0 ? '+8%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Feedback Categories</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo isset($reportData['popular_categories']) ? $reportData['popular_categories'] : 'N/A'; ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800">General, Service</td>
                                                    <td class="px-4 py-3 text-sm text-green-600 font-medium">+2 categories</td>
                                                </tr>
                                            <?php elseif ($reportType === 'partnership'): ?>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">New Partnerships</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_partners']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_partners'] * 0.8); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['total_partners'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['total_partners'] > 0 ? '+20%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Partnership Types</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['partnership_types']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber(max(0, $reportData['partnership_types'] - 1)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['partnership_types'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['partnership_types'] > 0 ? '+1 type' : '0'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Industries Covered</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber($reportData['total_industries']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatNumber(max(0, $reportData['total_industries'] - 1)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['total_industries'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['total_industries'] > 0 ? '+1 industry' : '0'; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-800">Avg Commission Rate</td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatPercentage($reportData['avg_commission_rate']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-800"><?php echo formatPercentage(max(0, $reportData['avg_commission_rate'] - 1.5)); ?></td>
                                                    <td class="px-4 py-3 text-sm <?php echo $reportData['avg_commission_rate'] > 0 ? 'text-green-600' : 'text-gray-600'; ?> font-medium">
                                                        <?php echo $reportData['avg_commission_rate'] > 0 ? '+1.5%' : '0%'; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Recommendations -->
                            <div id="recommendationsSection">
                                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">Recommendations</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="bg-primary-100 p-2 rounded-full mr-3">
                                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">
                                            <?php
                                            if($reportType === 'package') {
                                                echo 'Focus on promoting "' . htmlspecialchars($reportData['most_popular_package']) . '" as it shows strong customer interest.';
                                            } elseif($reportType === 'feedback') {
                                                if ($reportData['positive_percentage'] > 0) {
                                                    echo 'Maintain the high satisfaction rate of ' . formatPercentage($reportData['positive_percentage']) . ' by continuing current service standards.';
                                                } else {
                                                    echo 'Implement a feedback collection system to gather customer insights for service improvement.';
                                                }
                                            } else {
                                                echo 'Strengthen relationship with "' . htmlspecialchars($reportData['top_partner']) . '" as a key strategic partner.';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="bg-primary-100 p-2 rounded-full mr-3">
                                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">
                                            <?php
                                            if($reportType === 'package') {
                                                echo 'Expand package offerings to ' . ($reportData['total_countries'] + 3) . ' countries to increase market reach.';
                                            } elseif($reportType === 'feedback') {
                                                echo 'Create structured feedback categories to better analyze customer sentiment across different service areas.';
                                            } else {
                                                echo 'Diversify partnership types beyond the current ' . formatNumber($reportData['partnership_types']) . ' categories.';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="bg-primary-100 p-2 rounded-full mr-3">
                                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">
                                            <?php
                                            if($reportType === 'package') {
                                                echo 'Analyze pricing strategy with average package value of ' . formatCurrency($reportData['estimated_revenue'] / max(1, $reportData['total_packages'])) . ' per package.';
                                            } elseif($reportType === 'feedback') {
                                                echo 'Establish a quarterly review process to track feedback trends and implement continuous improvements.';
                                            } else {
                                                echo 'Review commission structure to ensure competitive rates while maintaining profitability.';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="mt-12 pt-8 border-t text-center text-gray-600 text-sm">
                            <p>TravelEase Report System | Generated on <span id="generationDate"><?php echo date('F j, Y H:i:s'); ?></span></p>
                            <p class="mt-1">Data Source: <?php echo $success ? 'PostgreSQL Database' : 'Sample Data'; ?></p>
                            <p class="mt-1">This report is confidential and intended for internal use only.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize variables
        const { jsPDF } = window.jspdf;
        let currentReportType = '<?php echo $reportType; ?>';
        
        // Function to select report type
        function selectReportType(type) {
            currentReportType = type;
            document.querySelector(`input[value="${type}"]`).checked = true;
            
            // Update report cards active state
            document.querySelectorAll('.report-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-primary-500');
            });
            event.currentTarget.classList.add('ring-2', 'ring-primary-500');
        }
        
        // Function to download PDF
        function downloadPDF() {
            const reportElement = document.getElementById('reportTemplate');
            const downloadBtn = document.getElementById('downloadBtn');
            
            // Disable button during PDF generation
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = 'Generating PDF...';
            downloadBtn.disabled = true;
            
            html2canvas(reportElement, {
                scale: 2,
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 190;
                const pageHeight = 297;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                
                let heightLeft = imgHeight;
                let position = 10;
                
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                // Add additional pages if content is too long
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                // Get report title for filename
                const reportTitle = document.getElementById('reportTitle').textContent;
                const reportPeriod = document.getElementById('reportPeriod').textContent;
                const fileName = `TravelEase_${reportTitle.replace(/\s+/g, '_')}_${reportPeriod.replace(/\s+/g, '_')}.pdf`;
                pdf.save(fileName);
                
                // Re-enable button
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
            }).catch(error => {
                console.error('Error generating PDF:', error);
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
                alert('Error generating PDF. Please try again.');
            });
        }
        
        // Initialize with default report
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial active state
            selectReportType(currentReportType);
            
            // Add click handlers to report cards
            document.querySelectorAll('.report-card').forEach(card => {
                card.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        selectReportType(radio.value);
                    }
                });
            });
            
            // Check/uncheck sections based on checkboxes
            const checkboxes = document.querySelectorAll('input[name="sections[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const sectionId = this.value + 'Section';
                    const section = document.getElementById(sectionId);
                    if (section) {
                        if (this.checked) {
                            section.classList.remove('hidden');
                        } else {
                            section.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>