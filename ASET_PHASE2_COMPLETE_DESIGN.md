# ASET MODULE PHASE 2 - COMPLETE DESIGN

## OVERVIEW
Modul Aset Phase 2 melibatkan 3 menu utama yang belum dibuat:
1. **Penyelenggaraan** - Pengurusan penyelenggaraan aset
2. **Penyusutan & Nilai** - Pengiraan susut nilai dan nilai semasa aset
3. **Pelupusan Aset** - Proses pelupusan aset yang rosak/tidak diperlukan

## STRUKTUR MENU & SUBMENU

### 1. PENYELENGGARAAN (3 submenu - page berasingan)
```
├─ Jadual Penyelenggaraan (jadual-penyelenggaraan)
│   - Senarai jadual penyelenggaraan berkala
│   - CRUD: create, show, edit, delete
│   - Fields: aset_id, jenis_penyelenggaraan, kekerapan, tarikh_mula, tarikh_akhir, status
│
├─ Kerja Penyelenggaraan (kerja-penyelenggaraan)
│   - Rekod kerja penyelenggaraan yang dilakukan
│   - CRUD: create, show, edit, delete
│   - Fields: jadual_id, aset_id, tarikh_kerja, vendor, kos, catatan, status
│   - Link ke Kewangan: Perbelanjaan > Penyelenggaraan
│
└─ Laporan Penyelenggaraan (laporan-penyelenggaraan)
    - Laporan statistik penyelenggaraan
    - Read only (view)
```

### 2. PENYUSUTAN & NILAI (3 submenu - page berasingan)
```
├─ Jadual Penyusutan (jadual-penyusutan)
│   - Tetapan kadar susut nilai mengikut kategori
│   - CRUD: create, show, edit, delete
│   - Fields: kategori_aset_id, kadar_susut, kaedah_susut, tempoh_guna
│
├─ Nilai Semasa (nilai-semasa-aset)
│   - Senarai nilai semasa setiap aset
│   - Read only (auto-calculate)
│   - Fields: aset_id, nilai_asal, susut_nilai_terkumpul, nilai_semasa
│
└─ Trend Penyusutan (trend-penyusutan)
    - Laporan trend penyusutan
    - Read only (view)
```

### 3. PELUPUSAN ASET (3 submenu - page berasingan)
```
├─ Permohonan Pelupusan (permohonan-pelupusan)
│   - Permohonan untuk lupus aset
│   - CRUD: create, show, edit, delete
│   - Workflow: approve, reject
│   - Fields: aset_id, sebab_pelupusan, kaedah_pelupusan, nilai_pelupusan
│
├─ Kelulusan Pelupusan (kelulusan-pelupusan)
│   - Senarai permohonan menunggu kelulusan
│   - Actions: approve, reject
│   - Read only + workflow actions
│
└─ Rekod Pelupusan (rekod-pelupusan)
    - Rekod aset yang telah dilupuskan
    - Read only (history)
```

## DATABASE TABLES REQUIRED

### 1. jadual_penyelenggaraan
```sql
- id
- masjid_id
- senarai_aset_id
- nama_jadual
- jenis_penyelenggaraan (Berkala, Pembaikan, Pemeriksaan)
- kekerapan (Harian, Mingguan, Bulanan, Tahunan)
- tarikh_mula
- tarikh_akhir
- status (Aktif, Tidak Aktif)
- catatan
- created_by
- timestamps
- soft_deletes
```

### 2. kerja_penyelenggaraan
```sql
- id
- masjid_id
- jadual_penyelenggaraan_id (nullable)
- senarai_aset_id
- tarikh_kerja
- jenis_kerja
- vendor_nama
- vendor_telefon
- kos
- transaksi_kewangan_id (link ke perbelanjaan)
- status (Dirancang, Sedang Berjalan, Selesai, Dibatalkan)
- catatan
- created_by
- timestamps
- soft_deletes
```

### 3. jadual_penyusutan
```sql
- id
- masjid_id
- kategori_aset_id
- kadar_susut_tahunan (percentage)
- kaedah_susut (Garis Lurus, Baki Berkurangan)
- tempoh_guna_tahun
- status (Aktif, Tidak Aktif)
- created_by
- timestamps
- soft_deletes
```

### 4. permohonan_pelupusan
```sql
- id
- masjid_id
- senarai_aset_id
- tarikh_permohonan
- sebab_pelupusan
- kaedah_pelupusan (Jualan, Derma, Buang, Tukar Ganti)
- nilai_pelupusan
- status (Menunggu, Diluluskan, Ditolak, Selesai)
- diluluskan_oleh
- tarikh_kelulusan
- catatan_kelulusan
- tarikh_pelupusan
- created_by
- timestamps
- soft_deletes
```

## INTEGRATION POINTS

### 1. Link dengan Kewangan
- Kerja Penyelenggaraan → Perbelanjaan (kategori: Penyelenggaraan)
- Pelupusan (Jualan) → Pendapatan (kategori: Jualan Aset)

### 2. Link dengan Operasi Fasiliti
- Fasiliti juga boleh ada jadual penyelenggaraan
- Tempahan fasiliti boleh trigger penyelenggaraan selepas guna

### 3. Link dengan Senarai Aset
- Semua modul ini bergantung kepada senarai_aset
- Status aset akan dikemaskini (Aktif → Dilupuskan)

## PERMISSIONS STRUCTURE

```
penyelenggaraan_header => 'Penyelenggaraan'
jadual_penyelenggaraan => '├─ Jadual Penyelenggaraan'
kerja_penyelenggaraan => '├─ Kerja Penyelenggaraan'
laporan_penyelenggaraan => '└─ Laporan Penyelenggaraan'

penyusutan_header => 'Penyusutan & Nilai'
jadual_penyusutan => '├─ Jadual Penyusutan'
nilai_semasa_aset => '├─ Nilai Semasa'
trend_penyusutan => '└─ Trend Penyusutan'

pelupusan_header => 'Pelupusan Aset'
permohonan_pelupusan => '├─ Permohonan Pelupusan'
kelulusan_pelupusan => '├─ Kelulusan Pelupusan'
rekod_pelupusan => '└─ Rekod Pelupusan'
```

## IMPLEMENTATION ORDER

### Phase 2A: Penyelenggaraan (Priority: HIGH)
1. Migration: jadual_penyelenggaraan, kerja_penyelenggaraan
2. Models: JadualPenyelenggaraan, KerjaPenyelenggaraan
3. Controllers & Routes
4. Views (index, create, show, edit)
5. Navbar links
6. Permissions

### Phase 2B: Pelupusan Aset (Priority: MEDIUM)
1. Migration: permohonan_pelupusan
2. Model: PermohonanPelupusan
3. Controllers & Routes
4. Views
5. Workflow (approve/reject)
6. Navbar links
7. Permissions

### Phase 2C: Penyusutan & Nilai (Priority: LOW)
1. Migration: jadual_penyusutan
2. Model: JadualPenyusutan
3. Service: PenyusutanService (auto-calculate)
4. Controllers & Routes
5. Views
6. Navbar links
7. Permissions

## ESTIMATED FILES TO CREATE

### Penyelenggaraan (14 files)
- 1 migration
- 2 models
- 3 controllers
- 8 views (index, create, show, edit x2 + laporan)

### Pelupusan (11 files)
- 1 migration
- 1 model
- 3 controllers
- 6 views

### Penyusutan (10 files)
- 1 migration
- 1 model
- 1 service
- 3 controllers
- 4 views

**TOTAL: ~35 files**

## CONFIRMATION NEEDED

Sebelum proceed, sila confirm:
1. Adakah struktur ini OK?
2. Mahu mulakan dengan Phase 2A (Penyelenggaraan) dahulu?
3. Adakah perlu tambah/ubah sebarang field?
