<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نرم‌افزار سجیل - مدیریت مالی و متنی</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
</body>
</html>
