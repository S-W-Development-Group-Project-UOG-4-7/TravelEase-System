<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#facc15",
            primaryDark: "#eab308",
            primarySoft: "#fef9c3",
            ink: "#111827",
            inkMuted: "#6b7280",
            card: "#ffffff",
            bgSoft: "#f9fafb",
          },
          boxShadow: {
            'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
            'medium': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
            'large': '0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02)'
          }
        },
      },
    };
  </script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { 
      font-family: Inter, sans-serif; 
      background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
      min-height: 100vh;
    }
    .form-input:focus {
      box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.15);
    }
    .form-group {
      transition: all 0.2s ease;
    }
    .form-group:focus-within {
      transform: translateY(-2px);
    }
    .btn-primary {
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px -1px rgba(250, 204, 21, 0.2), 0 2px 4px -1px rgba(250, 204, 21, 0.06);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(250, 204, 21, 0.3), 0 4px 6px -2px rgba(250, 204, 21, 0.1);
    }
    .btn-secondary {
      transition: all 0.2s ease;
    }
    .btn-secondary:hover {
      transform: translateY(-1px);
    }
    .floating-element {
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    .gradient-border {
      background: linear-gradient(white, white) padding-box,
                  linear-gradient(135deg, #facc15, #eab308) border-box;
      border: 1px solid transparent;
    }
    .pulse-glow {
      animation: pulse-glow 2s infinite;
    }
    @keyframes pulse-glow {
      0% { box-shadow: 0 0 0 0 rgba(250, 204, 21, 0.4); }
      70% { box-shadow: 0 0 0 10px rgba(250, 204, 21, 0); }
      100% { box-shadow: 0 0 0 0 rgba(250, 204, 21, 0); }
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8">
  <!-- Background decorative elements -->
  <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-primary/10 floating-element"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 rounded-full bg-primary/10 floating-element" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/3 right-1/4 w-12 h-12 rounded-full bg-primary/10 floating-element" style="animation-delay: 4s;"></div>
  </div>

  <div class="w-full max-w-md bg-white rounded-2xl shadow-large gradient-border overflow-hidden relative z-10">
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-primarySoft to-yellow-50 px-6 py-4 border-b border-yellow-100 relative">
      <div class="flex items-center justify-between">
        <a href="guest_dashboard.php" class="flex items-center text-xs font-medium text-inkMuted hover:text-ink transition-colors group">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Dashboard
        </a>
        <div class="flex items-center">
          <div class="relative">
            <img src="img/Logo.png" alt="TravelEase Logo" class="h-8">
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-primary rounded-full pulse-glow"></div>
          </div>
          <span class="ml-2 text-sm font-bold text-ink">TravelEase</span>
        </div>
      </div>
    </div>
    
    <!-- Content Section -->
    <div class="px-6 sm:px-8 py-6">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primarySoft mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-ink">Create Your Account</h1>
        <p class="text-sm text-inkMuted mt-2 max-w-xs mx-auto">
          Join TravelEase to start planning your perfect trip across Asia.
        </p>
      </div>

      <!-- Form -->
      <form method="POST" action="register_action.php" class="space-y-5">
        
        <div class="form-group">
          <label class="block text-sm font-semibold text-ink mb-2 flex items-center">
            <span>Full Name</span>
            <span class="ml-1 text-xs text-inkMuted">(required)</span>
          </label>
          <div class="relative">
            <input type="text" name="fullname" required placeholder="Enter your full name"
                   class="form-input w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="block text-sm font-semibold text-ink mb-2 flex items-center">
            <span>Email Address</span>
            <span class="ml-1 text-xs text-inkMuted">(required)</span>
          </label>
          <div class="relative">
            <input type="email" name="email" required placeholder="your.email@example.com"
                   class="form-input w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="block text-sm font-semibold text-ink mb-2 flex items-center">
            <span>Phone Number</span>
            <span class="ml-1 text-xs text-inkMuted">(required)</span>
          </label>
          <div class="relative">
            <input type="text" name="phone" required placeholder="+1 (555) 123-4567"
                   class="form-input w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="block text-sm font-semibold text-ink mb-2 flex items-center">
            <span>Password</span>
            <span class="ml-1 text-xs text-inkMuted">(required)</span>
          </label>
          <div class="relative">
            <input type="password" name="password" required placeholder="Create a strong password"
                   class="form-input w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-inkMuted mt-1">Use 8+ characters with a mix of letters, numbers & symbols</p>
        </div>

        <div class="form-group">
          <label class="block text-sm font-semibold text-ink mb-2 flex items-center">
            <span>Confirm Password</span>
            <span class="ml-1 text-xs text-inkMuted">(required)</span>
          </label>
          <div class="relative">
            <input type="password" name="confirm_password" required placeholder="Re-enter your password"
                   class="form-input w-full border border-yellow-200 bg-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-inkMuted/50">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-inkMuted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="btn-primary w-full py-3.5 rounded-lg bg-primary font-semibold text-ink text-sm hover:bg-primaryDark flex items-center justify-center">
          <span>Create Account</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </button>

        <div class="text-center text-xs text-inkMuted mt-4 pt-4 border-t border-yellow-100">
          Already have a TravelEase account?
        </div>

        <a href="login.php"
           class="btn-secondary flex items-center justify-center w-full text-center py-3 rounded-lg border border-yellow-200 text-inkMuted text-sm hover:bg-primarySoft hover:text-ink transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          Sign In to Your Account
        </a>

        <a href="guest_dashboard.php"
           class="btn-secondary flex items-center justify-center w-full text-center py-2.5 rounded-lg border border-yellow-100 text-inkMuted text-sm hover:bg-bgSoft hover:text-ink transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Continue as Guest
        </a>

      </form>
    </div>
  </div>
</body>
</html>