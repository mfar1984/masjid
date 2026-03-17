# MODUL ASNAF - PHASE 2 DESIGN DOCUMENT

## 📋 OVERVIEW

Phase 2 akan menambah 4 submenu utama untuk melengkapkan sistem pengurusan zakat:

1. **Permohonan Zakat** - Pengurusan permohonan bantuan zakat
2. **Agihan Zakat** - Pengurusan pemberian/agihan zakat kepada asnaf
3. **Laporan Zakat** - Laporan statistik dan analisis zakat
4. **Tetapan Zakat** - Konfigurasi sistem zakat

---

## 1️⃣ PERMOHONAN ZAKAT

### Konsep
Sistem untuk menguruskan permohonan bantuan zakat dari asnaf yang sudah berdaftar.

### Workflow
```
Asnaf Berdaftar → Buat Permohonan → Semakan → Kelulusan → Agihan
```

### Database: `permohonan_zakat`

**Fields (25-30 fields)**:
```php
- id
- asnaf_id (FK to asnaf table)
- masjid_id (multi-tenant)
- no_permohonan (auto-generated: PZ-2025-0001)
- tarikh_permohonan
- jenis_bantuan (enum: Tunai, Barangan, Pendidikan, Perubatan, Kecemasan)
- kategori_bantuan (enum: Bulanan, Sekali, Khas)
- jumlah_dipohon (decimal)
- sebab_permohonan (text)
- dokumen_sokongan_path (file)

// Status & Workflow
- status (enum: Menunggu, Dalam Semakan, Diluluskan, Ditolak, Dibatalkan)
- tarikh_semakan
- disemak_oleh (FK to users)
- catatan_semakan (text)
- tarikh_kelulusan
- diluluskan_oleh (FK to users)
- jumlah_diluluskan (decimal)
- catatan_kelulusan (text)
- sebab_penolakan (text)

// Audit
- created_by
- updated_by
- created_at
- updated_at
- deleted_at (soft delete)
```

### Features
- ✅ List permohonan dengan filters (status, jenis bantuan, tarikh)
- ✅ Create permohonan (pilih asnaf dari dropdown)
- ✅ View details permohonan
- ✅ Edit permohonan (jika status Menunggu)
- ✅ Workflow: Semak → Lulus/Tolak
- ✅ Upload dokumen sokongan
- ✅ Auto-generate no_permohonan
- ✅ Statistics cards (6 cards)
- ✅ Export to CSV/PDF

### Statistics Cards
1. Jumlah Permohonan
2. Menunggu Semakan
3. Dalam Semakan
4. Diluluskan
5. Ditolak
6. Jumlah Dipohon (RM)

### Filters
- Search (no permohonan, nama asnaf)
- Status
- Jenis Bantuan
- Tarikh Dari/Hingga

### Views
- `permohonan-zakat/index.blade.php`
- `permohonan-zakat/create.blade.php`
- `permohonan-zakat/edit.blade.php`
- `permohonan-zakat/show.blade.php`

---

## 2️⃣ AGIHAN ZAKAT

### Konsep
Sistem untuk merekod pemberian/agihan zakat kepada asnaf yang telah diluluskan.

### Workflow
```
Permohonan Diluluskan → Buat Agihan → Bayar → Selesai
```

### Database: `agihan_zakat`

**Fields (20-25 fields)**:
```php
- id
- permohonan_zakat_id (FK, nullable - boleh agihan tanpa permohonan)
- asnaf_id (FK to asnaf table)
- masjid_id (multi-tenant)
- no_agihan (auto-generated: AZ-2025-0001)
- tarikh_agihan
- jenis_agihan (enum: Tunai, Cek, Bank Transfer, Barangan, Baucar)
- kategori_asnaf (from asnaf table)
- jumlah_agihan (decimal)
- cara_bayaran (enum: Tunai, Cek, Transfer, Barangan)
- no_rujukan (cek/transfer number)
- bank_name (if transfer)
- account_number (if transfer)

// Barangan (if jenis_agihan = Barangan)
- senarai_barangan (JSON: [{item, quantity, value}])
- jumlah_nilai_barangan (decimal)

// Status
- status (enum: Belum Bayar, Sudah Bayar, Dibatalkan)
- tarikh_bayaran
- dibayar_oleh (FK to users)
- penerima_nama (confirmation)
- penerima_ic (confirmation)
- penerima_tandatangan_path (file/signature)
- resit_path (file)

// Audit
- created_by
- updated_by
- created_at
- updated_at
- deleted_at
```

### Features
- ✅ List agihan dengan filters
- ✅ Create agihan (pilih dari permohonan diluluskan OR manual)
- ✅ View details agihan
- ✅ Edit agihan (jika belum bayar)
- ✅ Mark as paid
- ✅ Print resit
- ✅ Upload bukti bayaran
- ✅ Statistics cards (6 cards)
- ✅ Export to CSV/PDF

### Statistics Cards
1. Jumlah Agihan
2. Belum Bayar
3. Sudah Bayar
4. Jumlah Nilai (RM)
5. Agihan Bulan Ini
6. Agihan Tahun Ini

### Filters
- Search (no agihan, nama asnaf)
- Status
- Jenis Agihan
- Kategori Asnaf
- Tarikh Dari/Hingga

### Views
- `agihan-zakat/index.blade.php`
- `agihan-zakat/create.blade.php`
- `agihan-zakat/edit.blade.php`
- `agihan-zakat/show.blade.php`
- `agihan-zakat/resit.blade.php` (printable)

---

## 3️⃣ LAPORAN ZAKAT

### Konsep
Dashboard dan laporan komprehensif untuk analisis zakat.

### Features

#### A. Dashboard Overview
- Total Asnaf Berdaftar
- Total Permohonan (Bulan/Tahun)
- Total Agihan (Bulan/Tahun)
- Jumlah Zakat Diagihkan (RM)
- Trend Chart (6 bulan terakhir)
- Top 5 Kategori Asnaf

#### B. Laporan Asnaf
- Senarai asnaf by kategori
- Senarai asnaf by status
- Senarai asnaf by pendapatan
- Demographics (umur, jantina, status perkahwinan)

#### C. Laporan Permohonan
- Summary by status
- Summary by jenis bantuan
- Summary by bulan/tahun
- Average processing time
- Approval rate

#### D. Laporan Agihan
- Summary by kategori asnaf
- Summary by jenis agihan
- Summary by bulan/tahun
- Top recipients
- Payment method breakdown

#### E. Laporan Kewangan
- Total kutipan zakat (if ada modul kutipan)
- Total agihan zakat
- Baki zakat
- Breakdown by kategori asnaf (8 asnaf)

### Charts
1. **Pie Chart**: Agihan by Kategori Asnaf
2. **Bar Chart**: Agihan by Bulan
3. **Line Chart**: Trend Permohonan vs Agihan
4. **Donut Chart**: Jenis Bantuan

### Filters
- Tarikh Dari/Hingga
- Kategori Asnaf
- Jenis Bantuan
- Status
- Masjid (Super Admin only)

### Export Options
- PDF Report (formatted)
- Excel/CSV
- Print View

### Views
- `laporan-zakat/index.blade.php` (dashboard)
- `laporan-zakat/asnaf.blade.php`
- `laporan-zakat/permohonan.blade.php`
- `laporan-zakat/agihan.blade.php`
- `laporan-zakat/kewangan.blade.php`

---

## 4️⃣ TETAPAN ZAKAT

### Konsep
Konfigurasi dan settings untuk sistem zakat.

### Database: `tetapan_zakat`

**Fields**:
```php
- id
- masjid_id (multi-tenant)
- setting_key
- setting_value
- setting_type (string, number, boolean, json)
- description
- created_at
- updated_at
```

### Settings Categories

#### A. Had Kifayah (Poverty Line)
```
- had_kifayah_individu (RM)
- had_kifayah_keluarga_2 (RM)
- had_kifayah_keluarga_3 (RM)
- had_kifayah_keluarga_4 (RM)
- had_kifayah_keluarga_5 (RM)
- had_kifayah_tambahan_per_orang (RM)
```

#### B. Had Bantuan (Assistance Limits)
```
- bantuan_minimum (RM)
- bantuan_maximum (RM)
- bantuan_bulanan_default (RM)
- bantuan_kecemasan_maximum (RM)
- bantuan_pendidikan_maximum (RM)
- bantuan_perubatan_maximum (RM)
```

#### C. Workflow Settings
```
- auto_approve_below_amount (RM) - auto approve if below this
- require_document_upload (boolean)
- minimum_documents_required (number)
- approval_levels (1 or 2 levels)
- notification_enabled (boolean)
- email_notification (boolean)
- sms_notification (boolean)
```

#### D. Permohonan Settings
```
- allow_multiple_applications (boolean)
- minimum_days_between_applications (number)
- maximum_applications_per_year (number)
- require_home_visit (boolean)
```

#### E. Agihan Settings
```
- default_payment_method (Tunai/Transfer)
- require_signature (boolean)
- require_photo_evidence (boolean)
- auto_generate_receipt (boolean)
```

#### F. Kategori Asnaf Allocation (%)
```
- fakir_percentage (%)
- miskin_percentage (%)
- amil_percentage (%)
- muallaf_percentage (%)
- riqab_percentage (%)
- gharimin_percentage (%)
- fisabilillah_percentage (%)
- ibnu_sabil_percentage (%)
```

### Features
- ✅ View all settings by category
- ✅ Edit settings (grouped by category)
- ✅ Reset to default
- ✅ Validation (percentages must total 100%)
- ✅ Audit log for changes
- ✅ Import/Export settings

### Views
- `tetapan-zakat/index.blade.php` (all settings in tabs)
- `tetapan-zakat/had-kifayah.blade.php`
- `tetapan-zakat/had-bantuan.blade.php`
- `tetapan-zakat/workflow.blade.php`
- `tetapan-zakat/permohonan.blade.php`
- `tetapan-zakat/agihan.blade.php`
- `tetapan-zakat/kategori-asnaf.blade.php`

---

## 🗂️ DATABASE RELATIONSHIPS

```
asnaf (existing)
  ├── hasMany → permohonan_zakat
  └── hasMany → agihan_zakat

permohonan_zakat
  ├── belongsTo → asnaf
  ├── belongsTo → masjid
  ├── hasOne → agihan_zakat
  ├── belongsTo → disemak_oleh (users)
  └── belongsTo → diluluskan_oleh (users)

agihan_zakat
  ├── belongsTo → asnaf
  ├── belongsTo → masjid
  ├── belongsTo → permohonan_zakat (nullable)
  ├── belongsTo → dibayar_oleh (users)
  └── belongsTo → created_by (users)

tetapan_zakat
  └── belongsTo → masjid
```

---

## 📊 NAVIGATION STRUCTURE

```
Pengurusan
  └── Asnaf
      ├── Senarai Asnaf ✅ (DONE - Phase 1)
      ├── Permohonan Zakat (Phase 2)
      ├── Agihan Zakat (Phase 2)
      ├── Laporan Zakat (Phase 2)
      └── Tetapan Zakat (Phase 2)
```

---

## 🎯 IMPLEMENTATION PRIORITY

### Priority 1 (Core Functionality)
1. **Permohonan Zakat** - Most important, enables workflow
2. **Agihan Zakat** - Second most important, records distribution
3. **Tetapan Zakat** - Needed for had kifayah calculations

### Priority 2 (Reporting)
4. **Laporan Zakat** - Analytics and insights

---

## 💡 ADDITIONAL FEATURES (Optional)

### A. Auto-Calculation Features
- Auto-calculate eligibility based on had kifayah
- Auto-suggest bantuan amount based on pendapatan
- Auto-flag if asnaf exceeds income threshold

### B. Notification System
- Email notification on approval/rejection
- SMS notification for payment ready
- Reminder for pending applications

### C. Document Management
- Scan and upload documents
- Document expiry tracking
- Auto-request document renewal

### D. Integration
- Bank transfer integration (FPX/DuitNow)
- SMS gateway integration
- Email service integration

---

## 🤔 QUESTIONS FOR DISCUSSION

### 1. Permohonan Zakat
- **Q**: Boleh asnaf buat permohonan sendiri atau mesti admin yang create?
- **Q**: Berapa kali setahun asnaf boleh mohon?
- **Q**: Perlu home visit untuk setiap permohonan?
- **Q**: Auto-approve untuk jumlah kecil (contoh: below RM 200)?

### 2. Agihan Zakat
- **Q**: Boleh buat agihan tanpa permohonan (ad-hoc)?
- **Q**: Jenis barangan yang boleh diagihkan (list tetap atau free text)?
- **Q**: Perlu signature digital atau upload gambar tandatangan?
- **Q**: Auto-generate resit atau manual?

### 3. Laporan Zakat
- **Q**: Format laporan yang diperlukan (PDF/Excel/Both)?
- **Q**: Perlu chart atau table sahaja?
- **Q**: Laporan bulanan auto-generate atau on-demand?
- **Q**: Email auto-send laporan ke admin?

### 4. Tetapan Zakat
- **Q**: Had kifayah ikut negeri atau custom per masjid?
- **Q**: Percentage allocation wajib 100% atau boleh flexible?
- **Q**: Perlu approval untuk ubah settings?
- **Q**: Settings apply immediately atau effective date?

### 5. General
- **Q**: Perlu modul Kutipan Zakat juga? (untuk track zakat masuk)
- **Q**: Integration dengan accounting system?
- **Q**: Multi-language support (BM/EN)?
- **Q**: Mobile app atau web sahaja?

---

## 📅 ESTIMATED TIMELINE

### Phase 2A: Permohonan Zakat (3-4 hours)
- Database migration
- Model & Controller
- Views (index, create, edit, show)
- Workflow implementation
- Testing

### Phase 2B: Agihan Zakat (3-4 hours)
- Database migration
- Model & Controller
- Views (index, create, edit, show, resit)
- Payment tracking
- Testing

### Phase 2C: Tetapan Zakat (2-3 hours)
- Database migration
- Settings management
- Views (tabbed interface)
- Validation
- Testing

### Phase 2D: Laporan Zakat (4-5 hours)
- Dashboard design
- Chart integration (Chart.js)
- Multiple report views
- Export functionality
- Testing

**Total Estimated Time**: 12-16 hours for complete Phase 2

---

## ✅ READY TO PROCEED?

Sila confirm:
1. Which menu to implement first?
2. Any changes to the design?
3. Any additional features needed?
4. Answers to the questions above?

Once confirmed, saya akan start implementation mengikut priority yang dipilih.
