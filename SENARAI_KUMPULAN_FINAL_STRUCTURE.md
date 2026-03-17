# Senarai Kumpulan - Final Structure with Headers & Submenus ✅

## Summary
Permission matrix sekarang ada HEADER modules dengan submenu yang ada visual indentation, sama seperti struktur navbar.

## Final Structure (35 rows total)

### 📊 PAPARAN PEMUKA (1 row)
```
Papan Pemuka                          │ - │ ☐ │ - │ - │ - │ - │ - │ -
```

### 👥 PENGURUSAN (20 rows)

**Ahli Kariah (standalone):**
```
Ahli Kariah                           │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐
```

**Ahli Jawatankuasa Masjid (header + 3 submenus):**
```
Ahli Jawatankuasa Masjid              │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
├─ Senarai AJK                        │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐
├─ Arkib AJK                          │ - │ ☐ │ - │ - │ - │ - │ - │ -
└─ Laporan AJK                        │ - │ ☐ │ - │ - │ - │ - │ - │ -
```

**Asnaf (header + 5 submenus):**
```
Asnaf                                 │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
├─ Senarai Asnaf                      │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐
├─ Permohonan Zakat                   │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ - │ -
├─ Agihan Zakat                       │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Laporan Zakat                      │ - │ ☐ │ - │ - │ - │ - │ - │ -
└─ Tetapan Asnaf                      │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
```

**Kebajikan (header + 6 submenus):**
```
Kebajikan                             │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
├─ Program Kebajikan                  │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Penerima Bantuan                   │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Permohonan Bantuan                 │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ ☐ │ - │ -
├─ Pembayaran Bantuan                 │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Laporan Kebajikan                  │ - │ ☐ │ - │ - │ - │ - │ - │ -
└─ Tetapan Kebajikan                  │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
```

### 💰 KEWANGAN (5 rows)

**Kewangan (header + 4 submenus):**
```
Kewangan                              │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
├─ Akaun Bank                         │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Transaksi Kewangan                 │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
├─ Laporan Kewangan                   │ - │ ☐ │ - │ - │ - │ - │ - │ -
└─ Tetapan Kewangan                   │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
```

### 📁 FAIL (2 rows)

**Fail (header + 1 submenu):**
```
Fail                                  │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
└─ Pengurusan Dokumen                 │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
```

### ⚙️ PENTADBIRAN SISTEM (8 rows)

**Standalone modules:**
```
Tetapan Umum                          │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
Senarai Masjid                        │ 🚫│ 🚫│ 🚫│ 🚫│ 🚫│ 🚫│ 🚫│ 🚫  ← SUPER ADMIN
Senarai Pengguna                      │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ ☐ │ ☐
Senarai Kumpulan                      │ ☐ │ ☐ │ ☐ │ ☐ │ - │ - │ - │ -
```

**Integrasi (header + 3 submenus):**
```
Integrasi                             │ - │ - │ - │ - │ - │ - │ - │ -  ← HEADER
├─ Email (SMTP)                       │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
├─ Cuaca                              │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
└─ API                                │ - │ ☐ │ ☐ │ - │ - │ - │ - │ -
```

## Module Keys Mapping

### Header Modules (No Checkboxes):
```php
'ajk_header' => 'Ahli Jawatankuasa Masjid',
'asnaf_header' => 'Asnaf',
'kebajikan_header' => 'Kebajikan',
'kewangan_header' => 'Kewangan',
'fail' => 'Fail',
'integrations_header' => 'Integrasi',
```

### Submenu Modules (With Indentation):
```php
// AJK Submenus
'ajk' => '├─ Senarai AJK',
'ajk_arkib' => '├─ Arkib AJK',
'ajk_laporan' => '└─ Laporan AJK',

// Asnaf Submenus
'asnaf' => '├─ Senarai Asnaf',
'permohonan_zakat' => '├─ Permohonan Zakat',
'agihan_zakat' => '├─ Agihan Zakat',
'laporan_zakat' => '├─ Laporan Zakat',
'tetapan_asnaf' => '└─ Tetapan Asnaf',

// Kebajikan Submenus
'program_kebajikan' => '├─ Program Kebajikan',
'penerima_bantuan' => '├─ Penerima Bantuan',
'permohonan_bantuan' => '├─ Permohonan Bantuan',
'pembayaran_bantuan' => '├─ Pembayaran Bantuan',
'laporan_kebajikan' => '├─ Laporan Kebajikan',
'tetapan_kebajikan' => '└─ Tetapan Kebajikan',

// Kewangan Submenus
'akaun_bank' => '├─ Akaun Bank',
'transaksi_kewangan' => '├─ Transaksi Kewangan',
'laporan_kewangan' => '├─ Laporan Kewangan',
'tetapan_kewangan' => '└─ Tetapan Kewangan',

// Fail Submenu
'documents' => '└─ Pengurusan Dokumen',

// Integrasi Submenus
'integrations_email' => '├─ Email (SMTP)',
'integrations_weather' => '├─ Cuaca',
'integrations_api' => '└─ API',
```

## Controller Methods Updated

### 1. `getAvailableModules()` - 35 modules
- Added header modules with `_header` suffix
- Added submenu modules with tree characters (├─ └─)
- Maintains visual hierarchy

### 2. `getHeaderModules()` - NEW METHOD
```php
private function getHeaderModules()
{
    return [
        'fail',
        'ajk_header',
        'asnaf_header',
        'kebajikan_header',
        'kewangan_header',
        'integrations_header',
    ];
}
```

### 3. `getReadOnlyModules()` - Updated
```php
'dashboard',
'ajk_arkib',      // NEW
'ajk_laporan',    // NEW
'laporan_zakat',
'laporan_kebajikan',
'laporan_kewangan',
```

### 4. `getSettingsOnlyModules()` - Updated
```php
'tetapan_asnaf',
'tetapan_kebajikan',
'tetapan_kewangan',
'settings',
'integrations_email',    // NEW
'integrations_weather',  // NEW
'integrations_api',      // NEW
```

### 5. `getWorkflowModules()` - Same
```php
'kariah',
'ajk',
'asnaf',
'permohonan_zakat',
'masjids',
'users',
```

### 6. `getPartialWorkflowModules()` - Same
```php
'permohonan_zakat',
```

## View Files Updated

### All 3 files updated with header module logic:
1. `resources/views/pentadbiran/kumpulan/create.blade.php`
2. `resources/views/pentadbiran/kumpulan/edit.blade.php`
3. `resources/views/pentadbiran/kumpulan/show.blade.php`

**Logic Added:**
```blade
@if(in_array($moduleKey, $headerModules))
    {{-- Header Modules: Tiada checkbox --}}
    <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
        <span class="material-icons">remove</span>
    </span>
@elseif(...)
```

## Visual Hierarchy

### Tree Characters Used:
- `├─` = Middle item in list
- `└─` = Last item in list
- No prefix = Standalone or header

### Example Display:
```
Asnaf                                 ← HEADER (no checkboxes)
├─ Senarai Asnaf                      ← Submenu (full CRUD + workflow)
├─ Permohonan Zakat                   ← Submenu (full CRUD + partial workflow)
├─ Agihan Zakat                       ← Submenu (full CRUD)
├─ Laporan Zakat                      ← Submenu (read only)
└─ Tetapan Asnaf                      ← Submenu (settings only)
```

## Benefits

### 1. Visual Clarity
✅ Clear parent-child relationship
✅ Easy to see which items belong to which group
✅ Matches navbar structure exactly

### 2. Better UX
✅ Users can quickly identify module groups
✅ Indentation shows hierarchy
✅ Tree characters provide visual guide

### 3. Maintainability
✅ Easy to add new submenus under existing headers
✅ Clear separation between headers and submenus
✅ Consistent with navigation pattern

## Total Count

- **Total Rows**: 35
- **Header Rows**: 6 (no checkboxes)
- **Submenu Rows**: 23 (with indentation)
- **Standalone Rows**: 6 (no indentation)

## Status
✅ **COMPLETE** - Permission matrix dengan header dan submenu yang betul

## Testing
Navigate to `/senarai-kumpulan/create` untuk tengok struktur baru dengan:
- Headers yang tiada checkbox
- Submenus dengan indentation (├─ └─)
- Visual hierarchy yang jelas
