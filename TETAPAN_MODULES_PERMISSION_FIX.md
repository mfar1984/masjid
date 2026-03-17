# Tetapan Modules - Permission Access Fix

## Masalah

User mendapat **403 error** bila access:
- `/tetapan-kebajikan`
- `/tetapan-asnaf`
- `/tetapan-kewangan`

**Root Cause**: 
- Routes check permission untuk parent module (`tetapan_kebajikan`, `tetapan_asnaf`, `tetapan_kewangan`)
- Tapi dalam permission matrix, parent modules ini adalah **HEADER** tanpa checkbox
- User hanya boleh enable checkbox untuk TABs di bawah parent (contoh: `tetapan_kebajikan_had_bantuan`, `tetapan_kebajikan_workflow`, dll)
- Menyebabkan user tidak boleh access page walaupun ada permission untuk TABs

## Struktur Permission Matrix

```
└─ Tetapan Kebajikan          ← HEADER (no checkbox)
   ├─ Had Bantuan             ← TAB (has checkbox: read, update)
   ├─ Workflow                ← TAB (has checkbox: read, update)
   ├─ Permohonan              ← TAB (has checkbox: read, update)
   ├─ Kategori Penerima       ← TAB (has checkbox: read, update)
   ├─ Pembayaran              ← TAB (has checkbox: read, update)
   ├─ Paparan                 ← TAB (has checkbox: read, update)
   └─ Kategori                ← TAB (has checkbox: read, update)
```

User enable checkbox untuk TABs, tapi route check permission untuk HEADER yang tidak ada checkbox.

## Penyelesaian

### Strategy
Remove middleware permission check dari routes, dan implement manual check dalam controller yang verify user ada permission untuk **ANY TAB** di bawah parent module.

### 1. Tetapan Kebajikan

#### Routes (routes/web.php)
```php
// BEFORE
Route::get('tetapan-kebajikan', [TetapanKebajikanController::class, 'index'])
    ->name('tetapan-kebajikan.index')
    ->middleware('permission:tetapan_kebajikan,read');  // ❌ Header module - no checkbox

// AFTER
Route::get('tetapan-kebajikan', [TetapanKebajikanController::class, 'index'])
    ->name('tetapan-kebajikan.index');  // ✅ No middleware - check in controller
```

#### Controller (TetapanKebajikanController.php)
```php
public function index()
{
    $user = Auth::user();
    $isSuperAdmin = $user->hasRole('Super Admin');
    
    // Check if user has permission to access ANY TAB
    if (!$isSuperAdmin) {
        $hasPermission = $user->hasPermission('tetapan_kebajikan_had_bantuan', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_had_bantuan', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_workflow', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_workflow', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_permohonan', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_permohonan', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_kategori_penerima', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_pembayaran', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_pembayaran', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_paparan', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_paparan', 'update') ||
                       $user->hasPermission('tetapan_kebajikan_kategori', 'read') ||
                       $user->hasPermission('tetapan_kebajikan_kategori', 'update');
        
        if (!$hasPermission) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    
    // ... rest of code
}
```

### 2. Tetapan Asnaf

#### Routes (routes/web.php)
```php
// BEFORE
Route::get('/tetapan-asnaf', [TetapanAsnafController::class, 'index'])
    ->middleware('permission:asnaf,read')  // ❌ Wrong module name
    ->name('tetapan-asnaf.index');

// AFTER
Route::get('/tetapan-asnaf', [TetapanAsnafController::class, 'index'])
    ->name('tetapan-asnaf.index');  // ✅ No middleware
```

#### Controller (TetapanAsnafController.php)
```php
public function index()
{
    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('Super Admin');
    
    // Check if user has permission to access ANY TAB
    if (!$isSuperAdmin) {
        $hasPermission = $user->hasPermission('tetapan_asnaf_had_kifayah', 'read') ||
                       $user->hasPermission('tetapan_asnaf_had_kifayah', 'update') ||
                       $user->hasPermission('tetapan_asnaf_had_bantuan', 'read') ||
                       $user->hasPermission('tetapan_asnaf_had_bantuan', 'update') ||
                       $user->hasPermission('tetapan_asnaf_workflow', 'read') ||
                       $user->hasPermission('tetapan_asnaf_workflow', 'update') ||
                       $user->hasPermission('tetapan_asnaf_permohonan', 'read') ||
                       $user->hasPermission('tetapan_asnaf_permohonan', 'update') ||
                       $user->hasPermission('tetapan_asnaf_kategori', 'read') ||
                       $user->hasPermission('tetapan_asnaf_kategori', 'update') ||
                       $user->hasPermission('tetapan_asnaf_payment', 'read') ||
                       $user->hasPermission('tetapan_asnaf_payment', 'update') ||
                       $user->hasPermission('tetapan_asnaf_display', 'read') ||
                       $user->hasPermission('tetapan_asnaf_display', 'update');
        
        if (!$hasPermission) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    
    // ... rest of code
}
```

### 3. Tetapan Kewangan

#### Routes (routes/web.php)
```php
// BEFORE
Route::get('tetapan-kewangan', [TetapanKewanganController::class, 'index'])
    ->name('tetapan-kewangan.index')
    ->middleware('permission:kewangan,read');  // ❌ Wrong module name

// AFTER
Route::get('tetapan-kewangan', [TetapanKewanganController::class, 'index'])
    ->name('tetapan-kewangan.index');  // ✅ No middleware
```

#### Controller (TetapanKewanganController.php)
```php
public function index(Request $request)
{
    $user = Auth::user();
    $isSuperAdmin = $user->hasRole('Super Admin');
    
    // Check if user has permission to access ANY TAB
    if (!$isSuperAdmin) {
        $hasPermission = $user->hasPermission('tetapan_kewangan_umum', 'read') ||
                       $user->hasPermission('tetapan_kewangan_umum', 'update') ||
                       $user->hasPermission('tetapan_kewangan_kategori', 'read') ||
                       $user->hasPermission('tetapan_kewangan_kategori', 'update');
        
        if (!$hasPermission) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    
    // ... rest of code
}
```

## Files Modified

### Routes
1. `routes/web.php`
   - Tetapan Kebajikan routes (2 routes)
   - Tetapan Asnaf routes (2 routes)
   - Tetapan Kewangan routes (5 routes)

### Controllers
1. `app/Http/Controllers/TetapanKebajikanController.php` - Added permission check for 7 TABs
2. `app/Http/Controllers/TetapanAsnafController.php` - Added permission check for 7 TABs
3. `app/Http/Controllers/TetapanKewanganController.php` - Added permission check for 2 TABs

## Permission Logic

### Super Admin
- ✅ Bypass all permission checks
- ✅ Full access to all Tetapan pages

### Regular User
- ✅ Can access Tetapan page IF has permission for ANY TAB
- ✅ Permission check uses OR logic - need at least 1 TAB permission
- ✅ Can have read OR update permission for any TAB
- ❌ Cannot access if no TAB permissions at all

## Testing Results

✅ Build successful - no errors
```bash
npm run build
# Exit Code: 0
```

## Expected Behavior After Fix

### Before Fix
- ❌ User enable checkbox untuk TABs tapi tetap 403 error
- ❌ Route check permission untuk HEADER yang tidak wujud
- ❌ Tidak boleh access walaupun ada permission untuk TABs

### After Fix
- ✅ User dengan permission untuk ANY TAB boleh access page
- ✅ User dengan `tetapan_kebajikan_had_bantuan.read` boleh access `/tetapan-kebajikan`
- ✅ User dengan `tetapan_asnaf_workflow.update` boleh access `/tetapan-asnaf`
- ✅ User dengan `tetapan_kewangan_kategori.read` boleh access `/tetapan-kewangan`
- ✅ Flexible - user hanya perlu 1 TAB permission untuk access parent page
- ✅ Consistent dengan pattern Integrasi module

## TAB Permissions Summary

### Tetapan Kebajikan (7 TABs)
1. `tetapan_kebajikan_had_bantuan`
2. `tetapan_kebajikan_workflow`
3. `tetapan_kebajikan_permohonan`
4. `tetapan_kebajikan_kategori_penerima`
5. `tetapan_kebajikan_pembayaran`
6. `tetapan_kebajikan_paparan`
7. `tetapan_kebajikan_kategori`

### Tetapan Asnaf (7 TABs)
1. `tetapan_asnaf_had_kifayah`
2. `tetapan_asnaf_had_bantuan`
3. `tetapan_asnaf_workflow`
4. `tetapan_asnaf_permohonan`
5. `tetapan_asnaf_kategori`
6. `tetapan_asnaf_payment`
7. `tetapan_asnaf_display`

### Tetapan Kewangan (2 TABs)
1. `tetapan_kewangan_umum`
2. `tetapan_kewangan_kategori`

## Notes

- Pattern ini consistent dengan Integrasi module yang juga ada TABs
- Super Admin tetap bypass semua checks
- Permission check guna OR logic - flexible untuk user
- Setiap TAB boleh ada read dan/atau update permission
- Controller check dilakukan sebelum load data untuk efficiency
