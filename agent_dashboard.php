<?php
// support_dashboard.php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Support Agent Dashboard | TravelEase - Premium Customer Support</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="TravelEase Customer Support Dashboard. Handle inquiries, cancellations, modifications, and customer records.">
  
  <!-- Tailwind CSS CDN -->
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
            },
            support: {
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
            },
            premium: {
              gold: '#f59e0b',
              light: '#ffffff',
              cream: '#fef7e5',
              sand: '#fef3c7'
            },
            gradient: {
              start: '#f59e0b',
              end: '#fbbf24'
            }
          },
          animation: {
            'fade-in-up': 'fadeInUp 0.6s ease-out',
            'fade-in-down': 'fadeInDown 0.6s ease-out',
            'slide-in-right': 'slideInRight 0.6s ease-out',
            'zoom-in': 'zoomIn 0.8s ease-out',
            'float': 'float 6s ease-in-out infinite',
            'gradient-shift': 'gradientShift 3s ease infinite',
            'pulse-slow': 'pulse 3s ease-in-out infinite',
            'bounce-slow': 'bounce 2s ease-in-out infinite',
            'spin-slow': 'spin 3s linear infinite',
            'glow': 'glow 2s ease-in-out infinite alternate',
            'text-shimmer': 'textShimmer 3s ease infinite',
          },
          keyframes: {
            fadeInUp: {
              '0%': { opacity: '0', transform: 'translateY(30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
            fadeInDown: {
              '0%': { opacity: '0', transform: 'translateY(-30px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' }
            },
            slideInRight: {
              '0%': { opacity: '0', transform: 'translateX(30px)' },
              '100%': { opacity: '1', transform: 'translateX(0)' }
            },
            zoomIn: {
              '0%': { opacity: '0', transform: 'scale(0.9)' },
              '100%': { opacity: '1', transform: 'scale(1)' }
            },
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-10px)' }
            },
            gradientShift: {
              '0%, 100%': { backgroundPosition: '0% 50%' },
              '50%': { backgroundPosition: '100% 50%' }
            },
            glow: {
              '0%': { boxShadow: '0 0 20px rgba(245, 158, 11, 0.3)' },
              '100%': { boxShadow: '0 0 30px rgba(245, 158, 11, 0.6), 0 0 40px rgba(245, 158, 11, 0.3)' }
            },
            textShimmer: {
              '0%': { backgroundPosition: '-200% center' },
              '100%': { backgroundPosition: '200% center' }
            }
          },
          backgroundSize: {
            '200%': '200% 200%',
            '300%': '300% 300%'
          },
          screens: {
            'xs': '475px',
            '3xl': '1600px',
          },
          backdropBlur: {
            'xs': '2px',
          }
        }
      }
    };
  </script>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-gold: #f59e0b;
      --gradient-start: #f59e0b;
      --gradient-end: #fbbf24;
    }
    
    body { 
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
      color: #1f2937;
      overflow-x: hidden;
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .support-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
      background-size: 200% 200%;
      animation: gradientShift 3s ease infinite;
    }
    .light-gradient {
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 100%);
    }
    .gold-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fcd34d 100%);
    }
    .hover-lift {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 25px 50px -12px rgba(245, 158, 11, 0.25);
    }
    .text-gradient {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .text-shimmer {
      background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b, #fbbf24);
      background-size: 300% 100%;
      animation: textShimmer 3s ease infinite;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .shadow-gold {
      box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
    }
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.3s ease-in-out;
    }
    .mobile-menu.open {
      transform: translateX(0);
    }
    .backdrop-blur-xl {
      backdrop-filter: blur(24px);
    }
    
    /* Status colors */
    .status-open {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      color: #92400e;
    }
    .status-in-progress {
      background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
      color: #1e40af;
    }
    .status-escalated {
      background: linear-gradient(135deg, #fce7f3 0%, #f9a8d4 100%);
      color: #9d174d;
    }
    .status-resolved {
      background: linear-gradient(135deg, #dcfce7 0%, #86efac 100%);
      color: #166534;
    }
    .status-cancelled {
      background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
      color: #991b1b;
    }
    
    /* Priority badges */
    .priority-high {
      background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
      color: #dc2626;
      border: 1px solid #fca5a5;
    }
    .priority-medium {
      background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%);
      color: #d97706;
      border: 1px solid #fcd34d;
    }
    .priority-low {
      background: linear-gradient(135deg, #f0f9ff 0%, #bae6fd 100%);
      color: #0369a1;
      border: 1px solid #7dd3fc;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #fef7e5;
    }
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #f59e0b, #fbbf24);
      border-radius: 10px;
    }
    
    /* Loading animation */
    .loading-bar {
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
      background-size: 200% 100%;
      animation: loading 2s infinite;
    }
    
    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Screen -->
  <div id="advanced-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="text-center">
      <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto mb-6"></div>
      <div class="text-2xl font-black text-gradient">TravelEase Support</div>
      <p class="text-gray-600 mt-2">Loading Dashboard...</p>
    </div>
  </div>

  <!-- Enhanced Mobile Menu -->
  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center">
              <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center font-black text-primary-600 text-xs">TE</div>
            </div>
            <span class="font-black text-xl text-gray-900">Support Panel</span>
          </div>
          <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-primary-50">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="space-y-4">
          <a href="#dashboard" class="flex items-center gap-4 p-4 rounded-2xl bg-primary-50 text-primary-600 font-semibold">
            <i class="fas fa-tachometer-alt w-6 text-center"></i>
            Dashboard
          </a>
          <a href="support_ticket.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all font-semibold">
            <i class="fas fa-ticket-alt w-6 text-center"></i>
            Tickets
          </a>
          <a href="support_chatbot.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all font-semibold">
            <i class="fas fa-robot w-6 text-center"></i>
            Chatbot Escalations
          </a>
          <a href="support_bookings.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all font-semibold">
            <i class="fas fa-calendar-check w-6 text-center"></i>
            Booking Operations
          </a>
          <a href="support_customer.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all font-semibold">
            <i class="fas fa-users w-6 text-center"></i>
            Customer Records
          </a>
        </nav>

        <!-- Mobile CTA Buttons -->
        <div class="mt-8 space-y-4">
          <a href="logout.php"
             class="block w-full text-center px-6 py-3 rounded-2xl glass-effect text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all font-semibold border border-primary-100">
            <i class="fas fa-sign-out-alt mr-2"></i>
            Logout
          </a>
        </div>

        <!-- Mobile Stats -->
        <div class="mt-8 pt-8 border-t border-primary-100">
          <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-center justify-between">
              <span>Active Tickets:</span>
              <span class="font-semibold text-primary-600">12</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Avg Response Time:</span>
              <span class="font-semibold text-primary-600">2.5h</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Satisfaction Score:</span>
              <span class="font-semibold text-primary-600">96%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Enhanced Support Navigation -->
  <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-primary-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <a href="support_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-primary-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="font-black text-xl tracking-tight text-gray-900">
                TravelEase
              </span>
              <span class="hidden sm:inline-block text-xs text-primary-600 font-medium">
                Customer Support
              </span>
            </div>
          </a>
        </div>

        <!-- Center Navigation -->
        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
          <a href="#dashboard" class="text-gray-700 hover:text-primary-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-tachometer-alt text-xs text-primary-500"></i>
              Dashboard
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="support_ticket.php" class="text-gray-700 hover:text-primary-600 transition-all duration-300 relative group">
            <i class="fas fa-ticket-alt text-xs text-primary-500 mr-2"></i>
            Tickets
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="support_chatbot.php" class="text-gray-700 hover:text-primary-600 transition-all duration-300 relative group">
            <i class="fas fa-robot text-xs text-primary-500 mr-2"></i>
            Chatbot
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="support_bookings.php" class="text-gray-700 hover:text-primary-600 transition-all duration-300 relative group">
            <i class="fas fa-calendar-check text-xs text-primary-500 mr-2"></i>
            Bookings
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="support_customer.php" class="text-gray-700 hover:text-primary-600 transition-all duration-300 relative group">
            <i class="fas fa-users text-xs text-primary-500 mr-2"></i>
            Customers
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>

        <!-- Support Agent Info & Logout -->
        <div class="hidden lg:flex items-center gap-4">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center font-semibold text-primary-700">
              SA
            </div>
            <div class="text-right">
              <div class="text-sm font-semibold text-gray-900">Support Agent</div>
              <div class="text-xs text-gray-600">Online</div>
            </div>
          </div>
          <a href="logout.php"
             class="px-4 py-2 rounded-xl glass-effect text-sm font-semibold text-gray-700 hover:text-red-600 hover:bg-red-50 transition-all duration-300 border border-primary-100">
            Logout
          </a>
        </div>

        <!-- Enhanced Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-primary-50 transition-colors">
          <i class="fas fa-bars text-lg"></i>
          <span class="ml-2 text-sm font-semibold">Menu</span>
        </button>
      </div>
    </nav>
  </header>

  <!-- Support Dashboard Main Content -->
  <main class="pt-20">
    <!-- Dashboard Stats Section -->
    <section id="dashboard" class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 animate-fade-in-down">
          <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
            Customer Support <span class="text-gradient">Dashboard</span>
          </h1>
          <p class="text-lg text-gray-700">
            Handle customer inquiries, escalations, cancellations, and modifications
          </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold hover-lift">
            <div class="flex items-center justify-between mb-4">
              <div class="h-12 w-12 rounded-xl bg-primary-100 flex items-center justify-center">
                <i class="fas fa-ticket-alt text-primary-600 text-xl"></i>
              </div>
              <span class="text-xs font-semibold px-3 py-1 rounded-full status-open">Open</span>
            </div>
            <p class="text-3xl font-black text-gray-900 mb-1">24</p>
            <p class="text-sm text-gray-600">Active Tickets</p>
          </div>

          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold hover-lift">
            <div class="flex items-center justify-between mb-4">
              <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-robot text-blue-600 text-xl"></i>
              </div>
              <span class="text-xs font-semibold px-3 py-1 rounded-full status-escalated">Escalated</span>
            </div>
            <p class="text-3xl font-black text-gray-900 mb-1">8</p>
            <p class="text-sm text-gray-600">Chatbot Escalations</p>
          </div>

          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold hover-lift">
            <div class="flex items-center justify-between mb-4">
              <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
              </div>
              <span class="text-xs font-semibold px-3 py-1 rounded-full status-cancelled">Today</span>
            </div>
            <p class="text-3xl font-black text-gray-900 mb-1">5</p>
            <p class="text-sm text-gray-600">Cancellations</p>
          </div>

          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold hover-lift">
            <div class="flex items-center justify-between mb-4">
              <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fas fa-user-edit text-green-600 text-xl"></i>
              </div>
              <span class="text-xs font-semibold px-3 py-1 rounded-full status-resolved">Updated</span>
            </div>
            <p class="text-3xl font-black text-gray-900 mb-1">12</p>
            <p class="text-sm text-gray-600">Records Updated</p>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="#tickets" class="group glass-effect rounded-xl p-4 border border-primary-100 hover:border-primary-300 transition-all duration-300 hover-lift">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg gold-gradient flex items-center justify-center">
                  <i class="fas fa-headset text-white"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">Respond to Inquiries</h3>
                  <p class="text-sm text-gray-600">Handle customer questions</p>
                </div>
              </div>
            </a>

            <a href="#chatbot" class="group glass-effect rounded-xl p-4 border border-primary-100 hover:border-primary-300 transition-all duration-300 hover-lift">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                  <i class="fas fa-robot text-purple-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">Chatbot Escalations</h3>
                  <p class="text-sm text-gray-600">Answer complex queries</p>
                </div>
              </div>
            </a>

            <a href="#bookings" class="group glass-effect rounded-xl p-4 border border-primary-100 hover:border-primary-300 transition-all duration-300 hover-lift">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                  <i class="fas fa-ban text-red-600"></i>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">Handle Cancellations</h3>
                  <p class="text-sm text-gray-600">Process refunds & cancellations</p>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Ticket Management Section -->
    <section id="tickets" class="py-8 bg-gradient-to-b from-white to-primary-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 animate-fade-in-up">
          <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">
            <i class="fas fa-ticket-alt text-primary-500 mr-3"></i>
            Ticket Management
          </h2>
          <p class="text-lg text-gray-700">Respond to customer inquiries and support requests</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Active Tickets -->
          <div class="lg:col-span-2">
            <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Active Tickets</h3>
                <a href="support_tickets.php" class="text-primary-600 hover:text-primary-800 text-sm font-semibold">
                  View All →
                </a>
              </div>
              
              <div class="space-y-4">
                <!-- Ticket 1 -->
                <div class="group p-4 rounded-xl border border-primary-100 hover:border-primary-300 bg-white hover:bg-primary-50 transition-all duration-300">
                  <div class="flex items-start justify-between mb-2">
                    <div>
                      <span class="text-sm font-semibold text-gray-900">Booking Issue - Japan Trip</span>
                      <span class="text-xs px-2 py-1 rounded-full priority-high ml-2">High</span>
                    </div>
                    <span class="text-xs text-gray-500">2h ago</span>
                  </div>
                  <p class="text-sm text-gray-700 mb-3">Customer cannot access booking details for upcoming Japan tour...</p>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">ID: #TK-7832</span>
                    <button class="text-primary-600 hover:text-primary-800 text-sm font-semibold">
                      <i class="fas fa-reply mr-1"></i> Respond
                    </button>
                  </div>
                </div>

                <!-- Ticket 2 -->
                <div class="group p-4 rounded-xl border border-primary-100 hover:border-primary-300 bg-white hover:bg-primary-50 transition-all duration-300">
                  <div class="flex items-start justify-between mb-2">
                    <div>
                      <span class="text-sm font-semibold text-gray-900">Payment Query - Bali Retreat</span>
                      <span class="text-xs px-2 py-1 rounded-full priority-medium ml-2">Medium</span>
                    </div>
                    <span class="text-xs text-gray-500">4h ago</span>
                  </div>
                  <p class="text-sm text-gray-700 mb-3">Customer has questions about payment options for Bali wellness retreat...</p>
                  <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">ID: #TK-7831</span>
                    <button class="text-primary-600 hover:text-primary-800 text-sm font-semibold">
                      <i class="fas fa-reply mr-1"></i> Respond
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Response Tools -->
          <div>
            <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
              <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Response Tools</h3>
              
              <div class="space-y-4">
                <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left group">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                      <i class="fas fa-clock text-primary-600"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-gray-900">Booking Confirmation</h4>
                      <p class="text-xs text-gray-600">Standard confirmation template</p>
                    </div>
                  </div>
                </button>

                <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left group">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                      <i class="fas fa-sync-alt text-primary-600"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-gray-900">Modification Request</h4>
                      <p class="text-xs text-gray-600">Standard modification template</p>
                    </div>
                  </div>
                </button>

                <button class="w-full p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors text-left group">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white flex items-center justify-center">
                      <i class="fas fa-times-circle text-primary-600"></i>
                    </div>
                    <div>
                      <h4 class="font-semibold text-gray-900">Cancellation Process</h4>
                      <p class="text-xs text-gray-600">Cancellation & refund template</p>
                    </div>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Chatbot Escalations Section -->
    <section id="chatbot" class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 animate-fade-in-up">
          <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">
            <i class="fas fa-robot text-purple-500 mr-3"></i>
            Chatbot Escalations
          </h2>
          <p class="text-lg text-gray-700">Handle complex queries escalated from the AI chatbot</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Escalated Conversations -->
          <div class="glass-effect rounded-2xl p-6 border border-purple-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Escalated Conversations</h3>
            
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-purple-100 bg-purple-50">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <span class="font-semibold text-gray-900">Complex Booking Query</span>
                    <span class="text-xs px-2 py-1 rounded-full status-escalated ml-2">Escalated</span>
                  </div>
                  <span class="text-xs text-gray-500">30m ago</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">
                  "I need to modify my booking for 5 people with different room preferences and dietary requirements..."
                </p>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-600">Chatbot ID: #CHAT-8923</span>
                  <button class="text-purple-600 hover:text-purple-800 text-sm font-semibold">
                    <i class="fas fa-comment-medical mr-1"></i> Take Over
                  </button>
                </div>
              </div>

              <div class="p-4 rounded-xl border border-purple-100 bg-purple-50">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <span class="font-semibold text-gray-900">Insurance Claim Process</span>
                    <span class="text-xs px-2 py-1 rounded-full status-escalated ml-2">Escalated</span>
                  </div>
                  <span class="text-xs text-gray-500">1h ago</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">
                  "Need assistance with travel insurance claim process after trip cancellation due to medical emergency..."
                </p>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-600">Chatbot ID: #CHAT-8922</span>
                  <button class="text-purple-600 hover:text-purple-800 text-sm font-semibold">
                    <i class="fas fa-comment-medical mr-1"></i> Take Over
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Chatbot Analytics -->
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Chatbot Performance</h3>
            
            <div class="space-y-6">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm font-medium text-gray-700">Escalation Rate</span>
                  <span class="text-sm font-semibold text-primary-600">12%</span>
                </div>
                <div class="w-full bg-primary-100 rounded-full h-2">
                  <div class="bg-primary-600 h-2 rounded-full" style="width: 12%"></div>
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm font-medium text-gray-700">Success Rate</span>
                  <span class="text-sm font-semibold text-green-600">88%</span>
                </div>
                <div class="w-full bg-green-100 rounded-full h-2">
                  <div class="bg-green-600 h-2 rounded-full" style="width: 88%"></div>
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm font-medium text-gray-700">Avg. Resolution Time</span>
                  <span class="text-sm font-semibold text-blue-600">8.5m</span>
                </div>
                <div class="w-full bg-blue-100 rounded-full h-2">
                  <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                </div>
              </div>

              <div class="pt-4 border-t border-primary-100">
                <h4 class="font-semibold text-gray-900 mb-3">Common Escalation Reasons</h4>
                <ul class="space-y-2 text-sm text-gray-700">
                  <li class="flex items-center">
                    <i class="fas fa-circle text-xs text-primary-500 mr-2"></i>
                    Complex booking modifications
                  </li>
                  <li class="flex items-center">
                    <i class="fas fa-circle text-xs text-primary-500 mr-2"></i>
                    Insurance & refund queries
                  </li>
                  <li class="flex items-center">
                    <i class="fas fa-circle text-xs text-primary-500 mr-2"></i>
                    Special requirements
                  </li>
                  <li class="flex items-center">
                    <i class="fas fa-circle text-xs text-primary-500 mr-2"></i>
                    Technical issues
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Booking Operations Section -->
    <section id="bookings" class="py-8 bg-gradient-to-b from-white to-primary-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 animate-fade-in-up">
          <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">
            <i class="fas fa-calendar-check text-green-500 mr-3"></i>
            Booking Operations
          </h2>
          <p class="text-lg text-gray-700">Handle cancellations, modifications, and booking issues</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Cancellation Requests -->
          <div class="glass-effect rounded-2xl p-6 border border-red-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
              <i class="fas fa-ban text-red-500 mr-2"></i>
              Cancellation Requests
            </h3>
            
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-red-100 bg-red-50">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <span class="font-semibold text-gray-900">Japan Tour - 2 Pax</span>
                    <span class="text-xs px-2 py-1 rounded-full priority-high ml-2">Refund Pending</span>
                  </div>
                  <span class="text-xs text-gray-500">Today</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">Booking #BK-4567 • Departure: 15 Mar 2024</p>
                <div class="flex gap-2">
                  <button class="flex-1 px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold transition-colors">
                    Process Refund
                  </button>
                  <button class="flex-1 px-3 py-2 rounded-lg bg-white hover:bg-gray-50 border border-red-200 text-gray-700 text-sm font-semibold transition-colors">
                    Contact Customer
                  </button>
                </div>
              </div>

              <div class="p-4 rounded-xl border border-red-100 bg-red-50">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <span class="font-semibold text-gray-900">Bali Retreat - 1 Pax</span>
                    <span class="text-xs px-2 py-1 rounded-full status-resolved ml-2">Refund Issued</span>
                  </div>
                  <span class="text-xs text-gray-500">Yesterday</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">Booking #BK-4566 • Refund: $2,450</p>
                <button class="w-full px-3 py-2 rounded-lg bg-white hover:bg-gray-50 border border-red-200 text-gray-700 text-sm font-semibold transition-colors">
                  View Details
                </button>
              </div>
            </div>
          </div>

          <!-- Modification Requests -->
          <div class="glass-effect rounded-2xl p-6 border border-blue-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
              <i class="fas fa-sync-alt text-blue-500 mr-2"></i>
              Modification Requests
            </h3>
            
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-blue-100 bg-blue-50">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <span class="font-semibold text-gray-900">Thailand Trip - Date Change</span>
                    <span class="text-xs px-2 py-1 rounded-full priority-medium ml-2">Review Needed</span>
                  </div>
                  <span class="text-xs text-gray-500">2h ago</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">Request to change dates from Apr 10 to Apr 20</p>
                <div class="flex gap-2">
                  <button class="flex-1 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-semibold transition-colors">
                    Check Availability
                  </button>
                  <button class="flex-1 px-3 py-2 rounded-lg bg-white hover:bg-gray-50 border border-blue-200 text-gray-700 text-sm font-semibold transition-colors">
                    Contact Customer
                  </button>
                </div>
              </div>

              <div class="p-4 rounded-xl border border-blue-100 bg-blue-50">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <span class="font-semibold text-gray-900">Vietnam Tour - Room Upgrade</span>
                    <span class="text-xs px-2 py-1 rounded-full status-resolved ml-2">Updated</span>
                  </div>
                  <span class="text-xs text-gray-500">Yesterday</span>
                </div>
                <p class="text-sm text-gray-700 mb-3">Upgrade from Standard to Deluxe Suite</p>
                <button class="w-full px-3 py-2 rounded-lg bg-white hover:bg-gray-50 border border-blue-200 text-gray-700 text-sm font-semibold transition-colors">
                  View Details
                </button>
              </div>
            </div>
          </div>

          <!-- Booking Issue Resolution -->
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
              <i class="fas fa-tools text-primary-500 mr-2"></i>
              Issue Resolution
            </h3>
            
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-primary-100 bg-primary-50">
                <div class="flex items-start justify-between mb-3">
                  <span class="font-semibold text-gray-900">Common Issues</span>
                  <span class="text-xs text-gray-500">Quick Fix</span>
                </div>
                
                <div class="space-y-3">
                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-key text-primary-600"></i>
                      <div>
                        <h4 class="font-medium text-gray-900">Access Issues</h4>
                        <p class="text-xs text-gray-600">Reset passwords, account access</p>
                      </div>
                    </div>
                  </button>

                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-file-invoice-dollar text-primary-600"></i>
                      <div>
                        <h4 class="font-medium text-gray-900">Payment Problems</h4>
                        <p class="text-xs text-gray-600">Failed payments, refund status</p>
                      </div>
                    </div>
                  </button>

                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-map-marked-alt text-primary-600"></i>
                      <div>
                        <h4 class="font-medium text-gray-900">Booking Details</h4>
                        <p class="text-xs text-gray-600">Missing info, incorrect details</p>
                      </div>
                    </div>
                  </button>
                </div>
              </div>

              <div class="text-center">
                <a href="booking_tools.php" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-semibold">
                  <i class="fas fa-external-link-alt mr-2"></i>
                  Open Advanced Booking Tools
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Customer Records Section -->
    <section id="customers" class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 animate-fade-in-up">
          <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">
            <i class="fas fa-users text-indigo-500 mr-3"></i>
            Customer Records
          </h2>
          <p class="text-lg text-gray-700">Update and manage customer information and records</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Customer Search -->
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Customer Search</h3>
            
            <div class="space-y-4">
              <div class="relative">
                <input type="text" 
                       placeholder="Search by name, email, phone, or booking ID..."
                       class="w-full p-3 pl-10 rounded-xl border border-primary-200 focus:border-primary-400 focus:ring-2 focus:ring-primary-200 focus:outline-none transition-all">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <button class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors">
                  <i class="fas fa-user-tag text-primary-600 mb-2"></i>
                  <div class="font-medium text-gray-900">By Booking ID</div>
                </button>
                <button class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors">
                  <i class="fas fa-envelope text-primary-600 mb-2"></i>
                  <div class="font-medium text-gray-900">By Email</div>
                </button>
                <button class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors">
                  <i class="fas fa-phone text-primary-600 mb-2"></i>
                  <div class="font-medium text-gray-900">By Phone</div>
                </button>
                <button class="p-3 rounded-xl bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors">
                  <i class="fas fa-id-card text-primary-600 mb-2"></i>
                  <div class="font-medium text-gray-900">By Name</div>
                </button>
              </div>
            </div>
          </div>

          <!-- Update Customer Information -->
          <div class="glass-effect rounded-2xl p-6 border border-primary-100 shadow-gold">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Update Records</h3>
            
            <div class="space-y-4">
              <div class="p-4 rounded-xl border border-primary-100 bg-primary-50">
                <h4 class="font-semibold text-gray-900 mb-3">Common Updates</h4>
                
                <div class="space-y-3">
                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <i class="fas fa-user-edit text-primary-600"></i>
                        <div>
                          <h4 class="font-medium text-gray-900">Contact Details</h4>
                          <p class="text-xs text-gray-600">Update phone, email, address</p>
                        </div>
                      </div>
                      <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                  </button>

                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <i class="fas fa-passport text-primary-600"></i>
                        <div>
                          <h4 class="font-medium text-gray-900">Passport & Documents</h4>
                          <p class="text-xs text-gray-600">Update travel documents</p>
                        </div>
                      </div>
                      <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                  </button>

                  <button class="w-full p-3 rounded-lg bg-white hover:bg-gray-50 border border-primary-200 text-left transition-colors">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <i class="fas fa-heart text-primary-600"></i>
                        <div>
                          <h4 class="font-medium text-gray-900">Preferences</h4>
                          <p class="text-xs text-gray-600">Dietary, room, activity preferences</p>
                        </div>
                      </div>
                      <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                  </button>
                </div>
              </div>

              <div class="text-center">
                <a href="customer_records.php" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-semibold">
                  <i class="fas fa-database mr-2"></i>
                  Access Full Customer Database
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Support Footer -->
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
            <li><a href="#tickets" class="hover:text-primary-600 transition-colors">Ticket Management</a></li>
            <li><a href="#chatbot" class="hover:text-primary-600 transition-colors">Chatbot Escalations</a></li>
            <li><a href="#bookings" class="hover:text-primary-600 transition-colors">Booking Operations</a></li>
            <li><a href="#customers" class="hover:text-primary-600 transition-colors">Customer Records</a></li>
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

  <!-- Combined JavaScript -->
  <script>
    // Loading screen
    window.addEventListener('load', function() {
      const advancedLoader = document.getElementById('advanced-loader');
      if (advancedLoader) {
        setTimeout(() => {
          advancedLoader.style.opacity = '0';
          setTimeout(() => {
            advancedLoader.style.display = 'none';
          }, 500);
        }, 800);
      }
    });

    // Enhanced mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      const mobileMenuClose = document.getElementById('mobile-menu-close');
      const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

      function toggleMobileMenu() {
        mobileMenu.classList.toggle('open');
        document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
      }

      if (menuButton) menuButton.addEventListener('click', toggleMobileMenu);
      if (mobileMenuClose) mobileMenuClose.addEventListener('click', toggleMobileMenu);
      if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', toggleMobileMenu);

      // Smooth scrolling for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          const href = this.getAttribute('href');
          if (!href || href === '#') return;
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      // Initialize animations for elements with animation classes
      const animatedElements = document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down, .animate-slide-in-right, .animate-zoom-in');
      animatedElements.forEach(el => {
        el.style.opacity = 0;
      });

      // Simple fade in on scroll
      const fadeInOnScroll = () => {
        animatedElements.forEach(el => {
          const rect = el.getBoundingClientRect();
          const isVisible = (rect.top <= window.innerHeight * 0.8);
          if (isVisible) {
            el.style.opacity = 1;
            el.style.transform = 'translateY(0) scale(1)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
          }
        });
      };

      window.addEventListener('scroll', fadeInOnScroll);
      fadeInOnScroll(); // Initial check

      // Ticket response buttons
      document.querySelectorAll('button:contains("Respond"), button:contains("Take Over")').forEach(btn => {
        btn.addEventListener('click', function() {
          const ticketId = this.closest('.group').querySelector('span:contains("ID:")').textContent.replace('ID: ', '');
          alert(`Opening response interface for ${ticketId}`);
          // In real implementation, this would open a modal or redirect to ticket page
        });
      });

      // Quick action buttons
      document.querySelectorAll('.priority-high, .priority-medium, .priority-low').forEach(badge => {
        badge.addEventListener('click', function() {
          const priority = this.textContent;
          console.log(`Filtering by ${priority} priority`);
          // In real implementation, this would filter tickets
        });
      });
    });

    // Update current time
    function updateCurrentTime() {
      const now = new Date();
      const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const dateString = now.toLocaleDateString([], { weekday: 'short', month: 'short', day: 'numeric' });
      
      const timeElements = document.querySelectorAll('.current-time');
      timeElements.forEach(el => {
        if (el.classList.contains('time-only')) {
          el.textContent = timeString;
        } else {
          el.textContent = `${dateString} ${timeString}`;
        }
      });
    }

    // Update time every minute
    setInterval(updateCurrentTime, 60000);
    updateCurrentTime(); // Initial call
  </script>
</body>
</html>