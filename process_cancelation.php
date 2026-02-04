<?php
// cancellation_form.php
session_start();

// Check if user is logged in (optional)
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Initialize variables
$success = false;
$error = '';
$form_data = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $form_data = [
        'booking_id' => $_POST['booking_id'] ?? '',
        'customer_name' => $_POST['customer_name'] ?? '',
        'customer_email' => $_POST['customer_email'] ?? '',
        'phone_number' => $_POST['phone_number'] ?? '',
        'booking_date' => $_POST['booking_date'] ?? '',
        'trip_name' => $_POST['trip_name'] ?? '',
        'cancellation_reason' => $_POST['cancellation_reason'] ?? '',
        'additional_notes' => $_POST['additional_notes'] ?? '',
        'refund_method' => $_POST['refund_method'] ?? '',
        'emergency_contact' => $_POST['emergency_contact'] ?? ''
    ];
    
    // Basic validation
    $errors = [];
    
    if (empty($form_data['booking_id'])) {
        $errors[] = "Booking ID is required";
    }
    
    if (empty($form_data['customer_name'])) {
        $errors[] = "Customer name is required";
    }
    
    if (empty($form_data['customer_email']) || !filter_var($form_data['customer_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($form_data['cancellation_reason'])) {
        $errors[] = "Cancellation reason is required";
    }
    
    // If no errors, process the cancellation
    if (empty($errors)) {
        // Here you would typically:
        // 1. Save to database
        // 2. Send confirmation email
        // 3. Process refund
        // 4. Update booking status
        
        // For this example, we'll simulate success
        $success = true;
        
        // Generate cancellation reference
        $cancellation_ref = "CAN-" . strtoupper(uniqid());
        
        // You could save to database here:
        // $sql = "INSERT INTO cancellations (ref, booking_id, customer_name, customer_email, reason, notes, refund_method, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        // Execute query...
        
        // You could send email here:
        // mail($form_data['customer_email'], "Cancellation Confirmation", "Your cancellation has been processed. Reference: $cancellation_ref");
        
    } else {
        $error = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cancellation Request | TravelEase Support</title>
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
    .cancel-gradient {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
    }
    .text-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .text-gradient-red {
      background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .shadow-gold {
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    .shadow-red {
      box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.2);
    }
    .alert-success {
      background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%);
      border: 1px solid #22c55e;
      color: #166534;
    }
    .alert-error {
      background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
      border: 1px solid #ef4444;
      color: #991b1b;
    }
    
    /* Custom checkbox and radio */
    .custom-checkbox:checked {
      background-color: #f59e0b;
      border-color: #f59e0b;
    }
    
    .custom-radio:checked {
      background-color: #f59e0b;
      border-color: #f59e0b;
    }
    
    .step-indicator {
      position: relative;
    }
    
    .step-indicator.active .step-circle {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
      color: white;
      border-color: #f59e0b;
    }
    
    .step-indicator.completed .step-circle {
      background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
      color: white;
      border-color: #10b981;
    }
    
    .step-indicator .step-circle {
      width: 40px;
      height: 40px;
      border: 2px solid #d1d5db;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    
    /* Fade in animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
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
                Cancellation Process
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Success/Error Messages -->
      <?php if ($success): ?>
        <div class="alert-success rounded-2xl p-6 mb-8 shadow-red fade-in">
          <div class="flex items-start gap-4">
            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
              <i class="fas fa-check-circle text-2xl text-green-600"></i>
            </div>
            <div>
              <h3 class="font-bold text-xl text-green-900 mb-2">Cancellation Request Submitted Successfully!</h3>
              <p class="text-green-700 mb-4">
                Your cancellation request has been processed. Reference: <strong><?php echo $cancellation_ref; ?></strong>
              </p>
              <p class="text-green-700 mb-4">
                A confirmation email has been sent to <strong><?php echo htmlspecialchars($form_data['customer_email']); ?></strong>
              </p>
              <div class="flex gap-4 mt-6">
                <a href="cancellation_form.php" class="px-6 py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
                  <i class="fas fa-plus mr-2"></i> New Cancellation
                </a>
                <a href="support_tickets.php" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition-colors">
                  <i class="fas fa-ticket-alt mr-2"></i> View Tickets
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php elseif ($error): ?>
        <div class="alert-error rounded-2xl p-6 mb-8 shadow-red fade-in">
          <div class="flex items-start gap-4">
            <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
              <i class="fas fa-exclamation-circle text-2xl text-red-600"></i>
            </div>
            <div>
              <h3 class="font-bold text-xl text-red-900 mb-2">Error Processing Request</h3>
              <p class="text-red-700"><?php echo $error; ?></p>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
              Process <span class="text-gradient-red">Cancellation</span>
            </h1>
            <p class="text-lg text-gray-700">Submit and manage booking cancellation requests</p>
          </div>
          <div class="flex gap-3">
            <a href="support_tickets.php" class="px-4 py-3 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
          </div>
        </div>
      </div>

      <!-- Progress Steps -->
      <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold mb-8">
        <div class="flex items-center justify-between relative">
          <!-- Line -->
          <div class="absolute top-5 left-10 right-10 h-1 bg-gray-200 -z-10"></div>
          
          <!-- Step 1 -->
          <div class="step-indicator active flex flex-col items-center">
            <div class="step-circle mb-2">
              <i class="fas fa-user"></i>
            </div>
            <span class="text-sm font-semibold">Customer Info</span>
          </div>
          
          <!-- Step 2 -->
          <div class="step-indicator flex flex-col items-center">
            <div class="step-circle mb-2">
              <i class="fas fa-calendar-times"></i>
            </div>
            <span class="text-sm font-semibold">Booking Details</span>
          </div>
          
          <!-- Step 3 -->
          <div class="step-indicator flex flex-col items-center">
            <div class="step-circle mb-2">
              <i class="fas fa-comment-alt"></i>
            </div>
            <span class="text-sm font-semibold">Reason</span>
          </div>
          
          <!-- Step 4 -->
          <div class="step-indicator flex flex-col items-center">
            <div class="step-circle mb-2">
              <i class="fas fa-check-circle"></i>
            </div>
            <span class="text-sm font-semibold">Confirmation</span>
          </div>
        </div>
      </div>

      <!-- Cancellation Form -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold overflow-hidden fade-in">
        <div class="p-8">
          <form method="POST" action="" id="cancellationForm" class="space-y-6">
            <!-- Section 1: Customer Information -->
            <div class="border-b border-primary-100 pb-6 mb-6">
              <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-primary-100 flex items-center justify-center">
                  <i class="fas fa-user text-primary-600"></i>
                </div>
                Customer Information
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Booking ID -->
                <div>
                  <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Booking ID <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input type="text" 
                           id="booking_id"
                           name="booking_id"
                           value="<?php echo htmlspecialchars($form_data['booking_id'] ?? ''); ?>"
                           required
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="Enter Booking ID">
                    <i class="fas fa-ticket-alt absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
                
                <!-- Customer Name -->
                <div>
                  <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Customer Name <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input type="text" 
                           id="customer_name"
                           name="customer_name"
                           value="<?php echo htmlspecialchars($form_data['customer_name'] ?? ''); ?>"
                           required
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="Full Name">
                    <i class="fas fa-user absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
                
                <!-- Customer Email -->
                <div>
                  <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input type="email" 
                           id="customer_email"
                           name="customer_email"
                           value="<?php echo htmlspecialchars($form_data['customer_email'] ?? ''); ?>"
                           required
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="email@example.com">
                    <i class="fas fa-envelope absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
                
                <!-- Phone Number -->
                <div>
                  <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                  </label>
                  <div class="relative">
                    <input type="tel" 
                           id="phone_number"
                           name="phone_number"
                           value="<?php echo htmlspecialchars($form_data['phone_number'] ?? ''); ?>"
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="+1 (555) 123-4567">
                    <i class="fas fa-phone absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Section 2: Booking Details -->
            <div class="border-b border-primary-100 pb-6 mb-6">
              <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-primary-100 flex items-center justify-center">
                  <i class="fas fa-calendar-alt text-primary-600"></i>
                </div>
                Booking Details
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Booking Date -->
                <div>
                  <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Booking Date
                  </label>
                  <div class="relative">
                    <input type="text" 
                           id="booking_date"
                           name="booking_date"
                           value="<?php echo htmlspecialchars($form_data['booking_date'] ?? ''); ?>"
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none datepicker"
                           placeholder="Select date">
                    <i class="fas fa-calendar absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
                
                <!-- Trip Name -->
                <div>
                  <label for="trip_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Trip/Service Name
                  </label>
                  <div class="relative">
                    <input type="text" 
                           id="trip_name"
                           name="trip_name"
                           value="<?php echo htmlspecialchars($form_data['trip_name'] ?? ''); ?>"
                           class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                           placeholder="e.g., Japan Adventure Tour">
                    <i class="fas fa-plane absolute left-3 top-3.5 text-primary-400"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Section 3: Cancellation Details -->
            <div class="border-b border-primary-100 pb-6 mb-6">
              <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-primary-100 flex items-center justify-center">
                  <i class="fas fa-ban text-primary-600"></i>
                </div>
                Cancellation Details
              </h2>
              
              <!-- Cancellation Reason -->
              <div class="mb-6">
                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                  Cancellation Reason <span class="text-red-500">*</span>
                </label>
                <select id="cancellation_reason" 
                        name="cancellation_reason"
                        required
                        class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="">Select a reason...</option>
                  <option value="change_of_plans" <?php echo ($form_data['cancellation_reason'] ?? '') == 'change_of_plans' ? 'selected' : ''; ?>>Change of Plans</option>
                  <option value="financial_reasons" <?php echo ($form_data['cancellation_reason'] ?? '') == 'financial_reasons' ? 'selected' : ''; ?>>Financial Reasons</option>
                  <option value="health_issues" <?php echo ($form_data['cancellation_reason'] ?? '') == 'health_issues' ? 'selected' : ''; ?>>Health Issues</option>
                  <option value="travel_restrictions" <?php echo ($form_data['cancellation_reason'] ?? '') == 'travel_restrictions' ? 'selected' : ''; ?>>Travel Restrictions</option>
                  <option value="dissatisfied_service" <?php echo ($form_data['cancellation_reason'] ?? '') == 'dissatisfied_service' ? 'selected' : ''; ?>>Dissatisfied with Service</option>
                  <option value="other" <?php echo ($form_data['cancellation_reason'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              
              <!-- Additional Notes -->
              <div class="mb-6">
                <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">
                  Additional Notes
                </label>
                <textarea id="additional_notes" 
                          name="additional_notes"
                          rows="4"
                          class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                          placeholder="Please provide any additional details about the cancellation..."><?php echo htmlspecialchars($form_data['additional_notes'] ?? ''); ?></textarea>
              </div>
              
              <!-- Refund Method -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  Preferred Refund Method
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <label class="flex items-center p-4 rounded-xl border border-primary-200 hover:bg-primary-50 cursor-pointer transition-colors">
                    <input type="radio" 
                           name="refund_method" 
                           value="original_payment" 
                           class="mr-3 custom-radio"
                           <?php echo ($form_data['refund_method'] ?? '') == 'original_payment' ? 'checked' : ''; ?>>
                    <div>
                      <div class="font-medium">Original Payment</div>
                      <div class="text-sm text-gray-600">Refund to original payment method</div>
                    </div>
                  </label>
                  
                  <label class="flex items-center p-4 rounded-xl border border-primary-200 hover:bg-primary-50 cursor-pointer transition-colors">
                    <input type="radio" 
                           name="refund_method" 
                           value="travel_credit" 
                           class="mr-3 custom-radio"
                           <?php echo ($form_data['refund_method'] ?? '') == 'travel_credit' ? 'checked' : ''; ?>>
                    <div>
                      <div class="font-medium">Travel Credit</div>
                      <div class="text-sm text-gray-600">Store credit for future travel</div>
                    </div>
                  </label>
                  
                  <label class="flex items-center p-4 rounded-xl border border-primary-200 hover:bg-primary-50 cursor-pointer transition-colors">
                    <input type="radio" 
                           name="refund_method" 
                           value="bank_transfer" 
                           class="mr-3 custom-radio"
                           <?php echo ($form_data['refund_method'] ?? '') == 'bank_transfer' ? 'checked' : ''; ?>>
                    <div>
                      <div class="font-medium">Bank Transfer</div>
                      <div class="text-sm text-gray-600">Direct transfer to bank account</div>
                    </div>
                  </label>
                </div>
              </div>
              
              <!-- Emergency Contact -->
              <div>
                <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">
                  Emergency Contact (Optional)
                </label>
                <div class="relative">
                  <input type="text" 
                         id="emergency_contact"
                         name="emergency_contact"
                         value="<?php echo htmlspecialchars($form_data['emergency_contact'] ?? ''); ?>"
                         class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                         placeholder="Name and phone number">
                  <i class="fas fa-exclamation-triangle absolute left-3 top-3.5 text-primary-400"></i>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                  Only for urgent matters related to this cancellation
                </p>
              </div>
            </div>

            <!-- Section 4: Terms & Confirmation -->
            <div class="pb-6">
              <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-primary-100 flex items-center justify-center">
                  <i class="fas fa-file-contract text-primary-600"></i>
                </div>
                Terms & Confirmation
              </h2>
              
              <!-- Terms Agreement -->
              <div class="bg-primary-50 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                  <input type="checkbox" 
                         id="terms_agreement"
                         name="terms_agreement"
                         required
                         class="mt-1 custom-checkbox">
                  <div>
                    <label for="terms_agreement" class="font-medium text-gray-900">
                      I agree to the cancellation terms and conditions
                    </label>
                    <p class="text-sm text-gray-600 mt-1">
                      By submitting this form, I acknowledge that:
                    </p>
                    <ul class="text-sm text-gray-600 mt-2 space-y-1">
                      <li class="flex items-center gap-2">
                        <i class="fas fa-check text-primary-600 text-xs"></i>
                        Cancellation fees may apply based on booking terms
                      </li>
                      <li class="flex items-center gap-2">
                        <i class="fas fa-check text-primary-600 text-xs"></i>
                        Refunds will be processed within 7-14 business days
                      </li>
                      <li class="flex items-center gap-2">
                        <i class="fas fa-check text-primary-600 text-xs"></i>
                        All information provided is accurate and complete
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              
              <!-- Warning Message -->
              <div class="bg-red-50 rounded-xl p-4 border border-red-200 mb-6">
                <div class="flex items-start gap-3">
                  <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-0.5"></i>
                  <div>
                    <h3 class="font-semibold text-red-900">Important Notice</h3>
                    <p class="text-sm text-red-700 mt-1">
                      Cancellations are subject to our terms and conditions. Please review the 
                      <a href="#" class="underline font-semibold">cancellation policy</a> 
                      before submitting this request. Some bookings may incur cancellation fees.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-primary-100">
              <button type="submit" 
                      class="px-8 py-4 rounded-xl cancel-gradient text-white font-bold hover:shadow-lg transition-shadow flex items-center justify-center gap-2 flex-1">
                <i class="fas fa-ban"></i>
                Submit Cancellation Request
              </button>
              
              <button type="button" 
                      onclick="previewCancellation()"
                      class="px-8 py-4 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow flex items-center justify-center gap-2">
                <i class="fas fa-eye"></i>
                Preview
              </button>
              
              <button type="reset" 
                      class="px-8 py-4 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-redo"></i>
                Reset Form
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Cancellation Policy Summary -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold mt-8 p-6">
        <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary-600"></i>
          Cancellation Policy Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="p-4 rounded-xl bg-primary-50">
            <div class="font-semibold text-primary-700 mb-2">Standard Cancellations</div>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>30+ days before: Full refund</li>
              <li>15-29 days: 75% refund</li>
              <li>7-14 days: 50% refund</li>
              <li>Less than 7 days: 25% refund</li>
            </ul>
          </div>
          
          <div class="p-4 rounded-xl bg-red-50">
            <div class="font-semibold text-red-700 mb-2">Non-refundable Items</div>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Airline tickets (varies by carrier)</li>
              <li>Special event tickets</li>
              <li>Third-party services</li>
              <li>Processing fees</li>
            </ul>
          </div>
          
          <div class="p-4 rounded-xl bg-green-50">
            <div class="font-semibold text-green-700 mb-2">Flexible Options</div>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Date changes (fee may apply)</li>
              <li>Travel credit for future use</li>
              <li>Transfer to another person</li>
              <li>Insurance claims assistance</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-primary-100 bg-primary-50 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center text-sm text-gray-600">
        <p>© <?php echo date('Y'); ?> TravelEase Customer Support. For authorized personnel only.</p>
        <p class="mt-2">Cancellation requests are processed within 24-48 business hours.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    // Initialize date picker
    flatpickr('.datepicker', {
      dateFormat: "Y-m-d",
      maxDate: "today"
    });
    
    // Preview function
    function previewCancellation() {
      const form = document.getElementById('cancellationForm');
      const formData = new FormData(form);
      let previewContent = "=== CANCELLATION REQUEST PREVIEW ===\n\n";
      
      // Collect form data for preview
      for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
          const label = document.querySelector(`label[for="${key}"]`)?.textContent || key;
          previewContent += `${label}: ${value}\n`;
        }
      }
      
      // Show preview in alert
      alert(previewContent);
      
      // You could also open a modal with the preview
      // showPreviewModal(previewContent);
    }
    
    // Form validation
    document.getElementById('cancellationForm').addEventListener('submit', function(e) {
      const requiredFields = ['booking_id', 'customer_name', 'customer_email', 'cancellation_reason'];
      let isValid = true;
      let errorMessage = '';
      
      requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
          isValid = false;
          input.style.borderColor = '#ef4444';
          errorMessage += `Please fill in ${field.replace('_', ' ')}\n`;
        } else {
          input.style.borderColor = '';
        }
      });
      
      // Email validation
      const email = document.getElementById('customer_email');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email.value && !emailRegex.test(email.value)) {
        isValid = false;
        email.style.borderColor = '#ef4444';
        errorMessage += 'Please enter a valid email address\n';
      }
      
      // Terms agreement validation
      const terms = document.getElementById('terms_agreement');
      if (!terms.checked) {
        isValid = false;
        terms.style.borderColor = '#ef4444';
        errorMessage += 'You must agree to the terms and conditions\n';
      }
      
      if (!isValid) {
        e.preventDefault();
        alert('Please correct the following errors:\n\n' + errorMessage);
      } else {
        // Show confirmation before submission
        const confirmed = confirm('Are you sure you want to submit this cancellation request? This action cannot be undone.');
        if (!confirmed) {
          e.preventDefault();
        } else {
          // Show loading state
          const submitBtn = document.querySelector('button[type="submit"]');
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
          submitBtn.disabled = true;
        }
      }
    });
    
    // Real-time booking ID validation (simulated)
    document.getElementById('booking_id').addEventListener('blur', function() {
      const bookingId = this.value.trim();
      if (bookingId.length > 0) {
        // Simulate API call to validate booking ID
        setTimeout(() => {
          if (bookingId.includes('BOOK')) {
            this.style.borderColor = '#10b981';
            // You could fetch and auto-fill customer details here
          } else {
            this.style.borderColor = '#ef4444';
          }
        }, 500);
      }
    });
    
    // Character counter for notes
    const notesTextarea = document.getElementById('additional_notes');
    if (notesTextarea) {
      notesTextarea.addEventListener('input', function() {
        const counter = document.getElementById('charCounter') || document.createElement('div');
        counter.id = 'charCounter';
        counter.className = 'text-sm text-gray-500 text-right mt-1';
        counter.textContent = `${this.value.length}/500 characters`;
        
        if (!this.nextElementSibling || this.nextElementSibling.id !== 'charCounter') {
          this.parentNode.appendChild(counter);
        }
        
        if (this.value.length > 500) {
          counter.style.color = '#ef4444';
        } else {
          counter.style.color = '#6b7280';
        }
      });
    }
  </script>
</body>
</html>