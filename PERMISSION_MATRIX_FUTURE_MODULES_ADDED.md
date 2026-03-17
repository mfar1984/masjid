# Permission Matrix - Future Modules Added

## Summary
Added placeholder modules in permission matrix for Operasi, Aset, Komunikasi, and Fail sections that don't have pages yet. This ensures permissions are ready when pages are built later.

## Problem Statement
User noticed that permission matrix (Senarai Kumpulan) was missing modules for:
- **Operasi** - Program & Pendidikan, Fasiliti & Tempahan, Pengurusan Jenazah
- **Aset** - All asset management submenus
- **Komunikasi** - Siaran Mesej, Kandungan Website, Pengumuman & Berita
- **Fail** - Perpustakaan Digital, Arkib & Rekod

These modules exist in navbar but not in permission matrix.

---

## Changes Made

### 1. Added Operasi Modules

```php
'operasi_header' => 'Operasi',
'program_pendidikan' => '├─ Program & Pendidikan',
'fasiliti_tempahan' => '├─ Fasiliti & Tempahan',
'pengurusan_jenazah' => '└─ Pengurusan Jenazah',
```

**Module Types:**
- `operasi_header` - Header only (no checkbox)
- `program_pendidikan` - Full CRUD module (when page built)
- `fasiliti_tempahan` - Full CRUD module (when page built)
- `pengurusan_jenazah` - Full CRUD module (when page built)

---

### 2. Added Aset Modules (Complete Hierarchy)

```php
'aset_header' => 'Aset',
'pengurusan_aset' => '├─ Pengurusan Aset',
'senarai_aset' => '│  ├─ Senarai Aset',
'kategori_aset' => '│  ├─ Kategori Aset',
'pemindahan_aset' => '│  ├─ Pemindahan Aset',
'pergerakan_aset' => '│  └─ Pergerakan Aset',
'penyelenggaraan_aset' => '├─ Penyelenggaraan',
'jadual_penyelenggaraan' => '│  ├─ Jadual Penyelenggaraan',
'kerja_penyelenggaraan' => '│  ├─ Kerja Penyelenggaraan',
'laporan_penyelenggaraan_aset' => '│  └─ Laporan Penyelenggaraan',
'penyusutan_nilai' => '├─ Penyusutan & Nilai',
'jadual_penyusutan' => '│  ├─ Jadual Penyusutan',
'nilai_semasa' => '│  ├─ Nilai Semasa',
'trend_penyusutan' => '│  └─ Trend Penyusutan',
'pelupusan_aset' => '├─ Pelupusan Aset',
'permohonan_pelupusan' => '│  ├─ Permohonan Pelupusan',
'kelulusan_pelupusan' => '│  ├─ Kelulusan Pelupusan',
'rekod_pelupusan' => '│  └─ Rekod Pelupusan',
'laporan_aset' => '└─ Laporan Aset',
'laporan_inventori' => '   ├─ Laporan Inventori',
'laporan_penyelenggaraan' => '   ├─ Laporan Penyelenggaraan',
'laporan_lokasi' => '   ├─ Laporan Lokasi',
'dashboard_aset' => '   └─ Dashboard Aset',
```

**Module Types:**
- `aset_header` - Header only (no checkbox)
- `pengurusan_aset` - Submenu header (no checkbox)
- `senarai_aset`, `kategori_aset`, etc. - Full CRUD modules (when pages built)
- `penyelenggaraan_aset` - Submenu header (no checkbox)
- `penyusutan_nilai` - Submenu header (no checkbox)
- `pelupusan_aset` - Submenu header (no checkbox)
- `laporan_aset` - Submenu header (no checkbox)
- `laporan_inventori`, `dashboard_aset`, etc. - View-only modules (when pages built)

**Visual Hierarchy:**
- Used `│` for vertical lines in nested submenus
- Used `├─` for middle items
- Used `└─` for last items
- Used proper indentation with spaces

---

### 3. Added Komunikasi Modules

```php
'komunikasi_header' => 'Komunikasi',
'siaran_mesej' => '├─ Siaran Mesej',
'kandungan_website' => '├─ Kandungan Website',
'pengumuman_berita' => '└─ Pengumuman & Berita',
```

**Module Types:**
- `komunikasi_header` - Header only (no checkbox)
- `siaran_mesej` - Full CRUD module (when page built)
- `kandungan_website` - Full CRUD module (when page built)
- `pengumuman_berita` - Full CRUD module (when page built)

---

### 4. Updated Fail Modules

**Before:**
```php
'fail' => 'Fail',
'documents' => '└─ Pengurusan Dokumen',
```

**After:**
```php
'fail_header' => 'Fail',
'documents' => '├─ Pengurusan Dokumen',
'perpustakaan_digital' => '├─ Perpustakaan Digital',
'arkib_rekod' => '└─ Arkib & Rekod',
```

**Changes:**
- Changed `fail` to `fail_header` for consistency
- Changed `documents` from `└─` to `├─` (no longer last item)
- Added `perpustakaan_digital` - Full CRUD module (when page built)
- Added `arkib_rekod` - Full CRUD module (when page built)

---

### 5. Updated Header Modules List

Added new headers to `getHeaderModules()`:

```php
'operasi_header', // Operasi - header only
'aset_header', // Aset - header only
'pengurusan_aset', // Pengurusan Aset - submenu header
'penyelenggaraan_aset', // Penyelenggaraan - submenu header
'penyusutan_nilai', // Penyusutan & Nilai - submenu header
'pelupusan_aset', // Pelupusan Aset - submenu header
'laporan_aset', // Laporan Aset - submenu header
'komunikasi_header', // Komunikasi - header only
'fail_header', // Fail - header only (changed from 'fail')
```

**Purpose:** These modules will NOT have checkboxes in permission matrix - they are visual headers only.

---

## Permission Matrix Structure (Complete)

```
Dashboard
Kariah
├─ Senarai Kariah
├─ Kategori Kariah
└─ Laporan Kariah

Ahli Jawatankuasa Masjid (Header)
├─ Senarai AJK
├─ Laporan AJK
└─ Arkib AJK

Asnaf (Header)
├─ Senarai Asnaf
├─ Permohonan Zakat
├─ Agihan Zakat
├─ Laporan Zakat
└─ Tetapan Asnaf (Header for TABs)
   ├─ Had Kifayah
   ├─ Had Bantuan
   ├─ Workflow
   ├─ Permohonan
   ├─ Kategori
   ├─ Payment Gateway
   └─ Display

Kebajikan (Header)
├─ Program Kebajikan
├─ Penerima Bantuan
├─ Permohonan Bantuan
├─ Pembayaran Bantuan
├─ Laporan Kebajikan
└─ Tetapan Kebajikan (Header for TABs)
   ├─ Had Bantuan
   ├─ Workflow
   ├─ Permohonan
   ├─ Kategori Penerima
   ├─ Pembayaran
   ├─ Paparan
   └─ Kategori

Kewangan (Header)
├─ Akaun Bank
├─ Transaksi Kewangan
├─ Laporan Kewangan
└─ Tetapan Kewangan (Header for TABs)
   ├─ Kategori
   └─ Paparan

Operasi (Header) ⭐ NEW
├─ Program & Pendidikan
├─ Fasiliti & Tempahan
└─ Pengurusan Jenazah

Aset (Header) ⭐ NEW
├─ Pengurusan Aset (Submenu Header)
│  ├─ Senarai Aset
│  ├─ Kategori Aset
│  ├─ Pemindahan Aset
│  └─ Pergerakan Aset
├─ Penyelenggaraan (Submenu Header)
│  ├─ Jadual Penyelenggaraan
│  ├─ Kerja Penyelenggaraan
│  └─ Laporan Penyelenggaraan
├─ Penyusutan & Nilai (Submenu Header)
│  ├─ Jadual Penyusutan
│  ├─ Nilai Semasa
│  └─ Trend Penyusutan
├─ Pelupusan Aset (Submenu Header)
│  ├─ Permohonan Pelupusan
│  ├─ Kelulusan Pelupusan
│  └─ Rekod Pelupusan
└─ Laporan Aset (Submenu Header)
   ├─ Laporan Inventori
   ├─ Laporan Penyelenggaraan
   ├─ Laporan Lokasi
   └─ Dashboard Aset

Komunikasi (Header) ⭐ NEW
├─ Siaran Mesej
├─ Kandungan Website
└─ Pengumuman & Berita

Fail (Header) ⭐ UPDATED
├─ Pengurusan Dokumen
├─ Perpustakaan Digital ⭐ NEW
└─ Arkib & Rekod ⭐ NEW

Pentadbiran Sistem
├─ Tetapan Umum
├─ Senarai Masjid
├─ Senarai Pengguna
└─ Senarai Kumpulan

Integrasi (Header)
├─ Email (SMTP)
├─ Cuaca
└─ API
```

---

## Module Counts

### Before
- Total modules: ~60
- Missing: Operasi (3), Aset (19), Komunikasi (3), Fail (2)

### After
- Total modules: ~87
- Added: 27 new modules
- All navbar items now have corresponding permission entries

---

## Benefits

✅ **Future-Proof**
- Permissions ready when pages are built
- No need to update permission matrix later
- Consistent structure from start

✅ **Complete Navbar Coverage**
- Every navbar menu item has permission entry
- No orphaned menu items
- Clear permission requirements

✅ **Proper Hierarchy**
- Visual tree structure with ASCII characters
- Clear parent-child relationships
- Easy to understand at a glance

✅ **Flexible Access Control**
- Can assign permissions before pages exist
- Users ready when features launch
- Gradual rollout possible

✅ **Consistent Naming**
- All headers end with `_header`
- Submenu headers clearly identified
- Module names match navbar labels

---

## When Pages Are Built

When developers build these pages later, they need to:

1. **Create Routes** with proper permission middleware:
   ```php
   Route::get('program-pendidikan', [ProgramPendidikanController::class, 'index'])
       ->middleware('permission:program_pendidikan,read');
   ```

2. **Create Controllers** with masjid scope:
   ```php
   $query = ProgramPendidikan::query();
   if (!$isSuperAdmin) {
       $query->where('masjid_id', $user->masjid_id);
   }
   ```

3. **Create Models** with HasMasjidScope trait:
   ```php
   use HasMasjidScope, SoftDeletes;
   ```

4. **Update Views** with permission checks:
   ```php
   @if(auth()->user()->hasPermission('program_pendidikan', 'create'))
       <button>Tambah</button>
   @endif
   ```

---

## Files Modified

### Controllers (1 file)
1. `app/Http/Controllers/RoleController.php`
   - Added 27 new modules to `getAvailableModules()`
   - Updated `getHeaderModules()` with new headers
   - Changed `fail` to `fail_header`

---

## Status: ✅ COMPLETE

Permission matrix now includes all modules from navbar, even those without pages yet. When pages are built, permissions are already in place and ready to use.
