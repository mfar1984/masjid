# OPERASI - FASILITI & TEMPAHAN MODULE - COMPLETE DESIGN SPECIFICATION

## MODULE OVERVIEW
Modul Fasiliti & Tempahan menguruskan tempahan fasiliti masjid dan penyewaan aset dengan sistem pembayaran yang terintegrasi dengan Modul Kewangan. Modul ini akan auto-create Pergerakan Aset dan Kutipan Dana.

## NAVIGATION STRUCTURE
```
Operasi (Main Menu)
├── Fasiliti & Tempahan
│   ├── Senarai Fasiliti (Master data for facilities)
│   ├── Tempahan Fasiliti (Facility bookings)
│   ├── Pembayaran Sewa (Rental payments)
│   └── Laporan Tempahan (Booking reports)
└── (Other Operasi modules - Future)
```

## MULTI-MASJID ISOLATION
- **Super Admin**: Can view all data, filter by masjid
- **Admin Masjid**: Only see their own masjid data, auto-assigned masjid_id
- All models use `HasMasjidScope` trait
- All controllers check user role and filter by masjid_id
- Follow exact pattern from Asnaf/Kebajikan/Kewangan/Aset modules

## PERMISSIONS
```php
'operasi' => [
    'create' => 'Cipta Operasi',
    'read' => 'Lihat Operasi',
    'update' => 'Kemaskini Operasi',
    'delete' => 'Padam Operasi',
    'approve' => 'Lulus Operasi',
]
```

---

## 1. SENARAI FASILITI (MASTER DATA)

### Purpose
Master data untuk fasiliti masjid yang boleh ditempah dan aset yang boleh disewa. Setiap fasiliti/aset ada harga sewa tersendiri.

### Database Schema
**Table**: `senarai_fasiliti`

```sql
CREATE TABLE senarai_fasiliti (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    kod_fasiliti VARCHAR(50) UNIQUE NOT NULL,
    
    -- Maklumat Fasiliti
    nama_fasiliti VARCHAR(255) NOT NULL,
    jenis_fasiliti ENUM('Dewan', 'Bilik', 'Padang', 'Tempat Letak Kereta', 'Aset', 'Lain-lain') NOT NULL,
    kategori_fasiliti VARCHAR(255),
    
    -- Link to Aset (if jenis = Aset)
    senarai_aset_id BIGINT UNSIGNED,
    
    -- Kapasiti & Spesifikasi
    kapasiti_maksimum INT,
    luas_kawasan VARCHAR(100),
    kemudahan TEXT,
    spesifikasi TEXT,
    
    -- Harga Sewa
    harga_sewa_sejam DECIMAL(10,2),
    harga_sewa_sehari DECIMAL(10,2),
    harga_sewa_separuh_hari DECIMAL(10,2),
    deposit_diperlukan DECIMAL(10,2),
    
    -- Syarat & Peraturan
    syarat_tempahan TEXT,
    peraturan_penggunaan TEXT,
    had_minimum_tempahan INT DEFAULT 1,
    had_maksimum_tempahan INT,
    
    -- Gambar & Dokumen
    gambar_fasiliti TEXT,
    dokumen_peraturan TEXT,
    
    -- Status
    status_fasiliti ENUM('Tersedia', 'Tidak Tersedia', 'Dalam Penyelenggaraan') DEFAULT 'Tersedia',
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (senarai_aset_id) REFERENCES senarai_aset(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Fasiliti**
- `kod_fasiliti` - Text (auto-generate: FS-YYYY-0001, readonly on edit)
- `nama_fasiliti` - Text (required, max 255)
- `jenis_fasiliti` - Dropdown (required)
  * Dewan
  * Bilik
  * Padang
  * Tempat Letak Kereta
  * Aset
  * Lain-lain
- `kategori_fasiliti` - Text (optional, e.g., "Dewan Serbaguna", "Bilik Mesyuarat")
- `senarai_aset_id` - Dropdown (show only if jenis=Aset, link to senarai_aset)

**Section 2: Kapasiti & Spesifikasi**
- `kapasiti_maksimum` - Number (optional, jumlah orang)
- `luas_kawasan` - Text (optional, e.g., "100 kaki persegi")
- `kemudahan` - Textarea (optional, list of facilities: AC, WiFi, etc)
- `spesifikasi` - Textarea (optional, technical details)

**Section 3: Harga Sewa**
- `harga_sewa_sejam` - Number (decimal 10,2, optional)
- `harga_sewa_separuh_hari` - Number (decimal 10,2, optional)
- `harga_sewa_sehari` - Number (decimal 10,2, optional)
- `deposit_diperlukan` - Number (decimal 10,2, optional)

**Section 4: Syarat & Peraturan**
- `syarat_tempahan` - Textarea (optional)
- `peraturan_penggunaan` - Textarea (optional)
- `had_minimum_tempahan` - Number (default: 1, minimum hours/days)
- `had_maksimum_tempahan` - Number (optional, maximum hours/days)

**Section 5: Gambar & Dokumen**
All files: Max 5MB, PDF/JPG/PNG

- `gambar_fasiliti` - File (multiple, max 5 images)
- `dokumen_peraturan` - File (single, PDF)

**Section 6: Status & Catatan**
- `status_fasiliti` - Dropdown (required, default: Tersedia)
  * Tersedia
  * Tidak Tersedia
  * Dalam Penyelenggaraan
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Fasiliti
  * Fasiliti Tersedia
  * Fasiliti Tidak Tersedia
  * Total Tempahan Bulan Ini

- **Filter** (1 row):
  * Jenis Fasiliti (dropdown)
  * Status Fasiliti (dropdown)
  * Cari (kod_fasiliti, nama_fasiliti)
  * Reset

- **Table Columns** (Desktop):
  * Kod Fasiliti
  * Nama Fasiliti
  * Jenis
  * Kapasiti
  * Harga Sewa/Hari
  * Status
  * Tindakan

- **Card View** (Mobile):
  * Kod Fasiliti (bold)
  * Nama Fasiliti
  * Jenis | Kapasiti
  * Harga Sewa/Hari
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Fasiliti
2. Kapasiti & Spesifikasi
3. Harga Sewa
4. Syarat & Peraturan
5. Gambar & Dokumen
6. Sejarah Tempahan (list from tempahan_fasiliti)
7. Status & Catatan
8. Maklumat Audit

---

## 2. TEMPAHAN FASILITI

### Purpose
Rekod tempahan fasiliti/aset dengan maklumat penyewa, tarikh, dan status kelulusan. Bila approved, auto-create Pergerakan Aset (if aset) dan Pembayaran Sewa.

### Database Schema
**Table**: `tempahan_fasiliti`

```sql
CREATE TABLE tempahan_fasiliti (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_tempahan VARCHAR(50) UNIQUE NOT NULL,
    
    -- Relations
    senarai_fasiliti_id BIGINT UNSIGNED NOT NULL,
    
    -- Maklumat Penyewa
    nama_penyewa VARCHAR(255) NOT NULL,
    no_ic_penyewa VARCHAR(12) NOT NULL,
    no_telefon_penyewa VARCHAR(20) NOT NULL,
    emel_penyewa VARCHAR(255),
    alamat_penyewa_1 VARCHAR(255) NOT NULL,
    alamat_penyewa_2 VARCHAR(255),
    poskod_penyewa VARCHAR(10) NOT NULL,
    bandar_penyewa VARCHAR(100) NOT NULL,
    negeri_penyewa VARCHAR(100) NOT NULL,
    organisasi_penyewa VARCHAR(255),
    
    -- Maklumat Tempahan
    tarikh_tempahan DATE NOT NULL,
    tarikh_mula DATETIME NOT NULL,
    tarikh_tamat DATETIME NOT NULL,
    tempoh_sewa INT NOT NULL,
    unit_tempoh ENUM('Jam', 'Separuh Hari', 'Hari') NOT NULL,
    
    -- Tujuan & Acara
    tujuan_tempahan TEXT NOT NULL,
    jenis_acara VARCHAR(255),
    bilangan_jangka_peserta INT,
    
    -- Harga & Bayaran
    harga_sewa DECIMAL(10,2) NOT NULL,
    deposit DECIMAL(10,2),
    jumlah_bayaran DECIMAL(10,2) NOT NULL,
    
    -- Dokumen
    surat_permohonan_path TEXT,
    salinan_ic_path TEXT,
    surat_sokongan_path TEXT,
    dokumen_lain TEXT,
    
    -- Status & Workflow
    status_tempahan ENUM('Baharu', 'Dalam Semakan', 'Lulus', 'Ditolak', 'Dibatalkan', 'Selesai') DEFAULT 'Baharu',
    
    -- Approval
    disemak_oleh BIGINT UNSIGNED,
    tarikh_disemak DATETIME,
    catatan_semakan TEXT,
    
    diluluskan_oleh BIGINT UNSIGNED,
    tarikh_diluluskan DATETIME,
    catatan_kelulusan TEXT,
    
    ditolak_oleh BIGINT UNSIGNED,
    tarikh_ditolak DATETIME,
    sebab_tolak TEXT,
    
    dibatalkan_oleh BIGINT UNSIGNED,
    tarikh_dibatalkan DATETIME,
    sebab_batal TEXT,
    
    -- Catatan
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (senarai_fasiliti_id) REFERENCES senarai_fasiliti(id) ON DELETE CASCADE,
    FOREIGN KEY (disemak_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (diluluskan_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (ditolak_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (dibatalkan_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```


### Form Fields (Create/Edit)

**Section 1: Maklumat Penyewa**
- `nama_penyewa` - Text (required, max 255)
- `no_ic_penyewa` - Text (required, 12 digits, format: 000000-00-0000)
- `no_telefon_penyewa` - Text (required, format: 01X-XXXXXXX)
- `emel_penyewa` - Email (optional)
- `alamat_penyewa_1` - Text (required)
- `alamat_penyewa_2` - Text (optional)
- `poskod_penyewa` - Text (required, 5 digits)
- `bandar_penyewa` - Text (required)
- `negeri_penyewa` - Dropdown (required, 16 states)
- `organisasi_penyewa` - Text (optional)

**Section 2: Maklumat Tempahan**
- `no_tempahan` - Text (auto-generate: TP-YYYY-0001, readonly)
- `tarikh_tempahan` - Date (required, default: today)
- `senarai_fasiliti_id` - Dropdown/Search (required, filter: status=Tersedia)
  * On select, auto-populate: harga_sewa (based on unit_tempoh)
- `tarikh_mula` - DateTime (required)
- `tarikh_tamat` - DateTime (required)
- `tempoh_sewa` - Number (readonly, auto-calculated from tarikh_mula to tarikh_tamat)
- `unit_tempoh` - Dropdown (required)
  * Jam
  * Separuh Hari
  * Hari

**Section 3: Tujuan & Acara**
- `tujuan_tempahan` - Textarea (required)
- `jenis_acara` - Text (optional, e.g., "Majlis Perkahwinan", "Mesyuarat")
- `bilangan_jangka_peserta` - Number (optional)

**Section 4: Harga & Bayaran**
- `harga_sewa` - Number (decimal 10,2, readonly, auto-calculated)
- `deposit` - Number (decimal 10,2, readonly, from fasiliti)
- `jumlah_bayaran` - Number (decimal 10,2, readonly, harga_sewa + deposit)

**Section 5: Dokumen**
All files: Max 5MB, PDF/JPG/PNG

- `surat_permohonan_path` - File (single, PDF)
- `salinan_ic_path` - File (single, PDF/JPG)
- `surat_sokongan_path` - File (single, PDF)
- `dokumen_lain` - File (multiple, max 3 files)

**Section 6: Catatan**
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Tempahan
  * Tempahan Baharu
  * Tempahan Lulus
  * Tempahan Aktif (Lulus, belum Selesai)

- **Filter** (1 row):
  * Fasiliti (dropdown)
  * Status Tempahan (dropdown)
  * Tarikh Dari (date)
  * Tarikh Hingga (date)
  * Cari (no_tempahan, nama_penyewa)
  * Reset

- **Table Columns** (Desktop):
  * No. Tempahan
  * Tarikh
  * Nama Penyewa
  * Fasiliti
  * Tarikh Mula - Tamat
  * Jumlah Bayaran
  * Status
  * Tindakan

- **Card View** (Mobile):
  * No. Tempahan (bold)
  * Tarikh | Status badge
  * Nama Penyewa
  * Fasiliti
  * Tarikh Mula - Tamat
  * Jumlah Bayaran
  * Action icons

### Show Page Sections
1. Maklumat Tempahan
2. Maklumat Penyewa
3. Maklumat Fasiliti (from senarai_fasiliti)
4. Tujuan & Acara
5. Harga & Bayaran
6. Dokumen (with download/view)
7. Workflow Timeline (Baharu → Semakan → Lulus/Ditolak)
8. Catatan
9. Maklumat Audit

### Workflow Actions (Buttons on Show Page)
- **Semak** (if status=Baharu, permission: update)
- **Lulus** (if status=Dalam Semakan, permission: approve)
  * Auto-create Pembayaran Sewa
  * Auto-create Pergerakan Aset (if fasiliti jenis=Aset)
- **Tolak** (if status=Baharu/Dalam Semakan, permission: update)
- **Batal** (if status!=Lulus/Ditolak/Dibatalkan/Selesai, permission: delete)
- **Tandakan Selesai** (if status=Lulus, tarikh_tamat < today, permission: update)

---

## 3. PEMBAYARAN SEWA

### Purpose
Rekod pembayaran sewa fasiliti/aset. Auto-created bila Tempahan diluluskan. Bila status=Sudah Bayar, auto-create Kutipan Dana di Modul Kewangan.

### Database Schema
**Table**: `pembayaran_sewa`

```sql
CREATE TABLE pembayaran_sewa (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_pembayaran VARCHAR(50) UNIQUE NOT NULL,
    
    -- Relations
    tempahan_fasiliti_id BIGINT UNSIGNED NOT NULL,
    senarai_fasiliti_id BIGINT UNSIGNED NOT NULL,
    
    -- Maklumat Pembayaran
    tarikh_pembayaran DATE NOT NULL,
    jumlah_sewa DECIMAL(10,2) NOT NULL,
    jumlah_deposit DECIMAL(10,2),
    jumlah_bayaran DECIMAL(10,2) NOT NULL,
    kaedah_bayaran ENUM('Tunai', 'Cek', 'Bank Transfer', 'Online Banking', 'E-Wallet') NOT NULL,
    
    -- Bank Details (if applicable)
    nama_bank VARCHAR(255),
    no_akaun VARCHAR(50),
    no_rujukan VARCHAR(100),
    
    -- Cek Details (if applicable)
    no_cek VARCHAR(50),
    tarikh_cek DATE,
    
    -- Dokumen Pembayaran
    resit_pembayaran_path TEXT,
    bukti_transfer_path TEXT,
    salinan_cek_path TEXT,
    
    -- Deposit Return
    deposit_dikembalikan DECIMAL(10,2),
    tarikh_kembalikan_deposit DATE,
    sebab_potongan_deposit TEXT,
    
    -- Status
    status_pembayaran ENUM('Belum Bayar', 'Sudah Bayar', 'Deposit Dikembalikan', 'Dibatalkan') DEFAULT 'Belum Bayar',
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (tempahan_fasiliti_id) REFERENCES tempahan_fasiliti(id) ON DELETE CASCADE,
    FOREIGN KEY (senarai_fasiliti_id) REFERENCES senarai_fasiliti(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```


### Form Fields (Create/Edit)

**Section 1: Maklumat Pembayaran**
- `no_pembayaran` - Text (auto-generate: PS-YYYY-0001, readonly)
- `tempahan_fasiliti_id` - Dropdown/Search (readonly if auto-created, show: no_tempahan - nama_penyewa)
  * On select, auto-populate: senarai_fasiliti_id, jumlah_sewa, jumlah_deposit, jumlah_bayaran
- `tarikh_pembayaran` - Date (required, default: today)
- `jumlah_sewa` - Number (decimal 10,2, readonly from tempahan)
- `jumlah_deposit` - Number (decimal 10,2, readonly from tempahan)
- `jumlah_bayaran` - Number (decimal 10,2, readonly, jumlah_sewa + jumlah_deposit)
- `kaedah_bayaran` - Dropdown (required)
  * Tunai
  * Cek
  * Bank Transfer
  * Online Banking
  * E-Wallet

**Section 2: Maklumat Bank** (Show if kaedah=Bank Transfer/Online Banking)
- `nama_bank` - Dropdown (required)
  * Maybank, CIMB Bank, Public Bank, RHB Bank, Hong Leong Bank, AmBank, Bank Islam, Bank Rakyat, BSN, Lain-lain
- `no_akaun` - Text (optional)
- `no_rujukan` - Text (required)

**Section 3: Maklumat Cek** (Show if kaedah=Cek)
- `no_cek` - Text (required)
- `tarikh_cek` - Date (required)
- `nama_bank` - Dropdown (required, same list as above)

**Section 4: Dokumen Pembayaran**
All files: Max 5MB, PDF/JPG/PNG

- `resit_pembayaran_path` - File (single, PDF/JPG)
- `bukti_transfer_path` - File (single, PDF/JPG, show if kaedah=Bank Transfer/Online Banking)
- `salinan_cek_path` - File (single, PDF/JPG, show if kaedah=Cek)

**Section 5: Deposit Return** (Show only on Edit, after event completed)
- `deposit_dikembalikan` - Number (decimal 10,2, optional, max=jumlah_deposit)
- `tarikh_kembalikan_deposit` - Date (optional)
- `sebab_potongan_deposit` - Textarea (optional, if deposit_dikembalikan < jumlah_deposit)

**Section 6: Status & Catatan**
- `status_pembayaran` - Dropdown (required, default: Belum Bayar)
  * Belum Bayar
  * Sudah Bayar
  * Deposit Dikembalikan
  * Dibatalkan
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Pembayaran
  * Sudah Bayar
  * Belum Bayar
  * Jumlah Terkumpul (RM)

- **Filter** (1 row):
  * Fasiliti (dropdown)
  * Kaedah Bayaran (dropdown)
  * Status Pembayaran (dropdown)
  * Tarikh Dari (date)
  * Tarikh Hingga (date)
  * Cari (no_pembayaran, nama penyewa)
  * Reset

- **Table Columns** (Desktop):
  * No. Pembayaran
  * Tarikh
  * No. Tempahan
  * Nama Penyewa
  * Fasiliti
  * Jumlah Bayaran
  * Kaedah Bayaran
  * Status
  * Tindakan

- **Card View** (Mobile):
  * No. Pembayaran (bold)
  * Tarikh | Kaedah
  * No. Tempahan
  * Nama Penyewa
  * Fasiliti
  * Jumlah Bayaran
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Pembayaran
2. Maklumat Tempahan (from tempahan_fasiliti)
3. Maklumat Fasiliti (from senarai_fasiliti)
4. Maklumat Bank/Cek (based on kaedah)
5. Dokumen Pembayaran (with download/view)
6. Deposit Return (if applicable)
7. Status & Catatan
8. Maklumat Audit

---

## 4. LAPORAN TEMPAHAN

### Page Layout
Similar to Laporan Kebajikan, with filters and charts

**Filter Section** (1 row):
- Fasiliti (dropdown)
- Status Tempahan (dropdown)
- Tarikh Dari (date)
- Tarikh Hingga (date)
- Cari (text)
- Reset button
- Cetak PDF button
- Export Excel button

**Stats Cards** (2 rows):
Row 1:
- Total Fasiliti
- Total Tempahan
- Total Pembayaran
- Jumlah Pendapatan (RM)

Row 2:
- Tempahan Lulus
- Tempahan Ditolak
- Tempahan Selesai
- Kadar Kelulusan (%)

**Charts Section**:
1. **Tempahan Mengikut Status** (Pie Chart)
   - Baharu
   - Dalam Semakan
   - Lulus
   - Ditolak
   - Dibatalkan
   - Selesai

2. **Pembayaran Mengikut Kaedah** (Bar Chart)
   - Tunai
   - Cek
   - Bank Transfer
   - Online Banking
   - E-Wallet

3. **Tempahan Mengikut Fasiliti** (Bar Chart)
   - Top 10 facilities by booking count

4. **Trend Tempahan Bulanan** (Line Chart)
   - Last 12 months

5. **Pendapatan Bulanan** (Line Chart)
   - Last 12 months

**Table Section**:
- List of tempahan with filters applied
- Columns: No. Tempahan, Tarikh, Penyewa, Fasiliti, Jumlah, Status
- Pagination

---

## INTEGRATION WITH EXISTING MODULES

### Integration 1: Tempahan Lulus → Pergerakan Aset (Auto-Create)
```
When: Tempahan status changed to 'Lulus' AND fasiliti jenis = 'Aset'
Action: Auto-create pergerakan_aset record

Flow:
TempahanFasiliti::update([
    'status_tempahan' => 'Lulus',
])
    ↓
Event: TempahanFasilitiLulus
    ↓
Listener: CreatePergerakanAset (if fasiliti->jenis_fasiliti == 'Aset')
    ↓
PergerakanAset::create([
    'senarai_aset_id' => $fasiliti->senarai_aset_id,
    'jenis_pergerakan' => 'Sewa',
    'is_lokasi_luaran' => true,
    'nama_peminjam' => $tempahan->nama_penyewa,
    'no_ic_peminjam' => $tempahan->no_ic_penyewa,
    'no_telefon_peminjam' => $tempahan->no_telefon_penyewa,
    'nama_tempat_luaran' => $tempahan->organisasi_penyewa ?? 'Tempat Acara',
    'alamat_luaran_1' => $tempahan->alamat_penyewa_1,
    'alamat_luaran_2' => $tempahan->alamat_penyewa_2,
    'poskod_luaran' => $tempahan->poskod_penyewa,
    'bandar_luaran' => $tempahan->bandar_penyewa,
    'negeri_luaran' => $tempahan->negeri_penyewa,
    'tarikh_pergerakan' => $tempahan->tarikh_mula,
    'tarikh_jangka_pulangan' => $tempahan->tarikh_tamat,
    'sebab_pergerakan' => $tempahan->tujuan_tempahan,
    'status_pulangan' => 'Belum Pulang',
])
    ↓
Update SenariAset:
    - status_aset = 'Disewa'
    - lokasi_semasa = 'Disewa - ' . $tempahan->nama_penyewa
```

### Integration 2: Tempahan Lulus → Pembayaran Sewa (Auto-Create)
```
When: Tempahan status changed to 'Lulus'
Action: Auto-create pembayaran_sewa record

Flow:
TempahanFasiliti::update([
    'status_tempahan' => 'Lulus',
])
    ↓
Event: TempahanFasilitiLulus
    ↓
Listener: CreatePembayaranSewa
    ↓
PembayaranSewa::create([
    'tempahan_fasiliti_id' => $tempahan->id,
    'senarai_fasiliti_id' => $tempahan->senarai_fasiliti_id,
    'tarikh_pembayaran' => now(),
    'jumlah_sewa' => $tempahan->harga_sewa,
    'jumlah_deposit' => $tempahan->deposit,
    'jumlah_bayaran' => $tempahan->jumlah_bayaran,
    'status_pembayaran' => 'Belum Bayar',
])
```


### Integration 3: Pembayaran Sewa (Sudah Bayar) → Kewangan (Auto-Create Kutipan Dana)
```
When: Pembayaran Sewa status changed to 'Sudah Bayar'
Action: Auto-create kutipan_dana record in Kewangan Module

Flow:
PembayaranSewa::update([
    'status_pembayaran' => 'Sudah Bayar',
])
    ↓
Event: PembayaranSewaCompleted
    ↓
Listener: CreateKutipanDana
    ↓
KutipanDana::create([
    'jenis_kutipan' => 'Kutipan Lain-lain',
    'kategori_kewangan_id' => 'Sewa Fasiliti & Aset', // From kategori_kewangan
    'tarikh_kutipan' => $pembayaran->tarikh_pembayaran,
    'jumlah' => $pembayaran->jumlah_bayaran,
    'kaedah_bayaran' => $pembayaran->kaedah_bayaran,
    'nama_bank' => $pembayaran->nama_bank,
    'no_rujukan' => $pembayaran->no_rujukan,
    'no_cek' => $pembayaran->no_cek,
    'tarikh_cek' => $pembayaran->tarikh_cek,
    'penerima' => 'Sewa Fasiliti: ' . $pembayaran->fasiliti->nama_fasiliti,
    'tujuan' => 'Sewa fasiliti untuk ' . $pembayaran->tempahan->tujuan_tempahan,
    'rujukan_id' => $pembayaran->id,
    'rujukan_type' => 'PembayaranSewa',
    'status_kutipan' => 'Selesai',
])
    ↓
TransaksiKewangan::create([
    'jenis_transaksi' => 'Pendapatan',
    'kategori_kewangan_id' => 'Sewa Fasiliti & Aset',
    'tarikh_transaksi' => $pembayaran->tarikh_pembayaran,
    'jumlah' => $pembayaran->jumlah_bayaran,
    'kaedah_bayaran' => $pembayaran->kaedah_bayaran,
    'penerima_pembayar' => $pembayaran->tempahan->nama_penyewa,
    'keterangan' => 'Sewa ' . $pembayaran->fasiliti->nama_fasiliti,
    'rujukan_id' => $pembayaran->id,
    'rujukan_type' => 'PembayaranSewa',
])
```

### Integration 4: Tempahan Selesai → Pergerakan Aset (Update Status)
```
When: Tempahan status changed to 'Selesai'
Action: Update pergerakan_aset status to 'Sudah Pulang'

Flow:
TempahanFasiliti::update([
    'status_tempahan' => 'Selesai',
])
    ↓
Event: TempahanFasilitiSelesai
    ↓
Listener: UpdatePergerakanAset (if fasiliti->jenis_fasiliti == 'Aset')
    ↓
PergerakanAset::where('rujukan_id', $tempahan->id)
    ->where('rujukan_type', 'TempahanFasiliti')
    ->update([
        'tarikh_sebenar_pulangan' => now(),
        'status_pulangan' => 'Sudah Pulang',
        'kondisi_selepas' => 'Baik', // Or from form input
    ])
    ↓
Update SenariAset:
    - status_aset = 'Aktif'
    - lokasi_semasa = (back to original location)
```

---

## ROUTES

```php
// Senarai Fasiliti
Route::resource('senarai-fasiliti', SenariFasilitiController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Tempahan Fasiliti
Route::resource('tempahan-fasiliti', TempahanFasilitiController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Tempahan Fasiliti - Workflow Actions
Route::post('tempahan-fasiliti/{id}/semak', [TempahanFasilitiController::class, 'semak'])
    ->name('tempahan-fasiliti.semak')
    ->middleware(['auth', 'verified', 'permission:operasi,update']);

Route::post('tempahan-fasiliti/{id}/lulus', [TempahanFasilitiController::class, 'lulus'])
    ->name('tempahan-fasiliti.lulus')
    ->middleware(['auth', 'verified', 'permission:operasi,approve']);

Route::post('tempahan-fasiliti/{id}/tolak', [TempahanFasilitiController::class, 'tolak'])
    ->name('tempahan-fasiliti.tolak')
    ->middleware(['auth', 'verified', 'permission:operasi,update']);

Route::post('tempahan-fasiliti/{id}/batal', [TempahanFasilitiController::class, 'batal'])
    ->name('tempahan-fasiliti.batal')
    ->middleware(['auth', 'verified', 'permission:operasi,delete']);

Route::post('tempahan-fasiliti/{id}/selesai', [TempahanFasilitiController::class, 'selesai'])
    ->name('tempahan-fasiliti.selesai')
    ->middleware(['auth', 'verified', 'permission:operasi,update']);

// Pembayaran Sewa
Route::resource('pembayaran-sewa', PembayaranSewaController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Laporan Tempahan
Route::get('laporan-tempahan', [LaporanTempahanController::class, 'index'])
    ->name('laporan-tempahan.index')
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

Route::get('laporan-tempahan/pdf', [LaporanTempahanController::class, 'pdf'])
    ->name('laporan-tempahan.pdf')
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

Route::get('laporan-tempahan/excel', [LaporanTempahanController::class, 'excel'])
    ->name('laporan-tempahan.excel')
    ->middleware(['auth', 'verified', 'permission:operasi,read']);
```

---

## MODELS

### SenariFasiliti.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class SenariFasiliti extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'senarai_fasiliti';

    protected $fillable = [
        'masjid_id',
        'kod_fasiliti',
        'nama_fasiliti',
        'jenis_fasiliti',
        'kategori_fasiliti',
        'senarai_aset_id',
        'kapasiti_maksimum',
        'luas_kawasan',
        'kemudahan',
        'spesifikasi',
        'harga_sewa_sejam',
        'harga_sewa_sehari',
        'harga_sewa_separuh_hari',
        'deposit_diperlukan',
        'syarat_tempahan',
        'peraturan_penggunaan',
        'had_minimum_tempahan',
        'had_maksimum_tempahan',
        'gambar_fasiliti',
        'dokumen_peraturan',
        'status_fasiliti',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'harga_sewa_sejam' => 'decimal:2',
        'harga_sewa_sehari' => 'decimal:2',
        'harga_sewa_separuh_hari' => 'decimal:2',
        'deposit_diperlukan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariAset()
    {
        return $this->belongsTo(SenariAset::class, 'senarai_aset_id');
    }

    public function tempahanFasiliti()
    {
        return $this->hasMany(TempahanFasiliti::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status_fasiliti', 'Tersedia');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_fasiliti', $jenis);
    }

    // Methods
    public static function generateKodFasiliti($masjidId)
    {
        $year = date('Y');
        $lastFasiliti = self::where('masjid_id', $masjidId)
            ->where('kod_fasiliti', 'like', "FS-{$year}-%")
            ->orderBy('kod_fasiliti', 'desc')
            ->first();

        if ($lastFasiliti) {
            $lastNumber = (int) substr($lastFasiliti->kod_fasiliti, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "FS-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
```


### TempahanFasiliti.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class TempahanFasiliti extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'tempahan_fasiliti';

    protected $fillable = [
        'masjid_id',
        'no_tempahan',
        'senarai_fasiliti_id',
        'nama_penyewa',
        'no_ic_penyewa',
        'no_telefon_penyewa',
        'emel_penyewa',
        'alamat_penyewa_1',
        'alamat_penyewa_2',
        'poskod_penyewa',
        'bandar_penyewa',
        'negeri_penyewa',
        'organisasi_penyewa',
        'tarikh_tempahan',
        'tarikh_mula',
        'tarikh_tamat',
        'tempoh_sewa',
        'unit_tempoh',
        'tujuan_tempahan',
        'jenis_acara',
        'bilangan_jangka_peserta',
        'harga_sewa',
        'deposit',
        'jumlah_bayaran',
        'surat_permohonan_path',
        'salinan_ic_path',
        'surat_sokongan_path',
        'dokumen_lain',
        'status_tempahan',
        'disemak_oleh',
        'tarikh_disemak',
        'catatan_semakan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'ditolak_oleh',
        'tarikh_ditolak',
        'sebab_tolak',
        'dibatalkan_oleh',
        'tarikh_dibatalkan',
        'sebab_batal',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_tempahan' => 'date',
        'tarikh_mula' => 'datetime',
        'tarikh_tamat' => 'datetime',
        'tarikh_disemak' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'tarikh_ditolak' => 'datetime',
        'tarikh_dibatalkan' => 'datetime',
        'harga_sewa' => 'decimal:2',
        'deposit' => 'decimal:2',
        'jumlah_bayaran' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function pembayaranSewa()
    {
        return $this->hasOne(PembayaranSewa::class);
    }

    public function disemakOleh()
    {
        return $this->belongsTo(User::class, 'disemak_oleh');
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    public function ditolakOleh()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    public function dibatalkanOleh()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeBaharu($query)
    {
        return $query->where('status_tempahan', 'Baharu');
    }

    public function scopeLulus($query)
    {
        return $query->where('status_tempahan', 'Lulus');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_tempahan', 'Lulus')
                     ->where('tarikh_tamat', '>=', now());
    }

    // Methods
    public static function generateNoTempahan($masjidId)
    {
        $year = date('Y');
        $lastTempahan = self::where('masjid_id', $masjidId)
            ->where('no_tempahan', 'like', "TP-{$year}-%")
            ->orderBy('no_tempahan', 'desc')
            ->first();

        if ($lastTempahan) {
            $lastNumber = (int) substr($lastTempahan->no_tempahan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "TP-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### PembayaranSewa.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class PembayaranSewa extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'pembayaran_sewa';

    protected $fillable = [
        'masjid_id',
        'no_pembayaran',
        'tempahan_fasiliti_id',
        'senarai_fasiliti_id',
        'tarikh_pembayaran',
        'jumlah_sewa',
        'jumlah_deposit',
        'jumlah_bayaran',
        'kaedah_bayaran',
        'nama_bank',
        'no_akaun',
        'no_rujukan',
        'no_cek',
        'tarikh_cek',
        'resit_pembayaran_path',
        'bukti_transfer_path',
        'salinan_cek_path',
        'deposit_dikembalikan',
        'tarikh_kembalikan_deposit',
        'sebab_potongan_deposit',
        'status_pembayaran',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_pembayaran' => 'date',
        'tarikh_cek' => 'date',
        'tarikh_kembalikan_deposit' => 'date',
        'jumlah_sewa' => 'decimal:2',
        'jumlah_deposit' => 'decimal:2',
        'jumlah_bayaran' => 'decimal:2',
        'deposit_dikembalikan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function tempahanFasiliti()
    {
        return $this->belongsTo(TempahanFasiliti::class);
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSudahBayar($query)
    {
        return $query->where('status_pembayaran', 'Sudah Bayar');
    }

    public function scopeBelumBayar($query)
    {
        return $query->where('status_pembayaran', 'Belum Bayar');
    }

    // Methods
    public static function generateNoPembayaran($masjidId)
    {
        $year = date('Y');
        $lastPembayaran = self::where('masjid_id', $masjidId)
            ->where('no_pembayaran', 'like', "PS-{$year}-%")
            ->orderBy('no_pembayaran', 'desc')
            ->first();

        if ($lastPembayaran) {
            $lastNumber = (int) substr($lastPembayaran->no_pembayaran, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "PS-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

---

## UI/UX DESIGN STANDARDS

### Following Masjid Project Rules

**Font:**
- Family: Poppins (consistent across all pages)
- Size: 10px - 14px only
- Headings: 14px bold
- Body text: 12px regular
- Small text: 10px regular

**Border Radius:**
- Cards: 8px
- Buttons: 6px
- Input fields: 4px
- Badges: 4px
- Don't overuse border radius

**Colors:**
- Primary: Blue (#3B82F6)
- Success: Green (#10B981)
- Warning: Orange (#F59E0B)
- Danger: Red (#EF4444)
- Gray: #6B7280

**Spacing:**
- Padding: 12px, 16px, 20px
- Margin: 12px, 16px, 20px
- Gap: 12px, 16px

**Components:**
- Use existing components from `resources/views/components/`
- Follow pattern from Asnaf, Kebajikan, Kewangan, Aset modules
- Consistent table styling
- Consistent form styling
- Consistent button styling

---

## IMPLEMENTATION PLAN

### Phase 1: Database & Backend (Day 1)
**Morning (4 hours):**
- Create 3 migrations (senarai_fasiliti, tempahan_fasiliti, pembayaran_sewa)
- Create 3 models with relationships
- Create 4 controllers with basic CRUD
- Setup routes with permissions

**Afternoon (4 hours):**
- Create seeders for default data (if needed)
- Test migrations
- Test models & relationships
- Test multi-masjid isolation

### Phase 2: Views & UI (Day 2)
**Morning (4 hours):**
- Create Senarai Fasiliti views (4 files: index, create, edit, show)
- Create Tempahan Fasiliti views (4 files: index, create, edit, show)

**Afternoon (4 hours):**
- Create Pembayaran Sewa views (4 files: index, create, edit, show)
- Create Laporan Tempahan view (1 file with tabs)
- Update navbar menu

### Phase 3: Integration & Testing (Day 3)
**Morning (4 hours):**
- Integrate with Aset Module (auto-create Pergerakan Aset)
- Integrate with Kewangan Module (auto-create Kutipan Dana)
- Test workflow actions
- Test all integrations

**Afternoon (4 hours):**
- Test all CRUD operations
- Test reports generation
- Test file uploads
- Test multi-masjid isolation
- Fix bugs & polish UI

**Total: 3 days (24 hours)**

---

## SUMMARY

### What Will Be Built

**4 Main Modules:**
1. ✅ **Senarai Fasiliti** - Master data for facilities & assets (4 pages)
2. ✅ **Tempahan Fasiliti** - Booking management with workflow (4 pages)
3. ✅ **Pembayaran Sewa** - Payment tracking (4 pages)
4. ✅ **Laporan Tempahan** - Reports with charts (1 page)

**Total Pages: ~13 pages**

**Database Tables: 3 tables**
- senarai_fasiliti
- tempahan_fasiliti
- pembayaran_sewa

**Controllers: 4 controllers**
- SenariFasilitiController
- TempahanFasilitiController
- PembayaranSewaController
- LaporanTempahanController

**Models: 3 models**
- SenariFasiliti
- TempahanFasiliti
- PembayaranSewa

**Integration Points: 4 integrations**
- Tempahan Lulus → Auto-create Pergerakan Aset (if jenis=Aset)
- Tempahan Lulus → Auto-create Pembayaran Sewa
- Pembayaran Sewa (Sudah Bayar) → Auto-create Kutipan Dana (Kewangan)
- Tempahan Selesai → Update Pergerakan Aset status

---

## BENEFITS

### ✅ Advantages:
1. **Integrated System** - Works seamlessly with Aset & Kewangan modules
2. **Auto-Create Records** - Reduces manual data entry
3. **Complete Tracking** - From booking to payment to asset movement
4. **Financial Integration** - All rental income auto-recorded in Kewangan
5. **Asset Management** - Auto-track asset location when rented out
6. **Multi-Masjid Ready** - Data isolation built-in
7. **Workflow System** - Approval process for bookings

---

## CONCLUSION

Modul Fasiliti & Tempahan ini direka khusus untuk keperluan masjid dengan:
- ✅ Integration penuh dengan Modul Aset (Pergerakan Aset)
- ✅ Integration penuh dengan Modul Kewangan (Kutipan Dana)
- ✅ Auto-create records untuk kurangkan manual entry
- ✅ Workflow approval system
- ✅ Multi-masjid data isolation
- ✅ Comprehensive reporting
- ✅ Fast implementation (3 hari)

**Status**: Ready for implementation
**Estimated Time**: 3 days
**Complexity**: Medium-High (due to integrations)
**Priority**: High (Core functionality)

---

**Last Updated**: 15 Dec 2025
**Document Version**: 1.0
**Author**: Kiro AI Assistant

