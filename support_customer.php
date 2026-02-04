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
// support_customers.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer Records | TravelEase Support</title>
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
    /* Modal animation */
    #addCustomerModal {
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
    #addCustomerModal .overflow-y-auto {
      scrollbar-width: thin;
      scrollbar-color: #f59e0b transparent;
    }
    #addCustomerModal .overflow-y-auto::-webkit-scrollbar {
      width: 6px;
    }
    #addCustomerModal .overflow-y-auto::-webkit-scrollbar-track {
      background: #fef3c7;
      border-radius: 3px;
    }
    #addCustomerModal .overflow-y-auto::-webkit-scrollbar-thumb {
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
                Customer Records
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
          <a href="support_bookings.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-calendar-check mr-2"></i>
            Bookings
          </a>
          <a href="support_customer.php" class="text-primary-600 font-bold">
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
              Customer <span class="text-gradient">Records</span>
            </h1>
            <p class="text-lg text-gray-700">Update and manage customer information and records</p>
          </div>
          <!-- Updated Button with onclick handler -->
          <button onclick="openAddCustomerModal()" 
                  class="px-6 py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
            <i class="fas fa-user-plus mr-2"></i> Add Customer
          </button>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Customers</label>
            <div class="relative">
              <input type="text" 
                     placeholder="Search by name, email, phone, or booking ID..."
                     class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
            <select class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <option value="">All Customers</option>
              <option value="active">Active</option>
              <option value="premium">Premium</option>
              <option value="vip">VIP</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
            <select class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <option value="recent">Most Recent</option>
              <option value="name">Name (A-Z)</option>
              <option value="bookings">Most Bookings</option>
              <option value="spending">Highest Spending</option>
            </select>
          </div>
        </div>
        
        <!-- Quick Search Options -->
        <div class="flex flex-wrap gap-3 mt-4">
          <button class="px-4 py-2 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 text-sm font-semibold transition-colors">
            <i class="fas fa-user-tag mr-2"></i> By Booking ID
          </button>
          <button class="px-4 py-2 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 text-sm font-semibold transition-colors">
            <i class="fas fa-envelope mr-2"></i> By Email
          </button>
          <button class="px-4 py-2 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 text-sm font-semibold transition-colors">
            <i class="fas fa-phone mr-2"></i> By Phone
          </button>
          <button class="px-4 py-2 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 text-sm font-semibold transition-colors">
            <i class="fas fa-id-card mr-2"></i> By Name
          </button>
        </div>
      </div>

      <!-- Customer List -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Customer Card 1 -->
        <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold hover:shadow-xl transition-shadow">
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl gold-gradient flex items-center justify-center">
                  <span class="font-bold text-white">SJ</span>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Sarah Johnson</h3>
                  <p class="text-sm text-primary-600">Premium Customer</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Active</span>
            </div>
            
            <div class="space-y-3 mb-4">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-envelope text-primary-500"></i>
                <span>sarah.j@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-phone text-primary-500"></i>
                <span>+1 (555) 123-4567</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-map-marker-alt text-primary-500"></i>
                <span>New York, USA</span>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="text-center p-2 rounded-lg bg-primary-50">
                <div class="text-lg font-bold text-gray-900">3</div>
                <div class="text-xs text-gray-600">Bookings</div>
              </div>
              <div class="text-center p-2 rounded-lg bg-primary-50">
                <div class="text-lg font-bold text-gray-900">$15,600</div>
                <div class="text-xs text-gray-600">Total Spent</div>
              </div>
            </div>
            
            <div class="flex gap-2">
              <button class="flex-1 px-3 py-2 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
                View Profile
              </button>
              <button class="px-3 py-2 rounded-lg border border-primary-200 text-primary-700 text-sm font-semibold hover:bg-primary-50 transition-colors">
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Customer Card 2 -->
        <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold hover:shadow-xl transition-shadow">
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                  <span class="font-bold text-blue-700">MC</span>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Michael Chen</h3>
                  <p class="text-sm text-blue-600">VIP Customer</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Active</span>
            </div>
            
            <div class="space-y-3 mb-4">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-envelope text-primary-500"></i>
                <span>michael.c@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-phone text-primary-500"></i>
                <span>+1 (555) 987-6543</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-map-marker-alt text-primary-500"></i>
                <span>San Francisco, USA</span>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="text-center p-2 rounded-lg bg-blue-50">
                <div class="text-lg font-bold text-gray-900">5</div>
                <div class="text-xs text-gray-600">Bookings</div>
              </div>
              <div class="text-center p-2 rounded-lg bg-blue-50">
                <div class="text-lg font-bold text-gray-900">$28,400</div>
                <div class="text-xs text-gray-600">Total Spent</div>
              </div>
            </div>
            
            <div class="flex gap-2">
              <button class="flex-1 px-3 py-2 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
                View Profile
              </button>
              <button class="px-3 py-2 rounded-lg border border-primary-200 text-primary-700 text-sm font-semibold hover:bg-primary-50 transition-colors">
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Customer Card 3 -->
        <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold hover:shadow-xl transition-shadow">
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                  <span class="font-bold text-green-700">ED</span>
                </div>
                <div>
                  <h3 class="font-bold text-gray-900">Emma Davis</h3>
                  <p class="text-sm text-green-600">Regular Customer</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Inactive</span>
            </div>
            
            <div class="space-y-3 mb-4">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-envelope text-primary-500"></i>
                <span>emma.d@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-phone text-primary-500"></i>
                <span>+1 (555) 456-7890</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="fas fa-map-marker-alt text-primary-500"></i>
                <span>Chicago, USA</span>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="text-center p-2 rounded-lg bg-green-50">
                <div class="text-lg font-bold text-gray-900">1</div>
                <div class="text-xs text-gray-600">Bookings</div>
              </div>
              <div class="text-center p-2 rounded-lg bg-green-50">
                <div class="text-lg font-bold text-gray-900">$6,200</div>
                <div class="text-xs text-gray-600">Total Spent</div>
              </div>
            </div>
            
            <div class="flex gap-2">
              <button class="flex-1 px-3 py-2 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors">
                View Profile
              </button>
              <button class="px-3 py-2 rounded-lg border border-primary-200 text-primary-700 text-sm font-semibold hover:bg-primary-50 transition-colors">
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Update Customer Information -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Edit Customer Form -->
        <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Customer Information</h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer ID</label>
              <input type="text" 
                     class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="Enter Customer ID">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" 
                       class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="First name">
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" 
                       class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="Last name">
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <input type="email" 
                     class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="customer@email.com">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
              <input type="tel" 
                     class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="+1 (555) 123-4567">
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
              <select class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="regular">Regular</option>
                <option value="premium">Premium</option>
                <option value="vip">VIP</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            
            <button class="w-full py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
              Update Customer Record
            </button>
          </div>
        </div>

        <!-- Customer Details -->
        <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Customer Details</h3>
          
          <div class="space-y-6">
            <!-- Contact Information -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Contact Information</h4>
              <div class="p-4 rounded-xl bg-primary-50">
                <div class="space-y-3">
                  <div class="flex items-center gap-3">
                    <i class="fas fa-user-edit text-primary-600"></i>
                    <div>
                      <div class="font-medium text-gray-900">Primary Contact</div>
                      <div class="text-sm text-gray-600">Update phone, email, address</div>
                    </div>
                    <button class="ml-auto text-primary-600 hover:text-primary-800">
                      <i class="fas fa-edit"></i>
                    </button>
                  </div>
                  
                  <div class="flex items-center gap-3">
                    <i class="fas fa-passport text-primary-600"></i>
                    <div>
                      <div class="font-medium text-gray-900">Travel Documents</div>
                      <div class="text-sm text-gray-600">Passport, visa, ID documents</div>
                    </div>
                    <button class="ml-auto text-primary-600 hover:text-primary-800">
                      <i class="fas fa-edit"></i>
                    </button>
                  </div>
                  
                  <div class="flex items-center gap-3">
                    <i class="fas fa-heart text-primary-600"></i>
                    <div>
                      <div class="font-medium text-gray-900">Preferences</div>
                      <div class="text-sm text-gray-600">Dietary, room, activity preferences</div>
                    </div>
                    <button class="ml-auto text-primary-600 hover:text-primary-800">
                      <i class="fas fa-edit"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Booking History -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Booking History</h4>
              <div class="space-y-3">
                <div class="p-3 rounded-xl border border-primary-200">
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="font-medium text-gray-900">Japan Luxury Tour</div>
                      <div class="text-sm text-gray-600">Booking #BK-4567 • Mar 15-30, 2024</div>
                    </div>
                    <span class="text-sm font-semibold text-green-700">$4,800</span>
                  </div>
                </div>
                
                <div class="p-3 rounded-xl border border-primary-200">
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="font-medium text-gray-900">Bali Wellness Retreat</div>
                      <div class="text-sm text-gray-600">Booking #BK-4566 • Apr 5-15, 2024</div>
                    </div>
                    <span class="text-sm font-semibold text-green-700">$2,450</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Notes Section -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-3">Customer Notes</h4>
              <textarea 
                rows="3"
                class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                placeholder="Add notes about customer interactions, preferences, or special requirements..."></textarea>
            </div>
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
            <li><a href="#" class="hover:text-primary-600 transition-colors">Knowledge Base</a></li>
            <li><a href="#" class="hover:text-primary-600 transition-colors">Quick Response Templates</a></li>
            <li><a href="#" class="hover:text-primary-600 transition-colors">Policy Guidelines</a></li>
            <li><a href="#" class="hover:text-primary-600 transition-colors">Training Materials</a></li>
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

  <!-- Add Customer Modal -->
  <div id="addCustomerModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeAddCustomerModal()"></div>

      <!-- Modal container -->
      <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
        <!-- Modal header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Add New Customer</h3>
              <p class="text-sm text-gray-600">Fill in the details to create a new customer record</p>
            </div>
            <button type="button" 
                    onclick="closeAddCustomerModal()"
                    class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
              <i class="fas fa-times text-lg"></i>
            </button>
          </div>
        </div>

        <!-- Modal body -->
        <div class="px-6 py-4">
          <form id="addCustomerForm" class="space-y-6">
            <!-- Personal Information -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-4 text-lg">Personal Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                  <input type="text" 
                         name="firstName"
                         required
                         class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                         placeholder="Enter first name">
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                  <input type="text" 
                         name="lastName"
                         required
                         class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                         placeholder="Enter last name">
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-4 text-lg">Contact Information</h4>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                  <input type="email" 
                         name="email"
                         required
                         class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                         placeholder="customer@example.com">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" 
                           name="phone"
                           required
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="+1 (555) 123-4567">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="tel" 
                           name="alternatePhone"
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="Optional">
                  </div>
                </div>
              </div>
            </div>

            <!-- Address Information -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-4 text-lg">Address Information</h4>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                  <input type="text" 
                         name="address"
                         class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                         placeholder="123 Main St">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" 
                           name="city"
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="City">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                    <input type="text" 
                           name="state"
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="State">
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" 
                           name="postalCode"
                           class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="ZIP/Postal code">
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                  <select name="country"
                          class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                    <option value="">Select Country</option>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="UK">United Kingdom</option>
                    <option value="AU">Australia</option>
                    <option value="JP">Japan</option>
                    <option value="SG">Singapore</option>
                    <option value="DE">Germany</option>
                    <option value="FR">France</option>
                    <option value="IT">Italy</option>
                    <option value="ES">Spain</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Customer Details -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-4 text-lg">Customer Details</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type *</label>
                  <select name="customerType"
                          required
                          class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                    <option value="regular">Regular</option>
                    <option value="premium">Premium</option>
                    <option value="vip">VIP</option>
                    <option value="corporate">Corporate</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                  <select name="status"
                          required
                          class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending Verification</option>
                  </select>
                </div>
              </div>
              
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                <input type="date" 
                       name="dateOfBirth"
                       class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              </div>
            </div>

            <!-- Travel Preferences -->
            <div>
              <h4 class="font-semibold text-gray-900 mb-4 text-lg">Travel Preferences</h4>
              <div class="space-y-3">
                <div class="flex items-center gap-2">
                  <input type="checkbox" 
                         name="preferences[]" 
                         value="business_travel" 
                         id="businessTravel"
                         class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                  <label for="businessTravel" class="text-sm text-gray-700">Business Travel</label>
                </div>
                
                <div class="flex items-center gap-2">
                  <input type="checkbox" 
                         name="preferences[]" 
                         value="leisure_travel" 
                         id="leisureTravel"
                         class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                  <label for="leisureTravel" class="text-sm text-gray-700">Leisure Travel</label>
                </div>
                
                <div class="flex items-center gap-2">
                  <input type="checkbox" 
                         name="preferences[]" 
                         value="family_travel" 
                         id="familyTravel"
                         class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                  <label for="familyTravel" class="text-sm text-gray-700">Family Travel</label>
                </div>
                
                <div class="flex items-center gap-2">
                  <input type="checkbox" 
                         name="preferences[]" 
                         value="adventure_travel" 
                         id="adventureTravel"
                         class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                  <label for="adventureTravel" class="text-sm text-gray-700">Adventure Travel</label>
                </div>
              </div>
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Initial Notes</label>
              <textarea name="notes"
                        rows="3"
                        class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                        placeholder="Add any initial notes or special requirements..."></textarea>
            </div>

            <!-- Form validation error container -->
            <div id="formErrors" class="hidden p-4 rounded-xl bg-red-50 border border-red-200">
              <div class="flex items-center gap-2 text-red-700">
                <i class="fas fa-exclamation-circle"></i>
                <span class="font-medium">Please fix the following errors:</span>
              </div>
              <ul id="errorList" class="mt-2 ml-6 list-disc text-sm text-red-600"></ul>
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <button type="button" 
                    onclick="closeAddCustomerModal()"
                    class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <div class="flex gap-3">
              <button type="button" 
                      onclick="saveAsDraft()"
                      class="px-6 py-3 rounded-xl border border-primary-300 text-primary-700 font-semibold hover:bg-primary-50 transition-colors">
                Save as Draft
              </button>
              <button type="button" 
                      onclick="submitCustomerForm()"
                      class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-shadow">
                <i class="fas fa-save mr-2"></i> Create Customer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Modal functions
    function openAddCustomerModal() {
      document.getElementById('addCustomerModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeAddCustomerModal() {
      document.getElementById('addCustomerModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
      resetForm();
    }

    // Form functions
    function resetForm() {
      document.getElementById('addCustomerForm').reset();
      hideErrors();
    }

    function hideErrors() {
      document.getElementById('formErrors').classList.add('hidden');
      document.getElementById('errorList').innerHTML = '';
    }

    function showErrors(errors) {
      const errorList = document.getElementById('errorList');
      errorList.innerHTML = '';
      
      errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
      });
      
      document.getElementById('formErrors').classList.remove('hidden');
    }

    function validateForm() {
      const form = document.getElementById('addCustomerForm');
      const errors = [];
      
      // Required field validation
      if (!form.firstName.value.trim()) errors.push('First name is required');
      if (!form.lastName.value.trim()) errors.push('Last name is required');
      if (!form.email.value.trim()) errors.push('Email is required');
      if (!form.phone.value.trim()) errors.push('Phone number is required');
      if (!form.customerType.value) errors.push('Customer type is required');
      if (!form.status.value) errors.push('Status is required');
      
      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (form.email.value && !emailRegex.test(form.email.value)) {
        errors.push('Please enter a valid email address');
      }
      
      return errors;
    }

    function submitCustomerForm() {
      hideErrors();
      
      const errors = validateForm();
      if (errors.length > 0) {
        showErrors(errors);
        return;
      }
      
      // Here you would typically send the form data to your server
      const form = document.getElementById('addCustomerForm');
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      console.log('Submitting customer data:', data);
      
      // Simulate API call
      setTimeout(() => {
        alert('Customer created successfully!');
        closeAddCustomerModal();
        
        // Here you would typically refresh the customer list or add the new customer to the UI
        // For now, we'll just show an alert
      }, 500);
    }

    function saveAsDraft() {
      const form = document.getElementById('addCustomerForm');
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      console.log('Saving as draft:', data);
      
      // Here you would save the draft to localStorage or send to server
      localStorage.setItem('customerDraft', JSON.stringify(data));
      
      alert('Draft saved successfully!');
      closeAddCustomerModal();
    }

    // Load draft if exists
    window.addEventListener('DOMContentLoaded', () => {
      const draft = localStorage.getItem('customerDraft');
      if (draft) {
        // Uncomment if you want to auto-load drafts
        // const data = JSON.parse(draft);
        // Object.keys(data).forEach(key => {
        //   const element = document.querySelector(`[name="${key}"]`);
        //   if (element) element.value = data[key];
        // });
      }
    });
  </script>
</body>
</html>