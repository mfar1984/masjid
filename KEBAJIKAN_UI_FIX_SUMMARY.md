# KEBAJIKAN UI FIX - SUMMARY

## Status: READY TO APPLY
**Date**: 13 December 2025
**Reference Pattern**: `resources/views/kariah/index.blade.php`

---

## FILES TO UPDATE

1. `resources/views/program-kebajikan/index.blade.php`
2. `resources/views/penerima-bantuan/index.blade.php`
3. `resources/views/permohonan-bantuan/index.blade.php`

---

## EXACT CHANGES NEEDED

### 1. TABLE HEADERS (All 3 files)

**FIND:**
```html
<th class="px-4 py-3 font-semibold text-xs">
```

**REPLACE WITH:**
```html
<th class="px-4 py-2 table-header">
```

**FIND:**
```html
<th class="px-4 py-3 font-semibold text-xs text-center">
```

**REPLACE WITH:**
```html
<th class="px-4 py-2 table-header text-center">
```

**Affected Lines:**
- **program-kebajikan/index.blade.php**: Lines 111-117 (7 headers)
- **penerima-bantuan/index.blade.php**: Lines 89-95 (7 headers)
- **permohonan-bantuan/index.blade.php**: Lines 93-100 (8 headers)

---

### 2. TABLE DATA CELLS (All 3 files)

**FIND patterns:**
```html
<td class="px-4 py-3 text-xs
<td class="px-4 py-3 text-sm
```

**REPLACE WITH:**
```html
<td class="px-4 py-2 table-data
```

**Example transformation:**
```html
<!-- BEFORE -->
<td class="px-4 py-3 text-xs text-gray-900 font-semibold">
    {{ $program->kod_program }}
</td>

<!-- AFTER -->
<td class="px-4 py-2 table-data">
    <div class="table-data-important text-gray-900">{{ $program->kod_program }}</div>
</td>
```

---

### 3. TABLE ROWS HOVER (All 3 files)

**FIND:**
```html
<tr class="hover:bg-gray-50 transition-colors">
```

**REPLACE WITH:**
```html
<tr class="hover:bg-white">
```

---

### 4. TBODY CLASS (All 3 files)

**FIND:**
```html
<tbody class="bg-white divide-y divide-gray-200">
```

**REPLACE WITH:**
```html
<tbody class="divide-y divide-gray-200">
```

---

## SPECIFIC FILE CHANGES

### Program Kebajikan (resources/views/program-kebajikan/index.blade.php)

**Lines 111-117**: Table headers
```html
<th class="px-4 py-2 table-header">Kod Program</th>
<th class="px-4 py-2 table-header">Nama Program</th>
<th class="px-4 py-2 table-header">Kategori</th>
<th class="px-4 py-2 table-header">Jenis Bantuan</th>
<th class="px-4 py-2 table-header">Had Maksimum</th>
<th class="px-4 py-2 table-header">Status</th>
<th class="px-4 py-2 table-header text-center">Tindakan</th>
```

**Lines 121+**: Table body - change tbody class
```html
<tbody class="divide-y divide-gray-200">
```

**Lines 123+**: Table rows
```html
<tr class="hover:bg-white">
```

**Lines 124+**: First column (Kod + Nama)
```html
<td class="px-4 py-2 table-data">
    <div class="table-data-important text-gray-900">{{ $program->kod_program }}</div>
    <div class="table-data text-gray-500">{{ $program->nama_program }}</div>
</td>
```

**Other columns**: Regular data
```html
<td class="px-4 py-2 table-data text-gray-600">{{ $program->kategori_program }}</td>
```

---

### Penerima Bantuan (resources/views/penerima-bantuan/index.blade.php)

**Lines 89-95**: Table headers
```html
<th class="px-4 py-2 table-header">No. Pendaftaran</th>
<th class="px-4 py-2 table-header">Nama Penuh</th>
<th class="px-4 py-2 table-header">No. KP</th>
<th class="px-4 py-2 table-header">No. Telefon</th>
<th class="px-4 py-2 table-header">Kategori</th>
<th class="px-4 py-2 table-header">Status</th>
<th class="px-4 py-2 table-header text-center">Tindakan</th>
```

**Lines 99+**: Table body
```html
<tbody class="divide-y divide-gray-200">
    @forelse($penerima as $item)
        <tr class="hover:bg-white">
            <td class="px-4 py-2 table-data">
                <div class="table-data-important text-gray-900">{{ $item->no_pendaftaran }}</div>
            </td>
            <td class="px-4 py-2 table-data">
                <div class="table-data-important text-gray-900">{{ $item->nama_penuh }}</div>
                <div class="table-data text-gray-500">{{ $item->no_kp }}</div>
            </td>
            <td class="px-4 py-2 table-data text-gray-600">{{ $item->no_kp }}</td>
            <td class="px-4 py-2 table-data text-gray-600">{{ $item->no_telefon }}</td>
            <!-- ... rest of columns -->
        </tr>
    @empty
        <!-- empty state unchanged -->
    @endforelse
</tbody>
```

---

### Permohonan Bantuan (resources/views/permohonan-bantuan/index.blade.php)

**Lines 93-100**: Table headers
```html
<th class="px-4 py-2 table-header">No. Permohonan</th>
<th class="px-4 py-2 table-header">Tarikh</th>
<th class="px-4 py-2 table-header">Nama Penerima</th>
<th class="px-4 py-2 table-header">Program</th>
<th class="px-4 py-2 table-header">Jumlah</th>
<th class="px-4 py-2 table-header">Keutamaan</th>
<th class="px-4 py-2 table-header">Status</th>
<th class="px-4 py-2 table-header text-center">Tindakan</th>
```

**Lines 104+**: Table body
```html
<tbody class="divide-y divide-gray-200">
    @forelse($permohonan as $item)
        <tr class="hover:bg-white">
            <td class="px-4 py-2 table-data">
                <div class="table-data-important text-gray-900">{{ $item->no_permohonan }}</div>
            </td>
            <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_permohonan->format('d/m/Y') }}</td>
            <td class="px-4 py-2 table-data">
                <div class="table-data-important text-gray-900">{{ $item->penerimaBantuan->nama_penuh }}</div>
                <div class="table-data text-gray-500">{{ $item->penerimaBantuan->no_kp }}</div>
            </td>
            <!-- ... rest of columns -->
        </tr>
    @empty
        <!-- empty state unchanged -->
    @endforelse
</tbody>
```

---

## VERIFICATION STEPS

After applying changes, verify:

1. **Desktop View**:
   - [ ] Table header background is light blue (`bg-blue-100`)
   - [ ] Table header text is gray (`text-gray-600`)
   - [ ] Table header padding is `px-4 py-2`
   - [ ] Table rows have white hover effect
   - [ ] Important data (names, codes) are bold
   - [ ] Secondary data (IC, etc) are gray and smaller

2. **Mobile View**:
   - [ ] Cards display correctly (no changes needed)
   - [ ] Action icons work properly

3. **Compare with Kariah**:
   - Open http://localhost:8000/kariah
   - Open http://localhost:8000/program-kebajikan
   - Compare table styling - should be IDENTICAL

---

## ESTIMATED TIME
- **Per file**: 5-10 minutes
- **Total**: 15-30 minutes

---

## NOTES
- Mobile card views are already correct - NO CHANGES NEEDED
- Empty states are correct - NO CHANGES NEEDED
- Pagination is correct - NO CHANGES NEEDED
- Only desktop table needs updates

---

END OF SUMMARY
