<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نرم‌افزار سجیل - مدیریت مالی و متنی</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#16213e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="سجیل">
    <link rel="apple-touch-icon" href="{{ asset('icon.svg') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Dynamic Theme Management */
        :root {
            --bg-primary: #1a1a2e;
            --bg-secondary: #16213e;
            --bg-tertiary: #0f0f23;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-tertiary: rgba(255, 255, 255, 0.6);
        }
        
        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #e2e8f0;
            --text-primary: #1a202c;
            --text-secondary: rgba(26, 32, 44, 0.8);
            --text-tertiary: rgba(26, 32, 44, 0.6);
        }
        
        [data-theme="dark"] {
            --bg-primary: #1a1a2e;
            --bg-secondary: #16213e;
            --bg-tertiary: #0f0f23;
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-tertiary: rgba(255, 255, 255, 0.6);
        }
        
        /* Glass effect with theme support */
        .glass {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1), 
                rgba(255, 255, 255, 0.05)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="light"] .glass {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.8), 
                rgba(255, 255, 255, 0.6)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .glass-card {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1), 
                rgba(255, 255, 255, 0.05)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="light"] .glass-card {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.8), 
                rgba(255, 255, 255, 0.6)
            );
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        /* Theme-aware text colors */
        [data-theme="light"] .text-white {
            color: var(--text-primary) !important;
        }
        
        [data-theme="light"] .text-white\/80 {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="light"] .text-white\/60 {
            color: var(--text-tertiary) !important;
        }
        
        /* Theme-aware background */
        body {
            background: var(--bg-primary);
            transition: background-color 0.3s ease;
        }
        
        /* Theme-aware input styling */
        [data-theme="light"] input,
        [data-theme="light"] textarea,
        [data-theme="light"] select {
            background: rgba(255, 255, 255, 0.8) !important;
            color: var(--text-primary) !important;
            border-color: rgba(0, 0, 0, 0.2) !important;
        }
        
        [data-theme="light"] input::placeholder,
        [data-theme="light"] textarea::placeholder {
            color: var(--text-tertiary) !important;
        }
        
        /* Theme-aware button styling */
        [data-theme="light"] .bg-white\/20 {
            background: rgba(0, 0, 0, 0.1) !important;
        }
        
        [data-theme="light"] .hover\:bg-white\/30:hover {
            background: rgba(0, 0, 0, 0.2) !important;
        }
        
        /* Theme transition animations */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Main Content -->
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Login Form -->
        <div id="login-section" class="flex-1 flex items-center justify-center p-4">
            <div class="glass-card rounded-3xl p-8 w-full max-w-sm">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-white/30 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-wallet text-3xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">سجیل</h2>
                    <p class="text-white/80 text-sm">مدیریت مالی و متنی</p>
                </div>
                <form id="login-form" class="space-y-4">
                    <div>
                        <input type="text" id="username" placeholder="نام کاربری" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                    </div>
                    <div>
                        <input type="password" id="password" placeholder="رمز عبور" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                    </div>
                    <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                        ورود
                    </button>
                </form>
            </div>
        </div>

        <!-- Dashboard -->
        <div id="dashboard" class="hidden flex-1 flex flex-col">
            <!-- Header -->
            <div class="glass fixed top-0 left-0 right-0 p-4 z-40">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-white">سجیل</h1>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <!-- Dark Mode Toggle -->
                        <div class="relative">
                            <button id="theme-toggle" class="text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-all duration-200">
                                <i id="theme-icon" class="fas fa-sun text-lg"></i>
                            </button>
                            <!-- Theme Menu -->
                            <div id="theme-menu" class="hidden absolute left-0 top-full mt-2 rounded-lg shadow-lg p-2 min-w-32 z-50">
                                <button class="theme-option w-full text-right px-3 py-2 text-white hover:bg-white/20 rounded-lg text-sm transition-all duration-200 flex items-center" data-theme="light">
                                    <i class="fas fa-sun ml-2"></i>روز
                                </button>
                                <button class="theme-option w-full text-right px-3 py-2 text-white hover:bg-white/20 rounded-lg text-sm transition-all duration-200 flex items-center" data-theme="dark">
                                    <i class="fas fa-moon ml-2"></i>شب
                                </button>
                                <button class="theme-option w-full text-right px-3 py-2 text-white hover:bg-white/20 rounded-lg text-sm transition-all duration-200 flex items-center" data-theme="system">
                                    <i class="fas fa-desktop ml-2"></i>سیستم
                                </button>
                            </div>
                        </div>
                        <button id="logout-btn" class="text-white/80 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 p-custom-main-content">

                <!-- Financial Section -->
                <div id="financial-section" class="tab-content">
                    <!-- Add Button -->
                    <div class="mb-6">
                        <button id="add-financial-btn" class="w-full glass-card rounded-2xl p-4 text-white hover:bg-white/20 transition-all duration-200">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-plus text-2xl ml-3"></i>
                                <span class="font-medium">افزودن هزینه</span>
                            </div>
                        </button>
                    </div>

                    <!-- Filter Button -->
                    <div class="mb-6">
                        <button id="filter-btn" class="glass-card rounded-2xl p-4 text-white hover:bg-white/20 transition-all duration-200 w-full">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-filter text-2xl ml-3"></i>
                                <span class="font-medium">فیلتر و جستجو</span>
                            </div>
                        </button>
                    </div>

                    <!-- Financial Records List -->
                    <div id="financial-list" class="space-y-3">
                        <!-- Financial records will be loaded here -->
                    </div>
                    
                    <!-- Load More Button -->
                    <div class="mt-6 text-center">
                        <button id="load-more-btn" class="glass-card rounded-2xl p-4 text-white hover:bg-white/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-chevron-down text-2xl ml-3"></i>
                                <span class="font-medium">بارگذاری بیشتر</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Text Section -->
                <div id="text-section" class="tab-content hidden">
                    <!-- Add Button -->
                    <div class="mb-6">
                        <button id="add-text-btn" class="w-full glass-card rounded-2xl p-4 text-white hover:bg-white/20 transition-all duration-200">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-plus text-2xl ml-3"></i>
                                <span class="font-medium">افزودن یادداشت</span>
                            </div>
                        </button>
                    </div>

                    <!-- Text Records List -->
                    <div id="text-list" class="space-y-3">
                        <!-- Text records will be loaded here -->
                    </div>
                </div>

                <!-- Reports Section -->
                <div id="reports-section" class="tab-content hidden">
                    <div id="reports-content">
                        <!-- Reports will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="glass fixed bottom-0 left-0 right-0 p-4">
                <div class="flex justify-around">
                    <button class="tab-btn active flex flex-col items-center py-2 px-4 text-white" data-tab="financial">
                        <i class="fas fa-money-bill-wave text-xl mb-1"></i>
                    </button>
                    <button class="tab-btn flex flex-col items-center py-2 px-4 text-white/60" data-tab="text">
                        <i class="fas fa-file-text text-xl mb-1"></i>
                    </button>
                    <button class="tab-btn flex flex-col items-center py-2 px-4 text-white/60" data-tab="reports">
                        <i class="fas fa-chart-bar text-xl mb-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Modal -->
    <div id="financial-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="glass-card rounded-3xl p-6 w-full max-w-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">افزودن هزینه</h3>
                <button id="close-financial-modal" class="text-white/60 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="financial-form" class="space-y-4">
                <div>
                    <input type="text" id="financial-title" placeholder="عنوان" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                </div>
                <div>
                    <input type="text" id="financial-amount" placeholder="مبلغ (تومان)" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                </div>
                <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                    افزودن
                </button>
            </form>
        </div>
    </div>

    <!-- Text Modal -->
    <div id="text-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="glass-card rounded-3xl p-6 w-full max-w-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">افزودن یادداشت</h3>
                <button id="close-text-modal" class="text-white/60 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="text-form" class="space-y-4">
                <div>
                    <input type="text" id="text-title" placeholder="عنوان" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                </div>
                <div>
                    <textarea id="text-description" placeholder="توضیحات" rows="4" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 resize-none" required></textarea>
                </div>
                <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                    افزودن
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filter-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="glass-card rounded-3xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">فیلتر و جستجو</h3>
                <button id="close-filter-modal" class="text-white/60 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="filter-form" class="space-y-4">
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">جستجو بر اساس عنوان</label>
                    <select id="title-filter" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                        <option value="">همه عناوین</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">فیلتر بر اساس تاریخ</label>
                    <input type="date" id="date-filter" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                </div>
                
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">مرتب‌سازی مبلغ</label>
                    <select id="amount-sort" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                        <option value="">بدون مرتب‌سازی</option>
                        <option value="highest">بیشترین هزینه</option>
                        <option value="lowest">کم‌ترین هزینه</option>
                    </select>
                </div>
                
                <div class="flex space-x-3 space-x-reverse">
                    <button type="button" id="clear-filters" class="flex-1 bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                        <i class="fas fa-times ml-2"></i>پاک کردن
                    </button>
                    <button type="submit" class="flex-1 bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                        <i class="fas fa-search ml-2"></i>جستجو
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center text-white">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white ml-3"></div>
                <span>در حال بارگذاری...</span>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Register service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed');
                    });
            });
        }

        // Dynamic theme management for PWA
        function updateManifestTheme(theme) {
            const manifestLink = document.querySelector('link[rel="manifest"]');
            if (manifestLink) {
                // Update theme-color meta tag
                const themeColorMeta = document.querySelector('meta[name="theme-color"]');
                if (themeColorMeta) {
                    themeColorMeta.content = theme === 'dark' ? '#1a1a2e' : '#16213e';
                }
                
                // Update background color for splash screen
                const backgroundColorMeta = document.querySelector('meta[name="background-color"]');
                if (!backgroundColorMeta) {
                    const meta = document.createElement('meta');
                    meta.name = 'background-color';
                    meta.content = theme === 'dark' ? '#0f0f23' : '#1a1a2e';
                    document.head.appendChild(meta);
                } else {
                    backgroundColorMeta.content = theme === 'dark' ? '#0f0f23' : '#1a1a2e';
                }
            }
        }

        // Listen for system theme changes
        if (window.matchMedia) {
            const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Set initial theme
            updateManifestTheme(darkModeQuery.matches ? 'dark' : 'light');
            
            // Listen for changes
            darkModeQuery.addEventListener('change', function(e) {
                updateManifestTheme(e.matches ? 'dark' : 'light');
            });
        }

        // Listen for custom theme changes from the app
        window.addEventListener('themeChanged', function(e) {
            updateManifestTheme(e.detail.theme);
        });
    </script>
</body>
</html>
