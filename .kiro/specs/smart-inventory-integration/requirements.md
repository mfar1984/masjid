# Requirements Document

## Introduction

Feature ini mengintegrasikan modul **Tempahan Fasiliti** dengan **Pergerakan Aset** untuk mewujudkan sistem inventori pintar. Apabila tempahan dibuat, sistem akan automatik mencipta rekod pergerakan aset dan mengurus ketersediaan (availability) berdasarkan kuantiti dan tarikh. Pemulangan aset akan mengembalikan kuantiti ke inventori.

## Glossary

- **Tempahan Fasiliti**: Sistem tempahan untuk fasiliti/aset masjid (dewan, kerusi, meja, dll)
- **Pergerakan Aset**: Rekod pergerakan keluar/masuk aset dari lokasi asal
- **Kuantiti Tersedia**: Jumlah unit yang boleh ditempah pada tarikh tertentu
- **Status Pulangan**: Status sama ada aset sudah dipulangkan (Belum Pulang, Sudah Pulang, Lewat)
- **Lokasi Destinasi**: Tempat aset dipindahkan/dipinjam

## Requirements

### Requirement 1

**User Story:** As a pengguna sistem, I want tempahan fasiliti automatik mencipta rekod pergerakan aset, so that saya tidak perlu masukkan data dua kali.

#### Acceptance Criteria

1. WHEN tempahan fasiliti diluluskan THEN the system SHALL automatik mencipta rekod pergerakan aset untuk setiap item dalam tempahan
2. WHEN pergerakan aset dicipta dari tempahan THEN the system SHALL mengisi lokasi destinasi dari maklumat penyewa dalam tempahan
3. WHEN pergerakan aset dicipta dari tempahan THEN the system SHALL set status_pulangan kepada "Belum Pulang"
4. WHEN pergerakan aset dicipta dari tempahan THEN the system SHALL menyimpan reference kepada tempahan_fasiliti_id

### Requirement 2

**User Story:** As a pengguna sistem, I want melihat kuantiti tersedia berdasarkan tarikh tempahan, so that saya tahu berapa unit boleh ditempah.

#### Acceptance Criteria

1. WHEN pengguna memilih fasiliti dalam form tempahan THEN the system SHALL memaparkan kuantiti tersedia untuk tarikh yang dipilih
2. WHEN kuantiti tersedia dikira THEN the system SHALL mengambil kira semua tempahan aktif yang bertindih dengan tarikh tersebut
3. WHEN kuantiti tersedia dikira THEN the system SHALL mengambil kira pergerakan aset yang belum dipulangkan
4. WHEN pengguna cuba tempah melebihi kuantiti tersedia THEN the system SHALL menolak tempahan dengan mesej ralat yang jelas

### Requirement 3

**User Story:** As a pengguna sistem, I want menandakan pemulangan aset dari senarai tempahan atau pergerakan aset, so that inventori dikemaskini secara automatik.

#### Acceptance Criteria

1. WHEN pengguna klik butang "Pulangkan" pada tempahan yang diluluskan THEN the system SHALL memaparkan modal untuk merekod pemulangan
2. WHEN pemulangan direkod THEN the system SHALL kemaskini status_pulangan kepada "Sudah Pulang" dalam pergerakan_aset
3. WHEN pemulangan direkod THEN the system SHALL kemaskini tarikh_sebenar_pulangan dengan tarikh semasa
4. WHEN pemulangan direkod THEN the system SHALL mengembalikan kuantiti ke inventori tersedia
5. WHEN pemulangan direkod dari tempahan THEN the system SHALL kemaskini status tempahan kepada "Selesai"

### Requirement 4

**User Story:** As a pengguna sistem, I want form tempahan mempunyai field lokasi destinasi, so that maklumat ini digunakan untuk pergerakan aset.

#### Acceptance Criteria

1. WHEN pengguna buat tempahan THEN the system SHALL memaparkan section "Lokasi Destinasi" dalam form
2. WHEN lokasi destinasi adalah dalaman THEN the system SHALL memaparkan dropdown lokasi dalam masjid
3. WHEN lokasi destinasi adalah luaran THEN the system SHALL memaparkan field alamat lengkap (nama tempat, alamat, poskod, bandar, negeri)
4. WHEN tempahan disimpan THEN the system SHALL menyimpan maklumat lokasi destinasi

### Requirement 5

**User Story:** As a pengguna sistem, I want melihat status pemulangan dalam senarai tempahan, so that saya tahu tempahan mana yang belum dipulangkan.

#### Acceptance Criteria

1. WHEN memaparkan senarai tempahan THEN the system SHALL memaparkan column status pemulangan
2. WHEN tempahan belum dipulangkan THEN the system SHALL memaparkan badge "Belum Pulang" berwarna oren
3. WHEN tempahan sudah dipulangkan THEN the system SHALL memaparkan badge "Sudah Pulang" berwarna hijau
4. WHEN tempahan lewat dipulangkan THEN the system SHALL memaparkan badge "Lewat" berwarna merah
5. WHEN pengguna filter by status pemulangan THEN the system SHALL memaparkan tempahan mengikut status yang dipilih

### Requirement 6

**User Story:** As a pengguna sistem, I want icon pemulangan dalam senarai tempahan dan pergerakan aset, so that saya boleh rekod pemulangan dengan cepat.

#### Acceptance Criteria

1. WHEN tempahan status "Lulus" dan belum dipulangkan THEN the system SHALL memaparkan icon "assignment_return" untuk pemulangan
2. WHEN pengguna klik icon pemulangan THEN the system SHALL memaparkan modal pemulangan dengan field kondisi selepas dan catatan
3. WHEN pergerakan aset status "Belum Pulang" atau "Lewat" THEN the system SHALL memaparkan icon pemulangan dalam senarai
4. WHEN pemulangan berjaya THEN the system SHALL memaparkan mesej kejayaan dan refresh senarai

### Requirement 7

**User Story:** As a pengguna sistem, I want sistem automatik detect tempahan yang lewat dipulangkan, so that saya boleh follow up dengan penyewa.

#### Acceptance Criteria

1. WHEN tarikh_tamat tempahan sudah lepas dan status masih "Belum Pulang" THEN the system SHALL automatik kemaskini status kepada "Lewat"
2. WHEN memaparkan statistik THEN the system SHALL memaparkan jumlah tempahan yang lewat dipulangkan
3. WHEN tempahan lewat THEN the system SHALL highlight rekod tersebut dalam senarai

### Requirement 8

**User Story:** As a pengguna sistem, I want melihat sejarah pergerakan aset yang berkaitan dengan tempahan, so that saya boleh track semua aktiviti.

#### Acceptance Criteria

1. WHEN melihat butiran tempahan THEN the system SHALL memaparkan senarai pergerakan aset yang berkaitan
2. WHEN melihat butiran pergerakan aset THEN the system SHALL memaparkan link kepada tempahan asal (jika ada)
3. WHEN pergerakan aset dicipta dari tempahan THEN the system SHALL menyimpan jenis_pergerakan sebagai "Pinjaman" atau "Sewa"
