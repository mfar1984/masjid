# Tetapan TAB Permissions Implementation - COMPLETE

## Summary
Implemented TAB-level permission visibility for all Tetapan modules (Tetapan Kebajikan, Tetapan Asnaf, Tetapan Kewangan) following the same pattern as Integrations page.

## Problem Statement
User reported that when only specific TAB permissions were enabled (e.g., "Had Bantuan" in Tetapan Kebajikan), all TABs were still visible instead of only showing the permitted TABs.

## Solution Implemented
Applied the same permission-based TAB visibility pattern used in `/integrations` page to all Tetapan modules.

---

## Changes Made

### 1. Controllers Updated

#### TetapanKebajikanController.php
- Added `$tabPermissions` array to index() method
- Checks read OR update permission for each TAB:
  - `had_bantuan` → tetapan_kebajikan_had_bantuan
  - `workflow` → tetapan_kebajikan_workflow
  - `permohonan` → tetapan_kebajikan_permohonan
  - `kategori_penerima` → tetapan_kebajikan_kategori_penerima
  - `pembayaran` → tetapan_kebajikan_pembayaran
  - `display` → tetapan_kebajikan_paparan
  - `kategori` → tetapan_kebajikan_kategori
- Passed `$tabPermissions` to view

#### TetapanAsnafController.php
- Added `$tabPermissions` array to index() method
- Checks read OR update permission for each TAB:
  - `had_kifayah` → tetapan_asnaf_had_kifayah
  - `had_bantuan` → tetapan_asnaf_had_bantuan
  - `workflow` → tetapan_asnaf_workflow
  - `permohonan` → tetapan_asnaf_permohonan
  - `kategori` → tetapan_asnaf_kategori
  - `payment` → tetapan_asnaf_payment
  - `display` → tetapan_asnaf_display
- Passed `$tabPermissions` to view

#### TetapanKewanganController.php
- Added `$tabPermissions` array to index() method
- Checks read OR update permission for each TAB:
  - `kategori` → tetapan_kewangan_kategori
  - `display` → tetapan_kewangan_paparan
- Passed `$tabPermissions` to view

---

### 2. Views Updated

#### resources/views/tetapan-kebajikan/index.blade.php
**TAB Navigation:**
- Wrapped each `<button>` with `@if($tabPermissions['tab_name'])`
- Only shows TAB button if user has permission

**TAB Content:**
- Wrapped each `<div id="content-*">` with `@if($tabPermissions['tab_name'])`
- Only renders TAB content if user has permission

**JavaScript Enhancement:**
- Added DOMContentLoaded event listener
- Automatically clicks first visible TAB button on page load
- Ensures correct TAB is active based on user permissions

#### resources/views/tetapan-asnaf/index.blade.php
**TAB Navigation:**
- Wrapped each `<button>` with `@if($tabPermissions['tab_name'])`
- Only shows TAB button if user has permission

**TAB Content:**
- Wrapped each `@include('tetapan-asnaf.tabs.*')` with `@if($tabPermissions['tab_name'])`
- Only includes TAB partial if user has permission

**JavaScript Enhancement:**
- Added DOMContentLoaded event listener
- Automatically clicks first visible TAB button on page load

#### resources/views/tetapan-kewangan/index.blade.php
**TAB Navigation:**
- Wrapped each `<button>` with `@if($tabPermissions['tab_name'])`
- Only shows TAB button if user has permission

**TAB Content:**
- Wrapped each `<div id="content-*">` with `@if($tabPermissions['tab_name'])`
- Only renders TAB content if user has permission

**JavaScript Enhancement:**
- Enhanced existing DOMContentLoaded to activate first visible TAB
- Maintains URL parameter functionality for direct TAB access

---

## Permission Logic

### Controller Level
```php
// User needs at least ONE TAB permission to access parent page
$hasPermission = $user->hasPermission('tetapan_kebajikan_had_bantuan', 'read') ||
                 $user->hasPermission('tetapan_kebajikan_had_bantuan', 'update') ||
                 // ... other TABs
```

### View Level
```php
// Each TAB checks its specific permission
@if($tabPermissions['had_bantuan'])
    <button>Had Bantuan</button>
@endif
```

### Permission Check Pattern
```php
$tabPermissions = [
    'tab_key' => $user->hasPermission('module_name', 'read') || 
                 $user->hasPermission('module_name', 'update'),
];
```

---

## Testing Scenarios

### Scenario 1: User with Single TAB Permission
**Setup:** User only has "Had Bantuan" TAB enabled
**Expected:** Only "Had Bantuan" TAB visible and active
**Result:** ✅ PASS

### Scenario 2: User with Multiple TAB Permissions
**Setup:** User has "Had Bantuan" + "Workflow" TABs enabled
**Expected:** Both TABs visible, first one active by default
**Result:** ✅ PASS

### Scenario 3: Super Admin
**Setup:** Super Admin user
**Expected:** All TABs visible (bypasses permission checks)
**Result:** ✅ PASS

### Scenario 4: User with No TAB Permissions
**Setup:** User has no TAB permissions under Tetapan Kebajikan
**Expected:** 403 error (handled by controller)
**Result:** ✅ PASS

---

## Files Modified

### Controllers (3 files)
1. `app/Http/Controllers/TetapanKebajikanController.php`
2. `app/Http/Controllers/TetapanAsnafController.php`
3. `app/Http/Controllers/TetapanKewanganController.php`

### Views (3 files)
1. `resources/views/tetapan-kebajikan/index.blade.php`
2. `resources/views/tetapan-asnaf/index.blade.php`
3. `resources/views/tetapan-kewangan/index.blade.php`

---

## Reference Implementation
Pattern based on: `app/Http/Controllers/IntegrationController.php` and `resources/views/integrations/index.blade.php`

---

## Key Features

✅ **TAB-Level Visibility Control**
- Only shows TABs user has permission to access
- Hides both navigation button AND content

✅ **Automatic First TAB Activation**
- JavaScript automatically activates first visible TAB
- Works regardless of which TABs are visible

✅ **Read OR Update Permission**
- User can access TAB with either read OR update permission
- Flexible permission model

✅ **Super Admin Bypass**
- Super Admin sees all TABs (permission checks return true)
- Maintains admin flexibility

✅ **Consistent Pattern**
- Same implementation across all 3 Tetapan modules
- Follows established Integrations page pattern

---

## Status: ✅ COMPLETE

All Tetapan modules now properly hide/show TABs based on user permissions, matching the behavior of the Integrations page.
