<?php
// generate_pdf.php - HTML to PDF download with proper headers
session_start();

// Get parameters
$title = $_GET['title'] ?? $_POST['title'] ?? 'TravelEase Report';
$type = $_GET['type'] ?? $_POST['type'] ?? 'package_performance';
$period = $_GET['period'] ?? $_POST['period'] ?? 'Last Month';

// Map report types to template files
$templateMap = [
    'package_performance' => 'templates/package_performance.php',
    'partnership_analysis' => 'templates/partnership_analysis.php',
    'customer_feedback' => 'templates/feedback_insights.php'
];

// Get the template file
$templateFile = $templateMap[$type] ?? 'templates/package_performance.php';

// Check if template file exists
if (!file_exists(__DIR__ . '/' . $templateFile)) {
    // If template doesn't exist, create a simple one
    $htmlContent = createSimpleReport($title, $type, $period);
} else {
    // Prepare data for template
    $reportData = [
        'title' => $title,
        'type' => $type,
        'period' => $period,
        'generated_by' => $_SESSION['full_name'] ?? 'Marketing Manager',
        'generated_at' => date('Y-m-d H:i:s'),
        'report_id' => 'TRV-' . date('Ymd') . '-' . rand(1000, 9999),
        'date_range' => $period
    ];
    
    // Extract data for template
    extract($reportData);
    
    // Start output buffering
    ob_start();
    include $templateFile;
    $htmlContent = ob_get_clean();
}

// Create a simple HTML file for download
$filename = preg_replace('/[^a-z0-9]/i', '_', $title) . '.html';
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($htmlContent));
echo $htmlContent;
exit;

function createSimpleReport($title, $type, $period) {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>' . htmlspecialchars($title) . '</title>
        <style>
            body { font-family: Arial; padding: 20px; }
            h1 { color: #f59e0b; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 10px; border: 1px solid #ddd; }
        </style>
    </head>
    <body>
        <h1>' . htmlspecialchars($title) . '</h1>
        <p><strong>Report Type:</strong> ' . htmlspecialchars($type) . '</p>
        <p><strong>Period:</strong> ' . htmlspecialchars($period) . '</p>
        <p><strong>Generated:</strong> ' . date('Y-m-d H:i:s') . '</p>
        <p><strong>Note:</strong> Template file not found. This is a basic report.</p>
    </body>
    </html>';
}
?>