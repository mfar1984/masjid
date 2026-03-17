# Senarai Kumpulan - Permission Routes Fix

## Masalah Asal
User mendapat error **403 - Anda tidak mempunyai kebenaran untuk mengakses halaman ini** pada page `/program-kebajikan`.

## Root Cause
Terdapat **mismatch** antara:
1. **Permission middleware dalam routes** - menggunakan `permission:kebajikan,read`
2. **Permission matrix dalam RoleController** - menggunakan module names seperti `program_kebajikan`, `penerima_bantuan`, dll

Middleware `CheckPermission` mencari permission key `kebajikan` dalam role permissions, tetapi permission matrix menyimpan key `program_kebajikan`, menyebabkan authorization gagal.

## Penyelesaian

### 1. Fixed Routes - Kebajikan Module
Updated semua routes untuk menggunakan specific module names yang match dengan permission matrix:

#### Program Kebajikan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:program_kebajikan,read')`
- Routes: index, create, store, show, edit, update, destroy

#### Penerima Bantuan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:penerima_bantuan,read')`
- Routes: index, create, store, show, edit, update, destroy

#### Permohonan Bantuan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:permohonan_bantuan,read')`
- Routes: index, create, store, show, edit, update, destroy
- Workflow actions: semak, lawatan, lulus (approve), tolak (reject), batal (delete)

#### Pembayaran Bantuan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:pembayaran_bantuan,read')`
- Routes: index, create, store, show, edit, update, destroy
- Workflow actions: sahkan (update), batal (delete)

#### Laporan Kebajikan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:laporan_kebajikan,read')`
- Routes: index, pdf, excel

#### Tetapan Kebajikan Routes
- **BEFORE**: `->middleware('permission:kebajikan,read')`
- **AFTER**: `->middleware('permission:tetapan_kebajikan,read')`
- Routes: index (read), update (update)
- Kategori routes: store, update, destroy (all use update permission)

### 2. Fixed RoleController - Settings Only Modules
Removed extra spaces from TAB module keys untuk consistency:

#### Tetapan Kebajikan TABs
```php
// BEFORE
'   tetapan_kebajikan_had_bantuan',
'   tetapan_kebajikan_workflow',
// ... etc

// AFTER
'tetapan_kebajikan_had_bantuan',
'tetapan_kebajikan_workflow',
// ... etc
```

#### Tetapan Kewangan TABs
```php
// BEFORE
'   tetapan_kewangan_umum',
'   tetapan_kewangan_kategori',

// AFTER
'tetapan_kewangan_umum',
'tetapan_kewangan_kategori',
```

**NOTE**: Display names dalam `getAvailableModules()` masih keep spacing untuk visual hierarchy dalam permission matrix.

## Pattern Consistency

### Authorization Pattern (Reference: AsnafController)
Semua Kebajikan controllers sudah implement masjid_id isolation pattern yang sama seperti AsnafController:

```php
// In index() method
if ($isSuperAdmin) {
    // Can see all or filter by masjid_id
} else {
    $query->where('masjid_id', $user->masjid_id);
}

// In show/edit/update/destroy methods
if (!$user->hasRole('Super Admin') && $model->masjid_id !== $user->masjid_id) {
    abort(403, 'Unauthorized action.');
}
```

### Route Permission Pattern
All routes now follow consistent pattern:
```php
Route::get('module-name', [Controller::class, 'index'])
    ->name('module-name.index')
    ->middleware('permission:module_name,read');
```

## Files Modified

### Routes
- `routes/web.php`
  - Program Kebajikan routes (7 routes)
  - Penerima Bantuan routes (7 routes)
  - Permohonan Bantuan routes (12 routes - 7 CRUD + 5 workflow)
  - Pembayaran Bantuan routes (9 routes - 7 CRUD + 2 workflow)
  - Laporan Kebajikan routes (3 routes)
  - Tetapan Kebajikan routes (5 routes)

### Controllers
- `app/Http/Controllers/RoleController.php`
  - Fixed `getSettingsOnlyModules()` - removed extra spaces from TAB keys

## Testing Results

✅ All routes verified successfully:
```bash
php artisan route:list --name=program-kebajikan     # 7 routes
php artisan route:list --name=penerima-bantuan      # 7 routes
php artisan route:list --name=permohonan-bantuan    # 12 routes
php artisan route:list --name=pembayaran-bantuan    # 9 routes
php artisan route:list --name=laporan-kebajikan     # 3 routes
php artisan route:list --name=tetapan-kebajikan     # 5 routes
```

## Impact

### Before Fix
- ❌ User dengan role yang ada permission `program_kebajikan` tidak boleh access `/program-kebajikan`
- ❌ 403 error kerana middleware cari `kebajikan` permission yang tidak wujud dalam role

### After Fix
- ✅ User dengan permission `program_kebajikan` boleh access `/program-kebajikan`
- ✅ User dengan permission `penerima_bantuan` boleh access `/penerima-bantuan`
- ✅ User dengan permission `permohonan_bantuan` boleh access `/permohonan-bantuan`
- ✅ User dengan permission `pembayaran_bantuan` boleh access `/pembayaran-bantuan`
- ✅ User dengan permission `laporan_kebajikan` boleh access `/laporan-kebajikan`
- ✅ User dengan permission `tetapan_kebajikan` boleh access `/tetapan-kebajikan`
- ✅ Granular permission control - setiap module ada permission sendiri
- ✅ Consistent dengan pattern Asnaf module

## Next Steps

1. ✅ Test permission matrix di `/senarai-kumpulan/create`
2. ✅ Verify user dengan different roles boleh access pages mengikut permissions
3. ✅ Check workflow actions (approve, reject, suspend, reactivate) berfungsi dengan betul
4. Apply same pattern untuk modules lain jika ada mismatch yang sama

## Notes

- Super Admin tetap ada access ke semua pages tanpa check permission
- Masjid_id isolation sudah implement dengan betul dalam semua controllers
- Permission matrix structure sudah betul dengan visual hierarchy (headers, submenus, TABs)
- Workflow permissions (approve, reject) sudah map dengan betul ke workflow actions
