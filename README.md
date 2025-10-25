# سجیل - سیستم مدیریت مالی و متنی

<div align="center">
  <img src="public/icon.svg" alt="سجیل" width="120" height="120">
  
  <h3>نرم‌افزار مدیریت مالی و یادداشت‌های متنی با قابلیت PWA</h3>
  
  [![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
  [![PWA](https://img.shields.io/badge/PWA-Enabled-green.svg)](https://web.dev/progressive-web-apps/)
  [![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
</div>

## 📋 فهرست مطالب

- [معرفی](#معرفی)
- [ویژگی‌ها](#ویژگی‌ها)
- [تکنولوژی‌های استفاده شده](#تکنولوژی‌های-استفاده-شده)
- [نصب و راه‌اندازی](#نصب-و-راه‌اندازی)
- [پیکربندی](#پیکربندی)
- [استفاده](#استفاده)
- [API Documentation](#api-documentation)
- [PWA Features](#pwa-features)
- [مشارکت](#مشارکت)
- [مجوز](#مجوز)

## 🚀 معرفی

**سجیل** یک سیستم مدیریت مالی و متنی پیشرفته است که به صورت PWA (Progressive Web App) طراحی شده و امکان مدیریت هزینه‌ها، یادداشت‌ها و گزارش‌گیری را فراهم می‌کند. این نرم‌افزار با رابط کاربری مدرن و قابلیت کار آفلاین، تجربه‌ای مشابه اپلیکیشن‌های موبایل ارائه می‌دهد.

### ✨ ویژگی‌های کلیدی

- 💰 **مدیریت مالی**: ثبت و مدیریت هزینه‌ها با قابلیت فیلتر و جستجو
- 📝 **یادداشت‌های متنی**: ثبت و مدیریت یادداشت‌های شخصی
- 📊 **گزارش‌گیری**: نمودارها و آمارهای مالی
- 🌙 **تم تاریک/روشن**: پشتیبانی از تم‌های مختلف با تشخیص خودکار
- 📱 **PWA**: قابلیت نصب روی دستگاه‌های موبایل و دسکتاپ
- 🔄 **کار آفلاین**: عملکرد بدون نیاز به اتصال اینترنت
- 🔐 **امنیت**: احراز هویت کاربران با Laravel Sanctum

## 🛠 ویژگی‌ها

### مدیریت مالی
- ثبت هزینه‌ها با عنوان و مبلغ
- فیلتر بر اساس عنوان، تاریخ و مبلغ
- مرتب‌سازی هزینه‌ها
- پیشنهاد خودکار عناوین
- ویرایش و حذف رکوردها

### مدیریت متنی
- ثبت یادداشت‌های متنی
- ویرایش و حذف یادداشت‌ها
- جستجو در یادداشت‌ها

### گزارش‌گیری
- آمار کلی هزینه‌ها
- نمودارهای روزانه و ماهانه
- گزارش 30 روز گذشته
- تعداد تراکنش‌ها

### رابط کاربری
- طراحی مدرن با Glass Effect
- پشتیبانی از RTL (راست به چپ)
- انیمیشن‌های نرم
- طراحی ریسپانسیو
- پشتیبانی از تم‌های مختلف

## 🔧 تکنولوژی‌های استفاده شده

### Backend
- **Laravel 11.x** - فریمورک PHP
- **PHP 8.2+** - زبان برنامه‌نویسی
- **MySQL** - پایگاه داده
- **Laravel Sanctum** - احراز هویت API
- **Laravel Migrations** - مدیریت پایگاه داده

### Frontend
- **HTML5** - ساختار
- **CSS3** - استایل‌دهی
- **JavaScript (ES6+)** - منطق کلاینت
- **Tailwind CSS** - فریمورک CSS
- **Chart.js** - نمودارها
- **Select2** - جستجوی پیشرفته
- **Font Awesome** - آیکون‌ها

### PWA & Performance
- **Service Worker** - کش و کار آفلاین
- **Web App Manifest** - تنظیمات PWA
- **SVG Icons** - آیکون‌های مقیاس‌پذیر
- **Dynamic Theme** - تغییر خودکار تم
- **Offline Support** - عملکرد آفلاین

### Development Tools
- **Composer** - مدیریت وابستگی‌ها
- **Artisan** - ابزارهای Laravel
- **Git** - کنترل نسخه

## 📦 نصب و راه‌اندازی

### پیش‌نیازها

```bash
# PHP 8.2 یا بالاتر
php --version

# Composer
composer --version

# MySQL
mysql --version

# Node.js (اختیاری برای توسعه)
node --version
```

### مرحله 1: کلون کردن پروژه

```bash
git clone https://github.com/Mahdihaghighi/sajjil.git
cd sajjil
```

### مرحله 2: نصب وابستگی‌ها

```bash
# نصب وابستگی‌های PHP
composer install

# کپی فایل تنظیمات
cp .env.example .env

# تولید کلید اپلیکیشن
php artisan key:generate
```

### مرحله 3: تنظیم پایگاه داده

فایل `.env` را ویرایش کنید:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sajjil
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### مرحله 4: اجرای Migration ها

```bash
# اجرای migration ها
php artisan migrate

# اجرای seeder ها (اختیاری)
php artisan db:seed
```

### مرحله 5: تنظیم Storage

```bash
# ایجاد لینک سمبلیک برای storage
php artisan storage:link
```

### مرحله 6: اجرای سرور

```bash
# اجرای سرور توسعه
php artisan serve
```

اپلیکیشن در آدرس `http://localhost:8000` در دسترس خواهد بود.

## ⚙️ پیکربندی

### تنظیمات PWA

فایل `public/manifest.json` را برای تنظیمات PWA ویرایش کنید:

```json
{
  "name": "سجیل - مدیریت مالی و متنی",
  "short_name": "سجیل",
  "theme_color": "#16213e",
  "background_color": "#1a1a2e"
}
```

### تنظیمات Service Worker

فایل `public/sw.js` را برای تنظیمات کش ویرایش کنید:

```javascript
const CACHE_NAME = 'sajjil-v1.0.0';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js'
];
```

### تنظیمات امنیتی

```bash
# تنظیم مجوزهای فایل‌ها
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 📱 استفاده

### ورود به سیستم

1. آدرس `http://localhost:8000` را در مرورگر باز کنید
2. با نام کاربری و رمز عبور وارد شوید
3. کاربر پیش‌فرض: `admin` / رمز: `123456`

### مدیریت مالی

- **افزودن هزینه**: روی دکمه "افزودن هزینه" کلیک کنید
- **فیلتر**: از دکمه "فیلتر و جستجو" استفاده کنید
- **ویرایش**: روی آیکون ویرایش کلیک کنید
- **حذف**: روی آیکون حذف کلیک کنید

### مدیریت یادداشت‌ها

- **افزودن یادداشت**: روی دکمه "افزودن یادداشت" کلیک کنید
- **ویرایش**: روی آیکون ویرایش کلیک کنید
- **حذف**: روی آیکون حذف کلیک کنید

### گزارش‌گیری

- به تب "گزارشات" بروید
- آمار کلی و نمودارها را مشاهده کنید

## 🔌 API Documentation

### Authentication

```http
POST /api/login
Content-Type: application/json

{
  "username": "admin",
  "password": "123456"
}
```

### Financial Records

```http
# دریافت لیست هزینه‌ها
GET /api/finance

# افزودن هزینه جدید
POST /api/finance
{
  "title": "خرید مواد غذایی",
  "amount": 50000,
  "type": "expense"
}

# ویرایش هزینه
PUT /api/finance/{id}
{
  "title": "خرید مواد غذایی",
  "amount": 60000,
  "type": "expense"
}

# حذف هزینه
DELETE /api/finance/{id}
```

### Text Records

```http
# دریافت لیست یادداشت‌ها
GET /api/texts

# افزودن یادداشت جدید
POST /api/texts
{
  "title": "یادداشت مهم",
  "description": "محتوای یادداشت"
}
```

### Reports

```http
# دریافت گزارش مالی
GET /api/reports/financial
```

## 🌐 PWA Features

### نصب اپلیکیشن

1. در مرورگر Chrome/Edge روی آیکون "نصب" کلیک کنید
2. یا از منوی مرورگر "Add to Home Screen" را انتخاب کنید

### کار آفلاین

- اپلیکیشن در حالت آفلاین کار می‌کند
- داده‌ها در کش ذخیره می‌شوند
- هنگام اتصال مجدد، داده‌ها همگام‌سازی می‌شوند

### تم‌های پویا

- تشخیص خودکار تم سیستم
- تغییر خودکار رنگ‌های PWA
- پشتیبانی از تم تاریک/روشن

## 🚀 استقرار (Deployment)

### Apache/Nginx

```bash
# تنظیم Document Root به پوشه public
DocumentRoot /path/to/sajjil/public

# تنظیم مجوزها
chown -R www-data:www-data /path/to/sajjil
chmod -R 755 /path/to/sajjil
```

### Docker

```dockerfile
FROM php:8.2-fpm

# نصب وابستگی‌ها
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# کپی فایل‌ها
COPY . /var/www

# نصب وابستگی‌ها
RUN composer install --no-dev --optimize-autoloader

# تنظیم مجوزها
RUN chown -R www-data:www-data /var/www
```

## 🧪 تست

```bash
# اجرای تست‌ها
php artisan test

# اجرای تست‌های خاص
php artisan test --filter=FinancialTest
```

## 📊 عملکرد

### بهینه‌سازی

- کش کردن فایل‌های استاتیک
- فشرده‌سازی تصاویر
- بهینه‌سازی کوئری‌های دیتابیس
- استفاده از Service Worker

### مانیتورینگ

```bash
# مشاهده لاگ‌ها
tail -f storage/logs/laravel.log

# پاک کردن کش
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 🤝 مشارکت

1. Fork کنید
2. شاخه جدید ایجاد کنید (`git checkout -b feature/amazing-feature`)
3. تغییرات را commit کنید (`git commit -m 'Add amazing feature'`)
4. به شاخه push کنید (`git push origin feature/amazing-feature`)
5. Pull Request ایجاد کنید

## 📝 تغییرات

### نسخه 1.0.0
- پیاده‌سازی اولیه سیستم
- مدیریت مالی و متنی
- PWA support
- تم‌های پویا
- API کامل

## 🐛 گزارش باگ

اگر باگی پیدا کردید، لطفاً در [Issues](https://github.com/Mahdihaghighi/sajjil/issues) گزارش دهید.

## 📄 مجوز

این پروژه تحت مجوز MIT منتشر شده است. برای جزئیات بیشتر فایل [LICENSE](LICENSE) را مطالعه کنید.

## 👨‍💻 توسعه‌دهنده

**محمد حقیقی**
- GitHub: [@Mahdihaghighi](https://github.com/Mahdihaghighi)
- Email: [ایمیل شما]

## 🙏 تشکر

- Laravel Framework
- Tailwind CSS
- Chart.js
- Font Awesome
- تمام مشارکت‌کنندگان

---

<div align="center">
  <p>ساخته شده با ❤️ در ایران</p>
  <p>⭐ اگر این پروژه مفید بود، ستاره بدهید!</p>
</div>
