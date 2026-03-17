# KEWANGAN MODULE - NEXT STEPS & IMPLEMENTATION GUIDE

## ✅ COMPLETED SO FAR

### 1. Navbar Updated
- ✅ Menu Kewangan simplified (30 → 16 submenu)
- ✅ File: `resources/views/components/double-navbar.blade.php`

### 2. Database Migrations
- ✅ 6 tables created successfully
- ✅ All migrations ran without errors
- ✅ Tables: akaun_bank, kategori_kewangan, transaksi_kewangan, kutipan_dana, perbelanjaan, tetapan_kewangan

### 3. Models Created
- ✅ 6 models generated
- ⏳ Need to add: relationships, scopes, traits, fillable

### 4. Documentation
- ✅ KEWANGAN_MODULE_DESIGN.md (Complete design spec)
- ✅ KEWANGAN_NAVBAR_UPDATE.md (Navbar changes)
- ✅ KEWANGAN_IMPLEMENTATION_PROGRESS.md (Progress tracker)

---

## 📋 REMAINING WORK

### PHASE 2: Complete Models (2-3 hours)

**For each model, add:**
1. Fillable fields
2. Casts
3. Relationships (belongsTo, hasMany, morphTo)
4. Scopes (for filtering)
5. HasMasjidScope trait
6. Soft deletes
7. Audit fields (created_by, updated_by, deleted_by)

**Models to update:**
- AkaunBank
- KategoriKewangan
- TransaksiKewangan
- KutipanDana
- Perbelanjaan
- TetapanKewangan

### PHASE 3: Controllers (3-4 hours)

**Create 6 controllers with CRUD:**
1. AkaunBankController
   - index, create, store, show, edit, update, destroy
   
2. TransaksiKewanganController
   - index (list all)
   - createPendapatan, storePendapatan
   - createPerbelanjaan, storePerbelanjaan
   - show, edit, update, destroy
   
3. KutipanDanaController
   - kutipanKariah, storeKutipanKariah
   - dermaSumbangan, storeDermaSumbangan
   - kutipanZakat, storeKutipanZakat
   - kutipanLain, storeKutipanLain
   - show, edit, update, destroy
   
4. PerbelanjaanController
   - utilitiBil, storeUtilitiBil
   - penyelenggaraan, storePenyelenggaraan
   - gajiElaun, storeGajiElaun
   - perbelanjaanLain, storePerbelanjaanLain
   - show, edit, update, destroy
   
5. LaporanKewanganController
   - index (main report page with tabs)
   - penyataKewangan
   - laporanPendapatan
   - laporanPerbelanjaan
   - aliranTunai
   - bakiBank
   - exportPdf, exportExcel
   
6. TetapanKewanganController
   - index (settings page with tabs)
   - update (save settings)

### PHASE 4: Routes (30 minutes)

**Add to routes/web.php:**
```php
// Akaun Bank
Route::resource('akaun-bank', AkaunBankController::class)
    ->middleware(['auth', 'verified', 'permission:kewangan,read']);

// Transaksi Kewangan
Route::get('/transaksi-kewangan', [TransaksiKewanganController::class, 'index'])
    ->name('transaksi-kewangan.index');
Route::get('/transaksi-kewangan/tambah-pendapatan', [TransaksiKewanganController::class, 'createPendapatan'])
    ->name('transaksi-kewangan.create-pendapatan');
// ... etc

// Kutipan Dana
Route::get('/kutipan-dana/kutipan-kariah', [KutipanDanaController::class, 'kutipanKariah'])
    ->name('kutipan-dana.kutipan-kariah');
// ... etc

// Perbelanjaan
Route::get('/perbelanjaan/utiliti-bil', [PerbelanjaanController::class, 'utilitiBil'])
    ->name('perbelanjaan.utiliti-bil');
// ... etc

// Laporan Kewangan
Route::get('/laporan-kewangan', [LaporanKewanganController::class, 'index'])
    ->name('laporan-kewangan.index');

// Tetapan Kewangan
Route::get('/tetapan-kewangan', [TetapanKewanganController::class, 'index'])
    ->name('tetapan-kewangan.index');
Route::post('/tetapan-kewangan', [TetapanKewanganController::class, 'update'])
    ->name('tetapan-kewangan.update');
```

### PHASE 5: Views (6-8 hours)

**Create ~25 view files:**

1. **Akaun Bank** (4 files)
   - index.blade.php
   - create.blade.php
   - edit.blade.php
   - show.blade.php

2. **Transaksi Kewangan** (5 files)
   - index.blade.php
   - create-pendapatan.blade.php
   - create-perbelanjaan.blade.php
   - edit.blade.php
   - show.blade.php

3. **Kutipan Dana** (4 files)
   - kutipan-kariah.blade.php
   - derma-sumbangan.blade.php
   - kutipan-zakat.blade.php
   - kutipan-lain.blade.php

4. **Perbelanjaan** (4 files)
   - utiliti-bil.blade.php
   - penyelenggaraan.blade.php
   - gaji-elaun.blade.php
   - perbelanjaan-lain.blade.php

5. **Laporan Kewangan** (1 file with tabs)
   - index.blade.php (with 5 tabs)

6. **Tetapan Kewangan** (1 file with tabs)
   - index.blade.php (with tabs for categories & settings)

### PHASE 6: Seeders (1 hour)

**Create 2 seeders:**

1. **KategoriKewanganSeeder**
   - Seed default income categories
   - Seed default expense categories
   - For all 63 masjids

2. **TetapanKewanganSeeder**
   - Seed default settings
   - For all 63 masjids

### PHASE 7: Update Navbar Links (15 minutes)

**Replace `href="#"` with actual routes:**
```blade
<a href="{{ route('akaun-bank.index') }}" ...>
<a href="{{ route('transaksi-kewangan.index') }}" ...>
<a href="{{ route('kutipan-dana.kutipan-kariah') }}" ...>
// ... etc
```

### PHASE 8: Integration (2 hours)

**Integrate with existing modules:**

1. **Agihan Zakat Integration**
   - When Agihan Zakat created → Auto-create TransaksiKewangan
   - Event: AgihanZakatDibayar
   - Listener: CreateKewanganTransaction

2. **Pembayaran Bantuan Integration**
   - When Pembayaran Bantuan created → Auto-create TransaksiKewangan
   - Event: PembayaranBantuanDibayar
   - Listener: CreateKewanganTransaction

### PHASE 9: Testing (2 hours)

**Test all functionality:**
- CRUD operations for all modules
- Multi-masjid data isolation
- Reports generation
- File uploads
- Integration with Agihan Zakat & Pembayaran Bantuan

---

## 🎯 ESTIMATED TIMELINE

| Phase | Task | Time | Status |
|-------|------|------|--------|
| 1 | Navbar & Migrations | 1 hour | ✅ DONE |
| 2 | Models & Relationships | 2-3 hours | ⏳ TODO |
| 3 | Controllers | 3-4 hours | ⏳ TODO |
| 4 | Routes | 30 mins | ⏳ TODO |
| 5 | Views | 6-8 hours | ⏳ TODO |
| 6 | Seeders | 1 hour | ⏳ TODO |
| 7 | Update Navbar Links | 15 mins | ⏳ TODO |
| 8 | Integration | 2 hours | ⏳ TODO |
| 9 | Testing | 2 hours | ⏳ TODO |
| **TOTAL** | | **18-22 hours** | **5% DONE** |

---

## 📝 QUICK START GUIDE (To Continue)

### Step 1: Complete Models
```bash
# Edit each model file and add:
# - Fillable fields
# - Relationships
# - Scopes
# - Traits (HasMasjidScope, SoftDeletes)
```

### Step 2: Create Controllers
```bash
php artisan make:controller AkaunBankController --resource
php artisan make:controller TransaksiKewanganController
php artisan make:controller KutipanDanaController
php artisan make:controller PerbelanjaanController
php artisan make:controller LaporanKewanganController
php artisan make:controller TetapanKewanganController
```

### Step 3: Add Routes
```bash
# Edit routes/web.php
# Add all Kewangan routes with permissions
```

### Step 4: Create Views
```bash
# Create folder structure:
mkdir -p resources/views/akaun-bank
mkdir -p resources/views/transaksi-kewangan
mkdir -p resources/views/kutipan-dana
mkdir -p resources/views/perbelanjaan
mkdir -p resources/views/laporan-kewangan
mkdir -p resources/views/tetapan-kewangan

# Create view files following Asnaf/Kebajikan pattern
```

### Step 5: Create Seeders
```bash
php artisan make:seeder KategoriKewanganSeeder
php artisan make:seeder TetapanKewanganSeeder
```

### Step 6: Run Seeders
```bash
php artisan db:seed --class=KategoriKewanganSeeder
php artisan db:seed --class=TetapanKewanganSeeder
```

### Step 7: Test Everything
```bash
# Manual testing in browser
# Check all CRUD operations
# Check multi-masjid isolation
# Check reports
```

---

## 🚀 PRIORITY ORDER

**If time is limited, implement in this order:**

1. **HIGH PRIORITY** (Core functionality):
   - Akaun Bank (master data)
   - Transaksi Kewangan (quick add)
   - Laporan Kewangan (basic reports)

2. **MEDIUM PRIORITY** (Detailed tracking):
   - Kutipan Dana
   - Perbelanjaan
   - Tetapan Kewangan

3. **LOW PRIORITY** (Nice to have):
   - Advanced reports
   - Integration with other modules
   - Export PDF/Excel

---

## 📌 NOTES

- Follow exact pattern from Asnaf & Kebajikan modules
- Use existing components from `resources/views/components/`
- Maintain UI/UX standards (Poppins font, 10-14px, 4-8px border radius)
- Ensure multi-masjid data isolation
- Test thoroughly before deployment

---

**Last Updated**: 13 Dec 2025 03:20 AM
**Status**: Phase 1 complete, ready for Phase 2
**Next Action**: Complete models with relationships and scopes

