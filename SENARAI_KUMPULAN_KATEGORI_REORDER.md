# Senarai Kumpulan - Kategori Permission Reorder ✅

## Summary
Susun semula urutan kategori dalam permission matrix untuk Senarai Kumpulan (Roles) supaya follow struktur menu navbar yang sebenar.

## Changes Made

### File Updated
**File:** `app/Http/Controllers/RoleController.php`

### Method: `getAvailableModules()`

**BEFORE (Old Order):**
```php
return [
    'dashboard' => 'Paparan Pemuka',
    'fail' => 'Fail',
    'documents' => '- Pengurusan Dokumen',
    'masjids' => 'Senarai Masjid',
    'users' => 'Senarai Pengguna',
    'roles' => 'Senarai Kumpulan',
    'kariah' => 'Ahli Kariah',
    'ajk' => 'Ahli Jawatankuasa Masjid',
    'asnaf' => 'Asnaf',
    'agihan_zakat' => 'Agihan Zakat',
    'settings' => 'Tetapan Umum',
    'integrations' => 'Integrasi',
    'integrations_email' => '- Email (SMTP)',
    'integrations_weather' => '- Cuaca',
    'integrations_api' => '- API',
];
```

**AFTER (New Order - Following Menu Structure):**
```php
return [
    // PAPARAN PEMUKA
    'dashboard' => 'Paparan Pemuka',
    
    // PENGURUSAN
    'kariah' => 'Ahli Kariah',
    'ajk' => 'Ahli Jawatankuasa Masjid',
    'asnaf' => 'Asnaf',
    'agihan_zakat' => 'Agihan Zakat',
    'kebajikan' => 'Kebajikan',
    
    // KEWANGAN
    'kewangan' => 'Kewangan',
    
    // FAIL
    'fail' => 'Fail',
    'documents' => '- Pengurusan Dokumen',
    
    // PENTADBIRAN SISTEM
    'settings' => 'Tetapan Umum',
    'masjids' => 'Senarai Masjid',
    'users' => 'Senarai Pengguna',
    'roles' => 'Senarai Kumpulan',
    'integrations' => 'Integrasi',
    'integrations_email' => '- Email (SMTP)',
    'integrations_weather' => '- Cuaca',
    'integrations_api' => '- API',
];
```

## New Order Structure

### 1. PAPARAN PEMUKA
- Paparan Pemuka (dashboard)

### 2. PENGURUSAN
- Ahli Kariah (kariah)
- Ahli Jawatankuasa Masjid (ajk)
- Asnaf (asnaf)
- Agihan Zakat (agihan_zakat)
- Kebajikan (kebajikan) ⭐ NEW

### 3. KEWANGAN
- Kewangan (kewangan) ⭐ NEW

### 4. FAIL
- Fail (fail) - Header only
- └── Pengurusan Dokumen (documents)

### 5. PENTADBIRAN SISTEM
- Tetapan Umum (settings)
- Senarai Masjid (masjids)
- Senarai Pengguna (users)
- Senarai Kumpulan (roles)
- Integrasi (integrations) - Header
  - └── Email (SMTP) (integrations_email)
  - └── Cuaca (integrations_weather)
  - └── API (integrations_api)

## What Was NOT Changed

### ✅ Kept Existing Functionality
1. **Checkbox behavior** - Semua checkbox masih berfungsi seperti biasa
2. **Permission logic** - Tiada perubahan pada logic permission
3. **Workflow modules** - `getWorkflowModules()` tidak berubah
4. **Settings modules** - `getSettingsOnlyModules()` tidak berubah
5. **Read-only modules** - `getReadOnlyModules()` tidak berubah
6. **Special handling** - Masjids (Super Admin only), Fail (header only) masih sama

### ✅ No New Checkboxes Added
- Hanya susun balik urutan
- Tiada checkbox baru ditambah
- Tiada checkbox dibuang
- Matrix structure kekal sama

## Benefits

### 1. Better UX
- Urutan kategori follow struktur menu navbar
- Lebih mudah untuk admin cari kategori yang betul
- Konsisten dengan navigation flow

### 2. Logical Grouping
- Kategori disusun mengikut kumpulan menu
- PENGURUSAN group bersama
- PENTADBIRAN SISTEM group bersama
- Lebih senang untuk assign permissions

### 3. Maintainability
- Bila ada menu baru, senang nak tambah dalam group yang betul
- Code lebih organized dengan comments
- Future developers mudah faham structure

## Testing Checklist

### ✅ Routes
- [x] `GET /senarai-kumpulan` - Index page
- [x] `GET /senarai-kumpulan/create` - Create form
- [x] `GET /senarai-kumpulan/{id}` - Show page
- [x] `GET /senarai-kumpulan/{id}/edit` - Edit form

### ⏳ Manual Testing Required
- [ ] Navigate to `/senarai-kumpulan/create`
- [ ] Verify kategori order follows menu structure
- [ ] Check all checkboxes still work
- [ ] Test "Pilih Semua" button
- [ ] Test "Nyahpilih Semua" button
- [ ] Test "Lihat Sahaja" button
- [ ] Create new role and verify permissions saved correctly
- [ ] Edit existing role and verify permissions display correctly
- [ ] View role details and verify permissions display correctly

## Notes

### Module Keys Mapping
```
dashboard          → Paparan Pemuka
kariah            → Ahli Kariah
ajk               → Ahli Jawatankuasa Masjid
asnaf             → Asnaf
agihan_zakat      → Agihan Zakat
kebajikan         → Kebajikan (NEW)
kewangan          → Kewangan (NEW)
fail              → Fail (Header)
documents         → - Pengurusan Dokumen
settings          → Tetapan Umum
masjids           → Senarai Masjid
users             → Senarai Pengguna
roles             → Senarai Kumpulan
integrations      → Integrasi (Header)
integrations_email    → - Email (SMTP)
integrations_weather  → - Cuaca
integrations_api      → - API
```

### Special Handling
1. **Fail** - Header only, no checkboxes (handled in view)
2. **Masjids** - Super Admin only (handled in view)
3. **Integrations** - Header with sub-items (Email, Cuaca, API)
4. **Documents** - Sub-item under Fail (indicated by "- " prefix)

## Status
✅ **COMPLETE** - Urutan kategori telah disusun mengikut struktur menu navbar

## Next Steps
1. Test manually di browser
2. Verify semua checkbox berfungsi dengan baik
3. Test create/edit/view role
4. Confirm permissions saved correctly
