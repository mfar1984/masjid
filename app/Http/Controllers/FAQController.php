<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pageTitle = 'Soalan Lazim (FAQ) - E-Masjid';
        
        $faqs = [
            [
                'category' => 'Umum',
                'icon' => 'help',
                'color' => 'blue',
                'questions' => [
                    [
                        'question' => 'Apakah itu Sistem E-Masjid?',
                        'answer' => 'Sistem E-Masjid adalah platform pengurusan masjid yang komprehensif untuk membantu pentadbiran masjid, pengurusan ahli kariah, dan aktiviti masjid secara digital.'
                    ],
                    [
                        'question' => 'Siapakah yang boleh menggunakan sistem ini?',
                        'answer' => 'Sistem ini direka untuk pentadbir masjid, ahli jawatankuasa masjid, imam, dan kakitangan yang terlibat dalam pengurusan masjid.'
                    ],
                    [
                        'question' => 'Apakah yang baharu dalam versi 3.0?',
                        'answer' => 'Versi 3.0 adalah kemaskini major yang memperkenalkan 13 modul baharu termasuk complete Kewangan Module (Akaun Bank, Transaksi, Laporan dengan 3 TAB baharu), Asnaf Module (Permohonan Zakat, Agihan, Laporan), Kebajikan Module (Program, Penerima, Permohonan, Pembayaran), AJK (Laporan & Arkib), dan Permission System yang diperluaskan dari 17 ke 23 modules dengan TAB-level permissions.'
                    ],
                    [
                        'question' => 'Adakah sistem ini selamat untuk data sensitif?',
                        'answer' => 'Ya, sistem ini menggunakan teknologi keselamatan terkini dengan enkripsi data dan pengurusan akses yang ketat untuk melindungi maklumat sensitif ahli kariah dan masjid.'
                    ]
                ]
            ],
            [
                'category' => 'Sistem Kebenaran & Keselamatan',
                'icon' => 'security',
                'color' => 'purple',
                'questions' => [
                    [
                        'question' => 'Apakah itu sistem kebenaran dalam E-Masjid?',
                        'answer' => 'Sistem kebenaran adalah mekanisme kawalan akses yang memastikan setiap pengguna hanya dapat mengakses data dan fungsi yang sesuai dengan peranan mereka. Super Admin dapat melihat semua data, manakala Admin Masjid hanya dapat melihat data masjid mereka sendiri.'
                    ],
                    [
                        'question' => 'Bagaimana pengasingan data multi-masjid berfungsi?',
                        'answer' => 'Setiap masjid mempunyai data yang diasingkan sepenuhnya. Admin Masjid hanya dapat melihat pengguna, kumpulan, dan data yang berkaitan dengan masjid mereka sahaja. Ini memastikan privasi dan keselamatan data antara masjid.'
                    ],
                    [
                        'question' => 'Apakah perbezaan antara Super Admin dan Admin Masjid?',
                        'answer' => 'Super Admin dapat melihat dan menguruskan semua masjid dalam sistem, manakala Admin Masjid hanya dapat menguruskan masjid mereka sendiri. Super Admin mempunyai akses penuh kepada semua statistik dan data sistem.'
                    ],
                    [
                        'question' => 'Bagaimana untuk memastikan keselamatan data masjid?',
                        'answer' => 'Sistem menggunakan middleware CheckPermission untuk memastikan setiap request diverifikasi. Data difilter berdasarkan masjid_id dan role pengguna. Semua akses dilog untuk audit trail.'
                    ],
                    [
                        'question' => 'Apakah itu TAB-level permissions?',
                        'answer' => 'TAB-level permissions adalah granular permission control yang membolehkan admin set akses untuk setiap TAB dalam modul. Contohnya dalam Laporan Kewangan ada 8 TABs (Penyata, Pendapatan, Perbelanjaan, Aliran Tunai, Penyata P&P, Perbandingan, Kategori, Baki Bank) - setiap TAB boleh dikonfigurasi permission secara berasingan dalam Senarai Kumpulan.'
                    ]
                ]
            ],
            [
                'category' => 'Pengurusan Masjid',
                'icon' => 'mosque',
                'color' => 'green',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk mendaftar masjid baru dalam sistem?',
                        'answer' => 'Pergi ke menu "Senarai Masjid" dan klik butang "Tambah Masjid". Isi semua maklumat yang diperlukan termasuk nama masjid, alamat, koordinat GPS, dan maklumat hubungan.'
                    ],
                    [
                        'question' => 'Bolehkah saya edit maklumat masjid yang telah didaftarkan?',
                        'answer' => 'Ya, anda boleh edit maklumat masjid dengan mengklik butang "Edit" pada senarai masjid. Semua perubahan akan direkodkan dalam log audit untuk tujuan jejak audit.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mencari masjid tertentu?',
                        'answer' => 'Gunakan fungsi carian pada halaman "Senarai Masjid" dengan memasukkan nama masjid, negeri, atau maklumat lain yang berkaitan. Sistem akan menapis hasil secara real-time.'
                    ],
                    [
                        'question' => 'Apakah status-status masjid yang ada dalam sistem?',
                        'answer' => 'Sistem menyokong beberapa status: Pending (menunggu kelulusan), Active (aktif dan beroperasi), Suspended (digantung sementara), dan Inactive (tidak aktif). Status boleh diubah oleh pentadbir sistem.'
                    ],
                    [
                        'question' => 'Mengapa kad statistik masjid menunjukkan data yang berbeza untuk pengguna yang berbeza?',
                        'answer' => 'Kad statistik menunjukkan data berdasarkan tahap akses pengguna. Super Admin melihat statistik semua masjid (56 total, 31 aktif, dll), manakala Admin Masjid hanya melihat data masjid mereka sendiri.'
                    ]
                ]
            ],
            [
                'category' => 'Kad Statistik & Dashboard',
                'icon' => 'dashboard',
                'color' => 'teal',
                'questions' => [
                    [
                        'question' => 'Mengapa kad statistik menunjukkan angka yang berbeza dari sebelumnya?',
                        'answer' => 'Sistem telah dinaik taraf untuk menunjukkan data real-time berdasarkan pengasingan masjid. Setiap pengguna kini melihat statistik yang tepat untuk tahap akses mereka, bukan data global.'
                    ],
                    [
                        'question' => 'Apakah itu kad statistik dinamik?',
                        'answer' => 'Kad statistik dinamik menyesuaikan bilangan dan jenis kad berdasarkan data sebenar. Contohnya, jika tiada pengguna yang belum disahkan, kad tersebut tidak akan dipaparkan. Ini memberikan paparan yang lebih bersih dan relevan.'
                    ],
                    [
                        'question' => 'Mengapa layout kad menggunakan full width sekarang?',
                        'answer' => 'Layout full width memastikan kad tersebar merata di seluruh container, memberikan penggunaan ruang yang optimum dan pengalaman visual yang lebih baik pada semua saiz skrin.'
                    ],
                    [
                        'question' => 'Berapa banyak kad yang akan dipaparkan untuk setiap modul?',
                        'answer' => 'Bilangan kad adalah dinamik: Senarai Kumpulan (2-4 kad), Senarai Pengguna (tepat 3 kad), Senarai Masjid (4-6 kad). Bilangan bergantung pada data yang ada dan tahap akses pengguna.'
                    ]
                ]
            ],
            [
                'category' => 'Pengurusan Kumpulan & Peranan',
                'icon' => 'groups',
                'color' => 'indigo',
                'questions' => [
                    [
                        'question' => 'Apakah perbezaan antara System Role dan Custom Role?',
                        'answer' => 'System Role (seperti Super Admin, Admin Masjid) adalah peranan terbina dalam sistem yang tidak boleh diubah. Custom Role adalah peranan yang dicipta khusus oleh masjid untuk keperluan mereka sendiri.'
                    ],
                    [
                        'question' => 'Mengapa saya hanya nampak beberapa kumpulan dalam senarai?',
                        'answer' => 'Anda hanya dapat melihat kumpulan yang berkaitan dengan masjid anda. Admin Masjid hanya melihat kumpulan masjid mereka, manakala Super Admin melihat semua kumpulan dalam sistem.'
                    ],
                    [
                        'question' => 'Bagaimana untuk menambah kumpulan baharu?',
                        'answer' => 'Pergi ke Senarai Kumpulan dan klik "Tambah Kumpulan". Kumpulan yang dicipta akan dikaitkan dengan masjid anda dan hanya visible kepada pengguna masjid tersebut.'
                    ],
                    [
                        'question' => 'Mengapa kad statistik kumpulan menunjukkan bilangan yang berbeza?',
                        'answer' => 'Kad statistik kumpulan menunjukkan data berdasarkan pengasingan masjid. Admin Masjid hanya melihat statistik kumpulan masjid mereka (contoh: 1 Jumlah, 1 Aktif, 1 Tersuai), manakala Super Admin melihat semua kumpulan sistem.'
                    ],
                    [
                        'question' => 'Berapa banyak modules dalam permission matrix sekarang?',
                        'answer' => 'Permission matrix telah diperluaskan dari 17 modules kepada 23 modules dalam v3.0. Modules baharu termasuk: Permohonan Zakat, Laporan Zakat, Tetapan Asnaf, Program Kebajikan, Penerima Bantuan, Permohonan Bantuan, Pembayaran Bantuan, Laporan Kebajikan, Tetapan Kebajikan, Akaun Bank, Transaksi Kewangan, Laporan Kewangan, dan Tetapan Kewangan. Semua modules disusun mengikut ASCII sorting dengan visual separators.'
                    ]
                ]
            ],
            [
                'category' => 'Pengurusan Pengguna',
                'icon' => 'people',
                'color' => 'orange',
                'questions' => [
                    [
                        'question' => 'Mengapa saya hanya nampak beberapa pengguna dalam senarai?',
                        'answer' => 'Sistem menggunakan pengasingan data berdasarkan masjid. Admin Masjid hanya dapat melihat pengguna yang berkaitan dengan masjid mereka, manakala Super Admin dapat melihat semua pengguna sistem.'
                    ],
                    [
                        'question' => 'Apakah maksud kad "Jumlah Pengguna", "Belum Disahkan", dan "Disahkan"?',
                        'answer' => 'Kad ini menunjukkan statistik pengguna yang tepat: Jumlah Pengguna (semua pengguna dalam scope anda), Belum Disahkan (pengguna yang belum verify email), Disahkan (pengguna yang sudah verify email).'
                    ],
                    [
                        'question' => 'Mengapa kad peranan pengguna (seperti "Masjid Abidin", "Admin Masjid") tidak lagi dipaparkan?',
                        'answer' => 'Kad peranan telah dialih keluar untuk mengelakkan kekacauan UI apabila terdapat banyak peranan. Sekarang sistem menunjukkan 3 kad tetap yang lebih penting dan konsisten untuk semua pengguna.'
                    ],
                    [
                        'question' => 'Bagaimana untuk menambah pengguna baharu?',
                        'answer' => 'Pergi ke Senarai Pengguna dan klik "Tambah Pengguna". Pengguna baharu akan dikaitkan dengan masjid anda dan hanya visible kepada pengguna masjid yang sama.'
                    ]
                ]
            ],
            [
                'category' => 'Modul Kewangan',
                'icon' => 'account_balance',
                'color' => 'green',
                'questions' => [
                    [
                        'question' => 'Apakah modul-modul dalam Kewangan?',
                        'answer' => 'Modul Kewangan mengandungi 4 sub-modul: Akaun Bank (pengurusan akaun bank masjid), Transaksi Kewangan (rekod pendapatan & perbelanjaan), Laporan Kewangan (8 TAB laporan termasuk Penyata P&P, Perbandingan Bulanan, Laporan Kategori), dan Tetapan Kewangan (konfigurasi kategori pendapatan/perbelanjaan).'
                    ],
                    [
                        'question' => 'Bagaimana untuk merekod transaksi kewangan?',
                        'answer' => 'Pergi ke Transaksi Kewangan dan pilih jenis transaksi (Pendapatan atau Perbelanjaan). Untuk Pendapatan ada 4 form: Kutipan Kariah, Derma & Sumbangan, Kutipan Zakat, Kutipan Lain. Untuk Perbelanjaan ada 4 form: Utiliti & Bil, Penyelenggaraan, Gaji & Elaun, Perbelanjaan Lain. Setiap form ada kategori dropdown untuk categorization yang proper.'
                    ],
                    [
                        'question' => 'Apakah itu Penyata Pendapatan & Perbelanjaan?',
                        'answer' => 'Penyata P&P adalah laporan kewangan yang menunjukkan BAHAGIAN A: PENDAPATAN (semua kutipan), BAHAGIAN B: PERBELANJAAN (semua perbelanjaan), dan LEBIHAN/KURANGAN (SURPLUS/DEFICIT). Format ini lebih sesuai untuk konteks masjid berbanding Imbangan Duga.'
                    ],
                    [
                        'question' => 'Bagaimana untuk lihat laporan kewangan masjid lain (Super Admin)?',
                        'answer' => 'Super Admin boleh gunakan dropdown "Pilih Masjid" dalam Laporan Kewangan untuk lihat laporan masjid lain. Data isolation yang proper memastikan setiap masjid hanya nampak data mereka sendiri, kecuali Super Admin.'
                    ],
                    [
                        'question' => 'Apakah itu Baki Pada Masa Transaksi?',
                        'answer' => 'Baki Pada Masa Transaksi adalah historical balance calculation yang menunjukkan baki bank pada masa transaksi tersebut dibuat. Ia dikira dengan formula: baki_awal + pendapatan_sebelum - perbelanjaan_sebelum. Ini berbeza dengan Baki Semasa (Terkini) yang menunjukkan baki terkini.'
                    ],
                    [
                        'question' => 'Apakah itu kategori integration dalam form kewangan?',
                        'answer' => 'Semua 8 forms kewangan (4 Kutipan Dana + 4 Perbelanjaan) kini ada kategori dropdown. Untuk Derma & Sumbangan ada Jenis Derma (sub-category), untuk Utiliti & Bil ada Jenis Bil. Kategori ini dynamic dan customizable dari Tetapan Kewangan untuk better reporting dan analysis.'
                    ]
                ]
            ],
            [
                'category' => 'Modul Asnaf & Kebajikan',
                'icon' => 'volunteer_activism',
                'color' => 'pink',
                'questions' => [
                    [
                        'question' => 'Apakah modul-modul dalam Asnaf?',
                        'answer' => 'Modul Asnaf mengandungi: Asnaf (pengurusan data asnaf), Permohonan Zakat (permohonan bantuan zakat dengan workflow approve/reject), Agihan Zakat (rekod agihan kepada asnaf), Laporan Zakat (laporan view-only), dan Tetapan Asnaf (konfigurasi had kifayah, had bantuan, workflow, kategori).'
                    ],
                    [
                        'question' => 'Apakah modul-modul dalam Kebajikan?',
                        'answer' => 'Modul Kebajikan mengandungi: Program Kebajikan (pengurusan program bantuan), Penerima Bantuan (data penerima), Permohonan Bantuan (permohonan bantuan kebajikan), Pembayaran Bantuan (rekod pembayaran), Laporan Kebajikan (laporan view-only), dan Tetapan Kebajikan (konfigurasi had bantuan, workflow, kategori, tempoh bantuan).'
                    ],
                    [
                        'question' => 'Bagaimana workflow permohonan zakat berfungsi?',
                        'answer' => 'Permohonan Zakat mempunyai workflow approve/reject sahaja (tanpa suspend/reactivate). Selepas permohonan dibuat, ia boleh diluluskan atau ditolak oleh admin. Jika diluluskan, agihan zakat boleh direkod dalam modul Agihan Zakat.'
                    ],
                    [
                        'question' => 'Apakah perbezaan antara Asnaf dan Penerima Bantuan?',
                        'answer' => 'Asnaf adalah untuk bantuan zakat (8 kategori asnaf mengikut syarak), manakala Penerima Bantuan adalah untuk bantuan kebajikan umum (tidak terhad kepada kategori asnaf). Kedua-duanya ada sistem kategori, had bantuan, dan workflow yang berasingan.'
                    ],
                    [
                        'question' => 'Bagaimana untuk set had kifayah dan had bantuan?',
                        'answer' => 'Pergi ke Tetapan Asnaf atau Tetapan Kebajikan, pilih TAB "Had Kifayah" atau "Had Bantuan". Masukkan nilai minimum dan maksimum untuk setiap kategori. Had ini akan digunakan untuk validasi semasa permohonan dan agihan bantuan.'
                    ],
                    [
                        'question' => 'Apakah itu tempoh bantuan dalam Kebajikan?',
                        'answer' => 'Tempoh Bantuan adalah konfigurasi berapa lama bantuan akan diberikan (contoh: 1 bulan, 3 bulan, 6 bulan, 1 tahun). Ia boleh dikonfigurasi dalam Tetapan Kebajikan dan akan digunakan untuk calculate tarikh tamat bantuan.'
                    ]
                ]
            ],
            [
                'category' => 'Modul AJK Masjid',
                'icon' => 'badge',
                'color' => 'cyan',
                'questions' => [
                    [
                        'question' => 'Apakah itu modul AJK?',
                        'answer' => 'Modul AJK (Ahli Jawatankuasa Masjid) adalah untuk pengurusan ahli jawatankuasa masjid termasuk maklumat peribadi, jawatan, tempoh perkhidmatan, dan status. Ia mengandungi 3 sub-modul: AJK Management, AJK Arkib, dan AJK Laporan.'
                    ],
                    [
                        'question' => 'Apakah perbezaan antara AJK Management dan AJK Arkib?',
                        'answer' => 'AJK Management menunjukkan ahli jawatankuasa yang aktif (active members), manakala AJK Arkib menunjukkan ahli jawatankuasa yang tidak aktif lagi (inactive members). Ini memudahkan pengurusan rekod historical AJK.'
                    ],
                    [
                        'question' => 'Apakah itu AJK Laporan?',
                        'answer' => 'AJK Laporan adalah laporan view-only yang menunjukkan statistik dan maklumat lengkap tentang ahli jawatankuasa masjid. Ia termasuk breakdown by jawatan, tempoh perkhidmatan, dan status keahlian.'
                    ],
                    [
                        'question' => 'Bagaimana untuk archive ahli AJK?',
                        'answer' => 'Dalam AJK Management, tukar status ahli kepada "Inactive" atau "Suspended". Ahli tersebut akan automatically muncul dalam AJK Arkib dan tidak lagi ditunjukkan dalam senarai aktif.'
                    ],
                    [
                        'question' => 'Bolehkah saya restore ahli AJK dari arkib?',
                        'answer' => 'Ya, pergi ke AJK Arkib, cari ahli yang ingin di-restore, dan tukar status kembali kepada "Active". Ahli tersebut akan kembali muncul dalam AJK Management.'
                    ]
                ]
            ],
            [
                'category' => 'Carian dan Peta',
                'icon' => 'map',
                'color' => 'teal',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk melihat lokasi masjid di peta?',
                        'answer' => 'Pada senarai masjid, klik butang "Lihat di Maps" untuk membuka lokasi masjid di Google Maps dalam tab baru. Pastikan koordinat GPS telah dimasukkan dengan betul.'
                    ],
                    [
                        'question' => 'Bolehkah saya cari masjid berdasarkan lokasi?',
                        'answer' => 'Ya, sistem menyokong carian berdasarkan negeri dan daerah. Anda juga boleh menggunakan fungsi carian umum untuk mencari berdasarkan nama atau alamat masjid.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mengemaskini koordinat GPS masjid?',
                        'answer' => 'Edit maklumat masjid dan masukkan koordinat latitude dan longitude yang tepat. Anda boleh dapatkan koordinat ini dari Google Maps atau aplikasi GPS lain.'
                    ]
                ]
            ],
            [
                'category' => 'Lampiran & Dokumen',
                'icon' => 'attach_file',
                'color' => 'purple',
                'questions' => [
                    [
                        'question' => 'Jenis fail apa yang boleh dimuat naik sebagai lampiran?',
                        'answer' => 'Sistem menerima fail PDF, JPG, PNG, dan JPEG. Saiz maksimum setiap fail adalah 10MB. Anda boleh memuat naik dokumen seperti sijil pendaftaran, pelan bangunan, atau gambar masjid.'
                    ],
                    [
                        'question' => 'Bagaimana untuk melihat atau memuat turun lampiran?',
                        'answer' => 'Pada halaman maklumat masjid, klik butang "Lihat" untuk membuka lampiran dalam tab baru, atau klik "Muat Turun" untuk menyimpan fail ke komputer anda.'
                    ],
                    [
                        'question' => 'Bolehkah saya padam lampiran yang telah dimuat naik?',
                        'answer' => 'Ya, pada halaman edit masjid, anda boleh padam lampiran sedia ada dengan mengklik butang "Padam" di sebelah nama fail.'
                    ]
                ]
            ],
            [
                'category' => 'Status Sistem',
                'icon' => 'monitor_heart',
                'color' => 'red',
                'questions' => [
                    [
                        'question' => 'Bagaimana untuk menyemak kesihatan sistem?',
                        'answer' => 'Pergi ke menu "Bantuan & Sokongan" > "Status Sistem" untuk melihat status database, cache, storage, dan komponen sistem lain secara real-time.'
                    ],
                    [
                        'question' => 'Apakah yang perlu dilakukan jika sistem menunjukkan status "Bermasalah"?',
                        'answer' => 'Semak butiran status sistem untuk mengenal pasti komponen yang bermasalah. Jika masalah berterusan, hubungi sokongan teknikal dengan maklumat error yang ditunjukkan.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mengemaskini status sistem?',
                        'answer' => 'Klik butang "Kemaskini" pada halaman Status Sistem untuk mendapatkan status terkini semua komponen sistem.'
                    ],
                    [
                        'question' => 'Bagaimana untuk semak status integrasi eksternal?',
                        'answer' => 'Status integrasi seperti email SMTP, weather API, dan external services boleh disemak dalam Status Sistem. Jika ada masalah connectivity, ia akan ditunjukkan dalam health checks.'
                    ],
                    [
                        'question' => 'Mengapa weather widget tidak menunjukkan data?',
                        'answer' => 'Semak Status Sistem untuk connectivity issues. Pastikan weather API key valid dan tidak exceed rate limits. Jika masalah berterusan, sistem akan menggunakan fallback data.'
                    ]
                ]
            ],
            [
                'category' => 'Teknikal',
                'icon' => 'build',
                'color' => 'amber',
                'questions' => [
                    [
                        'question' => 'Mengapa sistem kadang-kadang lambat?',
                        'answer' => 'Prestasi sistem bergantung pada status cache, database, dan queue. Semak Status Sistem untuk memastikan semua komponen berfungsi dengan baik. Cache yang tidak optimum boleh menjejaskan prestasi.'
                    ],
                    [
                        'question' => 'Bagaimana untuk backup data masjid?',
                        'answer' => 'Sistem melakukan backup automatik secara berkala. Untuk backup manual, hubungi pentadbir sistem. Data disimpan dengan selamat dan boleh dipulihkan jika diperlukan.'
                    ],
                    [
                        'question' => 'Apakah yang perlu dilakukan jika tidak dapat login?',
                        'answer' => 'Pastikan email dan kata laluan betul. Jika masih tidak dapat login, hubungi pentadbir sistem untuk reset kata laluan atau semak status akaun anda.'
                    ],
                    [
                        'question' => 'Mengapa saya mendapat error "Trying to access array offset on int"?',
                        'answer' => 'Error ini telah diperbaiki dalam v1.3. Ia disebabkan oleh format data yang tidak konsisten antara controller dan component. Sistem kini menggunakan format array yang seragam untuk semua statistik.'
                    ],
                    [
                        'question' => 'Apakah itu middleware CheckPermission?',
                        'answer' => 'CheckPermission adalah lapisan keselamatan yang memastikan setiap request diverifikasi berdasarkan permission pengguna. Ia memastikan data isolation dan akses yang selamat kepada resources sistem.'
                    ],
                    [
                        'question' => 'Mengapa layout responsive grid berubah?',
                        'answer' => 'Sistem kini menggunakan dynamic grid columns berdasarkan bilangan kad sebenar. Ini memastikan penggunaan ruang yang optimum: mobile (1 column), tablet (2 columns), desktop (dynamic columns based on card count).'
                    ],
                    [
                        'question' => 'Apakah yang dimaksudkan dengan "masjid isolation"?',
                        'answer' => 'Masjid isolation memastikan setiap masjid hanya dapat mengakses data mereka sendiri. Ini dilaksanakan melalui filtering berdasarkan masjid_id pada semua database queries dan middleware permission checks.'
                    ],
                    [
                        'question' => 'Mengapa form integrasi tidak dapat di-edit?',
                        'answer' => 'Isu ini telah diperbaiki dalam v1.6. Pastikan anda klik butang "Edit Konfigurasi" untuk enable form fields. Jika masih bermasalah, refresh page dan cuba lagi.'
                    ],
                    [
                        'question' => 'Bagaimana data integrasi disimpan dengan selamat?',
                        'answer' => 'Semua data integrasi disimpan dalam database dengan encryption. API keys dan sensitive information tidak disimpan dalam plain text. Sistem menggunakan Laravel\'s built-in security features.'
                    ]
                ]
            ],
            [
                'category' => 'Sistem Integrasi',
                'icon' => 'integration_instructions',
                'color' => 'indigo',
                'questions' => [
                    [
                        'question' => 'Apakah itu Sistem Integrasi dalam E-Masjid v1.6?',
                        'answer' => 'Sistem Integrasi adalah hub pusat untuk mengkonfigurasi semua integrasi eksternal seperti Email (SMTP), Cuaca, dan API. Ia membolehkan masjid menyambung dengan platform luar untuk fungsi yang diperluas.'
                    ],
                    [
                        'question' => 'Bagaimana untuk mengkonfigurasi email SMTP?',
                        'answer' => 'Pergi ke menu Integrasi > tab Email. Masukkan maklumat SMTP server, port, username, password, dan pilih jenis encryption (TLS/SSL). Anda boleh test email untuk memastikan konfigurasi betul.'
                    ],
                    [
                        'question' => 'Apakah itu Weather Widget dalam navbar?',
                        'answer' => 'Weather Widget menunjukkan cuaca real-time dalam navigation bar termasuk suhu, keadaan cuaca, UV Index, kelembapan, kelajuan angin, dan ramalan cuaca. Data diambil dari provider cuaca yang dikonfigurasi.'
                    ],
                    [
                        'question' => 'Bagaimana untuk setup integrasi cuaca?',
                        'answer' => 'Pergi ke Integrasi > tab Cuaca. Pilih provider (OpenWeatherMap atau WeatherAPI), masukkan API key, set lokasi, dan konfigurasi settings lain. Weather widget akan mula menunjukkan data selepas konfigurasi siap.'
                    ],
                    [
                        'question' => 'Apakah itu API Configuration dan Token Management?',
                        'answer' => 'API Configuration membolehkan anda setup API endpoints, rate limiting, timeout, dan SSL verification. Token Management menggunakan Laravel Sanctum untuk generate, display, dan revoke API tokens dengan abilities yang specific.'
                    ],
                    [
                        'question' => 'Bagaimana untuk generate API token baharu?',
                        'answer' => 'Dalam tab API, scroll ke bahagian Token Management dan klik "Generate Token". Pilih abilities yang diperlukan dan token akan di-generate. Token akan dipaparkan dalam senarai dengan maklumat created date dan last used.'
                    ],
                    [
                        'question' => 'Mengapa UV Index tidak menunjukkan data yang betul?',
                        'answer' => 'Isu UV Index telah diperbaiki dalam v1.6. Pastikan weather configuration setup dengan betul dan API key valid. UV Index akan menunjukkan nilai real-time dari weather provider yang dipilih.'
                    ],
                    [
                        'question' => 'Apakah itu Tetapan Umum dalam integrasi?',
                        'answer' => 'Tetapan Umum mengandungi konfigurasi sistem seperti azan audio files, prayer time settings, dan system preferences. Ia membolehkan customization pengalaman masjid mengikut keperluan tempatan.'
                    ],
                    [
                        'question' => 'Bagaimana system version di-update secara automatik?',
                        'answer' => 'System version dalam footer kini sync dengan release notes. Apabila versi baharu dikeluarkan, version number akan auto-update untuk menunjukkan versi semasa yang sedang digunakan.'
                    ],
                    [
                        'question' => 'Adakah integrasi ini selamat untuk digunakan?',
                        'answer' => 'Ya, semua integrasi menggunakan encryption dan secure connections. API keys disimpan dengan selamat, SMTP menggunakan TLS/SSL, dan token management menggunakan Laravel Sanctum yang industry-standard untuk keselamatan API.'
                    ]
                ]
            ]
        ];

        return view('bantuan.faq', compact('user', 'pageTitle', 'faqs'));
    }
}
