<?php
// guest_dashboard.php
// Premium TravelEase Guest Dashboard with Enhanced Features & Device Responsiveness
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Guest Dashboard | TravelEase - Premium Asia Travel Experiences</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Premium Asia travel experiences with TravelEase. Curated luxury trips across South, East, Southeast, Central & West Asia.">

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
            'parallax': 'parallax 20s linear infinite',
            'pulse-slow': 'pulse 3s ease-in-out infinite',
            'bounce-slow': 'bounce 2s ease-in-out infinite',
            'spin-slow': 'spin 3s linear infinite'
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
            parallax: {
              '0%': { transform: 'translateY(0)' },
              '100%': { transform: 'translateY(-50%)' }
            }
          },
          backgroundSize: {
            '200%': '200% 200%'
          },
          screens: {
            'xs': '475px',
            '3xl': '1600px'
          }
        }
      }
    };
  </script>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { 
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #fef7e5 50%, #fef3c7 100%);
      color: #1f2937;
      overflow-x: hidden;
    }
    .premium-font { font-family: "Playfair Display", serif; }
    .glass-effect {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
    }
    .premium-gradient {
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
    .parallax-bg {
      background-image: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
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
    
    /* Enhanced responsive design */
    @media (max-width: 768px) {
      .hero-headline {
        font-size: 2.5rem;
        line-height: 1.2;
      }
      .destination-card {
        min-height: 300px;
      }
      .experience-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 640px) {
      .hero-headline {
        font-size: 2rem;
      }
      .cta-buttons {
        flex-direction: column;
        width: 100%;
      }
      .cta-buttons a {
        width: 100%;
        text-align: center;
      }
    }
    
    /* Loading animations */
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
  </style>
</head>
<body class="min-h-screen">

  <!-- Loading Bar -->
  <div class="loading-bar fixed top-0 left-0 z-50"></div>

  <!-- Enhanced Mobile Menu -->
  <div id="mobile-menu" class="mobile-menu fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
    <div class="fixed top-0 left-0 h-full w-80 max-w-full bg-white/95 backdrop-blur-xl shadow-2xl overflow-y-auto">
      <div class="p-6">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center">
              <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center font-black text-amber-600 text-xs">TE</div>
            </div>
            <span class="premium-font font-black text-xl text-gray-900">TravelEase</span>
          </div>
          <button id="mobile-menu-close" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="space-y-4">
          <a href="#hero" class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 text-amber-600 font-semibold">
            <i class="fas fa-star w-6 text-center"></i>
            Premium
          </a>
          <a href="#destinations" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-map-marked-alt w-6 text-center"></i>
            Destinations
          </a>
          <a href="#experiences" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-gem w-6 text-center"></i>
            Experiences
          </a>
          <a href="#testimonials" class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold">
            <i class="fas fa-comment-alt w-6 text-center"></i>
            Testimonials
          </a>
        </nav>

        <!-- Mobile CTA Buttons -->
        <div class="mt-8 space-y-4">
          <a href="login.php" class="block w-full text-center px-6 py-3 rounded-2xl glass-effect text-gray-700 hover:bg-amber-50 transition-all font-semibold border border-amber-100">
            Sign In
          </a>
          <a href="create_account.php" class="block w-full text-center px-6 py-3 rounded-2xl gold-gradient text-white hover:shadow-2xl hover:shadow-amber-500/25 transition-all font-bold shadow-gold">
            Join Premium
          </a>
        </div>

        <!-- Mobile Contact Info -->
        <div class="mt-8 pt-8 border-t border-amber-100">
          <div class="space-y-3 text-sm text-gray-600">
            <div class="flex items-center gap-3">
              <i class="fas fa-phone-alt text-amber-500"></i>
              <span>+1 (555) 123-4567</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fas fa-envelope text-amber-500"></i>
              <span>concierge@travelease.com</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fas fa-clock text-amber-500"></i>
              <span>24/7 Concierge</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Enhanced Premium Navigation -->
  <header class="fixed top-0 left-0 right-0 z-30 glass-effect border-b border-amber-100/50 backdrop-blur-xl">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <a href="guest_dashboard.php" class="flex items-center gap-3 group">
            <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-lg shadow-amber-200 group-hover:scale-105 transition-transform duration-300">
              <img src="img/Logo.png" alt="TravelEase Logo" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col leading-tight">
              <span class="premium-font font-black text-xl tracking-tight text-gray-900">
                TravelEase
              </span>
              <span class="hidden sm:inline-block text-xs text-gray-600 font-medium">
                Luxury Asia Travel
              </span>
            </div>
          </a>
        </div>

        <!-- Center Navigation -->
        <div class="hidden lg:flex items-center gap-8 text-sm font-semibold">
          <a href="#hero" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            <span class="flex items-center gap-2">
              <i class="fas fa-star text-xs text-amber-500"></i>
              Premium
            </span>
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#destinations" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            Destinations
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#experiences" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            Experiences
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
          <a href="#testimonials" class="text-gray-700 hover:text-amber-600 transition-all duration-300 relative group">
            Testimonials
            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-500 group-hover:w-full transition-all duration-300"></span>
          </a>
        </div>

        <!-- CTA Buttons -->
        <div class="hidden lg:flex items-center gap-4">
          <a href="login.php"
             class="px-6 py-2.5 rounded-xl glass-effect text-sm font-semibold text-gray-700 hover:text-amber-600 hover:bg-amber-50 transition-all duration-300 border border-amber-100">
            Sign In
          </a>
          <a href="create_account.php"
             class="px-6 py-2.5 rounded-xl gold-gradient text-sm font-bold text-white hover:shadow-2xl hover:shadow-amber-500/25 transition-all duration-300 shadow-gold">
            Join Premium
          </a>
        </div>

        <!-- Enhanced Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-3 rounded-2xl text-gray-700 hover:bg-amber-50 transition-colors">
          <i class="fas fa-bars text-lg"></i>
        </button>
      </div>
    </nav>
  </header>

  <!-- Enhanced Premium Hero Section -->
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <!-- Animated Background -->
    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-white/80 z-10"></div>
      <div class="parallax-bg absolute inset-0 animate-parallax"></div>
      <div class="absolute inset-0 bg-gradient-to-br from-amber-200/30 via-transparent to-yellow-100/30 z-20"></div>
      
      <!-- Floating Elements -->
      <div class="absolute top-20 left-10 animate-float">
        <div class="w-8 h-8 rounded-full bg-amber-400/30 backdrop-blur-sm"></div>
      </div>
      <div class="absolute top-40 right-20 animate-float" style="animation-delay: 1s;">
        <div class="w-6 h-6 rounded-full bg-yellow-500/40 backdrop-blur-sm"></div>
      </div>
      <div class="absolute bottom-40 left-20 animate-float" style="animation-delay: 2s;">
        <div class="w-10 h-10 rounded-full bg-amber-300/50 backdrop-blur-sm"></div>
      </div>
    </div>

    <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="animate-fade-in-up">
        <!-- Premium Badge -->
        <div class="inline-flex items-center gap-3 glass-effect px-6 py-3 rounded-2xl mb-8 border border-amber-200 shadow-gold">
          <i class="fas fa-crown text-amber-500 animate-pulse-slow"></i>
          <span class="text-sm font-semibold text-amber-600">LUXURY ASIA TRAVEL CURATED</span>
        </div>

        <!-- Main Headline -->
        <h1 class="premium-font hero-headline text-4xl xs:text-5xl sm:text-6xl lg:text-7xl font-black mb-6 leading-tight">
          <span class="text-gray-900">Discover</span>
          <span class="text-gradient block mt-2">Asian Elegance</span>
        </h1>

        <!-- Subheadline -->
        <p class="text-lg sm:text-xl lg:text-2xl text-gray-700 max-w-4xl mx-auto mb-8 leading-relaxed">
          Experience Asia's most exclusive destinations with bespoke itineraries, 
          luxury accommodations, and unparalleled service.
        </p>

        <!-- Enhanced CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 cta-buttons">
          <a href="create_account.php"
             class="group px-8 sm:px-12 py-4 rounded-2xl gold-gradient text-base sm:text-lg font-bold text-white hover:shadow-2xl hover:shadow-amber-500/50 transition-all duration-500 transform hover:scale-105 shadow-gold w-full sm:w-auto">
            <span class="flex items-center gap-3 justify-center">
              Begin Luxury Journey
              <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </span>
          </a>
          <a href="#destinations"
             class="group px-8 sm:px-12 py-4 rounded-2xl glass-effect text-base sm:text-lg font-semibold text-gray-700 border border-amber-200 hover:bg-amber-50 hover:border-amber-300 transition-all duration-300 shadow-sm w-full sm:w-auto">
            <span class="flex items-center gap-3 justify-center">
              Explore Destinations
              <i class="fas fa-compass group-hover:rotate-90 transition-transform"></i>
            </span>
          </a>
        </div>

        <!-- Premium Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 max-w-2xl mx-auto">
          <div class="text-center">
            <div class="text-xl sm:text-2xl font-black text-amber-600 mb-1">15K+</div>
            <div class="text-xs sm:text-sm text-gray-600">Luxury Travelers</div>
          </div>
          <div class="text-center">
            <div class="text-xl sm:text-2xl font-black text-amber-600 mb-1">4.9★</div>
            <div class="text-xs sm:text-sm text-gray-600">Premium Rating</div>
          </div>
          <div class="text-center">
            <div class="text-xl sm:text-2xl font-black text-amber-600 mb-1">50+</div>
            <div class="text-xs sm:text-sm text-gray-600">Destinations</div>
          </div>
          <div class="text-center">
            <div class="text-xl sm:text-2xl font-black text-amber-600 mb-1">24/7</div>
            <div class="text-xs sm:text-sm text-gray-600">Concierge</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 animate-bounce-slow">
      <a href="#destinations" class="text-amber-500 hover:text-amber-600 transition-colors">
        <i class="fas fa-chevron-down text-2xl"></i>
      </a>
    </div>
  </section>

  <!-- Enhanced Premium Destinations Section -->
  <section id="destinations" class="py-16 sm:py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-12 sm:mb-16 animate-fade-in-up">
        <h2 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-6">
          <span class="text-gray-900">Curated</span>
          <span class="text-gradient block">Luxury Destinations</span>
        </h2>
        <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
          Handpicked luxury experiences across Asia's most captivating destinations. 
          Each journey is meticulously crafted for the discerning traveler.
        </p>
      </div>

      <!-- Premium Destination Cards -->
      <div class="grid gap-6 sm:gap-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Japan Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1540959733332-4ab8c8a34c9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Japan Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Japan</h3>
              <p class="text-amber-600 text-sm font-semibold">From $8,500</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Kyoto · Tokyo · Osaka</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Private tea ceremonies, luxury ryokan stays, and exclusive cultural experiences.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">12-16 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Bali Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Bali Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Bali</h3>
              <p class="text-amber-600 text-sm font-semibold">From $6,200</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Ubud · Seminyak · Nusa Dua</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Private villas, spiritual retreats, and bespoke wellness experiences.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">10-14 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Thailand Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Thailand Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Thailand</h3>
              <p class="text-amber-600 text-sm font-semibold">From $7,800</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Bangkok · Phuket · Chiang Mai</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Private island access, luxury resorts, and exclusive culinary journeys.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">14-18 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Vietnam Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1583417319070-4a69db38a482?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Vietnam Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Vietnam</h3>
              <p class="text-amber-600 text-sm font-semibold">From $5,900</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Halong Bay · Hoi An · Saigon</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Cruise through emerald waters, ancient towns, and vibrant city life.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">10-14 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Destinations Row -->
      <div class="grid gap-6 sm:gap-8 md:grid-cols-2 xl:grid-cols-4 mt-6 sm:mt-8">
        <!-- Sri Lanka Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1559668612-8f8f9d254b1a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Sri Lanka Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Sri Lanka</h3>
              <p class="text-amber-600 text-sm font-semibold">From $5,500</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Kandy · Ella · Galle</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Tea plantations, ancient temples, and pristine beaches.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">9-12 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Maldives Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Maldives Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Maldives</h3>
              <p class="text-amber-600 text-sm font-semibold">From $9,500</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Private Islands</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Overwater villas, private yacht charters, and exclusive marine experiences.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">7-12 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- South Korea Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1534274867514-d5b47ef89ed7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="South Korea Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">South Korea</h3>
              <p class="text-amber-600 text-sm font-semibold">From $7,200</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Seoul · Busan · Jeju Island</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Modern cities, ancient palaces, and stunning natural landscapes.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">10-14 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- India Card -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold destination-card">
          <div class="relative h-64 sm:h-80 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="India Luxury"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">PREMIUM</span>
            </div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">India</h3>
              <p class="text-amber-600 text-sm font-semibold">From $6,800</p>
            </div>
          </div>
          <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fas fa-star text-amber-500 text-sm"></i>
              <span class="text-sm text-gray-600">Rajasthan · Kerala · Goa</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              Palaces, backwaters, and vibrant cultural experiences.
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">12-16 days</span>
              <button class="text-amber-600 hover:text-amber-700 transition-colors">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Waterfalls & Natural Wonders Section -->
  <section class="py-16 sm:py-20 relative bg-gradient-to-b from-amber-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12 sm:mb-16 animate-fade-in-up">
        <h2 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-6">
          <span class="text-gray-900">Asia's Natural</span>
          <span class="text-gradient block">Wonders</span>
        </h2>
        <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
          Discover breathtaking waterfalls, pristine landscapes, and natural marvels across Asia's diverse terrain.
        </p>
      </div>

      <div class="grid gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Kawasan Falls, Philippines -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold">
          <div class="relative h-56 sm:h-64 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Kawasan Falls, Philippines"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-lg sm:text-xl font-black text-gray-900">Kawasan Falls</h3>
              <p class="text-amber-600 text-sm font-semibold">Philippines</p>
            </div>
          </div>
        </div>

        <!-- Kuang Si Falls, Laos -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold">
          <div class="relative h-56 sm:h-64 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1591277720113-9e08e2340fb8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Kuang Si Falls, Laos"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-lg sm:text-xl font-black text-gray-900">Kuang Si Falls</h3>
              <p class="text-amber-600 text-sm font-semibold">Laos</p>
            </div>
          </div>
        </div>

        <!-- Detian Falls, Vietnam/China -->
        <div class="group relative overflow-hidden rounded-3xl hover-lift glass-effect border border-amber-100 shadow-gold">
          <div class="relative h-56 sm:h-64 overflow-hidden">
            <img 
              src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
              alt="Detian Falls, Vietnam/China"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            <div class="absolute bottom-4 left-4">
              <h3 class="text-lg sm:text-xl font-black text-gray-900">Detian Falls</h3>
              <p class="text-amber-600 text-sm font-semibold">Vietnam/China</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Premium Experiences Section -->
  <section id="experiences" class="py-16 sm:py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <!-- Left Content -->
        <div class="animate-fade-in-up">
          <h2 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-6">
            <span class="text-gray-900">Exclusive</span>
            <span class="text-gradient block">Travel Experiences</span>
          </h2>
          <p class="text-lg sm:text-xl text-gray-700 mb-8 leading-relaxed">
            Our premium experiences are designed for travelers who seek more than just a vacation. 
            Each journey is a masterpiece of luxury, culture, and adventure.
          </p>

          <!-- Experience Features -->
          <div class="space-y-6">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center flex-shrink-0">
                <i class="fas fa-crown text-white"></i>
              </div>
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Bespoke Itineraries</h4>
                <p class="text-gray-700">Every journey is custom-designed around your preferences and interests.</p>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-shield text-white"></i>
              </div>
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">24/7 Concierge</h4>
                <p class="text-gray-700">Dedicated travel concierge available around the clock throughout your journey.</p>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl gold-gradient flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star text-white"></i>
              </div>
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Luxury Partners</h4>
                <p class="text-gray-700">Exclusive access to five-star hotels, private guides, and premium services.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Experience Showcase -->
        <div class="relative animate-zoom-in">
          <div class="glass-effect rounded-3xl p-6 sm:p-8 border border-amber-100 shadow-gold">
            <div class="grid gap-4 sm:gap-6 experience-grid">
              <!-- Experience 1 -->
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 hover:bg-amber-100 transition-all duration-300">
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-utensils text-white text-lg sm:text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 mb-1">Private Dining Experiences</h4>
                  <p class="text-sm text-gray-700">Michelin-star chefs in exclusive locations</p>
                </div>
              </div>

              <!-- Experience 2 -->
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 hover:bg-amber-100 transition-all duration-300">
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-spa text-white text-lg sm:text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 mb-1">Wellness Retreats</h4>
                  <p class="text-sm text-gray-700">Private wellness and spiritual journeys</p>
                </div>
              </div>

              <!-- Experience 3 -->
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 hover:bg-amber-100 transition-all duration-300">
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-helicopter text-white text-lg sm:text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 mb-1">Helicopter Tours</h4>
                  <p class="text-sm text-gray-700">Aerial views of iconic landscapes</p>
                </div>
              </div>

              <!-- Experience 4 -->
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50 hover:bg-amber-100 transition-all duration-300">
                <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
                  <i class="fas fa-gem text-white text-lg sm:text-xl"></i>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 mb-1">Cultural Immersion</h4>
                  <p class="text-sm text-gray-700">Private access to cultural sites and events</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Premium Testimonials -->
  <section id="testimonials" class="py-16 sm:py-20 relative bg-gradient-to-b from-white to-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12 sm:mb-16 animate-fade-in-up">
        <h2 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-6">
          <span class="text-gray-900">Elite</span>
          <span class="text-gradient block">Traveler Stories</span>
        </h2>
        <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto">
          Discover why discerning travelers choose TravelEase for their most memorable journeys.
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Testimonial 1 -->
        <div class="group glass-effect rounded-3xl p-6 sm:p-8 border border-amber-100 hover-lift shadow-gold">
          <div class="flex items-center gap-4 mb-6">
            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
              <span class="font-black text-white text-sm sm:text-base">SR</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Sarah & Robert</h4>
              <p class="text-sm text-amber-600">Japan Luxury Tour</p>
            </div>
          </div>
          <p class="text-gray-700 leading-relaxed mb-6">
            "The attention to detail was extraordinary. From private geisha dinners to exclusive temple visits, every moment was perfection."
          </p>
          <div class="flex items-center justify-between">
            <div class="flex text-amber-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="text-xs text-gray-500">March 2024</span>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="group glass-effect rounded-3xl p-6 sm:p-8 border border-amber-100 hover-lift shadow-gold">
          <div class="flex items-center gap-4 mb-6">
            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
              <span class="font-black text-white text-sm sm:text-base">MJ</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Michael Johnson</h4>
              <p class="text-sm text-amber-600">Southeast Asia Grand Tour</p>
            </div>
          </div>
          <p class="text-gray-700 leading-relaxed mb-6">
            "TravelEase redefined luxury travel for us. The seamless transitions between destinations and exclusive access made all the difference."
          </p>
          <div class="flex items-center justify-between">
            <div class="flex text-amber-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="text-xs text-gray-500">January 2024</span>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="group glass-effect rounded-3xl p-6 sm:p-8 border border-amber-100 hover-lift shadow-gold">
          <div class="flex items-center gap-4 mb-6">
            <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl gold-gradient flex items-center justify-center">
              <span class="font-black text-white text-sm sm:text-base">EC</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Emma Chen</h4>
              <p class="text-sm text-amber-600">Bali Wellness Retreat</p>
            </div>
          </div>
          <p class="text-gray-700 leading-relaxed mb-6">
            "An transformative experience. The private villa, personalized wellness program, and cultural immersion exceeded all expectations."
          </p>
          <div class="flex items-center justify-between">
            <div class="flex text-amber-500">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="text-xs text-gray-500">February 2024</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Final Premium CTA -->
  <section class="py-16 sm:py-20 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="glass-effect rounded-3xl p-8 sm:p-12 border border-amber-200 relative overflow-hidden shadow-gold">
        <!-- Background Elements -->
        <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-amber-200/50 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-yellow-200/50 blur-3xl"></div>
        
        <div class="relative z-10">
          <h2 class="premium-font text-3xl sm:text-4xl lg:text-5xl font-black mb-6">
            Ready for Your
            <span class="text-gradient block">Luxury Journey?</span>
          </h2>
          <p class="text-lg sm:text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
            Join our community of elite travelers and experience Asia like never before. 
            Your bespoke luxury adventure awaits.
          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="create_account.php"
               class="group px-8 sm:px-12 py-4 rounded-2xl gold-gradient text-base sm:text-lg font-bold text-white hover:shadow-2xl hover:shadow-amber-500/50 transition-all duration-500 transform hover:scale-105 shadow-gold w-full sm:w-auto">
              <span class="flex items-center gap-3 justify-center">
                Begin Your Journey
                <i class="fas fa-gem group-hover:rotate-180 transition-transform"></i>
              </span>
            </a>
            <a href="login.php"
               class="group px-8 sm:px-12 py-4 rounded-2xl glass-effect text-base sm:text-lg font-semibold text-gray-700 border border-amber-200 hover:bg-amber-50 hover:border-amber-300 transition-all duration-300 shadow-sm w-full sm:w-auto">
              <span class="flex items-center gap-3 justify-center">
                Sign In to Account
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
              </span>
            </a>
          </div>

          <p class="text-sm text-gray-600 mt-8">
            <i class="fas fa-shield-alt text-amber-500 mr-2"></i>
            Secure booking · Best price guarantee · 24/7 luxury concierge
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Premium Footer -->
  <footer class="border-t border-amber-100 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid gap-8 md:grid-cols-4 mb-8">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="h-10 w-10 rounded-xl gold-gradient flex items-center justify-center">
              <div class="h-6 w-6 rounded-lg bg-white flex items-center justify-center font-black text-amber-600 text-xs">TE</div>
            </div>
            <span class="premium-font font-black text-lg text-gray-900">TravelEase</span>
          </div>
          <p class="text-sm text-gray-700 mb-4">
            Curating Asia's finest luxury travel experiences for discerning travelers.
          </p>
          <div class="flex gap-3">
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="h-10 w-10 rounded-xl glass-effect flex items-center justify-center text-gray-600 hover:text-amber-600 hover:bg-amber-100 transition-all border border-amber-100">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Destinations</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="#" class="hover:text-amber-600 transition-colors">Japan Luxury</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Bali Retreats</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Thailand Islands</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Vietnam Culture</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Sri Lanka Nature</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Experiences</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="#" class="hover:text-amber-600 transition-colors">Luxury Cruises</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Private Villas</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Culinary Tours</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Wellness Retreats</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Adventure Travel</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
          <ul class="space-y-2 text-sm text-gray-700">
            <li><a href="#" class="hover:text-amber-600 transition-colors">Contact Concierge</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Booking Assistance</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Travel Insurance</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-amber-600 transition-colors">Terms of Service</a></li>
          </ul>
        </div>
      </div>

      <div class="pt-8 border-t border-amber-100 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
        <p>© <?php echo date('Y'); ?> TravelEase Luxury Travel. All rights reserved.</p>
        <div class="flex items-center gap-4">
          <span>Premium Asia Travel Curator</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Enhanced JavaScript -->
  <script>
    // Enhanced Mobile menu functionality
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

    function toggleMobileMenu() {
      mobileMenu.classList.toggle('open');
      document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    }

    if (menuButton) {
      menuButton.addEventListener('click', toggleMobileMenu);
    }

    if (mobileMenuClose) {
      mobileMenuClose.addEventListener('click', toggleMobileMenu);
    }

    if (mobileMenuBackdrop) {
      mobileMenuBackdrop.addEventListener('click', toggleMobileMenu);
    }

    // Enhanced scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down, .animate-slide-in-right, .animate-zoom-in').forEach(el => {
      el.style.opacity = 0;
      el.style.transform = 'translateY(30px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });

    // Enhanced Parallax effect for hero background
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.parallax-bg');
      if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
      }
    });

    // Add floating animation to premium elements
    document.querySelectorAll('.gold-gradient').forEach((el, index) => {
      el.style.animationDelay = `${index * 0.2}s`;
    });

    // Enhanced loading bar
    window.addEventListener('load', () => {
      const loadingBar = document.querySelector('.loading-bar');
      if (loadingBar) {
        loadingBar.style.opacity = '0';
        setTimeout(() => {
          loadingBar.remove();
        }, 500);
      }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Enhanced touch interactions for mobile
    let touchStartY = 0;
    let touchEndY = 0;

    document.addEventListener('touchstart', e => {
      touchStartY = e.changedTouches[0].screenY;
    });

    document.addEventListener('touchend', e => {
      touchEndY = e.changedTouches[0].screenY;
      handleSwipe();
    });

    function handleSwipe() {
      const swipeDistance = touchStartY - touchEndY;
      if (Math.abs(swipeDistance) > 50) {
        // Add swipe functionality if needed
      }
    }

    // Performance optimization: Lazy loading for images
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            imageObserver.unobserve(img);
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }
  </script>
</body>
</html>