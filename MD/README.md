# E-Masjid - Sistem Pengurusan Masjid

## Gambaran Keseluruhan

E-Masjid adalah sistem pengurusan masjid yang komprehensif yang dibangunkan menggunakan Laravel 12 dengan Tailwind CSS dan DaisyUI. Sistem ini direka untuk menampung multiple masjid dengan pendekatan modular yang membolehkan setiap masjid mempunyai pengurusan yang berasingan namun terpusat.

## Visi Sistem Multi-Masjid

Sistem ini direka untuk menjadi platform terpusat yang membolehkan:
- Multiple masjid mendaftar dan menguruskan operasi mereka melalui API
- **Data Isolation**: Setiap masjid mempunyai data yang diasingkan menggunakan `masjid_id` filter
- **MasjidScope Trait**: Automatic scoping untuk semua models kecuali yang dikecualikan
- Pentadbiran terpusat untuk pengurusan sistem global
- Modul-modul yang boleh dikustomisasi mengikut keperluan setiap masjid
- Integrasi API untuk aplikasi mobile dan sistem luaran

### Data Isolation Strategy

#### **Models Yang DI-ISOLATED (Menggunakan MasjidScope):**
Semua models akan menggunakan `masjid_id` filter untuk data isolation:
- ✅ Ahli Kariah, AJK, Asnaf & Kebajikan
- ✅ Semua data Kewangan (Transaksi, Lejar, Pembelian, dll)
- ✅ Operasi (Program, Fasiliti, Jenazah)
- ✅ Aset (Daftar, Penyelenggaraan, Pergerakan, dll)
- ✅ Komunikasi (Siaran, Kandungan, Pengumuman)
- ✅ Fail (Dokumen, Perpustakaan, Arkib)
- ✅ Tetapan Umum, Integrasi, Log Audit

#### **Models Yang TIDAK DI-ISOLATED (Global Data):**
Models untuk pentadbiran sistem global:
- ❌ Senarai Masjid (global registry)
- ❌ Senarai Pengguna (cross-masjid access)
- ❌ Senarai Kumpulan (global roles)
- ❌ Log Keselamatan (system-wide security monitoring)

## Struktur Modular

Sistem ini dibahagikan kepada 8 modul utama yang boleh diakses melalui navbar dengan 42 sub-modul dan 1 struktur nested:

### 1. 📊 Papan Pemuka (Dashboard)
- Paparan statistik keseluruhan masjid dengan KPI real-time
- Trend sumbangan dan aktiviti bulanan dengan carta interaktif
- Widget cuaca semasa dengan maklumat terperinci dan ramalan
- Notifikasi dan pengumuman terkini dari semua modul
- Analytics dashboard untuk decision making

### 2. 👥 Pengurusan
- **Ahli Kariah**: Pengurusan ahli kariah masjid dengan sistem CRUD lengkap, import/export data
- **AJK**: Pengurusan Ahli Jawatankuasa, struktur organisasi, dan hierarki peranan
- **Asnaf & Kebajikan**: Pengurusan bantuan, program kebajikan, dan tracking beneficiaries

### 3. 💰 Kewangan (Struktur Nested)
#### **Operasi Harian**
- **Transaksi Harian**: Rekod semua transaksi masuk/keluar harian
- **Kutipan Dana**: Sistem kutipan sumbangan, derma, kutipan Jumaat
- **Khairat Kematian**: Pengurusan dana khairat kematian dan claim

#### **Perakaunan**
- **Lejar Am**: General ledger dan chart of accounts
- **Laporan Transaksi**: Detailed transaction reports
- **Laporan Jurnal**: Journal entries dan posting
- **Kunci Kira-Kira**: Balance sheet dan financial position
- **Akaun Untung Rugi**: Profit & loss statement
- **Imbangan Duga**: Trial balance untuk balancing accounts

#### **Pembelian**
- **Pesanan Pembelian**: Purchase order management
- **Nota Penerimaan Barang**: Goods received notes
- **Invois Pembekal**: Supplier invoice processing
- **Pembelian Tunai**: Cash purchases tracking
- **Baucar Pembayaran**: Payment vouchers
- **Pulangan Barang**: Goods return management
- **Nota Kredit Pembekal**: Supplier credit notes

#### **Analisis & Dashboard**
- **Papan Pemuka Kewangan**: Financial KPIs dan real-time metrics
- **Analisis Trend**: Trend analysis, forecasting, dan comparative reports

### 4. 🎯 Operasi
- **Program & Pendidikan**: Pengurusan program agama, kelas mengaji, ceramah, dan tracking attendance
- **Fasiliti & Tempahan**: Sistem tempahan dewan, surau, equipment rental
- **Pengurusan Jenazah**: Urusan jenazah, kafan, pengebumian, coordination dengan authorities

### 5. 📦 Aset
- **Daftar Aset**: Asset registry dengan barcode/QR code tracking
- **Penyelenggaraan**: Maintenance scheduling, work orders, dan service history
- **Pergerakan Aset**: Asset movement tracking dan location management
- **Penilaian & Susut Nilai**: Asset valuation, depreciation calculation
- **Laporan Aset**: Comprehensive asset reports dan analytics
- **Pelupusan Aset**: Asset disposal process dan documentation

### 6. 📢 Komunikasi
- **Siaran Mesej**: WhatsApp broadcast, SMS notifications, emergency alerts
- **Kandungan Website**: Blog posts, artikel, ceramah content untuk website API integration
- **Pengumuman & Berita**: News management, announcements untuk website publication

### 7. 📁 Fail
- **Pengurusan Dokumen**: File management dengan sharing function, upload PDF/Office files (max 10MB)
- **Perpustakaan Digital**: Digital library untuk buku agama, artikel, panduan solat
- **Arkib & Rekod**: Historical records, meeting minutes, legal documents, compliance files

### 8. ⚙️ Pentadbiran Sistem
- **Tetapan Umum**: Konfigurasi asas sistem, masjid profile, general settings
- **Senarai Masjid**: Multi-mosque management, registration via API
- **Senarai Pengguna**: User management, access control, profile management
- **Senarai Kumpulan**: User groups, role hierarchy, permission sets
- **Integrasi**: API configuration, third-party integrations, webhook settings
- **Log Audit**: Activity logging, change tracking, compliance audit trail
- **Log Keselamatan**: Security monitoring, unauthorized access attempts, breach detection

## Ciri-ciri Utama

### 1. Sistem Multi-Masjid
- Setiap masjid boleh mendaftar secara berasingan melalui API
- Data masjid diasingkan dengan selamat
- Pentadbiran terpusat untuk semua masjid
- Konfigurasi yang fleksibel untuk setiap masjid

### 2. Pengurusan Pengguna & Akses Lanjutan
- Sistem peranan dan kebenaran yang fleksibel menggunakan Spatie Permission
- Multi-level authentication: Super Admin, Admin Masjid, AJK, Pengguna biasa
- Pengguna boleh mempunyai akses ke multiple masjid dengan role yang berbeza
- Log audit komprehensif untuk semua aktiviti pengguna dan sistem
- Pengurusan kumpulan pengguna yang terstruktur dengan inheritance permissions

### 3. Interface Responsif dan Modern
- Reka bentuk mobile-first dengan Alpine.js untuk interactivity
- Navbar modular dengan nested dropdown yang smooth dan responsive
- Color-coded ribbon system untuk visual identification (35+ unique colors)
- Komponen UI yang boleh digunakan semula dengan consistent design language
- Font Poppins dengan saiz minimum 10px, maksimum 14px
- Border radius minimum 0px, maksimum 2px untuk konsistensi design
- Hover effects dan transitions yang smooth untuk better UX

### 4. Sistem Cuaca Terintegrasi
- Integrasi API cuaca untuk lokasi masjid dengan real-time updates
- Widget cuaca di navbar dengan maklumat terperinci dan forecast
- Ramalan cuaca 5 hari dan maklumat semasa (suhu, kelembapan, angin)
- Tooltip interaktif dengan data cuaca lengkap dan weather alerts
- Automatic location detection atau manual location setting

### 5. Sistem Fail dan Dokumen Lanjutan
- File management dengan sharing capabilities dan permission control
- Support multiple file formats: PDF, Office documents, images
- Digital library untuk koleksi buku agama dan educational materials
- Archive system untuk historical records dan compliance documents
- File versioning, download tracking, dan virus scanning
- Integration dengan website untuk content publishing via API

## Teknologi yang Digunakan

### Backend
- **Laravel 12** - Framework PHP dengan multi-tenancy support
- **MySQL/PostgreSQL** - Database untuk production (SQLite untuk development)
- **Laravel Sanctum** - API authentication dan SPA authentication

#### **Spatie Packages (Composer)**:
- **laravel-permission** - Role-based access control dengan multi-masjid support
- **laravel-activitylog** - Comprehensive activity logging dengan masjid isolation
- **laravel-medialibrary** - Media management dengan file organization
- **laravel-media-manager** - Advanced media management interface
- **laravel-backup** - Automated backup system
- **laravel-data** - Data transfer objects untuk API
- **laravel-health** - System health monitoring
- **laravel-query-builder** - API query filtering dan sorting
- **laravel-sitemap** - SEO sitemap generation
- **laravel-tags** - Tagging system untuk content organization

#### **Additional Composer Packages**:
- **laravel/telescope** - Debugging dan monitoring (development)
- **laravel/horizon** - Queue monitoring dan management
- **barryvdh/laravel-debugbar** - Development debugging
- **spatie/laravel-sluggable** - URL slug generation
- **spatie/laravel-translatable** - Multi-language support
- **intervention/image** - Image processing dan optimization

### Frontend
- **Tailwind CSS 4.1.11** - Utility-first CSS framework
- **DaisyUI 5.0.50** - Component library dengan theme support
- **Alpine.js 3.x** - Lightweight JavaScript framework untuk interactivity
- **Chart.js 4.4.0** - Data visualization dan analytics charts
- **Material Icons** - Comprehensive icon system
- **Vite 7.0.4** - Fast build tool dengan hot reload

#### **NPM Packages untuk Enhanced UI**:
- **@tailwindcss/forms** - Better form styling
- **@tailwindcss/typography** - Rich text styling
- **@headlessui/alpine** - Unstyled accessible UI components
- **alpinejs/persist** - State persistence
- **alpinejs/focus** - Focus management
- **sortablejs** - Drag & drop functionality
- **flatpickr** - Date/time picker
- **choices.js** - Enhanced select boxes
- **tom-select** - Advanced select component
- **quill** - Rich text editor untuk content management

### Development Tools
- **Laravel Pint** - Code formatting
- **Laravel Sail** - Docker development environment
- **PHPUnit** - Testing framework
- **Faker** - Data seeding
- **Concurrently** - Run multiple commands

## Struktur Projek

```
E-Masjid/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── KariahController.php
│   │   ├── WeatherController.php
│   │   └── DashboardController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Kariah.php
│   │   └── NavigateUser.php
│   └── Services/
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_kariah_table.php
│   │   └── add_bangsa_to_kariah_table.php
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── kariah/
│   │   ├── components/
│   │   └── overview.blade.php
│   ├── css/
│   │   └── app.css
│   └── js/
│       ├── app.js
│       └── bootstrap.js
├── routes/
│   ├── web.php
│   └── console.php
├── public/
│   ├── images/
│   └── build/
├── tests/
│   ├── Feature/
│   └── Unit/
└── MD/
    └── [Dokumentasi]
```

## Persediaan dan Pemasangan

### Keperluan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan npm
- SQLite (atau MySQL/PostgreSQL)

### Langkah Pemasangan
1. Clone repository
2. Install dependencies: `composer install`
3. Install frontend dependencies: `npm install`
4. Copy `.env.example` ke `.env`
5. Generate application key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`
7. Build assets: `npm run build`

### Development
- Start server: `php artisan serve`
- Watch assets: `npm run dev`
- Run tests: `php artisan test`

## Statistik Sistem

### Menu Structure Overview
```
📊 Total Modules: 8
📋 Total Sub-modules: 42
🔗 Nested Structure: 1 (Kewangan)
🎨 Color-coded Ribbons: 35+
⚡ Interactive Elements: Alpine.js powered
📱 Responsive Design: Mobile-first approach
```

### Module Breakdown
- **Papan Pemuka**: 1 main dashboard
- **Pengurusan**: 3 management modules
- **Kewangan**: 4 categories → 18 financial sub-modules
- **Operasi**: 3 operational modules
- **Aset**: 6 asset management modules
- **Komunikasi**: 3 communication modules
- **Fail**: 3 file management modules
- **Pentadbiran Sistem**: 7 system administration modules

## Dokumentasi Lanjut

Rujuk folder MD untuk dokumentasi terperinci:
- [Modul Pengguna & Akses](modules/pengguna-akses.md) - Detailed user management documentation
- [Struktur Database](database-structure.md) - Database schema dan relationships
- [API Documentation](api-documentation.md) - REST API endpoints dan integration
- [Frontend Components](frontend-components.md) - UI components dan styling guide
- [Deployment Guide](deployment-guide.md) - Production deployment instructions
- [Testing Guide](testing-guide.md) - Testing strategies dan best practices
- [Security Guide](security-guide.md) - Security implementation dan best practices
- [Performance Guide](performance-guide.md) - Optimization techniques dan monitoring
