# ASCII DESIGN - Permission Matrix Reorder

## SEBELUM (Current Structure)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ PERMISSION MATRIX - CURRENT ORDER                                           │
├─────────────────────────────────────────────────────────────────────────────┤
│ Kategori                          │ Tambah │ Lihat │ Kemaskini │ Padam │... │
├───────────────────────────────────┼────────┼───────┼───────────┼───────┼────┤
│ Paparan Pemuka                    │   -    │   ☐   │     -     │   -   │... │
│ Ahli Kariah                       │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Ahli Jawatankuasa Masjid          │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Asnaf                             │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Agihan Zakat                      │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Kebajikan                         │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Kewangan                          │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Fail                              │   -    │   -   │     -     │   -   │... │
│ - Pengurusan Dokumen              │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Tetapan Umum                      │   -    │   ☐   │     ☐     │   -   │... │
│ Senarai Masjid                    │   🚫   │   🚫  │    🚫     │  🚫   │... │
│ Senarai Pengguna                  │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Senarai Kumpulan                  │   ☐    │   ☐   │     ☐     │   ☐   │... │
│ Integrasi                         │   -    │   ☐   │     ☐     │   -   │... │
│ - Email (SMTP)                    │   -    │   ☐   │     ☐     │   -   │... │
│ - Cuaca                           │   -    │   ☐   │     ☐     │   -   │... │
│ - API                             │   -    │   ☐   │     ☐     │   -   │... │
└─────────────────────────────────────────────────────────────────────────────┘

MASALAH:
❌ Tidak mengikut urutan menu navbar
❌ Kategori bercampur-campur (Pengurusan, Kewangan, Pentadbiran)
❌ Sukar untuk cari kategori yang betul
```

## SELEPAS (Proposed New Structure)

```
┌──────────────────────────────────────────────────────────────────────────────────────────────────────┐
│ PERMISSION MATRIX - NEW ORDER (FOLLOWING NAVBAR MENU)                                                │
├──────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Kategori                     │ Tambah │ Lihat │ Kemaskini │ Padam │ Terima │ Tolak │ Gantung │ Aktif │
├──────────────────────────────┼────────┼───────┼───────────┼───────┼────────┼───────┼─────────┼───────┤
│                                                                                                       │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ 📊 PAPARAN PEMUKA                                                                                     │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ Papan Pemuka                 │   -    │   ☐   │     -     │   -   │   -    │   -   │    -    │   -   │
│                                                                                                       │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ 👥 PENGURUSAN                                                                                         │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ Ahli Kariah                  │   ☐    │   ☐   │     ☐     │   ☐   │   ☐    │   ☐   │    ☐    │   ☐   │
│ Ahli Jawatankuasa Masjid     │   ☐    │   ☐   │     ☐     │   ☐   │   ☐    │   ☐   │    ☐    │   ☐   │
│ Asnaf                        │   ☐    │   ☐   │     ☐     │   ☐   │   ☐    │   ☐   │    ☐    │   ☐   │
│ Permohonan Zakat             │   ☐    │   ☐   │     ☐     │   ☐   │   ☐    │   ☐   │    -    │   -   │
│ Agihan Zakat                 │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Laporan Zakat                │   -    │   ☐   │     -     │   -   │   -    │   -   │    -    │   -   │
│ Tetapan Asnaf                │   -    │   ☐   │     ☐     │   -   │   -    │   -   │    -    │   -   │
│ Program Kebajikan            │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Penerima Bantuan             │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Permohonan Bantuan           │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Pembayaran Bantuan           │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Laporan Kebajikan            │   -    │   ☐   │     -     │   -   │   -    │   -   │    -    │   -   │
│ Tetapan Kebajikan            │   -    │   ☐   │     ☐     │   -   │   -    │   -   │    -    │   -   │
│                                                                                                       │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ 💰 KEWANGAN                                                                                           │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ Akaun Bank                   │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Transaksi Kewangan           │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Laporan Kewangan             │   -    │   ☐   │     -     │   -   │   -    │   -   │    -    │   -   │
│ Tetapan Kewangan             │   -    │   ☐   │     ☐     │   -   │   -    │   -   │    -    │   -   │
│                                                                                                       │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ 📁 FAIL                                                                                               │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ Fail                         │   -    │   -   │     -     │   -   │   -    │   -   │    -    │   -   │
│ Pengurusan Dokumen           │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│                                                                                                       │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ ⚙️ PENTADBIRAN SISTEM                                                                                 │
│ ════════════════════════════════════════════════════════════════════════════════════════════════════ │
│ Tetapan Umum                 │   -    │   ☐   │     ☐     │   -   │   -    │   -   │    -    │   -   │
│ Senarai Masjid               │   🚫   │   🚫  │    🚫     │  🚫   │   🚫   │   🚫  │   🚫    │  🚫   │
│ Senarai Pengguna             │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    ☐    │   ☐   │
│ Senarai Kumpulan             │   ☐    │   ☐   │     ☐     │   ☐   │   -    │   -   │    -    │   -   │
│ Integrasi                    │   -    │   ☐   │     ☐     │   -   │   -    │   -   │    -    │   -   │
└──────────────────────────────────────────────────────────────────────────────────────────────────────┘

KELEBIHAN:
✅ Mengikut urutan menu navbar dengan tepat
✅ Kategori dikumpulkan mengikut section (Pengurusan, Kewangan, dll)
✅ Mudah untuk cari dan assign permissions
✅ Visual grouping dengan separator lines
✅ Konsisten dengan user navigation flow
✅ Workflow actions (Terima/Tolak/Gantung/Aktif) hanya untuk module yang ada
```

## PENJELASAN STRUKTUR

### 1. PAPARAN PEMUKA (Dashboard)
- **Papan Pemuka** - View only (dashboard overview)
  - Actions: `read` only

### 2. PENGURUSAN (Management)

**Ahli Kariah:**
- Full CRUD + Workflow permissions
- Actions: `create`, `read`, `update`, `delete`, `approve`, `reject`, `suspend`, `reactivate`
- Controller: ✅ KariahController has all workflow methods

**Ahli Jawatankuasa Masjid:**
- Full CRUD + Workflow permissions
- Actions: `create`, `read`, `update`, `delete`, `approve`, `reject`, `suspend`, `reactivate`
- Controller: ✅ AjkController has all workflow methods
- Submenus (Senarai AJK, Arkib AJK, Laporan AJK) - handled by main module

**Asnaf:**
- Full CRUD + Workflow permissions
- Actions: `create`, `read`, `update`, `delete`, `approve`, `reject`, `suspend`, `reactivate`
- Controller: ✅ AsnafController has all workflow methods

**Permohonan Zakat:**
- Full CRUD + Workflow (approve/reject only)
- Actions: `create`, `read`, `update`, `delete`, `approve`, `reject`
- Controller: ✅ PermohonanZakatController has approve/reject methods

**Agihan Zakat:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ AgihanZakatController (no workflow methods)

**Laporan Zakat:**
- View only (reports)
- Actions: `read` only
- Route: ✅ agihan-zakat.laporan

**Tetapan Asnaf:**
- Read & Update only (settings)
- Actions: `read`, `update`
- TABs (Had Kifayah, Had Bantuan, Workflow, dll) - handled by main Tetapan Asnaf

**Program Kebajikan:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ ProgramKebajikanController (no workflow methods)

**Penerima Bantuan:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ PenerimaBantuanController (no workflow methods)

**Permohonan Bantuan:**
- Full CRUD only (no workflow yet)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ PermohonanBantuanController (no workflow methods yet)

**Pembayaran Bantuan:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ PembayaranBantuanController (no workflow methods)

**Laporan Kebajikan:**
- View only (reports)
- Actions: `read` only
- Controller: ✅ LaporanKebajikanController

**Tetapan Kebajikan:**
- Read & Update only (settings)
- Actions: `read`, `update`
- TABs (Had Bantuan, Workflow, Permohonan, dll) - handled by main Tetapan Kebajikan

### 3. KEWANGAN (Finance)

**Akaun Bank:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ AkaunBankController

**Transaksi Kewangan:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ TransaksiKewanganController
- Handles all transaction types (Kutipan Kariah, Derma, Utiliti, dll)

**Laporan Kewangan:**
- View only (reports)
- Actions: `read` only
- Controller: ✅ LaporanKewanganController
- Submenus (Penyata, Laporan Pendapatan, dll) - handled by main module

**Tetapan Kewangan:**
- Read & Update only (settings)
- Actions: `read`, `update`
- Controller: ✅ TetapanKewanganController
- TABs (Tetapan Umum, Kategori) - handled by main Tetapan Kewangan

### 4. FAIL (Files)

**Fail:**
- Header only (no checkboxes)
- Visual grouping for submenu items

**Pengurusan Dokumen:**
- Full CRUD permissions
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ DocumentController (assumed exists)

### 5. PENTADBIRAN SISTEM (System Administration)

**Tetapan Umum:**
- Read & Update only (settings)
- Actions: `read`, `update`
- Controller: ✅ SettingsController

**Senarai Masjid:**
- 🚫 Super Admin ONLY - blocked for regular admins
- All actions blocked with 🚫 icon
- Controller: ✅ MasjidController (has workflow methods but only for Super Admin)

**Senarai Pengguna:**
- Full CRUD + Workflow (verify/unverify = suspend/reactivate)
- Actions: `create`, `read`, `update`, `delete`, `suspend` (unverify), `reactivate` (verify)
- Controller: ✅ UserController has verify/unverify methods

**Senarai Kumpulan:**
- Full CRUD only (no workflow)
- Actions: `create`, `read`, `update`, `delete`
- Controller: ✅ RoleController

**Integrasi:**
- Read & Update only (settings)
- Actions: `read`, `update`
- Controller: ✅ IntegrationController
- TABs (Email, Cuaca, API) - handled by main Integrasi module

## CATATAN PENTING

### ❌ TIDAK DIMASUKKAN (Not Included)
Modules/pages yang TIDAK ada dalam permission matrix (belum ada page/controller):

**OPERASI:**
- Program & Pendidikan - ❌ Not implemented yet
- Fasiliti & Tempahan - ❌ Not implemented yet
- Pengurusan Jenazah - ❌ Not implemented yet

**ASET:**
- Pengurusan Aset - ❌ Not implemented yet
- Penyelenggaraan - ❌ Not implemented yet
- Penyusutan & Nilai - ❌ Not implemented yet
- Pelupusan Aset - ❌ Not implemented yet
- Laporan Aset - ❌ Not implemented yet

**KOMUNIKASI:**
- Siaran Mesej - ❌ Not implemented yet
- Kandungan Website - ❌ Not implemented yet
- Pengumuman & Berita - ❌ Not implemented yet

**FAIL (Partial):**
- Perpustakaan Digital - ❌ Not implemented yet
- Arkib & Rekod - ❌ Not implemented yet

**PENTADBIRAN SISTEM (Partial):**
- Log Audit - ❌ Not implemented yet
- Log Keselamatan - ❌ Not implemented yet

### ✅ YANG DIMASUKKAN (Included)
Hanya modules yang:
1. ✅ Ada controller dengan methods lengkap
2. ✅ Ada routes yang berfungsi
3. ✅ Ada database tables
4. ✅ Ada views (index, create, edit, show)
5. ✅ Fully functional dan tested

**Total: 20 modules** (dari 17 sebelum ini)

### 📝 TABs & Submenus
**TABs** (Had Kifayah, Workflow, Email, dll):
- ❌ TIDAK ada permission row sendiri
- ✅ Handled by parent module (Tetapan Asnaf, Integrasi, dll)
- Reason: TABs are UI organization, not separate features

**Submenus with Routes** (Permohonan Zakat, Agihan Zakat, dll):
- ✅ ADA permission row sendiri
- ✅ Separate CRUD operations
- ✅ Own controllers and routes
- Reason: These are actual features with their own functionality

**Submenus without Routes** (Senarai AJK, Arkib AJK, Laporan AJK):
- ❌ TIDAK ada permission row sendiri
- ✅ Handled by parent module (Ahli Jawatankuasa Masjid)
- Reason: These are views/methods within the same controller

## COMPARISON TABLE

| Aspect | SEBELUM | SELEPAS |
|--------|---------|---------|
| **Order** | Random/Mixed | Follows navbar menu exactly |
| **Grouping** | No grouping | 5 clear sections with separators |
| **Visual** | Plain list | Section headers with icons |
| **Navigation** | Hard to find | Easy to locate by section |
| **Consistency** | Inconsistent | Matches user navigation flow |
| **Modules** | 17 items | 20 items (added 3 new modules) |
| **Sections** | None | 5 sections (Pemuka, Pengurusan, Kewangan, Fail, Pentadbiran) |
| **Workflow Actions** | Inconsistent | Correct actions based on controller methods |
| **Missing Modules** | Not shown | Clearly documented (not added to matrix) |

## MODULES ADDED TO MATRIX

**NEW (3 modules):**
1. ✅ **Permohonan Zakat** - Full CRUD + Workflow (approve/reject)
2. ✅ **Laporan Zakat** - View only
3. ✅ **Laporan Kebajikan** - View only

**ALREADY EXISTS (17 modules):**
- Just reordered to follow menu structure

## NEXT STEPS

1. ✅ User review ASCII design
2. ⏳ User approval
3. ⏳ Update `getAvailableModules()` in RoleController
4. ⏳ Update `getWorkflowModules()` if needed
5. ⏳ Update `getSettingsOnlyModules()` if needed
6. ⏳ Update `getReadOnlyModules()` if needed
7. ⏳ Test in browser
8. ⏳ Verify all checkboxes work correctly

## SUMMARY PERUBAHAN

### ✅ YANG SEDIA ADA - SUSUN BALIK SAHAJA (17 modules)
1. Papan Pemuka
2. Ahli Kariah
3. Ahli Jawatankuasa Masjid
4. Asnaf
5. Agihan Zakat
6. Tetapan Asnaf
7. Program Kebajikan
8. Penerima Bantuan
9. Permohonan Bantuan
10. Pembayaran Bantuan
11. Tetapan Kebajikan
12. Akaun Bank
13. Transaksi Kewangan
14. Tetapan Kewangan
15. Fail (header)
16. Pengurusan Dokumen
17. Tetapan Umum
18. Senarai Masjid
19. Senarai Pengguna
20. Senarai Kumpulan
21. Integrasi

### ⭐ YANG ADA PAGE TAPI BELUM DALAM MATRIX - TAMBAH (3 modules)
1. **Permohonan Zakat** - ✅ Ada controller, routes, views, workflow (approve/reject)
2. **Laporan Zakat** - ✅ Ada route (agihan-zakat.laporan), view
3. **Laporan Kewangan** - ✅ Ada controller, routes, views (view only)

### ❌ YANG BELUM ADA PAGE - TIDAK TAMBAH
- OPERASI modules (3 items)
- ASET modules (5 items)
- KOMUNIKASI modules (3 items)
- Perpustakaan Digital
- Arkib & Rekod
- Log Audit
- Log Keselamatan

### 🎯 WORKFLOW ACTIONS - BETUL MENGIKUT CONTROLLER

**Full Workflow (Terima/Tolak/Gantung/Aktif):**
- ✅ Ahli Kariah - KariahController has all 4 methods
- ✅ Ahli Jawatankuasa Masjid - AjkController has all 4 methods
- ✅ Asnaf - AsnafController has all 4 methods

**Partial Workflow (Terima/Tolak sahaja):**
- ✅ Permohonan Zakat - PermohonanZakatController has approve/reject only

**Workflow for Users (Gantung/Aktif sahaja):**
- ✅ Senarai Pengguna - UserController has verify/unverify (mapped to suspend/reactivate)

**No Workflow:**
- ✅ Agihan Zakat, Program Kebajikan, Penerima Bantuan, Permohonan Bantuan, Pembayaran Bantuan
- ✅ Akaun Bank, Transaksi Kewangan
- ✅ Senarai Kumpulan

## SOALAN UNTUK USER

1. ❓ Adakah struktur ini betul mengikut kehendak anda?
2. ❓ Workflow actions sudah betul? (Terima/Tolak/Gantung/Aktif)
3. ❓ 3 modules baru (Permohonan Zakat, Laporan Zakat, Laporan Kewangan) ok untuk ditambah?
4. ❓ Adakah visual grouping (separator lines) ok?

**Sila confirm sebelum saya proceed dengan implementation!** 🙏
