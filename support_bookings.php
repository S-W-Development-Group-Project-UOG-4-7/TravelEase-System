<?php
session_start();

// Check login session for Customer Support Agent
if (!isset($_SESSION['support_logged_in'])) {
    $_SESSION['support_logged_in'] = true;
    $_SESSION['full_name'] = 'Customer Support Agent';
}

// Session values
$agentName = $_SESSION['full_name'] ?? 'Customer Support Agent';

// Profile image using UI Avatars
$profileImage = 'https://ui-avatars.com/api/?name=' 
    . urlencode($agentName) 
    . '&background=2563eb&color=fff&bold=true';

// Current year
$currentYear = date('Y');
// support_bookings.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Operations | TravelEase Support</title>
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

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { 
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
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
    .shadow-gold {
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    .status-cancelled { background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); color: #991b1b; }
    .status-modified { background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); color: #1e40af; }
    .status-active { background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%); color: #166534; }
    .status-refunded { background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%); color: #374151; }
    
    /* Button styling for anchor tags */
    .btn-link {
      display: inline-block;
      text-decoration: none;
      cursor: pointer;
    }
    .btn-link:hover {
      text-decoration: none;
    }
    
    /* Modal animation */
    #tripDetailsModal {
      animation: modalFadeIn 0.3s ease-out;
    }
    @keyframes modalFadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    /* Scrollbar styling for modal */
    #tripDetailsModal .overflow-y-auto {
      scrollbar-width: thin;
      scrollbar-color: #f59e0b transparent;
    }
    #tripDetailsModal .overflow-y-auto::-webkit-scrollbar {
      width: 6px;
    }
    #tripDetailsModal .overflow-y-auto::-webkit-scrollbar-track {
      background: #fef3c7;
      border-radius: 3px;
    }
    #tripDetailsModal .overflow-y-auto::-webkit-scrollbar-thumb {
      background: #f59e0b;
      border-radius: 3px;
    }
  </style>
</head>
<body class="min-h-screen">
  <!-- Navigation -->
  <header class="sticky top-0 left-0 right-0 z-30 glass-effect border-b border-primary-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <a href="support_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-primary-200">
              <img src="img/Logo.png" alt="TravelEase Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="font-black text-xl tracking-tight text-gray-900">
                TravelEase
              </span>
              <span class="hidden sm:inline-block text-xs text-primary-600 font-medium">
                Booking Operations
              </span>
            </div>
          </a>
        </div>

        <!-- Navigation -->
        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
          <a href="agent_dashboard.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-tachometer-alt mr-2"></i>
            Dashboard
          </a>
          <a href="support_ticket.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-ticket-alt mr-2"></i>
            Tickets
          </a>
          <a href="support_chatbot.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-robot mr-2"></i>
            Chatbot
          </a>
          <a href="support_bookings.php" class="text-primary-600 font-bold">
            <i class="fas fa-calendar-check mr-2"></i>
            Bookings
          </a>
          <a href="support_customer.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-users mr-2"></i>
            Customers
          </a>
        </div>

        <!-- User & Logout -->
        <div class="flex items-center gap-4">
          <div class="hidden lg:flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center font-semibold text-primary-700">
              SA
            </div>
          </div>
          <a href="logout.php"
             class="px-4 py-2 rounded-xl glass-effect text-sm font-semibold text-gray-700 hover:text-red-600 hover:bg-red-50 transition-all border border-primary-100">
            Logout
          </a>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
              Booking <span class="text-gradient">Operations</span>
            </h1>
            <p class="text-lg text-gray-700">Handle cancellations, modifications, and booking issues</p>
          </div>
          <div class="flex items-center gap-4">
            <a href="process_cancelation.php" class="px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors btn-link">
              <i class="fas fa-ban mr-2"></i> Process Cancellation
            </a>
          </div>
        </div>
      </div>

      <!-- Quick Stats - Make them clickable -->
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
        <a href="?filter=cancelled_today" class="glass-effect rounded-2xl p-6 border border-red-100 hover:border-red-300 transition-colors block">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
              <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">5</div>
          <div class="text-sm text-gray-600">Today's Cancellations</div>
        </a>
        
        <a href="?filter=modifications" class="glass-effect rounded-2xl p-6 border border-blue-100 hover:border-blue-300 transition-colors block">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
              <i class="fas fa-sync-alt text-blue-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">12</div>
          <div class="text-sm text-gray-600">Modification Requests</div>
        </a>
        
        <a href="?filter=refunds" class="glass-effect rounded-2xl p-6 border border-green-100 hover:border-green-300 transition-colors block">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
              <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">$8,450</div>
          <div class="text-sm text-gray-600">Total Refunds Today</div>
        </a>
        
        <a href="?filter=resolved" class="glass-effect rounded-2xl p-6 border border-primary-100 hover:border-primary-300 transition-colors block">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-primary-100 flex items-center justify-center">
              <i class="fas fa-tools text-primary-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">18</div>
          <div class="text-sm text-gray-600">Issues Resolved</div>
        </a>
      </div>

      <!-- Cancellation Requests -->
      <div class="glass-effect rounded-2xl border border-red-100 shadow-gold mb-8">
        <div class="p-6 border-b border-red-100">
          <h3 class="text-xl font-bold text-gray-900">
            <i class="fas fa-ban text-red-500 mr-2"></i>
            Cancellation Requests
          </h3>
        </div>
        
        <div class="divide-y divide-red-100">
          <!-- Cancellation 1 -->
          <div class="p-6 hover:bg-red-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Japan Tour - 2 Pax</span>
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-cancelled">Refund Pending</span>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                  <p><span class="font-medium">Booking:</span> #BK-4567 • Japan Luxury Experience</p>
                  <p><span class="font-medium">Departure:</span> March 15, 2024 • 14 days</p>
                  <p><span class="font-medium">Customer:</span> Sarah Johnson • sarah.j@email.com</p>
                  <p><span class="font-medium">Refund Amount:</span> $4,800</p>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                
                <a href="contact_customer.php?email=sarah.j@email.com" class="px-4 py-2 rounded-xl border border-red-200 text-red-700 font-semibold hover:bg-red-50 transition-colors btn-link text-center">
                  Contact Customer
                </a>
              </div>
            </div>
          </div>

          <!-- Cancellation 2 - Updated with onclick for details -->
          <div class="p-6 hover:bg-red-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Bali Wellness Retreat - 1 Pax</span>
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-refunded">Refunded</span>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                  <p><span class="font-medium">Booking:</span> #BK-4566 • Bali Luxury Retreat</p>
                  <p><span class="font-medium">Departure:</span> April 5, 2024 • 10 days</p>
                  <p><span class="font-medium">Customer:</span> Michael Chen • michael.c@email.com</p>
                  <p><span class="font-medium">Refund Amount:</span> $2,450 (Processed)</p>
                </div>
              </div>
              <div>
                <!-- Updated: Added onclick to show details -->
                <button onclick="showBaliWellnessDetails()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                  View Details
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modification Requests -->
      <div class="glass-effect rounded-2xl border border-blue-100 shadow-gold mb-8">
        <div class="p-6 border-b border-blue-100">
          <h3 class="text-xl font-bold text-gray-900">
            <i class="fas fa-sync-alt text-blue-500 mr-2"></i>
            Modification Requests
          </h3>
        </div>
        
        <div class="divide-y divide-blue-100">
          <!-- Modification 1 -->
          <div class="p-6 hover:bg-blue-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Thailand Trip - Date Change</span>
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-modified">Review Needed</span>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                  <p><span class="font-medium">Booking:</span> #BK-4568 • Thailand Island Hopping</p>
                  <p><span class="font-medium">Current Dates:</span> Apr 10-24, 2024</p>
                  <p><span class="font-medium">Requested Dates:</span> Apr 20-May 4, 2024</p>
                  <p><span class="font-medium">Customer:</span> Robert Williams • robert.w@email.com</p>
                  <p><span class="font-medium">Notes:</span> Requires date change due to work schedule</p>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <a href="check_availability.php?id=4568&date=2024-04-20" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors btn-link text-center">
                  Check Availability
                </a>
                <a href="contact_customer.php?email=robert.w@email.com" class="px-4 py-2 rounded-xl border border-blue-200 text-blue-700 font-semibold hover:bg-blue-50 transition-colors btn-link text-center">
                  Contact Customer
                </a>
              </div>
            </div>
          </div>

          <!-- Modification 2 - Updated: Vietnam Cultural Tour -->
          <div class="p-6 hover:bg-blue-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Vietnam Cultural Tour - Itinerary Enhancement</span>
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-active">Enhanced</span>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                  <p><span class="font-medium">Booking:</span> #BK-4569 • Vietnam Cultural Journey</p>
                  <p><span class="font-medium">Original:</span> Standard Itinerary (12 days)</p>
                  <p><span class="font-medium">Enhanced to:</span> Premium Cultural Experience (14 days)</p>
                  <p><span class="font-medium">Customer:</span> Emma Davis • emma.d@email.com</p>
                  <p><span class="font-medium">Additional Cost:</span> $1,200 (Paid)</p>
                </div>
              </div>
              <div>
                <!-- Updated: Added onclick to show details -->
                <button onclick="showVietnamCulturalDetails()" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                  View Details
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Booking Tools -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Issue Resolution -->
        <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-tools text-primary-500 mr-2"></i>
            Issue Resolution
          </h3>
          
          <div class="space-y-4">
            <a href="access_issues.php" class="w-full p-4 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left block">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                  <i class="fas fa-key text-primary-600"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Access Issues</h4>
                  <p class="text-sm text-gray-600">Reset passwords, account access problems</p>
                </div>
              </div>
            </a>

            <a href="payment_problems.php" class="w-full p-4 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left block">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                  <i class="fas fa-file-invoice-dollar text-primary-600"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Payment Problems</h4>
                  <p class="text-sm text-gray-600">Failed payments, refund status, billing issues</p>
                </div>
              </div>
            </a>

            <a href="booking_details_issues.php" class="w-full p-4 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left block">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                  <i class="fas fa-map-marked-alt text-primary-600"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Booking Details</h4>
                  <p class="text-sm text-gray-600">Missing info, incorrect details, itinerary issues</p>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- Refund Calculator -->
        <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-calculator text-primary-500 mr-2"></i>
            Refund Calculator
          </h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Booking Amount ($)</label>
              <input type="number" 
                     id="bookingAmount"
                     class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="5000"
                     value="5000">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Days Before Departure</label>
              <input type="number" 
                     id="daysBefore"
                     class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="30"
                     value="30">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
              <select id="cancellationReason" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="customer">Customer Request</option>
                <option value="medical">Medical Emergency</option>
                <option value="weather">Weather/Force Majeure</option>
                <option value="operator">Operator Cancellation</option>
              </select>
            </div>
            
            <a href="#" onclick="calculateRefund(); return false;" class="w-full py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow block text-center btn-link">
              Calculate Refund
            </a>
            
            <div class="p-4 rounded-xl bg-green-50 border border-green-200">
              <div class="text-sm text-gray-700 mb-2">Estimated Refund:</div>
              <div id="refundAmount" class="text-2xl font-black text-green-700">$4,250</div>
              <div id="refundPercentage" class="text-xs text-gray-600 mt-2">85% of booking amount</div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-bolt text-primary-500 mr-2"></i>
            Quick Actions
          </h3>
          
          <div class="grid grid-cols-2 gap-4">
            <a href="cancel_booking.php" class="p-4 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 transition-colors block text-center">
              <i class="fas fa-ban text-red-600 text-xl mb-2"></i>
              <div class="font-semibold text-gray-900 text-sm">Cancel Booking</div>
            </a>
            
            <a href="modify_booking.php" class="p-4 rounded-xl bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors block text-center">
              <i class="fas fa-edit text-blue-600 text-xl mb-2"></i>
              <div class="font-semibold text-gray-900 text-sm">Modify Booking</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-primary-100 bg-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid gap-6 md:grid-cols-4 mb-6">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <div class="h-6 w-6 rounded-lg bg-white flex items-center justify-center font-black text-primary-600 text-xs">TE</div>
            </div>
            <span class="font-black text-lg text-gray-900">TravelEase Support</span>
          </div>
          <p class="text-sm text-gray-700">
            24/7 Customer Support for premium travel experiences.
          </p>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Support Tools</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="support_tickets.php" class="hover:text-primary-600 transition-colors">Ticket Management</a></li>
            <li><a href="support_chatbot.php" class="hover:text-primary-600 transition-colors">Chatbot Escalations</a></li>
            <li><a href="support_bookings.php" class="hover:text-primary-600 transition-colors">Booking Operations</a></li>
            <li><a href="support_customers.php" class="hover:text-primary-600 transition-colors">Customer Records</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Resources</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="knowledge_base.php" class="hover:text-primary-600 transition-colors">Knowledge Base</a></li>
            <li><a href="response_templates.php" class="hover:text-primary-600 transition-colors">Quick Response Templates</a></li>
            <li><a href="policy_guidelines.php" class="hover:text-primary-600 transition-colors">Policy Guidelines</a></li>
            <li><a href="training_materials.php" class="hover:text-primary-600 transition-colors">Training Materials</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Contact</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-center gap-2">
              <i class="fas fa-headset text-primary-500"></i>
              <span>Support Hotline: +1 (555) 123-4567</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="fas fa-envelope text-primary-500"></i>
              <span>support@travelease.com</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="fas fa-clock text-primary-500"></i>
              <span>24/7 Availability</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="pt-6 border-t border-primary-100 text-center text-sm text-gray-600">
        <p>© <?php echo date('Y'); ?> TravelEase Customer Support. Internal Use Only.</p>
      </div>
    </div>
  </footer>

  <!-- Trip Details Modal -->
  <div id="tripDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeTripDetailsModal()"></div>

      <!-- Modal container -->
      <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
        <!-- Modal header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Trip Details</h3>
              <p class="text-sm text-gray-600" id="modalSubtitle">Complete booking information</p>
            </div>
            <button type="button" 
                    onclick="closeTripDetailsModal()"
                    class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
              <i class="fas fa-times text-lg"></i>
            </button>
          </div>
        </div>

        <!-- Modal body -->
        <div class="px-6 py-4">
          <div id="tripDetailsContent">
            <!-- Content will be dynamically inserted here -->
          </div>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-end">
            <button type="button" 
                    onclick="closeTripDetailsModal()"
                    class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Refund Calculator Function
    function calculateRefund() {
      const bookingAmount = parseFloat(document.getElementById('bookingAmount').value) || 0;
      const daysBefore = parseInt(document.getElementById('daysBefore').value) || 0;
      const reason = document.getElementById('cancellationReason').value;
      
      let refundPercentage = 0;
      
      // Calculate refund based on days before departure
      if (daysBefore >= 30) {
        refundPercentage = 100; // Full refund
      } else if (daysBefore >= 15) {
        refundPercentage = 75;
      } else if (daysBefore >= 7) {
        refundPercentage = 50;
      } else if (daysBefore >= 3) {
        refundPercentage = 25;
      } else {
        refundPercentage = 0;
      }
      
      // Adjust based on cancellation reason
      if (reason === 'medical') {
        refundPercentage += 25; // Additional 25% for medical
        refundPercentage = Math.min(refundPercentage, 100);
      } else if (reason === 'operator') {
        refundPercentage = 100; // Full refund if operator cancels
      } else if (reason === 'weather') {
        refundPercentage = 100; // Full refund for force majeure
      }
      
      const refundAmount = (bookingAmount * refundPercentage) / 100;
      
      // Update display
      document.getElementById('refundAmount').textContent = '$' + refundAmount.toFixed(2);
      document.getElementById('refundPercentage').textContent = 
        refundPercentage + '% of booking amount';
      
      // Show calculation details
      alert(`Refund Calculation:\n\n` +
            `Booking Amount: $${bookingAmount}\n` +
            `Days Before Departure: ${daysBefore}\n` +
            `Cancellation Reason: ${document.getElementById('cancellationReason').options[document.getElementById('cancellationReason').selectedIndex].text}\n` +
            `Refund Percentage: ${refundPercentage}%\n` +
            `Refund Amount: $${refundAmount.toFixed(2)}`);
    }
    
    // Modal Functions
    function openTripDetailsModal() {
      document.getElementById('tripDetailsModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeTripDetailsModal() {
      document.getElementById('tripDetailsModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    // Bali Wellness Retreat Details
    function showBaliWellnessDetails() {
      document.getElementById('modalTitle').textContent = 'Bali Wellness Retreat - Complete Details';
      document.getElementById('modalSubtitle').textContent = 'Booking #BK-4566 • Refund Processed';
      
      const content = `
        <div class="space-y-6">
          <!-- Booking Summary -->
          <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Booking Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Booking ID</p>
                <p class="font-semibold text-gray-900">#BK-4566</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 rounded-full text-xs font-semibold status-refunded">Refunded</span>
              </div>
              <div>
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="font-semibold text-gray-900">$2,450</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Refund Amount</p>
                <p class="font-semibold text-green-700">$2,450 (100%)</p>
              </div>
            </div>
          </div>

          <!-- Trip Details -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Trip Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p class="text-sm text-gray-600">Trip Name</p>
                <p class="font-semibold text-gray-900">Bali Luxury Wellness Retreat</p>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Duration</p>
                  <p class="font-semibold text-gray-900">10 Days / 9 Nights</p>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Original Dates</p>
                  <p class="font-semibold text-gray-900">April 5 - April 15, 2024</p>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Travelers</p>
                  <p class="font-semibold text-gray-900">1 Adult (Single Occupancy)</p>
                </div>
              </div>
              
              <div>
                <p class="text-sm text-gray-600">Accommodation</p>
                <p class="font-semibold text-gray-900">Ubud Wellness Resort & Spa</p>
                <p class="text-sm text-gray-600 mt-1">Luxury Villa with Private Pool</p>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Inclusions</p>
                  <ul class="list-disc pl-5 text-sm text-gray-700 mt-1">
                    <li>Daily yoga & meditation sessions</li>
                    <li>3 wellness spa treatments</li>
                    <li>Organic plant-based meals</li>
                    <li>Private airport transfers</li>
                    <li>Cultural temple tours</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Customer Information -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Customer Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Customer Name</p>
                <p class="font-semibold text-gray-900">Michael Chen</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-semibold text-gray-900">michael.c@email.com</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Phone</p>
                <p class="font-semibold text-gray-900">+1 (555) 987-6543</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Customer Type</p>
                <p class="font-semibold text-gray-900">VIP Customer</p>
              </div>
            </div>
          </div>

          <!-- Refund Details -->
          <div class="p-4 rounded-xl bg-green-50 border border-green-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Refund Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Refund Request Date</p>
                <p class="font-semibold text-gray-900">March 15, 2024</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Refund Processed Date</p>
                <p class="font-semibold text-gray-900">March 17, 2024</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Refund Method</p>
                <p class="font-semibold text-gray-900">Credit Card (Original Payment)</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Transaction ID</p>
                <p class="font-semibold text-gray-900">REF-789456123</p>
              </div>
            </div>
            <div class="mt-3">
              <p class="text-sm text-gray-600">Cancellation Reason</p>
              <p class="font-semibold text-gray-900">Medical emergency - Doctor advised against travel</p>
            </div>
          </div>

          <!-- Itinerary -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Original Itinerary</h4>
            <div class="space-y-3">
              <div class="p-3 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                  <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="font-bold text-blue-700">D1</span>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">Arrival in Bali</p>
                    <p class="text-sm text-gray-600">Private transfer to Ubud Wellness Resort, Welcome ceremony</p>
                  </div>
                </div>
              </div>
              <div class="p-3 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                  <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="font-bold text-blue-700">D2-4</span>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">Wellness Program</p>
                    <p class="text-sm text-gray-600">Morning yoga, meditation, spa treatments, nutrition workshops</p>
                  </div>
                </div>
              </div>
              <div class="p-3 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                  <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="font-bold text-blue-700">D5-7</span>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">Cultural Exploration</p>
                    <p class="text-sm text-gray-600">Temple visits, traditional cooking class, rice terrace tours</p>
                  </div>
                </div>
              </div>
              <div class="p-3 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                  <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="font-bold text-blue-700">D8-10</span>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">Relaxation & Departure</p>
                    <p class="text-sm text-gray-600">Free time, final spa treatment, airport transfer</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      
      document.getElementById('tripDetailsContent').innerHTML = content;
      openTripDetailsModal();
    }

    // Vietnam Cultural Tour Details
    function showVietnamCulturalDetails() {
      document.getElementById('modalTitle').textContent = 'Vietnam Cultural Tour - Complete Details';
      document.getElementById('modalSubtitle').textContent = 'Booking #BK-4569 • Enhanced Itinerary';
      
      const content = `
        <div class="space-y-6">
          <!-- Booking Summary -->
          <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Booking Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Booking ID</p>
                <p class="font-semibold text-gray-900">#BK-4569</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 rounded-full text-xs font-semibold status-active">Enhanced & Active</span>
              </div>
              <div>
                <p class="text-sm text-gray-600">Original Amount</p>
                <p class="font-semibold text-gray-900">$3,800</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Final Amount</p>
                <p class="font-semibold text-green-700">$5,000</p>
              </div>
            </div>
          </div>

          <!-- Trip Details -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Trip Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p class="text-sm text-gray-600">Trip Name</p>
                <p class="font-semibold text-gray-900">Vietnam Premium Cultural Journey</p>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Duration</p>
                  <p class="font-semibold text-gray-900">14 Days / 13 Nights</p>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Travel Dates</p>
                  <p class="font-semibold text-gray-900">May 10 - May 24, 2024</p>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Travelers</p>
                  <p class="font-semibold text-gray-900">2 Adults (Double Occupancy)</p>
                </div>
              </div>
              
              <div>
                <p class="text-sm text-gray-600">Accommodation Level</p>
                <p class="font-semibold text-gray-900">Luxury Heritage Hotels & Boutique Resorts</p>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-600">Enhancements Added</p>
                  <ul class="list-disc pl-5 text-sm text-gray-700 mt-1">
                    <li>Extended from 12 to 14 days</li>
                    <li>Private cooking class with chef</li>
                    <li>Ha Long Bay luxury cruise upgrade</li>
                    <li>Private guide for all tours</li>
                    <li>Premium dining experiences</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Customer Information -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Customer Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Customer Name</p>
                <p class="font-semibold text-gray-900">Emma Davis</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-semibold text-gray-900">emma.d@email.com</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Phone</p>
                <p class="font-semibold text-gray-900">+1 (555) 456-7890</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Customer Type</p>
                <p class="font-semibold text-gray-900">Premium Customer</p>
              </div>
            </div>
          </div>

          <!-- Modification Details -->
          <div class="p-4 rounded-xl bg-purple-50 border border-purple-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Modification Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-600">Modification Request Date</p>
                <p class="font-semibold text-gray-900">March 20, 2024</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Modification Approved</p>
                <p class="font-semibold text-gray-900">March 22, 2024</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Additional Cost</p>
                <p class="font-semibold text-green-700">$1,200</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Payment Status</p>
                <p class="font-semibold text-green-700">Paid in Full</p>
              </div>
            </div>
            <div class="mt-3">
              <p class="text-sm text-gray-600">Modification Reason</p>
              <p class="font-semibold text-gray-900">Wanted more immersive cultural experience and extended duration</p>
            </div>
          </div>

          <!-- Enhanced Itinerary -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-3">Enhanced Itinerary Highlights</h4>
            <div class="space-y-4">
              <div class="p-4 rounded-lg border border-gray-200">
                <div class="flex items-start gap-3">
                  <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-utensils text-purple-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Hanoi Culinary Masterclass</p>
                    <p class="text-sm text-gray-600">Private cooking class with renowned Vietnamese chef, includes market tour and 5-course meal preparation</p>
                  </div>
                </div>
              </div>
              
              <div class="p-4 rounded-lg border border-gray-200">
                <div class="flex items-start gap-3">
                  <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-ship text-purple-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Ha Long Bay Premium Cruise</p>
                    <p class="text-sm text-gray-600">2-night luxury cruise on private junk boat with butler service, seafood feast, and kayaking</p>
                  </div>
                </div>
              </div>
              
              <div class="p-4 rounded-lg border border-gray-200">
                <div class="flex items-start gap-3">
                  <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-landmark text-purple-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Hue Imperial City Private Tour</p>
                    <p class="text-sm text-gray-600">Exclusive access to restricted areas of the Imperial City with historian guide</p>
                  </div>
                </div>
              </div>
              
              <div class="p-4 rounded-lg border border-gray-200">
                <div class="flex items-start gap-3">
                  <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-spa text-purple-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">Hoi An Lantern Making Workshop</p>
                    <p class="text-sm text-gray-600">Private workshop with master artisan, includes materials to create your own traditional lantern</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Price Breakdown -->
          <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Price Breakdown</h4>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Original Package (12 days)</span>
                <span class="font-semibold text-gray-900">$3,800</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Duration Extension (2 extra days)</span>
                <span class="font-semibold text-gray-900">+$400</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Ha Long Bay Cruise Upgrade</span>
                <span class="font-semibold text-gray-900">+$350</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-gray-600">Private Guide & Experiences</span>
                <span class="font-semibold text-gray-900">+$450</span>
              </div>
              <div class="flex justify-between border-t border-gray-300 pt-2 mt-2">
                <span class="font-semibold text-gray-900">Total Package Cost</span>
                <span class="font-bold text-green-700">$5,000</span>
              </div>
            </div>
          </div>
        </div>
      `;
      
      document.getElementById('tripDetailsContent').innerHTML = content;
      openTripDetailsModal();
    }

    // Add click handlers to stats cards
    document.addEventListener('DOMContentLoaded', function() {
      // Make all stats cards clickable with visual feedback
      const statsCards = document.querySelectorAll('.glass-effect a');
      statsCards.forEach(card => {
        card.addEventListener('click', function(e) {
          const filter = this.getAttribute('href').split('=')[1];
          alert(`Filtering by: ${filter}`);
          // In real implementation, this would filter the data
        });
      });
    });
  </script>
</body>
</html>