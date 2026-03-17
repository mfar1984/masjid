# KEBAJIKAN MODULE - COMPLETE DESIGN SPECIFICATION

## MODULE OVERVIEW
Kebajikan module manages welfare programs for non-asnaf beneficiaries with comprehensive application workflow, payment tracking, and reporting.

## NAVIGATION STRUCTURE
```
Kebajikan (Main Menu)
├── Program Kebajikan (Master data for welfare programs)
├── Penerima Bantuan (Beneficiary database)
├── Permohonan Bantuan (Aid applications with workflow)
├── Pembayaran Bantuan (Payment/disbursement)
├── Laporan Kebajikan (Statistics and reports)
└── Tetapan Kebajikan (Configuration)
```

## MULTI-MASJID ISOLATION
- **Super Admin**: Can view all data, filter by masjid
- **Admin Masjid**: Only see their own masjid data, auto-assigned masjid_id
- All models use `HasMasjidScope` trait
- All controllers check user role and filter by masjid_id
- Follow exact pattern from Asnaf/AJK modules

## PERMISSIONS
```php
'kebajikan' => [
    'create' => 'Cipta Kebajikan',
    'read' => 'Lihat Kebajikan',
    'update' => 'Kemaskini Kebajikan',
    'delete' => 'Padam Kebajikan',
    'archive' => 'Arkib Kebajikan',
]
```

---

## 1. PROGRAM KEBAJIKAN

### Database Schema
**Table**: `program_kebajikan`

```sql
CREATE TABLE program_kebajikan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    kod_program VARCHAR(50) UNIQUE NOT NULL,
    nama_program VARCHAR(255) NOT NULL,
    kategori_program ENUM('Pendidikan', 'Kesihatan', 'Kecemasan', 'Kebajikan Am', 'Anak Yatim', 'OKU', 'Warga Emas', 'Ibu Tunggal', 'Lain-lain') NOT NULL,
    jenis_bantuan ENUM('Tunai', 'Barangan', 'Perkhidmatan', 'Campuran') NOT NULL,
    had_maksimum DECIMAL(10,2) DEFAULT NULL,
    had_minimum DECIMAL(10,2) DEFAULT NULL,
    tempoh_bantuan ENUM('Sekali', 'Bulanan', 'Tahunan', 'Mengikut Keperluan') NOT NULL,
    syarat_kelayakan TEXT,
    dokumen_diperlukan TEXT,
    status_program ENUM('Aktif', 'Tidak Aktif', 'Tamat') DEFAULT 'Aktif',
    tarikh_mula DATE,
    tarikh_tamat DATE,
    catatan TEXT,
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)
1. **Maklumat Program**
   - `kod_program` - Text (auto-generate: KB-YYYY-0001, readonly on edit)
   - `nama_program` - Text (required, max 255)
   - `kategori_program` - Dropdown (required)
     * Pendidikan
     * Kesihatan
     * Kecemasan
     * Kebajikan Am
     * Anak Yatim
     * OKU
     * Warga Emas
     * Ibu Tunggal
     * Lain-lain
   - `jenis_bantuan` - Dropdown (required)
     * Tunai
     * Barangan
     * Perkhidmatan
     * Campuran

2. **Had Bantuan**
   - `had_minimum` - Number (decimal 10,2, optional)
   - `had_maksimum` - Number (decimal 10,2, optional)
   - `tempoh_bantuan` - Dropdown (required)
     * Sekali
     * Bulanan
     * Tahunan
     * Mengikut Keperluan

3. **Tempoh Program**
   - `tarikh_mula` - Date (optional)
   - `tarikh_tamat` - Date (optional)
   - `status_program` - Dropdown (required, default: Aktif)
     * Aktif
     * Tidak Aktif
     * Tamat

4. **Syarat & Dokumen**
   - `syarat_kelayakan` - Textarea (optional)
   - `dokumen_diperlukan` - Textarea (optional, list of required documents)
   - `catatan` - Textarea (optional)

### Index Page
- **Stats Cards** (using x-statistics-grid):
  * Total Program
  * Program Aktif
  * Program Tidak Aktif
  * Program Tamat

- **Filter** (1 row flexbox):
  * Kategori Program (dropdown)
  * Jenis Bantuan (dropdown)
  * Status Program (dropdown)
  * Cari (search: kod_program, nama_program)
  * Reset button

- **Table Columns** (Desktop):
  * Kod Program
  * Nama Program
  * Kategori
  * Jenis Bantuan
  * Had Maksimum
  * Status
  * Tindakan (View, Edit, Archive, Delete)

- **Card View** (Mobile):
  * Kod Program (bold)
  * Nama Program
  * Kategori | Jenis Bantuan
  * Had Maksimum
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Program
2. Had Bantuan
3. Tempoh Program
4. Syarat & Dokumen
5. Maklumat Audit

---

## 2. PENERIMA BANTUAN

### Database Schema
**Table**: `penerima_bantuan`

```sql
CREATE TABLE penerima_bantuan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_pendaftaran VARCHAR(50) UNIQUE NOT NULL,
    
    -- Maklumat Peribadi
    nama_penuh VARCHAR(255) NOT NULL,
    no_kp VARCHAR(12) UNIQUE NOT NULL,
    jantina ENUM('Lelaki', 'Perempuan') NOT NULL,
    tarikh_lahir DATE NOT NULL,
    umur INT,
    bangsa VARCHAR(100),
    agama VARCHAR(100) DEFAULT 'Islam',
    status_perkahwinan ENUM('Bujang', 'Berkahwin', 'Duda', 'Janda', 'Bercerai') NOT NULL,
    kewarganegaraan VARCHAR(100) DEFAULT 'Malaysia',
    
    -- Maklumat Hubungan
    no_telefon VARCHAR(20) NOT NULL,
    no_telefon_kecemasan VARCHAR(20),
    emel VARCHAR(255),
    
    -- Alamat Semasa
    alamat_1 VARCHAR(255) NOT NULL,
    alamat_2 VARCHAR(255),
    poskod VARCHAR(10) NOT NULL,
    bandar VARCHAR(100) NOT NULL,
    negeri VARCHAR(100) NOT NULL,
    
    -- Maklumat Keluarga
    bilangan_tanggungan INT DEFAULT 0,
    bilangan_anak INT DEFAULT 0,
    bilangan_anak_sekolah INT DEFAULT 0,
    nama_pasangan VARCHAR(255),
    no_kp_pasangan VARCHAR(12),
    pekerjaan_pasangan VARCHAR(255),
    pendapatan_pasangan DECIMAL(10,2),
    
    -- Maklumat Pekerjaan & Kewangan
    status_pekerjaan ENUM('Bekerja', 'Tidak Bekerja', 'Pesara', 'OKU', 'Pelajar', 'Suri Rumah') NOT NULL,
    pekerjaan VARCHAR(255),
    majikan VARCHAR(255),
    pendapatan_bulanan DECIMAL(10,2),
    pendapatan_lain DECIMAL(10,2),
    jumlah_pendapatan DECIMAL(10,2),
    
    -- Maklumat Perumahan
    jenis_kediaman ENUM('Rumah Sendiri', 'Rumah Sewa', 'Rumah Keluarga', 'Rumah Pangsa', 'Rumah Setinggan', 'Lain-lain') NOT NULL,
    sewa_bulanan DECIMAL(10,2),
    
    -- Kategori Kebajikan
    kategori_penerima VARCHAR(255),
    status_oku ENUM('Ya', 'Tidak') DEFAULT 'Tidak',
    jenis_oku VARCHAR(255),
    no_kad_oku VARCHAR(50),
    status_yatim ENUM('Ya', 'Tidak') DEFAULT 'Tidak',
    status_ibu_tunggal ENUM('Ya', 'Tidak') DEFAULT 'Tidak',
    status_warga_emas ENUM('Ya', 'Tidak') DEFAULT 'Tidak',
    
    -- Dokumen (JSON array of file paths)
    gambar_profil VARCHAR(255),
    salinan_ic TEXT,
    salinan_ic_pasangan TEXT,
    sijil_lahir_anak TEXT,
    slip_gaji TEXT,
    penyata_bank TEXT,
    kad_oku TEXT,
    sijil_kematian TEXT,
    surat_sokongan TEXT,
    dokumen_lain TEXT,
    
    -- Status
    status_penerima ENUM('Aktif', 'Tidak Aktif', 'Tamat') DEFAULT 'Aktif',
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Peribadi**
- `nama_penuh` - Text (required, max 255)
- `no_kp` - Text (required, 12 digits, unique, format: 000000-00-0000)
- `jantina` - Radio (required): Lelaki, Perempuan
- `tarikh_lahir` - Date (required, auto-calculate umur)
- `umur` - Number (readonly, auto-calculated)
- `bangsa` - Text (optional)
- `agama` - Text (default: Islam)
- `status_perkahwinan` - Dropdown (required)
  * Bujang
  * Berkahwin
  * Duda
  * Janda
  * Bercerai
- `kewarganegaraan` - Text (default: Malaysia)

**Section 2: Maklumat Hubungan**
- `no_telefon` - Text (required, format: 01X-XXXXXXX)
- `no_telefon_kecemasan` - Text (optional)
- `emel` - Email (optional)

**Section 3: Alamat Semasa**
- `alamat_1` - Text (required)
- `alamat_2` - Text (optional)
- `poskod` - Text (required, 5 digits)
- `bandar` - Text (required)
- `negeri` - Dropdown (required, 14 states)

**Section 4: Maklumat Keluarga**
- `bilangan_tanggungan` - Number (default: 0)
- `bilangan_anak` - Number (default: 0)
- `bilangan_anak_sekolah` - Number (default: 0)
- `nama_pasangan` - Text (optional)
- `no_kp_pasangan` - Text (optional, 12 digits)
- `pekerjaan_pasangan` - Text (optional)
- `pendapatan_pasangan` - Number (decimal 10,2, optional)

**Section 5: Maklumat Pekerjaan & Kewangan**
- `status_pekerjaan` - Dropdown (required)
  * Bekerja
  * Tidak Bekerja
  * Pesara
  * OKU
  * Pelajar
  * Suri Rumah
- `pekerjaan` - Text (optional)
- `majikan` - Text (optional)
- `pendapatan_bulanan` - Number (decimal 10,2, optional)
- `pendapatan_lain` - Number (decimal 10,2, optional)
- `jumlah_pendapatan` - Number (readonly, auto-calculated: pendapatan_bulanan + pendapatan_lain + pendapatan_pasangan)

**Section 6: Maklumat Perumahan**
- `jenis_kediaman` - Dropdown (required)
  * Rumah Sendiri
  * Rumah Sewa
  * Rumah Keluarga
  * Rumah Pangsa
  * Rumah Setinggan
  * Lain-lain
- `sewa_bulanan` - Number (decimal 10,2, optional, show if Rumah Sewa)

**Section 7: Kategori Kebajikan**
- `kategori_penerima` - Text (optional, e.g., "Anak Yatim, OKU")
- `status_oku` - Radio (default: Tidak): Ya, Tidak
- `jenis_oku` - Text (optional, show if status_oku = Ya)
- `no_kad_oku` - Text (optional, show if status_oku = Ya)
- `status_yatim` - Radio (default: Tidak): Ya, Tidak
- `status_ibu_tunggal` - Radio (default: Tidak): Ya, Tidak
- `status_warga_emas` - Radio (default: Tidak): Ya, Tidak

**Section 8: Muat Naik Dokumen**
All file uploads: Max 5MB, PDF/JPG/PNG only

- `gambar_profil` - File (single, image only)
- `salinan_ic` - File (multiple, max 2 files)
- `salinan_ic_pasangan` - File (multiple, max 2 files)
- `sijil_lahir_anak` - File (multiple, max 5 files)
- `slip_gaji` - File (multiple, max 3 files)
- `penyata_bank` - File (multiple, max 3 files)
- `kad_oku` - File (multiple, max 2 files)
- `sijil_kematian` - File (single)
- `surat_sokongan` - File (multiple, max 3 files)
- `dokumen_lain` - File (multiple, max 5 files)

**Section 9: Status & Catatan**
- `status_penerima` - Dropdown (required, default: Aktif)
  * Aktif
  * Tidak Aktif
  * Tamat
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Penerima
  * Penerima Aktif
  * Penerima Tidak Aktif
  * Total Tanggungan

- **Filter** (1 row):
  * Status Penerima (dropdown)
  * Kategori Penerima (dropdown)
  * Status OKU (dropdown)
  * Cari (no_pendaftaran, nama_penuh, no_kp)
  * Reset

- **Table Columns** (Desktop):
  * No. Pendaftaran
  * Nama Penuh
  * No. KP
  * No. Telefon
  * Kategori
  * Status
  * Tindakan

- **Card View** (Mobile):
  * No. Pendaftaran (bold)
  * Nama Penuh
  * No. KP | No. Telefon
  * Kategori badges
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Peribadi
2. Maklumat Hubungan
3. Alamat Semasa
4. Maklumat Keluarga
5. Maklumat Pekerjaan & Kewangan
6. Maklumat Perumahan
7. Kategori Kebajikan
8. Dokumen (with download links)
9. Status & Catatan
10. Maklumat Audit
11. Sejarah Permohonan (list of applications)

---

## 3. PERMOHONAN BANTUAN

### Database Schema
**Table**: `permohonan_bantuan`

```sql
CREATE TABLE permohonan_bantuan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_permohonan VARCHAR(50) UNIQUE NOT NULL,
    
    -- Relations
    penerima_bantuan_id BIGINT UNSIGNED NOT NULL,
    program_kebajikan_id BIGINT UNSIGNED NOT NULL,
    
    -- Maklumat Permohonan
    tarikh_permohonan DATE NOT NULL,
    jenis_bantuan ENUM('Tunai', 'Barangan', 'Perkhidmatan', 'Campuran') NOT NULL,
    jumlah_dipohon DECIMAL(10,2),
    tujuan_permohonan TEXT NOT NULL,
    keutamaan ENUM('Biasa', 'Sederhana', 'Tinggi', 'Kecemasan') DEFAULT 'Biasa',
    
    -- Dokumen Sokongan (JSON array)
    surat_permohonan TEXT,
    surat_hospital TEXT,
    sijil_kematian TEXT,
    resit_perbelanjaan TEXT,
    gambar_bukti_1 TEXT,
    gambar_bukti_2 TEXT,
    gambar_bukti_3 TEXT,
    dokumen_sokongan_lain TEXT,
    
    -- Lawatan Rumah
    tarikh_lawatan DATE,
    masa_lawatan TIME,
    pegawai_lawatan VARCHAR(255),
    laporan_lawatan TEXT,
    gambar_lawatan_1 TEXT,
    gambar_lawatan_2 TEXT,
    gambar_lawatan_3 TEXT,
    skor_kelayakan INT,
    
    -- Keputusan
    status_permohonan ENUM('Baharu', 'Dalam Semakan', 'Lawatan Rumah', 'Lulus', 'Ditolak', 'Dibatalkan') DEFAULT 'Baharu',
    tarikh_keputusan DATE,
    jumlah_diluluskan DECIMAL(10,2),
    catatan_keputusan TEXT,
    sebab_tolak TEXT,
    
    -- Approval Workflow
    disemak_oleh BIGINT UNSIGNED,
    tarikh_disemak DATETIME,
    catatan_semakan TEXT,
    
    diluluskan_oleh BIGINT UNSIGNED,
    tarikh_diluluskan DATETIME,
    catatan_kelulusan TEXT,
    
    ditolak_oleh BIGINT UNSIGNED,
    tarikh_ditolak DATETIME,
    
    dibatalkan_oleh BIGINT UNSIGNED,
    tarikh_dibatalkan DATETIME,
    sebab_batal TEXT,
    
    -- Additional Info
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (penerima_bantuan_id) REFERENCES penerima_bantuan(id) ON DELETE CASCADE,
    FOREIGN KEY (program_kebajikan_id) REFERENCES program_kebajikan(id) ON DELETE CASCADE,
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

**Section 1: Maklumat Permohonan**
- `no_permohonan` - Text (auto-generate: PB-YYYY-0001, readonly)
- `tarikh_permohonan` - Date (required, default: today)
- `penerima_bantuan_id` - Dropdown/Search (required, show: no_pendaftaran - nama_penuh)
  * On select, auto-populate penerima details below (readonly display)
- `program_kebajikan_id` - Dropdown (required, filter by status=Aktif)
  * On select, show program details (jenis_bantuan, had_maksimum)
- `jenis_bantuan` - Dropdown (required, auto-fill from program)
  * Tunai
  * Barangan
  * Perkhidmatan
  * Campuran
- `jumlah_dipohon` - Number (decimal 10,2, required if jenis=Tunai/Campuran)
- `tujuan_permohonan` - Textarea (required)
- `keutamaan` - Dropdown (required, default: Biasa)
  * Biasa
  * Sederhana
  * Tinggi
  * Kecemasan

**Section 2: Dokumen Sokongan**
All files: Max 5MB, PDF/JPG/PNG

- `surat_permohonan` - File (multiple, max 2 files)
- `surat_hospital` - File (multiple, max 3 files)
- `sijil_kematian` - File (single)
- `resit_perbelanjaan` - File (multiple, max 5 files)
- `gambar_bukti_1` - File (single, image only)
- `gambar_bukti_2` - File (single, image only)
- `gambar_bukti_3` - File (single, image only)
- `dokumen_sokongan_lain` - File (multiple, max 5 files)

**Section 3: Lawatan Rumah** (Show only if status >= 'Lawatan Rumah')
- `tarikh_lawatan` - Date (optional)
- `masa_lawatan` - Time (optional)
- `pegawai_lawatan` - Text (optional)
- `laporan_lawatan` - Textarea (optional)
- `gambar_lawatan_1` - File (single, image only)
- `gambar_lawatan_2` - File (single, image only)
- `gambar_lawatan_3` - File (single, image only)
- `skor_kelayakan` - Number (0-100, optional)

**Section 4: Keputusan** (Show only on Edit, for authorized users)
- `status_permohonan` - Dropdown (required)
  * Baharu
  * Dalam Semakan
  * Lawatan Rumah
  * Lulus
  * Ditolak
  * Dibatalkan
- `tarikh_keputusan` - Date (required if status=Lulus/Ditolak)
- `jumlah_diluluskan` - Number (decimal 10,2, required if status=Lulus)
- `catatan_keputusan` - Textarea (optional)
- `sebab_tolak` - Textarea (required if status=Ditolak)
- `sebab_batal` - Textarea (required if status=Dibatalkan)

**Section 5: Catatan**
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Permohonan
  * Baharu
  * Dalam Semakan
  * Lawatan Rumah
  * Lulus
  * Ditolak

- **Filter** (1 row):
  * Program Kebajikan (dropdown)
  * Status Permohonan (dropdown)
  * Keutamaan (dropdown)
  * Tarikh Dari (date)
  * Tarikh Hingga (date)
  * Cari (no_permohonan, nama penerima)
  * Reset

- **Table Columns** (Desktop):
  * No. Permohonan
  * Tarikh
  * Nama Penerima
  * Program
  * Jumlah Dipohon
  * Keutamaan (badge)
  * Status (badge)
  * Tindakan

- **Card View** (Mobile):
  * No. Permohonan (bold)
  * Tarikh | Keutamaan badge
  * Nama Penerima
  * Program
  * Jumlah Dipohon
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Permohonan
2. Maklumat Penerima (from penerima_bantuan)
3. Maklumat Program (from program_kebajikan)
4. Dokumen Sokongan (with download/view)
5. Lawatan Rumah (if exists)
6. Keputusan
7. Workflow Timeline (Baharu → Semakan → Lawatan → Keputusan)
8. Catatan
9. Maklumat Audit

### Workflow Actions (Buttons on Show Page)
- **Semak** (if status=Baharu, permission: update)
- **Lawatan Rumah** (if status=Dalam Semakan, permission: update)
- **Lulus** (if status=Lawatan Rumah, permission: update)
- **Tolak** (if status=Baharu/Dalam Semakan/Lawatan Rumah, permission: update)
- **Batal** (if status!=Lulus/Ditolak/Dibatalkan, permission: delete)

---

## 4. PEMBAYARAN BANTUAN

### Database Schema
**Table**: `pembayaran_bantuan`

```sql
CREATE TABLE pembayaran_bantuan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_pembayaran VARCHAR(50) UNIQUE NOT NULL,
    
    -- Relations
    permohonan_bantuan_id BIGINT UNSIGNED NOT NULL,
    penerima_bantuan_id BIGINT UNSIGNED NOT NULL,
    program_kebajikan_id BIGINT UNSIGNED NOT NULL,
    
    -- Maklumat Pembayaran
    tarikh_pembayaran DATE NOT NULL,
    jumlah_bayaran DECIMAL(10,2) NOT NULL,
    kaedah_bayaran ENUM('Tunai', 'Cek', 'Bank Transfer', 'Barangan', 'Baucar') NOT NULL,
    
    -- Bank Details (if applicable)
    nama_bank VARCHAR(255),
    no_akaun VARCHAR(50),
    no_rujukan VARCHAR(100),
    
    -- Cek Details (if applicable)
    no_cek VARCHAR(50),
    tarikh_cek DATE,
    
    -- Barangan Details (if applicable)
    senarai_barangan TEXT,
    nilai_barangan DECIMAL(10,2),
    
    -- Dokumen Pembayaran (JSON array)
    resit_pembayaran TEXT,
    salinan_cek TEXT,
    bukti_transfer TEXT,
    gambar_penyerahan_1 TEXT,
    gambar_penyerahan_2 TEXT,
    gambar_penyerahan_3 TEXT,
    
    -- Penerimaan
    tarikh_diterima DATE,
    diterima_oleh VARCHAR(255),
    surat_akuan TEXT,
    tandatangan_digital TEXT,
    
    -- Status
    status_pembayaran ENUM('Belum Bayar', 'Sudah Bayar', 'Dibatalkan') DEFAULT 'Belum Bayar',
    catatan TEXT,
    
    -- Approval
    dibayar_oleh BIGINT UNSIGNED,
    tarikh_dibayar DATETIME,
    disahkan_oleh BIGINT UNSIGNED,
    tarikh_disahkan DATETIME,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (permohonan_bantuan_id) REFERENCES permohonan_bantuan(id) ON DELETE CASCADE,
    FOREIGN KEY (penerima_bantuan_id) REFERENCES penerima_bantuan(id) ON DELETE CASCADE,
    FOREIGN KEY (program_kebajikan_id) REFERENCES program_kebajikan(id) ON DELETE CASCADE,
    FOREIGN KEY (dibayar_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (disahkan_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Pembayaran**
- `no_pembayaran` - Text (auto-generate: PBY-YYYY-0001, readonly)
- `permohonan_bantuan_id` - Dropdown/Search (required, filter: status=Lulus, belum ada pembayaran)
  * Show: no_permohonan - nama penerima - jumlah diluluskan
  * On select, auto-populate:
    - penerima_bantuan_id
    - program_kebajikan_id
    - jumlah_bayaran (from jumlah_diluluskan)
- `tarikh_pembayaran` - Date (required, default: today)
- `jumlah_bayaran` - Number (decimal 10,2, required, readonly from permohonan)
- `kaedah_bayaran` - Dropdown (required)
  * Tunai
  * Cek
  * Bank Transfer
  * Barangan
  * Baucar

**Section 2: Maklumat Bank** (Show if kaedah=Bank Transfer)
- `nama_bank` - Dropdown (required)
  * Maybank
  * CIMB Bank
  * Public Bank
  * RHB Bank
  * Hong Leong Bank
  * AmBank
  * Bank Islam
  * Bank Rakyat
  * BSN
  * Lain-lain
- `no_akaun` - Text (required)
- `no_rujukan` - Text (required)

**Section 3: Maklumat Cek** (Show if kaedah=Cek)
- `no_cek` - Text (required)
- `tarikh_cek` - Date (required)
- `nama_bank` - Dropdown (required, same list as above)

**Section 4: Maklumat Barangan** (Show if kaedah=Barangan)
- `senarai_barangan` - Textarea (required, list of items)
- `nilai_barangan` - Number (decimal 10,2, required)

**Section 5: Dokumen Pembayaran**
All files: Max 5MB, PDF/JPG/PNG

- `resit_pembayaran` - File (multiple, max 3 files)
- `salinan_cek` - File (single, show if kaedah=Cek)
- `bukti_transfer` - File (single, show if kaedah=Bank Transfer)
- `gambar_penyerahan_1` - File (single, image only)
- `gambar_penyerahan_2` - File (single, image only)
- `gambar_penyerahan_3` - File (single, image only)

**Section 6: Penerimaan**
- `tarikh_diterima` - Date (optional)
- `diterima_oleh` - Text (optional, nama penerima)
- `surat_akuan` - File (single, PDF)
- `tandatangan_digital` - Canvas/Signature Pad (save as image)

**Section 7: Status & Catatan**
- `status_pembayaran` - Dropdown (required, default: Belum Bayar)
  * Belum Bayar
  * Sudah Bayar
  * Dibatalkan
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Pembayaran
  * Sudah Bayar
  * Belum Bayar
  * Jumlah Dibayar (RM)

- **Filter** (1 row):
  * Program Kebajikan (dropdown)
  * Kaedah Bayaran (dropdown)
  * Status Pembayaran (dropdown)
  * Tarikh Dari (date)
  * Tarikh Hingga (date)
  * Cari (no_pembayaran, nama penerima)
  * Reset

- **Table Columns** (Desktop):
  * No. Pembayaran
  * Tarikh
  * Nama Penerima
  * Program
  * Jumlah Bayaran
  * Kaedah Bayaran
  * Status (badge)
  * Tindakan

- **Card View** (Mobile):
  * No. Pembayaran (bold)
  * Tarikh | Kaedah
  * Nama Penerima
  * Program
  * Jumlah Bayaran
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Pembayaran
2. Maklumat Permohonan (from permohonan_bantuan)
3. Maklumat Penerima (from penerima_bantuan)
4. Maklumat Program (from program_kebajikan)
5. Maklumat Bank/Cek/Barangan (based on kaedah)
6. Dokumen Pembayaran (with download/view)
7. Penerimaan (with signature display)
8. Status & Catatan
9. Maklumat Audit

---

## 5. LAPORAN KEBAJIKAN

### Page Layout
Similar to Laporan Zakat, with filters and charts

**Filter Section** (1 row):
- Program Kebajikan (dropdown)
- Kategori Program (dropdown)
- Status (dropdown)
- Tarikh Dari (date)
- Tarikh Hingga (date)
- Cari (text)
- Reset button
- Cetak PDF button
- Export Excel button

**Stats Cards** (2 rows):
Row 1:
- Total Program
- Total Penerima
- Total Permohonan
- Total Pembayaran

Row 2:
- Permohonan Lulus
- Permohonan Ditolak
- Jumlah Dibayar (RM)
- Jumlah Belum Bayar (RM)

**Charts Section**:
1. **Permohonan Mengikut Status** (Pie Chart)
   - Baharu
   - Dalam Semakan
   - Lawatan Rumah
   - Lulus
   - Ditolak
   - Dibatalkan

2. **Pembayaran Mengikut Kaedah** (Bar Chart)
   - Tunai
   - Cek
   - Bank Transfer
   - Barangan
   - Baucar

3. **Permohonan Mengikut Program** (Bar Chart)
   - Top 10 programs by application count

4. **Trend Permohonan Bulanan** (Line Chart)
   - Last 12 months

5. **Penerima Mengikut Kategori** (Pie Chart)
   - Pendidikan
   - Kesihatan
   - Kecemasan
   - Kebajikan Am
   - Anak Yatim
   - OKU
   - Warga Emas
   - Ibu Tunggal
   - Lain-lain

**Table Section**:
- List of permohonan with filters applied
- Columns: No. Permohonan, Tarikh, Penerima, Program, Jumlah, Status
- Pagination

---

## 6. TETAPAN KEBAJIKAN

### Database Schema
**Table**: `tetapan_kebajikan`

```sql
CREATE TABLE tetapan_kebajikan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    setting_key VARCHAR(255) NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_masjid_setting (masjid_id, setting_key),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE
);
```

### Settings Categories (Tabs)

**Tab 1: Had Bantuan**
Settings:
- `had_minimum_pendidikan` - Number (RM)
- `had_maksimum_pendidikan` - Number (RM)
- `had_minimum_kesihatan` - Number (RM)
- `had_maksimum_kesihatan` - Number (RM)
- `had_minimum_kecemasan` - Number (RM)
- `had_maksimum_kecemasan` - Number (RM)
- `had_minimum_kebajikan_am` - Number (RM)
- `had_maksimum_kebajikan_am` - Number (RM)

**Tab 2: Workflow**
Settings:
- `auto_approve_below_amount` - Number (RM, auto-approve if below this amount)
- `require_home_visit` - Boolean (Ya/Tidak)
- `home_visit_mandatory_above` - Number (RM, mandatory if above this amount)
- `approval_levels` - Number (1-3 levels)
- `notification_email` - Text (email for notifications)
- `notification_sms` - Boolean (Ya/Tidak)

**Tab 3: Permohonan**
Settings:
- `allow_multiple_applications` - Boolean (Ya/Tidak, allow same penerima multiple active applications)
- `application_cooldown_days` - Number (days before can apply again)
- `max_applications_per_year` - Number (per penerima)
- `require_documents` - Boolean (Ya/Tidak)
- `mandatory_documents` - JSON (list of mandatory document types)

**Tab 4: Kategori Penerima**
Settings:
- `enable_oku` - Boolean (Ya/Tidak)
- `enable_yatim` - Boolean (Ya/Tidak)
- `enable_ibu_tunggal` - Boolean (Ya/Tidak)
- `enable_warga_emas` - Boolean (Ya/Tidak)
- `custom_categories` - JSON (array of custom category names)

**Tab 5: Pembayaran**
Settings:
- `default_payment_method` - Dropdown (Tunai/Cek/Bank Transfer/Barangan/Baucar)
- `enable_digital_signature` - Boolean (Ya/Tidak)
- `require_acknowledgment_letter` - Boolean (Ya/Tidak)
- `payment_approval_required` - Boolean (Ya/Tidak)

**Tab 6: Display Settings**
Settings:
- `show_penerima_photo` - Boolean (Ya/Tidak)
- `show_financial_details` - Boolean (Ya/Tidak)
- `items_per_page` - Number (10/25/50/100)
- `default_sort_order` - Dropdown (Terbaru/Terlama/Nama A-Z/Nama Z-A)

### Page Layout
Similar to Tetapan Asnaf with tabs

- Tab navigation at top
- Each tab shows relevant settings in a form
- Save button at bottom of each tab
- Settings are saved per masjid_id

---

## ROUTES

```php
// Program Kebajikan
Route::resource('program-kebajikan', ProgramKebajikanController::class)
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

// Penerima Bantuan
Route::resource('penerima-bantuan', PenerimaBantuanController::class)
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

// Permohonan Bantuan
Route::resource('permohonan-bantuan', PermohonanBantuanController::class)
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

// Permohonan Bantuan - Workflow Actions
Route::post('permohonan-bantuan/{id}/semak', [PermohonanBantuanController::class, 'semak'])
    ->name('permohonan-bantuan.semak')
    ->middleware(['auth', 'verified', 'permission:kebajikan,update']);

Route::post('permohonan-bantuan/{id}/lawatan', [PermohonanBantuanController::class, 'lawatan'])
    ->name('permohonan-bantuan.lawatan')
    ->middleware(['auth', 'verified', 'permission:kebajikan,update']);

Route::post('permohonan-bantuan/{id}/lulus', [PermohonanBantuanController::class, 'lulus'])
    ->name('permohonan-bantuan.lulus')
    ->middleware(['auth', 'verified', 'permission:kebajikan,update']);

Route::post('permohonan-bantuan/{id}/tolak', [PermohonanBantuanController::class, 'tolak'])
    ->name('permohonan-bantuan.tolak')
    ->middleware(['auth', 'verified', 'permission:kebajikan,update']);

Route::post('permohonan-bantuan/{id}/batal', [PermohonanBantuanController::class, 'batal'])
    ->name('permohonan-bantuan.batal')
    ->middleware(['auth', 'verified', 'permission:kebajikan,delete']);

// Pembayaran Bantuan
Route::resource('pembayaran-bantuan', PembayaranBantuanController::class)
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

// Laporan Kebajikan
Route::get('laporan-kebajikan', [LaporanKebajikanController::class, 'index'])
    ->name('laporan-kebajikan.index')
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

Route::get('laporan-kebajikan/pdf', [LaporanKebajikanController::class, 'pdf'])
    ->name('laporan-kebajikan.pdf')
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

Route::get('laporan-kebajikan/excel', [LaporanKebajikanController::class, 'excel'])
    ->name('laporan-kebajikan.excel')
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

// Tetapan Kebajikan
Route::get('tetapan-kebajikan', [TetapanKebajikanController::class, 'index'])
    ->name('tetapan-kebajikan.index')
    ->middleware(['auth', 'verified', 'permission:kebajikan,read']);

Route::post('tetapan-kebajikan', [TetapanKebajikanController::class, 'update'])
    ->name('tetapan-kebajikan.update')
    ->middleware(['auth', 'verified', 'permission:kebajikan,update']);
```

---

## MODELS

### ProgramKebajikan.php
```php
use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKebajikan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;
    
    protected $table = 'program_kebajikan';
    
    protected $fillable = [
        'masjid_id', 'kod_program', 'nama_program', 'kategori_program',
        'jenis_bantuan', 'had_maksimum', 'had_minimum', 'tempoh_bantuan',
        'syarat_kelayakan', 'dokumen_diperlukan', 'status_program',
        'tarikh_mula', 'tarikh_tamat', 'catatan'
    ];
    
    protected $casts = [
        'tarikh_mula' => 'date',
        'tarikh_tamat' => 'date',
        'had_maksimum' => 'decimal:2',
        'had_minimum' => 'decimal:2',
    ];
    
    // Relationships
    public function masjid() { return $this->belongsTo(Masjid::class); }
    public function permohonanBantuan() { return $this->hasMany(PermohonanBantuan::class); }
    public function pembayaranBantuan() { return $this->hasMany(PembayaranBantuan::class); }
    
    // Auto-generate kod_program
    public static function generateKodProgram($masjidId) {
        $year = date('Y');
        $prefix = 'KB-' . $year . '-';
        $lastProgram = self::where('masjid_id', $masjidId)
            ->where('kod_program', 'like', $prefix . '%')
            ->orderBy('kod_program', 'desc')
            ->first();
        $nextNumber = $lastProgram ? intval(substr($lastProgram->kod_program, -4)) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### PenerimaBantuan.php
```php
use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;
    
    protected $table = 'penerima_bantuan';
    
    protected $fillable = [
        'masjid_id', 'no_pendaftaran', 'nama_penuh', 'no_kp', 'jantina',
        'tarikh_lahir', 'umur', 'bangsa', 'agama', 'status_perkahwinan',
        'kewarganegaraan', 'no_telefon', 'no_telefon_kecemasan', 'emel',
        'alamat_1', 'alamat_2', 'poskod', 'bandar', 'negeri',
        'bilangan_tanggungan', 'bilangan_anak', 'bilangan_anak_sekolah',
        'nama_pasangan', 'no_kp_pasangan', 'pekerjaan_pasangan', 'pendapatan_pasangan',
        'status_pekerjaan', 'pekerjaan', 'majikan', 'pendapatan_bulanan',
        'pendapatan_lain', 'jumlah_pendapatan', 'jenis_kediaman', 'sewa_bulanan',
        'kategori_penerima', 'status_oku', 'jenis_oku', 'no_kad_oku',
        'status_yatim', 'status_ibu_tunggal', 'status_warga_emas',
        'gambar_profil', 'salinan_ic', 'salinan_ic_pasangan', 'sijil_lahir_anak',
        'slip_gaji', 'penyata_bank', 'kad_oku', 'sijil_kematian',
        'surat_sokongan', 'dokumen_lain', 'status_penerima', 'catatan'
    ];
    
    protected $casts = [
        'tarikh_lahir' => 'date',
        'pendapatan_pasangan' => 'decimal:2',
        'pendapatan_bulanan' => 'decimal:2',
        'pendapatan_lain' => 'decimal:2',
        'jumlah_pendapatan' => 'decimal:2',
        'sewa_bulanan' => 'decimal:2',
        'salinan_ic' => 'array',
        'salinan_ic_pasangan' => 'array',
        'sijil_lahir_anak' => 'array',
        'slip_gaji' => 'array',
        'penyata_bank' => 'array',
        'kad_oku' => 'array',
        'surat_sokongan' => 'array',
        'dokumen_lain' => 'array',
    ];
    
    // Relationships
    public function masjid() { return $this->belongsTo(Masjid::class); }
    public function permohonanBantuan() { return $this->hasMany(PermohonanBantuan::class); }
    public function pembayaranBantuan() { return $this->hasMany(PembayaranBantuan::class); }
    
    // Auto-generate no_pendaftaran
    public static function generateNoPendaftaran($masjidId) {
        $year = date('Y');
        $prefix = 'PNB-' . $year . '-';
        $lastPenerima = self::where('masjid_id', $masjidId)
            ->where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->first();
        $nextNumber = $lastPenerima ? intval(substr($lastPenerima->no_pendaftaran, -4)) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### PermohonanBantuan.php
```php
use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;
    
    protected $table = 'permohonan_bantuan';
    
    protected $fillable = [
        'masjid_id', 'no_permohonan', 'penerima_bantuan_id', 'program_kebajikan_id',
        'tarikh_permohonan', 'jenis_bantuan', 'jumlah_dipohon', 'tujuan_permohonan',
        'keutamaan', 'surat_permohonan', 'surat_hospital', 'sijil_kematian',
        'resit_perbelanjaan', 'gambar_bukti_1', 'gambar_bukti_2', 'gambar_bukti_3',
        'dokumen_sokongan_lain', 'tarikh_lawatan', 'masa_lawatan', 'pegawai_lawatan',
        'laporan_lawatan', 'gambar_lawatan_1', 'gambar_lawatan_2', 'gambar_lawatan_3',
        'skor_kelayakan', 'status_permohonan', 'tarikh_keputusan', 'jumlah_diluluskan',
        'catatan_keputusan', 'sebab_tolak', 'disemak_oleh', 'tarikh_disemak',
        'catatan_semakan', 'diluluskan_oleh', 'tarikh_diluluskan', 'catatan_kelulusan',
        'ditolak_oleh', 'tarikh_ditolak', 'dibatalkan_oleh', 'tarikh_dibatalkan',
        'sebab_batal', 'catatan'
    ];
    
    protected $casts = [
        'tarikh_permohonan' => 'date',
        'tarikh_lawatan' => 'date',
        'tarikh_keputusan' => 'date',
        'tarikh_disemak' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'tarikh_ditolak' => 'datetime',
        'tarikh_dibatalkan' => 'datetime',
        'jumlah_dipohon' => 'decimal:2',
        'jumlah_diluluskan' => 'decimal:2',
        'surat_permohonan' => 'array',
        'surat_hospital' => 'array',
        'resit_perbelanjaan' => 'array',
        'dokumen_sokongan_lain' => 'array',
    ];
    
    // Relationships
    public function masjid() { return $this->belongsTo(Masjid::class); }
    public function penerimaBantuan() { return $this->belongsTo(PenerimaBantuan::class); }
    public function programKebajikan() { return $this->belongsTo(ProgramKebajikan::class); }
    public function pembayaranBantuan() { return $this->hasOne(PembayaranBantuan::class); }
    
    // Auto-generate no_permohonan
    public static function generateNoPermohonan($masjidId) {
        $year = date('Y');
        $prefix = 'PB-' . $year . '-';
        $lastPermohonan = self::where('masjid_id', $masjidId)
            ->where('no_permohonan', 'like', $prefix . '%')
            ->orderBy('no_permohonan', 'desc')
            ->first();
        $nextNumber = $lastPermohonan ? intval(substr($lastPermohonan->no_permohonan, -4)) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### PembayaranBantuan.php
```php
use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;
    
    protected $table = 'pembayaran_bantuan';
    
    protected $fillable = [
        'masjid_id', 'no_pembayaran', 'permohonan_bantuan_id', 'penerima_bantuan_id',
        'program_kebajikan_id', 'tarikh_pembayaran', 'jumlah_bayaran', 'kaedah_bayaran',
        'nama_bank', 'no_akaun', 'no_rujukan', 'no_cek', 'tarikh_cek',
        'senarai_barangan', 'nilai_barangan', 'resit_pembayaran', 'salinan_cek',
        'bukti_transfer', 'gambar_penyerahan_1', 'gambar_penyerahan_2', 'gambar_penyerahan_3',
        'tarikh_diterima', 'diterima_oleh', 'surat_akuan', 'tandatangan_digital',
        'status_pembayaran', 'catatan', 'dibayar_oleh', 'tarikh_dibayar',
        'disahkan_oleh', 'tarikh_disahkan'
    ];
    
    protected $casts = [
        'tarikh_pembayaran' => 'date',
        'tarikh_cek' => 'date',
        'tarikh_diterima' => 'date',
        'tarikh_dibayar' => 'datetime',
        'tarikh_disahkan' => 'datetime',
        'jumlah_bayaran' => 'decimal:2',
        'nilai_barangan' => 'decimal:2',
        'resit_pembayaran' => 'array',
    ];
    
    // Relationships
    public function masjid() { return $this->belongsTo(Masjid::class); }
    public function permohonanBantuan() { return $this->belongsTo(PermohonanBantuan::class); }
    public function penerimaBantuan() { return $this->belongsTo(PenerimaBantuan::class); }
    public function programKebajikan() { return $this->belongsTo(ProgramKebajikan::class); }
    
    // Auto-generate no_pembayaran
    public static function generateNoPembayaran($masjidId) {
        $year = date('Y');
        $prefix = 'PBY-' . $year . '-';
        $lastPembayaran = self::where('masjid_id', $masjidId)
            ->where('no_pembayaran', 'like', $prefix . '%')
            ->orderBy('no_pembayaran', 'desc')
            ->first();
        $nextNumber = $lastPembayaran ? intval(substr($lastPembayaran->no_pembayaran, -4)) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### TetapanKebajikan.php
```php
class TetapanKebajikan extends Model
{
    use HasFactory;
    
    protected $table = 'tetapan_kebajikan';
    
    public $timestamps = true;
    
    protected $fillable = ['masjid_id', 'setting_key', 'setting_value', 'setting_type'];
    
    // Relationships
    public function masjid() { return $this->belongsTo(Masjid::class); }
    
    // Helper methods
    public static function getSetting($masjidId, $key, $default = null) {
        $setting = self::where('masjid_id', $masjidId)
            ->where('setting_key', $key)
            ->first();
        return $setting ? $setting->setting_value : $default;
    }
    
    public static function setSetting($masjidId, $key, $value, $type = 'text') {
        return self::updateOrCreate(
            ['masjid_id' => $masjidId, 'setting_key' => $key],
            ['setting_value' => $value, 'setting_type' => $type]
        );
    }
}
```

---

## IMPLEMENTATION ORDER

1. **Program Kebajikan** (Master data first)
   - Migration
   - Model
   - Controller
   - Views (index, create, edit, show)
   - Routes
   - Seeder (sample programs)

2. **Penerima Bantuan** (Beneficiary database)
   - Migration
   - Model
   - Controller
   - Views (index, create, edit, show)
   - Routes
   - Seeder (sample beneficiaries)

3. **Permohonan Bantuan** (Applications with workflow)
   - Migration
   - Model
   - Controller (with workflow methods)
   - Views (index, create, edit, show)
   - Routes
   - Seeder (sample applications)

4. **Pembayaran Bantuan** (Payments)
   - Migration
   - Model
   - Controller
   - Views (index, create, edit, show)
   - Routes
   - Seeder (sample payments)

5. **Laporan Kebajikan** (Reports)
   - Controller
   - View (with charts)
   - Routes

6. **Tetapan Kebajikan** (Settings)
   - Migration
   - Model
   - Controller
   - Views (tabs)
   - Routes
   - Seeder (default settings)

---

## UI/UX STANDARDS (MUST FOLLOW)

### Font & Sizing
- Font: Poppins
- Font size: 10px - 14px only
- Border radius: 4px - 8px maximum

### Desktop View
- Table layout with bg-blue-100 header
- Hover effect: hover:bg-white on rows
- Action icons: text-[8px]
  * View: text-gray-600
  * Edit: text-blue-600
  * Copy: text-purple-600
  * Archive: text-orange-600
  * Delete: text-red-600

### Mobile View
- Card layout
- Responsive design
- Touch-friendly buttons

### Components to Use
- `x-statistics-grid` for stats cards
- `x-delete-modal` for delete confirmation with security code
- Follow exact pattern from Kariah/AJK/Permohonan Zakat views

### Filter Layout
- All filters in 1 row using flexbox
- Responsive on mobile (stack vertically)

---

## VALIDATION RULES

### Program Kebajikan
- kod_program: required, unique, max:50
- nama_program: required, max:255
- kategori_program: required, in:enum values
- jenis_bantuan: required, in:enum values
- had_maksimum: nullable, numeric, min:0
- had_minimum: nullable, numeric, min:0
- tempoh_bantuan: required, in:enum values
- status_program: required, in:enum values

### Penerima Bantuan
- nama_penuh: required, max:255
- no_kp: required, size:12, unique, regex:/^\d{12}$/
- jantina: required, in:Lelaki,Perempuan
- tarikh_lahir: required, date, before:today
- status_perkahwinan: required, in:enum values
- no_telefon: required, regex:/^01[0-9]-[0-9]{7,8}$/
- alamat_1: required, max:255
- poskod: required, size:5, regex:/^\d{5}$/
- bandar: required, max:100
- negeri: required, max:100
- status_pekerjaan: required, in:enum values
- jenis_kediaman: required, in:enum values
- status_penerima: required, in:enum values
- Files: max:5MB, mimes:pdf,jpg,jpeg,png

### Permohonan Bantuan
- penerima_bantuan_id: required, exists:penerima_bantuan,id
- program_kebajikan_id: required, exists:program_kebajikan,id
- tarikh_permohonan: required, date
- jenis_bantuan: required, in:enum values
- jumlah_dipohon: required_if:jenis_bantuan,Tunai,Campuran, numeric, min:0
- tujuan_permohonan: required
- keutamaan: required, in:enum values
- status_permohonan: required, in:enum values
- Files: max:5MB, mimes:pdf,jpg,jpeg,png

### Pembayaran Bantuan
- permohonan_bantuan_id: required, exists:permohonan_bantuan,id
- tarikh_pembayaran: required, date
- jumlah_bayaran: required, numeric, min:0
- kaedah_bayaran: required, in:enum values
- nama_bank: required_if:kaedah_bayaran,Bank Transfer,Cek
- no_akaun: required_if:kaedah_bayaran,Bank Transfer
- no_rujukan: required_if:kaedah_bayaran,Bank Transfer
- no_cek: required_if:kaedah_bayaran,Cek
- tarikh_cek: required_if:kaedah_bayaran,Cek
- senarai_barangan: required_if:kaedah_bayaran,Barangan
- nilai_barangan: required_if:kaedah_bayaran,Barangan, numeric, min:0
- status_pembayaran: required, in:enum values
- Files: max:5MB, mimes:pdf,jpg,jpeg,png

---

## FILE UPLOAD HANDLING

### Storage Structure
```
storage/app/public/kebajikan/
├── program/
├── penerima/
│   ├── profil/
│   ├── ic/
│   ├── sijil/
│   └── dokumen/
├── permohonan/
│   ├── surat/
│   ├── bukti/
│   └── lawatan/
└── pembayaran/
    ├── resit/
    ├── bukti/
    └── penyerahan/
```

### Upload Rules
- Max file size: 5MB
- Allowed types: PDF, JPG, JPEG, PNG
- Multiple files: Store as JSON array of paths
- Single file: Store as string path
- Delete old files when updating

---

## TESTING CHECKLIST

### Multi-Masjid Isolation
- [ ] Super Admin can see all data
- [ ] Super Admin can filter by masjid
- [ ] Admin Masjid only sees their masjid data
- [ ] Auto-assign masjid_id on create
- [ ] Cannot view/edit other masjid's data

### CRUD Operations
- [ ] Create with validation
- [ ] Read with filters
- [ ] Update with validation
- [ ] Soft delete
- [ ] Archive (if applicable)

### File Uploads
- [ ] Upload single file
- [ ] Upload multiple files
- [ ] View/download files
- [ ] Delete files on update
- [ ] File size validation
- [ ] File type validation

### Workflow (Permohonan Bantuan)
- [ ] Baharu → Dalam Semakan
- [ ] Dalam Semakan → Lawatan Rumah
- [ ] Lawatan Rumah → Lulus/Ditolak
- [ ] Batal at any stage
- [ ] Workflow timeline display
- [ ] Notification on status change

### Reports
- [ ] Filter by date range
- [ ] Filter by program/status
- [ ] Stats calculation correct
- [ ] Charts display correctly
- [ ] Export PDF
- [ ] Export Excel

---

## NOTES

- Follow EXACT pattern from Asnaf/AJK/Permohonan Zakat modules
- Do NOT create custom designs
- Test ALL functionality before claiming done
- Use `php artisan tinker --execute="..."` for testing
- Check diagnostics after code changes
- Remove debug code after testing
- Deep check for clean code

---

END OF SPECIFICATION
