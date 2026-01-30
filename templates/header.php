<?php
// header.php - Common report header
function generateReportHeader($agencyName, $reportTitle, $reportPeriod, $dateRange, $preparedBy = '') {
    $generatedDate = date('F j, Y');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($reportTitle); ?> - <?php echo htmlspecialchars($agencyName); ?></title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="report-container">
            <div class="report-header">
                <div class="agency-info">
                    <div class="logo-placeholder">
                        <i class="fas fa-globe-americas"></i>
                        <h1><?php echo htmlspecialchars($agencyName); ?></h1>
                    </div>
                </div>
                <div class="report-title-section">
                    <h2><?php echo htmlspecialchars($reportTitle); ?></h2>
                    <div class="report-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Report Period: <?php echo htmlspecialchars($reportPeriod); ?> (<?php echo htmlspecialchars($dateRange); ?>)</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-check"></i>
                            <span>Generated: <?php echo $generatedDate; ?></span>
                        </div>
                        <?php if (!empty($preparedBy)): ?>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Prepared by: <?php echo htmlspecialchars($preparedBy); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="report-content">
    <?php
}
?>