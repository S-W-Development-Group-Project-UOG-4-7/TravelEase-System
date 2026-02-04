<?php
// contact_customer.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Customer | TravelEase Support</title>
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
                Contact Customer
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
          <a href="support_bookings.php" class="text-gray-700 hover:text-primary-600 transition-colors">
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
              Contact <span class="text-gradient">Customer</span>
            </h1>
            <p class="text-lg text-gray-700">Send messages and updates to customers regarding their bookings</p>
          </div>
          <a href="support_bookings.php"
             class="px-6 py-3 rounded-xl glass-effect text-gray-700 font-bold hover:bg-gray-50 transition-colors border border-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
          </a>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold p-6">
        <form id="contactCustomerForm" class="space-y-6">
          <!-- Customer Information -->
          <div class="p-4 rounded-xl bg-blue-50 border border-blue-200">
            <h4 class="font-bold text-lg text-gray-900 mb-3">Customer Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                <input type="text" 
                       name="customerName"
                       required
                       class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="Enter customer full name"
                       value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input type="email" 
                       name="customerEmail"
                       required
                       class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="customer@example.com"
                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                <input type="tel" 
                       name="customerPhone"
                       required
                       class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="+1 (555) 123-4567"
                       value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Reference *</label>
                <input type="text" 
                       name="bookingReference"
                       required
                       class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                       placeholder="BK-1234"
                       value="<?php echo isset($_GET['booking']) ? htmlspecialchars($_GET['booking']) : ''; ?>">
              </div>
            </div>
          </div>

          <!-- Contact Method -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-4">Contact Method *</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div class="relative">
                <input type="radio" name="contactMethod" id="methodEmail" value="email" class="sr-only peer" checked>
                <label for="methodEmail" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                  <i class="fas fa-envelope text-2xl text-gray-600 mb-2"></i>
                  <span class="font-semibold text-gray-900">Email</span>
                  <span class="text-xs text-gray-600 mt-1">Send email to customer</span>
                </label>
              </div>
              
              <div class="relative">
                <input type="radio" name="contactMethod" id="methodPhone" value="phone" class="sr-only peer">
                <label for="methodPhone" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                  <i class="fas fa-phone text-2xl text-gray-600 mb-2"></i>
                  <span class="font-semibold text-gray-900">Phone Call</span>
                  <span class="text-xs text-gray-600 mt-1">Make a phone call</span>
                </label>
              </div>
              
              <div class="relative">
                <input type="radio" name="contactMethod" id="methodSMS" value="sms" class="sr-only peer">
                <label for="methodSMS" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-colors cursor-pointer">
                  <i class="fas fa-comment-alt text-2xl text-gray-600 mb-2"></i>
                  <span class="font-semibold text-gray-900">SMS</span>
                  <span class="text-xs text-gray-600 mt-1">Send text message</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Message Details -->
          <div>
            <h4 class="font-bold text-lg text-gray-900 mb-4">Message Details</h4>
            
            <!-- Subject -->
            <div class="mb-4">
              <label for="messageSubject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
              <input type="text" 
                     id="messageSubject"
                     name="messageSubject"
                     required
                     class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                     placeholder="Enter message subject">
            </div>
            
            <!-- Message Type -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Message Type *</label>
              <select id="messageType" 
                      name="messageType"
                      required
                      class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="">Select message type</option>
                <option value="cancellation_update">Cancellation Update</option>
                <option value="refund_update">Refund Update</option>
                <option value="modification_request">Modification Request</option>
                <option value="booking_confirmation">Booking Confirmation</option>
                <option value="payment_issue">Payment Issue</option>
                <option value="general_inquiry">General Inquiry</option>
              </select>
            </div>
            
            <!-- Priority -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
              <select id="messagePriority" 
                      name="messagePriority"
                      required
                      class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="normal">Normal</option>
                <option value="high">High Priority</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
            
            <!-- Message Content -->
            <div class="mb-4">
              <label for="messageContent" class="block text-sm font-medium text-gray-700 mb-2">Message Content *</label>
              <textarea id="messageContent" 
                        name="messageContent"
                        rows="8"
                        required
                        class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                        placeholder="Type your message here..."></textarea>
            </div>
            
            <!-- Template Selection -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Use Template (Optional)</label>
              <select id="messageTemplate" 
                      name="messageTemplate"
                      onchange="loadTemplate(this.value)"
                      class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                <option value="">Select a template...</option>
                <option value="cancellation_refund">Cancellation & Refund Confirmation</option>
                <option value="modification_confirmed">Modification Request Confirmed</option>
                <option value="payment_received">Payment Received Confirmation</option>
                <option value="booking_updated">Booking Successfully Updated</option>
              </select>
            </div>
            
            <!-- Internal Notes -->
            <div class="mb-4">
              <label for="internalNotes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes (Optional)</label>
              <textarea id="internalNotes" 
                        name="internalNotes"
                        rows="3"
                        class="w-full p-3 rounded-xl border border-gray-300 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                        placeholder="Add internal notes about this contact..."></textarea>
            </div>
            
            <!-- Form validation error container -->
            <div id="contactFormErrors" class="hidden p-4 rounded-xl bg-red-50 border border-red-200">
              <div class="flex items-center gap-2 text-red-700">
                <i class="fas fa-exclamation-circle"></i>
                <span class="font-medium">Please fix the following errors:</span>
              </div>
              <ul id="contactErrorList" class="mt-2 ml-6 list-disc text-sm text-red-600"></ul>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <button type="button" 
                    onclick="window.location.href='support_bookings.php'"
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
                      onclick="sendCustomerMessage()"
                      class="px-6 py-3 rounded-xl gold-gradient text-white font-semibold hover:shadow-lg transition-shadow">
                <i class="fas fa-paper-plane mr-2"></i> Send Message
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Recent Contacts -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold mt-8 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
          <i class="fas fa-history text-primary-500 mr-2"></i>
          Recent Contacts
        </h3>
        
        <div class="space-y-3">
          <div class="p-3 rounded-xl border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="font-semibold text-gray-900">Sarah Johnson</p>
                <p class="text-sm text-gray-600">Regarding booking #BK-4567 - Japan Tour</p>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-600">Today, 10:30 AM</p>
                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Email Sent</span>
              </div>
            </div>
          </div>
          
          <div class="p-3 rounded-xl border border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="font-semibold text-gray-900">Michael Chen</p>
                <p class="text-sm text-gray-600">Regarding booking #BK-4566 - Bali Retreat</p>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-600">Yesterday, 3:15 PM</p>
                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Phone Call</span>
              </div>
            </div>
          </div>
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

  <!-- JavaScript -->
  <script>
    // Get URL parameters
    function getUrlParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    // Auto-fill form from URL parameters
    document.addEventListener('DOMContentLoaded', function() {
      const customerName = getUrlParam('name');
      const customerEmail = getUrlParam('email');
      const customerPhone = getUrlParam('phone');
      const bookingRef = getUrlParam('booking');
      
      if (customerName) {
        document.querySelector('input[name="customerName"]').value = customerName;
      }
      if (customerEmail) {
        document.querySelector('input[name="customerEmail"]').value = customerEmail;
      }
      if (customerPhone) {
        document.querySelector('input[name="customerPhone"]').value = customerPhone;
      }
      if (bookingRef) {
        document.querySelector('input[name="bookingReference"]').value = bookingRef;
        // Auto-generate subject
        const tripName = getUrlParam('trip') || 'Booking';
        document.getElementById('messageSubject').value = `Regarding your booking ${bookingRef} - ${tripName}`;
      }
    });

    // Template loading function
    function loadTemplate(templateId) {
      if (!templateId) return;
      
      const customerName = document.querySelector('input[name="customerName"]').value;
      const bookingRef = document.querySelector('input[name="bookingReference"]').value;
      
      const templates = {
        'cancellation_refund': {
          subject: 'Important Update Regarding Your Cancellation Request',
          content: `Dear ${customerName || '[Customer Name]'},

Thank you for contacting TravelEase regarding your booking #${bookingRef || '[Booking Reference]'}.

We have processed your cancellation request as per your instructions. The refund amount has been initiated and should reflect in your account within 5-7 business days.

If you have any questions or need further assistance, please don't hesitate to contact us.

Warm regards,
The TravelEase Support Team`
        },
        'modification_confirmed': {
          subject: 'Your Booking Modification Has Been Confirmed',
          content: `Dear ${customerName || '[Customer Name]'},

We're pleased to inform you that your modification request for booking #${bookingRef || '[Booking Reference]'} has been successfully processed.

Your updated itinerary will be sent to you shortly. All other arrangements remain as previously confirmed.

Should you have any questions about your updated booking, please contact our support team.

Thank you for choosing TravelEase.

Best regards,
The TravelEase Support Team`
        },
        'payment_received': {
          subject: 'Payment Confirmation for Your TravelEase Booking',
          content: `Dear ${customerName || '[Customer Name]'},

We have successfully received your payment for booking #${bookingRef || '[Booking Reference]'}.

Your booking is now confirmed. You will receive your detailed itinerary and travel documents 30 days prior to departure.

We're excited to welcome you on your TravelEase journey!

Kind regards,
The TravelEase Support Team`
        },
        'booking_updated': {
          subject: 'Your TravelEase Booking Has Been Updated',
          content: `Dear ${customerName || '[Customer Name]'},

This is to confirm that your booking #${bookingRef || '[Booking Reference]'} has been successfully updated as requested.

All changes have been processed and your new itinerary details are attached for your reference.

If you have any questions, please contact our support team.

Best regards,
The TravelEase Support Team`
        }
      };

      if (templates[templateId]) {
        document.getElementById('messageSubject').value = templates[templateId].subject;
        document.getElementById('messageContent').value = templates[templateId].content;
      }
    }

    // Form validation
    function validateForm() {
      const form = document.getElementById('contactCustomerForm');
      const errors = [];
      
      // Required field validation
      if (!form.customerName.value.trim()) errors.push('Customer name is required');
      if (!form.customerEmail.value.trim()) errors.push('Email address is required');
      if (!form.customerPhone.value.trim()) errors.push('Phone number is required');
      if (!form.bookingReference.value.trim()) errors.push('Booking reference is required');
      if (!form.messageSubject.value.trim()) errors.push('Subject is required');
      if (!form.messageType.value) errors.push('Message type is required');
      if (!form.messagePriority.value) errors.push('Priority is required');
      if (!form.messageContent.value.trim()) errors.push('Message content is required');
      
      return errors;
    }

    // Error display functions
    function hideErrors() {
      document.getElementById('contactFormErrors').classList.add('hidden');
      document.getElementById('contactErrorList').innerHTML = '';
    }

    function showErrors(errors) {
      const errorList = document.getElementById('contactErrorList');
      errorList.innerHTML = '';
      
      errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
      });
      
      document.getElementById('contactFormErrors').classList.remove('hidden');
    }

    // Send message function
    function sendCustomerMessage() {
      hideErrors();
      
      const errors = validateForm();
      if (errors.length > 0) {
        showErrors(errors);
        return;
      }
      
      // Collect form data
      const form = document.getElementById('contactCustomerForm');
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      // Add timestamp and agent
      data.timestamp = new Date().toISOString();
      data.agent = 'SA';
      
      console.log('Sending customer message:', data);
      
      // Simulate sending message
      setTimeout(() => {
        alert(`Message sent successfully to ${data.customerName}!\n\n` +
              `Contact Method: ${data.contactMethod}\n` +
              `Email: ${data.customerEmail}\n` +
              `Subject: ${data.messageSubject}\n\n` +
              `The customer has been notified.`);
        
        // Redirect back to bookings page
        window.location.href = 'support_bookings.php';
      }, 1000);
    }

    // Save as draft function
    function saveAsDraft() {
      const form = document.getElementById('contactCustomerForm');
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      data.savedAt = new Date().toISOString();
      
      console.log('Saving contact draft:', data);
      alert('Draft saved successfully!');
    }
  </script>
</body>
</html>