# MODUL ASNAF - PHASE 2 FINAL DESIGN (UPDATED)

## 📋 IMPLEMENTATION ORDER (CONFIRMED)

1. **Tetapan Asnaf** ✅ (START HERE)
2. **Permohonan Zakat**
3. **Agihan Zakat**
4. **Laporan Zakat**
5. **Kutipan Zakat** (Future - with Chip-Asia integration)

---

## 1️⃣ TETAPAN ASNAF (PRIORITY 1)

### Purpose
Konfigurasi sistem zakat untuk setiap masjid termasuk had kifayah, had bantuan, workflow settings, dan payment gateway integration.

### Database: `tetapan_asnaf`

```php
- id
- masjid_id (multi-tenant)
- setting_key (unique per masjid)
- setting_value (text/json)
- setting_type (string, number, boolean, json, encrypted)
- category (had_kifayah, had_bantuan, workflow, payment_gateway, etc)
- description
- is_active (boolean)
- created_by
- updated_by
- created_at
- updated_at
```

### Settings Categories

#### A. Had Kifayah (Custom per Masjid)
```
✅ had_kifayah_individu (RM)
✅ had_kifayah_keluarga_2 (RM)
✅ had_kifayah_keluarga_3 (RM)
✅ had_kifayah_keluarga_4 (RM)
✅ had_kifayah_keluarga_5 (RM)
✅ had_kifayah_tambahan_per_orang (RM)
✅ formula_calculation (auto/manual)
```

#### B. Had Bantuan
```
✅ bantuan_minimum (RM)
✅ bantuan_maximum (RM)
✅ bantuan_bulanan_default (RM)
✅ bantuan_kecemasan_maximum (RM)
✅ bantuan_pendidikan_maximum (RM)
✅ bantuan_perubatan_maximum (RM)
✅ bantuan_sekali_maximum (RM)
```

#### C. Workflow Settings
```
✅ require_mesyuarat_attachment (boolean) - WAJIB TRUE
✅ auto_approve_enabled (boolean) - WAJIB FALSE
✅ approval_levels (1 or 2)
✅ require_document_upload (boolean)
✅ minimum_documents_required (number)
✅ notification_enabled (boolean)
✅ email_notification (boolean)
✅ sms_notification (boolean)
```

#### D. Permohonan Settings
```
✅ allow_multiple_applications (boolean) - TRUE
✅ maximum_applications_per_year (number) - 0 = unlimited
✅ minimum_days_between_applications (number)
✅ require_home_visit (boolean)
✅ allow_adhoc_agihan (boolean) - TRUE (emergency cases)
```

#### E. Kategori Asnaf Allocation (Flexible %)
```
✅ fakir_percentage (%)
✅ miskin_percentage (%)
✅ amil_percentage (%)
✅ muallaf_percentage (%)
✅ riqab_percentage (%)
✅ gharimin_percentage (%)
✅ fisabilillah_percentage (%)
✅ ibnu_sabil_percentage (%)
✅ total_must_be_100 (boolean) - FALSE (flexible)
```

#### F. Payment Gateway Integration (Chip-Asia)
```
✅ payment_gateway_enabled (boolean)
✅ payment_gateway_provider (chip-asia)
✅ chip_asia_brand_id (encrypted)
✅ chip_asia_api_key (encrypted)
✅ chip_asia_secret_key (encrypted)
✅ chip_asia_webhook_url (auto-generated)
✅ chip_asia_test_mode (boolean)
✅ bank_account_name (string)
✅ bank_account_number (string)
✅ bank_name (string)
✅ bank_swift_code (string)
```

#### G. Display Settings (for Public Website)
```
✅ show_on_public_website (boolean)
✅ accept_online_donations (boolean)
✅ donation_page_title (string)
✅ donation_page_description (text)
✅ donation_banner_image (file)
✅ minimum_donation_amount (RM)
```

### Views Structure
```
tetapan-asnaf/
  ├── index.blade.php (tabbed interface)
  │   ├── Tab 1: Had Kifayah
  │   ├── Tab 2: Had Bantuan
  │   ├── Tab 3: Workflow
  │   ├── Tab 4: Permohonan
  │   ├── Tab 5: Kategori Asnaf
  │   ├── Tab 6: Payment Gateway
  │   └── Tab 7: Display Settings
  └── components/
      ├── had-kifayah-form.blade.php
      ├── had-bantuan-form.blade.php
      ├── workflow-form.blade.php
      ├── permohonan-form.blade.php
      ├── kategori-asnaf-form.blade.php
      ├── payment-gateway-form.blade.php
      └── display-settings-form.blade.php
```

---

## 2️⃣ PERMOHONAN ZAKAT

### Updated Requirements
- ✅ **Admin sahaja** boleh create (user account)
- ✅ **Unlimited applications** per year (configurable in Tetapan)
- ✅ **NO auto-approve** - semua perlu approval
- ✅ **WAJIB attachment mesyuarat** untuk approval

### Database: `permohonan_zakat`

```php
- id
- asnaf_id (FK to asnaf)
- masjid_id (multi-tenant)
- no_permohonan (auto: PZ-2025-0001)
- tarikh_permohonan
- jenis_bantuan (Tunai, Barangan, Pendidikan, Perubatan, Kecemasan)
- kategori_bantuan (Bulanan, Sekali, Khas)
- jumlah_dipohon (decimal)
- sebab_permohonan (text)
- dokumen_sokongan_path (file)

// Workflow
- status (Menunggu, Dalam Semakan, Diluluskan, Ditolak, Dibatalkan)
- tarikh_semakan
- disemak_oleh (FK to users)
- catatan_semakan (text)

// Approval (WAJIB attachment mesyuarat)
- tarikh_kelulusan
- diluluskan_oleh (FK to users)
- jumlah_diluluskan (decimal)
- catatan_kelulusan (text)
- minit_mesyuarat_path (file) - WAJIB for approval
- tarikh_mesyuarat (date) - WAJIB for approval
- no_mesyuarat (string) - WAJIB for approval

// Rejection
- sebab_penolakan (text)
- tarikh_penolakan

// Audit
- created_by
- updated_by
- created_at
- updated_at
- deleted_at
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Permohonan**
1. Pilih Asnaf (dropdown with search - from asnaf table)
2. Tarikh Permohonan (date)
3. Jenis Bantuan (dropdown: Tunai, Barangan, Pendidikan, Perubatan, Kecemasan)
4. Kategori Bantuan (dropdown: Bulanan, Sekali, Khas)
5. Jumlah Dipohon (RM)
6. Sebab Permohonan (textarea)
7. Dokumen Sokongan (file upload - optional)

**Section 2: Maklumat Asnaf (Auto-display from selected asnaf)**
- Nama
- No. IC
- Kategori Asnaf
- Pendapatan Bulanan
- Bilangan Tanggungan
- Status Semasa

**Approval Form (Separate - for Admin only)**
1. Jumlah Diluluskan (RM)
2. Tarikh Mesyuarat (date) - WAJIB
3. No. Mesyuarat (text) - WAJIB
4. Minit Mesyuarat (file upload) - WAJIB
5. Catatan Kelulusan (textarea)

**Rejection Form**
1. Sebab Penolakan (textarea) - WAJIB

---

## 3️⃣ AGIHAN ZAKAT

### Updated Requirements
- ✅ **Boleh dengan/tanpa permohonan** (ad-hoc/emergency)
- ✅ **Jenis barangan: free text**
- ✅ **Signature: upload gambar**

### Database: `agihan_zakat`

```php
- id
- permohonan_zakat_id (FK, nullable - for ad-hoc)
- asnaf_id (FK to asnaf)
- masjid_id (multi-tenant)
- no_agihan (auto: AZ-2025-0001)
- tarikh_agihan
- jenis_agihan (Tunai, Cek, Bank Transfer, Barangan, Baucar)
- kategori_asnaf (from asnaf)
- jumlah_agihan (decimal)

// Payment Details
- cara_bayaran (Tunai, Cek, Transfer, Barangan)
- no_rujukan (cek/transfer number)
- bank_name (if transfer)
- account_number (if transfer)

// Barangan (if jenis_agihan = Barangan)
- senarai_barangan (text) - FREE TEXT
- jumlah_nilai_barangan (decimal)

// Status & Confirmation
- status (Belum Bayar, Sudah Bayar, Dibatalkan)
- tarikh_bayaran
- dibayar_oleh (FK to users)
- penerima_nama (confirmation)
- penerima_ic (confirmation)
- penerima_tandatangan_path (file) - UPLOAD GAMBAR
- resit_path (file)
- bukti_bayaran_path (file)

// Ad-hoc/Emergency
- is_adhoc (boolean)
- sebab_adhoc (text) - if is_adhoc = true
- kelulusan_adhoc_oleh (FK to users)
- kelulusan_adhoc_path (file) - attachment approval

// Audit
- created_by
- updated_by
- created_at
- updated_at
- deleted_at
```

### Form Fields (Create/Edit)

**Section 1: Jenis Agihan**
1. Jenis Agihan (radio: Dari Permohonan / Ad-hoc/Emergency)

**If "Dari Permohonan":**
2. Pilih Permohonan (dropdown - permohonan yang diluluskan)
   - Auto-fill: Asnaf, Jumlah Diluluskan, Kategori

**If "Ad-hoc/Emergency":**
2. Pilih Asnaf (dropdown with search)
3. Sebab Ad-hoc (textarea) - WAJIB
4. Dokumen Kelulusan (file upload) - WAJIB
5. Diluluskan Oleh (dropdown users)

**Section 2: Maklumat Agihan**
6. Tarikh Agihan (date)
7. Jenis Agihan (dropdown: Tunai, Cek, Bank Transfer, Barangan, Baucar)
8. Jumlah Agihan (RM)

**Section 3: Cara Bayaran**
9. Cara Bayaran (dropdown: Tunai, Cek, Transfer, Barangan)

**If Cek/Transfer:**
10. No. Rujukan (text)
11. Nama Bank (text)
12. No. Akaun (text)

**If Barangan:**
10. Senarai Barangan (textarea) - FREE TEXT
11. Jumlah Nilai Barangan (RM)

**Section 4: Pengesahan Penerimaan**
12. Nama Penerima (text)
13. No. IC Penerima (text)
14. Tandatangan Penerima (file upload - gambar)
15. Bukti Bayaran (file upload - optional)

---

## 4️⃣ LAPORAN ZAKAT

### Dashboard Sections

**A. Overview Cards (6 cards)**
1. Total Asnaf Berdaftar
2. Total Permohonan (Bulan Ini)
3. Total Agihan (Bulan Ini)
4. Jumlah Diagihkan (RM - Bulan Ini)
5. Permohonan Menunggu
6. Agihan Belum Bayar

**B. Charts**
1. Pie Chart: Agihan by Kategori Asnaf (8 categories)
2. Bar Chart: Permohonan vs Agihan (6 bulan terakhir)
3. Line Chart: Trend Agihan (12 bulan)
4. Donut Chart: Jenis Bantuan

**C. Report Sections**
1. Laporan Asnaf
   - By Kategori
   - By Status
   - By Pendapatan Range
   - Demographics

2. Laporan Permohonan
   - By Status
   - By Jenis Bantuan
   - By Bulan/Tahun
   - Approval Rate

3. Laporan Agihan
   - By Kategori Asnaf
   - By Jenis Agihan
   - By Cara Bayaran
   - Top Recipients

4. Laporan Kewangan
   - Total Agihan by Kategori Asnaf
   - Monthly Breakdown
   - Yearly Summary

**D. Filters**
- Tarikh Dari/Hingga
- Kategori Asnaf
- Jenis Bantuan
- Status
- Masjid (Super Admin only)

**E. Export**
- PDF (formatted report)
- Excel/CSV
- Print View

---

## 5️⃣ KUTIPAN ZAKAT (FUTURE - Phase 3)

### Integration with Chip-Asia

**Purpose**: Track zakat masuk dari pembayar melalui online payment

### Database: `kutipan_zakat`

```php
- id
- masjid_id
- no_kutipan (auto: KZ-2025-0001)
- tarikh_kutipan
- nama_pembayar
- no_ic_pembayar (optional)
- telefon_pembayar
- email_pembayar
- jumlah_kutipan (decimal)
- jenis_zakat (Fitrah, Harta, Pendapatan, Perniagaan, Emas, Saham, KWSP)
- cara_bayaran (Tunai, Cek, Transfer, Online)

// Online Payment (Chip-Asia)
- payment_method (fpx, card, ewallet)
- payment_status (pending, success, failed)
- chip_asia_payment_id
- chip_asia_transaction_id
- chip_asia_receipt_url
- payment_response (json)

// Receipt
- resit_path (auto-generated)
- resit_number (auto)

// Audit
- created_by
- created_at
- updated_at
```

### Chip-Asia Integration Flow

```
1. User access public donation page
2. Fill form (nama, email, telefon, jumlah)
3. Click "Bayar Sekarang"
4. Redirect to Chip-Asia payment page
5. User complete payment (FPX/Card/E-wallet)
6. Chip-Asia webhook callback
7. System update payment status
8. Auto-generate receipt
9. Email receipt to user
10. Update kutipan_zakat table
```

### Public Donation Page (Future Website)

**URL**: `https://emasjid.com/donate/{masjid-slug}`

**Display if**:
- `payment_gateway_enabled = true`
- `show_on_public_website = true`
- `accept_online_donations = true`
- Chip-Asia credentials configured

**Form Fields**:
1. Nama Pembayar
2. No. IC (optional)
3. Telefon
4. Email
5. Jenis Zakat (dropdown)
6. Jumlah (RM) - minimum from settings
7. Catatan (optional)

---

## 📊 COMPLETE NAVIGATION STRUCTURE

```
Pengurusan
  └── Asnaf
      ├── Senarai Asnaf ✅ (Phase 1 - DONE)
      ├── Permohonan Zakat (Phase 2)
      ├── Agihan Zakat (Phase 2)
      ├── Laporan Zakat (Phase 2)
      └── Tetapan Asnaf (Phase 2 - START HERE)

Kewangan (Future)
  └── Kutipan Zakat (Phase 3)
      ├── Senarai Kutipan
      ├── Laporan Kutipan
      └── Tetapan Payment Gateway
```

---

## 🎯 IMPLEMENTATION PLAN

### Phase 2A: Tetapan Asnaf (2-3 hours) ✅ START
- Migration
- Model & Controller
- Tabbed interface view
- Validation & encryption for API keys
- Testing

### Phase 2B: Permohonan Zakat (3-4 hours)
- Migration
- Model & Controller
- Views with approval workflow
- Mesyuarat attachment validation
- Testing

### Phase 2C: Agihan Zakat (3-4 hours)
- Migration
- Model & Controller
- Views with ad-hoc support
- Resit generation
- Testing

### Phase 2D: Laporan Zakat (4-5 hours)
- Dashboard with charts
- Multiple report views
- Export functionality
- Testing

### Phase 3: Kutipan Zakat (6-8 hours)
- Migration
- Chip-Asia integration
- Public donation page
- Webhook handling
- Receipt generation
- Testing

---

## ✅ READY TO START: TETAPAN ASNAF

Saya akan mulakan dengan **Tetapan Asnaf** sekarang. Confirm untuk proceed?
