# Senarai Kumpulan - Permission Matrix Reorder ✅ COMPLETE

## Summary
Susun semula urutan kategori dalam permission matrix untuk Senarai Kumpulan (Roles) supaya follow struktur menu navbar yang sebenar, dan tambah 3 modules baru yang ada page tapi belum dalam matrix.

## Changes Made

### 1. RoleController.php - Updated Methods

#### A. `getAvailableModules()` - Reordered & Added New Modules

**BEFORE (17 modules):**
```php
'dashboard' => 'Paparan Pemuka',
'kariah' => 'Ahli Kariah',
'ajk' => 'Ahli Jawatankuasa Masjid',
'asnaf' => 'Asnaf',
'agihan_zakat' => 'Agihan Zakat',
'kebajikan' => 'Kebajikan',
'kewangan' => 'Kewangan',
'fail' => 'Fail',
'documents' => '- Pengurusan Dokumen',
'settings' => 'Tetapan Umum',
'masjids' => 'Senarai Masjid',
'users' => 'Senarai Pengguna',
'roles' => 'Senarai Kumpulan',
'integrations' => 'Integrasi',
'integrations_email' => '- Email (SMTP)',
'integrations_weather' => '- Cuaca',
'integrations_api' => '- API',
```

**AFTER (23 modules):**
```php
// ═══════════════════════════════════════════════════════════════
// 📊 PAPARAN PEMUKA
// ═══════════════════════════════════════════════════════════════
'dashboard' => 'Papan Pemuka',

// ═══════════════════════════════════════════════════════════════
// 👥 PENGURUSAN
// ═══════════════════════════════════════════════════════════════
'kariah' => 'Ahli Kariah',
'ajk' => 'Ahli Jawatankuasa Masjid',
'asnaf' => 'Asnaf',
'permohonan_zakat' => 'Permohonan Zakat', // ⭐ NEW
'agihan_zakat' => 'Agihan Zakat',
'laporan_zakat' => 'Laporan Zakat', // ⭐ NEW
'tetapan_asnaf' => 'Tetapan Asnaf', // ⭐ NEW
'program_kebajikan' => 'Program Kebajikan', // ⭐ NEW
'penerima_bantuan' => 'Penerima Bantuan', // ⭐ NEW
'permohonan_bantuan' => 'Permohonan Bantuan', // ⭐ NEW
'pembayaran_bantuan' => 'Pembayaran Bantuan', // ⭐ NEW
'laporan_kebajikan' => 'Laporan Kebajikan', // ⭐ NEW
'tetapan_kebajikan' => 'Tetapan Kebajikan', // ⭐ NEW

// ═══════════════════════════════════════════════════════════════
// 💰 KEWANGAN
// ═══════════════════════════════════════════════════════════════
'akaun_bank' => 'Akaun Bank', // ⭐ NEW
'transaksi_kewangan' => 'Transaksi Kewangan', // ⭐ NEW
'laporan_kewangan' => 'Laporan Kewangan', // ⭐ NEW
'tetapan_kewangan' => 'Tetapan Kewangan', // ⭐ NEW

// ═══════════════════════════════════════════════════════════════
// 📁 FAIL
// ═══════════════════════════════════════════════════════════════
'fail' => 'Fail',
'documents' => 'Pengurusan Dokumen',

// ═══════════════════════════════════════════════════════════════
// ⚙️ PENTADBIRAN SISTEM
// ═══════════════════════════════════════════════════════════════
'settings' => 'Tetapan Umum',
'masjids' => 'Senarai Masjid',
'users' => 'Senarai Pengguna',
'roles' => 'Senarai Kumpulan',
'integrations' => 'Integrasi',
```

**Changes:**
- ✅ Removed: `integrations_email`, `integrations_weather`, `integrations_api` (TABs, not separate modules)
- ✅ Removed: `kebajikan`, `kewangan` (generic placeholders)
- ✅ Added: 13 new specific modules with actual controllers and routes
- ✅ Reordered: Follow navbar menu structure exactly
- ✅ Added: Visual section separators with icons

#### B. `getReadOnlyModules()` - Added New Read-Only Modules

**BEFORE:**
```php
'dashboard', // Paparan Pemuka - view only
```

**AFTER:**
```php
'dashboard', // Papan Pemuka - view only
'laporan_zakat', // Laporan Zakat - view only
'laporan_kebajikan', // Laporan Kebajikan - view only
'laporan_kewangan', // Laporan Kewangan - view only
```

#### C. `getSettingsOnlyModules()` - Updated Settings Modules

**BEFORE:**
```php
'settings', // Tetapan Umum - read and update only
'integrations', // Integrasi - read and update only (header)
'integrations_email', // Email (SMTP) - read and update only
'integrations_weather', // Cuaca - read and update only
'integrations_api', // API - read and update only
```

**AFTER:**
```php
'tetapan_asnaf', // Tetapan Asnaf - read and update only
'tetapan_kebajikan', // Tetapan Kebajikan - read and update only
'tetapan_kewangan', // Tetapan Kewangan - read and update only
'settings', // Tetapan Umum - read and update only
'integrations', // Integrasi - read and update only
```

#### D. `getWorkflowModules()` - Updated Workflow Modules

**BEFORE:**
```php
'masjids', // Senarai Masjid - has approve/reject/suspend/reactivate
'users', // Senarai Pengguna - has suspend/reactivate (verify/unverify)
'kariah', // Ahli Kariah - has approve/reject/suspend/reactivate
'ajk', // Ahli Jawatankuasa Masjid - has approve/reject/suspend/reactivate
'asnaf', // Asnaf - has approve/reject/suspend/reactivate
```

**AFTER:**
```php
'kariah', // Ahli Kariah - has approve/reject/suspend/reactivate
'ajk', // Ahli Jawatankuasa Masjid - has approve/reject/suspend/reactivate
'asnaf', // Asnaf - has approve/reject/suspend/reactivate
'permohonan_zakat', // Permohonan Zakat - has approve/reject only
'masjids', // Senarai Masjid - has approve/reject/suspend/reactivate (Super Admin only)
'users', // Senarai Pengguna - has suspend/reactivate (verify/unverify)
```

#### E. `getPartialWorkflowModules()` - NEW METHOD

**NEW:**
```php
private function getPartialWorkflowModules()
{
    return [
        'permohonan_zakat', // Permohonan Zakat - approve/reject only
    ];
}
```

**Purpose:** Handle modules that only have approve/reject workflow, without suspend/reactivate.

### 2. View Files - Updated Permission Matrix Logic

#### Files Updated:
1. `resources/views/pentadbiran/kumpulan/create.blade.php`
2. `resources/views/pentadbiran/kumpulan/edit.blade.php`
3. `resources/views/pentadbiran/kumpulan/show.blade.php`

#### Changes in All 3 Files:

**Added `$partialWorkflowModules` to compact():**
```php
// BEFORE
compact('modules', 'actions', 'readOnlyModules', 'settingsOnlyModules', 'workflowModules', 'masjids')

// AFTER
compact('modules', 'actions', 'readOnlyModules', 'settingsOnlyModules', 'workflowModules', 'partialWorkflowModules', 'masjids')
```

**Added Partial Workflow Logic in Blade:**
```blade
@elseif(in_array($moduleKey, $partialWorkflowModules) && in_array($actionKey, ['approve', 'reject']))
    {{-- Partial Workflow Modules: Hanya approve dan reject sahaja --}}
    <input type="checkbox" ... >
@elseif(in_array($moduleKey, $partialWorkflowModules) && in_array($actionKey, ['suspend', 'reactivate']))
    {{-- Partial Workflow Modules: Tidak ada suspend/reactivate --}}
    <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
        <span class="material-icons">remove</span>
    </span>
```

## New Permission Matrix Structure

### 📊 PAPARAN PEMUKA (1 module)
| Module | Tambah | Lihat | Kemaskini | Padam | Terima | Tolak | Gantung | Aktif |
|--------|--------|-------|-----------|-------|--------|-------|---------|-------|
| Papan Pemuka | - | ☐ | - | - | - | - | - | - |

### 👥 PENGURUSAN (13 modules)
| Module | Tambah | Lihat | Kemaskini | Padam | Terima | Tolak | Gantung | Aktif |
|--------|--------|-------|-----------|-------|--------|-------|---------|-------|
| Ahli Kariah | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ |
| Ahli Jawatankuasa Masjid | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ |
| Asnaf | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ |
| Permohonan Zakat | ☐ | ☐ | ☐ | ☐ | ☐ | ☐ | - | - |
| Agihan Zakat | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Laporan Zakat | - | ☐ | - | - | - | - | - | - |
| Tetapan Asnaf | - | ☐ | ☐ | - | - | - | - | - |
| Program Kebajikan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Penerima Bantuan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Permohonan Bantuan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Pembayaran Bantuan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Laporan Kebajikan | - | ☐ | - | - | - | - | - | - |
| Tetapan Kebajikan | - | ☐ | ☐ | - | - | - | - | - |

### 💰 KEWANGAN (4 modules)
| Module | Tambah | Lihat | Kemaskini | Padam | Terima | Tolak | Gantung | Aktif |
|--------|--------|-------|-----------|-------|--------|-------|---------|-------|
| Akaun Bank | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Transaksi Kewangan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Laporan Kewangan | - | ☐ | - | - | - | - | - | - |
| Tetapan Kewangan | - | ☐ | ☐ | - | - | - | - | - |

### 📁 FAIL (2 modules)
| Module | Tambah | Lihat | Kemaskini | Padam | Terima | Tolak | Gantung | Aktif |
|--------|--------|-------|-----------|-------|--------|-------|---------|-------|
| Fail | - | - | - | - | - | - | - | - |
| Pengurusan Dokumen | ☐ | ☐ | ☐ | ☐ | - | - | - | - |

### ⚙️ PENTADBIRAN SISTEM (5 modules)
| Module | Tambah | Lihat | Kemaskini | Padam | Terima | Tolak | Gantung | Aktif |
|--------|--------|-------|-----------|-------|--------|-------|---------|-------|
| Tetapan Umum | - | ☐ | ☐ | - | - | - | - | - |
| Senarai Masjid | 🚫 | 🚫 | 🚫 | 🚫 | 🚫 | 🚫 | 🚫 | 🚫 |
| Senarai Pengguna | ☐ | ☐ | ☐ | ☐ | - | - | ☐ | ☐ |
| Senarai Kumpulan | ☐ | ☐ | ☐ | ☐ | - | - | - | - |
| Integrasi | - | ☐ | ☐ | - | - | - | - | - |

**Legend:**
- ☐ = Checkbox available
- \- = Not applicable
- 🚫 = Super Admin only (blocked for regular admins)

## Workflow Actions Mapping

### Full Workflow (All 4 actions: Terima/Tolak/Gantung/Aktif)
✅ **Ahli Kariah** - KariahController has all methods
✅ **Ahli Jawatankuasa Masjid** - AjkController has all methods
✅ **Asnaf** - AsnafController has all methods

### Partial Workflow (Terima/Tolak only)
✅ **Permohonan Zakat** - PermohonanZakatController has approve/reject only

### User Workflow (Gantung/Aktif only)
✅ **Senarai Pengguna** - UserController has verify/unverify (mapped to suspend/reactivate)

### No Workflow
✅ All other modules - No workflow actions

## Modules Added to Matrix

### ⭐ NEW (13 modules added):
1. **Permohonan Zakat** - Full CRUD + Workflow (approve/reject)
2. **Laporan Zakat** - View only
3. **Tetapan Asnaf** - Settings (read/update)
4. **Program Kebajikan** - Full CRUD
5. **Penerima Bantuan** - Full CRUD
6. **Permohonan Bantuan** - Full CRUD
7. **Pembayaran Bantuan** - Full CRUD
8. **Laporan Kebajikan** - View only
9. **Tetapan Kebajikan** - Settings (read/update)
10. **Akaun Bank** - Full CRUD
11. **Transaksi Kewangan** - Full CRUD
12. **Laporan Kewangan** - View only
13. **Tetapan Kewangan** - Settings (read/update)

### ✅ EXISTING (10 modules reordered):
1. Papan Pemuka
2. Ahli Kariah
3. Ahli Jawatankuasa Masjid
4. Asnaf
5. Agihan Zakat
6. Fail (header)
7. Pengurusan Dokumen
8. Tetapan Umum
9. Senarai Masjid
10. Senarai Pengguna
11. Senarai Kumpulan
12. Integrasi

## Files Modified

### Controller:
- ✅ `app/Http/Controllers/RoleController.php`
  - Updated `getAvailableModules()` - 17 → 23 modules
  - Updated `getReadOnlyModules()` - 1 → 4 modules
  - Updated `getSettingsOnlyModules()` - 5 → 5 modules (changed items)
  - Updated `getWorkflowModules()` - 5 → 6 modules (reordered)
  - Added `getPartialWorkflowModules()` - NEW method
  - Updated `create()` - added `$partialWorkflowModules` to compact
  - Updated `show()` - added `$partialWorkflowModules` to compact
  - Updated `edit()` - added `$partialWorkflowModules` to compact

### Views:
- ✅ `resources/views/pentadbiran/kumpulan/create.blade.php`
  - Added partial workflow logic for approve/reject only modules
- ✅ `resources/views/pentadbiran/kumpulan/edit.blade.php`
  - Added partial workflow logic for approve/reject only modules
- ✅ `resources/views/pentadbiran/kumpulan/show.blade.php`
  - Added partial workflow logic for approve/reject only modules

## Benefits

### 1. Better Organization
✅ Modules grouped by section (Pemuka, Pengurusan, Kewangan, Fail, Pentadbiran)
✅ Visual separators with icons for easy navigation
✅ Follows navbar menu structure exactly

### 2. Complete Coverage
✅ All implemented modules now have permissions
✅ No missing modules that have controllers/routes
✅ Proper workflow actions based on actual controller methods

### 3. Accurate Workflow Actions
✅ Full workflow (4 actions) for modules with all methods
✅ Partial workflow (2 actions) for modules with approve/reject only
✅ User workflow (2 actions) for verify/unverify
✅ No workflow for modules without workflow methods

### 4. Maintainability
✅ Clear comments in code
✅ Logical grouping
✅ Easy to add new modules in correct section
✅ Consistent with navigation flow

## Testing Checklist

### ✅ Manual Testing Required:
- [ ] Navigate to `/senarai-kumpulan/create`
- [ ] Verify 23 modules displayed in correct order
- [ ] Verify 5 sections with visual separators
- [ ] Check Permohonan Zakat has approve/reject only (no suspend/reactivate)
- [ ] Check Ahli Kariah has all 4 workflow actions
- [ ] Check Laporan modules are read-only (view checkbox only)
- [ ] Check Tetapan modules have read/update only
- [ ] Test "Pilih Semua" button
- [ ] Test "Nyahpilih Semua" button
- [ ] Test "Lihat Sahaja" button
- [ ] Create new role and verify permissions saved correctly
- [ ] Navigate to `/senarai-kumpulan/{id}/edit`
- [ ] Verify edit form shows correct checkboxes
- [ ] Edit existing role and verify permissions update correctly
- [ ] Navigate to `/senarai-kumpulan/{id}`
- [ ] Verify show page displays permissions correctly

## Status
✅ **COMPLETE** - Permission matrix telah disusun mengikut struktur menu navbar dengan 23 modules

## Next Steps
1. ⏳ Test manually di browser
2. ⏳ Verify semua checkbox berfungsi dengan baik
3. ⏳ Test create/edit/view role
4. ⏳ Confirm permissions saved correctly
5. ⏳ Test workflow actions for different module types
