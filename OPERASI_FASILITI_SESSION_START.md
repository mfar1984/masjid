# OPERASI FASILITI & TEMPAHAN - SESSION START

**Date**: 15 December 2025
**Status**: Starting Implementation
**Based On**: OPERASI_FASILITI_TEMPAHAN_COMPLETE_DESIGN.md

---

## SESSION CONTEXT

### Previous Session Summary
✅ **Aset Module - Bulk Add**: Implemented kuantiti field (1-1000 units)
✅ **Aset Module - File Uploads**: Section 7 with optional file uploads
✅ **Aset Module - Placeholders**: Added examples for Kod Aset, Nama Aset, Jenis Aset
✅ **Design Document**: Complete design for Operasi Fasiliti & Tempahan (1346 lines)

### Current Session Goal
Implement Modul Operasi > Fasiliti & Tempahan dengan 4 sub-modules:
1. Senarai Fasiliti (Master data)
2. Tempahan Fasiliti (Booking management)
3. Pembayaran Sewa (Payment tracking)
4. Laporan Tempahan (Reports)

---

## IMPLEMENTATION PLAN

### Phase 1: Database & Backend (Day 1 - 8 hours)
**Morning (4 hours):**
- [ ] Create migration: senarai_fasiliti
- [ ] Create migration: tempahan_fasiliti
- [ ] Create migration: pembayaran_sewa
- [ ] Create Model: SenariFasiliti with relationships
- [ ] Create Model: TempahanFasiliti with relationships
- [ ] Create Model: PembayaranSewa with relationships
- [ ] Create Controller: SenariFasilitiController (basic CRUD)
- [ ] Create Controller: TempahanFasilitiController (basic CRUD + workflow)
- [ ] Create Controller: PembayaranSewaController (basic CRUD)
- [ ] Create Controller: LaporanTempahanController
- [ ] Setup routes with permissions

**Afternoon (4 hours):**
- [ ] Test migrations
- [ ] Test models & relationships
- [ ] Test multi-masjid isolation
- [ ] Create seeders (if needed)

### Phase 2: Views & UI (Day 2 - 8 hours)
**Morning (4 hours):**
- [ ] Create Senarai Fasiliti views (index, create, edit, show)
- [ ] Create Tempahan Fasiliti views (index, create, edit, show)

**Afternoon (4 hours):**
- [ ] Create Pembayaran Sewa views (index, create, edit, show)
- [ ] Create Laporan Tempahan view
- [ ] Update navbar menu (add Operasi > Fasiliti & Tempahan)

### Phase 3: Integration & Testing (Day 3 - 8 hours)
**Morning (4 hours):**
- [ ] Integration 1: Tempahan Lulus → Auto-create Pergerakan Aset (if jenis=Aset)
- [ ] Integration 2: Tempahan Lulus → Auto-create Pembayaran Sewa
- [ ] Integration 3: Pembayaran Sewa (Sudah Bayar) → Auto-create Kutipan Dana
- [ ] Integration 4: Tempahan Selesai → Update Pergerakan Aset status

**Afternoon (4 hours):**
- [ ] Test all CRUD operations
- [ ] Test workflow actions
- [ ] Test all integrations
- [ ] Test multi-masjid isolation
- [ ] Fix bugs & polish UI

---

## MODULE OVERVIEW

### 4 Sub-Modules
1. **Senarai Fasiliti** - Master data for facilities & assets
2. **Tempahan Fasiliti** - Booking management with workflow
3. **Pembayaran Sewa** - Payment tracking
4. **Laporan Tempahan** - Reports with charts

### 3 Database Tables
- `senarai_fasiliti` - Facilities master data
- `tempahan_fasiliti` - Booking records
- `pembayaran_sewa` - Payment records

### 4 Controllers
- SenariFasilitiController
- TempahanFasilitiController
- PembayaranSewaController
- LaporanTempahanController

### 3 Models
- SenariFasiliti
- TempahanFasiliti
- PembayaranSewa

### 4 Integration Points
1. Tempahan Lulus → Auto-create Pergerakan Aset (if jenis=Aset)
2. Tempahan Lulus → Auto-create Pembayaran Sewa
3. Pembayaran Sewa (Sudah Bayar) → Auto-create Kutipan Dana
4. Tempahan Selesai → Update Pergerakan Aset status

---

## KEY FEATURES

### Auto-Generate Numbers
- Kod Fasiliti: FS-YYYY-0001
- No. Tempahan: TP-YYYY-0001
- No. Pembayaran: PS-YYYY-0001

### Workflow System
Baharu → Dalam Semakan → Lulus/Ditolak → Selesai

### Multi-Masjid Isolation
- Super Admin: View all data
- Admin Masjid: Only their masjid data
- Auto-assign masjid_id

### File Uploads (Optional)
- Gambar Fasiliti (max 5 images)
- Dokumen Peraturan (PDF)
- Surat Permohonan (PDF)
- Salinan IC (PDF/JPG)
- Resit Pembayaran (PDF/JPG)
- Bukti Transfer (PDF/JPG)

---

## UI/UX STANDARDS

### Font
- Family: Poppins
- Size: 10px - 14px only
- Headings: 14px bold
- Body: 12px regular
- Small: 10px regular

### Border Radius
- Cards: 8px
- Buttons: 6px
- Inputs: 4px
- Badges: 4px

### Colors
- Primary: Blue (#3B82F6)
- Success: Green (#10B981)
- Warning: Orange (#F59E0B)
- Danger: Red (#EF4444)

---

## NEXT STEPS

1. Start with Phase 1: Database & Backend
2. Create migrations for 3 tables
3. Create models with relationships
4. Create controllers with basic CRUD
5. Setup routes with permissions

---

**Session Status**: Ready to Start
**Estimated Time**: 3 days (24 hours)
**Priority**: High
**Complexity**: Medium-High (due to integrations)

