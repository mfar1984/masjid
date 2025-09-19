# DETAIL STRUCTURE AND FUNCTION - SISTEM E-MASJID

## 📋 KANDUNGAN
1. [Overview Sistem](#overview-sistem)
2. [Pentadbiran Sistem](#pentadbiran-sistem)
3. [Pengurusan](#pengurusan)
4. [Operasi](#operasi)
5. [Kewangan](#kewangan)
6. [Laporan](#laporan)
7. [Integrasi](#integrasi)
8. [Tetapan](#tetapan)
9. [Bantuan](#bantuan)
10. [Relationship Matrix](#relationship-matrix)

---

## 🎯 OVERVIEW SISTEM

### **Konsep Utama E-Masjid**
Sistem E-Masjid adalah platform pengurusan masjid yang komprehensif dengan **Multi-Tenant Architecture** yang memisahkan data antara masjid-masjid yang berbeza. Sistem ini direka untuk memudahkan pengurusan operasi harian masjid dari aspek pentadbiran, kewangan, operasi dan laporan.

### **Hierarki Pengguna**
1. **Super Admin** - Akses penuh kepada semua masjid dan data sistem
2. **Admin Masjid** - Akses terhad kepada data masjid mereka sahaja
3. **Pengguna Biasa** - Akses mengikut peranan yang ditetapkan

### **Prinsip Data Isolation**
- Setiap data mempunyai `masjid_id` untuk pengasingan
- Super Admin boleh lihat semua data dari semua masjid
- Admin Masjid hanya boleh akses data masjid sendiri
- Automatic filtering menggunakan `HasMasjidScope` trait

### **Permission Matrix System**
Sistem menggunakan 8 jenis kebenaran:
- **Create** - Tambah data baru
- **Read** - Lihat data
- **Update** - Kemaskini data
- **Delete** - Padam data
- **Approve** - Luluskan permohonan
- **Reject** - Tolak permohonan
- **Suspend** - Gantung status
- **Reactivate** - Aktifkan semula

---

## 🏛️ PENTADBIRAN SISTEM

### **1. Papan Pemuka (Overview)** ✅ *SEDIA ADA*
**Tujuan:** Paparan utama yang memberikan gambaran keseluruhan aktiviti dan statistik masjid

**Fungsi Sedia Ada:**
- Widget cuaca dan waktu solat (real-time dari API)
- Navigasi utama ke semua modul sistem
- Responsive design untuk desktop dan mobile
- Multi-tenant data isolation

**Route:** `/overview` → `overview.blade.php`

**Data Isolation:**
- Super Admin: Boleh akses semua data masjid
- Admin Masjid: Hanya data masjid mereka

**Relationship:**
- Berkaitan dengan Integrasi Cuaca untuk widget cuaca
- Berkaitan dengan API waktu solat untuk paparan solat
- Navigation hub untuk semua modul sistem

### **2. Senarai Masjid** ✅ *SEDIA ADA* *(Super Admin Sahaja)*
**Tujuan:** Pengurusan pendaftaran dan kelulusan masjid dalam sistem

**Fungsi Sedia Ada:**
- Senarai semua masjid dengan filtering dan search
- CRUD operations lengkap (Create, Read, Update, Delete)
- Workflow kelulusan: Approve, Reject, Suspend, Reactivate
- Maklumat lengkap masjid (alamat, koordinat, kapasiti, kategori)
- Upload dan pengurusan dokumen lampiran masjid
- Export data masjid ke CSV/Excel
- Auto-generate kod masjid berdasarkan negeri

**Routes Sedia Ada:**
- `/senarai-masjid` → MasjidController@index
- `/senarai-masjid/create` → MasjidController@create
- `/senarai-masjid/{id}/edit` → MasjidController@edit
- `/senarai-masjid/{id}/approve` → MasjidController@approve
- `/senarai-masjid/{id}/reject` → MasjidController@reject
- `/senarai-masjid/{id}/suspend` → MasjidController@suspend

**Permission Required:** `permission:masjids,read/create/update/delete/approve/reject/suspend/reactivate`

**Workflow Sedia Ada:**
1. Masjid mendaftar dengan maklumat lengkap dan dokumen
2. Status: "Pending" - menunggu kelulusan Super Admin
3. Super Admin semak dokumen dan maklumat
4. Kelulusan: Status → "Active", Penolakan: Status → "Rejected"
5. Boleh suspend/reactivate masjid yang sudah aktif

**Database Fields:**
- Maklumat Asas: nama, nama_penuh, kod_masjid, kategori
- Alamat: alamat, poskod, bandar, negeri, latitude, longitude
- Kontak: telefon, faks, email, laman_web
- Pendaftar: pendaftar_nama, pendaftar_telefon, pendaftar_email, pendaftar_jawatan
- Workflow: status, diluluskan_oleh, tarikh_diluluskan, catatan_kelulusan
- Lain-lain: kapasiti_jemaah, tarikh_ditubuhkan, logo_path, attachment_path

**Relationship:**
- **Senarai Pengguna**: Setiap masjid mempunyai Admin Masjid dan pengguna
- **Senarai Kumpulan**: Masjid boleh cipta peranan custom
- **Tetapan Umum**: Setiap masjid ada tetapan tersendiri
- **Ahli Kariah**: Setiap kariah belong kepada masjid

### **3. Senarai Pengguna** ✅ *SEDIA ADA*
**Tujuan:** Pengurusan akaun pengguna dan peranan mereka dalam sistem

**Fungsi Sedia Ada:**
- Senarai semua pengguna dengan filtering dan search
- CRUD operations lengkap untuk pengguna
- Assignment peranan kepada pengguna
- Workflow verification: Verify/Unverify pengguna
- Pengurusan status pengguna (Verified, Unverified, Suspended)
- Multi-tenant data isolation (pengguna filtered by masjid_id)

**Routes Sedia Ada:**
- `/senarai-pengguna` → UserController@index
- `/senarai-pengguna/create` → UserController@create
- `/senarai-pengguna/{id}/edit` → UserController@edit
- `/senarai-pengguna/{id}/verify` → UserController@verify
- `/senarai-pengguna/{id}/unverify` → UserController@unverify

**Permission Required:** `permission:users,read/create/update/delete/suspend/reactivate`

**Data Isolation:**
- Super Admin: Lihat pengguna semua masjid
- Admin Masjid: Lihat pengguna masjid mereka sahaja (auto-filtered by masjid_id)

**Workflow Sedia Ada:**
1. Admin cipta akaun pengguna baru
2. Assign peranan dan masjid kepada pengguna
3. Pengguna boleh login dengan credentials
4. Admin boleh verify/unverify pengguna

**Database Fields:**
- Maklumat Asas: name, email, phone, password
- Assignment: masjid_id, role_id
- Status: email_verified_at, is_active
- Timestamps: created_at, updated_at

**Relationship:**
- **Senarai Masjid**: Setiap pengguna belong kepada masjid (masjid_id)
- **Senarai Kumpulan**: Setiap pengguna ada peranan (role_id)
- **Ahli Kariah**: Pengguna boleh jadi ahli kariah juga
- **Tetapan**: Pengguna access tetapan mengikut masjid mereka

### **4. Senarai Kumpulan (Peranan)** ✅ *SEDIA ADA*
**Tujuan:** Pengurusan peranan custom dan kebenaran akses dalam sistem

**Fungsi Sedia Ada:**
- CRUD operations lengkap untuk peranan
- Permission Matrix dengan 8 jenis kebenaran per modul
- Peranan sistem (Super Admin, Admin Masjid) dan peranan custom
- Multi-tenant: Peranan global (Super Admin) dan peranan per masjid
- Clone/duplicate peranan untuk memudahkan setup

**Routes Sedia Ada:**
- `/senarai-kumpulan` → RoleController@index
- `/senarai-kumpulan/create` → RoleController@create
- `/senarai-kumpulan/{id}/edit` → RoleController@edit

**Permission Required:** `permission:roles,read/create/update/delete`

**Permission Matrix Sedia Ada:**
```
Modul                    | Create | Read | Update | Delete | Approve | Reject | Suspend | Reactivate
-------------------------|--------|------|--------|--------|---------|--------|---------|------------
Dashboard                |   -    |  ✓   |   -    |   -    |    -    |   -    |    -    |     -
Senarai Masjid          |   ✓    |  ✓   |   ✓    |   ✓    |    ✓    |   ✓    |    ✓    |     ✓
Senarai Pengguna        |   ✓    |  ✓   |   ✓    |   ✓    |    -    |   -    |    ✓    |     ✓
Senarai Kumpulan        |   ✓    |  ✓   |   ✓    |   ✓    |    -    |   -    |    -    |     -
Ahli Kariah             |   ✓    |  ✓   |   ✓    |   ✓    |    ✓    |   ✓    |    ✓    |     ✓
Tetapan Umum            |   -    |  ✓   |   ✓    |   -    |    -    |   -    |    -    |     -
Integrasi               |   -    |  ✓   |   ✓    |   -    |    -    |   -    |    -    |     -
- Email (SMTP)          |   -    |  ✓   |   ✓    |   -    |    -    |   -    |    -    |     -
- Cuaca                 |   -    |  ✓   |   ✓    |   -    |    -    |   -    |    -    |     -
- API                   |   -    |  ✓   |   ✓    |   -    |    -    |   -    |    -    |     -
```

**Database Fields:**
- Maklumat Asas: name, description, is_system_role, is_active
- Multi-tenant: masjid_id (NULL untuk system roles)
- Permissions: permissions (JSON field dengan matrix)

**Data Isolation:**
- System Roles (masjid_id = NULL): Super Admin sahaja boleh manage
- Masjid Roles (masjid_id != NULL): Admin Masjid boleh manage untuk masjid mereka

**Relationship:**
- **Senarai Pengguna**: Setiap pengguna mempunyai peranan (role_id)
- **Senarai Masjid**: Peranan custom belong kepada masjid
- **Semua Modul**: Peranan mengawal akses kepada setiap fungsi melalui middleware

---

## 👥 PENGURUSAN

### **1. Ahli Kariah** ✅ *SEDIA ADA*
**Tujuan:** Pengurusan ahli kariah masjid dengan sistem kelulusan dan dokumen

**Fungsi Sedia Ada:**
- CRUD operations lengkap untuk ahli kariah
- Upload dokumen IC (depan & belakang) dengan file validation
- Workflow lengkap: Approve, Reject, Suspend, Reactivate
- Pengurusan status kariah (Aktif, Menunggu, Ditolak, Tidak Aktif, Digantung)
- Pengiraan umur automatik dari nombor IC
- Filtering dan search berdasarkan nama, IC, telefon, status
- Export data kariah ke CSV dengan filtering
- Statistics cards (6 cards): Total, Aktif, Menunggu, Ditolak, Tidak Aktif, Digantung
- Multi-tenant data isolation (auto-filtered by masjid_id)

**Routes Sedia Ada:**
- `/kariah` → KariahController@index
- `/kariah/create` → KariahController@create
- `/kariah/{id}/edit` → KariahController@edit
- `/kariah/{id}/approve` → KariahController@approve
- `/kariah/{id}/reject` → KariahController@reject
- `/kariah/{id}/suspend` → KariahController@suspend
- `/kariah/{id}/reactivate` → KariahController@reactivate
- `/kariah-export` → KariahController@export

**Permission Required:** `permission:kariah,read/create/update/delete/approve/reject/suspend/reactivate`

**Workflow Sedia Ada:**
1. Admin cipta kariah baru dengan maklumat dan upload dokumen IC
2. Status: "Menunggu" - menunggu kelulusan
3. Admin semak dokumen dan maklumat
4. Approve: Status → "Aktif", Reject: Status → "Ditolak" dengan catatan
5. Boleh suspend/reactivate kariah yang sudah aktif

**Database Fields Sedia Ada:**
- Maklumat Peribadi: nama, no_ic, telefon, email, alamat, bangsa
- Maklumat Keahlian: tarikh_keahlian, zon, status
- Dokumen: ic_depan_path, ic_belakang_path
- Workflow: status, catatan_kelulusan, catatan_penolakan, diluluskan_oleh, ditolak_oleh
- Multi-tenant: masjid_id (auto-assigned)
- Audit: created_by, updated_by, created_at, updated_at

**Data Isolation:**
- Super Admin: Boleh lihat kariah semua masjid
- Admin Masjid: Hanya kariah masjid mereka (auto-filtered by HasMasjidScope trait)

**Relationship:**
- **Senarai Masjid**: Setiap kariah belong kepada masjid (masjid_id)
- **Senarai Pengguna**: created_by, updated_by reference kepada users
- **File Storage**: IC documents disimpan dalam public/storage/kariah/
- **Future Modules**: Kariah akan jadi base untuk derma, zakat, aktiviti

### **2. Pengurusan Dokumen** 🚧 *PLANNED - GOOGLE DRIVE STYLE*
**Tujuan:** Sistem pengurusan dokumen seperti Google Drive dengan sharing capabilities

**Fungsi:**
- **File Management**: Upload, organize, rename, delete files
- **Folder Structure**: Create nested folders untuk organization
- **File Sharing**: Share files/folders dengan masjid lain
- **Permission Control**: View, edit, download permissions
- **Version Control**: Track file versions dan changes
- **Search Function**: Search files by name, content, tags
- **Storage Quota**: Monitor storage usage per masjid
- **File Preview**: Preview documents, images, PDFs

**Sharing System:**
- **Internal Sharing**: Share dalam masjid (between users)
- **External Sharing**: Share dengan masjid lain
- **Permission Levels**: View Only, Comment, Edit, Full Access
- **Share Links**: Generate shareable links dengan expiry
- **Access Logs**: Track who accessed shared files

**Data Structure:**
- **My Documents**: Files owned by current masjid
- **Shared with Me**: Files shared dari masjid lain
- **Shared by Me**: Files yang saya share kepada others
- **Recent Files**: Recently accessed files
- **Starred Files**: Bookmarked important files

**Relationship:**
- **Senarai Masjid**: Files belong kepada masjid, sharing between masjids
- **Senarai Pengguna**: File ownership, sharing permissions
- **All Modules**: Documents dapat di-attach kepada records lain

### **3. Ahli Jawatankuasa Masjid** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan ahli jawatankuasa dan struktur organisasi masjid

**Menu Sedia Ada dalam Navigation:**
- Senarai AJK
- Arkib AJK
- Laporan AJK

**Fungsi Dirancang:**
- **Committee Management**: Database ahli jawatankuasa dengan positions
- **Organization Chart**: Visual struktur organisasi masjid
- **Term Management**: Track tenure, election cycles
- **Meeting Management**: Schedule dan minit mesyuarat
- **Responsibility Matrix**: Define roles dan responsibilities
- **Performance Tracking**: KPI dan achievement tracking

**Relationship:**
- **Ahli Kariah**: AJK selected from kariah database
- **Senarai Masjid**: Committee structure per masjid
- **Future Modules**: AJK involvement dalam decision making

### **4. Asnaf** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan penerima zakat dan bantuan

**Menu Sedia Ada dalam Navigation:**
- Senarai Asnaf
- Permohonan Zakat
- Agihan Zakat
- Laporan Zakat
- Tetapan Asnaf

**Fungsi Dirancang:**
- **Asnaf Database**: Registry penerima zakat dengan categories
- **Application System**: Online application untuk bantuan
- **Eligibility Assessment**: Criteria dan scoring system
- **Distribution Management**: Track agihan dan amounts
- **Impact Monitoring**: Follow-up on assistance effectiveness

**Relationship:**
- **Ahli Kariah**: Asnaf may be kariah members
- **Kewangan**: Zakat collection dan distribution
- **Laporan**: Transparency reports untuk stakeholders

### **5. Kebajikan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan program kebajikan dan bantuan

**Menu Sedia Ada dalam Navigation:**
- Program Kebajikan
- Penerima Bantuan
- Permohonan Bantuan
- Pembayaran Bantuan
- Laporan Kebajikan
- Tetapan Kebajikan

**Fungsi Dirancang:**
- **Welfare Programs**: Different types of assistance programs
- **Beneficiary Management**: Database penerima bantuan
- **Application Processing**: Workflow untuk permohonan bantuan
- **Payment Distribution**: Track payments dan disbursements
- **Program Effectiveness**: Monitor success rates dan impact

**Relationship:**
- **Asnaf**: Overlap dengan zakat recipients
- **Kewangan**: Budget allocation untuk welfare programs
- **Laporan**: Impact reports dan transparency

---

## 🏢 OPERASI

### **1. Fasiliti & Tempahan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan fasiliti masjid dan sistem tempahan

**Menu Sedia Ada dalam Navigation:**
- Senarai Fasiliti
- Kalendar Tempahan
- Pengurusan Tempahan
- Laporan Penggunaan

**Fungsi Dirancang:**
- Senarai fasiliti masjid (dewan, bilik, parking, surau)
- Sistem tempahan online dengan kalendar
- Pengurusan harga sewa dan deposit
- Workflow kelulusan tempahan
- Pengurusan konflik jadual automatik
- QR code untuk check-in fasiliti

**Workflow Tempahan Dirancang:**
1. Pengguna/Kariah pilih fasiliti dan tarikh
2. Sistem check ketersediaan real-time
3. Isi maklumat tempahan dan bayaran
4. Admin sahkan tempahan
5. Pengguna terima pengesahan dan QR code
6. Sistem update kalendar dan generate invoice

### **2. Inventori & Aset** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan inventori dan aset masjid

**Menu Sedia Ada dalam Navigation:**
- Senarai Aset
- Maintenance Schedule
- Pinjaman Aset
- Laporan Aset

**Fungsi Dirancang:**
- Database aset dan inventori masjid
- Tracking lokasi, kondisi, dan nilai aset
- Jadual maintenance dan pembaikan
- Sistem pinjaman aset kepada kariah/AJK
- Laporan susut nilai dan audit aset
- Barcode/QR code untuk asset tracking

### **3. Pengurusan Kenderaan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan kenderaan masjid dan sistem booking

**Menu Sedia Ada dalam Navigation:**
- Senarai Kenderaan
- Jadual Penggunaan
- Maintenance Kenderaan
- Laporan Kenderaan

**Fungsi Dirancang:**
- Database kenderaan masjid (van, kereta, bas)
- Sistem booking kenderaan untuk aktiviti
- Jadual maintenance dan roadtax
- Log book penggunaan dan mileage
- Pengurusan permit dan insurans

**Relationship untuk Modul Operasi:**
- **Future Kewangan > Jualan**: Tempahan fasiliti generate invoice
- **Future Kewangan > Perakaunan**: Record pembayaran sewa dan nilai aset
- **Ahli Kariah**: Kariah dapat diskaun tempahan dan priority booking
- **Pengurusan > Aktiviti**: Aktiviti guna fasiliti dan kenderaan
- **Future Laporan**: Statistik penggunaan dan ROI fasiliti

---

## 💰 KEWANGAN

### **1. Pembelian** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan pembelian dan procurement masjid

**Menu Sedia Ada dalam Navigation:**
- Pesanan Pembelian
- Penerimaan Barang
- Invois Pembekal
- Laporan Pembelian

**Fungsi Dirancang:**
- Sistem purchase order (PO)
- Pengurusan vendor dan pembekal
- Workflow approval untuk pembelian
- Tracking delivery dan penerimaan barang
- Integration dengan inventori dan perakaunan

### **2. Jualan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan jualan produk dan perkhidmatan masjid

**Menu Sedia Ada dalam Navigation:**
- Sebut Harga
- Invois Jualan
- Resit Pembayaran
- Laporan Jualan

**Fungsi Dirancang:**
- Sistem Point of Sale (POS) untuk kedai masjid
- Pengurusan produk dan perkhidmatan
- Workflow: Quotation → Invoice → Receipt
- Integration dengan payment gateway
- Diskaun khusus untuk ahli kariah
- Inventory management untuk produk

**Workflow Jualan Dirancang:**
1. Cipta sebut harga untuk pelanggan
2. Pelanggan setuju, convert ke invoice
3. Pelanggan bayar (cash/online), generate resit
4. Auto-update stok dan rekod kewangan
5. Integration dengan perakaunan

### **3. Perakaunan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Sistem perakaunan lengkap untuk masjid

**Menu Sedia Ada dalam Navigation:**
- Carta Akaun
- Jurnal Entry
- Kunci Kira-kira
- Penyata Untung Rugi

**Fungsi Dirancang:**
- Chart of Accounts (Carta Akaun) sesuai dengan masjid
- Double-entry bookkeeping system
- Auto journal entry dari modul lain
- Financial statements (Balance Sheet, P&L, Cash Flow)
- Rekonsiliasi bank dan petty cash
- Budget planning dan variance analysis

### **4. Derma & Wakaf** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan derma dan wakaf untuk masjid

**Menu Sedia Ada dalam Navigation:**
- Portal Derma
- Kempen Derma
- Laporan Derma
- Pengurusan Wakaf

**Fungsi Dirancang:**
- Portal derma online dengan payment gateway
- Kategori derma (pembangunan, operasi, khairat, wakaf)
- Kempen derma dengan target dan progress tracking
- Auto-generate resit derma untuk tax exemption
- Pengurusan wakaf dan amanah
- Laporan transparansi untuk penderma

### **5. Zakat** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Pengurusan kutipan dan agihan zakat

**Menu Sedia Ada dalam Navigation:**
- Kutipan Zakat
- Agihan Zakat
- Laporan Zakat
- Kalkulator Zakat

**Fungsi Dirancang:**
- Kalkulator zakat (fitrah, harta, perniagaan, emas)
- Sistem bayaran zakat online
- Database penerima zakat (8 asnaf)
- Workflow agihan zakat dengan approval
- Laporan kutipan dan agihan untuk MAIWP/JAIS
- Integration dengan sistem zakat negeri

**Relationship untuk Modul Kewangan:**
- **Operasi > Fasiliti**: Sewa fasiliti generate invoice dalam Jualan
- **Operasi > Inventori**: Produk untuk dijual, nilai aset untuk Perakaunan
- **Ahli Kariah**: Kariah sebagai pelanggan, penderma, pembayar/penerima zakat
- **Pengurusan > Kebajikan**: Agihan zakat untuk program kebajikan
- **Future Laporan**: Source data untuk laporan kewangan dan audit

---

## 📊 LAPORAN

### **1. Laporan Pengurusan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Laporan statistik dan analisis pengurusan masjid

**Menu Sedia Ada dalam Navigation:**
- Laporan Kariah
- Laporan AJK
- Laporan Aktiviti
- Laporan Kebajikan

**Fungsi Dirancang:**
- **Laporan Kariah**: Statistik kariah mengikut status, demografi, trend pertumbuhan
- **Laporan AJK**: Prestasi jawatankuasa, kehadiran mesyuarat
- **Laporan Aktiviti**: Statistik program, kehadiran, feedback peserta
- **Laporan Kebajikan**: Agihan bantuan, impact assessment

**Data Source:**
- **Ahli Kariah**: Data utama untuk analisis demografi
- **Future AJK**: Data jawatankuasa dan mesyuarat
- **Future Aktiviti**: Data program dan peserta
- **Future Kebajikan**: Data bantuan dan penerima

### **2. Laporan Operasi** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Laporan operasi dan penggunaan fasiliti masjid

**Menu Sedia Ada dalam Navigation:**
- Laporan Fasiliti
- Laporan Aset
- Laporan Kenderaan
- Laporan Maintenance

**Fungsi Dirancang:**
- **Laporan Fasiliti**: Utilization rate, revenue dari sewa, popular time slots
- **Laporan Aset**: Nilai aset, depreciation, maintenance cost
- **Laporan Kenderaan**: Usage, mileage, maintenance schedule
- **Laporan Maintenance**: Preventive vs corrective maintenance, cost analysis

### **3. Laporan Kewangan** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Laporan kewangan komprehensif masjid

**Menu Sedia Ada dalam Navigation:**
- Laporan Pendapatan
- Laporan Perbelanjaan
- Laporan Derma
- Laporan Zakat

**Fungsi Dirancang:**
- **Laporan Pendapatan**: Income dari sewa, jualan, derma, zakat
- **Laporan Perbelanjaan**: Operating expenses, capital expenditure
- **Laporan Derma**: Transparency report, donor analysis, campaign performance
- **Laporan Zakat**: Kutipan vs agihan, compliance dengan syariah

### **4. Dashboard Analytics** 🚧 *DALAM NAVIGASI - BELUM IMPLEMENT*
**Tujuan:** Real-time analytics dan KPI dashboard

**Menu Sedia Ada dalam Navigation:**
- KPI Dashboard
- Trend Analysis
- Comparative Reports
- Executive Summary

**Fungsi Dirancang:**
- **KPI Dashboard**: Real-time metrics untuk semua modul
- **Trend Analysis**: Historical data dengan forecasting
- **Comparative Reports**: Benchmark dengan masjid lain (anonymized)
- **Executive Summary**: Monthly/yearly summary untuk AJK

**Data Source untuk Semua Laporan:**
- **Semua Modul Sedia Ada**: Kariah, Pengguna, Masjid, Tetapan
- **Future Modules**: Aktiviti, Fasiliti, Kewangan, Derma, Zakat
- **External APIs**: Cuaca, waktu solat untuk correlation analysis

---

## 🔗 INTEGRASI

### **1. Integrasi Email (SMTP)** ✅ *SEDIA ADA*
**Tujuan:** Konfigurasi sistem email untuk notifikasi automatik

**Fungsi Sedia Ada:**
- Setup SMTP server (Gmail, Outlook, Custom SMTP)
- Test email configuration dengan real email sending
- Health check untuk SMTP connection
- Email templates untuk pelbagai tujuan
- Log email yang dihantar

**Routes Sedia Ada:**
- `/email-configurations/{id}` → Update SMTP settings
- `/email-configurations/smtp-health` → Health check
- `/test-email-send` → Test email functionality

**Permission Required:** `permission:integrations_email,read/update`

**Features Implemented:**
- SMTP configuration form dengan validation
- Test email dengan custom recipient
- Health monitoring untuk email service
- Integration dengan Laravel Mail system

**Relationship:**
- **Semua Modul**: Hantar notifikasi email untuk workflow
- **Ahli Kariah**: Email notification untuk approval/rejection
- **Senarai Pengguna**: Email untuk password reset dan verification

### **2. Integrasi Cuaca** ✅ *SEDIA ADA*
**Tujuan:** Paparan maklumat cuaca untuk aktiviti masjid

**Fungsi Sedia Ada:**
- Integration dengan Tomorrow.io Weather API
- Real-time weather data dengan caching (10 minutes)
- Weather widget di navigation bar
- Weather condition mapping dengan icons
- Fallback data jika API gagal

**Routes Sedia Ada:**
- `/weather-configurations` → WeatherConfigurationController@index
- `/weather-configurations/test` → Test API connection
- `/weather-configurations/refresh` → Refresh weather data
- `/weather` → WeatherController@getWeather (API endpoint)

**Permission Required:** `permission:integrations_weather,read/update`

**API Integration:**
- Tomorrow.io API dengan API key management
- Weather codes mapping untuk condition display
- Automatic fallback ke default weather (24°C, Clear)
- Location-based weather (default: Kuala Lumpur)

**Relationship:**
- **Papan Pemuka**: Weather widget dalam navigation
- **Future Aktiviti**: Weather data untuk planning aktiviti luar
- **Tetapan**: Weather configuration per masjid

### **3. Integrasi API** ✅ *SEDIA ADA*
**Tujuan:** Pengurusan API keys dan konfigurasi integrasi luaran

**Fungsi Sedia Ada:**
- Sanctum token management untuk API access
- API configuration dengan SSL verification
- Rate limiting dan security settings
- API health monitoring
- Token generation dan revocation

**Routes Sedia Ada:**
- `/sanctum-tokens` → SanctumTokenController@index
- `/sanctum-tokens` (POST) → Create new API token
- `/sanctum-tokens` (DELETE) → Revoke all tokens
- `/api-configurations/{id}` → Update API settings
- `/api-configurations/test` → Test API connection

**Permission Required:** `permission:integrations_api,read/update`

**Features Implemented:**
- Laravel Sanctum integration untuk API authentication
- API token management dengan expiry
- SSL verification toggle
- API health check dan monitoring
- Rate limiting configuration

**Relationship:**
- **Integrasi Cuaca**: API keys untuk weather service
- **Future Payment Gateway**: API untuk pembayaran online
- **External Systems**: Integration dengan sistem JAKIM, MAIWP
- **Mobile App**: API access untuk future mobile application

---

## ⚙️ TETAPAN

### **1. Tetapan Sistem**
**Tujuan:** Konfigurasi asas sistem untuk setiap masjid

**Fungsi:**
- Maklumat asas masjid
- Koordinat GPS untuk waktu solat
- Zon waktu solat
- Logo dan branding masjid
- Bahasa dan format tarikh
- Backup dan restore settings

**Data Isolation:**
- Super Admin: Tetapan global sistem
- Admin Masjid: Tetapan khusus masjid mereka

**Relationship:**
- **Dashboard**: Gunakan tetapan untuk display
- **Integrasi**: Konfigurasi untuk API
- **Semua Modul**: Rujuk tetapan sistem

### **2. Tetapan Waktu Solat**
**Tujuan:** Konfigurasi waktu solat dan azan untuk masjid

**Fungsi:**
- Pilih zon waktu solat Malaysia
- Adjustment manual waktu solat
- Setting azan automatik
- Upload fail audio azan custom
- Jadual waktu solat bulanan

**Relationship:**
- **Dashboard**: Paparan waktu solat
- **Integrasi**: API waktu solat JAKIM

### **3. Tetapan Keselamatan**
**Tujuan:** Konfigurasi keselamatan dan akses sistem

**Fungsi:**
- Password policy
- Session timeout
- Two-factor authentication
- Login attempt limits
- IP whitelist/blacklist
- Audit log settings

**Relationship:**
- **Senarai Pengguna**: Enforce security policy
- **Peranan**: Security permissions

---

## 🆘 BANTUAN

### **1. Panduan Pengguna**
**Tujuan:** Dokumentasi lengkap penggunaan sistem

**Fungsi:**
- Tutorial step-by-step
- Video panduan
- FAQ (Soalan Lazim)
- Troubleshooting guide
- Best practices

### **2. Hubungi Sokongan**
**Tujuan:** Saluran komunikasi dengan tim sokongan

**Fungsi:**
- Borang aduan dan pertanyaan
- Live chat support
- Ticket system
- Knowledge base
- Remote assistance

### **3. Status Sistem**
**Tujuan:** Monitoring kesihatan dan prestasi sistem

**Fungsi:**
- Server uptime monitoring
- Database performance
- API response time
- Error rate tracking
- Maintenance schedule

### **4. Nota Keluaran**
**Tujuan:** Maklumat update dan versi baru sistem

**Fungsi:**
- Changelog setiap versi
- New features announcement
- Bug fixes list
- Breaking changes notice
- Upgrade instructions

---

## 🔄 RELATIONSHIP MATRIX

### **Hubungan Utama Antara Modul:**

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   PENTADBIRAN   │────│   PENGURUSAN    │────│    OPERASI      │
│                 │    │                 │    │                 │
│ • Senarai Masjid│    │ • Ahli Kariah   │    │ • Fasiliti      │
│ • Pengguna      │    │ • Aktiviti      │    │ • Inventori     │
│ • Peranan       │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │    KEWANGAN     │
                    │                 │
                    │ • Jualan        │
                    │ • Perakaunan    │
                    │ • Derma         │
                    │ • Zakat         │
                    └─────────────────┘
                                 │
                    ┌─────────────────┐
                    │    LAPORAN      │
                    │                 │
                    │ • Lap. Kariah   │
                    │ • Lap. Kewangan │
                    │ • Lap. Operasi  │
                    └─────────────────┘
```

### **Contoh Workflow Terintegrasi:**

**Scenario: Tempahan Dewan untuk Majlis Kahwin**

1. **Operasi > Fasiliti**: Pelanggan tempah dewan
2. **Kewangan > Jualan**: System generate quotation
3. **Ahli Kariah**: Check status kariah untuk diskaun
4. **Kewangan > Jualan**: Quotation approve, jadi invoice
5. **Kewangan > Perakaunan**: Payment record masuk akaun
6. **Laporan**: Data masuk laporan pendapatan bulanan

**Scenario: Pengurusan Kariah Baru**

1. **Pengurusan > Kariah**: Kariah baru register
2. **Pentadbiran > Pengguna**: Admin dapat notification
3. **Pengurusan > Kariah**: Admin approve/reject
4. **Integrasi > Email**: Hantar email confirmation
5. **Laporan**: Update statistik kariah
6. **Kewangan > Derma**: Kariah boleh start derma

### **Data Flow Diagram:**

```
[Kariah Register] → [Approval Workflow] → [Email Notification] → [Active Status]
        │                    │                     │                    │
        ▼                    ▼                     ▼                    ▼
[Upload Documents] → [Admin Review] → [Integration Email] → [Access Granted]
        │                    │                     │                    │
        ▼                    ▼                     ▼                    ▼
[Status: Pending] → [Status: Approved] → [Email Sent] → [Status: Active]
```

### **Multi-Tenant Data Isolation Visual:**

```
┌─────────────────────────────────────────────────────────────────┐
│                        SUPER ADMIN VIEW                         │
├─────────────────────────────────────────────────────────────────┤
│  Masjid A Data    │  Masjid B Data    │  Masjid C Data         │
│  ┌─────────────┐  │  ┌─────────────┐  │  ┌─────────────┐       │
│  │ • Kariah    │  │  │ • Kariah    │  │  │ • Kariah    │       │
│  │ • Pengguna  │  │  │ • Pengguna  │  │  │ • Pengguna  │       │
│  │ • Kewangan  │  │  │ • Kewangan  │  │  │ • Kewangan  │       │
│  │ • Laporan   │  │  │ • Laporan   │  │  │ • Laporan   │       │
│  └─────────────┘  │  └─────────────┘  │  └─────────────┘       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      ADMIN MASJID A VIEW                       │
├─────────────────────────────────────────────────────────────────┤
│                    Masjid A Data SAHAJA                        │
│                  ┌─────────────────────┐                       │
│                  │ • Kariah Masjid A   │                       │
│                  │ • Pengguna Masjid A │                       │
│                  │ • Kewangan Masjid A │                       │
│                  │ • Laporan Masjid A  │                       │
│                  └─────────────────────┘                       │
└─────────────────────────────────────────────────────────────────┘
```

### **Permission Matrix Implementation:**

```
┌─────────────────────────────────────────────────────────────────┐
│                    PERMISSION MATRIX                            │
├─────────────────────────────────────────────────────────────────┤
│ Role: Bendahari Masjid                                          │
│                                                                 │
│ Modul Kariah:     [✓] Read  [✗] Create  [✗] Update  [✗] Delete │
│ Modul Kewangan:   [✓] Read  [✓] Create  [✓] Update  [✗] Delete │
│ Modul Laporan:    [✓] Read  [✗] Create  [✗] Update  [✗] Delete │
│ Modul Pengguna:   [✗] Read  [✗] Create  [✗] Update  [✗] Delete │
└─────────────────────────────────────────────────────────────────┘
```

### **System Architecture Overview:**

```
┌─────────────────────────────────────────────────────────────────┐
│                         FRONTEND LAYER                         │
├─────────────────────────────────────────────────────────────────┤
│  Desktop View (Tables)     │     Mobile View (Cards)           │
│  • Poppins Font 9-14px     │     • Touch-friendly UI           │
│  • Material Icons 16px     │     • Responsive Components       │
│  • Tailwind CSS + DaisyUI  │     • Alpine.js Interactions     │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                       MIDDLEWARE LAYER                         │
├─────────────────────────────────────────────────────────────────┤
│  • CheckPermission Middleware                                   │
│  • Data Isolation (HasMasjidScope)                            │
│  • Authentication (Sanctum)                                    │
│  • CSRF Protection                                             │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                       BUSINESS LOGIC                           │
├─────────────────────────────────────────────────────────────────┤
│  Controllers  │  Models  │  Services  │  Traits  │  Providers  │
│  • CRUD Ops   │  • ORM   │  • Weather │  • Scope │  • Health   │
│  • Workflow   │  • Cast  │  • Status  │  • Multi │  • Route    │
│  • Validation │  • Scope │  • Email   │  • Tenant│  • App      │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                        DATABASE LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│  SQLite Database (Development)  │  MySQL (Production)           │
│  • Multi-tenant Tables         │  • Foreign Key Constraints    │
│  • masjid_id Isolation         │  • Indexes for Performance    │
│  • Workflow Status Fields      │  • Migration System           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🎯 KESIMPULAN

Sistem E-Masjid direka sebagai ecosystem yang lengkap untuk pengurusan masjid moden. Setiap modul saling berkaitan dan berkongsi data untuk memberikan pengalaman pengurusan yang seamless dan efisien.

**Kelebihan Utama:**
- **Multi-Tenant**: Satu sistem untuk banyak masjid
- **Data Isolation**: Data selamat dan terpisah
- **Permission Matrix**: Kawalan akses yang granular
- **Integrated Workflow**: Semua proses bersambung
- **Responsive Design**: Boleh guna di desktop dan mobile
- **Scalable Architecture**: Boleh berkembang mengikut keperluan

**Target Pengguna:**
- Jawatankuasa Masjid
- Imam dan Bilal
- Bendahari Masjid
- Ahli Kariah
- Pihak Berkuasa Agama

**Roadmap Pembangunan:**
- **Fasa 1** (Selesai): Pentadbiran Sistem, Pengurusan Kariah
- **Fasa 2** (Dalam Pembangunan): Operasi dan Kewangan
- **Fasa 3** (Dirancang): Laporan Lanjutan dan Analytics
- **Fasa 4** (Masa Depan): Mobile App dan API Public

Sistem ini akan membantu masjid-masjid di Malaysia untuk mendigitalkan operasi mereka dan meningkatkan kualiti perkhidmatan kepada masyarakat. Dengan pendekatan multi-tenant yang selamat dan permission system yang fleksibel, E-Masjid mampu menampung keperluan pelbagai saiz masjid dari surau kecil hingga masjid besar di bandar.
