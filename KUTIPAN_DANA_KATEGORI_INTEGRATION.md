# Kutipan Dana - Kategori Integration Complete

## Summary
Successfully integrated kategori dropdowns into all Kutipan Dana forms, replacing hardcoded options with dynamic data from `kategori_kewangan` table.

## Changes Made

### 1. Controller Updates (`app/Http/Controllers/KutipanDanaController.php`)

#### Updated Methods:
- `kutipanKariah()` - Added `$kariah` and `$kaedahBayaran` variables
- `dermaSumbangan()` - Added `$kaedahBayaran` variable  
- `kutipanZakat()` - Added `$kaedahBayaran` variable
- `kutipanLain()` - Added `$kaedahBayaran` variable
- `index()` - Fixed stats array structure to match view expectations

#### Stats Fix:
Changed from array of objects to simple associative array:
```php
$stats = [
    'kutipan_kariah' => (clone $baseQuery)->kutipanKariah()->sum('jumlah'),
    'derma_sumbangan' => (clone $baseQuery)->dermaSumbangan()->sum('jumlah'),
    'kutipan_zakat' => (clone $baseQuery)->kutipanZakat()->sum('jumlah'),
    'kutipan_lain' => (clone $baseQuery)->kutipanLain()->sum('jumlah'),
];
```

#### Validation:
Already updated in previous session - `kaedah_bayaran` validation changed from enum to `max:255`

### 2. View Updates

#### All 4 Forms Updated:
- `resources/views/kutipan-dana/kutipan-kariah.blade.php`
- `resources/views/kutipan-dana/derma-sumbangan.blade.php`
- `resources/views/kutipan-dana/kutipan-zakat.blade.php`
- `resources/views/kutipan-dana/kutipan-lain.blade.php`

#### Kaedah Bayaran Dropdown:
**Before (Hardcoded):**
```html
<select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
    <option value="">-- Pilih Kaedah --</option>
    <option value="Tunai">Tunai</option>
    <option value="Online Transfer">Online Transfer</option>
    <option value="Cek">Cek</option>
</select>
```

**After (Dynamic from Kategori):**
```html
<select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
    <option value="">-- Pilih Kaedah --</option>
    @foreach($kaedahBayaran as $kaedah)
        <option value="{{ $kaedah->nama_kategori }}" {{ old('kaedah_bayaran') == $kaedah->nama_kategori ? 'selected' : '' }}>
            {{ $kaedah->nama_kategori }}
        </option>
    @endforeach
</select>
```

### 3. Data Source

Kaedah Bayaran now pulls from `kategori_kewangan` table where:
- `jenis_kategori` = 'kaedah_bayaran'
- `status` = 'Aktif'
- Ordered by `urutan`

Available options (13 total):
1. Tunai
2. Online Banking
3. Kad Kredit
4. Kad Debit
5. Cek
6. Bank Draf
7. Wang Pos
8. E-Wallet (Touch 'n Go, GrabPay, etc)
9. QR Pay (DuitNow QR)
10. FPX
11. Pindahan Antarabangsa
12. Autodebit
13. Lain-lain

## Benefits

1. **Centralized Management**: Admin can add/edit/delete kaedah bayaran from Tetapan Kewangan
2. **Consistency**: Same payment methods across all modules (Transaksi Kewangan, Kutipan Dana, Perbelanjaan)
3. **Flexibility**: Each masjid can customize their own payment methods
4. **Maintainability**: No need to update code when adding new payment methods

## Testing Checklist

- [x] Controller passes `$kaedahBayaran` to all 4 form views
- [x] All 4 forms display dropdown correctly
- [x] Dropdown populated from database
- [x] Old values preserved on validation errors
- [x] Stats calculation fixed in index page
- [x] No diagnostic errors
- [x] Validation accepts any string value (max:255)

## Files Modified

1. `app/Http/Controllers/KutipanDanaController.php`
2. `resources/views/kutipan-dana/kutipan-kariah.blade.php`
3. `resources/views/kutipan-dana/derma-sumbangan.blade.php`
4. `resources/views/kutipan-dana/kutipan-zakat.blade.php`
5. `resources/views/kutipan-dana/kutipan-lain.blade.php`

## Bug Fixes

### Kariah Dropdown Issue
**Problem**: Error `Call to undefined method aktif()` on Kariah model
**Solution**: 
- Changed `aktif()` to `active()` (correct scope name in Kariah model)
- Changed `nama_penuh` to `nama` (correct field name)
- Changed `no_kariah` to `no_ic` (correct field name)

```php
// Before
$kariah = \App\Models\Kariah::where('masjid_id', $masjidId)->aktif()->orderBy('nama_penuh')->get();

// After
$kariah = \App\Models\Kariah::where('masjid_id', $masjidId)->active()->orderBy('nama')->get();
```

View updated to use correct field names:
```blade
{{ $k->nama }} - {{ $k->no_ic }}
```

## Status: ✅ COMPLETE

All Kutipan Dana forms now use dynamic kategori dropdowns. Bug fixed and ready for testing.
