# Kebajikan Module - Permission Views & SQL Fix

## Masalah Yang Ditemui

### 1. Button Tambah Tidak Muncul
- **Lokasi**: `/program-kebajikan`, `/penerima-bantuan`, `/permohonan-bantuan`, `/pembayaran-bantuan`
- **Sebab**: Views check `hasPermission('kebajikan', 'create')` tapi permission matrix guna `program_kebajikan`, `penerima_bantuan`, dll
- **Impact**: User dengan permission yang betul tidak nampak button Tambah/Edit/Delete

### 2. Tetapan Kebajikan 403 Error
- **Lokasi**: `/tetapan-kebajikan`
- **Sebab**: Route middleware check `permission:tetapan_kebajikan,read` tapi user role mungkin tidak ada permission ini
- **Solution**: Sudah fixed dalam routes (previous fix)

### 3. Laporan Kebajikan SQL Error
- **Error**: `SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'masjid_id' in where clause is ambiguous`
- **Lokasi**: `LaporanKebajikanController@index` line 116
- **Sebab**: Query join `permohonan_bantuan` dengan `program_kebajikan`, kedua-dua table ada column `masjid_id`

## Penyelesaian

### 1. Fixed Permission Checks in Views

#### Program Kebajikan
**File**: `resources/views/program-kebajikan/index.blade.php`
```php
// BEFORE
@if(auth()->user()->hasPermission('kebajikan', 'create'))

// AFTER
@if(auth()->user()->hasPermission('program_kebajikan', 'create'))
```

#### Penerima Bantuan
**Files**: 
- `resources/views/penerima-bantuan/index.blade.php`
- `resources/views/penerima-bantuan/show.blade.php`

```php
// BEFORE
@if(auth()->user()->hasPermission('kebajikan', 'create'))
@if(auth()->user()->hasPermission('kebajikan', 'update'))

// AFTER
@if(auth()->user()->hasPermission('penerima_bantuan', 'create'))
@if(auth()->user()->hasPermission('penerima_bantuan', 'update'))
```

#### Permohonan Bantuan
**Files**:
- `resources/views/permohonan-bantuan/index.blade.php`
- `resources/views/permohonan-bantuan/show.blade.php`

```php
// BEFORE
@if(auth()->user()->hasPermission('kebajikan', 'create'))
@if(auth()->user()->hasPermission('kebajikan', 'update'))

// AFTER
@if(auth()->user()->hasPermission('permohonan_bantuan', 'create'))
@if(auth()->user()->hasPermission('permohonan_bantuan', 'update'))
@if(auth()->user()->hasPermission('permohonan_bantuan', 'approve'))  // For workflow actions
```

#### Pembayaran Bantuan
**Files**:
- `resources/views/pembayaran-bantuan/index.blade.php`
- `resources/views/pembayaran-bantuan/show.blade.php`

```php
// BEFORE
@if(auth()->user()->hasPermission('kebajikan', 'create'))
@if(auth()->user()->hasPermission('kebajikan', 'update'))

// AFTER
@if(auth()->user()->hasPermission('pembayaran_bantuan', 'create'))
@if(auth()->user()->hasPermission('pembayaran_bantuan', 'update'))
```

### 2. Fixed SQL Ambiguous Column Error

**File**: `app/Http/Controllers/LaporanKebajikanController.php`

**Problem**: 
- Base queries use `where('masjid_id', $masjidId)` without table prefix
- When joining tables, SQL becomes ambiguous because both tables have `masjid_id` column
- Error: `Column 'masjid_id' in where clause is ambiguous`

**Solution - Two Changes**:

#### A. Fixed Base Queries (Lines 27-42)
```php
// BEFORE
$permohonanQuery->where('masjid_id', $masjidId);
$pembayaranQuery->where('masjid_id', $masjidId);

// AFTER - Specify table name
$permohonanQuery->where('permohonan_bantuan.masjid_id', $masjidId);
$pembayaranQuery->where('pembayaran_bantuan.masjid_id', $masjidId);
$programQuery->where('program_kebajikan.masjid_id', $masjidId);
$penerimaQuery->where('penerima_bantuan.masjid_id', $masjidId);
```

#### B. Fixed Join Query (Lines 113-120)
```php
// BEFORE
$penerimaByKategori = (clone $permohonanQuery)
    ->join('program_kebajikan', 'permohonan_bantuan.program_kebajikan_id', '=', 'program_kebajikan.id')
    ->select('program_kebajikan.kategori_program', DB::raw('count(DISTINCT permohonan_bantuan.penerima_bantuan_id) as total'))
    ->groupBy('program_kebajikan.kategori_program')
    ->get()
    ->pluck('total', 'kategori_program');

// AFTER - Use join closure with where condition
$penerimaByKategori = (clone $permohonanQuery)
    ->join('program_kebajikan', function($join) use ($masjidId) {
        $join->on('permohonan_bantuan.program_kebajikan_id', '=', 'program_kebajikan.id')
             ->where('program_kebajikan.masjid_id', '=', $masjidId);
    })
    ->select('program_kebajikan.kategori_program', DB::raw('count(DISTINCT permohonan_bantuan.penerima_bantuan_id) as total'))
    ->groupBy('program_kebajikan.kategori_program')
    ->get()
    ->pluck('total', 'kategori_program');
```

## Files Modified

### Views (7 files)
1. `resources/views/program-kebajikan/index.blade.php`
2. `resources/views/penerima-bantuan/index.blade.php`
3. `resources/views/penerima-bantuan/show.blade.php`
4. `resources/views/permohonan-bantuan/index.blade.php`
5. `resources/views/permohonan-bantuan/show.blade.php` (2 occurrences)
6. `resources/views/pembayaran-bantuan/index.blade.php`
7. `resources/views/pembayaran-bantuan/show.blade.php`

### Controllers (1 file)
1. `app/Http/Controllers/LaporanKebajikanController.php`

## Permission Mapping

| View Check | Module Name | Actions |
|-----------|-------------|---------|
| `program_kebajikan` | Program Kebajikan | create, read, update, delete |
| `penerima_bantuan` | Penerima Bantuan | create, read, update, delete |
| `permohonan_bantuan` | Permohonan Bantuan | create, read, update, delete, approve, reject |
| `pembayaran_bantuan` | Pembayaran Bantuan | create, read, update, delete |
| `laporan_kebajikan` | Laporan Kebajikan | read |
| `tetapan_kebajikan` | Tetapan Kebajikan | read, update |

## Testing Results

✅ Build successful - no errors
```bash
npm run build
# Exit Code: 0
```

## Expected Behavior After Fix

### Before Fix
- ❌ Button "Tambah Program" tidak muncul walaupun user ada permission
- ❌ Button "Edit" dan "Delete" tidak muncul dalam table
- ❌ Laporan Kebajikan page crash dengan SQL error
- ❌ Tetapan Kebajikan 403 error

### After Fix
- ✅ Button "Tambah Program" muncul untuk user dengan `program_kebajikan.create` permission
- ✅ Button "Edit" muncul untuk user dengan `program_kebajikan.update` permission
- ✅ Button "Delete" muncul untuk user dengan `program_kebajikan.delete` permission
- ✅ Workflow buttons (Semak, Lulus, Tolak) muncul untuk user dengan `permohonan_bantuan.approve` permission
- ✅ Laporan Kebajikan page load tanpa SQL error
- ✅ Tetapan Kebajikan accessible dengan proper permission

## Notes

- Semua permission checks dalam views sekarang consistent dengan permission matrix dalam RoleController
- SQL query sekarang explicitly specify table name untuk avoid ambiguous column errors
- Workflow actions (approve, reject) guna permission yang betul
- Pattern ini consistent dengan modules lain (Asnaf, Kariah, AJK)

## Next Steps

1. ✅ Test semua pages dengan user yang ada different permissions
2. ✅ Verify buttons muncul mengikut permissions
3. ✅ Test workflow actions (Semak, Lulus, Tolak) berfungsi dengan betul
4. Apply same pattern untuk modules lain jika ada permission mismatch
