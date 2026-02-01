<?php
// support_tickets.php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Ticket Management | TravelEase Support</title>
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
    .status-open { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; }
    .status-in-progress { background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); color: #1e40af; }
    .status-escalated { background: linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%); color: #9d174d; }
    .status-resolved { background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%); color: #166534; }
    .status-closed { background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%); color: #374151; }
    .priority-high { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); color: #dc2626; border: 1px solid #fca5a5; }
    .priority-medium { background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%); color: #d97706; border: 1px solid #fcd34d; }
    .priority-low { background: linear-gradient(135deg, #f0f9ff 0%, #bae6fd 100%); color: #0369a1; border: 1px solid #7dd3fc; }
    
    /* Add button styling for anchor tags */
    .btn-link {
      display: inline-block;
      text-decoration: none;
      cursor: pointer;
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
          <a href="support_ticket.php" class="text-primary-600 font-bold">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div>
          <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
            Ticket <span class="text-gradient">Management</span>
          </h1>
          <p class="text-lg text-gray-700">Respond to customer inquiries and support requests</p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold mb-8">
        <form method="GET" action="support_tickets.php" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <option value="">All Status</option>
              <option value="open">Open</option>
              <option value="in_progress">In Progress</option>
              <option value="awaiting_customer">Awaiting Customer</option>
              <option value="resolved">Resolved</option>
              <option value="closed">Closed</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
            <select name="priority" class="w-full p-3 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <option value="">All Priority</option>
              <option value="high">High</option>
              <option value="medium">Medium</option>
              <option value="low">Low</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="relative">
              <input type="text" 
                     name="search"
                     placeholder="Search tickets..."
                     class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none">
              <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
          </div>
          <div class="md:col-span-3">
            <button type="submit" class="px-6 py-3 rounded-xl gold-gradient text-white font-bold hover:shadow-lg transition-shadow">
              <i class="fas fa-filter mr-2"></i> Apply Filters
            </button>
            <a href="support_tickets.php" class="px-6 py-3 rounded-xl bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition-colors ml-2">
              <i class="fas fa-redo mr-2"></i> Reset
            </a>
          </div>
        </form>
      </div>

      <!-- Tickets Table -->
      <div class="glass-effect rounded-2xl border border-primary-100 shadow-gold overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-primary-50">
              <tr>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Ticket ID</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Subject</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Customer</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Status</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Priority</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Created</th>
                <th class="p-4 text-left text-sm font-semibold text-gray-900">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary-100">
              <!-- Ticket 1 -->
              <tr class="hover:bg-primary-50 transition-colors">
                <td class="p-4">
                  <span class="font-mono text-sm font-semibold text-gray-900">#TK-7832</span>
                </td>
                <td class="p-4">
                  <div>
                    <div class="font-medium text-gray-900">Booking Issue - Japan Trip</div>
                    <div class="text-sm text-gray-600 mt-1">Cannot access booking details</div>
                  </div>
                </td>
                <td class="p-4">
                  <div class="font-medium text-gray-900">Sarah Johnson</div>
                  <div class="text-sm text-gray-600">sarah.j@email.com</div>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-open">Open</span>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold priority-high">High</span>
                </td>
                <td class="p-4 text-sm text-gray-600">2 hours ago</td>
                <td class="p-4">
                  <!-- Fixed: Changed button to anchor tag -->
                  <a href="view_ticket.php?id=7832" class="px-3 py-1 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors inline-block btn-link">
                    Respond
                  </a>
                </td>
              </tr>

              <!-- Ticket 2 -->
              <tr class="hover:bg-primary-50 transition-colors">
                <td class="p-4">
                  <span class="font-mono text-sm font-semibold text-gray-900">#TK-7831</span>
                </td>
                <td class="p-4">
                  <div>
                    <div class="font-medium text-gray-900">Payment Query - Bali Retreat</div>
                    <div class="text-sm text-gray-600 mt-1">Questions about payment options</div>
                  </div>
                </td>
                <td class="p-4">
                  <div class="font-medium text-gray-900">Michael Chen</div>
                  <div class="text-sm text-gray-600">michael.c@email.com</div>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-in-progress">In Progress</span>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold priority-medium">Medium</span>
                </td>
                <td class="p-4 text-sm text-gray-600">4 hours ago</td>
                <td class="p-4">
                  <!-- Fixed: Changed button to anchor tag -->
                  <a href="view_ticket.php?id=7831" class="px-3 py-1 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors inline-block btn-link">
                    View
                  </a>
                </td>
              </tr>

              <!-- Ticket 3 -->
              <tr class="hover:bg-primary-50 transition-colors">
                <td class="p-4">
                  <span class="font-mono text-sm font-semibold text-gray-900">#TK-7830</span>
                </td>
                <td class="p-4">
                  <div>
                    <div class="font-medium text-gray-900">Itinerary Modification Request</div>
                    <div class="text-sm text-gray-600 mt-1">Wants to change tour dates</div>
                  </div>
                </td>
                <td class="p-4">
                  <div class="font-medium text-gray-900">Robert Williams</div>
                  <div class="text-sm text-gray-600">robert.w@email.com</div>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-awaiting-customer">Awaiting Customer</span>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold priority-medium">Medium</span>
                </td>
                <td class="p-4 text-sm text-gray-600">1 day ago</td>
                <td class="p-4">
                  <!-- Fixed: Changed button to anchor tag -->
                  <a href="followup_ticket.php?id=7830" class="px-3 py-1 rounded-lg bg-primary-100 text-primary-700 text-sm font-semibold hover:bg-primary-200 transition-colors inline-block btn-link">
                    Follow Up
                  </a>
                </td>
              </tr>

              <!-- Ticket 4 -->
              <tr class="hover:bg-primary-50 transition-colors">
                <td class="p-4">
                  <span class="font-mono text-sm font-semibold text-gray-900">#TK-7829</span>
                </td>
                <td class="p-4">
                  <div>
                    <div class="font-medium text-gray-900">Refund Status Inquiry</div>
                    <div class="text-sm text-gray-600 mt-1">Checking refund progress</div>
                  </div>
                </td>
                <td class="p-4">
                  <div class="font-medium text-gray-900">Emma Davis</div>
                  <div class="text-sm text-gray-600">emma.d@email.com</div>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold status-resolved">Resolved</span>
                </td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold priority-low">Low</span>
                </td>
                <td class="p-4 text-sm text-gray-600">2 days ago</td>
                <td class="p-4">
                  <!-- Fixed: Changed button to anchor tag -->
                  <a href="close_ticket.php?id=7829" class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 transition-colors inline-block btn-link">
                    Closed
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-primary-100">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
              Showing 1-4 of 24 tickets
            </div>
            <div class="flex items-center gap-2">
              <a href="?page=1" class="p-2 rounded-lg border border-primary-200 text-primary-600 hover:bg-primary-50 inline-block">
                <i class="fas fa-chevron-left"></i>
              </a>
              <a href="?page=1" class="p-2 rounded-lg bg-primary-600 text-white inline-block">1</a>
              <a href="?page=2" class="p-2 rounded-lg border border-primary-200 text-primary-600 hover:bg-primary-50 inline-block">2</a>
              <a href="?page=3" class="p-2 rounded-lg border border-primary-200 text-primary-600 hover:bg-primary-50 inline-block">3</a>
              <a href="?page=2" class="p-2 rounded-lg border border-primary-200 text-primary-600 hover:bg-primary-50 inline-block">
                <i class="fas fa-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mt-8">
        <!-- Fixed: Made stats clickable if needed -->
        <a href="?status=all" class="glass-effect rounded-2xl p-6 border border-primary-100 hover:border-primary-300 transition-colors block">
          <div class="text-2xl font-black text-gray-900 mb-2">24</div>
          <div class="text-sm text-gray-600">Total Tickets</div>
        </a>
        <a href="?status=open" class="glass-effect rounded-2xl p-6 border border-primary-100 hover:border-primary-300 transition-colors block">
          <div class="text-2xl font-black text-gray-900 mb-2">12</div>
          <div class="text-sm text-gray-600">Open Tickets</div>
        </a>
        <div class="glass-effect rounded-2xl p-6 border border-primary-100">
          <div class="text-2xl font-black text-gray-900 mb-2">2.5h</div>
          <div class="text-sm text-gray-600">Avg Response Time</div>
        </div>
        <div class="glass-effect rounded-2xl p-6 border border-primary-100">
          <div class="text-2xl font-black text-gray-900 mb-2">96%</div>
          <div class="text-sm text-gray-600">Satisfaction Rate</div>
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
            <li><a href="support_ticket.php" class="hover:text-primary-600 transition-colors">Ticket Management</a></li>
            <li><a href="support_chatbot.php" class="hover:text-primary-600 transition-colors">Chatbot Escalations</a></li>
            <li><a href="support_bookings.php" class="hover:text-primary-600 transition-colors">Booking Operations</a></li>
            <li><a href="support_customers.php" class="hover:text-primary-600 transition-colors">Customer Records</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Resources</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="knowledge_base.php" class="hover:text-primary-600 transition-colors">Knowledge Base</a></li>
            <li><a href="templates.php" class="hover:text-primary-600 transition-colors">Quick Response Templates</a></li>
            <li><a href="policies.php" class="hover:text-primary-600 transition-colors">Policy Guidelines</a></li>
            <li><a href="training.php" class="hover:text-primary-600 transition-colors">Training Materials</a></li>
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