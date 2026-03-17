# Laporan Kewangan - Masjid Filter for Super Admin

## Issue
Dropdown "Semua Akaun Bank" sepatutnya untuk filter Akaun Bank sahaja. Untuk Super Admin, perlu ada dropdown berasingan untuk pilih Masjid supaya boleh lihat laporan kewangan masjid lain.

## Solution
Tambah dropdown "Pilih Masjid" untuk Super Admin di bahagian filter Laporan Kewangan.

## Changes Made

### 1. Controller Update (`app/Http/Controllers/LaporanKewanganController.php`)

**Added:**
```php
// Get all masjids for Super Admin dropdown
$masjids = $isSuperAdmin ? \App\Models\Masjid::orderBy('nama_masjid')->get() : collect();
```

**Updated compact() to include:**
```php
'masjids',
'masjidId',
```

### 2. View Update (`resources/views/laporan-kewangan/index.blade.php`)

**Added Masjid Dropdown (Super Admin only):**
```blade
@if($isSuperAdmin)
<select name="masjid_id" class="px-3 py-2 border border-gray-300 rounded text-xs">
    <option value="">Pilih Masjid</option>
    @foreach($masjids as $masjid)
        <option value="{{ $masjid->id }}" {{ $masjidId == $masjid->id ? 'selected' : '' }}>
            {{ $masjid->nama_masjid }}
        </option>
    @endforeach
</select>
@endif
```

## Filter Order (Updated)
1. **Pilih Masjid** (Super Admin only) ✨ NEW
2. Tarikh Dari
3. Tarikh Hingga
4. Semua Akaun Bank (filter by bank account)
5. Button Cari
6. Button Reset

## How It Works

### For Super Admin:
1. Dropdown "Pilih Masjid" muncul di bahagian filter
2. Boleh pilih masjid untuk lihat laporan kewangan masjid tersebut
3. Dropdown "Semua Akaun Bank" akan show akaun bank untuk masjid yang dipilih
4. Jika tidak pilih masjid, akan guna masjid default Super Admin

### For Regular Users:
1. Dropdown "Pilih Masjid" tidak muncul
2. Hanya boleh lihat laporan kewangan masjid sendiri
3. Dropdown "Semua Akaun Bank" show akaun bank masjid sendiri sahaja

## Logic Flow
```
1. User access Laporan Kewangan
2. Controller check: isSuperAdmin?
   - YES: Load all masjids, allow masjid_id filter
   - NO: Use user's masjid_id only
3. Get masjid_id from:
   - Request (if Super Admin selected)
   - OR user's masjid_id (default)
4. Load data based on selected masjid_id
5. Display filters:
   - Super Admin: Masjid + Date + Bank
   - Regular User: Date + Bank only
```

## Design Standards
✅ Font: Poppins (inherited)
✅ Font size: 12px (text-xs)
✅ Border radius: 4px (rounded class)
✅ Consistent styling with other filters
✅ Responsive layout (flex-col md:flex-row)

## Testing Checklist
- [ ] Super Admin can see "Pilih Masjid" dropdown
- [ ] Regular users cannot see "Pilih Masjid" dropdown
- [ ] Super Admin can select different masjid
- [ ] Data changes when different masjid selected
- [ ] "Semua Akaun Bank" shows correct banks for selected masjid
- [ ] Reset button clears all filters
- [ ] Selected masjid persists after form submit

## Benefits
1. **Clear Separation**: Masjid filter vs Bank filter
2. **Super Admin Flexibility**: Can view any masjid's reports
3. **User-Friendly**: Clear dropdown labels
4. **Consistent UX**: Follows same pattern as other filters
5. **Permission-Based**: Only Super Admin sees masjid dropdown

## Status
✅ **COMPLETE** - Masjid filter dropdown added for Super Admin in Laporan Kewangan.
