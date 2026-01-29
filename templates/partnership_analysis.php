<?php
// partnership_analysis.php
include_once 'header.php';

// Sample data
$agencyName = "Sunrise Travel Agency";
$reportTitle = "Monthly Partnership Analysis Report";
$reportPeriod = "Month";
$dateRange = "November 1-30, 2023";
$preparedBy = "Michael Chen";

// Executive Summary
$executiveSummary = "This month, 85% of our partners performed within expected parameters, with 'Oceanview Resorts' and 'Global Airlines' being top performers. However, 'City Transit Co.' showed concerning reliability issues with 12% delayed responses. Contract renewals are due for 3 partners next month.";

// Partner Data
$partners = [
    [
        'name' => 'Oceanview Resorts',
        'type' => 'Hotel',
        'contract_period' => 'Jan 2023 - Dec 2024',
        'bookings_handled' => 89,
        'revenue_contribution' => '$28,500',
        'customer_rating' => 4.7,
        'response_time' => '2.4 hours',
        'reliability_score' => 95
    ],
    [
        'name' => 'Global Airlines',
        'type' => 'Airline',
        'contract_period' => 'Mar 2023 - Feb 2025',
        'bookings_handled' => 124,
        'revenue_contribution' => '$42,800',
        'customer_rating' => 4.5,
        'response_time' => '1.8 hours',
        'reliability_score' => 92
    ],
    [
        'name' => 'Mountain Guides Co.',
        'type' => 'Guide',
        'contract_period' => 'May 2023 - Apr 2024',
        'bookings_handled' => 45,
        'revenue_contribution' => '$9,200',
        'customer_rating' => 4.8,
        'response_time' => '3.1 hours',
        'reliability_score' => 88
    ],
    [
        'name' => 'City Transit Co.',
        'type' => 'Transport',
        'contract_period' => 'Feb 2023 - Jan 2024',
        'bookings_handled' => 67,
        'revenue_contribution' => '$5,800',
        'customer_rating' => 3.9,
        'response_time' => '6.5 hours',
        'reliability_score' => 72
    ],
    [
        'name' => 'Adventure Meals',
        'type' => 'Catering',
        'contract_period' => 'Jun 2023 - May 2024',
        'bookings_handled' => 38,
        'revenue_contribution' => '$3,400',
        'customer_rating' => 4.2,
        'response_time' => '4.2 hours',
        'reliability_score' => 85
    ],
];

// Risk Analysis
$riskAnalysis = [
    'late_responses' => [
        'City Transit Co.' => 12,
        'Adventure Meals' => 3
    ],
    'customer_complaints' => [
        'City Transit Co.' => 8,
        'Global Airlines' => 2
    ],
    'contract_issues' => [
        'City Transit Co.' => 'Contract ends Jan 2024',
        'Mountain Guides Co.' => 'Contract ends Apr 2024'
    ]
];

$topPerformer = 'Oceanview Resorts';
$lowPerformer = 'City Transit Co.';

// Generate the report
generateReportHeader($agencyName, $reportTitle, $reportPeriod, $dateRange, $preparedBy);
?>

<!-- Executive Summary -->
<div class="executive-summary">
    <h3><i class="fas fa-chart-line"></i> Executive Summary</h3>
    <p><?php echo $executiveSummary; ?></p>
</div>

<!-- Partner Overview -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-handshake"></i>
        <h3>Partner Overview</h3>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Partner Name</th>
                <th>Type</th>
                <th>Contract Period</th>
                <th>Bookings Handled</th>
                <th>Revenue Contribution</th>
                <th>Customer Rating</th>
                <th>Response Time</th>
                <th>Reliability Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($partners as $partner): 
                $reliabilityClass = $partner['reliability_score'] >= 90 ? 'status-active' : 
                                   ($partner['reliability_score'] >= 80 ? '' : 'status-inactive');
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($partner['name']); ?></strong></td>
                <td><?php echo $partner['type']; ?></td>
                <td><?php echo $partner['contract_period']; ?></td>
                <td><?php echo $partner['bookings_handled']; ?></td>
                <td><?php echo $partner['revenue_contribution']; ?></td>
                <td>
                    <div class="rating-stars">
                        <?php
                        $fullStars = floor($partner['customer_rating']);
                        $hasHalfStar = ($partner['customer_rating'] - $fullStars) >= 0.5;
                        
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
                        <span style="margin-left: 5px; color: #666;"><?php echo $partner['customer_rating']; ?></span>
                    </div>
                </td>
                <td><?php echo $partner['response_time']; ?></td>
                <td>
                    <span class="<?php echo $reliabilityClass; ?>">
                        <?php echo $partner['reliability_score']; ?>%
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Risk Analysis -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>Risk Analysis</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-top: 20px;">
        <div>
            <h4 style="color: #f59e0b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-clock"></i> Late Responses
            </h4>
            <?php foreach ($riskAnalysis['late_responses'] as $partner => $count): ?>
            <div style="background: #fffbeb; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #f59e0b;">
                <div style="display: flex; justify-content: space-between;">
                    <strong><?php echo $partner; ?></strong>
                    <span><?php echo $count; ?> late responses</span>
                </div>
                <div class="progress-container" style="margin-top: 8px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo min($count * 10, 100); ?>%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div>
            <h4 style="color: #ef4444; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-times"></i> Customer Complaints
            </h4>
            <?php foreach ($riskAnalysis['customer_complaints'] as $partner => $count): ?>
            <div style="background: #fef2f2; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #ef4444;">
                <div style="display: flex; justify-content: space-between;">
                    <strong><?php echo $partner; ?></strong>
                    <span><?php echo $count; ?> complaints</span>
                </div>
                <div class="progress-container" style="margin-top: 8px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo min($count * 15, 100); ?>%; background: linear-gradient(90deg, #ef4444, #dc2626);"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div>
            <h4 style="color: #8b5cf6; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-contract"></i> Contract Issues
            </h4>
            <?php foreach ($riskAnalysis['contract_issues'] as $partner => $issue): ?>
            <div style="background: #f5f3ff; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #8b5cf6;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong><?php echo $partner; ?></strong>
                    <span style="font-size: 0.9rem; color: #8b5cf6;">
                        <i class="fas fa-calendar-exclamation"></i>
                    </span>
                </div>
                <p style="margin-top: 8px; color: #666; font-size: 0.9rem;"><?php echo $issue; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Top & Low Performing Partners -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-trophy"></i>
        <h3>Top & Low Performing Partners</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 30px;">
        <div class="insight-card">
            <h4><i class="fas fa-crown"></i> Top Performer of the Period</h4>
            <p><strong><?php echo $topPerformer; ?></strong> achieved the highest reliability score (95%) and handled 89 bookings with a customer rating of 4.7/5.</p>
            <div style="margin-top: 15px; display: flex; gap: 20px;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #10b981;">95%</div>
                    <div style="font-size: 0.85rem; color: #666;">Reliability</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #10b981;">4.7</div>
                    <div style="font-size: 0.85rem; color: #666;">Rating</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #10b981;">89</div>
                    <div style="font-size: 0.85rem; color: #666;">Bookings</div>
                </div>
            </div>
        </div>
        
        <div class="insight-card danger">
            <h4><i class="fas fa-exclamation-circle"></i> Partners Needing Improvement</h4>
            <p><strong><?php echo $lowPerformer; ?></strong> shows concerning metrics with 72% reliability score, 12 late responses, and 8 customer complaints this month.</p>
            <div style="margin-top: 15px; display: flex; gap: 20px;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #ef4444;">72%</div>
                    <div style="font-size: 0.85rem; color: #666;">Reliability</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #ef4444;">8</div>
                    <div style="font-size: 0.85rem; color: #666;">Complaints</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 700; color: #ef4444;">6.5h</div>
                    <div style="font-size: 0.85rem; color: #666;">Avg. Response</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations -->
<div class="section">
    <div class="section-header">
        <i class="fas fa-clipboard-check"></i>
        <h3>Action Items</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <div style="background: #f0f7ff; padding: 20px; border-radius: 8px;">
            <h4 style="color: #1a6fc4; margin-bottom: 10px;">Immediate Actions</h4>
            <ul style="list-style-type: none;">
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Schedule meeting with City Transit Co.</li>
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Review contract renewal terms</li>
            </ul>
        </div>
        
        <div style="background: #f0f7ff; padding: 20px; border-radius: 8px;">
            <h4 style="color: #1a6fc4; margin-bottom: 10px;">Short-term Goals</h4>
            <ul style="list-style-type: none;">
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Establish backup transport partners</li>
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Implement partner performance dashboard</li>
            </ul>
        </div>
        
        <div style="background: #f0f7ff; padding: 20px; border-radius: 8px;">
            <h4 style="color: #1a6fc4; margin-bottom: 10px;">Long-term Strategy</h4>
            <ul style="list-style-type: none;">
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Develop partner training program</li>
                <li style="padding: 5px 0;"><i class="fas fa-arrow-right" style="color: #1a6fc4; margin-right: 8px;"></i>Create tiered partnership model</li>
            </ul>
        </div>
    </div>
</div>

<div class="report-footer">
    <p>Report generated on <?php echo date('F j, Y'); ?> | Sunrise Travel Agency Partnership Reports</p>
</div>

</div><!-- Close report-content -->
</div><!-- Close report-container -->
</body>
</html>