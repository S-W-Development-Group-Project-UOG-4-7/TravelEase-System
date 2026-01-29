<?php
// feedback_insights.php
include_once 'header.php';

// Sample data
$agencyName = "Sunrise Travel Agency";
$reportTitle = "Monthly Feedback Insights Report";
$reportPeriod = "Month";
$dateRange = "November 1-30, 2023";
$preparedBy = "Sarah Miller";

// Executive Summary
$executiveSummary = "This month we received 128 customer feedback submissions with an average rating of 4.3/5. Positive feedback increased by 12% compared to last month, primarily praising guide expertise and accommodation quality. However, 15% of negative feedback mentioned transportation delays, requiring immediate attention.";

// Feedback Overview
$feedbackOverview = [
    'total_feedback' => 128,
    'average_rating' => 4.3,
    'positive_percentage' => 78,
    'negative_percentage' => 15,
    'neutral_percentage' => 7
];

// Rating Distribution
$ratingDistribution = [
    5 => 42,
    4 => 58,
    3 => 18,
    2 => 7,
    1 => 3
];

// Common Feedback Themes
$feedbackThemes = [
    'Service Quality' => 65,
    'Pricing' => 48,
    'Guide Behavior' => 52,
    'Accommodation' => 71,
    'Transport' => 39
];

// Issues & Suggestions
$issues = [
    'Transport delays reported by 12 customers',
    'Communication gaps during booking process',
    'Wi-Fi availability at some resorts'
];

$suggestions = [
    'Implement real-time transport tracking',
    'Provide more meal options for dietary restrictions',
    'Offer earlier check-in options'
];

$improvements = [
    'Partner with more reliable transport providers',
    'Create a pre-trip communication checklist',
    'Add Wi-Fi availability to package descriptions'
];

// Generate the report
generateReportHeader($agencyName, $reportTitle, $reportPeriod, $dateRange, $preparedBy);
?>

<!-- Executive Summary -->
<div class="executive-summary">
    <h3><i class="fas fa-chart-line"></i> Executive Summary</h3>
    <p><?php echo $executiveSummary; ?></p>
</div>

<!-- Feedback Overview -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-comments"></i>
        <h3>Feedback Overview</h3>
    </div>
    
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-label">Total Feedback Received</div>
            <div class="metric-value"><?php echo $feedbackOverview['total_feedback']; ?></div>
        </div>
        
        <div class="metric-card">
            <div class="metric-label">Average Rating</div>
            <div class="metric-value"><?php echo $feedbackOverview['average_rating']; ?>/5</div>
            <div class="rating-stars">
                <?php
                $avgRating = $feedbackOverview['average_rating'];
                $fullStars = floor($avgRating);
                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                
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
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-label">Positive Feedback</div>
            <div class="metric-value"><?php echo $feedbackOverview['positive_percentage']; ?>%</div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $feedbackOverview['positive_percentage']; ?>%;"></div>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-label">Negative Feedback</div>
            <div class="metric-value"><?php echo $feedbackOverview['negative_percentage']; ?>%</div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $feedbackOverview['negative_percentage']; ?>%; background: linear-gradient(90deg, #ef4444, #dc2626);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rating Distribution -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-star"></i>
        <h3>Rating Distribution</h3>
    </div>
    
    <div style="background: #f9fbfe; padding: 20px; border-radius: 8px;">
        <?php
        $totalRatings = array_sum($ratingDistribution);
        foreach ($ratingDistribution as $stars => $count):
            $percentage = ($count / $totalRatings) * 100;
        ?>
        <div class="progress-container" style="margin-bottom: 15px;">
            <div class="progress-label">
                <span>
                    <?php 
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $stars) {
                            echo '<i class="fas fa-star" style="color: #fbbf24;"></i>';
                        } else {
                            echo '<i class="far fa-star" style="color: #d1d5db;"></i>';
                        }
                    }
                    ?>
                </span>
                <span><?php echo $count; ?> feedback (<?php echo round($percentage, 1); ?>%)</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%; 
                    <?php 
                    if ($stars >= 4) {
                        echo 'background: linear-gradient(90deg, #10b981, #059669);';
                    } elseif ($stars == 3) {
                        echo 'background: linear-gradient(90deg, #f59e0b, #d97706);';
                    } else {
                        echo 'background: linear-gradient(90deg, #ef4444, #dc2626);';
                    }
                    ?>">
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Common Feedback Themes -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-th-large"></i>
        <h3>Common Feedback Themes</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php
        $maxCount = max($feedbackThemes);
        foreach ($feedbackThemes as $theme => $count):
            $percentage = ($count / $maxCount) * 100;
        ?>
        <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <strong><?php echo $theme; ?></strong>
                <span><?php echo $count; ?> mentions</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Issues & Suggestions -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>Issues & Suggestions</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <div>
            <h4 style="color: #ef4444; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-exclamation-circle"></i> Repeated Complaints
            </h4>
            <ul style="list-style-type: none;">
                <?php foreach ($issues as $issue): ?>
                <li style="padding: 8px 0; border-bottom: 1px dashed #eee; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="fas fa-times-circle" style="color: #ef4444; margin-top: 4px;"></i>
                    <span><?php echo $issue; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div>
            <h4 style="color: #3b82f6; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-lightbulb"></i> Customer Suggestions
            </h4>
            <ul style="list-style-type: none;">
                <?php foreach ($suggestions as $suggestion): ?>
                <li style="padding: 8px 0; border-bottom: 1px dashed #eee; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="fas fa-lightbulb" style="color: #fbbf24; margin-top: 4px;"></i>
                    <span><?php echo $suggestion; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Improvement Recommendations -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-tasks"></i>
        <h3>Improvement Recommendations</h3>
    </div>
    
    <div class="insight-card">
        <h4><i class="fas fa-check-circle"></i> Areas to Improve</h4>
        <ul style="list-style-type: none; margin-top: 10px;">
            <?php foreach ($improvements as $improvement): ?>
            <li style="padding: 8px 0; display: flex; align-items: flex-start; gap: 10px;">
                <i class="fas fa-check" style="color: #10b981; margin-top: 4px;"></i>
                <span><?php echo $improvement; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="report-footer">
    <p>Report generated on <?php echo date('F j, Y'); ?> | Sunrise Travel Agency Feedback Reports</p>
</div>

</div><!-- Close report-content -->
</div><!-- Close report-container -->
</body>
</html>