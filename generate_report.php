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

// Report types
$reportTypes = [
    'performance' => 'Performance Overview',
    'campaign' => 'Campaign Analysis',
    'leads' => 'Lead Generation',
    'revenue' => 'Revenue Analysis',
    'social' => 'Social Media',
    'email' => 'Email Marketing'
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
    'html' => 'HTML Report'
];

// Report templates
$templates = [
    [
        'name' => 'Monthly Marketing Report',
        'description' => 'Comprehensive monthly performance with charts',
        'icon' => 'calendar'
    ],
    [
        'name' => 'Campaign ROI Report',
        'description' => 'Detailed ROI analysis for campaigns',
        'icon' => 'chart-line'
    ],
    [
        'name' => 'Lead Conversion Report',
        'description' => 'Lead sources and conversion funnel',
        'icon' => 'users'
    ]
];
$footerLinks = [
    'Marketing Tools' => [
        ['text' => 'Dashboard', 'link' => 'marketing_dashboard1.php'],
        ['text' => 'Campaigns', 'link' => 'marketing_campaigns.php'],
        ['text' => 'Lead Management', 'link' => 'marketing_leads.php'],
        ['text' => 'Report Generator', 'link' => 'marketing_report.php']
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
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
    }

@keyframes slideOut {
  from {
    transform: translateX(0);
  }
  to {
    transform: translateX(-100%);
  }
    }

    .mobile-menu.open > div:last-child {
  animation: slideIn 0.3s ease-out forwards;
    }

    .mobile-menu.closing > div:last-child {
  animation: slideOut 0.3s ease-in forwards;
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
          <a href="marketing_dashboard1.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
          <a href="marketing_campaigns.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-bullhorn w-6 text-center"></i>
            Packages
          </a>
         <!--- <a href="marketing_leads.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Leads
          </a>--->
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
          </a>
          <a href="partnership.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-handshake w-6 text-center"></i>
            Partnerships
          </a>
           <a href="marketing_feedback.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
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
          <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-gray-700 hover:bg-amber-50 transition-all">
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
          <a href="#" class="flex items-center gap-3 group">
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
          <a href="marketing_dashboard1.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
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
          <!---<a href="marketing_leads.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-amber-500 mr-2"></i>
            Leads
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>--->
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
           <a href="marketing_feedback.php" class="text-amber-600 transition-all duration-300 relative group">
                        <i class="fas fa-user-check text-xs text-amber-500 mr-2"></i>
                        Customer Feedback
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-amber-500"></span>
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
        <p class="text-lg text-gray-700">Create custom marketing reports in minutes</p>
      </div>

      <!-- Report Generation Steps -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-semibold mb-2">1</div>
              <span class="text-sm font-medium text-gray-900">Report Type</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">2</div>
              <span class="text-sm font-medium text-gray-700">Settings</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">3</div>
              <span class="text-sm font-medium text-gray-700">Generate</span>
            </div>
          </div>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: 33%"></div>
        </div>
      </div>

      <!-- Report Generation Form -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
        <form id="reportForm" class="space-y-6">
          <!-- Step 1: Report Type -->
          <div id="step1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Select Report Type</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <?php foreach ($templates as $template): ?>
              <div class="p-4 rounded-xl border-2 border-amber-200 bg-white hover:border-amber-400 transition-colors cursor-pointer template-card">
                <div class="flex items-center gap-3 mb-2">
                  <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                    <i class="fas fa-<?= htmlspecialchars($template['icon']) ?> text-white"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($template['name']) ?></h4>
                    <p class="text-xs text-gray-600"><?= htmlspecialchars($template['description']) ?></p>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Or select from predefined types:</label>
              <select id="reportType" class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Select a report type</option>
                <?php foreach ($reportTypes as $value => $label): ?>
                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="flex justify-end">
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                Next: Settings <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Step 2: Report Settings -->
          <div id="step2" class="hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">2. Configure Report Settings</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Period</label>
                <select id="reportPeriod" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                  <?php foreach ($periods as $value => $label): ?>
                  <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="customDateRange" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="startDate" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="endDate" class="w-full p-3 rounded-xl border border-amber-200 bg-white">
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Report Format</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <?php foreach ($formats as $value => $label): ?>
                  <label class="flex items-center p-3 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                    <input type="radio" name="format" value="<?= htmlspecialchars($value) ?>" class="text-amber-600 focus:ring-amber-500" 
                           <?= $value === 'pdf' ? 'checked' : '' ?>>
                    <span class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($label) ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Include Sections</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Executive Summary</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Key Metrics</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Charts & Graphs</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Detailed Data Tables</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Recommendations</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Appendix</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
              </button>
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                Next: Generate <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Step 3: Generate & Preview -->
          <div id="step3" class="hidden">
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
                  <span class="text-gray-600">Estimated Size:</span>
                  <span class="font-medium text-gray-900">2-5 MB</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Generation Time:</span>
                  <span class="font-medium text-gray-900">30-60 seconds</span>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Report Name</label>
              <input type="text" id="reportName" placeholder="e.g., Marketing Report - November 2024" 
                     class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Notification (Optional)</label>
              <input type="email" placeholder="your-email@example.com" 
                     class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            <div class="flex justify-between">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
              </button>
              <button type="button" onclick="generateReport()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-magic mr-2"></i> Generate Report
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Quick Templates -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Report Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button onclick="useQuickTemplate('weekly')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fas fa-calendar-week text-green-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Weekly Summary</h4>
                <p class="text-xs text-gray-600">Last 7 days performance</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">PDF format, 1-2 MB</div>
          </button>

          <button onclick="useQuickTemplate('monthly')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-chart-bar text-blue-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Monthly Performance</h4>
                <p class="text-xs text-gray-600">Complete monthly analysis</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">Excel format, 3-5 MB</div>
          </button>

          <button onclick="useQuickTemplate('campaign')" class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fas fa-bullhorn text-purple-600"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900">Campaign ROI</h4>
                <p class="text-xs text-gray-600">Detailed ROI analysis</p>
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
        <!-- Updated Footer Logo -->
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

    function toggleMobileMenu() {
      if (isMenuOpen) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    }

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

    window.addEventListener('load', () => {
      const loadingBar = document.querySelector('.loading-bar');
      if (loadingBar) {
        loadingBar.style.opacity = '0';
        setTimeout(() => {
          loadingBar.remove();
        }, 500);
      }
      
      initializeCharts();
    });
// Report Generation Functions
let currentStep = 1;

function updateProgressBar() {
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        const width = (currentStep / 3) * 100;
        progressFill.style.width = `${width}%`;
    }
}

function updateStepIndicators() {
    // Update step numbers
    const steps = document.querySelectorAll('.flex.flex-col.items-center');
    steps.forEach((step, index) => {
        const numberDiv = step.querySelector('div:first-child');
        const textSpan = step.querySelector('span:last-child');
        
        if (index + 1 === currentStep) {
            numberDiv.classList.remove('bg-amber-100', 'text-amber-800');
            numberDiv.classList.add('bg-amber-500', 'text-white');
            textSpan.classList.remove('text-gray-700');
            textSpan.classList.add('text-gray-900');
        } else {
            numberDiv.classList.remove('bg-amber-500', 'text-white');
            numberDiv.classList.add('bg-amber-100', 'text-amber-800');
            textSpan.classList.remove('text-gray-900');
            textSpan.classList.add('text-gray-700');
        }
    });
}

function showStep(stepNumber) {
    // Hide all steps
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.add('hidden');
    
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
        showStep(currentStep + 1);
    }
}

function prevStep() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
}

// Handle custom date range visibility
document.getElementById('reportPeriod').addEventListener('change', function() {
    const customRangeDiv = document.getElementById('customDateRange');
    if (this.value === 'custom') {
        customRangeDiv.classList.remove('hidden');
    } else {
        customRangeDiv.classList.add('hidden');
    }
    updatePreview();
});

// Template card selection
document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function() {
        // Remove selection from all cards
        document.querySelectorAll('.template-card').forEach(c => {
            c.classList.remove('border-amber-400');
            c.classList.add('border-amber-200');
        });
        
        // Select this card
        this.classList.remove('border-amber-200');
        this.classList.add('border-amber-400');
        
        // Update report type dropdown
        const title = this.querySelector('h4').textContent;
        const reportTypeSelect = document.getElementById('reportType');
        let found = false;
        
        for (let option of reportTypeSelect.options) {
            if (option.text === title) {
                reportTypeSelect.value = option.value;
                found = true;
                break;
            }
        }
        
        if (!found) {
            reportTypeSelect.value = 'performance'; // Default fallback
        }
        
        updatePreview();
    });
});

// Update preview function
function updatePreview() {
    // Get selected values
    const reportTypeSelect = document.getElementById('reportType');
    const reportType = reportTypeSelect.options[reportTypeSelect.selectedIndex].text;
    
    const periodSelect = document.getElementById('reportPeriod');
    const period = periodSelect.options[periodSelect.selectedIndex].text;
    
    const format = document.querySelector('input[name="format"]:checked')?.value || 'pdf';
    const formatLabels = {
        'pdf': 'PDF Document',
        'excel': 'Excel Spreadsheet',
        'csv': 'CSV Data',
        'html': 'HTML Report'
    };
    
    // Update preview elements
    document.getElementById('previewType').textContent = reportType || '-';
    document.getElementById('previewPeriod').textContent = period || '-';
    document.getElementById('previewFormat').textContent = formatLabels[format] || '-';
    
    // Auto-generate report name if empty
    if (!document.getElementById('reportName').value && reportType && period) {
        const now = new Date();
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const month = monthNames[now.getMonth()];
        const year = now.getFullYear();
        document.getElementById('reportName').value = `${reportType} - ${month} ${year}`;
    }
}

// Quick template functions
function useQuickTemplate(templateType) {
    // Reset to step 1
    showStep(1);
    
    // Clear any existing selections
    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('border-amber-400');
        card.classList.add('border-amber-200');
    });
    
    // Set based on template type
    const reportTypeSelect = document.getElementById('reportType');
    const periodSelect = document.getElementById('reportPeriod');
    
    switch(templateType) {
        case 'weekly':
            reportTypeSelect.value = 'performance';
            periodSelect.value = 'last_week';
            break;
        case 'monthly':
            reportTypeSelect.value = 'performance';
            periodSelect.value = 'last_month';
            break;
        case 'campaign':
            reportTypeSelect.value = 'campaign';
            periodSelect.value = 'last_month';
            break;
    }
    
    // Set PDF format for quick templates
    document.querySelector('input[name="format"][value="pdf"]').checked = true;
    
    // Update preview
    updatePreview();
    
    // Auto-advance to step 3
    setTimeout(() => {
        showStep(3);
    }, 300);
}

// Generate report function
function generateReport() {
    // Get form values
    const reportName = document.getElementById('reportName').value;
    const reportType = document.getElementById('reportType').value;
    const reportTypeText = document.getElementById('reportType').options[document.getElementById('reportType').selectedIndex].text;
    const period = document.getElementById('reportPeriod').value;
    const periodText = document.getElementById('reportPeriod').options[document.getElementById('reportPeriod').selectedIndex].text;
    const format = document.querySelector('input[name="format"]:checked')?.value;
    
    // Validation
    if (!reportName.trim()) {
        alert('Please enter a report name');
        document.getElementById('reportName').focus();
        return;
    }
    
    if (!reportType) {
        alert('Please select a report type');
        showStep(1);
        return;
    }
    
    // Show loading/confirmation
    const generateButton = document.querySelector('button[onclick="generateReport()"]');
    const originalText = generateButton.innerHTML;
    
    generateButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
    generateButton.disabled = true;
    
    // Simulate generation process
    setTimeout(() => {
        // Success message
        alert(`✅ Report "${reportName}" generated successfully!\n\n` +
              `Type: ${reportTypeText}\n` +
              `Period: ${periodText}\n` +
              `Format: ${format.toUpperCase()}\n\n` +
              `The report will be available for download shortly.`);
        
        // Reset button
        generateButton.innerHTML = originalText;
        generateButton.disabled = false;
        
        // Optionally reset form
        // showStep(1);
        // document.getElementById('reportForm').reset();
        
    }, 2000);
}

// Initialize event listeners for format changes
document.querySelectorAll('input[name="format"]').forEach(radio => {
    radio.addEventListener('change', updatePreview);
});

// Initialize event listeners for type changes
document.getElementById('reportType').addEventListener('change', updatePreview);

// Initialize event listeners for period changes
document.getElementById('reportPeriod').addEventListener('change', updatePreview);

// Initialize event listeners for report name changes
document.getElementById('reportName').addEventListener('input', updatePreview);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    
    // Set today's date as default for custom date range
    const today = new Date().toISOString().split('T')[0];
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
    const oneWeekAgoStr = oneWeekAgo.toISOString().split('T')[0];
    
    document.getElementById('startDate').value = oneWeekAgoStr;
    document.getElementById('endDate').value = today;
    
    // Add event listeners for custom date range
    document.getElementById('startDate').addEventListener('change', updatePreview);
    document.getElementById('endDate').addEventListener('change', updatePreview);
});

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        // Don't prevent default for mobile menu links
        if (this.getAttribute('href') === '#') return;
        
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          
          // Close mobile menu if open
          if (isMenuOpen) {
            closeMobileMenu();
          }
        }
      });
    });

    function initializeCharts() {
      const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
      if (revenueCtx) {
        new Chart(revenueCtx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
              label: 'Revenue ($)',
              data: [185000, 210000, 195000, 245000, 265000, 284000, 275000],
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: false, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
              x: { grid: { display: false } }
            }
          }
        });
      }

      const trafficCtx = document.getElementById('trafficChart')?.getContext('2d');
      if (trafficCtx) {
        new Chart(trafficCtx, {
          type: 'doughnut',
          data: {
            labels: ['Organic Search', 'Social Media', 'Email', 'Direct', 'Referral'],
            datasets: [{
              data: [35, 25, 15, 12, 13],
              backgroundColor: ['#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#fef3c7'],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
          }
        });
      }

      const funnelCtx = document.getElementById('funnelChart')?.getContext('2d');
      if (funnelCtx) {
        new Chart(funnelCtx, {
          type: 'bar',
          data: {
            labels: ['Awareness', 'Interest', 'Consideration', 'Intent', 'Conversion'],
            datasets: [{
              data: [5000, 3500, 2000, 800, 240],
              backgroundColor: ['#fef3c7', '#fde68a', '#fcd34d', '#fbbf24', '#f59e0b'],
              borderWidth: 0,
              borderRadius: 4
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
              y: { grid: { display: false } }
            }
          }
        });
      }
    }
    

    // Feature card click handling
    document.querySelectorAll('.feature-card').forEach(card => {
      card.addEventListener('click', function() {
        const link = this.getAttribute('onclick')?.match(/href='([^']+)'/)?.[1];
        if (link) {
          window.location.href = link;
        }
      });
    });
  </script>
</body>
</html>