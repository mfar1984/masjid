# Inventory Integration Design Document

## Tarikh: 15 Disember 2025

---

## 1. Overview

Dokumen ini menerangkan design untuk integrasi antara modul-modul berikut:
- **Tempahan Fasiliti** (`/tempahan-fasiliti`)
- **Pergerakan Aset** (`/pergerakan-aset`)
- **Senarai Aset** (`/senarai-aset`)
- **Transaksi Kewangan** (`/transaksi-kewangan`)

### 1.1 Objektif
1. Sync data antara Tempahan Fasiliti dan Pergerakan Aset
2. Track kuantiti aset yang dipinjam dan dipulangkan
3. Handle partial return (pulangan sebahagian)
4. Auto-create transaksi kewangan untuk aset hilang/rosak

---

## 2. Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           TEMPAHAN FASILITI                                  │
│                                                                              │
│  [Create Tempahan] ──► [Tambah Fasiliti Items] ──► [Submit]                 │
│         │                      │                        │                    │
│         │                      │                        ▼                    │
│         │                      │              [Status: Baharu]               │
│         │                      │                        │                    │
│         │                      │                        ▼                    │
│         │                      │              [Approval Process]             │
│         │                      │                        │                    │
│         │                      │                        ▼                    │
│         │                      │              [Status: Lulus]                │
│         │                      │                        │                    │
│         │                      ▼                        ▼                    │
│         │              ┌──────────────────────────────────────┐              │
│         │              │   AUTO-CREATE PERGERAKAN ASET        │              │
│         │              │   (untuk setiap item dengan kuantiti)│              │
│         │              └──────────────────────────────────────┘              │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           PERGERAKAN ASET                                    │
│                                                                              │
│  Setiap item tempahan = 1 rekod pergerakan aset                             │
│                                                                              │
│  Fields:                                                                     │
│  - tempahan_fasiliti_id (link ke tempahan)                                  │
│  - tempahan_fasiliti_item_id (link ke item)                                 │
│  - senarai_aset_id (link ke aset - via fasiliti)                            │
│  - kuantiti (jumlah dipinjam)                                               │
│  - kuantiti_dipulangkan (default: 0)                                        │
│  - kuantiti_hilang (auto-calculate)                                         │
│  - status_pulangan (Belum Pulang/Sebahagian/Sudah Pulang/Hilang)            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           PULANGAN ASET                                      │
│                                                                              │
│  2 CARA PULANGAN:                                                           │
│                                                                              │
│  ┌─────────────────────────────┐    ┌─────────────────────────────┐         │
│  │ DARI TEMPAHAN FASILITI      │    │ DARI PERGERAKAN ASET        │         │
│  │ (Bulk Return)               │    │ (Individual Return)         │         │
│  │                             │    │                             │         │
│  │ - Pulang SEMUA items        │    │ - Pulang satu-satu          │         │
│  │ - Satu klik untuk semua     │    │ - Partial return OK         │         │
│  │ - Update semua pergerakan   │    │ - Masukkan kuantiti pulang  │         │
│  └─────────────────────────────┘    └─────────────────────────────┘         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           PARTIAL RETURN LOGIC                               │
│                                                                              │
│  Contoh:                                                                     │
│  - Pinjam: 400 unit                                                         │
│  - Pulang kali 1: 200 unit → Status: Sebahagian                             │
│  - Pulang kali 2: 198 unit → Status: Sebahagian                             │
│  - Balance: 2 unit                                                          │
│                                                                              │
│  Bila user "Selesaikan Pulangan":                                           │
│  - kuantiti_dipulangkan = 398                                               │
│  - kuantiti_hilang = 2 (auto-calculate: 400 - 398)                          │
│  - Status: Hilang (jika ada balance)                                        │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────┐        │
│  │ JIKA ADA KUANTITI HILANG:                                       │        │
│  │                                                                  │        │
│  │ 1. Update status_aset dalam senarai_aset                        │        │
│  │ 2. Auto-create Transaksi Kewangan (Ganti Rugi)                  │        │
│  │    - Kategori: Kutipan Lain                                     │        │
│  │    - Jenis: "Ganti Rugi Aset Hilang"                            │        │
│  │    - Jumlah: Harga aset × kuantiti hilang                       │        │
│  │    - Link ke: pergerakan_aset_id, senarai_aset_id               │        │
│  └─────────────────────────────────────────────────────────────────┘        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Database Schema Changes

### 3.1 Table: `pergerakan_aset` (Existing - Add columns)

```sql
-- Columns to ADD
ALTER TABLE pergerakan_aset ADD COLUMN kuantiti_dipulangkan INT DEFAULT 0;
ALTER TABLE pergerakan_aset ADD COLUMN kuantiti_hilang INT DEFAULT 0;
ALTER TABLE pergerakan_aset ADD COLUMN kuantiti_rosak INT DEFAULT 0;
ALTER TABLE pergerakan_aset ADD COLUMN nilai_ganti_rugi DECIMAL(12,2) DEFAULT 0;
ALTER TABLE pergerakan_aset ADD COLUMN transaksi_kewangan_id BIGINT UNSIGNED NULL;
ALTER TABLE pergerakan_aset ADD COLUMN tarikh_selesai_pulangan DATETIME NULL;
ALTER TABLE pergerakan_aset ADD COLUMN diselesaikan_oleh BIGINT UNSIGNED NULL;
```

### 3.2 Table: `tempahan_fasiliti_items` (Existing - Add columns)

```sql
-- Columns to ADD
ALTER TABLE tempahan_fasiliti_items ADD COLUMN kuantiti_dipulangkan INT DEFAULT 0;
ALTER TABLE tempahan_fasiliti_items ADD COLUMN kuantiti_hilang INT DEFAULT 0;
ALTER TABLE tempahan_fasiliti_items ADD COLUMN status_pulangan VARCHAR(50) DEFAULT 'Belum Pulang';
```

### 3.3 Table: `senarai_fasiliti` (Check existing)

```sql
-- Ensure link to senarai_aset exists
-- senarai_aset_id should link fasiliti to aset for inventory tracking
```

### 3.4 Table: `kategori_kewangan` (Add new categories)

```sql
-- Add new jenis for Kutipan Lain
INSERT INTO kategori_kewangan (masjid_id, jenis_transaksi, nama_kategori, kod_kategori, is_active)
VALUES 
(1, 'Pendapatan', 'Ganti Rugi Aset Hilang', 'KL-ASET-HILANG', 1),
(1, 'Pendapatan', 'Ganti Rugi Aset Rosak', 'KL-ASET-ROSAK', 1);
```

---

## 4. UI Changes

### 4.1 Tempahan Fasiliti Index (`/tempahan-fasiliti`)

**Existing Icon:** `assignment_return` (Pulangkan)

**Behavior:**
- Click icon → Modal "Rekod Pemulangan Bulk"
- Show list of all items dalam tempahan
- User boleh:
  - "Pulang Semua" - set semua items sebagai dipulangkan penuh
  - "Pulang Sebahagian" - redirect ke pergerakan-aset untuk partial return

### 4.2 Pergerakan Aset Index (`/pergerakan-aset`)

**Add New Icon:** `assignment_return` (Pulangkan) - untuk pergerakan yang belum pulang

**New Column:** "Kuantiti" showing `dipulangkan/total`

**Behavior:**
- Click icon → Modal "Rekod Pemulangan"
- Fields:
  - Kuantiti Asal: [readonly] 400
  - Kuantiti Dipulangkan Sebelum: [readonly] 200
  - Kuantiti Pulang Kali Ini: [input] ___
  - Kondisi: [dropdown] Baik/Rosak Ringan/Rosak Teruk
  - Catatan: [textarea]
- Auto-calculate balance
- Button: "Rekod Pulangan" / "Selesaikan & Tutup"

### 4.3 Pergerakan Aset Create (`/pergerakan-aset/create`)

**Add Field:** "Kuantiti" untuk manual pergerakan

```html
<div>
    <label>Kuantiti *</label>
    <input type="number" name="kuantiti" min="1" required>
</div>
```

### 4.4 Modal Rekod Pemulangan (New Component)

```
┌─────────────────────────────────────────────────────────────────┐
│  Rekod Pemulangan                                         [X]   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  No. Pergerakan: PG-2025-0001                                   │
│  Aset: Kerusi Lipat (AST-2025-0001)                             │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ Kuantiti Asal          : 400                            │    │
│  │ Sudah Dipulangkan      : 200                            │    │
│  │ Baki Belum Pulang      : 200                            │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
│  Kuantiti Pulang Kali Ini *                                     │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ [___________] unit                                      │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
│  Kondisi Selepas Pulang *                                       │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │ [Baik                                              ▼]   │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
│  Catatan                                                        │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                                                         │    │
│  │                                                         │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
│  [ ] Selesaikan pulangan (tutup rekod ini)                      │
│      ⚠️ Baki yang tidak dipulangkan akan dikira sebagai HILANG  │
│                                                                  │
│  ┌──────────────┐  ┌──────────────────────────────────────┐     │
│  │    Batal     │  │         Rekod Pulangan               │     │
│  └──────────────┘  └──────────────────────────────────────┘     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Business Logic

### 5.1 Auto-Create Pergerakan Aset (When Tempahan Approved)

```php
// In TempahanFasilitiController@approve or similar
public function approve(TempahanFasiliti $tempahan)
{
    DB::transaction(function () use ($tempahan) {
        // Update tempahan status
        $tempahan->update(['status_tempahan' => 'Lulus']);
        
        // Create pergerakan for each item
        foreach ($tempahan->items as $item) {
            PergerakanAset::create([
                'masjid_id' => $tempahan->masjid_id,
                'no_pergerakan' => PergerakanAset::generateNoPergerakan($tempahan->masjid_id),
                'senarai_aset_id' => $item->senariFasiliti->senarai_aset_id,
                'tempahan_fasiliti_id' => $tempahan->id,
                'tempahan_fasiliti_item_id' => $item->id,
                'kuantiti' => $item->quantity,
                'kuantiti_dipulangkan' => 0,
                'tarikh_pergerakan' => $tempahan->tarikh_mula,
                'jenis_pergerakan' => 'Sewa', // or 'Pinjaman'
                'lokasi_destinasi' => $tempahan->alamat_penyewa ?? 'Luaran',
                'nama_peminjam' => $tempahan->nama_penyewa,
                'no_telefon_peminjam' => $tempahan->no_telefon_penyewa,
                'tarikh_jangka_pulangan' => $tempahan->tarikh_tamat,
                'status_pulangan' => 'Belum Pulang',
                'kondisi_sebelum' => 'Baik',
                'created_by' => auth()->id(),
            ]);
        }
    });
}
```

### 5.2 Partial Return Logic

```php
// In PergerakanAsetController@pulang
public function pulang(Request $request, PergerakanAset $pergerakan)
{
    $validated = $request->validate([
        'kuantiti_pulang' => 'required|integer|min:1|max:' . ($pergerakan->kuantiti - $pergerakan->kuantiti_dipulangkan),
        'kondisi_selepas' => 'required|in:Baik,Rosak Ringan,Rosak Teruk',
        'catatan' => 'nullable|string',
        'selesaikan' => 'boolean',
    ]);
    
    DB::transaction(function () use ($pergerakan, $validated) {
        $newTotal = $pergerakan->kuantiti_dipulangkan + $validated['kuantiti_pulang'];
        $baki = $pergerakan->kuantiti - $newTotal;
        
        $updateData = [
            'kuantiti_dipulangkan' => $newTotal,
            'kondisi_selepas' => $validated['kondisi_selepas'],
            'catatan' => $validated['catatan'],
        ];
        
        // Determine status
        if ($validated['selesaikan'] ?? false) {
            // User wants to close this record
            $updateData['kuantiti_hilang'] = $baki;
            $updateData['status_pulangan'] = $baki > 0 ? 'Hilang' : 'Sudah Pulang';
            $updateData['tarikh_sebenar_pulangan'] = now();
            $updateData['tarikh_selesai_pulangan'] = now();
            $updateData['diselesaikan_oleh'] = auth()->id();
            
            // Create ganti rugi transaction if ada hilang
            if ($baki > 0) {
                $this->createGantiRugiTransaction($pergerakan, $baki);
            }
        } else {
            // Partial return, keep open
            $updateData['status_pulangan'] = $newTotal >= $pergerakan->kuantiti ? 'Sudah Pulang' : 'Sebahagian';
            if ($newTotal >= $pergerakan->kuantiti) {
                $updateData['tarikh_sebenar_pulangan'] = now();
            }
        }
        
        $pergerakan->update($updateData);
        
        // Sync with tempahan_fasiliti_items
        if ($pergerakan->tempahanFasilitiItem) {
            $pergerakan->tempahanFasilitiItem->update([
                'kuantiti_dipulangkan' => $newTotal,
                'kuantiti_hilang' => $updateData['kuantiti_hilang'] ?? 0,
                'status_pulangan' => $updateData['status_pulangan'],
            ]);
        }
        
        // Update parent tempahan status
        $this->updateTempahanStatus($pergerakan->tempahanFasiliti);
    });
}

private function createGantiRugiTransaction($pergerakan, $kuantitiHilang)
{
    $aset = $pergerakan->senariAset;
    $nilaiGantiRugi = $aset->harga_perolehan * $kuantitiHilang;
    
    // Find or create kategori
    $kategori = KategoriKewangan::firstOrCreate([
        'masjid_id' => $pergerakan->masjid_id,
        'nama_kategori' => 'Ganti Rugi Aset Hilang',
    ], [
        'jenis_transaksi' => 'Pendapatan',
        'kod_kategori' => 'KL-ASET-HILANG',
        'is_active' => true,
    ]);
    
    $transaksi = TransaksiKewangan::create([
        'masjid_id' => $pergerakan->masjid_id,
        'no_transaksi' => TransaksiKewangan::generateNoTransaksi($pergerakan->masjid_id),
        'tarikh_transaksi' => now(),
        'jenis_transaksi' => 'Pendapatan',
        'kategori_kewangan_id' => $kategori->id,
        'jumlah' => $nilaiGantiRugi,
        'keterangan' => "Ganti rugi {$kuantitiHilang} unit {$aset->nama_aset} (No. Pergerakan: {$pergerakan->no_pergerakan})",
        'pergerakan_aset_id' => $pergerakan->id,
        'status' => 'Belum Bayar', // Peminjam perlu bayar
        'created_by' => auth()->id(),
    ]);
    
    // Update pergerakan with transaksi link
    $pergerakan->update([
        'nilai_ganti_rugi' => $nilaiGantiRugi,
        'transaksi_kewangan_id' => $transaksi->id,
    ]);
}

private function updateTempahanStatus($tempahan)
{
    if (!$tempahan) return;
    
    $allItems = $tempahan->items;
    $allReturned = $allItems->every(fn($item) => $item->status_pulangan === 'Sudah Pulang');
    $anyPartial = $allItems->contains(fn($item) => $item->status_pulangan === 'Sebahagian');
    $anyLost = $allItems->contains(fn($item) => $item->status_pulangan === 'Hilang');
    
    if ($allReturned) {
        $tempahan->update(['status_pemulangan' => 'Sudah Pulang']);
    } elseif ($anyLost) {
        $tempahan->update(['status_pemulangan' => 'Hilang']);
    } elseif ($anyPartial) {
        $tempahan->update(['status_pemulangan' => 'Sebahagian']);
    }
}
```

### 5.3 Bulk Return from Tempahan Fasiliti

```php
// In TempahanFasilitiController@pulangSemua
public function pulangSemua(Request $request, TempahanFasiliti $tempahan)
{
    $validated = $request->validate([
        'kondisi_selepas' => 'required|in:Baik,Rosak Ringan,Rosak Teruk,Hilang',
        'catatan' => 'nullable|string',
    ]);
    
    DB::transaction(function () use ($tempahan, $validated) {
        // Update all pergerakan records
        foreach ($tempahan->pergerakanAset as $pergerakan) {
            $pergerakan->update([
                'kuantiti_dipulangkan' => $pergerakan->kuantiti,
                'kuantiti_hilang' => 0,
                'status_pulangan' => 'Sudah Pulang',
                'kondisi_selepas' => $validated['kondisi_selepas'],
                'tarikh_sebenar_pulangan' => now(),
                'catatan' => $validated['catatan'],
            ]);
        }
        
        // Update all items
        foreach ($tempahan->items as $item) {
            $item->update([
                'kuantiti_dipulangkan' => $item->quantity,
                'kuantiti_hilang' => 0,
                'status_pulangan' => 'Sudah Pulang',
            ]);
        }
        
        // Update tempahan
        $tempahan->update([
            'status_pemulangan' => 'Sudah Pulang',
        ]);
    });
}
```

---

## 6. Status Flow

### 6.1 Status Pulangan Values

| Status | Description |
|--------|-------------|
| `Belum Pulang` | Aset masih di luar, belum ada pulangan |
| `Sebahagian` | Ada pulangan partial, masih ada baki |
| `Sudah Pulang` | Semua kuantiti sudah dipulangkan |
| `Lewat` | Tarikh jangka pulangan sudah lepas, belum pulang |
| `Hilang` | Rekod ditutup dengan baki yang tidak dipulangkan |

### 6.2 Auto Status Update (Cron Job)

```php
// Check for late returns daily
PergerakanAset::where('status_pulangan', 'Belum Pulang')
    ->where('tarikh_jangka_pulangan', '<', now())
    ->update(['status_pulangan' => 'Lewat']);
```

---

## 7. Implementation Phases

### Phase 1: Database & Model Updates ✅ DONE
- [x] Add new columns to `pergerakan_aset` (kuantiti_dipulangkan, kuantiti_hilang, kuantiti_rosak, nilai_ganti_rugi, transaksi_kewangan_id, tarikh_selesai_pulangan, diselesaikan_oleh)
- [x] Add new columns to `tempahan_fasiliti_items` (kuantiti_dipulangkan, kuantiti_hilang, status_pulangan)
- [x] Update Model relationships (PergerakanAset, TempahanFasilitiItem)
- [x] Add kategori kewangan for ganti rugi (KL-ASET-HILANG, KL-ASET-ROSAK)

### Phase 2: Auto-Create Pergerakan ✅ DONE (Already existed)
- [x] Implement auto-create when tempahan approved (TempahanFasilitiController@lulus)
- [x] Link pergerakan to tempahan items (via tempahan_fasiliti_item_id)
- [ ] Update existing data (if any) - Manual if needed

### Phase 3: Pulangan dari Pergerakan Aset ✅ DONE
- [ ] Add kuantiti field to create form - PENDING (manual pergerakan)
- [x] Add pulangan icon to index (assignment_return icon)
- [x] Create pulangan modal component (inline in index.blade.php)
- [x] Implement partial return logic (InventoryService@processPartialReturn)

### Phase 4: Pulangan dari Tempahan Fasiliti ✅ DONE
- [x] Update pulangan modal for bulk return (TempahanFasilitiController@pulang)
- [x] Sync status between modules (InventoryService@processBulkReturn)
- [x] Update statistics cards (PergerakanAsetController - added Sebahagian)

### Phase 5: Ganti Rugi Integration ✅ DONE
- [x] Auto-create transaksi kewangan (InventoryService@createGantiRugiTransaction)
- [x] Link to pergerakan record (transaksi_kewangan_id, nilai_ganti_rugi)
- [x] Show in transaksi kewangan list (auto via existing views)

### Phase 6: Testing & Refinement
- [ ] Test all scenarios
- [ ] Fix edge cases
- [ ] Update sample data

---

## 8. Files to Modify

### Controllers
- `app/Http/Controllers/TempahanFasilitiController.php`
- `app/Http/Controllers/PergerakanAsetController.php`
- `app/Http/Controllers/TransaksiKewanganController.php`

### Models
- `app/Models/PergerakanAset.php`
- `app/Models/TempahanFasiliti.php`
- `app/Models/TempahanFasilitiItem.php`
- `app/Models/TransaksiKewangan.php`

### Views
- `resources/views/pergerakan-aset/index.blade.php`
- `resources/views/pergerakan-aset/create.blade.php`
- `resources/views/tempahan-fasiliti/index.blade.php`
- `resources/views/tempahan-fasiliti/show.blade.php`
- `resources/views/components/pulangan-modal.blade.php` (new)

### Migrations
- `database/migrations/xxxx_add_pulangan_fields_to_pergerakan_aset.php`
- `database/migrations/xxxx_add_pulangan_fields_to_tempahan_fasiliti_items.php`
- `database/migrations/xxxx_add_ganti_rugi_kategori_kewangan.php`

---

## 9. Notes

1. **Fasiliti vs Aset**: Fasiliti adalah item yang boleh disewa (kerusi, meja, PA system). Setiap fasiliti boleh link ke senarai_aset untuk track inventory.

2. **Kuantiti Tracking**: Pergerakan aset track kuantiti yang keluar. Bila pulang, track berapa yang masuk balik.

3. **Ganti Rugi**: Bila ada aset hilang, auto-create transaksi kewangan sebagai "Belum Bayar". Admin boleh update bila peminjam bayar.

4. **Sync Status**: Status di tempahan_fasiliti, tempahan_fasiliti_items, dan pergerakan_aset mesti sentiasa sync.


---

## 10. Senario Penggunaan

### Senario 1: Tempahan Baru dengan Multiple Items

```
1. User create tempahan fasiliti
2. Tambah items:
   - Kerusi Lipat: 100 unit
   - Meja Bulat: 20 unit
   - PA System: 1 set
3. Submit tempahan → Status: Baharu
4. Admin approve → Status: Lulus
5. System auto-create 3 rekod pergerakan aset:
   - PG-2025-0001: Kerusi Lipat (100 unit)
   - PG-2025-0002: Meja Bulat (20 unit)
   - PG-2025-0003: PA System (1 set)
```

### Senario 2: Pulangan Penuh dari Tempahan Fasiliti

```
1. User click icon "Pulangkan" di tempahan-fasiliti
2. Modal show semua items
3. User pilih "Pulang Semua" dengan kondisi "Baik"
4. System update:
   - Semua pergerakan: status_pulangan = "Sudah Pulang"
   - Semua items: status_pulangan = "Sudah Pulang"
   - Tempahan: status_pemulangan = "Sudah Pulang"
```

### Senario 3: Pulangan Partial dari Pergerakan Aset

```
1. Pinjam: 100 kerusi
2. Hari 1: Pulang 50 kerusi
   - kuantiti_dipulangkan = 50
   - status_pulangan = "Sebahagian"
3. Hari 2: Pulang 48 kerusi
   - kuantiti_dipulangkan = 98
   - status_pulangan = "Sebahagian"
4. Hari 3: User "Selesaikan Pulangan"
   - kuantiti_dipulangkan = 98
   - kuantiti_hilang = 2
   - status_pulangan = "Hilang"
   - Auto-create transaksi ganti rugi: RM X × 2 = RM Y
```

### Senario 4: Aset Rosak

```
1. Pinjam: 20 meja
2. Pulang: 20 meja, kondisi "Rosak Ringan"
3. System update:
   - kuantiti_dipulangkan = 20
   - kuantiti_rosak = 20 (optional tracking)
   - kondisi_selepas = "Rosak Ringan"
   - status_pulangan = "Sudah Pulang"
4. Admin boleh create transaksi ganti rugi manual jika perlu
```

### Senario 5: Lewat Pulang

```
1. Tarikh jangka pulangan: 15/12/2025
2. Hari ini: 16/12/2025
3. Cron job update status_pulangan = "Lewat"
4. Show in dashboard/statistics
5. Bila pulang, status bertukar ke "Sudah Pulang" atau "Hilang"
```

---

## 11. API Endpoints (Routes)

```php
// Pergerakan Aset
Route::post('/pergerakan-aset/{pergerakan}/pulang', [PergerakanAsetController::class, 'pulang'])
    ->name('pergerakan-aset.pulang');

Route::post('/pergerakan-aset/{pergerakan}/selesaikan', [PergerakanAsetController::class, 'selesaikan'])
    ->name('pergerakan-aset.selesaikan');

// Tempahan Fasiliti
Route::post('/tempahan-fasiliti/{tempahan}/pulang-semua', [TempahanFasilitiController::class, 'pulangSemua'])
    ->name('tempahan-fasiliti.pulang-semua');

Route::post('/tempahan-fasiliti/{tempahan}/approve', [TempahanFasilitiController::class, 'approve'])
    ->name('tempahan-fasiliti.approve');
```

---

## 12. Statistics Updates

### Pergerakan Aset Statistics

```php
$stats = [
    ['label' => 'Jumlah Pergerakan', 'value' => $total, 'icon' => 'swap_horiz', 'color' => 'blue'],
    ['label' => 'Belum Pulang', 'value' => $belumPulang, 'icon' => 'pending', 'color' => 'orange'],
    ['label' => 'Sebahagian', 'value' => $sebahagian, 'icon' => 'hourglass_empty', 'color' => 'yellow'],
    ['label' => 'Lewat', 'value' => $lewat, 'icon' => 'warning', 'color' => 'red'],
    ['label' => 'Hilang', 'value' => $hilang, 'icon' => 'error', 'color' => 'red'],
];
```

### Tempahan Fasiliti Statistics

```php
$stats = [
    ['label' => 'Jumlah Tempahan', 'value' => $total, 'icon' => 'event', 'color' => 'blue'],
    ['label' => 'Baharu', 'value' => $baharu, 'icon' => 'fiber_new', 'color' => 'blue'],
    ['label' => 'Lulus', 'value' => $lulus, 'icon' => 'check_circle', 'color' => 'green'],
    ['label' => 'Belum Pulang', 'value' => $belumPulang, 'icon' => 'pending', 'color' => 'orange'],
    ['label' => 'Lewat', 'value' => $lewat, 'icon' => 'warning', 'color' => 'red'],
];
```

---

## 13. Checklist Sebelum Implementation

- [ ] Confirm: Setiap fasiliti ada link ke senarai_aset?
- [ ] Confirm: Harga aset untuk calculate ganti rugi dari mana?
- [ ] Confirm: Siapa yang approve tempahan? (workflow)
- [ ] Confirm: Perlu notification bila lewat?
- [ ] Confirm: Perlu report untuk aset hilang?

---

## 14. Questions for User

1. **Link Fasiliti-Aset**: Adakah setiap fasiliti dalam `senarai_fasiliti` sudah ada link ke `senarai_aset`? Atau fasiliti dan aset adalah entiti berbeza?

2. **Harga Ganti Rugi**: Guna harga perolehan aset atau ada harga khas untuk ganti rugi?

3. **Approval Workflow**: Siapa yang approve tempahan? Ada multi-level approval?

4. **Notification**: Perlu email/notification bila:
   - Tempahan diluluskan
   - Aset lewat pulang
   - Ada aset hilang

5. **Report**: Perlu laporan khas untuk:
   - Aset yang kerap hilang
   - Peminjam yang kerap lewat
   - Nilai ganti rugi belum dibayar

---

*Document Version: 1.0*
*Last Updated: 15 Disember 2025*
