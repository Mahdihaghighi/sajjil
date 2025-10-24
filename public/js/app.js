// Global variables
let authToken = localStorage.getItem('auth_token');
let currentUser = null;
let currentTab = 'financial';
let currentTheme = localStorage.getItem('theme') || 'system';

// API Base URL
const API_BASE = '/api';

// Get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    if (authToken) {
        checkAuth();
    } else {
        showLogin();
    }
    
    setupEventListeners();
    initializeTheme();
});

// Setup event listeners after DOM is loaded
function setupEventListeners() {
    // Login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    // Logout button
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }
    
    // Modal buttons
    const addFinancialBtn = document.getElementById('add-financial-btn');
    if (addFinancialBtn) {
        addFinancialBtn.addEventListener('click', () => {
            document.getElementById('financial-modal').classList.remove('hidden');
        });
    }
    
    const addTextBtn = document.getElementById('add-text-btn');
    if (addTextBtn) {
        addTextBtn.addEventListener('click', () => {
            document.getElementById('text-modal').classList.remove('hidden');
        });
    }
    
    // Close modal buttons
    const closeFinancialModal = document.getElementById('close-financial-modal');
    if (closeFinancialModal) {
        closeFinancialModal.addEventListener('click', () => {
            document.getElementById('financial-modal').classList.add('hidden');
        });
    }
    
    const closeTextModal = document.getElementById('close-text-modal');
    if (closeTextModal) {
        closeTextModal.addEventListener('click', () => {
            document.getElementById('text-modal').classList.add('hidden');
        });
    }
    
    // Close modals when clicking outside
    const financialModal = document.getElementById('financial-modal');
    if (financialModal) {
        financialModal.addEventListener('click', (e) => {
            if (e.target.id === 'financial-modal') {
                document.getElementById('financial-modal').classList.add('hidden');
            }
        });
    }
    
    const textModal = document.getElementById('text-modal');
    if (textModal) {
        textModal.addEventListener('click', (e) => {
            if (e.target.id === 'text-modal') {
                document.getElementById('text-modal').classList.add('hidden');
            }
        });
    }
    
    // Financial form
    const financialForm = document.getElementById('financial-form');
    if (financialForm) {
        financialForm.addEventListener('submit', handleFinancialSubmit);
    }
    
    // Text form
    const textForm = document.getElementById('text-form');
    if (textForm) {
        textForm.addEventListener('submit', handleTextSubmit);
    }
    
    // Title suggestion for financial title
    const financialTitle = document.getElementById('financial-title');
    if (financialTitle) {
        financialTitle.addEventListener('input', (e) => {
            if (e.target.value.length >= 2) {
                getTitleSuggestions(e.target.value, 'financial', e.target);
            } else {
                hideSuggestions(e.target);
            }
        });
    }
    
    // Live formatting for financial amount
    const financialAmount = document.getElementById('financial-amount');
    if (financialAmount) {
        financialAmount.addEventListener('blur', (e) => {
            formatAmountInput(e.target);
        });
        
        financialAmount.addEventListener('input', (e) => {
            // Allow typing without immediate formatting
            let value = e.target.value;
            
            // Convert Persian numbers to English numbers
            let englishValue = value.replace(/[۰-۹]/g, function(d) {
                return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
            });
            
            // Only remove non-numeric characters except decimal point
            let cleanValue = englishValue.replace(/[^\d.]/g, '');
            
            // Ensure only one decimal point
            const parts = cleanValue.split('.');
            if (parts.length > 2) {
                cleanValue = parts[0] + '.' + parts.slice(1).join('');
            }
            
            // Only update if the cleaned value is different
            if (cleanValue !== englishValue) {
                e.target.value = cleanValue;
            }
        });
    }
    
    // Title suggestion for text title
    const textTitle = document.getElementById('text-title');
    if (textTitle) {
        textTitle.addEventListener('input', (e) => {
            if (e.target.value.length >= 2) {
                getTitleSuggestions(e.target.value, 'text', e.target);
            } else {
                hideSuggestions(e.target);
            }
        });
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.suggestion-container')) {
            hideAllSuggestions();
        }
    });
    
    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const themeMenu = document.getElementById('theme-menu');
            if (themeMenu) {
                themeMenu.classList.toggle('hidden');
            }
        });
    }
    
    // Theme options
    document.querySelectorAll('.theme-option').forEach(option => {
        option.addEventListener('click', (e) => {
            const theme = e.target.dataset.theme;
            setTheme(theme);
            const themeMenu = document.getElementById('theme-menu');
            if (themeMenu) {
                themeMenu.classList.add('hidden');
            }
        });
    });
    
    // Close theme menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#theme-toggle') && !e.target.closest('#theme-menu')) {
            const themeMenu = document.getElementById('theme-menu');
            if (themeMenu) {
                themeMenu.classList.add('hidden');
            }
        }
    });
    
    // Filter modal
    const filterBtn = document.getElementById('filter-btn');
    const filterModal = document.getElementById('filter-modal');
    const closeFilterModal = document.getElementById('close-filter-modal');
    const filterForm = document.getElementById('filter-form');
    
    if (filterBtn) {
        filterBtn.addEventListener('click', () => {
            filterModal.classList.remove('hidden');
        });
    }
    
    if (closeFilterModal) {
        closeFilterModal.addEventListener('click', () => {
            filterModal.classList.add('hidden');
        });
    }
    
    if (filterForm) {
        filterForm.addEventListener('submit', handleFilterSubmit);
    }
    
    // Close filter modal when clicking outside
    if (filterModal) {
        filterModal.addEventListener('click', (e) => {
            if (e.target.id === 'filter-modal') {
                filterModal.classList.add('hidden');
            }
        });
    }
    
    // Load more button
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMoreRecords);
    }
    
    // Initialize Select2 for filter modal
    initializeSelect2();
}

function initializeSelect2() {
    // Initialize Select2 for title filter with search capability
    const titleFilter = document.getElementById('title-filter');
    if (titleFilter) {
        $(titleFilter).select2({
            placeholder: 'همه عناوین',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            language: {
                noResults: function() {
                    return 'نتیجه‌ای یافت نشد';
                },
                searching: function() {
                    return 'در حال جستجو...';
                },
                inputTooShort: function() {
                    return 'حداقل 1 کاراکتر وارد کنید';
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    }
    
    // Initialize Select2 for amount sort without search
    const amountSort = document.getElementById('amount-sort');
    if (amountSort) {
        $(amountSort).select2({
            placeholder: 'بدون مرتب‌سازی',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            minimumResultsForSearch: Infinity, // Disable search for this select
            language: {
                noResults: function() {
                    return 'نتیجه‌ای یافت نشد';
                }
            }
        });
    }
}


// Authentication functions
async function checkAuth() {
    try {
        const response = await fetch(`${API_BASE}/user`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            currentUser = await response.json();
            showDashboard();
        } else {
            localStorage.removeItem('auth_token');
            showLogin();
        }
    } catch (error) {
        console.error('Auth check failed:', error);
        showLogin();
    }
}

async function handleLogin(e) {
    e.preventDefault();
    showLoading();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch(`${API_BASE}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            authToken = data.token;
            currentUser = data.user;
            localStorage.setItem('auth_token', authToken);
            showDashboard();
        } else {
            alert('خطا در ورود: ' + (data.message || 'نام کاربری یا رمز عبور اشتباه است'));
        }
    } catch (error) {
        console.error('Login failed:', error);
        alert('خطا در اتصال به سرور');
    } finally {
        hideLoading();
    }
}

function handleLogout() {
    localStorage.removeItem('auth_token');
    authToken = null;
    currentUser = null;
    showLogin();
}

// UI Functions
function showLogin() {
    document.getElementById('login-section').classList.remove('hidden');
    document.getElementById('dashboard').classList.add('hidden');
}

function showDashboard() {
    document.getElementById('login-section').classList.add('hidden');
    document.getElementById('dashboard').classList.remove('hidden');
    
    // Setup tab navigation after dashboard is shown
    setupTabNavigation();
    
    // Load initial data
    loadFinancialRecords();
    loadTextRecords();
}

function setupTabNavigation() {
    // Tab navigation
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const tabName = e.target.closest('[data-tab]').dataset.tab;
            if (tabName) {
                switchTab(tabName);
            }
        });
    });
}

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

// Tab Management
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'text-white');
        btn.classList.add('text-white/60');
    });
    
    const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeTab) {
        activeTab.classList.add('active', 'text-white');
        activeTab.classList.remove('text-white/60');
    }
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    const targetSection = document.getElementById(`${tabName}-section`);
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    currentTab = tabName;
    
    // Load data for the tab
    if (tabName === 'reports') {
        loadReports();
    }
}

// Financial Functions
async function handleFinancialSubmit(e) {
    e.preventDefault();
    
    const title = document.getElementById('financial-title').value;
    const amountFormatted = document.getElementById('financial-amount').value;
    // Convert formatted amount back to number (handle Persian numbers)
    // First convert Persian numbers to English, then remove non-numeric characters
    const englishAmount = amountFormatted.replace(/[۰-۹]/g, function(d) {
        return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
    });
    const amount = parseFloat(englishAmount.replace(/[^\d.]/g, ''));
    const type = 'expense'; // همه رکوردها هزینه هستند
    
    // Debug information
    console.log('Form data:', { title, amountFormatted, englishAmount, amount, type });
    
    // Validate required fields
    if (!title.trim()) {
        showNotification('لطفاً عنوان را وارد کنید', 'error');
        return;
    }
    
    if (!amount || isNaN(amount) || amount <= 0) {
        showNotification('لطفاً مبلغ معتبر وارد کنید', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/finance`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, amount, type })
        });
        
        if (response.ok) {
            const data = await response.json();
            document.getElementById('financial-form').reset();
            document.getElementById('financial-modal').classList.add('hidden');
            loadFinancialRecords();
            showNotification('رکورد مالی با موفقیت افزوده شد', 'success');
        } else {
            const errorText = await response.text();
            console.error('API Error:', errorText);
            showNotification('خطا در افزودن رکورد: ' + response.status, 'error');
        }
    } catch (error) {
        console.error('Financial submit failed:', error);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}


function displayFinancialRecords(records) {
    const container = document.getElementById('financial-list');
    
    if (records.length === 0) {
        container.innerHTML = '<div class="glass-card rounded-2xl p-6 text-center"><p class="text-white/70">هیچ رکورد مالی یافت نشد</p></div>';
        return;
    }
    
    const recordsHTML = records.map(record => createFinancialRecordHTML(record)).join('');
    container.innerHTML = recordsHTML;
}

// Text Functions
async function handleTextSubmit(e) {
    e.preventDefault();
    
    const title = document.getElementById('text-title').value;
    const description = document.getElementById('text-description').value;
    
    try {
        const response = await fetch(`${API_BASE}/texts`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, description })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            document.getElementById('text-form').reset();
            document.getElementById('text-modal').classList.add('hidden');
            loadTextRecords();
            showNotification('رکورد متنی با موفقیت افزوده شد', 'success');
        } else {
            showNotification('خطا در افزودن رکورد: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Text submit failed:', error);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}

async function loadTextRecords() {
    try {
        const response = await fetch(`${API_BASE}/texts`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayTextRecords(data.data);
        }
    } catch (error) {
        console.error('Failed to load text records:', error);
    }
}

function displayTextRecords(records) {
    const container = document.getElementById('text-list');
    
    if (records.length === 0) {
        container.innerHTML = '<div class="glass-card rounded-2xl p-6 text-center"><p class="text-white/70">هیچ یادداشت یافت نشد</p></div>';
        return;
    }
    
    container.innerHTML = records.map(record => `
        <div class="glass-card rounded-2xl p-4 text-white" data-text-id="${record.id}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-bold text-lg mb-2">${record.title}</h4>
                    <p class="text-white/80 text-sm mb-3 line-clamp-3">${record.description}</p>
                    <p class="text-white/60 text-xs">${formatDate(record.created_at)}</p>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <button onclick="editTextRecord(${record.id})" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-xl text-sm transition-all duration-200">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteTextRecord(${record.id})" class="bg-red-500/20 hover:bg-red-500/30 text-red-300 px-3 py-2 rounded-xl text-sm transition-all duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Reports Functions
async function loadReports() {
    try {
        const response = await fetch(`${API_BASE}/reports/financial`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayReports(data);
        }
    } catch (error) {
        console.error('Failed to load reports:', error);
    }
}

function displayReports(data) {
    const container = document.getElementById('reports-content');
    
    container.innerHTML = `
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="glass-card rounded-2xl p-6 text-white">
                <h4 class="text-lg font-bold mb-2">کل هزینه</h4>
                <p class="text-3xl font-bold">${formatNumber(data.summary.total_expense)} تومان</p>
            </div>
            <div class="glass-card rounded-2xl p-6 text-white">
                <h4 class="text-lg font-bold mb-2">هزینه 30 روز گذشته</h4>
                <p class="text-3xl font-bold">${formatNumber(data.summary.last_30_days_expense)} تومان</p>
            </div>
            <div class="glass-card rounded-2xl p-6 text-white">
                <h4 class="text-lg font-bold mb-2">تعداد تراکنش</h4>
                <p class="text-3xl font-bold">${data.summary.expense_count}</p>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl p-6 text-white">
            <h4 class="text-lg font-bold mb-4">نمودار روزانه هزینه‌ها</h4>
            <canvas id="dailyChart" width="400" height="200"></canvas>
        </div>
    `;
    
    // Create chart
    createDailyChart(data.chart_data.daily);
}

function createDailyChart(dailyData) {
    const ctx = document.getElementById('dailyChart').getContext('2d');
    const labels = Object.keys(dailyData).sort();
    const expenseData = labels.map(date => dailyData[date].expense);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'هزینه',
                data: expenseData,
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Utility Functions
window.formatNumber = function(num) {
    return new Intl.NumberFormat('fa-IR').format(num);
}

window.formatAmountInput = function(inputElement) {
    let value = inputElement.value;
    
    // Convert Persian numbers to English numbers
    let englishValue = value.replace(/[۰-۹]/g, function(d) {
        return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
    });
    
    // Remove all non-numeric characters except decimal point
    let cleanValue = englishValue.replace(/[^\d.]/g, '');
    
    // Only format if we have a valid number and it's not already formatted
    if (cleanValue && !isNaN(parseFloat(cleanValue))) {
        const number = parseFloat(cleanValue);
        if (number >= 0) {
            // Format the number with thousand separators
            const formatted = new Intl.NumberFormat('fa-IR').format(number);
            // Only update if the formatted value is different
            if (formatted !== value) {
                inputElement.value = formatted;
            }
        }
    }
}

window.formatDate = function(dateString) {
    if (!dateString) return '';
    const safe = typeof dateString === 'string' ? dateString.replace(' ', 'T') : dateString;
    const date = new Date(safe);
    if (isNaN(date)) return '';
    return date.toLocaleString('fa-IR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Theme Functions
function initializeTheme() {
    setTheme(currentTheme);
}

function setTheme(theme) {
    currentTheme = theme;
    localStorage.setItem('theme', theme);
    
    // Remove existing theme classes
    document.body.classList.remove('light', 'dark', 'system');
    
    // Add new theme class
    document.body.setAttribute('data-theme', theme);
    
    // Update theme icon
    updateThemeIcon(theme);
    
    // Update theme options
    updateThemeOptions(theme);
}

function updateThemeIcon(theme) {
    const themeIcon = document.getElementById('theme-icon');
    if (themeIcon) {
        switch (theme) {
            case 'light':
                themeIcon.className = 'fas fa-sun text-lg';
                break;
            case 'dark':
                themeIcon.className = 'fas fa-moon text-lg';
                break;
            case 'system':
                themeIcon.className = 'fas fa-desktop text-lg';
                break;
        }
    }
}

function updateThemeOptions(theme) {
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('active');
        if (option.dataset.theme === theme) {
            option.classList.add('active');
        }
    });
}

// Filter Functions
let currentPage = 1;
let currentFilters = {};
let hasMoreRecords = true;

async function loadFinancialRecords() {
    currentPage = 1;
    currentFilters = {};
    hasMoreRecords = true;
    
    try {
        const response = await fetch(`${API_BASE}/finance?page=${currentPage}&per_page=10`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            displayFinancialRecords(data.data);
            updateLoadMoreButton(data.has_more);
            updateTitleFilter(data.all_titles || []);
        } else {
            console.error('Failed to load financial records:', data);
        }
    } catch (error) {
        console.error('Failed to load financial records:', error);
    }
}

function updateTitleFilter(titles) {
    const titleFilter = document.getElementById('title-filter');
    if (!titleFilter) return;
    
    // Clear existing options except the first one
    titleFilter.innerHTML = '<option value="">همه عناوین</option>';
    
    // Add unique titles
    titles.forEach(title => {
        const option = document.createElement('option');
        option.value = title;
        option.textContent = title;
        titleFilter.appendChild(option);
    });
    
    // Refresh Select2 if it's initialized
    if ($(titleFilter).hasClass('select2-hidden-accessible')) {
        $(titleFilter).select2('destroy');
        $(titleFilter).select2({
            placeholder: 'همه عناوین',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            language: {
                noResults: function() {
                    return 'نتیجه‌ای یافت نشد';
                },
                searching: function() {
                    return 'در حال جستجو...';
                },
                inputTooShort: function() {
                    return 'حداقل 1 کاراکتر وارد کنید';
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    }
}

async function handleFilterSubmit(e) {
    e.preventDefault();
    
    const titleFilter = document.getElementById('title-filter');
    const dateFilter = document.getElementById('date-filter');
    const amountSort = document.getElementById('amount-sort');
    
    // Build filter object
    currentFilters = {};
    if (titleFilter && titleFilter.value) {
        currentFilters.title = titleFilter.value;
    }
    if (dateFilter && dateFilter.value) {
        currentFilters.date = dateFilter.value;
    }
    if (amountSort && amountSort.value) {
        currentFilters.sort = amountSort.value;
    }
    
    // Reset pagination
    currentPage = 1;
    hasMoreRecords = true;
    
    // Apply filters
    await applyFilters();
    
    // Close modal
    document.getElementById('filter-modal').classList.add('hidden');
}

async function applyFilters() {
    try {
        const params = new URLSearchParams({
            page: currentPage,
            per_page: 10,
            ...currentFilters
        });
        
        const response = await fetch(`${API_BASE}/finance?${params}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            if (currentPage === 1) {
                displayFinancialRecords(data.data);
            } else {
                appendFinancialRecords(data.data);
            }
            updateLoadMoreButton(data.has_more);
        } else {
            console.error('Failed to apply filters:', data);
        }
    } catch (error) {
        console.error('Failed to apply filters:', error);
    }
}

async function loadMoreRecords() {
    if (!hasMoreRecords) return;
    
    currentPage++;
    await applyFilters();
}

function updateLoadMoreButton(hasMore) {
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (!loadMoreBtn) return;
    
    hasMoreRecords = hasMore;
    
    if (hasMore) {
        loadMoreBtn.disabled = false;
        loadMoreBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <i class="fas fa-chevron-down text-2xl ml-3"></i>
                <span class="font-medium">بارگذاری بیشتر</span>
            </div>
        `;
    } else {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <i class="fas fa-check text-2xl ml-3"></i>
                <span class="font-medium">تمام اطلاعات نمایش داده شد</span>
            </div>
        `;
    }
}

function appendFinancialRecords(records) {
    const container = document.getElementById('financial-list');
    const newRecords = records.map(record => createFinancialRecordHTML(record)).join('');
    
    container.innerHTML += newRecords;
}

function createFinancialRecordHTML(record) {
    return `
        <div class="glass-card rounded-2xl p-4 text-white" data-record-id="${record.id}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-bold text-lg mb-2">${record.title}</h4>
                    <p class="text-white/80 text-lg font-medium">${formatNumber(record.amount)} تومان</p>
                    <p class="text-white/60 text-sm mt-2">${formatDate(record.created_at)}</p>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <button onclick="editFinancialRecord(${record.id})" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-xl text-sm transition-all duration-200">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteFinancialRecord(${record.id})" class="bg-red-500/20 hover:bg-red-500/30 text-red-300 px-3 py-2 rounded-xl text-sm transition-all duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function clearFilters() {
    const titleFilter = document.getElementById('title-filter');
    const dateFilter = document.getElementById('date-filter');
    const amountSort = document.getElementById('amount-sort');
    
    if (titleFilter) {
        titleFilter.value = '';
        if ($(titleFilter).hasClass('select2-hidden-accessible')) {
            $(titleFilter).val('').trigger('change');
        }
    }
    if (dateFilter) dateFilter.value = '';
    if (amountSort) {
        amountSort.value = '';
        if ($(amountSort).hasClass('select2-hidden-accessible')) {
            $(amountSort).val('').trigger('change');
        }
    }
    
    currentFilters = {};
    currentPage = 1;
    hasMoreRecords = true;
    
    loadFinancialRecords();
}

// Edit and Delete Functions
window.editFinancialRecord = async function(id) {
    try {
        // Get record data
        const response = await fetch(`${API_BASE}/finance/${id}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            console.log('Record data:', data); // Debug log
            
            // The API now returns the record directly
            console.log('Processed record:', data);
            showEditModal(data);
        } else {
            showNotification('خطا در دریافت اطلاعات رکورد', 'error');
        }
    } catch (error) {
        console.error('Failed to get record:', error);
        showNotification('خطا در دریافت اطلاعات رکورد', 'error');
    }
}

window.showEditModal = function(record) {
    console.log('Showing edit modal for record:', record);
    
    // Create edit modal
    const editModal = document.createElement('div');
    editModal.id = 'edit-financial-modal';
    editModal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
    editModal.innerHTML = `
        <div class="glass-card rounded-3xl p-6 w-full max-w-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">ویرایش هزینه</h3>
                <button id="close-edit-modal" class="text-white/60 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="edit-financial-form" class="space-y-4">
                <input type="hidden" id="edit-record-id" value="${record.id}">
                <div>
                    <input type="text" id="edit-financial-title" value="${record.title || ''}" placeholder="عنوان" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                </div>
                <div>
                    <input type="text" id="edit-financial-amount" value="${record.amount || ''}" placeholder="مبلغ (تومان)" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                </div>
                <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">
                    ویرایش
                </button>
            </form>
        </div>
    `;
    
    document.body.appendChild(editModal);
    
    // Set values after modal is added to DOM
    setTimeout(() => {
        const titleInput = document.getElementById('edit-financial-title');
        const amountInput = document.getElementById('edit-financial-amount');
        
        if (titleInput) {
            titleInput.value = record.title || '';
        }
        if (amountInput) {
            amountInput.value = record.amount || '';
        }
    }, 100);
    
    // Add event listeners
    document.getElementById('close-edit-modal').addEventListener('click', () => {
        editModal.remove();
    });
    
    document.getElementById('edit-financial-form').addEventListener('submit', handleEditSubmit);
    
    // Format amount input
    const amountInput = document.getElementById('edit-financial-amount');
    amountInput.addEventListener('blur', (e) => {
        formatAmountInput(e.target);
    });
    
    amountInput.addEventListener('input', (e) => {
        let value = e.target.value;
        let englishValue = value.replace(/[۰-۹]/g, function(d) {
            return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
        });
        let cleanValue = englishValue.replace(/[^\d.]/g, '');
        const parts = cleanValue.split('.');
        if (parts.length > 2) {
            cleanValue = parts[0] + '.' + parts.slice(1).join('');
        }
        if (cleanValue !== englishValue) {
            e.target.value = cleanValue;
        }
    });
}

window.handleEditSubmit = async function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit-record-id').value;
    const title = document.getElementById('edit-financial-title').value;
    const amountFormatted = document.getElementById('edit-financial-amount').value;
    
    // Convert formatted amount back to number
    const englishAmount = amountFormatted.replace(/[۰-۹]/g, function(d) {
        return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
    });
    const amount = parseFloat(englishAmount.replace(/[^\d.]/g, ''));
    
    if (!title.trim()) {
        showNotification('لطفاً عنوان را وارد کنید', 'error');
        return;
    }
    
    if (!amount || isNaN(amount) || amount <= 0) {
        showNotification('لطفاً مبلغ معتبر وارد کنید', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/finance/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, amount, type: 'expense' })
        });
        
        if (response.ok) {
            document.getElementById('edit-financial-modal').remove();
            showNotification('رکورد مالی با موفقیت ویرایش شد', 'success');
            loadFinancialRecords();
        } else {
            const errorText = await response.text();
            console.error('API Error:', errorText);
            showNotification('خطا در ویرایش رکورد: ' + response.status, 'error');
        }
    } catch (error) {
        console.error('Edit failed:', error);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}

window.deleteFinancialRecord = async function(id) {
    if (!confirm('آیا مطمئن هستید که می‌خواهید این رکورد را حذف کنید؟')) {
        return;
    }
    
    try {
        console.log('Deleting record with ID:', id);
        const response = await fetch(`${API_BASE}/finance/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        console.log('Delete response status:', response.status);
        
        if (response.ok) {
            showNotification('رکورد مالی با موفقیت حذف شد', 'success');
            
            // Remove the record from DOM immediately
            const recordElement = document.querySelector(`[data-record-id="${id}"]`);
            if (recordElement) {
                recordElement.remove();
            }
            
            // Reload the financial records to ensure data consistency
            loadFinancialRecords();
        } else {
            const errorText = await response.text();
            console.error('API Error:', errorText);
            showNotification('خطا در حذف رکورد: ' + response.status, 'error');
        }
    } catch (error) {
        console.error('Delete failed:', error);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Title Suggestion Functions
async function getTitleSuggestions(query, type, inputElement) {
    try {
        const response = await fetch(`${API_BASE}/suggest/titles?query=${encodeURIComponent(query)}&type=${type}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.suggestions.length > 0) {
            showSuggestions(inputElement, data.suggestions);
        } else {
            hideSuggestions(inputElement);
        }
    } catch (error) {
        console.error('Failed to get suggestions:', error);
    }
}

function showSuggestions(inputElement, suggestions) {
    // Remove existing suggestions
    hideSuggestions(inputElement);
    
    // Create suggestions container
    const container = document.createElement('div');
    container.className = 'suggestion-container absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1';
    container.style.position = 'absolute';
    container.style.top = '100%';
    container.style.left = '0';
    container.style.right = '0';
    
    // Add suggestions
    suggestions.forEach(suggestion => {
        const item = document.createElement('div');
        item.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
        item.textContent = suggestion;
        item.addEventListener('click', () => {
            inputElement.value = suggestion;
            hideSuggestions(inputElement);
            inputElement.focus();
        });
        container.appendChild(item);
    });
    
    // Position the container
    inputElement.parentElement.style.position = 'relative';
    inputElement.parentElement.appendChild(container);
}

function hideSuggestions(inputElement) {
    const container = inputElement.parentElement.querySelector('.suggestion-container');
    if (container) {
        container.remove();
    }
}

function hideAllSuggestions() {
    document.querySelectorAll('.suggestion-container').forEach(container => {
        container.remove();
    });
}

// Placeholder functions for edit/delete operations
function editFinancialRecord(id) {
    showNotification('قابلیت ویرایش در نسخه بعدی اضافه خواهد شد', 'info');
}

function deleteFinancialRecord(id) {
    if (confirm('آیا از حذف این رکورد اطمینان دارید؟')) {
        showNotification('قابلیت حذف در نسخه بعدی اضافه خواهد شد', 'info');
    }
}

window.editTextRecord = async function(id) {
    try {
        const response = await fetch(`${API_BASE}/texts/${id}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        if (!response.ok) {
            showNotification('خطا در دریافت یادداشت', 'error');
            return;
        }
        const record = await response.json();

        // Create edit modal
        const modal = document.createElement('div');
        modal.id = 'edit-text-modal';
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="glass-card rounded-3xl p-6 w-full max-w-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">ویرایش یادداشت</h3>
                    <button id="close-edit-text-modal" class="text-white/60 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="edit-text-form" class="space-y-4">
                    <input type="hidden" id="edit-text-id" value="${record.id}">
                    <div>
                        <input type="text" id="edit-text-title" value="${record.title || ''}" placeholder="عنوان" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" required>
                    </div>
                    <div>
                        <textarea id="edit-text-description" placeholder="توضیحات" rows="4" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50 resize-none" required>${record.description || ''}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-2xl transition-all duration-200">ذخیره</button>
                </form>
            </div>
        `;
        document.body.appendChild(modal);

        document.getElementById('close-edit-text-modal').addEventListener('click', () => modal.remove());
        document.getElementById('edit-text-form').addEventListener('submit', window.handleEditTextSubmit);
    } catch (e) {
        console.error(e);
        showNotification('خطا در ارتباط با سرور', 'error');
    }
}

window.handleEditTextSubmit = async function(e) {
    e.preventDefault();
    const id = document.getElementById('edit-text-id').value;
    const title = document.getElementById('edit-text-title').value.trim();
    const description = document.getElementById('edit-text-description').value.trim();
    if (!title || !description) {
        showNotification('عنوان و توضیحات الزامی است', 'error');
        return;
    }
    try {
        const response = await fetch(`${API_BASE}/texts/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, description })
        });
        if (response.ok) {
            document.getElementById('edit-text-modal').remove();
            showNotification('یادداشت با موفقیت ویرایش شد', 'success');
            loadTextRecords();
        } else {
            const t = await response.text();
            console.error(t);
            showNotification('خطا در ویرایش یادداشت', 'error');
        }
    } catch (e) {
        console.error(e);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}

window.deleteTextRecord = async function(id) {
    if (!confirm('آیا از حذف این یادداشت اطمینان دارید؟')) return;
    try {
        const response = await fetch(`${API_BASE}/texts/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            // Remove from DOM immediately for responsiveness
            const el = document.querySelector(`[data-text-id="${id}"]`);
            if (el) el.remove();
            showNotification('یادداشت با موفقیت حذف شد', 'success');
            // Reload list to keep state consistent
            loadTextRecords();
        } else {
            const t = await response.text();
            console.error(t);
            showNotification('خطا در حذف یادداشت', 'error');
        }
    } catch (e) {
        console.error(e);
        showNotification('خطا در اتصال به سرور', 'error');
    }
}
