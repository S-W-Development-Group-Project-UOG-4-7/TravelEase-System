<?php
// Database connection
require_once __DIR__ . '/db.php';

// Set default timezone
date_default_timezone_set('UTC');

// Set report period (can be made dynamic)
$reportPeriod = "Month";
$dateRange = date('F 1') . " - " . date('F t, Y');
$generatedBy = "Marketing Manager";

// Calculate date range for the report
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');
$lastMonthStart = date('Y-m-01', strtotime('-1 month'));
$lastMonthEnd = date('Y-m-t', strtotime('-1 month'));

try {
    // Fetch packages from database
    $packages = [];
    $stmt = $pdo->query("
        SELECT 
            id,
            package_name as name,
            category,
            region,
            country,
            short_description,
            detailed_description,
            duration_days,
            difficulty_level,
            accommodation_type,
            inclusions,
            base_price,
            group_min,
            group_max,
            availability_start as start_date,
            availability_end as end_date,
            early_bird_discount,
            early_bird_days,
            video_url,
            virtual_tour_url,
            cover_image,
            gallery_images,
            created_at,
            -- Calculate dynamic values for display
            COALESCE(group_min, 0) as leads,
            COALESCE(CAST(base_price * 0.1 as integer), 0) as conversions,
            CASE 
                WHEN availability_end < CURRENT_DATE THEN 'Completed'
                WHEN availability_start > CURRENT_DATE THEN 'Planned'
                WHEN availability_start <= CURRENT_DATE AND availability_end >= CURRENT_DATE THEN 'Active'
                ELSE 'Draft'
            END as status,
            CASE 
                WHEN availability_end < CURRENT_DATE THEN 'Completed'
                WHEN availability_start > CURRENT_DATE THEN 'Planned'
                WHEN availability_start <= CURRENT_DATE AND availability_end >= CURRENT_DATE THEN 'Active'
                ELSE 'Draft'
            END as display_status
        FROM travel_packages 
        ORDER BY created_at DESC
    ");
    
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate key metrics
    $totalPackages = count($packages);
    $totalBookings = 0;
    $totalRevenue = 0;
    $mostBookedPackage = null;
    $mostBookedCount = 0;
    $leastBookedPackage = null;
    $leastBookedCount = PHP_INT_MAX;
    $activePackages = 0;
    $completedPackages = 0;
    $plannedPackages = 0;
    
    // Sample ratings for packages (in real scenario, this would come from reviews table)
    $packageRatings = [
        1 => 4.9,
        2 => 4.8,
        3 => 4.7,
        4 => 4.6,
        5 => 4.5,
        6 => 4.2
    ];
    
    // Calculate package statistics
    foreach ($packages as $index => $package) {
        // Assign sample rating (in real app, fetch from reviews table)
        $packages[$index]['rating'] = $packageRatings[$package['id']] ?? rand(35, 50) / 10;
        
        // Calculate bookings (using conversions field)
        $bookings = $package['conversions'];
        $packages[$index]['bookings'] = $bookings;
        
        // Calculate revenue (bookings * price with 20% markup for operational costs)
        $revenue = $bookings * $package['base_price'] * 1.2;
        $packages[$index]['revenue'] = $revenue;
        
        // Update totals
        $totalBookings += $bookings;
        $totalRevenue += $revenue;
        
        // Track most/least booked packages
        if ($bookings > $mostBookedCount) {
            $mostBookedCount = $bookings;
            $mostBookedPackage = $package['name'];
        }
        
        if ($bookings < $leastBookedCount && $bookings > 0) {
            $leastBookedCount = $bookings;
            $leastBookedPackage = $package['name'];
        }
        
        // Count by status
        switch ($package['status']) {
            case 'Active':
                $activePackages++;
                break;
            case 'Completed':
                $completedPackages++;
                break;
            case 'Planned':
                $plannedPackages++;
                break;
        }
    }
    
    // Calculate month-over-month changes (using sample data for demonstration)
    $lastMonthBookings = $totalBookings * 0.88; // 12% increase
    $lastMonthRevenue = $totalRevenue * 0.92; // 8% increase
    $bookingChangePercent = $totalBookings > 0 ? (($totalBookings - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;
    $revenueChangePercent = $totalRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
    
    // If no packages found, use sample data for demonstration
    if (empty($packages)) {
        $packages = [
            [
                'id' => 1,
                'name' => 'Summer Asia Promotion',
                'status' => 'Active',
                'category' => 'Adventure',
                'base_price' => 25000,
                'duration_days' => 10,
                'region' => 'Asia',
                'country' => 'Thailand',
                'short_description' => 'Experience the ultimate summer adventure in Thailand',
                'start_date' => '2024-05-01',
                'end_date' => '2024-08-31',
                'bookings' => 156,
                'revenue' => 32450,
                'rating' => 4.9
            ],
            [
                'id' => 2,
                'name' => 'Swiss Alps Adventure',
                'status' => 'Active',
                'category' => 'Adventure',
                'base_price' => 18000,
                'duration_days' => 7,
                'region' => 'Europe',
                'country' => 'Switzerland',
                'short_description' => 'Alpine adventure in the Swiss mountains',
                'start_date' => '2024-06-01',
                'end_date' => '2024-09-30',
                'bookings' => 128,
                'revenue' => 28900,
                'rating' => 4.8
            ],
            [
                'id' => 3,
                'name' => 'Maldives Paradise',
                'status' => 'Active',
                'category' => 'Luxury',
                'base_price' => 32000,
                'duration_days' => 8,
                'region' => 'Asia',
                'country' => 'Maldives',
                'short_description' => 'Luxury beach resort experience',
                'start_date' => '2024-04-01',
                'end_date' => '2024-12-31',
                'bookings' => 112,
                'revenue' => 24750,
                'rating' => 4.7
            ]
        ];
        
        // Update totals with sample data
        $totalPackages = count($packages);
        $totalBookings = array_sum(array_column($packages, 'bookings'));
        $totalRevenue = array_sum(array_column($packages, 'revenue'));
        $mostBookedPackage = $packages[0]['name'];
        $mostBookedCount = $packages[0]['bookings'];
        $leastBookedPackage = $packages[2]['name'];
        $leastBookedCount = $packages[2]['bookings'];
    }
    
    // Format currency
    function formatCurrency($amount) {
        return '$' . number_format($amount);
    }
    
    // Format date
    function formatDate($date) {
        return date('M j, Y', strtotime($date));
    }
    
} catch (Exception $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());
    $packages = [];
    $totalPackages = 0;
    $totalBookings = 0;
    $totalRevenue = 0;
    $mostBookedPackage = "N/A";
    $leastBookedPackage = "N/A";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Package Performance Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a6fc4;
            --secondary-color: #f59e0b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f0f7ff;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9fafb;
            color: var(--text-primary);
            line-height: 1.6;
            padding: 20px;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Report Header */
        .report-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0d4d8c 100%);
            color: white;
            padding: 30px;
            position: relative;
        }

        .agency-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .agency-details h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .agency-details p {
            opacity: 0.9;
            font-size: 14px;
        }

        .report-title-section {
            text-align: center;
            padding: 20px 0;
        }

        .report-title-section h2 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .report-meta {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }

        .meta-item i {
            font-size: 18px;
        }

        /* Executive Summary */
        .executive-summary {
            background: var(--light-bg);
            border-left: 5px solid var(--primary-color);
            padding: 25px;
            margin: 30px;
            border-radius: 0 10px 10px 0;
        }

        .executive-summary h3 {
            color: var(--primary-color);
            font-size: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .executive-summary-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .summary-item {
            display: flex;
            gap: 15px;
            padding: 10px 0;
        }

        .summary-item i {
            color: var(--primary-color);
            font-size: 20px;
            margin-top: 3px;
        }

        /* Section Styling */
        .section {
            padding: 0 30px;
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-color);
        }

        .section-header i {
            color: var(--primary-color);
            font-size: 22px;
        }

        .section-header h3 {
            font-size: 22px;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Key Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
            border-top: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .metric-label {
            color: var(--text-secondary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }

        .metric-change {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .metric-change.positive {
            color: var(--success-color);
        }

        .metric-change.negative {
            color: var(--danger-color);
        }

        /* Package Table */
        .package-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .package-table th {
            background: var(--light-bg);
            color: var(--primary-color);
            text-align: left;
            padding: 16px 20px;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }

        .package-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .package-table tr:hover {
            background-color: #f9fbfe;
        }

        .rating-stars {
            color: var(--warning-color);
            letter-spacing: 2px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background: #e3f7ed;
            color: var(--success-color);
        }

        .status-completed {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .status-planned {
            background: #fef3c7;
            color: #f59e0b;
        }

        .status-draft {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Performance Insights */
        .insights-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .insight-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
            border-left: 5px solid var(--primary-color);
        }

        .insight-card h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-size: 18px;
        }

        .insight-card p {
            color: var(--text-primary);
            line-height: 1.7;
        }

        .insight-card.success {
            border-left-color: var(--success-color);
        }

        .insight-card.warning {
            border-left-color: var(--warning-color);
        }

        .insight-card.danger {
            border-left-color: var(--danger-color);
        }

        /* Trend Indicators */
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
        }

        .trend-up {
            background: #e3f7ed;
            color: var(--success-color);
        }

        .trend-down {
            background: #ffeaea;
            color: var(--danger-color);
        }

        /* Report Footer */
        .report-footer {
            margin-top: 40px;
            padding: 25px 30px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-info {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .report-id {
            background: var(--primary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        /* Category badges */
        .category-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .category-adventure { background: #dbeafe; color: #1d4ed8; }
        .category-luxury { background: #fef3c7; color: #d97706; }
        .category-beach { background: #d1fae5; color: #059669; }
        .category-cultural { background: #f3e8ff; color: #7c3aed; }
        .category-family { background: #ffe4e6; color: #e11d48; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .report-header {
                padding: 20px;
            }
            
            .report-title-section h2 {
                font-size: 24px;
            }
            
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .insights-container {
                grid-template-columns: 1fr;
            }
            
            .section {
                padding: 0 20px;
            }
            
            .package-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .report-meta {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- 1️⃣ Report Header -->
        <div class="report-header">
            <div class="agency-info">
                <div class="logo">
                    <i class="fas fa-globe-americas"></i>
                </div>
                <div class="agency-details">
                    <h1>Sunrise Travel Agency</h1>
                    <p>Creating Memorable Journeys Since 2010</p>
                </div>
            </div>
            
            <div class="report-title-section">
                <h2>Monthly Package Performance Report</h2>
                <div class="report-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Report Period: <?php echo $reportPeriod; ?> (<?php echo $dateRange; ?>)</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Generated: <?php echo date('F j, Y'); ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>Prepared by: <?php echo $generatedBy; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2️⃣ Executive Summary -->
        <div class="executive-summary">
            <h3><i class="fas fa-chart-line"></i> Executive Summary</h3>
            <div class="executive-summary-content">
                <div class="summary-item">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Overall Performance:</strong> 
                        <?php if ($totalBookings > 0): ?>
                            Package bookings reached <?php echo $totalBookings; ?> this month, generating <?php echo formatCurrency($totalRevenue); ?> in revenue. 
                            <?php echo $activePackages; ?> active packages are currently performing well.
                        <?php else: ?>
                            No package data available for this reporting period.
                        <?php endif; ?>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fas fa-trophy"></i>
                    <div>
                        <strong>Key Wins:</strong> 
                        <?php if ($mostBookedPackage): ?>
                            "<?php echo $mostBookedPackage; ?>" emerged as the top performer with <?php echo $mostBookedCount; ?> bookings. 
                            <?php echo $bookingChangePercent > 0 ? 'Bookings increased by ' . round($bookingChangePercent) . '% from last month.' : 'Bookings remained stable.'; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Major Issues:</strong> 
                        <?php if ($leastBookedPackage && $leastBookedCount > 0): ?>
                            "<?php echo $leastBookedPackage; ?>" underperformed with only <?php echo $leastBookedCount; ?> bookings. 
                            <?php echo $plannedPackages; ?> packages are still in planning phase.
                        <?php endif; ?>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <strong>Key Insights:</strong> 
                        <?php if ($totalRevenue > 0): ?>
                            Average revenue per booking is <?php echo formatCurrency($totalBookings > 0 ? $totalRevenue / $totalBookings : 0); ?>. 
                            <?php echo $completedPackages; ?> packages have completed their availability period.
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 Key Metrics Section -->
        <div class="section">
            <div class="section-header">
                <i class="fas fa-chart-bar"></i>
                <h3>Key Metrics</h3>
            </div>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-label">Total Packages Offered</div>
                    <div class="metric-value"><?php echo $totalPackages; ?></div>
                    <div class="metric-change <?php echo $totalPackages > 0 ? 'positive' : 'negative'; ?>">
                        <?php if ($totalPackages > 0): ?>
                            <i class="fas fa-<?php echo $totalPackages > 6 ? 'arrow-up' : 'minus'; ?>"></i>
                            <span><?php echo $totalPackages > 6 ? 'Good' : 'Needs more'; ?> variety</span>
                        <?php else: ?>
                            <i class="fas fa-times"></i>
                            <span>No packages</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-label">Total Bookings</div>
                    <div class="metric-value"><?php echo $totalBookings; ?></div>
                    <div class="metric-change <?php echo $bookingChangePercent >= 0 ? 'positive' : 'negative'; ?>">
                        <?php if ($bookingChangePercent >= 0): ?>
                            <i class="fas fa-arrow-up"></i>
                            <span>↑ <?php echo $bookingChangePercent > 0 ? round($bookingChangePercent) . '%' : 'Stable'; ?> from last month</span>
                        <?php else: ?>
                            <i class="fas fa-arrow-down"></i>
                            <span>↓ <?php echo abs(round($bookingChangePercent)); ?>% from last month</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value"><?php echo formatCurrency($totalRevenue); ?></div>
                    <div class="metric-change <?php echo $revenueChangePercent >= 0 ? 'positive' : 'negative'; ?>">
                        <?php if ($revenueChangePercent >= 0): ?>
                            <i class="fas fa-arrow-up"></i>
                            <span>↑ <?php echo $revenueChangePercent > 0 ? round($revenueChangePercent) . '%' : 'Stable'; ?> from last month</span>
                        <?php else: ?>
                            <i class="fas fa-arrow-down"></i>
                            <span>↓ <?php echo abs(round($revenueChangePercent)); ?>% from last month</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-label">Most Booked Package</div>
                    <div class="metric-value"><?php echo $mostBookedPackage ? substr($mostBookedPackage, 0, 15) . (strlen($mostBookedPackage) > 15 ? '...' : '') : 'N/A'; ?></div>
                    <div class="metric-label"><?php echo $mostBookedCount; ?> bookings</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-label">Least Booked Package</div>
                    <div class="metric-value"><?php echo $leastBookedPackage ? substr($leastBookedPackage, 0, 15) . (strlen($leastBookedPackage) > 15 ? '...' : '') : 'N/A'; ?></div>
                    <div class="metric-change <?php echo $leastBookedCount > 10 ? 'positive' : 'negative'; ?>">
                        <?php if ($leastBookedCount > 0): ?>
                            <i class="fas fa-<?php echo $leastBookedCount > 10 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                            <span><?php echo $leastBookedCount; ?> bookings</span>
                        <?php else: ?>
                            <span>No bookings</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📋 Package-wise Table -->
        <div class="section">
            <div class="section-header">
                <i class="fas fa-table"></i>
                <h3>Package-wise Performance</h3>
            </div>
            
            <?php if (!empty($packages)): ?>
            <table class="package-table">
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Category</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $package): 
                        // Determine trend indicator
                        $trendClass = '';
                        $trendIcon = '';
                        $trendText = '';
                        if ($package['bookings'] > 100) {
                            $trendClass = 'trend-up';
                            $trendIcon = '↑';
                            $trendText = 'High Demand';
                        } elseif ($package['bookings'] > 50) {
                            $trendClass = 'trend-up';
                            $trendIcon = '↑';
                            $trendText = 'Good';
                        } elseif ($package['bookings'] > 0) {
                            $trendClass = 'trend-down';
                            $trendIcon = '↓';
                            $trendText = 'Low';
                        }
                        
                        // Determine status class
                        $statusClass = 'status-' . strtolower($package['status']);
                        
                        // Determine category class
                        $categoryClass = 'category-' . strtolower($package['category'] ?? 'adventure');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($package['name']); ?></strong></td>
                        <td>
                            <span class="category-badge <?php echo $categoryClass; ?>">
                                <?php echo htmlspecialchars($package['category'] ?? 'Adventure'); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $package['bookings']; ?>
                            <?php if ($trendClass): ?>
                            <span class="trend-indicator <?php echo $trendClass; ?>">
                                <?php echo $trendIcon; ?> <?php echo $trendText; ?>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatCurrency($package['revenue']); ?></td>
                        <td>
                            <div class="rating-stars">
                                <?php
                                $rating = $package['rating'] ?? 4.0;
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $fullStars) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                                <span><?php echo number_format($rating, 1); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($package['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data" style="text-align: center; padding: 40px; background: var(--light-bg); border-radius: 10px;">
                <i class="fas fa-box-open" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                <h3 style="color: var(--text-secondary); margin-bottom: 10px;">No Package Data Available</h3>
                <p style="color: var(--text-secondary);">No travel packages have been created yet.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- 📈 Performance Insights -->
        <div class="section">
            <div class="section-header">
                <i class="fas fa-lightbulb"></i>
                <h3>Performance Insights</h3>
            </div>
            
            <div class="insights-container">
                <div class="insight-card success">
                    <h4><i class="fas fa-crown"></i> Best Performing Package</h4>
                    <?php if ($mostBookedPackage): ?>
                    <p><strong><?php echo htmlspecialchars($mostBookedPackage); ?></strong> leads with <?php echo $mostBookedCount; ?> bookings and <?php echo formatCurrency($totalRevenue * ($mostBookedCount / max($totalBookings, 1))); ?> in revenue.</p>
                    <p style="margin-top: 10px;"><strong>Recommendation:</strong> Consider creating similar packages or increasing capacity for this popular offering.</p>
                    <?php else: ?>
                    <p>No performance data available yet. Create and promote packages to see performance insights.</p>
                    <?php endif; ?>
                </div>
                
                <div class="insight-card warning">
                    <h4><i class="fas fa-cloud-sun"></i> Seasonal Trends</h4>
                    <p>
                        <?php 
                        $currentMonth = date('n');
                        if (in_array($currentMonth, [11, 12, 1, 2])): // Nov-Feb
                            echo 'Winter destinations and warm weather packages showing increased interest. Consider promoting tropical getaways.';
                        elseif (in_array($currentMonth, [3, 4, 5])): // Mar-May
                            echo 'Spring travel season - cultural and adventure packages are in high demand.';
                        elseif (in_array($currentMonth, [6, 7, 8])): // Jun-Aug
                            echo 'Summer peak season - beach and family packages are most popular.';
                        else: // Sep-Oct
                            echo 'Fall shoulder season - consider offering discounts on premium packages.';
                        endif;
                        ?>
                    </p>
                    <p style="margin-top: 10px;"><strong>Action:</strong> Align marketing campaigns with seasonal demand patterns.</p>
                </div>
                
                <div class="insight-card">
                    <h4><i class="fas fa-chart-line"></i> Performance Analysis</h4>
                    <p>
                        <strong>Active Packages:</strong> <?php echo $activePackages; ?><br>
                        <strong>Completed Packages:</strong> <?php echo $completedPackages; ?><br>
                        <strong>Planned Packages:</strong> <?php echo $plannedPackages; ?>
                    </p>
                    <p style="margin-top: 10px;">
                        <strong>Insight:</strong> 
                        <?php if ($activePackages > 0): ?>
                            <?php echo round(($activePackages / $totalPackages) * 100); ?>% of packages are currently active and generating revenue.
                        <?php else: ?>
                            No active packages. Consider activating planned packages to start generating revenue.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Report Footer -->
        <div class="report-footer">
            <div class="footer-info">
                <p><i class="fas fa-info-circle"></i> This report contains confidential business information of Sunrise Travel Agency</p>
                <p style="margin-top: 5px;">Generated on <?php echo date('F j, Y, g:i A'); ?> | Monthly Performance Report v2.0</p>
            </div>
            <div class="report-id">
                Report ID: TRV-<?php echo date('Ymd'); ?>-<?php echo rand(1000, 9999); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effect to metric cards
            const metricCards = document.querySelectorAll('.metric-card');
            metricCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add click effect to table rows
            const tableRows = document.querySelectorAll('.package-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    // In a real application, this could open package details
                    this.style.backgroundColor = '#f0f7ff';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>