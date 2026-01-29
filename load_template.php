<?php
// load_template.php
session_start();
require_once 'generate_report.php'; // Include your main file to get functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_GET['type'] ?? $_POST['report_type'] ?? '';
    
    // Map report types to template files
    $templateMap = [
        'package_performance' => 'package_performance.php',
        'partnership_analysis' => 'partnership_analysis.php',
        'customer_feedback' => 'feedback_insights.php',
        'campaign_roi' => 'campaign_roi.php',
        'seasonal_forecast' => 'seasonal_forecast.php',
        'channel_performance' => 'channel_performance.php'
    ];
    
    $templateFile = $templateMap[$reportType] ?? null;
    
    if ($templateFile && file_exists($templateFile)) {
        // Prepare report data
        $reportData = [
            'title' => $_POST['report_title'] ?? 'Marketing Report',
            'type' => $reportType,
            'period' => $_POST['report_period'] ?? 'Last Month',
            'format' => $_POST['format'] ?? 'pdf',
            'sections' => $_POST['sections'] ?? [],
            'custom_date_start' => $_POST['custom_start_date'] ?? '',
            'custom_date_end' => $_POST['custom_end_date'] ?? '',
            'email' => $_POST['email'] ?? '',
            'generated_by' => $_SESSION['full_name'] ?? 'Marketing Manager',
            'generated_at' => date('Y-m-d H:i:s'),
            'report_id' => 'TRV-' . date('Ymd') . '-' . rand(1000, 9999)
        ];
        
        // Load template content
        $content = loadTemplateContent($templateFile, $reportData);
        
        if ($content) {
            echo $content;
        } else {
            echo '<div style="padding: 20px; text-align: center; color: #666;">Template content could not be loaded.</div>';
        }
    } else {
        echo '<div style="padding: 20px; text-align: center; color: #666;">Template file not found: ' . htmlspecialchars($templateFile ?? 'Unknown') . '</div>';
    }
}
?>