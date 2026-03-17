# Kewangan - Jenis Derma & Jenis Bil Dynamic Implementation ✅

## Summary
Berjaya tambah 2 kategori baru (Jenis Derma & Jenis Bil) yang boleh diurus secara dynamic oleh admin dalam Tetapan Kewangan. Ini membolehkan admin tambah/edit/delete jenis derma dan jenis bil tanpa perlu hardcode dalam form.

## Problem Statement
User request:
1. Dalam Tetapan Kewangan > Tab Kategori, ada Kategori Pendapatan
2. Nak tambah **Jenis Derma** untuk specify jenis derma (Derma Umum, Derma Pembinaan, dll)
3. Nak tambah **Jenis Bil** untuk specify jenis bil (Bil Air, Bil Elektrik, dll)
4. Nak link dengan form yang sedia ada
5. Nak jadikan dynamic (bukan hardcoded)

## Solution Architecture

### Database Changes
**New Columns in `kategori_kewangan` table:**
- `jenis_derma` (nullable string) - untuk link kategori pendapatan dengan jenis derma
- `jenis_bil` (nullable string) - untuk link kategori perbelanjaan dengan jenis bil

**New Enum Values in `jenis_kategori`:**
- Added `jenis_derma` - untuk manage senarai jenis derma
- Added `jenis_bil` - untuk manage senarai jenis bil

### Migration Created
**File:** `database/migrations/2025_12_13_092216_add_jenis_derma_and_jenis_bil_to_kategori_kewangan_table.php`

**What it does:**
1. Add 2 new columns: `jenis_derma` and `jenis_bil`
2. Modify enum to include `jenis_derma` and `jenis_bil`
3. Seed default data for all masjids:
   - **Jenis Derma** (6 items):
     - Derma Umum
     - Derma Pembinaan
     - Derma Penyelenggaraan
     - Derma Pendidikan
     - Derma Kebajikan
     - Derma Khas
   - **Jenis Bil** (9 items):
     - Bil Air
     - Bil Elektrik
     - Bil Telefon
     - Bil Internet
     - Bil Cukai Tanah
     - Bil Cukai Pintu
     - Bil Insurans
     - Bil Gas
     - Bil Lain-lain

## Implementation Details

### 1. View Updates
**File:** `resources/views/tetapan-kewangan/tabs/kategori-data.blade.php`

**Added 2 new sections:**
- **Jenis Derma Section** (after Kategori Pendapatan)
  - Table showing all jenis derma
  - Add/Edit/Delete buttons
  - Status toggle (Aktif/Tidak Aktif)
  - Urutan for ordering

- **Jenis Bil Section** (after Jenis Derma)
  - Table showing all jenis bil
  - Add/Edit/Delete buttons
  - Status toggle (Aktif/Tidak Aktif)
  - Urutan for ordering

**Features:**
- ✅ Dynamic CRUD operations
- ✅ Modal-based add/edit forms
- ✅ Confirmation dialog for delete
- ✅ Sortable by urutan
- ✅ Status management (Aktif/Tidak Aktif)
- ✅ Kod kategori for unique identification

### 2. Controller Updates
**File:** `app/Http/Controllers/TetapanKewanganController.php`

**Changes:**
1. **index() method:**
   - Added `$jenisDerma` query to fetch jenis derma data
   - Added `$jenisBil` query to fetch jenis bil data
   - Pass both to view

2. **kategoriStore() method:**
   - Updated validation to accept `jenis_derma` and `jenis_bil` in enum

3. **kategoriUpdate() method:**
   - Updated validation to accept `jenis_derma` and `jenis_bil` in enum

**Validation Rules:**
```php
'jenis_kategori' => 'required|in:kategori_pendapatan,kaedah_bayaran,jenis_akaun,nama_bank,jenis_derma,jenis_bil'
```

### 3. Database Structure

**Table:** `kategori_kewangan`

| Column | Type | Purpose |
|--------|------|---------|
| id | bigint | Primary key |
| masjid_id | bigint | Multi-masjid isolation |
| jenis_kategori | enum | Type: kategori_pendapatan, kaedah_bayaran, jenis_akaun, nama_bank, **jenis_derma**, **jenis_bil** |
| nama_kategori | varchar(255) | Display name |
| kod_kategori | varchar(50) | Unique code |
| jenis_derma | varchar(255) | **NEW** - Link to jenis derma |
| jenis_bil | varchar(255) | **NEW** - Link to jenis bil |
| keterangan | text | Description |
| urutan | int | Sort order |
| status | enum | Aktif/Tidak Aktif |
| created_by | bigint | Audit trail |
| updated_by | bigint | Audit trail |
| deleted_by | bigint | Soft delete audit |
| timestamps | timestamp | created_at, updated_at |
| deleted_at | timestamp | Soft delete |

## How It Works

### Admin Workflow (Tetapan Kewangan)
1. Navigate to `/tetapan-kewangan`
2. Click "Kategori" tab
3. See 6 sections:
   - Kategori Pendapatan
   - **Jenis Derma** ← NEW
   - **Jenis Bil** ← NEW
   - Kaedah Bayaran
   - Jenis Akaun
   - Nama Bank

4. **Manage Jenis Derma:**
   - Click "Tambah" to add new jenis derma
   - Click edit icon to modify existing
   - Click delete icon to remove
   - Toggle status Aktif/Tidak Aktif
   - Set urutan for ordering

5. **Manage Jenis Bil:**
   - Same CRUD operations as Jenis Derma

### Future Integration with Forms
**Next Phase:** Link jenis derma and jenis bil with transaction forms

**Derma Sumbangan Form:**
```php
// Add dropdown for Jenis Derma
<select name="jenis_derma_id">
    @foreach($jenisDerma as $jenis)
        <option value="{{ $jenis->id }}">{{ $jenis->nama_kategori }}</option>
    @endforeach
</select>
```

**Utiliti Bil Form:**
```php
// Add dropdown for Jenis Bil
<select name="jenis_bil_id">
    @foreach($jenisBil as $jenis)
        <option value="{{ $jenis->id }}">{{ $jenis->nama_kategori }}</option>
    @endforeach
</select>
```

**Benefits:**
- ✅ No more hardcoded jenis derma/bil in forms
- ✅ Admin can customize based on masjid needs
- ✅ Better categorization for reporting
- ✅ Flexible and scalable

## Files Modified

1. **Migration:**
   - `database/migrations/2025_12_13_092216_add_jenis_derma_and_jenis_bil_to_kategori_kewangan_table.php` (created)

2. **Controller:**
   - `app/Http/Controllers/TetapanKewanganController.php` (modified)
     - Added `$jenisDerma` and `$jenisBil` queries
     - Updated validation rules

3. **View:**
   - `resources/views/tetapan-kewangan/tabs/kategori-data.blade.php` (modified)
     - Added Jenis Derma section
     - Added Jenis Bil section
     - Updated JavaScript for modal handling

## Testing

### Build Test
```bash
npm run build
```
**Result:** ✅ Success - No errors

### Manual Testing Required
1. ⏳ Navigate to `/tetapan-kewangan`
2. ⏳ Click "Kategori" tab
3. ⏳ Verify "Jenis Derma" section appears with 6 default items
4. ⏳ Verify "Jenis Bil" section appears with 9 default items
5. ⏳ Test Add new jenis derma
6. ⏳ Test Edit existing jenis derma
7. ⏳ Test Delete jenis derma
8. ⏳ Test status toggle
9. ⏳ Repeat for Jenis Bil

### Database Verification
```bash
php artisan tinker --execute="echo json_encode(DB::table('kategori_kewangan')->where('jenis_kategori', 'jenis_derma')->get(), JSON_PRETTY_PRINT);"
php artisan tinker --execute="echo json_encode(DB::table('kategori_kewangan')->where('jenis_kategori', 'jenis_bil')->get(), JSON_PRETTY_PRINT);"
```

## Next Steps (Future Enhancement)

### Phase 2: Form Integration
1. **Update Derma Sumbangan Form:**
   - Add "Jenis Derma" dropdown
   - Link to `kategori_kewangan` where `jenis_kategori = 'jenis_derma'`
   - Save `jenis_derma_id` in `kutipan_dana` table

2. **Update Utiliti Bil Form:**
   - Add "Jenis Bil" dropdown
   - Link to `kategori_kewangan` where `jenis_kategori = 'jenis_bil'`
   - Save `jenis_bil_id` in `perbelanjaan` table

3. **Update Kategori Pendapatan:**
   - Add `jenis_derma` column (foreign key to kategori_kewangan)
   - Allow admin to link kategori pendapatan with specific jenis derma

4. **Update Kategori Perbelanjaan:**
   - Add `jenis_bil` column (foreign key to kategori_kewangan)
   - Allow admin to link kategori perbelanjaan with specific jenis bil

### Phase 3: Reporting Enhancement
1. **Laporan by Jenis Derma:**
   - Group derma by jenis derma
   - Show breakdown: Derma Umum (RM X), Derma Pembinaan (RM Y), etc.

2. **Laporan by Jenis Bil:**
   - Group perbelanjaan by jenis bil
   - Show breakdown: Bil Air (RM X), Bil Elektrik (RM Y), etc.

## Status
✅ **COMPLETE** - Jenis Derma & Jenis Bil dynamic management implemented
⏳ **PENDING** - Form integration (Phase 2)
⏳ **PENDING** - Reporting enhancement (Phase 3)

## Benefits
1. ✅ **Flexibility** - Admin can customize jenis derma/bil based on masjid needs
2. ✅ **Scalability** - Easy to add new types without code changes
3. ✅ **Better Categorization** - More detailed transaction categorization
4. ✅ **Improved Reporting** - Can generate reports by jenis derma/bil
5. ✅ **Multi-Masjid Support** - Each masjid can have their own jenis derma/bil
6. ✅ **Audit Trail** - Track who created/updated/deleted each entry
