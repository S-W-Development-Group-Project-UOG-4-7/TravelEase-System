<?php
// check_availability.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Check Availability | TravelEase Support</title>
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
    .availability-available { background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%); color: #166534; }
    .availability-limited { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; }
    .availability-unavailable { background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); color: #991b1b; }
    .availability-waitlist { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #3730a3; }
    
    /* Animation for results */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 0.5s ease-out;
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
                Check Availability
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
          <a href="support_tickets.php" class="text-gray-700 hover:text-primary-600 transition-colors">
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
          <a href="support_customers.php" class="text-gray-700 hover:text-primary-600 transition-colors">
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
              Check <span class="text-gradient">Availability</span>
            </h1>
            <p class="text-lg text-gray-700">Check real-time availability for tours, flights, hotels, and modifications</p>
          </div>
          <a href="support_bookings.php" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
          </a>
        </div>
      </div>

      <!-- Search Form -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold mb-8">
        <div class="p-6 border-b border-primary-100">
          <h3 class="text-xl font-bold text-gray-900">
            <i class="fas fa-search text-primary-500 mr-2"></i>
            Search Availability
          </h3>
        </div>
        
        <div class="p-6">
          <form id="availabilityForm" class="space-y-6">
            <!-- Search Type -->
            <div>
              <h4 class="font-bold text-lg text-gray-900 mb-4">What would you like to check?</h4>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="relative">
                  <input type="radio" name="searchType" id="typeTour" value="tour" class="sr-only peer" checked>
                  <label for="typeTour" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                    <i class="fas fa-globe-americas text-3xl text-gray-600 mb-3"></i>
                    <span class="font-semibold text-gray-900">Tour Package</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Check tour package availability</span>
                  </label>
                </div>
                
                <div class="relative">
                  <input type="radio" name="searchType" id="typeHotel" value="hotel" class="sr-only peer">
                  <label for="typeHotel" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                    <i class="fas fa-hotel text-3xl text-gray-600 mb-3"></i>
                    <span class="font-semibold text-gray-900">Hotel</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Check hotel room availability</span>
                  </label>
                </div>
                
                <div class="relative">
                  <input type="radio" name="searchType" id="typeFlight" value="flight" class="sr-only peer">
                  <label for="typeFlight" class="flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                    <i class="fas fa-plane text-3xl text-gray-600 mb-3"></i>
                    <span class="font-semibold text-gray-900">Flight</span>
                    <span class="text-sm text-gray-600 mt-1 text-center">Check flight seat availability</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Destination -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i>Destination *
                </label>
                <select id="destination" name="destination" required 
                        class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="">Select Destination</option>
                  <option value="thailand">Thailand</option>
                  <option value="vietnam">Vietnam</option>
                  <option value="japan">Japan</option>
                  <option value="bali">Bali, Indonesia</option>
                  <option value="singapore">Singapore</option>
                  <option value="malaysia">Malaysia</option>
                  <option value="cambodia">Cambodia</option>
                  <option value="sri_lanka">Sri Lanka</option>
                  <option value="maldives">Maldives</option>
                  <option value="philippines">Philippines</option>
                </select>
              </div>

              <!-- Tour/Hotel/Flight Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-hashtag text-primary-500 mr-1"></i>Tour/Hotel/Flight Name *
                </label>
                <input type="text" id="itemName" name="itemName" required 
                       class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="e.g., Thailand Island Hopping, Marina Bay Sands, TG 402">
              </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-calendar-day text-primary-500 mr-1"></i>Check-in / Departure Date *
                </label>
                <input type="date" id="startDate" name="startDate" required 
                       class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       min="<?php echo date('Y-m-d'); ?>">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-calendar-alt text-primary-500 mr-1"></i>Check-out / Return Date *
                </label>
                <input type="date" id="endDate" name="endDate" required 
                       class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       min="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>

            <!-- Travelers/Rooms -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-user-friends text-primary-500 mr-1"></i>Adults *
                </label>
                <select id="adults" name="adults" required 
                        class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="1">1 Adult</option>
                  <option value="2" selected>2 Adults</option>
                  <option value="3">3 Adults</option>
                  <option value="4">4 Adults</option>
                  <option value="5">5 Adults</option>
                  <option value="6">6 Adults</option>
                  <option value="7">7 Adults</option>
                  <option value="8">8 Adults</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-child text-primary-500 mr-1"></i>Children
                </label>
                <select id="children" name="children" 
                        class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="0">No Children</option>
                  <option value="1">1 Child</option>
                  <option value="2">2 Children</option>
                  <option value="3">3 Children</option>
                  <option value="4">4 Children</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  <i class="fas fa-door-open text-primary-500 mr-1"></i>Rooms (for hotels)
                </label>
                <select id="rooms" name="rooms" 
                        class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="1">1 Room</option>
                  <option value="2">2 Rooms</option>
                  <option value="3">3 Rooms</option>
                  <option value="4">4 Rooms</option>
                  <option value="5">5 Rooms</option>
                </select>
              </div>
            </div>

            <!-- Special Requirements -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-star text-primary-500 mr-1"></i>Special Requirements
              </label>
              <div class="space-y-3">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <div class="flex items-center">
                    <input type="checkbox" id="accessible" name="requirements[]" value="accessible" 
                           class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                    <label for="accessible" class="ml-2 text-sm text-gray-700">Accessible Room</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" id="connecting" name="requirements[]" value="connecting" 
                           class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                    <label for="connecting" class="ml-2 text-sm text-gray-700">Connecting Rooms</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" id="smoking" name="requirements[]" value="smoking" 
                           class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                    <label for="smoking" class="ml-2 text-sm text-gray-700">Smoking Room</label>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" id="view" name="requirements[]" value="view" 
                           class="h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                    <label for="view" class="ml-2 text-sm text-gray-700">Sea/River View</label>
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                  <textarea id="notes" name="notes" rows="3"
                            class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                            placeholder="Any special requests or notes..."></textarea>
                </div>
              </div>
            </div>

            <!-- Search Button -->
            <div class="pt-4">
              <button type="button" onclick="checkAvailability()" 
                      class="w-full py-4 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow text-lg">
                <i class="fas fa-search mr-2"></i> Check Availability
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Results Section -->
      <div id="resultsSection" class="hidden">
        <!-- Results Header -->
        <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold mb-6">
          <div class="p-6 border-b border-primary-100">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-xl font-bold text-gray-900">
                  <i class="fas fa-list-alt text-primary-500 mr-2"></i>
                  Availability Results
                </h3>
                <p class="text-sm text-gray-600" id="resultsSummary"></p>
              </div>
              <button type="button" onclick="printResults()" 
                      class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-colors">
                <i class="fas fa-print mr-2"></i> Print Results
              </button>
            </div>
          </div>
        </div>

        <!-- Results Grid -->
        <div id="resultsGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Results will be dynamically inserted here -->
        </div>

        <!-- Alternative Options -->
        <div id="alternativeOptions" class="hidden glass-effect rounded-2xl border border-primary-100 shadow-gold p-6 mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-lightbulb text-primary-500 mr-2"></i>
            Alternative Options
          </h3>
          <div id="alternativesList" class="space-y-4">
            <!-- Alternatives will be dynamically inserted here -->
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-cogs text-primary-500 mr-2"></i>
            Next Steps
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button type="button" onclick="bookNow()" 
                    class="p-4 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
              <i class="fas fa-calendar-plus mr-2"></i> Book Now
            </button>
            <button type="button" onclick="holdReservation()" 
                    class="p-4 rounded-xl bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition-colors">
              <i class="fas fa-clock mr-2"></i> Hold Reservation (24h)
            </button>
            <button type="button" onclick="contactCustomer()" 
                    class="p-4 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
              <i class="fas fa-phone-alt mr-2"></i> Contact Customer
            </button>
          </div>
        </div>
      </div>

      <!-- Quick Search Templates -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold mt-8 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
          <i class="fas fa-bolt text-primary-500 mr-2"></i>
          Quick Search Templates
        </h3>
        <p class="text-gray-700 mb-4">Use these pre-filled templates for common searches:</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button type="button" onclick="loadTemplate('thailand_change')" 
                  class="p-4 rounded-xl bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors text-left">
            <div class="flex items-center mb-2">
              <i class="fas fa-sync-alt text-blue-600 mr-2"></i>
              <span class="font-semibold text-gray-900">Thailand Date Change</span>
            </div>
            <p class="text-sm text-gray-600">Check Apr 20-May 4, 2024 availability for 2 adults</p>
          </button>
          
          <button type="button" onclick="loadTemplate('bali_wellness')" 
                  class="p-4 rounded-xl bg-green-50 hover:bg-green-100 border border-green-200 transition-colors text-left">
            <div class="flex items-center mb-2">
              <i class="fas fa-spa text-green-600 mr-2"></i>
              <span class="font-semibold text-gray-900">Bali Wellness</span>
            </div>
            <p class="text-sm text-gray-600">Check Bali retreat availability for single traveler</p>
          </button>
          
          <button type="button" onclick="loadTemplate('vietnam_upgrade')" 
                  class="p-4 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 transition-colors text-left">
            <div class="flex items-center mb-2">
              <i class="fas fa-star text-purple-600 mr-2"></i>
              <span class="font-semibold text-gray-900">Vietnam Upgrade</span>
            </div>
            <p class="text-sm text-gray-600">Check premium Vietnam tour upgrades</p>
          </button>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-primary-100 bg-primary-50 mt-12">
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
            Real-time availability checking for seamless bookings.
          </p>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="support_bookings.php" class="hover:text-primary-600 transition-colors">Booking Operations</a></li>
            <li><a href="cancel_booking.php" class="hover:text-primary-600 transition-colors">Cancel Booking</a></li>
            <li><a href="modify_booking.php" class="hover:text-primary-600 transition-colors">Modify Booking</a></li>
            <li><a href="process_refund.php" class="hover:text-primary-600 transition-colors">Process Refund</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Resources</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="inventory_guide.php" class="hover:text-primary-600 transition-colors">Inventory Guide</a></li>
            <li><a href="booking_policies.php" class="hover:text-primary-600 transition-colors">Booking Policies</a></li>
            <li><a href="availability_faq.php" class="hover:text-primary-600 transition-colors">Availability FAQ</a></li>
            <li><a href="supplier_contacts.php" class="hover:text-primary-600 transition-colors">Supplier Contacts</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Real-time Status</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-center gap-2">
              <div class="h-2 w-2 rounded-full bg-green-500"></div>
              <span>System: <span class="font-semibold text-green-600">Online</span></span>
            </li>
            <li class="flex items-center gap-2">
              <div class="h-2 w-2 rounded-full bg-green-500"></div>
              <span>Inventory: <span class="font-semibold text-green-600">Live Sync</span></span>
            </li>
            <li class="flex items-center gap-2">
              <i class="fas fa-clock text-primary-500"></i>
              <span>Last Updated: <span id="lastUpdatedTime">Just now</span></span>
            </li>
          </ul>
        </div>
      </div>

      <div class="pt-6 border-t border-primary-100 text-center text-sm text-gray-600">
        <p>© <?php echo date('Y'); ?> TravelEase Customer Support. Real-time availability data is subject to change.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript -->
  <script>
    // Set minimum dates
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('startDate').min = today;
      document.getElementById('endDate').min = today;
      
      // Set default dates (30 days from now for start, 37 days for end)
      const defaultStart = new Date();
      defaultStart.setDate(defaultStart.getDate() + 30);
      const defaultEnd = new Date();
      defaultEnd.setDate(defaultEnd.getDate() + 37);
      
      document.getElementById('startDate').value = defaultStart.toISOString().split('T')[0];
      document.getElementById('endDate').value = defaultEnd.toISOString().split('T')[0];
      
      // Update last updated time
      updateLastUpdatedTime();
      setInterval(updateLastUpdatedTime, 60000); // Update every minute
    });

    function updateLastUpdatedTime() {
      const now = new Date();
      const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
      document.getElementById('lastUpdatedTime').textContent = timeString;
    }

    // Load template data
    function loadTemplate(templateId) {
      const templates = {
        'thailand_change': {
          searchType: 'tour',
          destination: 'thailand',
          itemName: 'Thailand Island Hopping',
          startDate: '2024-04-20',
          endDate: '2024-05-04',
          adults: '2',
          children: '0',
          rooms: '1'
        },
        'bali_wellness': {
          searchType: 'tour',
          destination: 'bali',
          itemName: 'Bali Luxury Wellness Retreat',
          startDate: '2024-06-01',
          endDate: '2024-06-11',
          adults: '1',
          children: '0',
          rooms: '1'
        },
        'vietnam_upgrade': {
          searchType: 'tour',
          destination: 'vietnam',
          itemName: 'Vietnam Cultural Journey',
          startDate: '2024-05-10',
          endDate: '2024-05-24',
          adults: '2',
          children: '0',
          rooms: '1'
        }
      };

      if (templates[templateId]) {
        const template = templates[templateId];
        
        // Set radio button
        document.querySelector(`input[value="${template.searchType}"]`).checked = true;
        
        // Set form values
        document.getElementById('destination').value = template.destination;
        document.getElementById('itemName').value = template.itemName;
        document.getElementById('startDate').value = template.startDate;
        document.getElementById('endDate').value = template.endDate;
        document.getElementById('adults').value = template.adults;
        document.getElementById('children').value = template.children;
        document.getElementById('rooms').value = template.rooms;
        
        // Show success message
        alert(`Loaded "${templateId.replace('_', ' ')}" template`);
      }
    }

    // Check availability function
    function checkAvailability() {
      // Get form values
      const searchType = document.querySelector('input[name="searchType"]:checked').value;
      const destination = document.getElementById('destination').value;
      const itemName = document.getElementById('itemName').value;
      const startDate = document.getElementById('startDate').value;
      const endDate = document.getElementById('endDate').value;
      const adults = parseInt(document.getElementById('adults').value);
      const children = parseInt(document.getElementById('children').value);
      const rooms = parseInt(document.getElementById('rooms').value);
      const notes = document.getElementById('notes').value;
      
      // Validate form
      if (!destination || !itemName || !startDate || !endDate) {
        alert('Please fill in all required fields');
        return;
      }
      
      if (new Date(startDate) >= new Date(endDate)) {
        alert('End date must be after start date');
        return;
      }
      
      // Calculate duration
      const start = new Date(startDate);
      const end = new Date(endDate);
      const duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
      
      // Simulate API call and show results
      simulateAvailabilityCheck(searchType, destination, itemName, startDate, endDate, duration, adults, children, rooms, notes);
    }

    // Simulate availability check (in real app, this would be an API call)
    function simulateAvailabilityCheck(searchType, destination, itemName, startDate, endDate, duration, adults, children, rooms, notes) {
      // Show loading state
      const resultsSection = document.getElementById('resultsSection');
      const resultsGrid = document.getElementById('resultsGrid');
      const resultsSummary = document.getElementById('resultsSummary');
      
      resultsSection.classList.remove('hidden');
      resultsGrid.innerHTML = '<div class="col-span-2 p-8 text-center"><i class="fas fa-spinner fa-spin text-4xl text-primary-500 mb-4"></i><p class="text-gray-700">Checking availability...</p></div>';
      
      // Simulate API delay
      setTimeout(() => {
        // Generate mock results based on search type
        let results = [];
        let alternatives = [];
        
        switch(searchType) {
          case 'tour':
            results = generateTourResults(destination, itemName, startDate, endDate, duration, adults, children);
            alternatives = generateTourAlternatives(destination, startDate, endDate, adults, children);
            break;
          case 'hotel':
            results = generateHotelResults(destination, itemName, startDate, endDate, duration, rooms);
            alternatives = generateHotelAlternatives(destination, startDate, endDate, rooms);
            break;
          case 'flight':
            results = generateFlightResults(destination, itemName, startDate, adults + children);
            alternatives = generateFlightAlternatives(destination, startDate, adults + children);
            break;
        }
        
        // Update results summary
        const totalTravelers = adults + children;
        resultsSummary.textContent = `${searchType.charAt(0).toUpperCase() + searchType.slice(1)} availability for "${itemName}" - ${startDate} to ${endDate} (${duration} days, ${totalTravelers} traveler${totalTravelers !== 1 ? 's' : ''})`;
        
        // Display results
        displayResults(results, searchType);
        
        // Display alternatives if main item is unavailable
        const mainAvailable = results.some(r => r.availability === 'available' || r.availability === 'limited');
        if (!mainAvailable && alternatives.length > 0) {
          displayAlternatives(alternatives, searchType);
        }
        
        // Scroll to results
        resultsSection.scrollIntoView({ behavior: 'smooth' });
        
      }, 1500); // Simulate 1.5 second API delay
    }

    // Generate mock tour results
    function generateTourResults(destination, itemName, startDate, endDate, duration, adults, children) {
      const availabilityOptions = ['available', 'limited', 'unavailable', 'waitlist'];
      const availabilityWeights = [0.6, 0.2, 0.15, 0.05]; // 60% available, 20% limited, etc.
      
      const results = [
        {
          id: 1,
          name: itemName,
          type: 'Standard Package',
          description: 'Includes accommodations, tours, and some meals',
          availability: getWeightedRandom(availabilityOptions, availabilityWeights),
          availableSpots: Math.floor(Math.random() * 10) + 1,
          pricePerPerson: getPriceByDestination(destination, 'standard'),
          totalPrice: 0, // Will be calculated
          duration: duration,
          includes: ['Accommodation', 'Daily Breakfast', 'Guided Tours', 'Transportation'],
          notes: 'Minimum 2 adults required'
        },
        {
          id: 2,
          name: itemName,
          type: 'Premium Package',
          description: 'Luxury accommodations, all meals, private guide',
          availability: getWeightedRandom(availabilityOptions, availabilityWeights),
          availableSpots: Math.floor(Math.random() * 5) + 1,
          pricePerPerson: getPriceByDestination(destination, 'premium'),
          totalPrice: 0,
          duration: duration,
          includes: ['Luxury Accommodation', 'All Meals', 'Private Guide', 'Airport Transfers', 'Spa Credit'],
          notes: 'Single supplement applies'
        },
        {
          id: 3,
          name: itemName,
          type: 'Custom Private Tour',
          description: 'Fully customizable private tour experience',
          availability: 'available', // Always available for custom tours
          availableSpots: 'Custom',
          pricePerPerson: getPriceByDestination(destination, 'custom'),
          totalPrice: 0,
          duration: duration,
          includes: ['Custom Itinerary', 'Private Guide & Vehicle', 'All Meals', 'VIP Experiences'],
          notes: 'Requires 7 days advance notice'
        }
      ];
      
      // Calculate total prices
      results.forEach(result => {
        const totalTravelers = adults + children;
        const childDiscount = 0.7; // 30% discount for children
        const adultPrice = result.pricePerPerson * adults;
        const childPrice = result.pricePerPerson * childDiscount * children;
        result.totalPrice = Math.round(adultPrice + childPrice);
      });
      
      return results;
    }

    // Generate mock hotel results
    function generateHotelResults(destination, hotelName, startDate, endDate, duration, rooms) {
      const roomTypes = [
        { type: 'Standard Room', category: 'standard' },
        { type: 'Deluxe Room', category: 'deluxe' },
        { type: 'Suite', category: 'suite' },
        { type: 'Family Room', category: 'family' }
      ];
      
      return roomTypes.map((room, index) => {
        const availability = Math.random() > 0.3 ? 'available' : (Math.random() > 0.5 ? 'limited' : 'unavailable');
        const availableRooms = availability === 'available' ? Math.floor(Math.random() * 5) + 1 : 
                              availability === 'limited' ? Math.floor(Math.random() * 2) + 1 : 0;
        
        return {
          id: index + 1,
          name: hotelName,
          type: room.type,
          description: `${room.type} with standard amenities`,
          availability: availability,
          availableRooms: availableRooms,
          pricePerNight: getHotelPriceByCategory(room.category),
          totalPrice: Math.round(getHotelPriceByCategory(room.category) * duration * rooms),
          duration: duration,
          includes: ['Free WiFi', 'Daily Housekeeping', 'Fitness Center Access'],
          notes: availability === 'limited' ? 'Only ' + availableRooms + ' room(s) left' : ''
        };
      });
    }

    // Generate mock flight results
    function generateFlightResults(destination, flightNumber, date, passengers) {
      const airlines = ['Thai Airways', 'Singapore Airlines', 'Qatar Airways', 'Emirates', 'Cathay Pacific'];
      const classes = ['Economy', 'Premium Economy', 'Business', 'First'];
      
      return classes.map((flightClass, index) => {
        const availability = Math.random() > 0.4 ? 'available' : (Math.random() > 0.5 ? 'limited' : 'waitlist');
        const availableSeats = availability === 'available' ? Math.floor(Math.random() * 20) + 1 : 
                              availability === 'limited' ? Math.floor(Math.random() * 5) + 1 : 0;
        
        return {
          id: index + 1,
          name: flightNumber || `${airlines[Math.floor(Math.random() * airlines.length)]} FL ${Math.floor(Math.random() * 900) + 100}`,
          type: flightClass,
          description: `${flightClass} class flight`,
          availability: availability,
          availableSeats: availableSeats,
          pricePerPerson: getFlightPriceByClass(flightClass),
          totalPrice: Math.round(getFlightPriceByClass(flightClass) * passengers),
          duration: 'Non-stop',
          includes: getFlightIncludes(flightClass),
          notes: availability === 'limited' ? 'Only ' + availableSeats + ' seat(s) left' : ''
        };
      });
    }

    // Display results
    function displayResults(results, searchType) {
      const resultsGrid = document.getElementById('resultsGrid');
      resultsGrid.innerHTML = '';
      
      results.forEach((result, index) => {
        const resultCard = document.createElement('div');
        resultCard.className = 'glass-effect rounded-2xl border border-primary-100 shadow-gold p-6 fade-in';
        resultCard.style.animationDelay = `${index * 0.1}s`;
        
        let availabilityBadge = '';
        let availabilityText = '';
        let availabilityClass = '';
        
        switch(result.availability) {
          case 'available':
            availabilityBadge = '<i class="fas fa-check-circle mr-1"></i> Available';
            availabilityText = result.availableSpots ? `${result.availableSpots} spots available` : 'Available';
            availabilityClass = 'availability-available';
            break;
          case 'limited':
            availabilityBadge = '<i class="fas fa-exclamation-triangle mr-1"></i> Limited';
            availabilityText = result.availableSpots ? `Only ${result.availableSpots} left` : 'Limited availability';
            availabilityClass = 'availability-limited';
            break;
          case 'unavailable':
            availabilityBadge = '<i class="fas fa-times-circle mr-1"></i> Unavailable';
            availabilityText = 'Sold out';
            availabilityClass = 'availability-unavailable';
            break;
          case 'waitlist':
            availabilityBadge = '<i class="fas fa-clock mr-1"></i> Waitlist';
            availabilityText = 'Join waitlist';
            availabilityClass = 'availability-waitlist';
            break;
        }
        
        resultCard.innerHTML = `
          <div class="flex items-start justify-between mb-4">
            <div>
              <h4 class="font-bold text-lg text-gray-900">${result.name}</h4>
              <p class="text-sm text-primary-600">${result.type}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold ${availabilityClass}">
              ${availabilityBadge}
            </span>
          </div>
          
          <p class="text-gray-700 mb-4">${result.description}</p>
          
          <div class="space-y-3 mb-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Availability:</span>
              <span class="font-semibold ${result.availability === 'available' || result.availability === 'limited' ? 'text-green-600' : 'text-red-600'}">
                ${availabilityText}
              </span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Duration:</span>
              <span class="font-semibold text-gray-900">${result.duration} ${searchType === 'flight' ? '' : 'days'}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">${searchType === 'hotel' ? 'Price per night:' : searchType === 'flight' ? 'Price per person:' : 'Price per person:'}</span>
              <span class="font-semibold text-gray-900">$${result.pricePerPerson.toLocaleString()}</span>
            </div>
            
            <div class="flex items-center justify-between border-t border-gray-200 pt-2">
              <span class="font-semibold text-gray-900">Total Price:</span>
              <span class="text-xl font-bold text-primary-600">$${result.totalPrice.toLocaleString()}</span>
            </div>
          </div>
          
          <div class="mb-4">
            <p class="text-sm font-medium text-gray-700 mb-2">Includes:</p>
            <div class="flex flex-wrap gap-1">
              ${result.includes.map(item => `<span class="px-2 py-1 bg-primary-50 text-primary-700 text-xs rounded">${item}</span>`).join('')}
            </div>
          </div>
          
          ${result.notes ? `<div class="p-3 rounded-lg bg-yellow-50 border border-yellow-200 mb-4">
            <p class="text-sm text-yellow-700"><i class="fas fa-info-circle mr-1"></i> ${result.notes}</p>
          </div>` : ''}
          
          <div class="flex gap-2">
            <button type="button" onclick="selectOption(${result.id}, '${result.availability}')" 
                    class="flex-1 px-4 py-2 rounded-xl ${result.availability === 'available' || result.availability === 'limited' ? 'gold-gradient text-white' : 'bg-gray-200 text-gray-700'} font-semibold hover:opacity-90 transition-opacity"
                    ${result.availability === 'unavailable' ? 'disabled' : ''}>
              ${result.availability === 'available' || result.availability === 'limited' ? 'Select' : result.availability === 'waitlist' ? 'Join Waitlist' : 'Unavailable'}
            </button>
            <button type="button" onclick="viewDetails(${result.id})" 
                    class="px-4 py-2 rounded-xl border border-primary-200 text-primary-700 font-semibold hover:bg-primary-50 transition-colors">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        `;
        
        resultsGrid.appendChild(resultCard);
      });
    }

    // Helper functions
    function getWeightedRandom(options, weights) {
      const totalWeight = weights.reduce((a, b) => a + b, 0);
      let random = Math.random() * totalWeight;
      
      for (let i = 0; i < options.length; i++) {
        random -= weights[i];
        if (random <= 0) {
          return options[i];
        }
      }
      
      return options[options.length - 1];
    }

    function getPriceByDestination(destination, type) {
      const prices = {
        thailand: { standard: 1200, premium: 2200, custom: 3500 },
        vietnam: { standard: 1100, premium: 2000, custom: 3200 },
        japan: { standard: 2500, premium: 4500, custom: 6500 },
        bali: { standard: 1000, premium: 1800, custom: 2800 },
        default: { standard: 1500, premium: 2500, custom: 4000 }
      };
      
      return prices[destination] ? prices[destination][type] : prices.default[type];
    }

    function getHotelPriceByCategory(category) {
      const prices = {
        standard: 150,
        deluxe: 250,
        suite: 450,
        family: 350
      };
      return prices[category] || 200;
    }

    function getFlightPriceByClass(flightClass) {
      const prices = {
        'Economy': 800,
        'Premium Economy': 1200,
        'Business': 2500,
        'First': 5000
      };
      return prices[flightClass] || 1000;
    }

    function getFlightIncludes(flightClass) {
      const includes = {
        'Economy': ['Meal', 'Entertainment', '20kg Baggage'],
        'Premium Economy': ['Premium Meal', 'Extra Legroom', '30kg Baggage', 'Priority Check-in'],
        'Business': ['Lie-flat Seat', 'Gourmet Dining', '40kg Baggage', 'Lounge Access', 'Priority Boarding'],
        'First': ['Private Suite', 'Fine Dining', '50kg Baggage', 'Chauffeur Service', 'Exclusive Lounge']
      };
      return includes[flightClass] || includes['Economy'];
    }

    // Generate and display alternatives (simplified)
    function generateTourAlternatives(destination, startDate, endDate, adults, children) {
      // In a real app, this would fetch real alternatives
      return [
        { name: 'Similar Tour in Same Region', price: 'Comparable', availability: 'available' },
        { name: 'Different Dates', price: 'Same', availability: 'available' },
        { name: 'Upgraded Package', price: '20% higher', availability: 'available' }
      ];
    }

    function displayAlternatives(alternatives, searchType) {
      const alternativesSection = document.getElementById('alternativeOptions');
      const alternativesList = document.getElementById('alternativesList');
      
      alternativesList.innerHTML = alternatives.map(alt => `
        <div class="p-4 rounded-xl border border-gray-200 hover:border-primary-300 transition-colors">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-semibold text-gray-900">${alt.name}</h4>
              <p class="text-sm text-gray-600">Price: ${alt.price}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold availability-available">
              <i class="fas fa-check-circle mr-1"></i> Available
            </span>
          </div>
          <button type="button" onclick="exploreAlternative('${alt.name}')" 
                  class="mt-3 text-sm text-primary-600 hover:text-primary-800 font-semibold">
            <i class="fas fa-search mr-1"></i> Explore this option
          </button>
        </div>
      `).join('');
      
      alternativesSection.classList.remove('hidden');
    }

    // Action functions
    function selectOption(optionId, availability) {
      if (availability === 'unavailable') {
        alert('This option is currently unavailable. Please select an available option.');
        return;
      }
      
      if (availability === 'waitlist') {
        if (confirm('This option is on waitlist. Would you like to add the customer to the waitlist?')) {
          alert('Customer added to waitlist successfully!');
        }
        return;
      }
      
      alert(`Option ${optionId} selected! Ready to proceed with booking.`);
      // In real app, this would store the selected option and proceed to booking
    }

    function viewDetails(optionId) {
      alert(`Viewing detailed information for option ${optionId}`);
      // In real app, this would open a modal with detailed information
    }

    function exploreAlternative(alternativeName) {
      // Pre-fill form with alternative search
      document.getElementById('itemName').value = alternativeName;
      alert(`Form updated to search for "${alternativeName}"`);
    }

    function bookNow() {
      if (confirm('Proceed to booking with selected option?')) {
        // In real app, this would redirect to booking page
        alert('Redirecting to booking page...');
        // window.location.href = 'booking_page.php';
      }
    }

    function holdReservation() {
      const holdDuration = prompt('Enter hold duration in hours (max 48):', '24');
      if (holdDuration && parseInt(holdDuration) > 0 && parseInt(holdDuration) <= 48) {
        alert(`Reservation held for ${holdDuration} hours!`);
        // In real app, this would create a reservation hold
      }
    }

    function contactCustomer() {
      alert('Opening customer contact form...');
      // In real app, this would open contact form
      // window.location.href = 'contact_customer.php';
    }

    function printResults() {
      window.print();
    }
  </script>
</body>
</html>