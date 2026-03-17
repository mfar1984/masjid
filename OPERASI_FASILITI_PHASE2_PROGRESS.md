# OPERASI FASILITI & TEMPAHAN - PHASE 2 PROGRESS

**Date**: 15 December 2025
**Phase**: 2 - Views & UI  
**Status**: IN PROGRESS (23% Complete)
**Token Usage**: ~144K / 200K (72%)

---

## ✅ COMPLETED (3/13 views - 23%)

### Senarai Fasiliti (3/4 views complete)
- [x] `resources/views/senarai-fasiliti/index.blade.php` ✅
- [x] `resources/views/senarai-fasiliti/create.blade.php` ✅
- [x] `resources/views/senarai-fasiliti/edit.blade.php` ✅
- [ ] `resources/views/senarai-fasiliti/show.blade.php` ⏳ NEXT

---

## 🔄 REMAINING WORK (10/13 views - 77%)

### Senarai Fasiliti (1 view remaining)
- [ ] show.blade.php

### Tempahan Fasiliti (4 views)
- [ ] index.blade.php
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] show.blade.php (with workflow buttons)

### Pembayaran Sewa (4 views)
- [ ] index.blade.php
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] show.blade.php

### Laporan Tempahan (1 view)
- [ ] index.blade.php (with charts)

### Navbar Update (1 task)
- [ ] Update `resources/views/components/double-navbar.blade.php`
  - Add "Operasi" menu
  - Add "Fasiliti & Tempahan" submenu with 4 links

---

## 📋 VIEWS CREATED SO FAR

### 1. Senarai Fasiliti Index ✅
**File**: `resources/views/senarai-fasiliti/index.blade.php`
**Features**:
- 4 stats cards (Total Fasiliti, Tersedia, Tidak Tersedia, Tempahan Bulan Ini)
- Search & filters (jenis, status)
- Desktop table view (7 columns)
- Mobile card view
- Pagination
- Delete modal
- Permission checks

### 2. Senarai Fasiliti Create ✅
**File**: `resources/views/senarai-fasiliti/create.blade.php`
**Features**:
- 6 sections with blue background:
  1. Maklumat Fasiliti (nama, jenis, kategori, aset)
  2. Kapasiti & Spesifikasi (kapasiti, luas, kemudahan)
  3. Harga Sewa (sejam, separuh hari, sehari, deposit)
  4. Syarat & Peraturan (syarat, peraturan, had min/max)
  5. Gambar & Dokumen (optional uploads)
  6. Status & Catatan
- Dynamic aset field (show/hide based on jenis)
- File upload support
- Form validation
- Poppins font 10-14px
- Border radius 4-8px

### 3. Senarai Fasiliti Edit ✅
**File**: `resources/views/senarai-fasiliti/edit.blade.php`
**Features**:
- Same structure as create
- Pre-filled with existing data
- Kod fasiliti readonly
- Dynamic aset field
- Update button

---

## 🎯 NEXT STEPS (Priority Order)

### IMMEDIATE (Session Seterusnya)
1. **Complete Senarai Fasiliti** (1 view)
   - Create `show.blade.php` with:
     - 6 information sections
     - Link to aset (if jenis=Aset)
     - List of tempahan history
     - Edit/Delete buttons
     - Audit info

2. **Create Tempahan Fasiliti Views** (4 views)
   - `index.blade.php`: Stats, filters, table
   - `create.blade.php`: 6 sections (Penyewa, Tempahan, Tujuan, Harga, Dokumen, Catatan)
   - `edit.blade.php`: Same as create
   - `show.blade.php`: WITH WORKFLOW BUTTONS (Semak, Lulus, Tolak, Batal, Selesai)

3. **Create Pembayaran Sewa Views** (4 views)
   - `index.blade.php`: Stats, filters, table
   - `create.blade.php`: 6 sections (Pembayaran, Bank, Cek, Dokumen, Deposit Return, Status)
   - `edit.blade.php`: Same as create
   - `show.blade.php`: Payment details, bank/cek info

4. **Create Laporan Tempahan View** (1 view)
   - `index.blade.php`: Filters, 8 stats cards, 5 charts, table

5. **Update Navbar** (1 task)
   - Add Operasi menu with dropdown

---

## 📖 REFERENCE PATTERNS TO FOLLOW

### For Show Pages
Pattern: `resources/views/senarai-aset/show.blade.php`
- Multiple information sections with blue background
- Related data (relationships)
- Action buttons (Edit, Delete)
- Audit information (created_by, updated_by)

### For Tempahan Show Page (IMPORTANT!)
**Workflow Buttons** based on status:
```php
@if($tempahan->status_tempahan == 'Baharu')
    <button>Semak</button>
@endif

@if($tempahan->status_tempahan == 'Dalam Semakan')
    <button>Lulus</button>
@endif

@if(in_array($tempahan->status_tempahan, ['Baharu', 'Dalam Semakan']))
    <button>Tolak</button>
@endif

@if(!in_array($tempahan->status_tempahan, ['Lulus', 'Ditolak', 'Dibatalkan', 'Selesai']))
    <button>Batal</button>
@endif

@if($tempahan->status_tempahan == 'Lulus' && $tempahan->tarikh_tamat < today())
    <button>Tandakan Selesai</button>
@endif
```

### For Laporan Page
Pattern: `resources/views/laporan-kebajikan/index.blade.php`
- Filter section (search, dropdowns, date range)
- Stats cards (2 rows, 4 cards each)
- Charts using Chart.js:
  - Pie chart (status distribution)
  - Bar chart (by category)
  - Line chart (monthly trend)
- Table with data
- Pagination

---

## 🎨 UI/UX STANDARDS (MUST FOLLOW)

### Font (Poppins)
- Headings: 14px bold (text-xl)
- Body: 12px regular (text-xs)
- Small: 10px regular (text-[10px])

### Border Radius
- Cards: 8px (rounded-lg)
- Buttons: 6px (rounded)
- Inputs: 4px (rounded-sm)
- Badges: 4px (rounded-sm)

### Colors
- Primary: Blue (#3B82F6) - bg-blue-600
- Success: Green (#10B981) - bg-green-600
- Warning: Orange (#F59E0B) - bg-orange-600
- Danger: Red (#EF4444) - bg-red-600

### Spacing
- Section padding: p-4
- Gap between elements: gap-3, gap-4
- Margin bottom: mb-4, mb-6

---

## 🔗 CONTROLLER METHODS REFERENCE

### SenariFasilitiController
- index() - with stats
- create() - with senariAset list
- store() - with file upload
- show() - with relationships
- edit() - with senariAset list
- update()
- destroy()

### TempahanFasilitiController
- index() - with stats
- create() - with senariFasiliti list
- store()
- show() - with relationships
- edit()
- update()
- destroy()
- **semak()** - workflow
- **lulus()** - workflow + create pembayaran
- **tolak()** - workflow
- **batal()** - workflow
- **selesai()** - workflow + update aset

### PembayaranSewaController
- index() - with stats
- create() - with tempahanFasiliti list
- store() - create kutipan dana if paid
- show() - with relationships
- edit()
- update() - create kutipan dana if status changed to paid
- destroy()

### LaporanTempahanController
- index() - with stats, charts data
- export() - PDF/Excel
- print() - Print view

---

## ⚠️ IMPORTANT NOTES

### File Uploads
- All file uploads are OPTIONAL
- Max size: 5MB
- Formats: JPG, PNG, PDF
- Store in: `storage/app/public/fasiliti/`

### Auto-Generate Fields
- `kod_fasiliti` - auto-generated in controller
- `no_tempahan` - auto-generated in controller
- `no_pembayaran` - auto-generated in controller

### Workflow Integration
- Tempahan Lulus → Create Pembayaran Sewa (auto)
- Tempahan Lulus → Create Pergerakan Aset (if jenis=Aset)
- Pembayaran Sudah Bayar → Create Kutipan Dana (auto)
- Tempahan Selesai → Update Pergerakan Aset status

### Permission Checks
```php
@if(auth()->user()->hasPermission('fasiliti', 'create'))
@if(auth()->user()->hasPermission('fasiliti', 'update'))
@if(auth()->user()->hasPermission('fasiliti', 'delete'))
```

---

## 📊 PROGRESS SUMMARY

**Phase 2 Overall**: 23% Complete (3/13 views)

| Module | Progress | Status |
|--------|----------|--------|
| Senarai Fasiliti | 75% (3/4) | 🟡 In Progress |
| Tempahan Fasiliti | 0% (0/4) | ⚪ Not Started |
| Pembayaran Sewa | 0% (0/4) | ⚪ Not Started |
| Laporan Tempahan | 0% (0/1) | ⚪ Not Started |
| Navbar Update | 0% (0/1) | ⚪ Not Started |

**Estimated Time Remaining**: 6-7 hours

---

## 🚀 RECOMMENDATION

**Token usage sudah 72%**. Cadangan:
1. Buat summary lengkap (DONE ✅)
2. Buka session baharu untuk sambung
3. Session baharu fokus pada:
   - Complete senarai-fasiliti show
   - All tempahan-fasiliti views (4 views)
   - All pembayaran-sewa views (4 views)
   - Laporan-tempahan view (1 view)
   - Navbar update

**Files to read in new session**:
- `OPERASI_FASILITI_PHASE2_PROGRESS.md` (this file)
- `HANDOVER_TO_NEW_SESSION_PHASE2.md`
- `app/Http/Controllers/TempahanFasilitiController.php`
- `app/Http/Controllers/PembayaranSewaController.php`
- `app/Http/Controllers/LaporanTempahanController.php`
- `resources/views/senarai-aset/show.blade.php` (pattern)
- `resources/views/laporan-kebajikan/index.blade.php` (pattern)

---

**Phase 2 Status**: IN PROGRESS ⏳
**Next Session Priority**: Complete remaining 10 views + navbar update
**Overall Module Progress**: ~40% Complete (Phase 1 done, Phase 2 partial)
