<?php
// cancel_booking.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cancel Booking | TravelEase Support</title>
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
                Cancel Booking
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
              Cancel <span class="text-gradient">Booking</span>
            </h1>
            <p class="text-lg text-gray-700">Process booking cancellations and refunds</p>
          </div>
          <a href="support_bookings.php" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
          </a>
        </div>
      </div>

      <!-- Cancel Booking Form -->
      <form method="POST" action="process_cancellation.php" class="glass-effect rounded-2xl p-6 border border-red-100 shadow-gold mb-8">
        <!-- Booking Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-red-100">
            <i class="fas fa-calendar-times text-red-500 mr-2"></i>Booking Information
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Booking Reference -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-hashtag text-red-500 mr-1"></i>Booking Reference *
              </label>
              <input type="text" name="booking_ref" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="BK-4567">
            </div>

            <!-- Customer Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user text-red-500 mr-1"></i>Customer Name *
              </label>
              <input type="text" name="customer_name" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="Sarah Johnson">
            </div>

            <!-- Departure Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-plane-departure text-red-500 mr-1"></i>Departure Date *
              </label>
              <input type="date" name="departure_date" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none">
            </div>

            <!-- Total Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-dollar-sign text-red-500 mr-1"></i>Total Amount ($) *
              </label>
              <input type="number" step="0.01" name="total_amount" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="4800.00">
            </div>
          </div>
        </div>

        <!-- Cancellation Details -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-red-100">
            <i class="fas fa-ban text-red-500 mr-2"></i>Cancellation Details
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cancellation Reason -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-exclamation-circle text-red-500 mr-1"></i>Cancellation Reason *
              </label>
              <select name="cancellation_reason" required 
                      class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none">
                <option value="">Select Reason</option>
                <option value="customer_request">Customer Request</option>
                <option value="medical">Medical Emergency</option>
                <option value="travel_restrictions">Travel Restrictions</option>
                <option value="weather">Weather Conditions</option>
                <option value="operator_cancellation">Operator Cancellation</option>
                <option value="schedule_change">Schedule Change</option>
                <option value="personal_reasons">Personal Reasons</option>
                <option value="financial_reasons">Financial Reasons</option>
                <option value="found_better_deal">Found Better Deal</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Cancellation Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-day text-red-500 mr-1"></i>Cancellation Date *
              </label>
              <input type="date" name="cancellation_date" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     value="<?php echo date('Y-m-d'); ?>">
            </div>

            <!-- Days Before Departure -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-clock text-red-500 mr-1"></i>Days Before Departure *
              </label>
              <input type="number" name="days_before_departure" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="30">
            </div>

            <!-- Refund Method -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-undo-alt text-red-500 mr-1"></i>Refund Method *
              </label>
              <select name="refund_method" required 
                      class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none">
                <option value="">Select Method</option>
                <option value="original_payment">Original Payment Method</option>
                <option value="credit_voucher">Travel Credit Voucher</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="check">Check</option>
              </select>
            </div>
          </div>

          <!-- Cancellation Notes -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <i class="fas fa-sticky-note text-red-500 mr-1"></i>Cancellation Notes *
            </label>
            <textarea name="cancellation_notes" required rows="4"
                      class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                      placeholder="Please provide detailed notes about the cancellation..."></textarea>
          </div>
        </div>

        <!-- Refund Calculation -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-red-100">
            <i class="fas fa-calculator text-red-500 mr-2"></i>Refund Calculation
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Refund Percentage -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Refund Percentage (%)</label>
              <input type="number" name="refund_percentage" min="0" max="100" 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="85">
            </div>

            <!-- Cancellation Fee -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Fee ($)</label>
              <input type="number" step="0.01" name="cancellation_fee" 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="200.00">
            </div>

            <!-- Refund Amount -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Refund Amount ($)</label>
              <input type="number" step="0.01" name="refund_amount" required 
                     class="w-full p-3 rounded-xl border border-red-200 focus:border-red-400 focus:ring-2 focus:ring-red-200 focus:outline-none"
                     placeholder="4600.00">
            </div>
          </div>

          <!-- Refund Breakdown -->
          <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200">
            <h4 class="font-semibold text-gray-900 mb-3">Refund Breakdown</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-700">Total Amount:</span>
                <span class="font-medium" id="displayTotalAmount">$0.00</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-700">Cancellation Fee:</span>
                <span class="font-medium text-red-600" id="displayCancellationFee">$0.00</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-700">Refund Amount:</span>
                <span class="font-medium text-green-600" id="displayRefundAmount">$0.00</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Options -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-red-100">
            <i class="fas fa-cog text-red-500 mr-2"></i>Additional Options
          </h3>
          
          <div class="space-y-4">
            <div class="flex items-center">
              <input type="checkbox" name="send_notification" id="send_notification" checked 
                     class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
              <label for="send_notification" class="ml-3 text-gray-700">
                Send cancellation confirmation to customer
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="issue_voucher" id="issue_voucher" 
                     class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
              <label for="issue_voucher" class="ml-3 text-gray-700">
                Issue travel credit voucher instead of refund
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="keep_deposit" id="keep_deposit" 
                     class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
              <label for="keep_deposit" class="ml-3 text-gray-700 font-semibold">
                Keep deposit (non-refundable)
              </label>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-red-100">
          <button type="submit" class="flex-1 px-6 py-4 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-shadow text-lg">
            <i class="fas fa-check-circle mr-2"></i>Process Cancellation
          </button>
          <button type="button" onclick="calculateRefund()" class="flex-1 px-6 py-4 rounded-xl bg-yellow-500 text-white font-bold hover:bg-yellow-600 transition-colors text-lg">
            <i class="fas fa-calculator mr-2"></i>Calculate Refund
          </button>
          <a href="support_bookings.php" class="flex-1 px-6 py-4 rounded-xl border-2 border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors text-lg text-center">
            <i class="fas fa-times mr-2"></i>Cancel
          </a>
        </div>
      </form>

      <!-- Cancellation Policy -->
      <div class="glass-effect rounded-2xl p-6 border border-red-100 shadow-gold">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          <i class="fas fa-info-circle text-red-500 mr-2"></i>Cancellation Policy
        </h3>
        <ul class="space-y-2 text-gray-700">
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Full refund if cancelled 30+ days before departure</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>75% refund if cancelled 15-29 days before departure</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>50% refund if cancelled 7-14 days before departure</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>25% refund if cancelled 3-6 days before departure</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-times text-red-500 mt-1 mr-2"></i>
            <span>No refund if cancelled less than 3 days before departure</span>
          </li>
        </ul>
      </div>
    </div>
  </main>

  <script>
    function calculateRefund() {
      const totalAmount = parseFloat(document.querySelector('input[name="total_amount"]').value) || 0;
      const daysBefore = parseInt(document.querySelector('input[name="days_before_departure"]').value) || 0;
      const cancellationFee = parseFloat(document.querySelector('input[name="cancellation_fee"]').value) || 0;
      
      let refundPercentage = 0;
      
      // Calculate refund percentage based on days before departure
      if (daysBefore >= 30) {
        refundPercentage = 100;
      } else if (daysBetween >= 15) {
        refundPercentage = 75;
      } else if (daysBetween >= 7) {
        refundPercentage = 50;
      } else if (daysBetween >= 3) {
        refundPercentage = 25;
      } else {
        refundPercentage = 0;
      }
      
      // Calculate refund amount
      const refundAmount = ((totalAmount * refundPercentage) / 100) - cancellationFee;
      const finalRefundAmount = Math.max(0, refundAmount);
      
      // Update form fields
      document.querySelector('input[name="refund_percentage"]').value = refundPercentage;
      document.querySelector('input[name="refund_amount"]').value = finalRefundAmount.toFixed(2);
      
      // Update display
      document.getElementById('displayTotalAmount').textContent = '$' + totalAmount.toFixed(2);
      document.getElementById('displayCancellationFee').textContent = '$' + cancellationFee.toFixed(2);
      document.getElementById('displayRefundAmount').textContent = '$' + finalRefundAmount.toFixed(2);
      
      // Show summary
      alert(`Refund Calculation Complete:\n\n` +
            `Total Amount: $${totalAmount.toFixed(2)}\n` +
            `Days Before Departure: ${daysBefore}\n` +
            `Refund Percentage: ${refundPercentage}%\n` +
            `Cancellation Fee: $${cancellationFee.toFixed(2)}\n` +
            `Final Refund Amount: $${finalRefundAmount.toFixed(2)}`);
    }
    
    // Auto-calculate on input change
    document.addEventListener('DOMContentLoaded', function() {
      const totalInput = document.querySelector('input[name="total_amount"]');
      const daysInput = document.querySelector('input[name="days_before_departure"]');
      const feeInput = document.querySelector('input[name="cancellation_fee"]');
      
      [totalInput, daysInput, feeInput].forEach(input => {
        input.addEventListener('change', calculateRefund);
      });
    });
  </script>
</body>
</html>