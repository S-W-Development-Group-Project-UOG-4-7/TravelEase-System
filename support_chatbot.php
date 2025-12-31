<?php
// support_chatbot.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Chatbot Escalations | TravelEase Support</title>
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
    .chatbot-bg {
      background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    }
    .customer-msg {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }
    .agent-msg {
      background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
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
                Chatbot Escalations
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
          <a href="support_chatbot.php" class="text-primary-600 font-bold">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
              Chatbot <span class="text-gradient">Escalations</span>
            </h1>
            <p class="text-lg text-gray-700">Handle complex queries escalated from the AI chatbot</p>
          </div>
          <div class="flex items-center gap-4">
            <button class="px-6 py-3 rounded-xl bg-purple-600 text-white font-bold hover:bg-purple-700 transition-colors">
              <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 border border-purple-100">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center">
              <i class="fas fa-robot text-purple-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">8</div>
          <div class="text-sm text-gray-600">Active Escalations</div>
        </div>
        <div class="glass-effect rounded-2xl p-6 border border-primary-100">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
              <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">88%</div>
          <div class="text-sm text-gray-600">Success Rate</div>
        </div>
        <div class="glass-effect rounded-2xl p-6 border border-primary-100">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
              <i class="fas fa-clock text-blue-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">8.5m</div>
          <div class="text-sm text-gray-600">Avg Resolution Time</div>
        </div>
        <div class="glass-effect rounded-2xl p-6 border border-primary-100">
          <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
              <i class="fas fa-percentage text-red-600 text-xl"></i>
            </div>
          </div>
          <div class="text-2xl font-black text-gray-900 mb-2">12%</div>
          <div class="text-sm text-gray-600">Escalation Rate</div>
        </div>
      </div>

      <!-- Escalated Conversations -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Chat Window -->
        <div class="glass-effect rounded-2xl border border-purple-100 shadow-gold overflow-hidden">
          <div class="p-6 bg-purple-50 border-b border-purple-100">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-xl font-bold text-gray-900">Active Escalation</h3>
                <p class="text-sm text-gray-600">Chatbot ID: #CHAT-8923</p>
              </div>
              <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">
                Complex Booking Query
              </span>
            </div>
          </div>
          
          <!-- Chat Messages -->
          <div class="p-6 h-96 overflow-y-auto">
            <div class="space-y-4">
              <!-- Chatbot Message -->
              <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md">
                  <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4">
                    <div class="flex items-center gap-2 mb-2">
                      <div class="h-6 w-6 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-robot text-purple-600 text-xs"></i>
                      </div>
                      <span class="text-xs font-semibold">TravelEase AI</span>
                    </div>
                    <p class="text-sm text-gray-700">
                      Hi! I'm here to help with your booking modifications. Could you tell me what specific changes you need?
                    </p>
                    <span class="text-xs text-gray-500 mt-2 block">2:30 PM</span>
                  </div>
                </div>
              </div>

              <!-- Customer Message -->
              <div class="flex justify-end">
                <div class="max-w-xs lg:max-w-md">
                  <div class="customer-msg rounded-2xl rounded-tr-none p-4">
                    <div class="flex items-center gap-2 mb-2 justify-end">
                      <span class="text-xs font-semibold">Customer</span>
                      <div class="h-6 w-6 rounded-full bg-primary-100 flex items-center justify-center">
                        <i class="fas fa-user text-primary-600 text-xs"></i>
                      </div>
                    </div>
                    <p class="text-sm text-gray-700">
                      I need to modify my Japan tour booking for 5 people. We have different room preferences and dietary requirements. Also need to change dates.
                    </p>
                    <span class="text-xs text-gray-500 mt-2 block text-right">2:31 PM</span>
                  </div>
                </div>
              </div>

              <!-- Chatbot Response -->
              <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md">
                  <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4">
                    <div class="flex items-center gap-2 mb-2">
                      <div class="h-6 w-6 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-robot text-purple-600 text-xs"></i>
                      </div>
                      <span class="text-xs font-semibold">TravelEase AI</span>
                    </div>
                    <p class="text-sm text-gray-700">
                      I understand this is a complex modification. For multiple room types and dietary requirements, I'll need to escalate this to a human support agent who can handle these specific needs.
                    </p>
                    <span class="text-xs text-gray-500 mt-2 block">2:32 PM</span>
                  </div>
                </div>
              </div>

              <!-- Escalation Notice -->
              <div class="text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                  <i class="fas fa-exclamation-triangle"></i>
                  Conversation escalated to human agent
                </div>
              </div>
            </div>
          </div>

          <!-- Response Area -->
          <div class="p-4 border-t border-purple-100">
            <div class="flex gap-2">
              <input type="text" 
                     placeholder="Type your response..."
                     class="flex-1 p-3 rounded-xl border border-purple-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 focus:outline-none">
              <button class="px-6 py-3 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors">
                Send
              </button>
            </div>
          </div>
        </div>

        <!-- Escalation Details -->
        <div>
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Escalation Details</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Information</label>
                <div class="p-3 rounded-xl bg-primary-50">
                  <div class="font-medium text-gray-900">Michael Chen</div>
                  <div class="text-sm text-gray-600">michael.c@email.com</div>
                  <div class="text-sm text-gray-600">+1 (555) 123-4567</div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Reference</label>
                <div class="p-3 rounded-xl bg-primary-50">
                  <div class="font-mono font-medium text-gray-900">#BK-7890</div>
                  <div class="text-sm text-gray-600">Japan Luxury Tour - 5 Pax</div>
                  <div class="text-sm text-gray-600">Departure: March 15, 2024</div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Escalation Reason</label>
                <div class="p-3 rounded-xl bg-primary-50">
                  <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                      <i class="fas fa-check-circle text-green-500"></i>
                      Multiple room preferences
                    </li>
                    <li class="flex items-center gap-2">
                      <i class="fas fa-check-circle text-green-500"></i>
                      Special dietary requirements
                    </li>
                    <li class="flex items-center gap-2">
                      <i class="fas fa-check-circle text-green-500"></i>
                      Date change request
                    </li>
                    <li class="flex items-center gap-2">
                      <i class="fas fa-check-circle text-green-500"></i>
                      Complex booking modification
                    </li>
                  </ul>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                <select class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
                  <option value="high">High Priority</option>
                  <option value="medium">Medium Priority</option>
                  <option value="low">Low Priority</option>
                </select>
              </div>

              <button class="w-full py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
                <i class="fas fa-check-circle mr-2"></i> Mark as Resolved
              </button>
            </div>
          </div>

          <!-- Quick Responses -->
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Responses</h3>
            
            <div class="space-y-3">
              <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-left transition-colors">
                <div class="font-medium text-gray-900 mb-1">Booking Confirmation</div>
                <div class="text-xs text-gray-600">Standard confirmation template</div>
              </button>
              
              <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-left transition-colors">
                <div class="font-medium text-gray-900 mb-1">Modification Process</div>
                <div class="text-xs text-gray-600">Explain modification steps</div>
              </button>
              
              <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 text-left transition-colors">
                <div class="font-medium text-gray-900 mb-1">Refund Information</div>
                <div class="text-xs text-gray-600">Refund policy and timeline</div>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Escalations -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold">
        <div class="p-6 border-b border-primary-100">
          <h3 class="text-xl font-bold text-gray-900">Pending Escalations</h3>
        </div>
        
        <div class="divide-y divide-primary-100">
          <!-- Escalation 1 -->
          <div class="p-6 hover:bg-primary-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Insurance Claim Process</span>
                  <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Awaiting</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">
                  Travel insurance claim process after trip cancellation due to medical emergency...
                </p>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                  <span><i class="fas fa-user mr-1"></i> Emma Davis</span>
                  <span><i class="fas fa-clock mr-1"></i> 1 hour ago</span>
                  <span><i class="fas fa-hashtag mr-1"></i> #CHAT-8922</span>
                </div>
              </div>
              <button class="px-4 py-2 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors">
                Take Over
              </button>
            </div>
          </div>

          <!-- Escalation 2 -->
          <div class="p-6 hover:bg-primary-50 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <div class="flex items-center gap-3 mb-2">
                  <span class="font-semibold text-gray-900">Special Accessibility Needs</span>
                  <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Awaiting</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">
                  Require wheelchair accessibility and special arrangements for Bali resort...
                </p>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                  <span><i class="fas fa-user mr-1"></i> Robert Wilson</span>
                  <span><i class="fas fa-clock mr-1"></i> 3 hours ago</span>
                  <span><i class="fas fa-hashtag mr-1"></i> #CHAT-8921</span>
                </div>
              </div>
              <button class="px-4 py-2 rounded-xl bg-purple-600 text-white font-semibold hover:bg-purple-700 transition-colors">
                Take Over
              </button>
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
</body>
</html>