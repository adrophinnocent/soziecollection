<div style="text-align: center;">
  <h1>✨ SOZIE COLLECTION ✨</h1>
  <p><strong>Your Scent. Your Signature.</strong></p>
  <p>A high-performance, modern luxury fragrance & perfume e-commerce web platform built with Laravel, Tailwind CSS, and Alpine.js.</p>

  <p>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Framework"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-38BDF8?style=flat-square&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
    <a href="https://alpinejs.dev"><img src="https://img.shields.io/badge/Alpine.js-v3.x-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white" alt="Alpine.js"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg?style=flat-square" alt="License"></a>
  </p>
</div>

---

## 📖 Overview

**Sozie Collection** is an enterprise-grade luxury e-commerce platform dedicated to haute parfumerie and artisanal fragrances. Engineered for elegance, speed, and exceptional user experience, it features a bilingual storefront (English & Swahili), an interactive fragrance discovery engine, dynamic volume-variant pricing, real-time cart management, seamless checkout, live order tracking, and a full-featured admin management dashboard.

---

## ✨ Key Features

### 🛍️ Luxury Customer Experience
* **Curated Fragrance Categories**: Women's Perfumes, Men's Perfumes, Unisex Creations, Concentrated Perfume Oils, Body Mists, and Signature Discovery Sets.
* **Granular Fragrance Profiles**: Comprehensive scent breakdown including Top Notes, Heart Notes, Base Notes, Concentration (Eau de Parfum, Extrait, Oils), Longevity, Intensity, Sillage, Seasonality, and Ideal Occasion.
* **Interactive Fragrance Finder**: Smart discovery API helping customers quickly find their perfect signature scent based on mood, scent family, and occasion.
* **Dynamic Size & Bottle Variants**: Instant price and availability adjustments across 10ml Travel Sprays, 30ml Pocket Luxury, 50ml Signature Bottles, and 100ml Deluxe Reserves.
* **Product Media & Showreels**: HD imagery, campaign artwork, and embedded video showreels for an immersive brand narrative.
* **Customer Reviews & Ratings**: Verified purchaser reviews with star ratings and localized testimonials.

### 🌐 Bilingual Support
* **Multi-Language Switcher**: Instant dynamic toggle between **English (EN)** and **Swahili (SW)** with locale persistence via custom middleware.

### 🛒 E-Commerce Engine & Orders
* **Interactive Cart**: Asynchronous cart drawer and dedicated cart page allowing quantity adjustments, coupon applications, and real-time total calculation.
* **Streamlined Checkout**: Clean, single-page guest or member checkout flow with instant order generation.
* **Live Order Tracking**: Dedicated customer portal allowing real-time status lookup (Pending, Processing, Shipped, Delivered) using unique order reference numbers.

### 👑 Admin Control Panel
* **Product Catalog Management**: Full CRUD operations for creating, updating, and managing products, variants, and stock quantities.
* **Order Management & Fulfillment**: Admin oversight of customer orders, cart breakdowns, shipping details, and live status updates.
* **Promotions & Hero Banners**: Dynamic homepage hero banner management system.

---

## 🛠️ Technology Stack

* **Backend Framework**: Laravel 12.x (PHP 8.3+)
* **Frontend UI**: Blade Templates, Alpine.js v3
* **Styling Framework**: Tailwind CSS v4.0
* **Asset Bundler**: Vite 8.x with `@tailwindcss/vite`
* **Database**: SQLite (Default) / MySQL / PostgreSQL
* **Code Formatting**: Laravel Pint
* **Testing Suite**: PHPUnit 12.x

---

## 🚀 Getting Started

### Prerequisites

Ensure your development environment meets the following requirements:
* **PHP**: `>= 8.3` (with extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
* **Composer**: `>= 2.x`
* **Node.js**: `>= 18.x`
* **NPM**: `>= 9.x`

---

### Step-by-Step Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-org/sozie-collection.git
   cd sozie-collection
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Configure Environment File**
   ```bash
   cp .env.example .env
   ```
   *Configure your database settings in `.env` if using MySQL/PostgreSQL, or keep the default SQLite configuration.*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Assets & Start Local Server**
   ```bash
   # Run both Laravel dev server & Vite bundler concurrently:
   composer run dev
   ```
   Or run them in separate terminals:
   ```bash
   # Terminal 1: Laravel Backend
   php artisan serve

   # Terminal 2: Vite Frontend
   npm run dev
   ```

8. **Access the Application**
   Open your browser and navigate to `http://localhost:8000`.

---

## 📁 Directory Structure

```
sozie-collection/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Admin, Cart, Checkout, Home, Order, Product
│   │   └── Middleware/     # SetLocale (Bilingual EN/SW switcher)
│   └── Models/             # Banner, Category, Coupon, Order, OrderItem, Product, ProductVariant, Review, User
├── database/
│   ├── factories/          # Model factories for testing
│   ├── migrations/         # Database migration files
│   └── seeders/            # Curated seed data for perfumes, categories & banners
├── resources/
│   ├── css/                # Tailwind CSS stylesheets
│   ├── js/                 # Alpine.js and JavaScript logic
│   └── views/              # Blade templates (Admin, Cart, Checkout, Orders, Shop, Home)
├── routes/
│   ├── console.php         # Console routes
│   └── web.php             # Public & Admin web routes
└── tests/                  # Unit and Feature test suites
```

---

## 🧪 Testing & Code Style

Run the automated test suite with PHPUnit:
```bash
php artisan test
```

Format code according to Laravel standards using Laravel Pint:
```bash
vendor/bin/pint --format agent
```

---

## 📄 License

The **Sozie Collection** project is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).
