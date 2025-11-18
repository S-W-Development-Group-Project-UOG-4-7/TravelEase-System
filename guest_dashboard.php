<?php
// guest_dashboard.php
// Guest dashboard for TravelEase with Asia map and Create Account link.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Guest Dashboard | TravelEase</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#facc15',        // yellow-400
            primaryDark: '#eab308',    // yellow-500
            primarySoft: '#fef9c3',    // yellow-100
            ink: '#111827',            // gray-900
            inkMuted: '#6b7280',       // gray-500
            card: '#ffffff',
            bgSoft: '#f9fafb'
          }
        }
      }
    };
  </script>

  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
  </style>
</head>
<body class="bg-bgSoft text-ink min-h-screen">

  <!-- Top Navigation -->
  <header class="sticky top-0 z-30 bg-card/90 backdrop-blur border-b border-yellow-100 shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Left: Logo + Brand -->
        <div class="flex items-center gap-3">
          <a href="guest_dashboard.php" class="flex items-center gap-2">
            <img src="img/Logo.png" alt="TravelEase Logo" class="h-9 w-auto">
            <span class="hidden sm:inline-block font-extrabold text-lg tracking-tight text-ink">
              TravelEase
            </span>
          </a>
        </div>

        <!-- Center: Navigation links (desktop) -->
        <div class="hidden md:flex items-center gap-6 text-sm font-medium">
          <a href="#overview" class="text-inkMuted hover:text-ink transition-colors">Overview</a>
          <a href="#destinations" class="text-inkMuted hover:text-ink transition-colors">Destinations</a>
          <a href="#map" class="text-inkMuted hover:text-ink transition-colors">Asia Map</a>
          <a href="#packages" class="text-inkMuted hover:text-ink transition-colors">Packages</a>
          <a href="#highlights" class="text-inkMuted hover:text-ink transition-colors">Highlights</a>
        </div>

        <!-- Right: CTA buttons (desktop) -->
        <div class="hidden md:flex items-center gap-3">
          <a href="login.php"
             class="text-xs sm:text-sm font-medium text-inkMuted hover:text-ink transition-colors">
            Sign In
          </a>
          <a href="create_account.php"
             class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-primary text-xs sm:text-sm font-semibold text-ink hover:bg-primaryDark hover:text-white transition shadow-sm">
            Create Account
          </a>
        </div>

        <!-- Mobile menu button -->
        <button id="mobile-menu-button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-ink hover:bg-primarySoft focus:outline-none">
          <span class="sr-only">Open main menu</span>
          <svg class="h-6 w-6" id="icon-menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg class="h-6 w-6 hidden" id="icon-close" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Mobile menu -->
      <div id="mobile-menu" class="md:hidden hidden pb-4 border-t border-yellow-100">
        <div class="pt-3 flex flex-col gap-2 text-sm font-medium">
          <a href="#overview" class="px-2 py-1.5 rounded-md text-inkMuted hover:bg-primarySoft hover:text-ink">Overview</a>
          <a href="#destinations" class="px-2 py-1.5 rounded-md text-inkMuted hover:bg-primarySoft hover:text-ink">Destinations</a>
          <a href="#map" class="px-2 py-1.5 rounded-md text-inkMuted hover:bg-primarySoft hover:text-ink">Asia Map</a>
          <a href="#packages" class="px-2 py-1.5 rounded-md text-inkMuted hover:bg-primarySoft hover:text-ink">Packages</a>
          <a href="#highlights" class="px-2 py-1.5 rounded-md text-inkMuted hover:bg-primarySoft hover:text-ink">Highlights</a>
          <div class="mt-2 flex flex-col gap-2">
            <a href="login.php"
               class="w-full px-3 py-2 rounded-full border border-primary bg-card text-xs font-semibold text-ink hover:bg-primarySoft transition text-center">
              Sign In
            </a>
            <a href="create_account.php"
               class="w-full px-3 py-2 rounded-full bg-primary text-xs font-semibold text-ink hover:bg-primaryDark hover:text-white transition shadow-sm text-center">
              Create Account
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10 space-y-8">

    <!-- Top section: Welcome + Quick search -->
    <section id="overview" class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1.2fr)] items-start">
      <!-- Welcome / Hero -->
      <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-5 sm:p-7 lg:p-8 relative overflow-hidden">
        <div class="absolute -right-20 -top-24 h-56 w-56 rounded-full bg-primarySoft opacity-70 blur-3xl pointer-events-none"></div>
        <div class="relative space-y-4">
          <div class="inline-flex items-center gap-2 rounded-full bg-primarySoft/80 px-3 py-1 text-xs font-medium text-ink border border-yellow-200">
            <span class="inline-block h-1.5 w-1.5 rounded-full bg-primaryDark"></span>
            TravelEase • Guest Dashboard
          </div>
          <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-ink">
              Explore all of Asia with TravelEase.
            </h1>
            <p class="mt-2 text-sm sm:text-base text-inkMuted max-w-2xl">
              Browse curated trips across <span class="font-semibold text-ink">South, East, Southeast, Central &amp; West Asia</span>.
              Sign in or create an account to save favorites and complete your bookings.
            </p>
          </div>

          <!-- Search + Filters -->
          <div class="mt-4 space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
              <div class="flex-1">
                <label class="block text-xs font-semibold text-inkMuted mb-1">Where to?</label>
                <div class="flex items-center gap-2 rounded-2xl border border-yellow-200 bg-white px-3 py-2.5">
                  <svg class="h-4 w-4 text-inkMuted" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                  </svg>
                  <input
                    type="text"
                    placeholder="Search Asian country, city or landmark"
                    class="w-full border-none focus:outline-none focus:ring-0 text-sm placeholder:text-inkMuted bg-transparent"
                  />
                </div>
              </div>
              <div class="flex gap-3">
                <div class="flex-1">
                  <label class="block text-xs font-semibold text-inkMuted mb-1">Travel month</label>
                  <select class="w-full rounded-2xl border border-yellow-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>Anytime</option>
                    <option>January - March</option>
                    <option>April - June</option>
                    <option>July - September</option>
                    <option>October - December</option>
                  </select>
                </div>
                <div class="flex-1">
                  <label class="block text-xs font-semibold text-inkMuted mb-1">Trip type</label>
                  <select class="w-full rounded-2xl border border-yellow-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option>Any</option>
                    <option>City escape</option>
                    <option>Nature &amp; adventure</option>
                    <option>Beach &amp; islands</option>
                    <option>Cultural</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="flex flex-wrap gap-2 text-xs">
              <button class="px-3 py-1.5 rounded-full bg-primary text-ink font-semibold shadow-sm hover:bg-primaryDark hover:text-white transition">
                Explore all Asia
              </button>
              <button class="px-3 py-1.5 rounded-full border border-yellow-200 bg-white text-inkMuted hover:text-ink hover:bg-primarySoft transition">
                South Asia
              </button>
              <button class="px-3 py-1.5 rounded-full border border-yellow-200 bg-white text-inkMuted hover:text-ink hover:bg-primarySoft transition">
                East Asia
              </button>
              <button class="px-3 py-1.5 rounded-full border border-yellow-200 bg-white text-inkMuted hover:text-ink hover:bg-primarySoft transition">
                Southeast Asia
              </button>
              <button class="px-3 py-1.5 rounded-full border border-yellow-200 bg-white text-inkMuted hover:text-ink hover:bg-primarySoft transition">
                Central &amp; West Asia
              </button>
            </div>
          </div>

          <!-- Sign-in info -->
          <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-2 text-xs text-inkMuted">
            <span class="font-semibold text-ink">Tip:</span>
            <span>Create a free TravelEase account to save itineraries, compare prices, and continue from any device.</span>
          </div>

          <div class="mt-3 flex flex-wrap gap-2 text-xs">
            <a href="create_account.php"
               class="px-3 py-2 rounded-full bg-primary text-ink font-semibold hover:bg-primaryDark hover:text-white transition">
              Sign up to book
            </a>
            <a href="login.php"
               class="px-3 py-2 rounded-full border border-yellow-200 bg-white text-inkMuted hover:bg-primarySoft hover:text-ink transition">
              I already have an account
            </a>
          </div>
        </div>
      </div>

      <!-- Right: Key metrics / teaser -->
      <div class="space-y-4">
        <!-- Stats card -->
        <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-4 sm:p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-ink">TravelEase at a glance</h2>
            <span class="text-[11px] px-2 py-1 rounded-full bg-primarySoft text-inkMuted border border-yellow-200">
              Guest view
            </span>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div class="rounded-2xl bg-primarySoft/60 px-3 py-2.5">
              <p class="text-[11px] text-inkMuted">Asian countries</p>
              <p class="text-lg font-bold text-ink">20+</p>
              <p class="mt-1 text-[10px] text-inkMuted">Across full Asia</p>
            </div>
            <div class="rounded-2xl bg-white px-3 py-2.5 border border-yellow-100">
              <p class="text-[11px] text-inkMuted">Verified tours</p>
              <p class="text-lg font-bold text-ink">120+</p>
              <p class="mt-1 text-[10px] text-inkMuted">Curated itineraries</p>
            </div>
            <div class="rounded-2xl bg-white px-3 py-2.5 border border-yellow-100">
              <p class="text-[11px] text-inkMuted">Guest rating</p>
              <p class="text-lg font-bold text-ink">4.8</p>
              <p class="mt-1 text-[10px] text-inkMuted">average / 5.0</p>
            </div>
          </div>
        </div>

        <!-- Call to action card -->
        <div class="bg-ink text-white rounded-3xl p-4 sm:p-5 flex flex-col gap-3 relative overflow-hidden">
          <div class="absolute -right-10 -bottom-10 h-32 w-32 rounded-full bg-primary opacity-40 blur-2xl pointer-events-none"></div>
          <h3 class="text-sm sm:text-base font-semibold">Start planning with TravelEase</h3>
          <p class="text-xs sm:text-sm text-gray-200">
            Create a free TravelEase account to unlock live pricing, personalized suggestions, and 1-on-1 Asia travel assistance.
          </p>
          <div class="flex flex-wrap gap-2 mt-1">
            <a href="create_account.php"
               class="px-3 py-2 rounded-full bg-primary text-ink text-xs font-semibold hover:bg-primaryDark hover:text-white transition">
              Create Account
            </a>
            <a href="login.php"
               class="px-3 py-2 rounded-full border border-white/30 text-xs font-semibold hover:bg-white/10 transition">
              Sign In
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular regions -->
    <section id="destinations" class="space-y-4">
      <div class="flex items-center justify-between gap-2">
        <h2 class="text-lg sm:text-xl font-bold text-ink">Popular regions across Asia</h2>
        <button class="text-xs sm:text-sm font-medium text-inkMuted hover:text-ink">
          View all destinations →
        </button>
      </div>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Region cards -->
        <article class="group bg-card rounded-3xl border border-yellow-100 shadow-sm overflow-hidden flex flex-col">
          <div class="h-24 sm:h-28 bg-gradient-to-tr from-primarySoft to-white flex items-center justify-center">
            <span class="text-xs font-semibold uppercase tracking-wide text-ink/70">
              South Asia
            </span>
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-sm font-semibold text-ink group-hover:text-primaryDark transition">
              India, Sri Lanka, Maldives &amp; more
            </h3>
            <p class="mt-1 text-xs text-inkMuted flex-1">
              Iconic culture, spices, beaches, and ancient cities.
            </p>
            <p class="mt-3 text-[11px] text-ink font-semibold">
              From $420 • 5–10 days
            </p>
          </div>
        </article>

        <article class="group bg-card rounded-3xl border border-yellow-100 shadow-sm overflow-hidden flex flex-col">
          <div class="h-24 sm:h-28 bg-gradient-to-tr from-primarySoft to-white flex items-center justify-center">
            <span class="text-xs font-semibold uppercase tracking-wide text-ink/70">
              East Asia
            </span>
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-sm font-semibold text-ink group-hover:text-primaryDark transition">
              Japan, China, South Korea &amp; more
            </h3>
            <p class="mt-1 text-xs text-inkMuted flex-1">
              Neon skylines, ancient temples, and modern cuisine.
            </p>
            <p class="mt-3 text-[11px] text-ink font-semibold">
              From $650 • 6–12 days
            </p>
          </div>
        </article>

        <article class="group bg-card rounded-3xl border border-yellow-100 shadow-sm overflow-hidden flex flex-col">
          <div class="h-24 sm:h-28 bg-gradient-to-tr from-primarySoft to-white flex items-center justify-center">
            <span class="text-xs font-semibold uppercase tracking-wide text-ink/70">
              Southeast Asia
            </span>
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-sm font-semibold text-ink group-hover:text-primaryDark transition">
              Thailand, Vietnam, Singapore &amp; more
            </h3>
            <p class="mt-1 text-xs text-inkMuted flex-1">
              Tropical islands, street food, and vibrant nightlife.
            </p>
            <p class="mt-3 text-[11px] text-ink font-semibold">
              From $380 • 4–9 days
            </p>
          </div>
        </article>

        <article class="group bg-card rounded-3xl border border-yellow-100 shadow-sm overflow-hidden flex flex-col">
          <div class="h-24 sm:h-28 bg-gradient-to-tr from-primarySoft to-white flex items-center justify-center">
            <span class="text-xs font-semibold uppercase tracking-wide text-ink/70">
              Central &amp; West Asia
            </span>
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-sm font-semibold text-ink group-hover:text-primaryDark transition">
              UAE, Turkey, Uzbekistan &amp; more
            </h3>
            <p class="mt-1 text-xs text-inkMuted flex-1">
              Desert adventures, bazaars, and silk road heritage.
            </p>
            <p class="mt-3 text-[11px] text-ink font-semibold">
              From $520 • 5–11 days
            </p>
          </div>
        </article>
      </div>
    </section>

    <!-- Asia Map Section -->
    <section id="map" class="space-y-4">
      <div class="flex items-center justify-between gap-2">
        <h2 class="text-lg sm:text-xl font-bold text-ink">Interactive Asia Map</h2>
        <span class="text-xs sm:text-sm text-inkMuted">
          Explore Asia and zoom into your target region
        </span>
      </div>

      <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-3 sm:p-4">
        <div class="aspect-[16/9] w-full rounded-2xl overflow-hidden border border-yellow-100">
          <!-- OpenStreetMap iframe centered on Asia -->
          <iframe
            title="Asia Map"
            class="w-full h-full"
            style="border:0;"
            loading="lazy"
            src="https://www.openstreetmap.org/export/embed.html?bbox=19.0,0.0,150.0,60.0&layer=mapnik">
          </iframe>
        </div>
        <p class="mt-2 text-xs text-inkMuted">
          Tip: use the map controls to zoom and pan around Asia. Later you can add TravelEase destinations as markers using JavaScript.
        </p>
      </div>
    </section>

    <!-- Recommended packages + highlights -->
    <section id="packages" class="grid gap-6 lg:grid-cols-[minmax(0,2.1fr)_minmax(0,1.3fr)]">
      <!-- Recommended packages -->
      <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-4 sm:p-5 lg:p-6">
        <div class="flex items-center justify-between gap-2 mb-4">
          <h2 class="text-lg font-bold text-ink">TravelEase picks for first-time Asia trips</h2>
          <button class="text-xs sm:text-sm font-medium text-inkMuted hover:text-ink">
            See all plans
          </button>
        </div>

        <div class="space-y-3">
          <!-- Package item -->
          <article class="flex flex-col sm:flex-row gap-3 sm:items-center rounded-2xl border border-yellow-100 bg-white px-3 py-3 hover:border-primaryDark hover:shadow-sm transition">
            <div class="flex-1">
              <h3 class="text-sm font-semibold text-ink">
                Classic South Asia Circuit
              </h3>
              <p class="mt-1 text-xs text-inkMuted">
                Delhi → Agra → Jaipur → Colombo → Maldives. Ideal for 10–14 days.
              </p>
              <p class="mt-1 text-[11px] text-inkMuted">
                Culture • Beaches • Iconic landmarks
              </p>
            </div>
            <div class="flex items-end sm:items-center gap-3 justify-between sm:justify-end">
              <div class="text-right">
                <p class="text-xs text-inkMuted">From</p>
                <p class="text-sm font-bold text-ink">$1,150</p>
                <p class="text-[11px] text-inkMuted">per person</p>
              </div>
              <button class="px-3 py-1.5 rounded-full bg-primary text-[11px] font-semibold text-ink hover:bg-primaryDark hover:text-white transition">
                View details
              </button>
            </div>
          </article>

          <article class="flex flex-col sm:flex-row gap-3 sm:items-center rounded-2xl border border-yellow-100 bg-white px-3 py-3 hover:border-primaryDark hover:shadow-sm transition">
            <div class="flex-1">
              <h3 class="text-sm font-semibold text-ink">
                Taste of East Asia
              </h3>
              <p class="mt-1 text-xs text-inkMuted">
                Tokyo → Kyoto → Seoul → Shanghai. Perfect for 8–12 days.
              </p>
              <p class="mt-1 text-[11px] text-inkMuted">
                Food • City lights • Traditions
              </p>
            </div>
            <div class="flex items-end sm:items-center gap-3 justify-between sm:justify-end">
              <div class="text-right">
                <p class="text-xs text-inkMuted">From</p>
                <p class="text-sm font-bold text-ink">$1,450</p>
                <p class="text-[11px] text-inkMuted">per person</p>
              </div>
              <button class="px-3 py-1.5 rounded-full bg-primary text-[11px] font-semibold text-ink hover:bg-primaryDark hover:text-white transition">
                View details
              </button>
            </div>
          </article>

          <article class="flex flex-col sm:flex-row gap-3 sm:items-center rounded-2xl border border-yellow-100 bg-white px-3 py-3 hover:border-primaryDark hover:shadow-sm transition">
            <div class="flex-1">
              <h3 class="text-sm font-semibold text-ink">
                Island Hopping Escape
              </h3>
              <p class="mt-1 text-xs text-inkMuted">
                Phuket → Bali → Langkawi → Maldives. Relaxed 7–10 day trip.
              </p>
              <p class="mt-1 text-[11px] text-inkMuted">
                Beaches • Resorts • Honeymoons
              </p>
            </div>
            <div class="flex items-end sm:items-center gap-3 justify-between sm:justify-end">
              <div class="text-right">
                <p class="text-xs text-inkMuted">From</p>
                <p class="text-sm font-bold text-ink">$980</p>
                <p class="text-[11px] text-inkMuted">per person</p>
              </div>
              <button class="px-3 py-1.5 rounded-full bg-primary text-[11px] font-semibold text-ink hover:bg-primaryDark hover:text-white transition">
                View details
              </button>
            </div>
          </article>
        </div>
      </div>

      <!-- Highlights / info sidebar -->
      <aside id="highlights" class="space-y-4">
        <!-- Upcoming events -->
        <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-4 sm:p-5">
          <h2 class="text-sm font-semibold text-ink mb-3">Seasonal highlights in Asia</h2>
          <ul class="space-y-2.5 text-xs">
            <li class="flex items-start justify-between gap-3">
              <div>
                <p class="font-semibold text-ink">Cherry blossom season</p>
                <p class="text-inkMuted">Japan • late March – early April</p>
              </div>
              <span class="px-2 py-1 rounded-full bg-primarySoft text-[11px] text-ink">High demand</span>
            </li>
            <li class="flex items-start justify-between gap-3">
              <div>
                <p class="font-semibold text-ink">Songkran Festival</p>
                <p class="text-inkMuted">Thailand • mid April</p>
              </div>
              <span class="px-2 py-1 rounded-full bg-primarySoft text-[11px] text-ink">Cultural</span>
            </li>
            <li class="flex items-start justify-between gap-3">
              <div>
                <p class="font-semibold text-ink">Winter in the Himalayas</p>
                <p class="text-inkMuted">Nepal &amp; India • Nov – Feb</p>
              </div>
              <span class="px-2 py-1 rounded-full bg-primarySoft text-[11px] text-ink">Adventure</span>
            </li>
          </ul>
        </div>

        <!-- Travel tips -->
        <div class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-4 sm:p-5">
          <h2 class="text-sm font-semibold text-ink mb-3">TravelEase planning checklist</h2>
          <ul class="space-y-2 text-xs text-inkMuted">
            <li class="flex items-center gap-2">
              <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-primarySoft text-[10px] font-bold text-ink">1</span>
              Check visa &amp; entry requirements for each Asian country.
            </li>
            <li class="flex items-center gap-2">
              <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-primarySoft text-[10px] font-bold text-ink">2</span>
              Compare weather and seasons across your target regions.
            </li>
            <li class="flex items-center gap-2">
              <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-primarySoft text-[10px] font-bold text-ink">3</span>
              Plan internal flights &amp; trains between Asian cities.
            </li>
          </ul>
          <button class="mt-3 w-full px-3 py-2 rounded-2xl border border-yellow-200 bg-white text-[11px] font-semibold text-ink hover:bg-primarySoft transition">
            Talk to a TravelEase specialist (after sign in)
          </button>
        </div>
      </aside>
    </section>

    <!-- Why TravelEase -->
    <section class="bg-card rounded-3xl border border-yellow-100 shadow-sm p-4 sm:p-5 lg:p-6">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <h2 class="text-lg font-bold text-ink">Why travelers choose TravelEase</h2>
          <p class="mt-1 text-xs sm:text-sm text-inkMuted max-w-xl">
            Transparent pricing, verified local partners across Asia, and support from the moment you start planning until you return home.
          </p>
        </div>
        <div class="grid grid-cols-3 gap-3 text-center text-xs">
          <div class="rounded-2xl bg-primarySoft px-3 py-2.5">
            <p class="text-[11px] text-inkMuted">Trusted bookings</p>
            <p class="text-base font-bold text-ink">15k+</p>
          </div>
          <div class="rounded-2xl bg-white border border-yellow-100 px-3 py-2.5">
            <p class="text-[11px] text-inkMuted">24/7 support</p>
            <p class="text-base font-bold text-ink">Across 6 timezones</p>
          </div>
          <div class="rounded-2xl bg-white border border-yellow-100 px-3 py-2.5">
            <p class="text-[11px] text-inkMuted">Secure payments</p>
            <p class="text-base font-bold text-ink">Multi-currency</p>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="border-t border-yellow-100 bg-card mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-inkMuted">
      <p>© <?php echo date('Y'); ?> TravelEase. All rights reserved.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-ink">Terms</a>
        <a href="#" class="hover:text-ink">Privacy</a>
        <a href="#" class="hover:text-ink">Contact</a>
      </div>
    </div>
  </footer>

  <!-- Minimal JS: mobile menu toggle -->
  <script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconMenu = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    menuButton.addEventListener('click', () => {
      const isOpen = !mobileMenu.classList.contains('hidden');
      if (isOpen) {
        mobileMenu.classList.add('hidden');
        iconMenu.classList.remove('hidden');
        iconClose.classList.add('hidden');
      } else {
        mobileMenu.classList.remove('hidden');
        iconMenu.classList.add('hidden');
        iconClose.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
