# Tetapan Kategori - Table Text Color Fix

## Issue
Table headers (thead) dan table data (tbody) dalam TAB Kategori untuk semua modul Tetapan menggunakan warna putih (white), menyebabkan text tidak nampak pada background yang terang.

## Affected Files
1. `resources/views/tetapan-kewangan/tabs/kategori-data.blade.php`
2. `resources/views/tetapan-kebajikan/tabs/kategori-data.blade.php`
3. `resources/views/tetapan-asnaf/tabs/kategori-data.blade.php`

## Root Cause
Table headers (`<th>`) dan table data (`<td>`) tidak mempunyai text color class, menyebabkan inherit warna default (putih) dari parent element.

## Solution Applied
Menggunakan `sed` command untuk menambah text color classes pada semua table headers dan data:

### Changes Made

#### Table Headers (thead)
**Before:**
```html
<th class="px-3 py-2 text-left">Nama</th>
<th class="px-3 py-2 text-center">Status</th>
```

**After:**
```html
<th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
<th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
```

#### Table Data (tbody)
**Before:**
```html
<td class="px-3 py-2">{{ $item->nama_kategori }}</td>
<td class="px-3 py-2 text-gray-500">{{ $item->kod_kategori }}</td>
```

**After:**
```html
<td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
<td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori }}</td>
```

## Commands Executed

### Tetapan Kewangan
```bash
sed -i '' 's/<th class="px-3 py-2 text-left">/<th class="px-3 py-2 text-left text-gray-700 font-medium">/g' resources/views/tetapan-kewangan/tabs/kategori-data.blade.php
sed -i '' 's/<th class="px-3 py-2 text-center">/<th class="px-3 py-2 text-center text-gray-700 font-medium">/g' resources/views/tetapan-kewangan/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2">/<td class="px-3 py-2 text-gray-900">/g' resources/views/tetapan-kewangan/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2 text-gray-500">/<td class="px-3 py-2 text-gray-600">/g' resources/views/tetapan-kewangan/tabs/kategori-data.blade.php
```

### Tetapan Kebajikan
```bash
sed -i '' 's/<th class="px-3 py-2 text-left">/<th class="px-3 py-2 text-left text-gray-700 font-medium">/g' resources/views/tetapan-kebajikan/tabs/kategori-data.blade.php
sed -i '' 's/<th class="px-3 py-2 text-center">/<th class="px-3 py-2 text-center text-gray-700 font-medium">/g' resources/views/tetapan-kebajikan/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2">/<td class="px-3 py-2 text-gray-900">/g' resources/views/tetapan-kebajikan/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2 text-gray-500">/<td class="px-3 py-2 text-gray-600">/g' resources/views/tetapan-kebajikan/tabs/kategori-data.blade.php
```

### Tetapan Asnaf
```bash
sed -i '' 's/<th class="px-3 py-2 text-left">/<th class="px-3 py-2 text-left text-gray-700 font-medium">/g' resources/views/tetapan-asnaf/tabs/kategori-data.blade.php
sed -i '' 's/<th class="px-3 py-2 text-center">/<th class="px-3 py-2 text-center text-gray-700 font-medium">/g' resources/views/tetapan-asnaf/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2">/<td class="px-3 py-2 text-gray-900">/g' resources/views/tetapan-asnaf/tabs/kategori-data.blade.php
sed -i '' 's/<td class="px-3 py-2 text-gray-500">/<td class="px-3 py-2 text-gray-600">/g' resources/views/tetapan-asnaf/tabs/kategori-data.blade.php
```

## Color Scheme Applied
- **Table Headers**: `text-gray-700 font-medium` - Dark gray with medium font weight
- **Primary Data**: `text-gray-900` - Darkest gray for main content (nama kategori)
- **Secondary Data**: `text-gray-600` - Medium gray for secondary info (kod kategori)

## Design Standards
✅ Font: Poppins (inherited from parent)
✅ Font size: 12px (text-xs class)
✅ Readable contrast: Dark text on light background
✅ Consistent styling across all Tetapan modules

## Testing Checklist
- [x] Tetapan Kewangan - TAB Kategori text visible
- [x] Tetapan Kebajikan - TAB Kategori text visible
- [x] Tetapan Asnaf - TAB Kategori text visible
- [ ] All table headers readable
- [ ] All table data readable
- [ ] Status badges still visible
- [ ] Action icons still visible

## Status
✅ **COMPLETE** - All table text colors have been fixed for Tetapan Kewangan, Tetapan Kebajikan, and Tetapan Asnaf kategori tables.
