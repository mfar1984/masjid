# ASET MODULE - PHASE 1 COMPLETE DESIGN SPECIFICATION

## MODULE OVERVIEW
Modul Aset Phase 1 menguruskan kategori aset, senarai aset, dan pergerakan aset masjid. Ketiga-tiga modul ini MESTI dibuat serentak kerana saling bergantung dan akan diintegrasikan dengan Fasiliti & Tempahan (Phase 2) dan Kewangan Module.

## IMPLEMENTATION ORDER (MUST DO TOGETHER)
1. **Kategori Aset** (Master data - MUST DO FIRST)
2. **Senarai Aset** (Main entity - depends on Kategori)
3. **Pergerakan Aset** (Track movement - depends on Senarai Aset)

## NAVIGATION STRUCTURE
```
Aset (Main Menu)
├── Pengurusan Aset
│   ├── Senarai Aset (Main asset list)
│   ├── Kategori Aset (Master data for categories)
│   ├── Pemindahan Aset (Transfer between locations - Future)
│   └── Pergerakan Aset (Movement tracking - Phase 1)
├── Penyelenggaraan (Future - Phase 3)
├── Penyusutan & Nilai (Future - Phase 4)
├── Pelupusan Aset (Future - Phase 5)
└── Laporan Aset (Future - Phase 6)
```

## MULTI-MASJID ISOLATION
- **Super Admin**: Can view all data, filter by masjid
- **Admin Masjid**: Only see their own masjid data, auto-assigned masjid_id
- All models use `HasMasjidScope` trait
- All controllers check user role and filter by masjid_id
- Follow exact pattern from Asnaf/Kebajikan/Kewangan modules

## PERMISSIONS
```php
'aset' => [
    'create' => 'Cipta Aset',
    'read' => 'Lihat Aset',
    'update' => 'Kemaskini Aset',
    'delete' => 'Padam Aset',
    'archive' => 'Arkib Aset',
]
```

---

## 1. KATEGORI ASET (MASTER DATA)

### Purpose
Master data untuk kategori aset yang akan digunakan dalam Senarai Aset. Setiap masjid boleh customize kategori mengikut keperluan.

### Database Schema
**Table**: `kategori_aset`

```sql
CREATE TABLE kategori_aset (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    kod_kategori VARCHAR(50) NOT NULL,
    nama_kategori VARCHAR(255) NOT NULL,
    jenis_kategori ENUM('Tanah & Bangunan', 'Kenderaan', 'Peralatan', 'Perabot', 'Elektronik', 'Lain-lain') NOT NULL,
    keterangan TEXT,
    urutan INT DEFAULT 0,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    UNIQUE KEY unique_masjid_kod (masjid_id, kod_kategori),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Default Kategori Aset (Seeder)
Setiap masjid akan dapat default categories:

**Tanah & Bangunan:**
- TB-001: Tanah Masjid
- TB-002: Bangunan Masjid
- TB-003: Surau
- TB-004: Pejabat
- TB-005: Rumah Imam
- TB-006: Dewan Serbaguna

**Kenderaan:**
- KD-001: Kereta
- KD-002: Van
- KD-003: Bas
- KD-004: Motosikal

**Peralatan:**
- PR-001: Sistem PA
- PR-002: Projector
- PR-003: Air Conditioner
- PR-004: Kipas
- PR-005: Khemah
- PR-006: Kerusi Lipat
- PR-007: Meja Lipat

**Perabot:**
- PB-001: Kabinet
- PB-002: Meja
- PB-003: Kerusi
- PB-004: Rak Buku
- PB-005: Almari

**Elektronik:**
- EL-001: Komputer
- EL-002: Printer
- EL-003: Scanner
- EL-004: Kamera
- EL-005: TV/Monitor

**Lain-lain:**
- LL-001: Lain-lain

### Form Fields (Create/Edit)
1. **Maklumat Kategori**
   - `kod_kategori` - Text (required, unique per masjid, uppercase, e.g., TB-001)
   - `nama_kategori` - Text (required, max 255)
   - `jenis_kategori` - Dropdown (required)
     * Tanah & Bangunan
     * Kenderaan
     * Peralatan
     * Perabot
     * Elektronik
     * Lain-lain
   - `keterangan` - Textarea (optional)
   - `urutan` - Number (default: 0, for sorting)
   - `status` - Dropdown (required, default: Aktif)
     * Aktif
     * Tidak Aktif

### Index Page
- **Stats Cards** (using x-statistics-grid):
  * Total Kategori
  * Kategori Aktif
  * Kategori Tidak Aktif
  * Total Aset (from senarai_aset)

- **Filter** (1 row flexbox):
  * Jenis Kategori (dropdown)
  * Status (dropdown)
  * Cari (search: kod_kategori, nama_kategori)
  * Reset button

- **Table Columns** (Desktop):
  * Kod Kategori
  * Nama Kategori
  * Jenis Kategori
  * Jumlah Aset (count from senarai_aset)
  * Status
  * Tindakan (View, Edit, Delete)

- **Card View** (Mobile):
  * Kod Kategori (bold)
  * Nama Kategori
  * Jenis Kategori | Jumlah Aset
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Kategori
2. Senarai Aset (list of assets in this category)
3. Maklumat Audit

---

## 2. SENARAI ASET (MAIN ENTITY)

### Purpose
Rekod lengkap semua aset masjid dengan maklumat terperinci termasuk lokasi, nilai, status, dan dokumen sokongan.

### Database Schema
**Table**: `senarai_aset`

```sql
CREATE TABLE senarai_aset (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_aset VARCHAR(50) UNIQUE NOT NULL,
    
    -- Kategori & Maklumat Asas
    kategori_aset_id BIGINT UNSIGNED NOT NULL,
    nama_aset VARCHAR(255) NOT NULL,
    kod_aset VARCHAR(50),
    jenis_aset VARCHAR(255),
    
    -- Maklumat Pembelian
    tarikh_perolehan DATE NOT NULL,
    cara_perolehan ENUM('Pembelian', 'Derma', 'Hibah', 'Wakaf', 'Pinjaman', 'Lain-lain') NOT NULL,
    pembekal VARCHAR(255),
    no_invois VARCHAR(100),
    harga_perolehan DECIMAL(12,2) NOT NULL,
    
    -- Maklumat Teknikal
    jenama VARCHAR(255),
    model VARCHAR(255),
    no_siri VARCHAR(255),
    warna VARCHAR(100),
    saiz VARCHAR(100),
    spesifikasi TEXT,
    
    -- Lokasi Semasa
    lokasi_semasa VARCHAR(255) NOT NULL,
    lokasi_terperinci TEXT,
    
    -- Warranty & Insurance
    tempoh_jaminan INT,
    tarikh_tamat_jaminan DATE,
    no_polisi_insurans VARCHAR(100),
    syarikat_insurans VARCHAR(255),
    tarikh_tamat_insurans DATE,
    
    -- Status & Kondisi
    status_aset ENUM('Aktif', 'Dalam Penyelenggaraan', 'Rosak', 'Dilupuskan', 'Hilang', 'Dipinjam', 'Disewa') DEFAULT 'Aktif',
    kondisi_aset ENUM('Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak') DEFAULT 'Baik',
    
    -- Dokumen (JSON array of file paths)
    gambar_aset TEXT,
    invois_path TEXT,
    warranty_card_path TEXT,
    manual_path TEXT,
    insurans_path TEXT,
    dokumen_lain TEXT,
    
    -- Catatan
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_aset_id) REFERENCES kategori_aset(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Asas**
- `no_aset` - Text (auto-generate: AST-YYYY-0001, readonly on edit)
- `kategori_aset_id` - Dropdown (required, filter by status=Aktif)
- `nama_aset` - Text (required, max 255)
- `kod_aset` - Text (optional, custom code by user)
- `jenis_aset` - Text (optional, e.g., "Kerusi Plastik", "Meja Kayu")

**Section 2: Maklumat Pembelian**
- `tarikh_perolehan` - Date (required)
- `cara_perolehan` - Dropdown (required)
  * Pembelian
  * Derma
  * Hibah
  * Wakaf
  * Pinjaman
  * Lain-lain
- `pembekal` - Text (optional, nama pembekal/penderma)
- `no_invois` - Text (optional)
- `harga_perolehan` - Number (decimal 12,2, required)

**Section 3: Maklumat Teknikal**
- `jenama` - Text (optional)
- `model` - Text (optional)
- `no_siri` - Text (optional, unique identifier)
- `warna` - Text (optional)
- `saiz` - Text (optional, e.g., "120cm x 60cm")
- `spesifikasi` - Textarea (optional, technical details)

**Section 4: Lokasi**
- `lokasi_semasa` - Text (required, e.g., "Dewan Utama", "Pejabat", "Stor")
- `lokasi_terperinci` - Textarea (optional, detailed location description)

**Section 5: Warranty & Insurance**
- `tempoh_jaminan` - Number (optional, in months)
- `tarikh_tamat_jaminan` - Date (optional, auto-calculate from tarikh_perolehan + tempoh_jaminan)
- `no_polisi_insurans` - Text (optional)
- `syarikat_insurans` - Text (optional)
- `tarikh_tamat_insurans` - Date (optional)

**Section 6: Status & Kondisi**
- `status_aset` - Dropdown (required, default: Aktif)
  * Aktif
  * Dalam Penyelenggaraan
  * Rosak
  * Dilupuskan
  * Hilang
  * Dipinjam
  * Disewa
- `kondisi_aset` - Dropdown (required, default: Baik)
  * Baru
  * Baik
  * Sederhana
  * Teruk
  * Rosak

**Section 7: Muat Naik Dokumen**
All file uploads: Max 5MB, PDF/JPG/PNG only

- `gambar_aset` - File (multiple, max 5 images)
- `invois_path` - File (single, PDF/JPG)
- `warranty_card_path` - File (single, PDF/JPG)
- `manual_path` - File (single, PDF)
- `insurans_path` - File (single, PDF)
- `dokumen_lain` - File (multiple, max 5 files)

**Section 8: Catatan**
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Aset
  * Aset Aktif
  * Aset Rosak
  * Nilai Total Aset (RM)

- **Filter** (1 row):
  * Kategori Aset (dropdown)
  * Status Aset (dropdown)
  * Kondisi Aset (dropdown)
  * Lokasi (dropdown - dynamic from existing locations)
  * Cari (no_aset, nama_aset, kod_aset, no_siri)
  * Reset

- **Table Columns** (Desktop):
  * No. Aset
  * Nama Aset
  * Kategori
  * Lokasi
  * Harga Perolehan
  * Status
  * Kondisi
  * Tindakan

- **Card View** (Mobile):
  * No. Aset (bold)
  * Nama Aset
  * Kategori | Lokasi
  * Harga Perolehan
  * Status badge | Kondisi badge
  * Action icons

### Show Page Sections
1. Maklumat Asas
2. Maklumat Pembelian
3. Maklumat Teknikal
4. Lokasi Semasa
5. Warranty & Insurance
6. Status & Kondisi
7. Dokumen (with download/view links)
8. Sejarah Pergerakan (list from pergerakan_aset)
9. Catatan
10. Maklumat Audit

---

## 3. PERGERAKAN ASET (MOVEMENT TRACKING)

### Purpose
Merekod pergerakan aset dari satu lokasi ke lokasi lain, termasuk pergerakan ke luar kawasan masjid dengan alamat penuh. Ini penting untuk Fasiliti & Tempahan (Phase 2) dimana aset boleh disewa dan dibawa keluar.

### Database Schema
**Table**: `pergerakan_aset`

```sql
CREATE TABLE pergerakan_aset (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    masjid_id BIGINT UNSIGNED NOT NULL,
    no_pergerakan VARCHAR(50) UNIQUE NOT NULL,
    
    -- Relations
    senarai_aset_id BIGINT UNSIGNED NOT NULL,
    
    -- Maklumat Pergerakan
    tarikh_pergerakan DATETIME NOT NULL,
    jenis_pergerakan ENUM('Pemindahan Dalaman', 'Pemindahan Luaran', 'Pinjaman', 'Sewa', 'Penyelenggaraan', 'Pulangan') NOT NULL,
    
    -- Lokasi Asal
    lokasi_asal VARCHAR(255) NOT NULL,
    
    -- Lokasi Destinasi (Dalaman)
    lokasi_destinasi VARCHAR(255),
    
    -- Lokasi Destinasi (Luaran) - FULL ADDRESS REQUIRED
    is_lokasi_luaran BOOLEAN DEFAULT FALSE,
    nama_tempat_luaran VARCHAR(255),
    alamat_luaran_1 VARCHAR(255),
    alamat_luaran_2 VARCHAR(255),
    poskod_luaran VARCHAR(10),
    bandar_luaran VARCHAR(100),
    negeri_luaran VARCHAR(100),
    
    -- Maklumat Peminjam/Penyewa (if applicable)
    nama_peminjam VARCHAR(255),
    no_ic_peminjam VARCHAR(12),
    no_telefon_peminjam VARCHAR(20),
    organisasi_peminjam VARCHAR(255),
    
    -- Tempoh & Pulangan
    tarikh_jangka_pulangan DATE,
    tarikh_sebenar_pulangan DATETIME,
    status_pulangan ENUM('Belum Pulang', 'Sudah Pulang', 'Lewat', 'Hilang', 'Rosak') DEFAULT 'Belum Pulang',
    
    -- Kondisi
    kondisi_sebelum ENUM('Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak') NOT NULL,
    kondisi_selepas ENUM('Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak'),
    
    -- Dokumen
    surat_kebenaran_path TEXT,
    gambar_sebelum TEXT,
    gambar_selepas TEXT,
    borang_pinjaman_path TEXT,
    
    -- Approval (for external movement)
    require_approval BOOLEAN DEFAULT FALSE,
    diluluskan_oleh BIGINT UNSIGNED,
    tarikh_diluluskan DATETIME,
    catatan_kelulusan TEXT,
    
    -- Catatan
    sebab_pergerakan TEXT,
    catatan TEXT,
    
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    deleted_by BIGINT UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (masjid_id) REFERENCES masjids(id) ON DELETE CASCADE,
    FOREIGN KEY (senarai_aset_id) REFERENCES senarai_aset(id) ON DELETE CASCADE,
    FOREIGN KEY (diluluskan_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (deleted_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Form Fields (Create/Edit)

**Section 1: Maklumat Pergerakan**
- `no_pergerakan` - Text (auto-generate: PG-YYYY-0001, readonly)
- `senarai_aset_id` - Dropdown/Search (required, show: no_aset - nama_aset)
  * On select, auto-populate: lokasi_asal (from lokasi_semasa), kondisi_sebelum (from kondisi_aset)
- `tarikh_pergerakan` - DateTime (required, default: now)
- `jenis_pergerakan` - Dropdown (required)
  * Pemindahan Dalaman
  * Pemindahan Luaran
  * Pinjaman
  * Sewa
  * Penyelenggaraan
  * Pulangan

**Section 2: Lokasi**
- `lokasi_asal` - Text (readonly, auto-filled from asset)
- `is_lokasi_luaran` - Radio (required): Dalaman / Luaran

**If Dalaman:**
- `lokasi_destinasi` - Text (required, e.g., "Dewan Utama", "Stor")

**If Luaran (FULL ADDRESS REQUIRED):**
- `nama_tempat_luaran` - Text (required, e.g., "Dewan Orang Ramai Kg. Baru")
- `alamat_luaran_1` - Text (required)
- `alamat_luaran_2` - Text (optional)
- `poskod_luaran` - Text (required, 5 digits)
- `bandar_luaran` - Text (required)
- `negeri_luaran` - Dropdown (required, 16 states)

**Section 3: Maklumat Peminjam/Penyewa** (Show if jenis=Pinjaman/Sewa)
- `nama_peminjam` - Text (required)
- `no_ic_peminjam` - Text (required, 12 digits)
- `no_telefon_peminjam` - Text (required)
- `organisasi_peminjam` - Text (optional)

**Section 4: Tempoh & Pulangan**
- `tarikh_jangka_pulangan` - Date (required if jenis=Pinjaman/Sewa)
- `tarikh_sebenar_pulangan` - DateTime (optional, fill on return)
- `status_pulangan` - Dropdown (default: Belum Pulang)
  * Belum Pulang
  * Sudah Pulang
  * Lewat
  * Hilang
  * Rosak

**Section 5: Kondisi**
- `kondisi_sebelum` - Dropdown (readonly, auto-filled from asset)
  * Baru
  * Baik
  * Sederhana
  * Teruk
  * Rosak
- `kondisi_selepas` - Dropdown (optional, fill on return)
  * Baru
  * Baik
  * Sederhana
  * Teruk
  * Rosak

**Section 6: Dokumen**
All files: Max 5MB, PDF/JPG/PNG

- `surat_kebenaran_path` - File (single, required if is_lokasi_luaran=true)
- `gambar_sebelum` - File (multiple, max 5 images)
- `gambar_selepas` - File (multiple, max 5 images, fill on return)
- `borang_pinjaman_path` - File (single, required if jenis=Pinjaman/Sewa)

**Section 7: Approval** (Show if is_lokasi_luaran=true)
- `require_approval` - Checkbox (default: true if luaran)
- `diluluskan_oleh` - Dropdown (users with permission)
- `tarikh_diluluskan` - DateTime (auto-fill on approval)
- `catatan_kelulusan` - Textarea (optional)

**Section 8: Catatan**
- `sebab_pergerakan` - Textarea (required)
- `catatan` - Textarea (optional)

### Index Page
- **Stats Cards**:
  * Total Pergerakan
  * Pergerakan Aktif (Belum Pulang)
  * Pergerakan Luaran
  * Aset Lewat Pulang

- **Filter** (1 row):
  * Aset (dropdown)
  * Jenis Pergerakan (dropdown)
  * Status Pulangan (dropdown)
  * Lokasi (dropdown)
  * Tarikh Dari (date)
  * Tarikh Hingga (date)
  * Cari (no_pergerakan, nama_peminjam)
  * Reset

- **Table Columns** (Desktop):
  * No. Pergerakan
  * Tarikh
  * Nama Aset
  * Jenis Pergerakan
  * Lokasi Asal → Destinasi
  * Jangka Pulangan
  * Status Pulangan
  * Tindakan

- **Card View** (Mobile):
  * No. Pergerakan (bold)
  * Tarikh | Jenis
  * Nama Aset
  * Lokasi Asal → Destinasi
  * Jangka Pulangan
  * Status badge
  * Action icons

### Show Page Sections
1. Maklumat Pergerakan
2. Maklumat Aset (from senarai_aset)
3. Lokasi Asal & Destinasi
4. Alamat Penuh (if luaran)
5. Maklumat Peminjam/Penyewa (if applicable)
6. Tempoh & Pulangan
7. Kondisi (Before & After)
8. Dokumen (with download/view)
9. Approval (if applicable)
10. Catatan
11. Maklumat Audit

### Workflow Actions (Buttons on Show Page)
- **Lulus** (if require_approval=true, status=pending, permission: update)
- **Tandakan Pulang** (if status_pulangan=Belum Pulang, permission: update)
  * Update: tarikh_sebenar_pulangan, kondisi_selepas, status_pulangan
  * Update senarai_aset: lokasi_semasa, kondisi_aset
- **Tandakan Lewat** (if tarikh_jangka_pulangan < today, permission: update)
- **Tandakan Hilang** (if status_pulangan=Belum Pulang, permission: update)
  * Update senarai_aset: status_aset = 'Hilang'

---

## INTEGRATION WITH FUTURE MODULES

### Integration 1: Fasiliti & Tempahan (Phase 2) → Pergerakan Aset
```
When: Tempahan approved & aset assigned
Action: Auto-create pergerakan_aset record

Flow:
Tempahan::create([
    'jenis_tempahan' => 'Sewa Aset',
    'aset_id' => $aset->id,
    'tarikh_mula' => '2025-12-20',
    'tarikh_tamat' => '2025-12-22',
])
    ↓
Event: TempahanApproved
    ↓
Listener: CreatePergerakanAset
    ↓
PergerakanAset::create([
    'senarai_aset_id' => $tempahan->aset_id,
    'jenis_pergerakan' => 'Sewa',
    'is_lokasi_luaran' => true,
    'nama_peminjam' => $tempahan->nama_penyewa,
    'tarikh_jangka_pulangan' => $tempahan->tarikh_tamat,
    // ... alamat from tempahan
])
```

### Integration 2: Fasiliti & Tempahan → Kewangan (Kutipan Dana)
```
When: Pembayaran sewa completed
Action: Auto-create kutipan_dana record

Flow:
PembayaranSewa::create([
    'tempahan_id' => $tempahan->id,
    'jumlah_bayaran' => $tempahan->harga_sewa,
    'status' => 'Sudah Bayar',
])
    ↓
Event: PembayaranSewaCompleted
    ↓
Listener: CreateKutipanDana
    ↓
KutipanDana::create([
    'jenis_kutipan' => 'Sewa Fasiliti & Aset',
    'kategori_kewangan_id' => 'Sewa Fasiliti',
    'jumlah' => $pembayaran->jumlah_bayaran,
    'rujukan_id' => $pembayaran->id,
    'rujukan_type' => 'PembayaranSewa',
])
    ↓
TransaksiKewangan::create([
    'jenis_transaksi' => 'Pendapatan',
    'kategori' => 'Sewa Fasiliti & Aset',
    'jumlah' => $pembayaran->jumlah_bayaran,
])
```

---

## ROUTES

```php
// Kategori Aset
Route::resource('kategori-aset', KategoriAsetController::class)
    ->middleware(['auth', 'verified', 'permission:aset,read']);

// Senarai Aset
Route::resource('senarai-aset', SenariAsetController::class)
    ->middleware(['auth', 'verified', 'permission:aset,read']);

// Pergerakan Aset
Route::resource('pergerakan-aset', PergerakanAsetController::class)
    ->middleware(['auth', 'verified', 'permission:aset,read']);

// Pergerakan Aset - Workflow Actions
Route::post('pergerakan-aset/{id}/lulus', [PergerakanAsetController::class, 'lulus'])
    ->name('pergerakan-aset.lulus')
    ->middleware(['auth', 'verified', 'permission:aset,update']);

Route::post('pergerakan-aset/{id}/pulang', [PergerakanAsetController::class, 'pulang'])
    ->name('pergerakan-aset.pulang')
    ->middleware(['auth', 'verified', 'permission:aset,update']);

Route::post('pergerakan-aset/{id}/lewat', [PergerakanAsetController::class, 'lewat'])
    ->name('pergerakan-aset.lewat')
    ->middleware(['auth', 'verified', 'permission:aset,update']);

Route::post('pergerakan-aset/{id}/hilang', [PergerakanAsetController::class, 'hilang'])
    ->name('pergerakan-aset.hilang')
    ->middleware(['auth', 'verified', 'permission:aset,update']);
```

---

## MODELS

### KategoriAset.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class KategoriAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'kategori_aset';

    protected $fillable = [
        'masjid_id',
        'kod_kategori',
        'nama_kategori',
        'jenis_kategori',
        'keterangan',
        'urutan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariAset()
    {
        return $this->hasMany(SenariAset::class);
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
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_kategori', $jenis);
    }
}
```

### SenariAset.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class SenariAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'senarai_aset';

    protected $fillable = [
        'masjid_id',
        'no_aset',
        'kategori_aset_id',
        'nama_aset',
        'kod_aset',
        'jenis_aset',
        'tarikh_perolehan',
        'cara_perolehan',
        'pembekal',
        'no_invois',
        'harga_perolehan',
        'jenama',
        'model',
        'no_siri',
        'warna',
        'saiz',
        'spesifikasi',
        'lokasi_semasa',
        'lokasi_terperinci',
        'tempoh_jaminan',
        'tarikh_tamat_jaminan',
        'no_polisi_insurans',
        'syarikat_insurans',
        'tarikh_tamat_insurans',
        'status_aset',
        'kondisi_aset',
        'gambar_aset',
        'invois_path',
        'warranty_card_path',
        'manual_path',
        'insurans_path',
        'dokumen_lain',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_perolehan' => 'date',
        'tarikh_tamat_jaminan' => 'date',
        'tarikh_tamat_insurans' => 'date',
        'harga_perolehan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function kategoriAset()
    {
        return $this->belongsTo(KategoriAset::class);
    }

    public function pergerakanAset()
    {
        return $this->hasMany(PergerakanAset::class);
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
    public function scopeAktif($query)
    {
        return $query->where('status_aset', 'Aktif');
    }

    public function scopeRosak($query)
    {
        return $query->where('status_aset', 'Rosak');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status_aset', 'Dipinjam');
    }

    public function scopeDisewa($query)
    {
        return $query->where('status_aset', 'Disewa');
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_aset_id', $kategoriId);
    }

    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi_semasa', $lokasi);
    }

    // Accessors
    public function getUmurAsetAttribute()
    {
        if (!$this->tarikh_perolehan) {
            return null;
        }
        
        return $this->tarikh_perolehan->diffInYears(now());
    }

    public function getIsWarrantyValidAttribute()
    {
        if (!$this->tarikh_tamat_jaminan) {
            return false;
        }
        
        return $this->tarikh_tamat_jaminan->isFuture();
    }

    public function getIsInsuranceValidAttribute()
    {
        if (!$this->tarikh_tamat_insurans) {
            return false;
        }
        
        return $this->tarikh_tamat_insurans->isFuture();
    }
}
```

### PergerakanAset.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class PergerakanAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'pergerakan_aset';

    protected $fillable = [
        'masjid_id',
        'no_pergerakan',
        'senarai_aset_id',
        'tarikh_pergerakan',
        'jenis_pergerakan',
        'lokasi_asal',
        'lokasi_destinasi',
        'is_lokasi_luaran',
        'nama_tempat_luaran',
        'alamat_luaran_1',
        'alamat_luaran_2',
        'poskod_luaran',
        'bandar_luaran',
        'negeri_luaran',
        'nama_peminjam',
        'no_ic_peminjam',
        'no_telefon_peminjam',
        'organisasi_peminjam',
        'tarikh_jangka_pulangan',
        'tarikh_sebenar_pulangan',
        'status_pulangan',
        'kondisi_sebelum',
        'kondisi_selepas',
        'surat_kebenaran_path',
        'gambar_sebelum',
        'gambar_selepas',
        'borang_pinjaman_path',
        'require_approval',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'sebab_pergerakan',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_pergerakan' => 'datetime',
        'tarikh_jangka_pulangan' => 'date',
        'tarikh_sebenar_pulangan' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'is_lokasi_luaran' => 'boolean',
        'require_approval' => 'boolean',
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
        return $this->belongsTo(SenariAset::class);
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
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
    public function scopeBelumPulang($query)
    {
        return $query->where('status_pulangan', 'Belum Pulang');
    }

    public function scopeSudahPulang($query)
    {
        return $query->where('status_pulangan', 'Sudah Pulang');
    }

    public function scopeLewat($query)
    {
        return $query->where('status_pulangan', 'Lewat');
    }

    public function scopeLuaran($query)
    {
        return $query->where('is_lokasi_luaran', true);
    }

    public function scopeDalaman($query)
    {
        return $query->where('is_lokasi_luaran', false);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pergerakan', $jenis);
    }

    // Accessors
    public function getIsLewatAttribute()
    {
        if (!$this->tarikh_jangka_pulangan || $this->status_pulangan !== 'Belum Pulang') {
            return false;
        }
        
        return $this->tarikh_jangka_pulangan->isPast();
    }

    public function getAlamatPenuhLuaranAttribute()
    {
        if (!$this->is_lokasi_luaran) {
            return null;
        }
        
        $parts = array_filter([
            $this->nama_tempat_luaran,
            $this->alamat_luaran_1,
            $this->alamat_luaran_2,
            $this->poskod_luaran . ' ' . $this->bandar_luaran,
            $this->negeri_luaran,
        ]);
        
        return implode(', ', $parts);
    }
}
```

---

## CONTROLLERS

### KategoriAsetController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriAsetController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = $user->isSuperAdmin() ? $request->masjid_id : $user->masjid_id;

        $query = KategoriAset::with(['masjid', 'createdBy'])
            ->when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))
            ->when($request->jenis_kategori, fn($q) => $q->where('jenis_kategori', $request->jenis_kategori))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function($query) use ($request) {
                $query->where('kod_kategori', 'like', "%{$request->search}%")
                      ->orWhere('nama_kategori', 'like', "%{$request->search}%");
            }))
            ->orderBy('urutan')
            ->orderBy('nama_kategori');

        $kategoriAset = $query->paginate(25);

        // Stats
        $stats = [
            'total' => KategoriAset::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->count(),
            'aktif' => KategoriAset::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status', 'Aktif')->count(),
            'tidak_aktif' => KategoriAset::when($masjidId, fn($q) => $q->where('masjid_id', $masjidId))->where('status', 'Tidak Aktif')->count(),
            'total_aset' => 0, // Will be calculated from senarai_aset
        ];

        return view('kategori-aset.index', compact('kategoriAset', 'stats'));
    }

    public function create()
    {
        return view('kategori-aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kod_kategori' => 'required|string|max:50',
            'nama_kategori' => 'required|string|max:255',
            'jenis_kategori' => 'required|in:Tanah & Bangunan,Kenderaan,Peralatan,Perabot,Elektronik,Lain-lain',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['masjid_id'] = Auth::user()->masjid_id;
        $validated['created_by'] = Auth::id();
        $validated['kod_kategori'] = strtoupper($validated['kod_kategori']);

        KategoriAset::create($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya ditambah.');
    }

    public function show(KategoriAset $kategoriAset)
    {
        $kategoriAset->load(['masjid', 'senariAset', 'createdBy', 'updatedBy']);
        
        return view('kategori-aset.show', compact('kategoriAset'));
    }

    public function edit(KategoriAset $kategoriAset)
    {
        return view('kategori-aset.edit', compact('kategoriAset'));
    }

    public function update(Request $request, KategoriAset $kategoriAset)
    {
        $validated = $request->validate([
            'kod_kategori' => 'required|string|max:50',
            'nama_kategori' => 'required|string|max:255',
            'jenis_kategori' => 'required|in:Tanah & Bangunan,Kenderaan,Peralatan,Perabot,Elektronik,Lain-lain',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['kod_kategori'] = strtoupper($validated['kod_kategori']);

        $kategoriAset->update($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya dikemaskini.');
    }

    public function destroy(KategoriAset $kategoriAset)
    {
        // Check if kategori has assets
        if ($kategoriAset->senariAset()->count() > 0) {
            return back()->with('error', 'Kategori tidak boleh dipadam kerana masih mempunyai aset.');
        }

        $kategoriAset->delete();

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berjaya dipadam.');
    }
}
```

---

## SEEDERS

### KategoriAsetSeeder.php
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriAsetSeeder extends Seeder
{
    public function run(): void
    {
        $masjids = DB::table('masjids')->pluck('id');
        
        $defaultData = [
            // Tanah & Bangunan
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-001', 'nama_kategori' => 'Tanah Masjid', 'urutan' => 1],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-002', 'nama_kategori' => 'Bangunan Masjid', 'urutan' => 2],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-003', 'nama_kategori' => 'Surau', 'urutan' => 3],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-004', 'nama_kategori' => 'Pejabat', 'urutan' => 4],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-005', 'nama_kategori' => 'Rumah Imam', 'urutan' => 5],
            ['jenis_kategori' => 'Tanah & Bangunan', 'kod_kategori' => 'TB-006', 'nama_kategori' => 'Dewan Serbaguna', 'urutan' => 6],
            
            // Kenderaan
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-001', 'nama_kategori' => 'Kereta', 'urutan' => 1],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-002', 'nama_kategori' => 'Van', 'urutan' => 2],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-003', 'nama_kategori' => 'Bas', 'urutan' => 3],
            ['jenis_kategori' => 'Kenderaan', 'kod_kategori' => 'KD-004', 'nama_kategori' => 'Motosikal', 'urutan' => 4],
            
            // Peralatan
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-001', 'nama_kategori' => 'Sistem PA', 'urutan' => 1],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-002', 'nama_kategori' => 'Projector', 'urutan' => 2],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-003', 'nama_kategori' => 'Air Conditioner', 'urutan' => 3],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-004', 'nama_kategori' => 'Kipas', 'urutan' => 4],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-005', 'nama_kategori' => 'Khemah', 'urutan' => 5],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-006', 'nama_kategori' => 'Kerusi Lipat', 'urutan' => 6],
            ['jenis_kategori' => 'Peralatan', 'kod_kategori' => 'PR-007', 'nama_kategori' => 'Meja Lipat', 'urutan' => 7],
            
            // Perabot
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-001', 'nama_kategori' => 'Kabinet', 'urutan' => 1],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-002', 'nama_kategori' => 'Meja', 'urutan' => 2],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-003', 'nama_kategori' => 'Kerusi', 'urutan' => 3],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-004', 'nama_kategori' => 'Rak Buku', 'urutan' => 4],
            ['jenis_kategori' => 'Perabot', 'kod_kategori' => 'PB-005', 'nama_kategori' => 'Almari', 'urutan' => 5],
            
            // Elektronik
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-001', 'nama_kategori' => 'Komputer', 'urutan' => 1],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-002', 'nama_kategori' => 'Printer', 'urutan' => 2],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-003', 'nama_kategori' => 'Scanner', 'urutan' => 3],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-004', 'nama_kategori' => 'Kamera', 'urutan' => 4],
            ['jenis_kategori' => 'Elektronik', 'kod_kategori' => 'EL-005', 'nama_kategori' => 'TV/Monitor', 'urutan' => 5],
            
            // Lain-lain
            ['jenis_kategori' => 'Lain-lain', 'kod_kategori' => 'LL-001', 'nama_kategori' => 'Lain-lain', 'urutan' => 1],
        ];
        
        foreach ($masjids as $masjidId) {
            foreach ($defaultData as $data) {
                DB::table('kategori_aset')->insert([
                    'masjid_id' => $masjidId,
                    'jenis_kategori' => $data['jenis_kategori'],
                    'kod_kategori' => $data['kod_kategori'],
                    'nama_kategori' => $data['nama_kategori'],
                    'urutan' => $data['urutan'],
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
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
- Follow pattern from Asnaf, Kebajikan & Kewangan modules
- Consistent table styling
- Consistent form styling
- Consistent button styling

---

## IMPLEMENTATION PLAN

### Phase 1A: Kategori Aset (2-3 hours)
**What will be done:**
1. Create migration for kategori_aset table
2. Create KategoriAset model with relationships
3. Create KategoriAsetController with CRUD
4. Create seeder for default categories
5. Create views (index, create, edit, show)
6. Add routes
7. Test CRUD operations

**Files to create:**
- `database/migrations/xxxx_create_kategori_aset_table.php`
- `database/seeders/KategoriAsetSeeder.php`
- `app/Models/KategoriAset.php`
- `app/Http/Controllers/KategoriAsetController.php`
- `resources/views/kategori-aset/index.blade.php`
- `resources/views/kategori-aset/create.blade.php`
- `resources/views/kategori-aset/edit.blade.php`
- `resources/views/kategori-aset/show.blade.php`

### Phase 1B: Senarai Aset (3-4 hours)
**What will be done:**
1. Create migration for senarai_aset table
2. Create SenariAset model with relationships
3. Create SenariAsetController with CRUD
4. Create views (index, create, edit, show)
5. Add file upload handling
6. Add routes
7. Test CRUD operations

**Files to create:**
- `database/migrations/xxxx_create_senarai_aset_table.php`
- `app/Models/SenariAset.php`
- `app/Http/Controllers/SenariAsetController.php`
- `resources/views/senarai-aset/index.blade.php`
- `resources/views/senarai-aset/create.blade.php`
- `resources/views/senarai-aset/edit.blade.php`
- `resources/views/senarai-aset/show.blade.php`

### Phase 1C: Pergerakan Aset (3-4 hours)
**What will be done:**
1. Create migration for pergerakan_aset table
2. Create PergerakanAset model with relationships
3. Create PergerakanAsetController with CRUD + workflow actions
4. Create views (index, create, edit, show)
5. Add file upload handling
6. Add workflow actions (lulus, pulang, lewat, hilang)
7. Add routes
8. Test CRUD operations & workflow

**Files to create:**
- `database/migrations/xxxx_create_pergerakan_aset_table.php`
- `app/Models/PergerakanAset.php`
- `app/Http/Controllers/PergerakanAsetController.php`
- `resources/views/pergerakan-aset/index.blade.php`
- `resources/views/pergerakan-aset/create.blade.php`
- `resources/views/pergerakan-aset/edit.blade.php`
- `resources/views/pergerakan-aset/show.blade.php`

### Phase 1D: Integration & Testing (2-3 hours)
**What will be done:**
1. Update navbar menu
2. Test multi-masjid isolation
3. Test all CRUD operations
4. Test file uploads
5. Test workflow actions
6. Test relationships
7. Fix bugs & polish UI
8. Run `npm run build`

**Total: 10-14 hours (1-2 days)**

---

## SUMMARY

### What Will Be Built (Phase 1)

**3 Main Modules:**
1. ✅ **Kategori Aset** - Master data for asset categories
2. ✅ **Senarai Aset** - Complete asset inventory
3. ✅ **Pergerakan Aset** - Asset movement tracking with full address support

**Total Pages: ~12 pages**

**Database Tables: 3 tables**
- kategori_aset
- senarai_aset
- pergerakan_aset

**Controllers: 3 controllers**
- KategoriAsetController
- SenariAsetController
- PergerakanAsetController

**Models: 3 models**
- KategoriAset
- SenariAset
- PergerakanAset

**Key Features:**
- ✅ Multi-masjid data isolation
- ✅ Full address tracking for external movement
- ✅ Workflow approval for external movement
- ✅ Asset condition tracking (before & after)
- ✅ Document upload support
- ✅ Integration ready for Phase 2 (Fasiliti & Tempahan)
- ✅ Integration ready for Kewangan Module

---

## NEXT STEPS

### Ready to Implement Phase 1?

**If YES, proceed with:**
1. Create Kategori Aset (migration, model, controller, views, seeder)
2. Create Senarai Aset (migration, model, controller, views)
3. Create Pergerakan Aset (migration, model, controller, views, workflow)
4. Update navbar menu
5. Test everything
6. Run `npm run build`

**After Phase 1 Complete:**
- Phase 2: Fasiliti & Tempahan (OPERASI module)
  * Will use Senarai Aset for rental
  * Will create Pergerakan Aset automatically
  * Will integrate with Kewangan (Kutipan Dana - Sewa Fasiliti)

---

## CONCLUSION

Modul Aset Phase 1 ini direka khusus untuk:
- ✅ Manage asset inventory dengan lengkap
- ✅ Track asset movement dengan alamat penuh
- ✅ Support external movement (pinjaman/sewa)
- ✅ Ready untuk integration dengan Fasiliti & Tempahan
- ✅ Ready untuk integration dengan Kewangan Module
- ✅ Multi-masjid data isolation
- ✅ Follow exact pattern dari Kebajikan & Kewangan modules

**Status**: Ready for implementation
**Estimated Time**: 10-14 hours (1-2 days)
**Complexity**: Medium
**Priority**: High (Required for Phase 2)

---

**Last Updated**: 14 Dec 2025
**Document Version**: 1.0
**Author**: Kiro AI Assistant
**Pattern Reference**: KEBAJIKAN_MODULE_COMPLETE_DESIGN.md, KEWANGAN_MODULE_DESIGN.md, ASNAF_PHASE2_FINAL_DESIGN.md
