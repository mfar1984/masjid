# HANDOVER: Inventory Integration Feature

## Tarikh: 15 Disember 2025

---

## TASK UNTUK NEW SESSION

Implement integrasi antara Tempahan Fasiliti, Pergerakan Aset, dan Transaksi Kewangan.

---

## DOKUMEN RUJUKAN

Baca dokumen ini sebelum mula:
- `INVENTORY_INTEGRATION_DESIGN.md` - Design lengkap dengan flow, schema, dan code snippets

---

## RINGKASAN FEATURE

### Objektif
1. Auto-create pergerakan aset bila tempahan fasiliti diluluskan
2. Sync status pulangan antara tempahan dan pergerakan
3. Support partial return (pulangan sebahagian)
4. Auto-create transaksi kewangan untuk aset hilang/rosak

### Flow Utama
```
Tempahan Fasiliti (Lulus) 
    → Auto-create Pergerakan Aset (setiap item)
    → Pulangan (bulk atau partial)
    → Jika ada hilang → Auto-create Transaksi Kewangan (Ganti Rugi)
```

---

## DATABASE CHANGES REQUIRED

### 1. Table `pergerakan_aset` - Add columns:
```sql
kuantiti_dipulangkan INT DEFAULT 0
kuantiti_hilang INT DEFAULT 0
kuantiti_rosak INT DEFAULT 0
nilai_ganti_rugi DECIMAL(12,2) DEFAULT 0
transaksi_kewangan_id BIGINT UNSIGNED NULL
tarikh_selesai_pulangan DATETIME NULL
diselesaikan_oleh BIGINT UNSIGNED NULL
```

### 2. Table `tempahan_fasiliti_items` - Add columns:
```sql
kuantiti_dipulangkan INT DEFAULT 0
kuantiti_hilang INT DEFAULT 0
status_pulangan VARCHAR(50) DEFAULT 'Belum Pulang'
```

### 3. Table `kategori_kewangan` - Add new categories:
- "Ganti Rugi Aset Hilang"
- "Ganti Rugi Aset Rosak"

---

## FILES TO MODIFY

### Controllers
- `app/Http/Controllers/TempahanFasilitiController.php` - Add approve(), pulangSemua()
- `app/Http/Controllers/PergerakanAsetController.php` - Add pulang(), selesaikan()

### Models
- `app/Models/PergerakanAset.php` - Add new fillable, relationships
- `app/Models/TempahanFasilitiItem.php` - Add new fillable

### Views
- `resources/views/pergerakan-aset/index.blade.php` - Add pulangan icon, kuantiti column
- `resources/views/pergerakan-aset/create.blade.php` - Add kuantiti field
- `resources/views/tempahan-fasiliti/index.blade.php` - Update pulangan modal

### New Files
- `resources/views/components/pulangan-modal.blade.php`
- Migration for new columns

---

## IMPLEMENTATION PHASES

### Phase 1: Database & Model ✅ DONE
- [x] Create migration for pergerakan_aset columns
- [x] Create migration for tempahan_fasiliti_items columns
- [x] Update Model fillable arrays
- [x] Add kategori kewangan for ganti rugi

### Phase 2: Auto-Create Pergerakan ✅ DONE
- [x] Add approve() method in TempahanFasilitiController (lulus method)
- [x] Auto-create pergerakan for each item when approved
- [x] Link pergerakan to tempahan items

### Phase 3: Pulangan dari Pergerakan Aset ✅ DONE
- [ ] Add kuantiti field to create form (PENDING - for manual pergerakan)
- [x] Add pulangan icon to index
- [x] Create pulangan modal
- [x] Implement partial return logic

### Phase 4: Pulangan dari Tempahan Fasiliti ✅ DONE
- [x] Update pulangan modal for bulk return
- [x] Sync status between modules

### Phase 5: Ganti Rugi Integration ✅ DONE
- [x] Auto-create transaksi kewangan when ada hilang
- [x] Link transaksi to pergerakan

### Phase 6: Testing
- [ ] Test all scenarios
- [ ] Update sample data

---

## KEY LOGIC

### Partial Return
```
Pinjam: 400 unit
Pulang kali 1: 200 → status: Sebahagian
Pulang kali 2: 198 → status: Sebahagian
Selesaikan: kuantiti_hilang = 2 → status: Hilang → create transaksi ganti rugi
```

### Status Values
- `Belum Pulang` - Aset masih di luar
- `Sebahagian` - Ada pulangan partial
- `Sudah Pulang` - Semua dipulangkan
- `Lewat` - Tarikh jangka pulangan sudah lepas
- `Hilang` - Rekod ditutup dengan baki tidak dipulangkan

---

## EXISTING STRUCTURE

### Tables Already Exist
- `pergerakan_aset` - has tempahan_fasiliti_id, tempahan_fasiliti_item_id, kuantiti
- `tempahan_fasiliti_items` - has quantity, senarai_fasiliti_id
- `senarai_fasiliti` - fasiliti yang boleh disewa
- `senarai_aset` - inventory aset

### Current Data
- Pergerakan Aset: 1 rekod dengan status "Lewat" (manual, bukan dari tempahan)
- Tempahan Fasiliti: Ada data dengan status "Lulus" tapi tiada pergerakan linked

---

## NOTES

1. Fasiliti dan Aset adalah entiti berbeza - fasiliti boleh link ke aset untuk inventory tracking
2. Ganti rugi guna harga_perolehan dari senarai_aset
3. Pulangan dari tempahan = bulk (semua items), dari pergerakan = individual (partial OK)
4. Status di tempahan, items, dan pergerakan mesti sentiasa sync

---

## ARAHAN UNTUK NEW SESSION

1. Baca `INVENTORY_INTEGRATION_DESIGN.md` untuk design lengkap
2. Mula dengan Phase 1 (Database & Model)
3. Test setiap phase sebelum proceed
4. Follow existing code patterns dalam project
5. Pastikan UI consistent dengan design sedia ada (Poppins font, 10-14px, etc.)

---

*Handover Document v1.0*
