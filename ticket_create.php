<?php
// new_ticket.php
session_start();

// Check if user is logged in (optional)
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data here
    // For demonstration, we'll just redirect back to tickets page
    header("Location: support_tickets.php?created=success");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create New Ticket | TravelEase Support</title>
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
    .priority-high { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); color: #dc2626; border: 1px solid #fca5a5; }
    .priority-medium { background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%); color: #d97706; border: 1px solid #fcd34d; }
    .priority-low { background: linear-gradient(135deg, #f0f9ff 0%, #bae6fd 100%); color: #0369a1; border: 1px solid #7dd3fc; }
    
    /* Custom file upload */
    .file-upload {
      position: relative;
      overflow: hidden;
    }
    .file-upload input[type="file"] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 100px;
      text-align: right;
      filter: alpha(opacity=0);
      opacity: 0;
      outline: none;
      background: white;
      cursor: pointer;
      display: block;
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
                Support Tickets
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
          <a href="new_ticket.php" class="text-primary-600 font-bold">
            <i class="fas fa-plus mr-2"></i>
            New Ticket
          </a>
          <a href="support_chatbot.php" class="text-gray-700 hover:text-primary-600 transition-colors">
            <i class="fas fa-robot mr-2"></i>
            Chatbot
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
              Create <span class="text-gradient">New Ticket</span>
            </h1>
            <p class="text-lg text-gray-700">Fill in the details to create a new support ticket</p>
          </div>
          <a href="support_tickets.php" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Tickets
          </a>
        </div>
      </div>

      <!-- Ticket Form -->
      <form method="POST" action="new_ticket.php" class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold mb-8">
        
        <!-- Customer Information Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-primary-100">
            <i class="fas fa-user-circle mr-2 text-primary-500"></i>Customer Information
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Selection/Add -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-1 text-primary-500"></i>Customer
              </label>
              <div class="flex gap-2">
                <select name="customer_id" class="flex-1 p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="">Select Existing Customer</option>
                  <option value="1">Sarah Johnson (sarah.j@email.com)</option>
                  <option value="2">Michael Chen (michael.c@email.com)</option>
                  <option value="3">Robert Williams (robert.w@email.com)</option>
                  <option value="4">Emma Davis (emma.d@email.com)</option>
                </select>
                <span class="p-3 text-gray-500">OR</span>
              </div>
            </div>
            
            <!-- New Customer Button -->
            <div class="flex items-end">
              <button type="button" class="px-4 py-3 rounded-xl bg-primary-100 text-primary-700 font-semibold hover:bg-primary-200 transition-colors">
                <i class="fas fa-user-plus mr-2"></i>Add New Customer
              </button>
            </div>
          </div>

          <!-- New Customer Fields (hidden by default) -->
          <div id="newCustomerFields" class="hidden mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-primary-50 rounded-xl">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
              <input type="text" name="customer_name" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="John Doe">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input type="email" name="customer_email" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="john@example.com">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
              <input type="tel" name="customer_phone" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="+1 (555) 123-4567">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
              <select name="customer_type" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="regular">Regular Customer</option>
                <option value="vip">VIP Customer</option>
                <option value="first_time">First Time Customer</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Ticket Details Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-primary-100">
            <i class="fas fa-ticket-alt mr-2 text-primary-500"></i>Ticket Details
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Subject -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-heading mr-1 text-primary-500"></i>Subject *
              </label>
              <input type="text" name="subject" required class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="Brief description of the issue">
            </div>

            <!-- Category -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-folder mr-1 text-primary-500"></i>Category *
              </label>
              <select name="category" required class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="">Select Category</option>
                <option value="booking">Booking Issues</option>
                <option value="payment">Payment & Refunds</option>
                <option value="itinerary">Itinerary Changes</option>
                <option value="flight">Flight Issues</option>
                <option value="hotel">Hotel Reservations</option>
                <option value="visa">Visa & Documentation</option>
                <option value="cancellation">Cancellations</option>
                <option value="technical">Technical Issues</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Priority -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-exclamation-circle mr-1 text-primary-500"></i>Priority *
              </label>
              <div class="grid grid-cols-3 gap-2">
                <label class="cursor-pointer">
                  <input type="radio" name="priority" value="low" class="hidden peer">
                  <div class="p-3 rounded-xl text-center priority-low peer-checked:ring-2 peer-checked:ring-blue-500 transition-all">
                    <i class="fas fa-arrow-down"></i> Low
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" name="priority" value="medium" class="hidden peer" checked>
                  <div class="p-3 rounded-xl text-center priority-medium peer-checked:ring-2 peer-checked:ring-yellow-500 transition-all">
                    <i class="fas fa-equals"></i> Medium
                  </div>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" name="priority" value="high" class="hidden peer">
                  <div class="p-3 rounded-xl text-center priority-high peer-checked:ring-2 peer-checked:ring-red-500 transition-all">
                    <i class="fas fa-arrow-up"></i> High
                  </div>
                </label>
              </div>
            </div>

            <!-- Assign To -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user-check mr-1 text-primary-500"></i>Assign To
              </label>
              <select name="assigned_to" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="">Unassigned</option>
                <option value="1">Support Agent 1</option>
                <option value="2">Support Agent 2</option>
                <option value="3">Senior Support</option>
                <option value="4">Billing Department</option>
              </select>
            </div>

            <!-- Related Booking -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar-check mr-1 text-primary-500"></i>Related Booking (Optional)
              </label>
              <input type="text" name="booking_ref" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="Booking ID #">
            </div>
          </div>
        </div>

        <!-- Description Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-primary-100">
            <i class="fas fa-align-left mr-2 text-primary-500"></i>Description
          </h3>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <i class="fas fa-comment-dots mr-1 text-primary-500"></i>Detailed Description *
            </label>
            <textarea name="description" required rows="6" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none" placeholder="Please provide a detailed description of the issue..."></textarea>
            <p class="text-sm text-gray-500 mt-2">Include specific dates, amounts, error messages, or any relevant details.</p>
          </div>

          <!-- Quick Templates -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <i class="fas fa-bolt mr-1 text-primary-500"></i>Quick Templates
            </label>
            <div class="flex flex-wrap gap-2">
              <button type="button" onclick="insertTemplate('payment')" class="px-3 py-2 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors">
                Payment Issue
              </button>
              <button type="button" onclick="insertTemplate('booking')" class="px-3 py-2 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors">
                Booking Problem
              </button>
              <button type="button" onclick="insertTemplate('change')" class="px-3 py-2 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors">
                Itinerary Change
              </button>
              <button type="button" onclick="insertTemplate('refund')" class="px-3 py-2 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors">
                Refund Request
              </button>
            </div>
          </div>
        </div>

        <!-- Attachments Section -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-primary-100">
            <i class="fas fa-paperclip mr-2 text-primary-500"></i>Attachments
          </h3>
          
          <div class="file-upload">
            <div class="p-8 border-2 border-dashed border-primary-300 rounded-xl text-center hover:bg-primary-50 transition-colors">
              <i class="fas fa-cloud-upload-alt text-4xl text-primary-400 mb-4"></i>
              <p class="text-gray-700 mb-2">Drag & drop files here or click to browse</p>
              <p class="text-sm text-gray-500 mb-4">Maximum file size: 10MB. Supported: JPG, PNG, PDF, DOC</p>
              <div class="relative inline-block">
                <button type="button" class="px-6 py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
                  <i class="fas fa-upload mr-2"></i>Browse Files
                </button>
                <input type="file" name="attachments[]" multiple class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer">
              </div>
            </div>
          </div>

          <!-- File List Preview -->
          <div id="fileList" class="mt-4 space-y-2 hidden">
            <!-- Files will be added here dynamically -->
          </div>
        </div>

        <!-- Additional Options -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-primary-100">
            <i class="fas fa-cog mr-2 text-primary-500"></i>Additional Options
          </h3>
          
          <div class="space-y-4">
            <div class="flex items-center">
              <input type="checkbox" name="notify_customer" id="notify_customer" checked class="h-5 w-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
              <label for="notify_customer" class="ml-3 text-gray-700">
                Send notification email to customer
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="internal_note" id="internal_note" class="h-5 w-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
              <label for="internal_note" class="ml-3 text-gray-700">
                Add as internal note (customer won't see this)
              </label>
            </div>
            
            <div class="flex items-center">
              <input type="checkbox" name="urgent" id="urgent" class="h-5 w-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
              <label for="urgent" class="ml-3 text-gray-700 font-semibold text-red-600">
                <i class="fas fa-exclamation-triangle mr-1"></i>Mark as Urgent (24-hour response required)
              </label>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-primary-100">
          <button type="submit" class="flex-1 px-6 py-4 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow text-lg">
            <i class="fas fa-check-circle mr-2"></i>Create Ticket
          </button>
          <button type="button" onclick="saveAsDraft()" class="flex-1 px-6 py-4 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition-colors text-lg">
            <i class="fas fa-save mr-2"></i>Save as Draft
          </button>
          <a href="support_tickets.php" class="flex-1 px-6 py-4 rounded-xl border-2 border-primary-300 text-primary-700 font-bold hover:bg-primary-50 transition-colors text-lg text-center">
            <i class="fas fa-times mr-2"></i>Cancel
          </a>
        </div>
      </form>

      <!-- Quick Tips -->
      <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          <i class="fas fa-lightbulb mr-2 text-primary-500"></i>Quick Tips
        </h3>
        <ul class="space-y-2 text-gray-700">
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Include booking references whenever possible for faster resolution</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Attach relevant documents (boarding passes, receipts, screenshots)</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Be specific about dates, times, and amounts in your description</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
            <span>Use High priority for time-sensitive issues like flight changes within 24 hours</span>
          </li>
        </ul>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-primary-100 bg-primary-50 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center text-sm text-gray-600">
        <p>© <?php echo date('Y'); ?> TravelEase Customer Support. Internal Use Only.</p>
        <p class="mt-2">Ticket ID will be automatically generated upon creation.</p>
      </div>
    </div>
  </footer>

  <script>
    // Show/hide new customer fields
    document.addEventListener('DOMContentLoaded', function() {
      const customerSelect = document.querySelector('select[name="customer_id"]');
      const newCustomerBtn = document.querySelector('button[type="button"]');
      const newCustomerFields = document.getElementById('newCustomerFields');
      
      newCustomerBtn.addEventListener('click', function() {
        newCustomerFields.classList.toggle('hidden');
        customerSelect.value = '';
      });
      
      customerSelect.addEventListener('change', function() {
        if (this.value) {
          newCustomerFields.classList.add('hidden');
        }
      });

      // File upload preview
      const fileInput = document.querySelector('input[type="file"]');
      const fileList = document.getElementById('fileList');
      
      fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        fileList.classList.remove('hidden');
        
        for (const file of this.files) {
          const fileItem = document.createElement('div');
          fileItem.className = 'flex items-center justify-between p-3 bg-white rounded-lg border border-primary-200';
          fileItem.innerHTML = `
            <div class="flex items-center">
              <i class="fas fa-file text-primary-500 mr-3"></i>
              <div>
                <div class="font-medium text-gray-900">${file.name}</div>
                <div class="text-sm text-gray-500">${(file.size / 1024).toFixed(2)} KB</div>
              </div>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700">
              <i class="fas fa-times"></i>
            </button>
          `;
          
          // Add remove functionality
          fileItem.querySelector('button').addEventListener('click', function() {
            // Remove from input (simplified - for production use DataTransfer)
            fileItem.remove();
            if (fileList.children.length === 0) {
              fileList.classList.add('hidden');
            }
          });
          
          fileList.appendChild(fileItem);
        }
      });
    });

    // Insert template text
    function insertTemplate(type) {
      const textarea = document.querySelector('textarea[name="description"]');
      const templates = {
        payment: "I'm having issues with my payment. The transaction appears to have failed but my card was charged. Transaction details: [Please provide transaction ID, amount, and date]",
        booking: "I'm unable to access/view my booking. When I try to retrieve it, I receive error message: [Please include exact error message]. Booking reference: [Booking ID]",
        change: "I need to make changes to my itinerary. Current booking: [Details]. Requested changes: [Specific changes with dates and times]",
        refund: "I would like to request a refund for [service/booking]. Reason for refund: [Detailed reason]. Booking reference: [Booking ID], Amount: [Amount]"
      };
      
      textarea.value = templates[type] || '';
      textarea.focus();
    }

    // Save as draft
    function saveAsDraft() {
      if (confirm('Save this ticket as draft? You can continue working on it later.')) {
        // In a real application, this would submit to a draft endpoint
        alert('Ticket saved as draft!');
        window.location.href = 'support_tickets.php';
      }
    }
  </script>
</body>
</html>