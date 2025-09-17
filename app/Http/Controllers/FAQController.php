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
                        'question' => 'Apakah yang baharu dalam versi 1.3?',
                        'answer' => 'Versi 1.3 memperkenalkan sistem kebenaran yang komprehensif, pengasingan data multi-masjid, kad statistik dinamik, dan penambahbaikan UI yang signifikan untuk pengalaman pengguna yang lebih baik.'
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
                    ]
                ]
            ]
        ];

        return view('bantuan.faq', compact('user', 'pageTitle', 'faqs'));
    }
}
