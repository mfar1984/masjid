# KEBAJIKAN MODULE - UI PATTERN GUIDE

## Reference: Kariah Module
**Source**: `resources/views/kariah/index.blade.php`

---

## EXACT PATTERN TO FOLLOW

### 1. FONT SIZES
```html
<!-- Page Title -->
<h1 class="text-xl font-bold text-gray-900 mb-1">

<!-- Subtitle -->
<p class="text-xs text-gray-600">

<!-- Button Text -->
<span class="text-xs">

<!-- Table Header -->
<th class="px-4 py-2 table-header">  <!-- text-xs implicit -->

<!-- Table Data -->
<td class="px-4 py-2 table-data">  <!-- text-sm implicit -->

<!-- Mobile Title -->
<h3 class="mobile-title text-gray-900">  <!-- text-sm implicit -->

<!-- Mobile Subtitle -->
<p class="mobile-subtitle text-gray-500">  <!-- text-xs implicit -->

<!-- Mobile Label -->
<p class="mobile-label text-gray-500 mb-1">  <!-- text-xs implicit -->

<!-- Mobile Data -->
<span class="mobile-data text-gray-900">  <!-- text-xs implicit -->
```

### 2. ICON SIZES
```html
<!-- Button Icons -->
<span class="material-icons mr-2" style="font-size: 16px !important;">add</span>

<!-- Empty State Icons -->
<span class="material-icons mb-2" style="font-size: 48px !important;">people</span>

<!-- Action Icons (in x-action-icons component) -->
<!-- text-[8px] for icon buttons -->
```

### 3. BUTTON HEIGHTS
```html
<!-- All Action Buttons -->
<a href="#" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
```

### 4. TABLE STRUCTURE

#### Desktop Table
```html
<div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
    <table class="min-w-full text-left text-sm">
        <thead class="bg-blue-100 text-gray-600">
            <tr>
                <th class="px-4 py-2 table-header">Column Name</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-white">
                <td class="px-4 py-2 table-data">
                    <div class="table-data-important text-gray-900">Main Info</div>
                    <div class="table-data text-gray-500">Sub Info</div>
                </td>
                <td class="px-4 py-2 table-data text-gray-600">Regular Data</td>
            </tr>
        </tbody>
    </table>
</div>
```

#### Mobile Card View
```html
<div class="md:hidden space-y-3">
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <!-- Header with Name and Actions -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex-1">
                <div class="flex items-center mb-1">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                        <span class="text-xs font-medium text-blue-600">A</span>
                    </div>
                    <h3 class="mobile-title text-gray-900">Name</h3>
                </div>
                <p class="mobile-subtitle text-gray-500">Subtitle</p>
            </div>
            <x-action-icons ... layout="mobile" />
        </div>

        <!-- Details -->
        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <p class="mobile-label text-gray-500 mb-1">Label</p>
                <span class="mobile-data text-gray-900">Data</span>
            </div>
        </div>
    </div>
</div>
```

### 5. STATUS BADGES
```html
<!-- Success/Active -->
<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
    Aktif
</span>

<!-- Danger/Inactive -->
<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">
    Tidak Aktif
</span>

<!-- Warning -->
<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">
    Menunggu
</span>

<!-- Info -->
<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
    Baharu
</span>
```

### 6. COLORS

#### Background Colors
- Page background: `bg-gray-50`
- Container: `bg-white`
- Table background: `bg-gray-50`
- Table header: `bg-blue-100`
- Table row hover: `hover:bg-white`

#### Text Colors
- Primary heading: `text-gray-900`
- Secondary text: `text-gray-600`
- Table header: `text-gray-600`
- Table data: `text-gray-600` or `text-gray-900` (for important)
- Muted text: `text-gray-500`

#### Button Colors
- Primary: `bg-blue-600 hover:bg-blue-700`
- Success: `bg-green-600 hover:bg-green-700`
- Danger: `bg-red-600 hover:bg-red-700`
- Secondary: `bg-gray-200 hover:bg-gray-300`

### 7. BORDER RADIUS
- Buttons: `rounded` (4px)
- Cards: `rounded-lg` (8px)
- Badges: `rounded-sm` (2px)
- Table container: `rounded-xs` (minimal)

### 8. SPACING
- Container padding: `p-6`
- Section margin bottom: `mb-6`
- Table cell padding: `px-4 py-2`
- Mobile card padding: `p-4`
- Gap between elements: `gap-3` or `gap-4`

---

## CSS CLASSES REFERENCE

### Custom Classes (from app.css)
```css
.table-header {
    font-size: 0.75rem;  /* 12px */
    font-weight: 600;
}

.table-data {
    font-size: 0.875rem;  /* 14px */
}

.table-data-important {
    font-size: 0.875rem;  /* 14px */
    font-weight: 600;
}

.mobile-title {
    font-size: 0.875rem;  /* 14px */
    font-weight: 600;
}

.mobile-subtitle {
    font-size: 0.75rem;  /* 12px */
}

.mobile-label {
    font-size: 0.75rem;  /* 12px */
    font-weight: 500;
}

.mobile-data {
    font-size: 0.75rem;  /* 12px */
    font-weight: 600;
}
```

---

## CHANGES NEEDED FOR KEBAJIKAN MODULES

### Program Kebajikan Index
**File**: `resources/views/program-kebajikan/index.blade.php`

**Line 112-119**: Change table headers
```html
<!-- FROM -->
<th class="px-4 py-3 font-semibold text-xs">Kod Program</th>

<!-- TO -->
<th class="px-4 py-2 table-header">Kod Program</th>
```

**Line 125+**: Change table data cells
```html
<!-- FROM -->
<td class="px-4 py-3 text-xs text-gray-900">

<!-- TO -->
<td class="px-4 py-2 table-data">
    <div class="table-data-important text-gray-900">{{ $program->kod_program }}</div>
    <div class="table-data text-gray-500">{{ $program->nama_program }}</div>
</td>
```

**Line 125+**: Change table rows
```html
<!-- FROM -->
<tr class="hover:bg-gray-50 transition-colors">

<!-- TO -->
<tr class="hover:bg-white">
```

### Penerima Bantuan Index
**File**: `resources/views/penerima-bantuan/index.blade.php`

**Same changes as Program Kebajikan**:
1. Table headers: `py-3 font-semibold text-xs` → `py-2 table-header`
2. Table data: `py-3 text-xs` → `py-2 table-data`
3. Important data: Add `<div class="table-data-important">`
4. Row hover: `hover:bg-gray-50` → `hover:bg-white`

### Permohonan Bantuan Index
**File**: `resources/views/permohonan-bantuan/index.blade.php`

**Same changes as above**

---

## VERIFICATION CHECKLIST

### Desktop Table
- [ ] Table header: `bg-blue-100 text-gray-600`
- [ ] Table header cells: `px-4 py-2 table-header`
- [ ] Table body: `divide-y divide-gray-200`
- [ ] Table rows: `hover:bg-white`
- [ ] Table data cells: `px-4 py-2 table-data`
- [ ] Important data: `table-data-important text-gray-900`
- [ ] Secondary data: `table-data text-gray-500`

### Mobile Cards
- [ ] Card container: `bg-white border border-gray-200 rounded-lg p-4 shadow-sm`
- [ ] Title: `mobile-title text-gray-900`
- [ ] Subtitle: `mobile-subtitle text-gray-500`
- [ ] Label: `mobile-label text-gray-500 mb-1`
- [ ] Data: `mobile-data text-gray-900`

### Buttons
- [ ] Height: `h-[32px]`
- [ ] Text size: `text-xs`
- [ ] Icon size: `font-size: 16px !important;`
- [ ] Padding: `px-4 py-1`

### Status Badges
- [ ] Size: `text-xs font-medium`
- [ ] Padding: `px-2 py-1`
- [ ] Border radius: `rounded-sm`
- [ ] Colors: `bg-{color}-100 text-{color}-800`

---

## QUICK FIX COMMANDS

### Find and Replace Patterns

1. **Table Headers**
```bash
# Find: class="px-4 py-3 font-semibold text-xs"
# Replace: class="px-4 py-2 table-header"
```

2. **Table Data**
```bash
# Find: class="px-4 py-3 text-xs
# Replace: class="px-4 py-2 table-data
```

3. **Table Row Hover**
```bash
# Find: class="hover:bg-gray-50 transition-colors"
# Replace: class="hover:bg-white"
```

---

END OF GUIDE
