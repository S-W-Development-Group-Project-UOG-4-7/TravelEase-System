<?php
// generate_report.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Updated travel-specific report types with template file mapping
$reportTypes = [
    'package_performance' => [
        'label' => 'Package Performance Analysis',
        'template' => 'templates/package_performance.php'
    ],
    'customer_feedback' => [
        'label' => 'Customer Feedback Analysis', 
        'template' => 'feedback_insights.php'
    ],
    'partnership_analysis' => [
        'label' => 'Partnership Performance',
        'template' => 'templates/partnership_analysis.php'
    ]
];

// Report periods
$periods = [
    'last_week' => 'Last Week',
    'last_month' => 'Last Month',
    'last_quarter' => 'Last Quarter',
    'last_year' => 'Last Year',
    'custom' => 'Custom Range'
];

// Report formats
$formats = [
    'pdf' => 'PDF Document',
    'excel' => 'Excel Spreadsheet',
    'csv' => 'CSV Data',
    'ppt' => 'PowerPoint Presentation'
];

// Travel-specific report templates with template file mapping
$templates = [
    [
        'id' => 'package_performance',
        'name' => 'Package Performance Report',
        'description' => 'Travel package bookings, revenue, and conversion metrics',
        'icon' => 'suitcase-rolling',
        'sections' => 6,
        'estimated_size' => '3-5 MB',
        'template_file' => 'templates/package_performance.php'
    ],
    [
        'id' => 'partnership_analysis',
        'name' => 'Partnership Performance',
        'description' => 'Partner performance and revenue sharing analysis',
        'icon' => 'handshake',
        'sections' => 6,
        'estimated_size' => '2-3 MB',
        'template_file' => 'templates/partnership_analysis.php'
    ],
    [
        'id' => 'customer_feedback',
        'name' => 'Customer Feedback Report',
        'description' => 'Customer satisfaction and NPS analysis',
        'icon' => 'comments',
        'sections' => 5,
        'estimated_size' => '1-2 MB',
        'template_file' => 'templates/feedback_insights.php'
    ]
];

// If form is submitted, generate report
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportData = [
        'title' => $_POST['report_title'] ?? 'Marketing Report',
        'type' => $_POST['report_type'] ?? 'package_performance',
        'period' => $_POST['report_period'] ?? 'last_month',
        'format' => $_POST['format'] ?? 'pdf',
        'sections' => $_POST['sections'] ?? [],
        'custom_date_start' => $_POST['custom_start_date'] ?? '',
        'custom_date_end' => $_POST['custom_end_date'] ?? '',
        'email' => $_POST['email'] ?? '',
        'generated_by' => $managerName,
        'generated_at' => date('Y-m-d H:i:s'),
        'report_id' => 'TRV-' . date('Ymd') . '-' . rand(1000, 9999)
    ];
    
    // Store report data in session
    $_SESSION['current_report'] = $reportData;
    
    // Redirect to preview
    header('Location: generate_report.php?preview=1&type=' . urlencode($reportData['type']));
    exit;
}

// Handle preview request
if (isset($_GET['preview']) && isset($_GET['type'])) {
    $previewType = $_GET['type'];
    $templateFile = null;
    
    // Find template file
    foreach ($templates as $template) {
        if ($template['id'] === $previewType) {
            $templateFile = $template['template_file'];
            break;
        }
    }
    
    if (!$templateFile && isset($reportTypes[$previewType])) {
        $templateFile = $reportTypes[$previewType]['template'];
    }
    
    if ($templateFile && file_exists(__DIR__ . '/' . $templateFile)) {
        $previewData = $_SESSION['current_report'] ?? [
            'title' => 'Marketing Report',
            'period' => 'Last Month',
            'generated_by' => $managerName,
            'generated_at' => date('Y-m-d H:i:s'),
            'report_id' => 'TRV-' . date('Ymd') . '-' . rand(1000, 9999)
        ];
        
        // Load template content directly
        ob_start();
        extract($previewData);
        include $templateFile;
        $templateContent = ob_get_clean();
    }
}

$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard.php'],
        ['text' => 'Packages', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Partnerships', 'link' => 'partnership.php'],
        ['text' => 'Reports', 'link' => 'marketing_report.php']
    ],
    'Resources' => [
        ['text' => 'Help Center', 'link' => '#'],
        ['text' => 'API Documentation', 'link' => '#'],
        ['text' => 'Tutorials', 'link' => '#'],
        ['text' => 'Support Center', 'link' => '#']
    ],
    'Account' => [
        ['text' => 'Profile Settings', 'link' => 'marketing_profile.php'],
        ['text' => 'Notification Preferences', 'link' => '#'],
        ['text' => 'Team Management', 'link' => '#'],
        ['text' => 'Logout', 'link' => 'login.php']
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Generate Report | TravelEase Marketing</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- PDF Generation Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
    .mobile-menu {
      display: none;
    }
    .mobile-menu.open {
      display: block;
    }
    @keyframes slideIn {
      from { transform: translateX(-100%); }
      to { transform: translateX(0); }
    }
    @keyframes slideOut {
      from { transform: translateX(0); }
      to { transform: translateX(-100%); }
    }
    .mobile-menu.open > div:last-child {
      animation: slideIn 0.3s ease-out forwards;
    }
    .mobile-menu.closing > div:last-child {
      animation: slideOut 0.3s ease-in forwards;
    }
    /* PDF Preview Modal */
    .pdf-preview-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    .pdf-preview-modal.active {
      display: flex;
    }
    .pdf-preview-content {
      background: white;
      border-radius: 1rem;
      width: 90%;
      max-width: 900px;
      max-height: 90vh;
      overflow-y: auto;
    }
    .template-card.selected {
      border-color: #f59e0b;
      background-color: #fffbeb;
    }
    /* Animation for success message */
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    .animate-slideIn {
      animation: slideIn 0.3s ease-out forwards;
    }
    .step-content {
      display: block;
    }
    .step-content.hidden {
      display: none;
    }
  </style>
</head>
<body class="min-h-screen">
  <!-- Success Message -->
  <?php if (isset($_GET['success'])): ?>
    <div class="fixed top-24 right-6 z-50 p-4 bg-green-100 text-green-800 rounded-xl shadow-lg flex items-center gap-3 animate-slideIn">
      <i class="fas fa-check-circle text-green-600 text-lg"></i>
      <div>
        <p class="font-semibold">Report generated successfully!</p>
        <p class="text-sm">Report ID: <?= htmlspecialchars($_GET['report_id'] ?? 'TRV-' . date('Ymd') . '-' . rand(1000, 9999)) ?></p>
        <p class="text-xs">Format: <?= htmlspecialchars($_GET['format'] ?? 'PDF') ?></p>
      </div>
      <button onclick="this.parentElement.remove()" class="ml-4 text-green-600 hover:text-green-800">
        <i class="fas fa-times"></i>
      </button>
    </div>
  <?php endif; ?>

  <!-- PDF Preview Modal -->
  <div id="pdfPreviewModal" class="pdf-preview-modal">
    <div class="pdf-preview-content">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-gray-900">Report Preview</h3>
          <div class="flex gap-2">
            <button onclick="downloadTemplatePDF()" class="px-5 py-2.5 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-all flex items-center gap-2">
              <i class="fas fa-download"></i> Download PDF
            </button>
            <button onclick="closePreview()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        
        <!-- Report Preview Content -->
        <div id="reportPreview" class="bg-white p-8 border border-gray-200 rounded-lg">
          <?php if (isset($templateContent)): ?>
            <?= $templateContent ?>
          <?php else: ?>
            <!-- Default preview content -->
            <div id="defaultPreviewContent">
              <p class="text-gray-600">Preview will appear here after selecting a report type and clicking Preview.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Loading Bar -->
  <div class="loading-bar fixed top-0 left-0 w-0 h-1 bg-amber-500 z-50 transition-all duration-300"></div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
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
          <a href="marketing_dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
          <a href="marketing_campaigns.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
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
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
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
        <div class="flex items-center gap-3">
          <a href="marketing_dashboard.php" class="flex items-center gap-3 group">
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
          <a href="marketing_campaigns.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
            Packages
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_report.php" class="text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-alt text-xs text-amber-500 mr-2"></i>
            Reports
            <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
          </a>
          <a href="partnership.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-handshake text-xs text-amber-500 mr-2"></i>
            Partnerships
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_feedback.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
            Customer Feedback
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
          <span class="text-gradient">Generate Report</span>
        </h1>
        <p class="text-lg text-gray-700">Create custom travel marketing reports in minutes</p>
      </div>

      <!-- Report Generation Steps -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div id="step1-indicator" class="h-8 w-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-semibold mb-2">1</div>
              <span class="text-sm font-medium text-gray-900">Report Type</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div id="step2-indicator" class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">2</div>
              <span class="text-sm font-medium text-gray-700">Settings</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div id="step3-indicator" class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">3</div>
              <span class="text-sm font-medium text-gray-700">Generate</span>
            </div>
          </div>
        </div>
        <div class="progress-bar">
          <div id="progressFill" class="progress-fill" style="width: 33%"></div>
        </div>
      </div>

      <!-- Report Generation Form -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
        <form id="reportForm" class="space-y-6" method="POST" action="">
          <!-- Step 1: Report Type -->
          <div id="step1" class="step-content">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Select Report Type</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <?php foreach ($templates as $template): ?>
              <div class="p-4 rounded-xl border-2 border-amber-200 bg-white hover:border-amber-400 transition-colors cursor-pointer template-card"
                   data-id="<?= htmlspecialchars($template['id']) ?>"
                   data-name="<?= htmlspecialchars($template['name']) ?>"
                   data-template="<?= htmlspecialchars($template['template_file']) ?>"
                   onclick="selectTemplate(this)">
                <div class="flex items-center gap-3 mb-2">
                  <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                    <i class="fas fa-<?= htmlspecialchars($template['icon']) ?> text-white"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($template['name']) ?></h4>
                    <p class="text-xs text-gray-600"><?= htmlspecialchars($template['description']) ?></p>
                  </div>
                </div>
                <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                  <span><?= $template['sections'] ?> sections</span>
                  <span><?= $template['estimated_size'] ?></span>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Or select from predefined types:</label>
              <select id="reportType" name="report_type" class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" onchange="updatePreview()">
                <option value="">Select a report type</option>
                <?php foreach ($reportTypes as $value => $typeData): ?>
                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($typeData['label']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="flex justify-end">
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                Next: Settings <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- Step 2: Report Settings -->
          <div id="step2" class="step-content hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">2. Configure Report Settings</h3>

            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
                <select id="reportPeriod" name="report_period" class="w-full p-3 rounded-xl border border-amber-200 bg-white" onchange="toggleCustomDateRange()">
                  <?php foreach ($periods as $value => $label): ?>
                  <option value="<?= htmlspecialchars($value) ?>" <?= $value === 'last_month' ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="customDateRange" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="startDate" name="custom_start_date" class="w-full p-3 rounded-xl border border-amber-200 bg-white" onchange="updatePreview()">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="endDate" name="custom_end_date" class="w-full p-3 rounded-xl border border-amber-200 bg-white" onchange="updatePreview()">
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Format</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <?php foreach ($formats as $value => $label): ?>
                  <label class="flex items-center p-3 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50 transition-colors">
                    <input type="radio" name="format" value="<?= htmlspecialchars($value) ?>" class="text-amber-600 focus:ring-amber-500" 
                           <?= $value === 'pdf' ? 'checked' : '' ?> onchange="updatePreview()">
                    <span class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($label) ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Include Sections</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="executive_summary" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <span class="ml-2 text-sm text-gray-700">Executive Summary</span>
                  </label>
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="key_metrics" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <span class="ml-2 text-sm text-gray-700">Key Metrics</span>
                  </label>
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="charts_graphs" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <span class="ml-2 text-sm text-gray-700">Charts & Graphs</span>
                  </label>
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="detailed_tables" class="rounded text-amber-600 focus:ring-amber-500">
                    <span class="ml-2 text-sm text-gray-700">Detailed Data Tables</span>
                  </label>
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="recommendations" class="rounded text-amber-600 focus:ring-amber-500">
                    <span class="ml-2 text-sm text-gray-700">Recommendations</span>
                  </label>
                  <label class="flex items-center p-2 hover:bg-amber-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="sections[]" value="appendix" class="rounded text-amber-600 focus:ring-amber-500">
                    <span class="ml-2 text-sm text-gray-700">Appendix</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
              </button>
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                Next: Generate <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- Step 3: Generate & Preview -->
          <div id="step3" class="step-content hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">3. Generate & Preview</h3>
            
            <div class="bg-amber-50 rounded-xl p-6 mb-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-file-alt text-white text-lg"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900" id="previewTitle">Report Preview</h4>
                  <p class="text-sm text-gray-600">Review your report settings before generating</p>
                </div>
              </div>
              

              <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Report Type:</span>
                  <span class="font-medium text-gray-900" id="previewType">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Period:</span>
                  <span class="font-medium text-gray-900" id="previewPeriod">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Format:</span>
                  <span class="font-medium text-gray-900" id="previewFormat">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Report ID:</span>
                  <span class="font-medium text-gray-900">TRV-<?= date('Ymd') ?>-<?= rand(1000, 9999) ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Generated by:</span>
                  <span class="font-medium text-gray-900"><?= htmlspecialchars($managerName) ?></span>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Title *</label>
              <input type="text" id="reportTitle" name="report_title" placeholder="e.g., Package Performance Report - November 2024" 
                     class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" required oninput="updatePreview()">
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Notification (Optional)</label>
              <input type="email" name="email" placeholder="your-email@example.com" 
                     class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            <div class="flex justify-between">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
              </button>
              <div class="flex gap-3">
                <button type="button" onclick="previewReport()" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-all flex items-center gap-2">
                  <i class="fas fa-eye"></i> Preview
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all flex items-center gap-2">
                  <i class="fas fa-magic"></i> Generate Report
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Quick Templates -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Report Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button onclick="useQuickTemplate('weekly_summary')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fas fa-calendar-week text-green-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Weekly Summary</h4>
                <p class="text-xs text-gray-600">Last 7 days package performance</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">PDF format, 1-2 MB</div>
          </button>

          <button onclick="useQuickTemplate('monthly_performance')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-chart-bar text-blue-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Monthly Performance</h4>
                <p class="text-xs text-gray-600">Complete monthly travel analysis</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">Excel format, 3-5 MB</div>
          </button>

          <button onclick="useQuickTemplate('campaign_roi')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fas fa-bullhorn text-purple-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Campaign ROI</h4>
                <p class="text-xs text-gray-600">Travel campaign ROI analysis</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">PDF format, 2-4 MB</div>
          </button>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-amber-100 bg-amber-50">
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
            Marketing Dashboard for TravelEase luxury travel platform.
          </p>
          <div class="flex gap-3">
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
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

      <div class="pt-8 border-t border-amber-100 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
        <p>© <?= $currentYear ?> TravelEase Marketing Dashboard. All rights reserved.</p>
        <div class="flex items-center gap-4">
          <span>Premium Marketing Platform</span>
          <span class="flex items-center">
            <i class="fas fa-circle text-green-500 text-xs mr-1"></i> All Systems Operational
          </span>
        </div>
      </div>
    </div>
  </footer>

 <script>
    // Report Generation Functions
    let currentStep = 1;
    let selectedTemplateId = '';
    let selectedTemplateFile = '';

    function updateProgressBar() {
        const progressFill = document.getElementById('progressFill');
        if (progressFill) {
            const width = (currentStep / 3) * 100;
            progressFill.style.width = `${width}%`;
        }
    }

    function updateStepIndicators() {
        for (let i = 1; i <= 3; i++) {
            const indicator = document.getElementById(`step${i}-indicator`);
            const text = indicator?.parentElement?.querySelector('span');
            
            if (indicator && text) {
                if (i === currentStep) {
                    indicator.classList.remove('bg-amber-100', 'text-amber-800');
                    indicator.classList.add('bg-amber-500', 'text-white');
                    text.classList.remove('text-gray-700');
                    text.classList.add('text-gray-900');
                } else {
                    indicator.classList.remove('bg-amber-500', 'text-white');
                    indicator.classList.add('bg-amber-100', 'text-amber-800');
                    text.classList.remove('text-gray-900');
                    text.classList.add('text-gray-700');
                }
            }
        }
    }

    function showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(step => {
            step.classList.add('hidden');
        });
        
        // Show current step
        document.getElementById(`step${stepNumber}`).classList.remove('hidden');
        
        // Update current step
        currentStep = stepNumber;
        
        // Update UI
        updateProgressBar();
        updateStepIndicators();
        updatePreview();
    }

    function nextStep() {
        if (currentStep < 3) {
            // Validate current step
            if (currentStep === 1) {
                const reportType = document.getElementById('reportType').value;
                if (!reportType && !selectedTemplateId) {
                    alert('Please select a report type or template');
                    return;
                }
            }
            showStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }

    function selectTemplate(element) {
        // Remove selection from all cards
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected', 'border-amber-400');
            card.classList.add('border-amber-200');
        });
        
        // Select this card
        element.classList.add('selected', 'border-amber-400');
        element.classList.remove('border-amber-200');
        
        // Get template data
        selectedTemplateId = element.getAttribute('data-id');
        selectedTemplateFile = element.getAttribute('data-template');
        const templateName = element.getAttribute('data-name');
        
        // Update report type dropdown
        const reportTypeSelect = document.getElementById('reportType');
        reportTypeSelect.value = selectedTemplateId;
        
        // Update report title
        document.getElementById('reportTitle').value = templateName + ' - ' + getCurrentPeriodText();
        
        updatePreview();
    }

    function toggleCustomDateRange() {
        const periodSelect = document.getElementById('reportPeriod');
        const customRangeDiv = document.getElementById('customDateRange');
        
        if (periodSelect.value === 'custom') {
            customRangeDiv.classList.remove('hidden');
        } else {
            customRangeDiv.classList.add('hidden');
        }
        updatePreview();
    }

    // Update preview function
    function updatePreview() {
        // Get selected values
        const reportTypeSelect = document.getElementById('reportType');
        const reportTypeValue = reportTypeSelect.value;
        let reportType = '-';
        
        // Find the label for the selected value
        if (reportTypeValue) {
            <?php foreach ($reportTypes as $value => $typeData): ?>
            if (reportTypeValue === '<?= $value ?>') {
                reportType = '<?= $typeData['label'] ?>';
            }
            <?php endforeach; ?>
        }
        
        const periodSelect = document.getElementById('reportPeriod');
        const period = periodSelect.options[periodSelect.selectedIndex]?.text || '-';
        
        const format = document.querySelector('input[name="format"]:checked')?.value || 'pdf';
        const formatLabels = {
            'pdf': 'PDF Document',
            'excel': 'Excel Spreadsheet',
            'csv': 'CSV Data',
            'ppt': 'PowerPoint Presentation'
        };
        
        // Update preview elements
        document.getElementById('previewType').textContent = reportType;
        document.getElementById('previewPeriod').textContent = period;
        document.getElementById('previewFormat').textContent = formatLabels[format] || '-';
    }

    function getCurrentPeriodText() {
        const period = document.getElementById('reportPeriod').value;
        const now = new Date();
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        
        switch(period) {
            case 'last_week':
                return 'Last Week Report';
            case 'last_month':
                return monthNames[(now.getMonth() - 1 + 12) % 12] + ' ' + now.getFullYear();
            case 'last_quarter':
                const quarter = Math.floor(((now.getMonth() - 3 + 12) % 12) / 3) + 1;
                return 'Q' + quarter + ' ' + now.getFullYear();
            case 'last_year':
                return (now.getFullYear() - 1).toString();
            case 'custom':
                const start = document.getElementById('startDate').value;
                const end = document.getElementById('endDate').value;
                if (start && end) {
                    return new Date(start).toLocaleDateString() + ' to ' + new Date(end).toLocaleDateString();
                }
                return 'Custom Period';
            default:
                return monthNames[now.getMonth()] + ' ' + now.getFullYear();
        }
    }

    // Quick template functions
    function useQuickTemplate(templateType) {
        // Reset to step 1
        showStep(1);
        
        // Clear any existing selections
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected', 'border-amber-400');
            card.classList.add('border-amber-200');
        });
        
        // Set based on template type
        const reportTypeSelect = document.getElementById('reportType');
        const periodSelect = document.getElementById('reportPeriod');
        
        switch(templateType) {
            case 'weekly_summary':
                reportTypeSelect.value = 'package_performance';
                periodSelect.value = 'last_week';
                break;
            case 'monthly_performance':
                reportTypeSelect.value = 'package_performance';
                periodSelect.value = 'last_month';
                break;
            case 'campaign_roi':
                reportTypeSelect.value = 'package_performance';
                periodSelect.value = 'last_month';
                break;
        }
        
        // Set PDF format for quick templates
        document.querySelector('input[name="format"][value="pdf"]').checked = true;
        
        // Update preview
        updatePreview();
        
        // Auto-advance to step 3
        showStep(3);
    }

   function previewReport() {
    const reportTitle = document.getElementById('reportTitle').value;
    const reportType = document.getElementById('reportType').value;
    
    if (!reportTitle.trim()) {
        alert('Please enter a report title');
        document.getElementById('reportTitle').focus();
        return;
    }
    
    if (!reportType) {
        alert('Please select a report type');
        showStep(1);
        return;
    }
    
    // Submit form to generate preview
    const form = document.getElementById('reportForm');
    const previewInput = document.createElement('input');
    previewInput.type = 'hidden';
    previewInput.name = 'preview_only';
    previewInput.value = '1';
    form.appendChild(previewInput);
    
    // Set action to reload with preview parameter
    form.action = 'generate_report.php?preview=1&type=' + encodeURIComponent(reportType);
    form.submit();
}

    // Main PDF Download Function
async function downloadTemplatePDF() {
    const reportTitle = document.getElementById('reportTitle').value || 'TravelEase_Report';
    const reportType = document.getElementById('reportType').value || 'package_performance';
    const periodSelect = document.getElementById('reportPeriod');
    const period = periodSelect.options[periodSelect.selectedIndex]?.text || 'Last Month';
    
    // Create filename
    const fileName = reportTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.pdf';
    
    // Show loading
    const downloadButton = document.querySelector('button[onclick="downloadTemplatePDF()"]');
    const originalText = downloadButton.innerHTML;
    downloadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating PDF...';
    downloadButton.disabled = true;
    
    try {
        // Get the report preview content
        const previewContent = document.getElementById('reportPreview');
        
        // Method 1: Try to generate PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        
        // Add report content
        pdf.setFontSize(16);
        pdf.text(reportTitle, 20, 20);
        pdf.setFontSize(12);
        pdf.text('Report Type: ' + reportType, 20, 35);
        pdf.text('Period: ' + period, 20, 45);
        pdf.text('Generated by: <?= htmlspecialchars($managerName) ?>', 20, 55);
        pdf.text('Date: ' + new Date().toLocaleDateString(), 20, 65);
        
        // Save the PDF
        pdf.save(fileName);
        
        // Show success message
        showNotification('✅ PDF downloaded successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        
        // Method 2: Fallback to HTML download
        window.location.href = 'generate_pdf.php?title=' + encodeURIComponent(reportTitle) + 
                              '&type=' + encodeURIComponent(reportType) + 
                              '&period=' + encodeURIComponent(period);
        
        showNotification('Downloading HTML report...', 'info');
    } finally {
        // Reset button
        downloadButton.innerHTML = originalText;
        downloadButton.disabled = false;
    }
}

    function closePreview() {
        document.getElementById('pdfPreviewModal').classList.remove('active');
    }

    // Helper function to show notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-24 right-6 z-50 p-4 ${
            type === 'success' ? 'bg-green-100 text-green-800' : 
            type === 'error' ? 'bg-red-100 text-red-800' : 
            'bg-blue-100 text-blue-800'
        } rounded-xl shadow-lg flex items-center gap-3 animate-slideIn`;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} text-lg"></i>
            <div>
                <p class="font-semibold">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Mobile Menu Functions
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    
    let isMenuOpen = false;

    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.remove('closing');
        setTimeout(() => {
            mobileMenu.classList.add('open');
        }, 10);
        document.body.style.overflow = 'hidden';
        isMenuOpen = true;
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
        mobileMenu.classList.add('closing');
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('closing');
        }, 300);
        document.body.style.overflow = '';
        isMenuOpen = false;
    }

    // Initialize event listeners for mobile menu
    if (menuButton) {
        menuButton.addEventListener('click', openMobileMenu);
    }

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileMenuBackdrop) {
        mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
    }

    // Close menu when clicking on menu links
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });

    // Close menu on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isMenuOpen) {
            closeMobileMenu();
        }
    });

    // Close preview on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('pdfPreviewModal').classList.contains('active')) {
            closePreview();
        }
    });

    // Close preview when clicking outside
    document.getElementById('pdfPreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize progress bar
        updateProgressBar();
        updateStepIndicators();
        
        // Set default dates for custom date range
        const today = new Date().toISOString().split('T')[0];
        const oneMonthAgo = new Date();
        oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
        const oneMonthAgoStr = oneMonthAgo.toISOString().split('T')[0];
        
        document.getElementById('startDate').value = oneMonthAgoStr;
        document.getElementById('endDate').value = today;
        
        // Set default report title
        document.getElementById('reportTitle').value = 'Travel Marketing Report - ' + getCurrentPeriodText();
        
        // Initialize preview
        updatePreview();
        
        // Remove loading bar
        const loadingBar = document.querySelector('.loading-bar');
        if (loadingBar) {
            setTimeout(() => {
                loadingBar.style.width = '100%';
                setTimeout(() => {
                    loadingBar.style.opacity = '0';
                    setTimeout(() => loadingBar.remove(), 500);
                }, 300);
            }, 100);
        }
        
        // Check if we should open preview modal
        <?php if (isset($_GET['preview'])): ?>
        setTimeout(() => {
            document.getElementById('pdfPreviewModal').classList.add('active');
        }, 500);
        <?php endif; ?>
    });
</script>
</body>
</html>