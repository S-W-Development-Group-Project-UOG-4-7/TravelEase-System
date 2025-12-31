<?php
// modify_booking.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Modify Booking | TravelEase Support</title>
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
                Modify Booking
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
          <a href="support_bookings.php" class="text-primary-600 font-bold">
            <i class="fas fa-calendar-check mr-2"></i>
            Bookings
          </a>
          <a href="support_tickets.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-ticket-alt mr-2"></i>
            Tickets
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
              Modify <span class="text-gradient">Booking</span>
            </h1>
            <p class="text-lg text-gray-700">Update booking details and make changes</p>
          </div>
          <a href="support_bookings.php" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
          </a>
        </div>
      </div>

      <!-- Modify Booking Form -->
      <form method="POST" action="process_modification.php" class="glass-effect rounded-2xl p-6 border border-blue-100 shadow-gold mb-8">
        <!-- Current Booking Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-blue-100">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>Current Booking Information
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Booking Reference -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-hashtag text-blue-500 mr-1"></i>Booking Reference *
              </label>
              <input type="text" name="booking_ref" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="BK-4568">
            </div>

            <!-- Customer Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user text-blue-500 mr-1"></i>Customer Name *
              </label>
              <input type="text" name="customer_name" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="Robert Williams">
            </div>

            <!-- Current Departure Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-plane-departure text-blue-500 mr-1"></i>Current Departure Date *
              </label>
              <input type="date" name="current_departure" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     value="2024-04-10">
            </div>

            <!-- Current Return Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-plane-arrival text-blue-500 mr-1"></i>Current Return Date *
              </label>
              <input type="date" name="current_return" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     value="2024-04-24">
            </div>

            <!-- Current Room Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-bed text-blue-500 mr-1"></i>Current Room Type *
              </label>
              <select name="current_room" required 
                      class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                <option value="standard">Standard Room</option>
                <option value="deluxe">Deluxe Room</option>
                <option value="suite" selected>Suite</option>
                <option value="villa">Villa</option>
              </select>
            </div>

            <!-- Current Total Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-dollar-sign text-blue-500 mr-1"></i>Current Total ($) *
              </label>
              <input type="number" step="0.01" name="current_total" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="3200.00" value="3200.00">
            </div>
          </div>
        </div>

        <!-- Requested Changes -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-blue-100">
            <i class="fas fa-sync-alt text-blue-500 mr-2"></i>Requested Changes
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- New Departure Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-plus text-blue-500 mr-1"></i>New Departure Date
              </label>
              <input type="date" name="new_departure" 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     value="2024-04-20">
            </div>

            <!-- New Return Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-plus text-blue-500 mr-1"></i>New Return Date
              </label>
              <input type="date" name="new_return" 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     value="2024-05-04">
            </div>

            <!-- New Room Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-bed text-blue-500 mr-1"></i>New Room Type
              </label>
              <select name="new_room" 
                      class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                <option value="">No Change</option>
                <option value="standard">Standard Room</option>
                <option value="deluxe">Deluxe Room</option>
                <option value="suite">Suite</option>
                <option value="villa">Villa</option>
              </select>
            </div>

            <!-- Number of Passengers -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-users text-blue-500 mr-1"></i>Number of Passengers
              </label>
              <input type="number" name="passengers" min="1" max="10" 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="2" value="2">
            </div>

            <!-- Modification Type -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-edit text-blue-500 mr-1"></i>Modification Type *
              </label>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <label class="cursor-pointer">
                  <input type="checkbox" name="modification_type[]" value="date_change" class="hidden peer" checked>
                  <div class="p-3 rounded-xl text-center bg-blue-50 border border-blue-200 text-gray-700 peer-checked:bg-blue-100 peer-checked:border-blue-400 peer-checked:text-blue-700 transition-all">
                    <i class="fas fa-calendar-alt mb-1"></i> Date Change
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="checkbox" name="modification_type[]" value="room_upgrade" class="hidden peer">
                  <div class="p-3 rounded-xl text-center bg-blue-50 border border-blue-200 text-gray-700 peer-checked:bg-blue-100 peer-checked:border-blue-400 peer-checked:text-blue-700 transition-all">
                    <i class="fas fa-star mb-1"></i> Room Upgrade
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="checkbox" name="modification_type[]" value="add_services" class="hidden peer">
                  <div class="p-3 rounded-xl text-center bg-blue-50 border border-blue-200 text-gray-700 peer-checked:bg-blue-100 peer-checked:border-blue-400 peer-checked:text-blue-700 transition-all">
                    <i class="fas fa-plus-circle mb-1"></i> Add Services
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="checkbox" name="modification_type[]" value="passenger_change" class="hidden peer">
                  <div class="p-3 rounded-xl text-center bg-blue-50 border border-blue-200 text-gray-700 peer-checked:bg-blue-100 peer-checked:border-blue-400 peer-checked:text-blue-700 transition-all">
                    <i class="fas fa-user-friends mb-1"></i> Passenger Change
                  </div>
                </label>
              </div>
            </div>
          </div>

          <!-- Change Details -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <i class="fas fa-align-left text-blue-500 mr-1"></i>Change Details *
            </label>
            <textarea name="change_details" required rows="4"
                      class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                      placeholder="Please provide detailed explanation of the requested changes..."></textarea>
          </div>
        </div>

        <!-- Cost Calculation -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-blue-100">
            <i class="fas fa-calculator text-blue-500 mr-2"></i>Cost Calculation
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Price Difference -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Price Difference ($)</label>
              <input type="number" step="0.01" name="price_difference" 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="800.00">
            </div>

            <!-- Modification Fee -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Modification Fee ($)</label>
              <input type="number" step="0.01" name="modification_fee" 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="100.00">
            </div>

            <!-- New Total Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">New Total Amount ($)</label>
              <input type="number" step="0.01" name="new_total" required 
                     class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                     placeholder="4000.00">
            </div>

            <!-- Payment Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
              <select name="payment_status" required 
                      class="w-full p-3 rounded-xl border border-blue-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                <option value="pending">Payment Pending</option>
                <option value="paid">Already Paid</option>
                <option value="refund">Refund Due</option>
                <option value="no_change">No Change Required</option>
              </select>
            </div>
          </div>

          <!-- Cost Breakdown -->
          <div class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-semibold text-gray-900 mb-3">Cost Breakdown</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-700">Current Total:</span>
                <span class="font-medium" id="displayCurrentTotal">$0.00</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-700">Price Difference:</span>
                <span class="font-medium" id="displayPriceDiff">$0.00</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-700">Modification Fee:</span>
                <span class="font-medium text-red-600" id="displayModFee">$0.00</span>
              </div>
              <div class="flex justify-between border-t border-blue-200 pt-2">
                <span class="text-gray-700 font-semibold">New Total:</span>
                <span class="font-bold text-green-600" id="displayNewTotal">$0.00</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Options -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-blue-100">
            <i class="fas fa-cog text-blue-500 mr-2"></i>Additional Options
          </h3>
          
          <div class="space-y-4">
            <div class="flex items-center">
              <input type="checkbox" name="check_availability" id="check_availability" checked 
                     class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
              <label for="check_availability" class="ml-3 text-gray-700">
                Check availability before confirming changes
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="contact_customer" id="contact_customer" checked 
                     class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
              <label for="contact_customer" class="ml-3 text-gray-700">
                Contact customer for confirmation
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="send_updated_itinerary" id="send_updated_itinerary" checked 
                     class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
              <label for="send_updated_itinerary" class="ml-3 text-gray-700">
                Send updated itinerary to customer
              </label>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-blue-100">
          <button type="submit" class="flex-1 px-6 py-4 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-shadow text-lg">
            <i class="fas fa-check-circle mr-2"></i>Apply Changes
          </button>
          <button type="button" onclick="calculateCosts()" class="flex-1 px-6 py-4 rounded-xl bg-yellow-500 text-white font-bold hover:bg-yellow-600 transition-colors text-lg">
            <i class="fas fa-calculator mr-2"></i>Calculate Costs
          </button>
          <a href="support_bookings.php" class="flex-1 px-6 py-4 rounded-xl border-2 border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors text-lg text-center">
            <i class="fas fa-times mr-2"></i>Cancel
          </a>
        </div>
      </form>

      <!-- Modification Notes -->
      <div class="glass-effect rounded-2xl p-6 border border-blue-100 shadow-gold">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          <i class="fas fa-lightbulb text-blue-500 mr-2"></i>Important Notes
        </h3>
        <ul class="space-y-2 text-gray-700">
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Always check room/flight availability before confirming date changes</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Standard modification fee is $100 per booking</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Contact customer for payment if additional costs apply</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Update all relevant systems (booking, payments, itineraries)</span>
          </li>
        </ul>
      </div>
    </div>
  </main>

  <script>
    function calculateCosts() {
      const currentTotal = parseFloat(document.querySelector('input[name="current_total"]').value) || 0;
      const priceDiff = parseFloat(document.querySelector('input[name="price_difference"]').value) || 0;
      const modFee = parseFloat(document.querySelector('input[name="modification_fee"]').value) || 0;
      
      // Calculate new total
      const newTotal = currentTotal + priceDiff + modFee;
      
      // Update form field
      document.querySelector('input[name="new_total"]').value = newTotal.toFixed(2);
      
      // Update display
      document.getElementById('displayCurrentTotal').textContent = '$' + currentTotal.toFixed(2);
      document.getElementById('displayPriceDiff').textContent = '$' + priceDiff.toFixed(2);
      document.getElementById('displayModFee').textContent = '$' + modFee.toFixed(2);
      document.getElementById('displayNewTotal').textContent = '$' + newTotal.toFixed(2);
      
      // Show summary
      alert(`Cost Calculation Complete:\n\n` +
            `Current Total: $${currentTotal.toFixed(2)}\n` +
            `Price Difference: $${priceDiff.toFixed(2)}\n` +
            `Modification Fee: $${modFee.toFixed(2)}\n` +
            `New Total: $${newTotal.toFixed(2)}`);
    }
    
    // Auto-calculate on input change
    document.addEventListener('DOMContentLoaded', function() {
      const currentInput = document.querySelector('input[name="current_total"]');
      const priceInput = document.querySelector('input[name="price_difference"]');
      const feeInput = document.querySelector('input[name="modification_fee"]');
      
      [currentInput, priceInput, feeInput].forEach(input => {
        input.addEventListener('change', calculateCosts);
      });
      
      // Initialize display
      calculateCosts();
    });
  </script>
</body>
</html>