<?php
// create_campaign.php
session_start();
if (!isset($_SESSION['marketing_logged_in'])) {
    $_SESSION['marketing_logged_in'] = true;
    $_SESSION['full_name'] = 'Marketing Manager';
}

$managerName = $_SESSION['full_name'] ?? 'Marketing Manager';
$profileImage = 'https://ui-avatars.com/api/?name=' . urlencode($managerName) . '&background=f59e0b&color=fff&bold=true';
$currentYear = date('Y');

// Campaign types
$campaignTypes = [
    'social' => 'Social Media Campaign',
    'email' => 'Email Marketing',
    'content' => 'Content Marketing',
    'ppc' => 'PPC Advertising',
    'influencer' => 'Influencer Marketing',
    'video' => 'Video Marketing',
    'affiliate' => 'Affiliate Marketing'
];

// Campaign statuses
$campaignStatuses = [
    'draft' => 'Draft',
    'planned' => 'Planned',
    'active' => 'Active',
    'paused' => 'Paused'
];

// Target audiences
$targetAudiences = [
    'luxury' => 'Luxury Travelers',
    'family' => 'Family Travelers',
    'adventure' => 'Adventure Seekers',
    'cultural' => 'Cultural Explorers',
    'wellness' => 'Wellness & Retreat',
    'business' => 'Business Travelers'
];

// Marketing channels
$channels = [
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
    'google_ads' => 'Google Ads',
    'email' => 'Email',
    'linkedin' => 'LinkedIn',
    'youtube' => 'YouTube',
    'tiktok' => 'TikTok',
    'blog' => 'Blog/Content'
];

// Campaign templates
$templates = [
    [
        'name' => 'Summer Promotion',
        'description' => 'Seasonal summer travel campaign',
        'icon' => 'sun'
    ],
    [
        'name' => 'Luxury Getaway',
        'description' => 'High-end luxury travel packages',
        'icon' => 'crown'
    ],
    [
        'name' => 'Weekend Escape',
        'description' => 'Short weekend trip packages',
        'icon' => 'plane'
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
  <title>Create New Campaign | TravelEase Marketing</title>
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
          <a href="marketing_dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-chart-line w-6 text-center"></i>
            Overview
          </a>
          <a href="marketing_campaigns.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-bullhorn w-6 text-center"></i>
            Campaigns
          </a>
          <a href="marketing_leads.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Leads
          </a>
          <a href="marketing_report.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-file-alt w-6 text-center"></i>
            Reports
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
          <a href="marketing_dashboard.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-chart-line text-xs text-amber-500"></i>
              Overview
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_campaigns.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-bullhorn text-xs text-amber-500 mr-2"></i>
            Campaigns
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_leads.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-amber-500 mr-2"></i>
            Leads
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="marketing_report.php" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <i class="fas fa-file-alt text-xs text-amber-500 mr-2"></i>
            Reports
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
          <span class="text-gradient">Create New Campaign</span>
        </h1>
        <p class="text-lg text-gray-700">Set up and launch your new marketing campaign</p>
      </div>

      <!-- Campaign Creation Steps -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-semibold mb-2">1</div>
              <span class="text-sm font-medium text-gray-900">Basic Info</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">2</div>
              <span class="text-sm font-medium text-gray-700">Targeting</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">3</div>
              <span class="text-sm font-medium text-gray-700">Budget & Schedule</span>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex flex-col items-center">
              <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-semibold mb-2">4</div>
              <span class="text-sm font-medium text-gray-700">Review</span>
            </div>
          </div>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: 25%"></div>
        </div>
      </div>

      <!-- Campaign Creation Form -->
      <div class="glass-effect rounded-2xl p-6 border border-amber-100 shadow">
        <form id="campaignForm" class="space-y-6" onsubmit="event.preventDefault(); return false;">
          <!-- Step 1: Basic Information -->
          <div id="step1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Campaign Basic Information</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                <input type="text" id="campaignName" placeholder="e.g., Summer Asia Promotion 2024" 
                       class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Type *</label>
                <select id="campaignType" class="w-full p-3 rounded-xl border border-amber-200 bg-white" required>
                  <option value="">Select campaign type</option>
                  <?php foreach ($campaignTypes as $value => $label): ?>
                  <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Description</label>
                <textarea id="campaignDescription" rows="3" placeholder="Describe your campaign objectives, goals, and key messages..." 
                          class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Initial Status *</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <?php foreach ($campaignStatuses as $value => $label): ?>
                  <label class="flex items-center p-3 rounded-xl border border-amber-200 bg-white cursor-pointer hover:bg-amber-50">
                    <input type="radio" name="status" value="<?= htmlspecialchars($value) ?>" class="text-amber-600 focus:ring-amber-500" 
                           <?= $value === 'draft' ? 'checked' : '' ?> required>
                    <span class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($label) ?></span>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                Next: Targeting <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Step 2: Targeting -->
          <div id="step2" class="hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">2. Campaign Targeting</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                <select id="targetAudience" class="w-full p-3 rounded-xl border border-amber-200 bg-white" required>
                  <option value="">Select target audience</option>
                  <?php foreach ($targetAudiences as $value => $label): ?>
                  <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Regions</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">North America</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Europe</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Asia Pacific</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Australia</label>
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Marketing Channels *</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                  <?php foreach ($channels as $value => $label): ?>
                  <div class="flex items-center">
                    <input type="checkbox" name="channels[]" value="<?= htmlspecialchars($value) ?>" 
                           class="rounded text-amber-600 focus:ring-amber-500 channel-checkbox" 
                           <?= in_array($value, ['facebook', 'instagram', 'email']) ? 'checked' : '' ?>>
                    <label class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($label) ?></label>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Targeted Packages</label>
                <div class="space-y-2">
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Japan Luxury Getaway</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500" checked>
                    <label class="ml-2 text-sm text-gray-700">Bali Wellness Retreat</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Thailand Island Hopping</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500">
                    <label class="ml-2 text-sm text-gray-700">Vietnam Culture Tour</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
              </button>
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                Next: Budget & Schedule <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Step 3: Budget & Schedule -->
          <div id="step3" class="hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">3. Budget & Schedule</h3>
            
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Total Budget ($) *</label>
                  <input type="number" id="campaignBudget" placeholder="e.g., 25000" min="0" step="100" 
                         class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Daily Budget ($)</label>
                  <input type="number" id="dailyBudget" placeholder="e.g., 500" min="0" step="10" 
                         class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                  <input type="date" id="startDate" 
                         class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                  <input type="date" id="endDate" 
                         class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Goals & KPIs</label>
                <div class="space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Target Leads:</span>
                    <input type="number" id="targetLeads" placeholder="1000" class="w-32 p-2 rounded-lg border border-amber-200 bg-white">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Target Conversions:</span>
                    <input type="number" id="targetConversions" placeholder="100" class="w-32 p-2 rounded-lg border border-amber-200 bg-white">
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Target ROI:</span>
                    <input type="text" id="targetROI" placeholder="3.5x" class="w-32 p-2 rounded-lg border border-amber-200 bg-white">
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
              </button>
              <button type="button" onclick="nextStep()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                Next: Review & Create <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <!-- Step 4: Review & Create -->
          <div id="step4" class="hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">4. Review & Create Campaign</h3>
            
            <div class="bg-amber-50 rounded-xl p-6 mb-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-bullhorn text-white text-lg"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900" id="previewName">Campaign Preview</h4>
                  <p class="text-sm text-gray-600">Review your campaign details before creating</p>
                </div>
              </div>

              <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Campaign Name:</span>
                  <span class="font-medium text-gray-900" id="previewCampaignName">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-medium text-gray-900" id="previewCampaignType">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Status:</span>
                  <span class="font-medium text-gray-900" id="previewStatus">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Target Audience:</span>
                  <span class="font-medium text-gray-900" id="previewAudience">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Budget:</span>
                  <span class="font-medium text-gray-900" id="previewBudget">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Start Date:</span>
                  <span class="font-medium text-gray-900" id="previewStartDate">-</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Channels:</span>
                  <span class="font-medium text-gray-900" id="previewChannels">-</span>
                </div>
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Team Members (Optional)</label>
              <input type="text" id="teamMembers" placeholder="Enter email addresses separated by commas" 
                     class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
              <textarea id="additionalNotes" rows="2" placeholder="Any additional notes or instructions..." 
                        class="w-full p-3 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"></textarea>
            </div>

            <div class="flex items-center mb-6">
              <input type="checkbox" id="notifyTeam" class="rounded text-amber-600 focus:ring-amber-500" checked>
              <label for="notifyTeam" class="ml-2 text-sm text-gray-700">Notify team members when campaign is created</label>
            </div>

            <div class="flex justify-between">
              <button type="button" onclick="prevStep()" class="px-5 py-2.5 rounded-xl border border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
              </button>
              <button type="button" onclick="createCampaign()" class="px-5 py-2.5 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-rocket mr-2"></i> Create Campaign
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Quick Templates -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Campaign Templates</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <?php foreach ($templates as $template): ?>
          <button onclick="useTemplate('<?= htmlspecialchars($template['name']) ?>')" 
                  class="p-4 rounded-xl border border-amber-200 bg-white hover:bg-amber-50 transition-colors text-left">
            <div class="flex items-center gap-3 mb-2">
              <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
                <i class="fas fa-<?= htmlspecialchars($template['icon']) ?> text-white"></i>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($template['name']) ?></h4>
                <p class="text-xs text-gray-600"><?= htmlspecialchars($template['description']) ?></p>
              </div>
            </div>
            <div class="text-xs text-amber-600">Click to use template</div>
          </button>
          <?php endforeach; ?>
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
  // Multi-step form functionality
  let currentStep = 1;

  function updateProgressBar() {
    const progress = document.querySelector('.progress-fill');
    const steps = document.querySelectorAll('.flex-1 > div > div');
    
    // Update progress bar width
    progress.style.width = `${(currentStep / 4) * 100}%`;
    
    // Update step indicators
    steps.forEach((step, index) => {
      const stepNumber = index + 1;
      if (stepNumber < currentStep) {
        step.classList.remove('bg-amber-100', 'text-amber-800');
        step.classList.add('bg-green-100', 'text-green-800');
      } else if (stepNumber === currentStep) {
        step.classList.remove('bg-amber-100', 'text-amber-800', 'bg-green-100', 'text-green-800');
        step.classList.add('bg-amber-500', 'text-white');
      } else {
        step.classList.remove('bg-amber-500', 'text-white', 'bg-green-100', 'text-green-800');
        step.classList.add('bg-amber-100', 'text-amber-800');
      }
    });
  }

  function nextStep() {
    // Validate current step before proceeding
    if (!validateStep(currentStep)) {
      return;
    }

    if (currentStep < 4) {
      document.getElementById(`step${currentStep}`).classList.add('hidden');
      currentStep++;
      document.getElementById(`step${currentStep}`).classList.remove('hidden');
      updateProgressBar();
      
      // Update preview in step 4
      if (currentStep === 4) {
        updatePreview();
      }
    }
  }

  function prevStep() {
    if (currentStep > 1) {
      document.getElementById(`step${currentStep}`).classList.add('hidden');
      currentStep--;
      document.getElementById(`step${currentStep}`).classList.remove('hidden');
      updateProgressBar();
    }
  }

  function validateStep(step) {
    switch(step) {
      case 1:
        const campaignName = document.getElementById('campaignName').value;
        const campaignType = document.getElementById('campaignType').value;
        
        if (!campaignName || campaignName.trim() === '') {
          alert('Please enter a campaign name.');
          document.getElementById('campaignName').focus();
          return false;
        }
        
        if (!campaignType) {
          alert('Please select a campaign type.');
          document.getElementById('campaignType').focus();
          return false;
        }
        
        return true;
      
      case 2:
        const targetAudience = document.getElementById('targetAudience').value;
        const channels = document.querySelectorAll('.channel-checkbox:checked');
        
        if (!targetAudience) {
          alert('Please select a target audience.');
          document.getElementById('targetAudience').focus();
          return false;
        }
        
        if (channels.length === 0) {
          alert('Please select at least one marketing channel.');
          return false;
        }
        
        return true;
      
      case 3:
        const budget = document.getElementById('campaignBudget').value;
        const startDate = document.getElementById('startDate').value;
        
        if (!budget || budget <= 0 || isNaN(budget)) {
          alert('Please enter a valid budget amount.');
          document.getElementById('campaignBudget').focus();
          return false;
        }
        
        if (!startDate) {
          alert('Please select a start date.');
          document.getElementById('startDate').focus();
          return false;
        }
        
        return true;
      
      default:
        return true;
    }
  }

  function updatePreview() {
    // Update preview with form values
    const campaignName = document.getElementById('campaignName').value;
    document.getElementById('previewCampaignName').textContent = campaignName || '-';
    
    const campaignTypeSelect = document.getElementById('campaignType');
    const campaignTypeText = campaignTypeSelect.options[campaignTypeSelect.selectedIndex]?.text;
    document.getElementById('previewCampaignType').textContent = campaignTypeText || '-';
    
    const statusRadio = document.querySelector('input[name="status"]:checked');
    const statusText = statusRadio ? statusRadio.parentElement.querySelector('span').textContent : '-';
    document.getElementById('previewStatus').textContent = statusText;
    
    const targetAudienceSelect = document.getElementById('targetAudience');
    const targetAudienceText = targetAudienceSelect.options[targetAudienceSelect.selectedIndex]?.text;
    document.getElementById('previewAudience').textContent = targetAudienceText || '-';
    
    const budgetValue = document.getElementById('campaignBudget').value;
    document.getElementById('previewBudget').textContent = budgetValue ? 
      '$' + parseInt(budgetValue).toLocaleString() : '-';
    
    const startDateValue = document.getElementById('startDate').value;
    if (startDateValue) {
      const date = new Date(startDateValue);
      document.getElementById('previewStartDate').textContent = 
        date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } else {
      document.getElementById('previewStartDate').textContent = '-';
    }
    
    // Get selected channels
    const selectedChannels = Array.from(document.querySelectorAll('.channel-checkbox:checked'))
      .map(cb => cb.nextElementSibling.textContent)
      .join(', ');
    document.getElementById('previewChannels').textContent = selectedChannels || 'None selected';
  }

  function createCampaign() {
    const campaignName = document.getElementById('campaignName').value;
    
    // Validate final step
    if (!campaignName || campaignName.trim() === '') {
      alert('Please enter a campaign name.');
      return;
    }
    
    // Show loading state
    const createBtn = document.querySelector('button[onclick="createCampaign()"]');
    const originalText = createBtn.innerHTML;
    createBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
    createBtn.disabled = true;
    
    // Simulate campaign creation
    setTimeout(() => {
      // Show success message
      alert(`✅ Campaign "${campaignName}" has been created successfully!\n\nYou can now manage it from the Campaigns page.`);
      
      // Reset button
      createBtn.innerHTML = originalText;
      createBtn.disabled = false;
      
      // Redirect to campaigns page
      window.location.href = 'marketing_campaigns.php';
    }, 2000);
  }

  function useTemplate(templateName) {
    // Set default values based on template
    const templates = {
      'Summer Promotion': {
        name: 'Summer Asia Promotion 2024',
        type: 'social',
        audience: 'luxury',
        budget: '25000',
        description: 'Promote summer travel packages to Asia destinations',
        startDate: getDateString(7), // 7 days from now
        endDate: getDateString(37), // 37 days from now (30 day campaign)
        dailyBudget: '833',
        targetLeads: '1000',
        targetConversions: '120',
        targetROI: '3.5x'
      },
      'Luxury Getaway': {
        name: 'Luxury Japan Getaway',
        type: 'influencer',
        audience: 'luxury',
        budget: '35000',
        description: 'High-end luxury travel packages to Japan',
        startDate: getDateString(14),
        endDate: getDateString(74),
        dailyBudget: '583',
        targetLeads: '750',
        targetConversions: '90',
        targetROI: '4.2x'
      },
      'Weekend Escape': {
        name: 'Weekend Escape Packages',
        type: 'email',
        audience: 'family',
        budget: '15000',
        description: 'Short weekend trip packages for busy professionals',
        startDate: getDateString(3),
        endDate: getDateString(33),
        dailyBudget: '455',
        targetLeads: '2000',
        targetConversions: '150',
        targetROI: '2.8x'
      }
    };
    
    function getDateString(daysFromNow) {
      const date = new Date();
      date.setDate(date.getDate() + daysFromNow);
      return date.toISOString().split('T')[0];
    }
    
    const template = templates[templateName];
    if (template) {
      document.getElementById('campaignName').value = template.name;
      document.getElementById('campaignType').value = template.type;
      document.getElementById('targetAudience').value = template.audience;
      document.getElementById('campaignBudget').value = template.budget;
      document.getElementById('campaignDescription').value = template.description;
      document.getElementById('startDate').value = template.startDate;
      document.getElementById('endDate').value = template.endDate;
      document.getElementById('dailyBudget').value = template.dailyBudget;
      document.getElementById('targetLeads').value = template.targetLeads;
      document.getElementById('targetConversions').value = template.targetConversions;
      document.getElementById('targetROI').value = template.targetROI;
      
      // Check appropriate channels based on template
      const checkboxes = document.querySelectorAll('.channel-checkbox');
      checkboxes.forEach(cb => cb.checked = false);
      
      if (template.type === 'social') {
        document.querySelector('input[value="facebook"]').checked = true;
        document.querySelector('input[value="instagram"]').checked = true;
      } else if (template.type === 'email') {
        document.querySelector('input[value="email"]').checked = true;
      } else if (template.type === 'influencer') {
        document.querySelector('input[value="instagram"]').checked = true;
        document.querySelector('input[value="youtube"]').checked = true;
      }
      
      // Show confirmation
      alert(`✅ "${templateName}" template loaded!\n\nReview and customize the settings before creating your campaign.`);
      
      // Auto-calculate if daily budget is not set
      if (!document.getElementById('dailyBudget').value && template.budget) {
        document.getElementById('dailyBudget').value = Math.round(template.budget / 30);
      }
    }
  }

  // Mobile menu functionality
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

  // Feature card click handling
  document.querySelectorAll('.feature-card').forEach(card => {
    card.addEventListener('click', function() {
      const link = this.getAttribute('onclick')?.match(/href='([^']+)'/)?.[1];
      if (link) {
        window.location.href = link;
      }
    });
  });

  // Initialize charts
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

  // Window load event handler
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

  // Form initialization
  document.addEventListener('DOMContentLoaded', function() {
    // Set default dates for campaign form
    const today = new Date();
    const nextWeek = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 2, today.getDate());
    
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    
    if (startDateInput) {
      startDateInput.value = nextWeek.toISOString().split('T')[0];
    }
    
    if (endDateInput) {
      endDateInput.value = nextMonth.toISOString().split('T')[0];
    }
    
    // Initialize progress bar
    updateProgressBar();
    
    // Auto-calculate daily budget
    const campaignBudgetInput = document.getElementById('campaignBudget');
    if (campaignBudgetInput) {
      campaignBudgetInput.addEventListener('input', function() {
        const totalBudget = parseFloat(this.value) || 0;
        const dailyBudget = document.getElementById('dailyBudget');
        
        if (totalBudget > 0 && dailyBudget && (!dailyBudget.value || dailyBudget.value === '0')) {
          // Auto-suggest daily budget (assuming 30-day campaign)
          dailyBudget.value = Math.round(totalBudget / 30);
        }
      });
    }
    
    // Prevent form submission on Enter key
    const campaignForm = document.getElementById('campaignForm');
    if (campaignForm) {
      campaignForm.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
        }
      });
    }
  });
</script>
</body>
</html>