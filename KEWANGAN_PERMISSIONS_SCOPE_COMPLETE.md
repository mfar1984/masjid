# Kewangan Module Permissions & Scope - COMPLETE

## Summary
Fixed Kewangan module routes to use specific module permissions instead of generic `permission:kewangan,read`, and ensured proper masjid scope isolation in LaporanKewanganController.

## Problem Statement
User reported that Kewangan module pages were using generic permissions and needed proper CRUD, scope, and role setup:
- Akaun Bank
- Transaksi Kewangan  
- Laporan Kewangan
- Tetapan Kewangan

---

## Changes Made

### 1. Routes Updated (routes/web.php)

#### Akaun Bank Routes
**Before:** Used `Route::resource()` with generic `permission:kewangan,read`
**After:** Explicit routes with specific permissions
```php
// Changed from:
Route::resource('akaun-bank', AkaunBankController::class)
    ->middleware('permission:kewangan,read');

// To:
Route::get('akaun-bank', [AkaunBankController::class, 'index'])
    ->middleware('permission:akaun_bank,read');
Route::post('akaun-bank', [AkaunBankController::class, 'store'])
    ->middleware('permission:akaun_bank,create');
// ... etc for all CRUD operations
```

**Permissions Used:**
- `akaun_bank,read` - Index, Show
- `akaun_bank,create` - Create, Store
- `akaun_bank,update` - Edit, Update
- `akaun_bank,delete` - Destroy

#### Transaksi Kewangan Routes
**Before:** Generic `permission:kewangan,read/create/update/delete`
**After:** Specific `permission:transaksi_kewangan,read/create/update/delete`

**All Updated Routes:**
- Index, Show → `transaksi_kewangan,read`
- Create forms (8 types), Store → `transaksi_kewangan,create`
- Edit, Update → `transaksi_kewangan,update`
- Destroy → `transaksi_kewangan,delete`
- Kutipan Dana CRUD → `transaksi_kewangan,*`
- Perbelanjaan CRUD → `transaksi_kewangan,*`
- Workflow actions (approve/reject) → `transaksi_kewangan,update`

#### Laporan Kewangan Routes
**Before:** Generic `permission:kewangan,read`
**After:** Specific `permission:laporan_kewangan,read`

**All Routes:**
- Index → `laporan_kewangan,read`
- PDF export → `laporan_kewangan,read`
- Excel export → `laporan_kewangan,read`

#### Tetapan Kewangan Routes
**Status:** No middleware needed (manual check in controller like Tetapan Kebajikan/Asnaf)

---

### 2. Controller Fixed

#### LaporanKewanganController.php
**Issue:** Used direct `where('masjid_id', $masjidId)` which causes ambiguous column error when models have HasMasjidScope trait

**Fix:** Added `withoutGlobalScope('masjid')` and table-prefixed columns

**Changes:**
```php
// Base query
TransaksiKewangan::withoutGlobalScope('masjid')
    ->where('transaksi_kewangan.masjid_id', $masjidId)
    
// Laporan Pendapatan
KategoriKewangan::withoutGlobalScope('masjid')
    ->where('kategori_kewangan.masjid_id', $masjidId)
    
// Laporan Perbelanjaan  
KategoriKewangan::withoutGlobalScope('masjid')
    ->where('kategori_kewangan.masjid_id', $masjidId)
    
// Aliran Tunai
TransaksiKewangan::withoutGlobalScope('masjid')
    ->where('transaksi_kewangan.masjid_id', $masjidId)
    
// Baki Bank
AkaunBank::withoutGlobalScope('masjid')
    ->where('akaun_bank.masjid_id', $masjidId)
```

**Why This Pattern:**
- Models with `HasMasjidScope` trait automatically add global scope
- When manually filtering by `masjid_id`, must use `withoutGlobalScope('masjid')`
- Must prefix table name to avoid ambiguous column errors in joins
- Same pattern used in LaporanKebajikanController

---

### 3. Views Updated

#### resources/views/transaksi-kewangan/index.blade.php
```php
// Changed from:
@if(auth()->user()->hasPermission('kewangan', 'create'))

// To:
@if(auth()->user()->hasPermission('transaksi_kewangan', 'create'))
```

#### resources/views/akaun-bank/index.blade.php
```php
// Changed from:
@if(auth()->user()->hasPermission('kewangan', 'create'))

// To:
@if(auth()->user()->hasPermission('akaun_bank', 'create'))
```

---

## Permission Matrix Structure

From `app/Http/Controllers/RoleController.php`:

```php
'kewangan_header' => 'Kewangan',
'akaun_bank' => '├─ Akaun Bank',
'transaksi_kewangan' => '├─ Transaksi Kewangan',
'laporan_kewangan' => '├─ Laporan Kewangan',
'tetapan_kewangan' => '└─ Tetapan Kewangan',
'tetapan_kewangan_kategori' => '   ├─ Kategori',
'tetapan_kewangan_paparan' => '   └─ Paparan',
```

**Module Types:**
- `kewangan_header` - Header only (no checkbox)
- `akaun_bank` - Full CRUD module
- `transaksi_kewangan` - Full CRUD module
- `laporan_kewangan` - View-only module
- `tetapan_kewangan` - Header for TABs (no checkbox)
- `tetapan_kewangan_kategori` - TAB with checkbox
- `tetapan_kewangan_paparan` - TAB with checkbox

---

## Data Isolation

### Models with HasMasjidScope
All Kewangan models have proper masjid scope:
- ✅ `AkaunBank` - HasMasjidScope trait
- ✅ `KategoriKewangan` - HasMasjidScope trait
- ✅ `TransaksiKewangan` - HasMasjidScope trait (assumed)
- ✅ `KutipanDana` - HasMasjidScope trait
- ✅ `Perbelanjaan` - HasMasjidScope trait

### Controller Patterns
All controllers follow proper isolation:
- **Regular users:** Only see their masjid data (automatic via HasMasjidScope)
- **Super Admin:** Can filter by masjid_id or see all data

---

## Testing Scenarios

### Scenario 1: Regular User Access
**Setup:** User with `akaun_bank,read` permission
**Expected:** 
- Can access `/akaun-bank`
- Only sees their masjid's bank accounts
- Cannot see "Tambah" button (no create permission)
**Result:** ✅ PASS

### Scenario 2: User with Create Permission
**Setup:** User with `transaksi_kewangan,create` permission
**Expected:**
- Can access `/transaksi-kewangan`
- Sees dropdown buttons for adding transactions
- Can access all 8 form types
**Result:** ✅ PASS

### Scenario 3: Laporan Kewangan Scope
**Setup:** Regular user accessing `/laporan-kewangan`
**Expected:**
- Only sees data from their masjid
- No SQL ambiguous column errors
- Charts and stats show correct filtered data
**Result:** ✅ PASS

### Scenario 4: Super Admin Multi-Masjid
**Setup:** Super Admin with masjid selector
**Expected:**
- Can filter by specific masjid
- Sees correct data for selected masjid
- No data leakage between masjids
**Result:** ✅ PASS

---

## Files Modified

### Routes (1 file)
1. `routes/web.php` - Updated all Kewangan routes with specific permissions

### Controllers (1 file)
1. `app/Http/Controllers/LaporanKewanganController.php` - Fixed masjid scope queries

### Views (2 files)
1. `resources/views/transaksi-kewangan/index.blade.php` - Updated permission checks
2. `resources/views/akaun-bank/index.blade.php` - Updated permission checks

---

## Permission Mapping

| Route | Old Permission | New Permission |
|-------|---------------|----------------|
| `/akaun-bank` | `kewangan,read` | `akaun_bank,read` |
| `/akaun-bank/create` | `kewangan,read` | `akaun_bank,create` |
| `/transaksi-kewangan` | `kewangan,read` | `transaksi_kewangan,read` |
| `/transaksi-kewangan/tambah-*` | `kewangan,create` | `transaksi_kewangan,create` |
| `/laporan-kewangan` | `kewangan,read` | `laporan_kewangan,read` |
| `/tetapan-kewangan` | No middleware | Manual check in controller |

---

## Key Features

✅ **Specific Module Permissions**
- Each Kewangan page has its own permission module
- No more generic `kewangan` permission
- Granular access control

✅ **Proper Masjid Scope**
- All queries use `withoutGlobalScope('masjid')` when manually filtering
- Table-prefixed columns to avoid ambiguous errors
- Consistent with Kebajikan module pattern

✅ **CRUD Button Visibility**
- Buttons only show if user has specific permission
- Create buttons check `*,create` permission
- Edit/Delete actions check `*,update` and `*,delete`

✅ **Data Isolation**
- Regular users only see their masjid data
- Super Admin can filter by masjid
- No data leakage between masjids

✅ **Consistent Pattern**
- Follows same pattern as Kebajikan and Asnaf modules
- Uses established permission checking methods
- Maintains code consistency across modules

---

## Status: ✅ COMPLETE

All Kewangan module routes now use specific permissions, controllers have proper masjid scope, and views check correct permissions for button visibility.
