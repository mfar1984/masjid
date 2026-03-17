# AGIHAN ZAKAT MODULE - DESIGN DOCUMENT

## 📅 Date: 12 December 2025

## 🎯 TUJUAN
Modul untuk merekod dan mengurus agihan/pembayaran zakat kepada asnaf yang permohonannya telah diluluskan.

---

## 📊 DATABASE STRUCTURE

### Table: `agihan_zakat`

**Relationships:**
- `permohonan_zakat_id` → permohonan_zakat (cascade delete)
- `asnaf_id` → asnaf (cascade delete)
- `masjid_id` → masjids (cascade delete)

**Fields:**
1. `id` - Primary key
2. `permohonan_zakat_id` - FK to permohonan_zakat
3. `asnaf_id` - FK to asnaf
4. `masjid_id` - FK to masjids
5. `no_agihan` - Unique agihan number (e.g., AG-2025-0001)
6. `tarikh_agihan` - Date of distribution
7. `jumlah_diagihkan` - Amount distributed (decimal 10,2)
8. `kaedah_bayaran` - Payment method (Tunai, Cek, Bank Transfer, E-Wallet)
9. `no_rujukan` - Reference number (cek/transfer)
10. `nama_bank` - Bank name (nullable)
11. `no_akaun` - Account number (nullable)
12. `status` - Status (Belum Bayar, Sudah Bayar, Dibatalkan)
13. `tarikh_bayaran` - Payment date (nullable)
14. `bukti_bayaran_path` - Payment proof file path
15. `catatan` - Notes
16. `created_by` - User who created
17. `updated_by` - User who updated
18. `dibayar_oleh` - User who paid
19. `timestamps` - created_at, updated_at
20. `softDeletes` - deleted_at

**Indexes:**
- masjid_id + status
- asnaf_id + status
- tarikh_agihan

---

## 🔄 WORKFLOW

### 1. Create Agihan
```
Permohonan Diluluskan → Pilih Permohonan → Isi Maklumat Agihan → Simpan
Status: Belum Bayar
```

### 2. Bayar Agihan
```
Belum Bayar → Upload Bukti → Tandakan Sudah Bayar
Status: Sudah Bayar
```

### 3. Batal Agihan
```
Belum Bayar → Batal → Nyatakan Sebab
Status: Dibatalkan
```

---

## 📋 FEATURES

### 1. Senarai Agihan (Index)
**URL**: `/agihan-zakat`

**Display:**
- Desktop: Table view
- Mobile: Card view

**Columns:**
- No Agihan
- Tarikh
- Asnaf (nama + IC)
- Jumlah
- Kaedah Bayaran
- Status
- Actions (View, Edit, Bayar, Batal, Delete)

**Filters:**
- Search (no agihan, nama asnaf, no IC)
- Status (Belum Bayar, Sudah Bayar, Dibatalkan)
- Kaedah Bayaran
- Date Range
- Masjid (Super Admin only)

**Stats Cards (5):**
1. Jumlah Agihan
2. Belum Bayar
3. Sudah Bayar
4. Dibatalkan
5. Total Diagihkan (RM)

**Actions:**
- Tambah Agihan (button)
- Export (button)

---

### 2. Tambah Agihan (Create)
**URL**: `/agihan-zakat/create`

**Form Sections:**

**A. Pilih Permohonan**
- Dropdown: Permohonan yang Diluluskan sahaja
- Display: No Permohonan, Nama Asnaf, Jumlah Diluluskan
- Auto-fill: Asnaf, Jumlah

**B. Maklumat Agihan**
- Tarikh Agihan * (date, default: today)
- Jumlah Diagihkan * (number, default: jumlah diluluskan)
- Kaedah Bayaran * (dropdown: Tunai, Cek, Bank Transfer, E-Wallet)

**C. Maklumat Bayaran (conditional)**
- If Cek/Bank Transfer/E-Wallet:
  - No Rujukan * (text)
  - Nama Bank (text)
  - No Akaun (text)

**D. Catatan**
- Catatan (textarea, optional)

**Buttons:**
- Simpan (blue)
- Batal (gray)

---

### 3. Lihat Agihan (Show)
**URL**: `/agihan-zakat/{id}`

**Sections:**

**1. Maklumat Agihan**
- No Agihan
- Tarikh Agihan
- Jumlah Diagihkan
- Kaedah Bayaran
- No Rujukan
- Nama Bank
- No Akaun
- Status (badge)
- Catatan

**2. Maklumat Permohonan**
- No Permohonan (link)
- Tarikh Permohonan
- Jenis Bantuan
- Jumlah Dipohon
- Jumlah Diluluskan

**3. Maklumat Asnaf**
- Nama (link to asnaf profile)
- No IC
- Kategori Asnaf
- Telefon
- Alamat

**4. Maklumat Bayaran** (if Sudah Bayar)
- Tarikh Bayaran
- Dibayar Oleh
- Bukti Bayaran (download link)

**Actions:**
- Kembali (gray)
- Edit (blue) - if Belum Bayar
- Tandakan Sudah Bayar (green) - if Belum Bayar
- Batal Agihan (red) - if Belum Bayar

---

### 4. Edit Agihan (Edit)
**URL**: `/agihan-zakat/{id}/edit`

**Rules:**
- Only editable if status = "Belum Bayar"
- Cannot change Permohonan/Asnaf
- Can change: Tarikh, Jumlah, Kaedah, Rujukan, Catatan

**Form:** Same as Create but pre-filled

---

### 5. Bayar Agihan (Modal)
**Trigger:** Button "Tandakan Sudah Bayar" in show page

**Modal Fields:**
- Tarikh Bayaran * (date, default: today)
- Bukti Bayaran * (file upload: PDF, JPG, PNG, max 5MB)
- Catatan (textarea, optional)

**Action:**
- Update status to "Sudah Bayar"
- Save tarikh_bayaran, dibayar_oleh, bukti_bayaran_path

---

### 6. Batal Agihan (Modal)
**Trigger:** Button "Batal Agihan" in show page

**Modal Fields:**
- Sebab Pembatalan * (textarea)

**Action:**
- Update status to "Dibatalkan"
- Save catatan

---

### 7. Export Agihan
**URL**: `/agihan-zakat/export`

**Format:** CSV

**Columns:**
- No Agihan
- Tarikh Agihan
- Nama Asnaf
- No IC
- Kategori Asnaf
- No Permohonan
- Jumlah Diagihkan
- Kaedah Bayaran
- No Rujukan
- Status
- Tarikh Bayaran

**Filters:** Apply same filters as index

---

## 🎨 UI/UX DESIGN

**Follow Kariah/AJK Pattern:**
- Font: Poppins 10-14px
- Border radius: 4-8px
- Desktop: Table view (bg-blue-100 header)
- Mobile: Card view
- Action icons: text-[8px]
- Colors: gray (view), blue (edit), green (bayar), red (batal/delete)

**Status Colors:**
- Belum Bayar: orange-100/orange-800
- Sudah Bayar: green-100/green-800
- Dibatalkan: red-100/red-800

**Kaedah Bayaran Icons:**
- Tunai: payments
- Cek: receipt
- Bank Transfer: account_balance
- E-Wallet: account_balance_wallet

---

## 🔐 PERMISSIONS

**Module:** `agihan_zakat`

**Actions:**
- `create` - Tambah agihan
- `read` - Lihat senarai & butiran
- `update` - Edit & bayar agihan
- `delete` - Padam agihan

**Multi-Masjid Isolation:**
- Super Admin: See all agihan
- Admin Masjid: Only see agihan from their masjid

---

## 📝 VALIDATION RULES

### Create/Update:
- `permohonan_zakat_id`: required, exists:permohonan_zakat,id
- `tarikh_agihan`: required, date
- `jumlah_diagihkan`: required, numeric, min:0
- `kaedah_bayaran`: required, in:Tunai,Cek,Bank Transfer,E-Wallet
- `no_rujukan`: required_if:kaedah_bayaran,Cek,Bank Transfer,E-Wallet
- `nama_bank`: nullable, string
- `no_akaun`: nullable, string
- `catatan`: nullable, string

### Bayar:
- `tarikh_bayaran`: required, date
- `bukti_bayaran`: required, file, mimes:pdf,jpg,jpeg,png, max:5120
- `catatan`: nullable, string

### Batal:
- `sebab_pembatalan`: required, string

---

## 🔗 ROUTES

```php
// Agihan Zakat Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/agihan-zakat', [AgihanZakatController::class, 'index'])
        ->middleware('permission:agihan_zakat,read')
        ->name('agihan-zakat.index');
    
    Route::get('/agihan-zakat/export', [AgihanZakatController::class, 'export'])
        ->middleware('permission:agihan_zakat,read')
        ->name('agihan-zakat.export');
    
    Route::get('/agihan-zakat/create', [AgihanZakatController::class, 'create'])
        ->middleware('permission:agihan_zakat,create')
        ->name('agihan-zakat.create');
    
    Route::post('/agihan-zakat', [AgihanZakatController::class, 'store'])
        ->middleware('permission:agihan_zakat,create')
        ->name('agihan-zakat.store');
    
    Route::get('/agihan-zakat/{agihanZakat}', [AgihanZakatController::class, 'show'])
        ->middleware('permission:agihan_zakat,read')
        ->name('agihan-zakat.show');
    
    Route::get('/agihan-zakat/{agihanZakat}/edit', [AgihanZakatController::class, 'edit'])
        ->middleware('permission:agihan_zakat,update')
        ->name('agihan-zakat.edit');
    
    Route::put('/agihan-zakat/{agihanZakat}', [AgihanZakatController::class, 'update'])
        ->middleware('permission:agihan_zakat,update')
        ->name('agihan-zakat.update');
    
    Route::delete('/agihan-zakat/{agihanZakat}', [AgihanZakatController::class, 'destroy'])
        ->middleware('permission:agihan_zakat,delete')
        ->name('agihan-zakat.destroy');
    
    Route::post('/agihan-zakat/{agihanZakat}/bayar', [AgihanZakatController::class, 'bayar'])
        ->middleware('permission:agihan_zakat,update')
        ->name('agihan-zakat.bayar');
    
    Route::post('/agihan-zakat/{agihanZakat}/batal', [AgihanZakatController::class, 'batal'])
        ->middleware('permission:agihan_zakat,update')
        ->name('agihan-zakat.batal');
});
```

---

## 📦 FILES TO CREATE

### Backend:
1. ✅ Migration: `2025_12_12_140945_create_agihan_zakat_table.php`
2. ⏳ Model: `app/Models/AgihanZakat.php`
3. ⏳ Controller: `app/Http/Controllers/AgihanZakatController.php`

### Frontend:
4. ⏳ Index: `resources/views/agihan-zakat/index.blade.php`
5. ⏳ Create: `resources/views/agihan-zakat/create.blade.php`
6. ⏳ Show: `resources/views/agihan-zakat/show.blade.php`
7. ⏳ Edit: `resources/views/agihan-zakat/edit.blade.php`

### Routes:
8. ⏳ Add routes to `routes/web.php`

### Permissions:
9. ⏳ Add 'agihan_zakat' to RoleController permission matrix

---

## ✅ NEXT STEPS

1. Create Model with relationships
2. Create Controller with all methods
3. Create Views (index, create, show, edit)
4. Add routes
5. Update RoleController
6. Update navigation menu
7. Test all features

---

**Status**: ✅ Migration Created & Run
**Next**: Create Model & Controller
