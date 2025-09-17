# E-Masjid - Sistem Pengurusan Masjid Komprehensif

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
<img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
<img src="https://img.shields.io/badge/Tailwind_CSS-4.1.11-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
<img src="https://img.shields.io/badge/Alpine.js-3.x-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
<img src="https://img.shields.io/badge/DaisyUI-5.0.50-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white" alt="DaisyUI">
</p>

## 🕌 Tentang E-Masjid

E-Masjid adalah sistem pengurusan masjid yang komprehensif dan modern yang dibangunkan menggunakan Laravel 12. Sistem ini direka khusus untuk memenuhi keperluan pengurusan masjid di Malaysia dengan pendekatan modular yang membolehkan multiple masjid beroperasi dalam satu platform terpusat.

## ✨ Ciri-ciri Utama

### 🏗️ Arsitektur Multi-Masjid
- **Platform Terpusat**: Satu sistem untuk multiple masjid dengan shared infrastructure
- **Data Isolation**: Automatic data isolation menggunakan `masjid_id` filter dan MasjidScope trait
- **Selective Isolation**: Global data untuk pentadbiran sistem, isolated data untuk operasi masjid
- **API Integration**: RESTful API dengan Laravel Sanctum authentication
- **Scalable Architecture**: Horizontal scaling dengan proper database indexing

### 🎨 Interface Modern & Responsif
- **Mobile-First Design**: Optimized untuk semua peranti
- **Interactive Navigation**: Navbar dengan nested dropdown menggunakan Alpine.js
- **Color-Coded System**: Ribbon colors untuk visual identification
- **Consistent Design**: Font Poppins (10px-14px), border radius (0px-2px)

### 🔐 Sistem Keselamatan Lanjutan
- **Role-Based Access Control**: Menggunakan Spatie Permission
- **Multi-Level Authentication**: Admin, AJK, Pengguna biasa
- **Audit Logging**: Rekod semua aktiviti sistem
- **Security Monitoring**: Log keselamatan dan akses tidak sah

## 📋 System Menu Structure

```
📊 DASHBOARD
└── Dashboard overview with statistics and analytics

👥 MANAGEMENT
├── Congregation Members
├── Committee Members
└── Welfare & Assistance

💰 FINANCE
├── Daily Operations
│   ├── Daily Transactions
│   ├── Fund Collection
│   └── Death Benefits
├── Accounting
│   ├── General Ledger
│   ├── Transaction Reports
│   ├── Journal Reports
│   ├── Balance Sheet
│   ├── Profit & Loss Account
│   └── Trial Balance
├── Purchasing
│   ├── Purchase Orders
│   ├── Goods Receipt Notes
│   ├── Supplier Invoices
│   ├── Cash Purchases
│   ├── Payment Vouchers
│   ├── Goods Returns
│   └── Supplier Credit Notes
└── Analysis & Dashboard
    ├── Financial Dashboard
    └── Trend Analysis

🎯 OPERATIONS
├── Programs & Education
├── Facilities & Bookings
└── Funeral Management

📦 ASSETS
├── Asset Register
├── Maintenance
├── Asset Movement
├── Valuation & Depreciation
├── Asset Reports
└── Asset Disposal

📢 COMMUNICATION
├── Message Broadcasting
├── Website Content
└── Announcements & News

📁 FILES
├── Document Management
├── Digital Library
└── Archive & Records

⚙️ SYSTEM ADMINISTRATION
├── General Settings
├── Mosque Directory
├── User Management
├── Group Management
├── Integrations
├── Audit Logs
└── Security Logs
```

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12** - Framework PHP dengan multi-tenancy support
- **PHP 8.2+** - Modern PHP dengan performance improvements
- **MySQL/PostgreSQL** - Production database dengan proper indexing
- **Laravel Sanctum** - API authentication dan SPA token management
- **Spatie Ecosystem** - Permission, Activity Log, Media Library, Query Builder

### Frontend
- **Tailwind CSS 4.1.11** - Utility-first CSS framework
- **DaisyUI 5.0.50** - Component library
- **Alpine.js 3.x** - Lightweight JavaScript framework
- **Chart.js 4.4.0** - Data visualization
- **Material Icons** - Icon system

### Development Tools
- **Vite 7.0.4** - Build tool dan hot reload
- **Laravel Pint** - Code formatting
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment

## 🚀 Pemasangan & Setup

### Keperluan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan npm
- SQLite (atau MySQL/PostgreSQL)

### Langkah Pemasangan
```bash
# Clone repository
git clone [repository-url]
cd E-Masjid

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build
```

### Development
```bash
# Start development server
php artisan serve

# Watch assets (separate terminal)
npm run dev

# Run tests
php artisan test
```

## 📚 Dokumentasi

Untuk dokumentasi yang lebih terperinci, sila rujuk folder `MD/`:
- [Struktur Sistem](MD/README.md)
- [Modul Pengguna & Akses](MD/modules/pengguna-akses.md)
- [API Documentation](MD/api-documentation.md)
- [Frontend Components](MD/frontend-components.md)

## 🤝 Kontribusi

Kami mengalu-alukan kontribusi dari komuniti. Sila baca panduan kontribusi sebelum membuat pull request.

## 📄 Lesen

Projek ini dilesenkan di bawah [MIT License](LICENSE).

## 📞 Sokongan

Untuk sokongan teknikal atau pertanyaan, sila hubungi pasukan pembangunan melalui:
- Email: [support@e-masjid.com]
- GitHub Issues: [Link to issues]

---

<p align="center">
<strong>Dibangunkan dengan ❤️ untuk komuniti masjid Malaysia</strong>
</p>
