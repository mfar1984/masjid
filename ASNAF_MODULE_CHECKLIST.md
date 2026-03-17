# MODUL ASNAF - IMPLEMENTATION CHECKLIST

## Phase 1 (MVP) - Senarai Asnaf

### ✅ DATABASE
- [x] Migration: create_asnaf_table.php (60+ fields)
- [x] Run migration
- [x] Create sample data (2-3 asnaf)

### 📋 MODEL
- [x] Create Asnaf Model
- [x] Add HasMasjidScope trait
- [x] Add fillable fields
- [x] Add casts (dates, decimals, booleans)
- [x] Add relationships: masjid, createdBy, updatedBy, diluluskanOleh
- [x] Add scopes: active, pending, approved, rejected
- [x] Add accessors: umur, total_pendapatan, pendapatan_per_kapita
- [x] Add mutators: format IC, format phone

### 🎮 CONTROLLER
- [x] Create AsnafController
- [x] Method: index() - List with filters, stats, pagination
- [x] Method: create() - Show form
- [x] Method: store() - Save with validation
- [x] Method: show() - View details
- [x] Method: edit() - Edit form
- [x] Method: update() - Update with validation
- [x] Method: destroy() - Delete with confirmation
- [x] Method: export() - Export to CSV
- [x] Method: approve() - Workflow approve
- [x] Method: reject() - Workflow reject
- [x] Method: suspend() - Workflow suspend
- [x] Method: reactivate() - Workflow reactivate
- [x] Data isolation: Super Admin vs Admin Masjid

### 🛣️ ROUTES
- [x] GET /asnaf - index
- [x] GET /asnaf/create - create
- [x] POST /asnaf - store
- [x] GET /asnaf/{id} - show
- [x] GET /asnaf/{id}/edit - edit
- [x] PUT /asnaf/{id} - update
- [x] DELETE /asnaf/{id} - destroy
- [x] GET /asnaf-export - export
- [x] POST /asnaf/{id}/approve - approve
- [x] POST /asnaf/{id}/reject - reject
- [x] POST /asnaf/{id}/suspend - suspend
- [x] POST /asnaf/{id}/reactivate - reactivate
- [x] Middleware: auth, verified, permission:asnaf,action

### 🎨 VIEWS (Follow Kariah Pattern)
- [x] index.blade.php
  - [x] Header with title & buttons (Tambah, Eksport)
  - [x] 6 Statistics cards (Total, Diluluskan, Menunggu, Ditolak, Digantung, Dalam Semakan)
  - [x] Filters: Search, Kategori Asnaf, Status (flexbox layout)
  - [x] Desktop table view
  - [x] Mobile card view
  - [x] Pagination
  - [x] Action icons: View, Edit, Approve, Reject, Suspend, Delete

- [x] create.blade.php (Multi-section form)
  - [x] Section 1: Maklumat Peribadi (9 fields)
  - [x] Section 2: Maklumat Alamat IC (4 fields)
  - [x] Section 3: Alamat Surat Menyurat (4 fields + checkbox)
  - [x] Section 4: Alamat Kediaman (5 fields + checkbox)
  - [x] Section 5: Maklumat Waris (5 fields)
  - [x] Section 6: Kategori Asnaf (2 fields)
  - [x] Section 7: Pekerjaan & Pendapatan (7 fields)
  - [x] Section 8: Tanggungan (2 fields)
  - [x] Section 9: Hutang (4 fields, conditional)
  - [x] Section 10: Kesihatan (3 fields)
  - [x] Section 11: Aset (3 fields)
  - [x] Section 12: Dokumen (7 file uploads)
  - [x] JavaScript: formatIC(), checkbox auto-fill alamat, toggleHutangFields()
  - [x] Validation errors display

- [x] edit.blade.php (Same as create, pre-filled with $asnaf data)

- [x] show.blade.php
  - [x] Header with back button
  - [x] Status badge
  - [x] All sections display (read-only)
  - [x] Document previews/downloads
  - [x] Workflow buttons (Approve, Reject, Suspend)
  - [x] Audit trail (Created by, Updated by, Approved by)

### 🔐 PERMISSIONS
- [x] Add 'asnaf' module to RoleController
- [x] Update Super Admin permissions (all actions)
- [x] Permission matrix: create, read, update, delete, approve, reject, suspend, reactivate

### 🧩 COMPONENTS (Reuse existing)
- [ ] Use <x-statistics-grid>
- [ ] Use <x-search-input>
- [ ] Use <x-filter-dropdown>
- [ ] Use <x-action-button>
- [ ] Use <x-action-icons>
- [ ] Use <x-approve-modal>
- [ ] Use <x-reject-modal>
- [ ] Use <x-suspend-modal>
- [ ] Use <x-unsuspend-modal>
- [ ] Use <x-delete-modal>

### 🔗 NAVIGATION
- [x] Update double-navbar.blade.php
- [x] Add "Senarai Asnaf" link in Pengurusan > Asnaf submenu

### 🧪 TESTING
- [x] Test create asnaf (sample data created via tinker)
- [ ] Test edit asnaf (manual testing required)
- [ ] Test delete asnaf (manual testing required)
- [ ] Test approve workflow (manual testing required)
- [ ] Test reject workflow (manual testing required)
- [ ] Test suspend workflow (manual testing required)
- [ ] Test filters (kategori, status, search) (manual testing required)
- [ ] Test export CSV (manual testing required)
- [ ] Test data isolation (Super Admin vs Admin Masjid) (manual testing required)
- [ ] Test file uploads (manual testing required)
- [ ] Test validation errors (manual testing required)
- [ ] Test mobile responsive (manual testing required)

### 📊 STATISTICS CARDS
1. Jumlah Asnaf (total count)
2. Aktif (status = Diluluskan)
3. Menunggu (status = Menunggu)
4. Ditolak (status = Ditolak)
5. Digantung (status = Digantung)
6. Fakir (kategori = Fakir) OR by most common kategori

### 🎯 VALIDATION RULES
- nama: required, string, max:255
- no_ic: required, string, size:14, unique
- telefon: required, string, max:15
- alamat_ic: required, string
- alamat_kediaman: required, string
- kategori_asnaf: required, in:Fakir,Miskin,Amil,Muallaf,Riqab,Gharimin,Fisabilillah,Ibnu Sabil
- status_pekerjaan: required
- pendapatan_bulanan: required, numeric, min:0
- nama_waris: required, string
- no_ic_waris: required, string, size:14
- telefon_waris: required, string
- Files: nullable, file, mimes:jpg,jpeg,png,pdf, max:2048

### 🔄 WORKFLOW STATES
1. Menunggu (default) - Orange badge
2. Dalam Semakan - Blue badge
3. Diluluskan - Green badge
4. Ditolak - Red badge
5. Digantung - Purple badge

### 📁 FILE STRUCTURE
```
app/
  Models/
    Asnaf.php
  Http/
    Controllers/
      AsnafController.php
database/
  migrations/
    2025_12_12_115105_create_asnaf_table.php
resources/
  views/
    asnaf/
      index.blade.php
      create.blade.php
      edit.blade.php
      show.blade.php
routes/
  web.php (add asnaf routes)
```

### 🎨 DESIGN STANDARDS (FOLLOW EXACTLY)
- Font: Poppins, 10-14px
- Border radius: 4-8px max
- Colors: Same as Kariah (blue, green, orange, red, purple)
- Layout: Same grid system as Kariah
- Buttons: h-[32px], text-xs
- Icons: Material Icons, text-[8px] for desktop
- Table: bg-blue-100 header, hover:bg-white rows
- Mobile cards: rounded-lg, p-4, shadow-sm

### ⚠️ CRITICAL RULES
- WAJIB: Data isolation (masjid_id filtering)
- WAJIB: Permission checks on every action
- WAJIB: Validation on store/update
- WAJIB: Audit trail (created_by, updated_by)
- WAJIB: Soft deletes
- WAJIB: Follow exact Kariah design pattern
- JANGAN: Tukar design sendiri
- JANGAN: Skip validation
- JANGAN: Lupa data isolation

---

## IMPLEMENTATION ORDER
1. ✅ Migration created
2. Run migration
3. Create Model
4. Create Controller (all methods)
5. Add Routes
6. Create Views (index → create → edit → show)
7. Update Permissions
8. Update Navigation
9. Create sample data
10. Test all features

---

**STATUS: READY TO IMPLEMENT**
**ESTIMATED TIME: 2-3 hours for complete Phase 1**
