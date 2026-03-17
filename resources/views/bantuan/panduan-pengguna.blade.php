<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Pengguna - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" style="font-family: 'Poppins', sans-serif;" x-data="userGuide()">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Panduan Pengguna</h1>
                    <p class="text-xs text-gray-600">Panduan lengkap untuk menggunakan Sistem E-Masjid v{{ \App\Models\Tetapan::getSystemVersion() }}</p>
                </div>

                <!-- Quick Navigation -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-sm p-4">
                    <h2 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                        <span class="material-icons text-blue-600 mr-2" style="font-size: 16px;">navigation</span>
                        Navigasi Pantas
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2">
                        <button @click="scrollToSection('getting-started')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">play_arrow</span>
                            <span>Bermula</span>
                        </button>
                        <button @click="scrollToSection('dashboard')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">dashboard</span>
                            <span>Dashboard</span>
                        </button>
                        <button @click="scrollToSection('kariah')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">people</span>
                            <span>Kariah & AJK</span>
                        </button>
                        <button @click="scrollToSection('asnaf-kebajikan')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">volunteer_activism</span>
                            <span>Asnaf & Kebajikan</span>
                        </button>
                        <button @click="scrollToSection('kewangan')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">account_balance</span>
                            <span>Kewangan</span>
                        </button>
                        <button @click="scrollToSection('integrations')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">integration_instructions</span>
                            <span>Integrasi</span>
                        </button>
                        <button @click="scrollToSection('management')" class="flex items-center justify-center text-blue-700 hover:text-blue-900 p-3 hover:bg-blue-100 rounded-sm transition-colors" style="font-size: 13px;">
                            <span class="material-icons mr-2" style="font-size: 20px;">settings</span>
                            <span>Pengurusan</span>
                        </button>
                    </div>
                </div>

                <!-- Getting Started Section -->
                <div id="getting-started" class="mb-8">
                    <div class="bg-green-50 border border-green-200 rounded-sm overflow-hidden">
                        <div class="bg-green-100 px-4 py-3 border-b border-green-200">
                            <h2 class="text-sm font-semibold text-green-800 flex items-center">
                                <span class="material-icons text-green-600 mr-2" style="font-size: 16px;">play_arrow</span>
                                Bermula dengan E-Masjid
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">login</span>
                                    1. Login ke Sistem
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Gunakan email dan kata laluan yang diberikan oleh pentadbir sistem untuk login.</p>
                                <ul class="text-xs text-gray-600 space-y-1 ml-4">
                                    <li>• Pastikan email telah disahkan oleh pentadbir</li>
                                    <li>• Role anda mesti dalam status aktif</li>
                                    <li>• Hubungi pentadbir jika tidak dapat login</li>
                                </ul>
                            </div>

                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">account_circle</span>
                                    2. Memahami Role & Permissions
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Sistem menggunakan role-based access control:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-purple-50 p-3 rounded-sm border border-purple-200">
                                        <h4 class="text-xs font-medium text-purple-800 mb-1">Super Admin</h4>
                                        <p class="text-xs text-purple-700">Akses penuh ke semua masjid dan data sistem</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Admin Masjid</h4>
                                        <p class="text-xs text-blue-700">Akses terhad kepada data masjid sendiri sahaja</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">explore</span>
                                    3. Navigasi Sistem
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Gunakan menu navigasi untuk akses modul-modul utama:</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">dashboard</span>Dashboard
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">mosque</span>Senarai Masjid
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">people</span>Senarai Pengguna
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">groups</span>Senarai Kumpulan
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">integration_instructions</span>Integrasi
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <span class="material-icons text-xs mr-1">help</span>Bantuan & Sokongan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Section -->
                <div id="dashboard" class="mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-sm overflow-hidden">
                        <div class="bg-blue-100 px-4 py-3 border-b border-blue-200">
                            <h2 class="text-sm font-semibold text-blue-800 flex items-center">
                                <span class="material-icons text-blue-600 mr-2" style="font-size: 16px;">dashboard</span>
                                Menggunakan Dashboard
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="bg-white rounded-sm border border-blue-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-blue-600 mr-2" style="font-size: 14px;">analytics</span>
                                    Kad Statistik
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Dashboard menunjukkan statistik real-time berdasarkan role anda:</p>
                                <ul class="text-xs text-gray-600 space-y-1 ml-4">
                                    <li>• <strong>Super Admin:</strong> Melihat statistik semua masjid dalam sistem</li>
                                    <li>• <strong>Admin Masjid:</strong> Melihat statistik masjid sendiri sahaja</li>
                                    <li>• Kad dinamik - hanya menunjukkan data yang relevan</li>
                                    <li>• Auto-refresh untuk data terkini</li>
                                </ul>
                            </div>

                            <div class="bg-white rounded-sm border border-blue-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-blue-600 mr-2" style="font-size: 14px;">wb_sunny</span>
                                    Weather Widget
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Widget cuaca dalam navbar (desktop sahaja) menunjukkan:</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Suhu semasa</li>
                                        <li>• Keadaan cuaca</li>
                                        <li>• UV Index</li>
                                    </ul>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Kelembapan</li>
                                        <li>• Kelajuan angin</li>
                                        <li>• Ramalan cuaca</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Integrations Section -->
                <div id="integrations" class="mb-8">
                    <div class="bg-indigo-50 border border-indigo-200 rounded-sm overflow-hidden">
                        <div class="bg-indigo-100 px-4 py-3 border-b border-indigo-200">
                            <h2 class="text-sm font-semibold text-indigo-800 flex items-center">
                                <span class="material-icons text-indigo-600 mr-2" style="font-size: 16px;">integration_instructions</span>
                                Sistem Integrasi
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Email Integration -->
                            <div class="bg-white rounded-sm border border-indigo-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-indigo-600 mr-2" style="font-size: 14px;">email</span>
                                    Integrasi Email (SMTP)
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Setup email untuk sistem notifications dan communications:</p>
                                <div class="bg-gray-50 p-3 rounded-sm mb-2">
                                    <h4 class="text-xs font-medium text-gray-800 mb-1">Langkah Setup:</h4>
                                    <ol class="text-xs text-gray-600 space-y-1 ml-4 list-decimal">
                                        <li>Pergi ke menu <strong>Integrasi</strong> > tab <strong>Email</strong></li>
                                        <li>Klik <strong>"Edit Konfigurasi"</strong></li>
                                        <li>Masukkan maklumat SMTP server (host, port, username, password)</li>
                                        <li>Pilih encryption type (TLS/SSL)</li>
                                        <li>Set From Name dan Reply-To address</li>
                                        <li>Klik <strong>"Test Email"</strong> untuk verify setup</li>
                                        <li>Simpan konfigurasi</li>
                                    </ol>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="bg-green-50 p-2 rounded-sm border border-green-200">
                                        <strong class="text-green-800">Supported:</strong>
                                        <ul class="text-green-700 mt-1 space-y-1">
                                            <li>• Gmail SMTP</li>
                                            <li>• Outlook/Hotmail</li>
                                            <li>• Custom SMTP servers</li>
                                        </ul>
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded-sm border border-blue-200">
                                        <strong class="text-blue-800">Features:</strong>
                                        <ul class="text-blue-700 mt-1 space-y-1">
                                            <li>• TLS/SSL encryption</li>
                                            <li>• Test email function</li>
                                            <li>• Custom from name</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Weather Integration -->
                            <div class="bg-white rounded-sm border border-indigo-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-indigo-600 mr-2" style="font-size: 14px;">wb_sunny</span>
                                    Integrasi Cuaca
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Setup weather data untuk navbar widget dan system information:</p>
                                <div class="bg-gray-50 p-3 rounded-sm mb-2">
                                    <h4 class="text-xs font-medium text-gray-800 mb-1">Langkah Setup:</h4>
                                    <ol class="text-xs text-gray-600 space-y-1 ml-4 list-decimal">
                                        <li>Pergi ke menu <strong>Integrasi</strong> > tab <strong>Cuaca</strong></li>
                                        <li>Pilih weather provider (OpenWeatherMap atau WeatherAPI)</li>
                                        <li>Masukkan API key dari provider</li>
                                        <li>Set lokasi (latitude, longitude atau city name)</li>
                                        <li>Configure refresh interval dan units</li>
                                        <li>Test connection untuk verify setup</li>
                                        <li>Simpan konfigurasi</li>
                                    </ol>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-sm border border-yellow-200">
                                    <h4 class="text-xs font-medium text-yellow-800 mb-1 flex items-center">
                                        <span class="material-icons text-yellow-600 mr-1" style="font-size: 12px;">info</span>
                                        Weather Widget Features:
                                    </h4>
                                    <ul class="text-xs text-yellow-700 space-y-1">
                                        <li>• Real-time temperature display dalam navbar</li>
                                        <li>• Detailed tooltip dengan UV Index, humidity, wind speed</li>
                                        <li>• Weather condition icons yang dynamic</li>
                                        <li>• Fallback data jika API tidak available</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- API Integration -->
                            <div class="bg-white rounded-sm border border-indigo-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-indigo-600 mr-2" style="font-size: 14px;">api</span>
                                    Konfigurasi API & Token Management
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Setup API endpoints dan manage authentication tokens:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-gray-50 p-3 rounded-sm">
                                        <h4 class="text-xs font-medium text-gray-800 mb-1">API Configuration:</h4>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• Base URL dan version settings</li>
                                            <li>• Rate limiting controls</li>
                                            <li>• Timeout dan retry settings</li>
                                            <li>• SSL verification options</li>
                                            <li>• Logging level configuration</li>
                                        </ul>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-sm">
                                        <h4 class="text-xs font-medium text-gray-800 mb-1">Token Management:</h4>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• Generate API tokens</li>
                                            <li>• Set token abilities/permissions</li>
                                            <li>• View active tokens list</li>
                                            <li>• Revoke tokens when needed</li>
                                            <li>• Token expiry management</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Section -->
                <div id="management" class="mb-8">
                    <div class="bg-purple-50 border border-purple-200 rounded-sm overflow-hidden">
                        <div class="bg-purple-100 px-4 py-3 border-b border-purple-200">
                            <h2 class="text-sm font-semibold text-purple-800 flex items-center">
                                <span class="material-icons text-purple-600 mr-2" style="font-size: 16px;">settings</span>
                                Pengurusan Sistem
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Masjid Management -->
                            <div class="bg-white rounded-sm border border-purple-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-purple-600 mr-2" style="font-size: 14px;">mosque</span>
                                    Pengurusan Masjid
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <h4 class="text-xs font-medium text-gray-800 mb-1">Tambah Masjid Baharu:</h4>
                                        <ul class="text-xs text-gray-600 space-y-1 ml-2">
                                            <li>1. Klik "Tambah Masjid" dalam Senarai Masjid</li>
                                            <li>2. Isi maklumat asas (nama, alamat, telefon)</li>
                                            <li>3. Set koordinat GPS untuk peta</li>
                                            <li>4. Upload dokumen sokongan</li>
                                            <li>5. Submit untuk approval</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-medium text-gray-800 mb-1">Edit Maklumat:</h4>
                                        <ul class="text-xs text-gray-600 space-y-1 ml-2">
                                            <li>• Klik butang "Edit" pada senarai</li>
                                            <li>• Update maklumat yang diperlukan</li>
                                            <li>• Tambah/buang lampiran</li>
                                            <li>• Simpan perubahan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- User Management -->
                            <div class="bg-white rounded-sm border border-purple-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-purple-600 mr-2" style="font-size: 14px;">people</span>
                                    Pengurusan Pengguna
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Tambah Pengguna:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Set nama dan email</li>
                                            <li>• Assign role yang sesuai</li>
                                            <li>• Link dengan masjid</li>
                                            <li>• Send invitation email</li>
                                        </ul>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-sm border border-green-200">
                                        <h4 class="text-xs font-medium text-green-800 mb-1">Verify Pengguna:</h4>
                                        <ul class="text-xs text-green-700 space-y-1">
                                            <li>• Review pending users</li>
                                            <li>• Verify email addresses</li>
                                            <li>• Activate user accounts</li>
                                            <li>• Set appropriate permissions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Management -->
                            <div class="bg-white rounded-sm border border-purple-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-purple-600 mr-2" style="font-size: 14px;">groups</span>
                                    Pengurusan Kumpulan & Role
                                </h3>
                                <div class="bg-gray-50 p-3 rounded-sm">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <h4 class="text-xs font-medium text-gray-800 mb-1">System Roles:</h4>
                                            <ul class="text-xs text-gray-600 space-y-1">
                                                <li>• <strong>Super Admin:</strong> Full system access</li>
                                                <li>• <strong>Admin Masjid:</strong> Masjid-specific access</li>
                                                <li>• Cannot be modified</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-medium text-gray-800 mb-1">Custom Roles:</h4>
                                            <ul class="text-xs text-gray-600 space-y-1">
                                                <li>• Create roles untuk keperluan khusus</li>
                                                <li>• Set specific permissions</li>
                                                <li>• Assign kepada users</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kariah & AJK Section -->
                <div id="kariah" class="mb-8">
                    <div class="bg-cyan-50 border border-cyan-200 rounded-sm overflow-hidden">
                        <div class="bg-cyan-100 px-4 py-3 border-b border-cyan-200">
                            <h2 class="text-sm font-semibold text-cyan-800 flex items-center">
                                <span class="material-icons text-cyan-600 mr-2" style="font-size: 16px;">people</span>
                                Ahli Kariah & AJK Masjid
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Ahli Kariah -->
                            <div class="bg-white rounded-sm border border-cyan-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-cyan-600 mr-2" style="font-size: 14px;">person_add</span>
                                    Pengurusan Ahli Kariah
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Modul untuk menguruskan data ahli kariah masjid:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Tambah Kariah:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Maklumat peribadi (nama, IC, alamat)</li>
                                            <li>• Maklumat hubungan (telefon, email)</li>
                                            <li>• Status keahlian</li>
                                            <li>• Upload dokumen sokongan</li>
                                        </ul>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-sm border border-green-200">
                                        <h4 class="text-xs font-medium text-green-800 mb-1">Workflow:</h4>
                                        <ul class="text-xs text-green-700 space-y-1">
                                            <li>• Approve/Reject permohonan</li>
                                            <li>• Suspend/Reactivate keahlian</li>
                                            <li>• Update status ahli</li>
                                            <li>• Generate laporan kariah</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- AJK Masjid -->
                            <div class="bg-white rounded-sm border border-cyan-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-cyan-600 mr-2" style="font-size: 14px;">badge</span>
                                    Ahli Jawatankuasa Masjid (AJK)
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Pengurusan ahli jawatankuasa masjid dengan 3 sub-modul:</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="bg-purple-50 p-3 rounded-sm border border-purple-200">
                                        <h4 class="text-xs font-medium text-purple-800 mb-1">AJK Management:</h4>
                                        <ul class="text-xs text-purple-700 space-y-1">
                                            <li>• Senarai AJK aktif</li>
                                            <li>• Maklumat jawatan</li>
                                            <li>• Tempoh perkhidmatan</li>
                                            <li>• Status keahlian</li>
                                        </ul>
                                    </div>
                                    <div class="bg-orange-50 p-3 rounded-sm border border-orange-200">
                                        <h4 class="text-xs font-medium text-orange-800 mb-1">AJK Arkib:</h4>
                                        <ul class="text-xs text-orange-700 space-y-1">
                                            <li>• Rekod AJK tidak aktif</li>
                                            <li>• Historical data</li>
                                            <li>• Archive management</li>
                                            <li>• Restore functionality</li>
                                        </ul>
                                    </div>
                                    <div class="bg-teal-50 p-3 rounded-sm border border-teal-200">
                                        <h4 class="text-xs font-medium text-teal-800 mb-1">AJK Laporan:</h4>
                                        <ul class="text-xs text-teal-700 space-y-1">
                                            <li>• Statistik AJK</li>
                                            <li>• Breakdown by jawatan</li>
                                            <li>• Tempoh perkhidmatan</li>
                                            <li>• View-only reports</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asnaf & Kebajikan Section -->
                <div id="asnaf-kebajikan" class="mb-8">
                    <div class="bg-pink-50 border border-pink-200 rounded-sm overflow-hidden">
                        <div class="bg-pink-100 px-4 py-3 border-b border-pink-200">
                            <h2 class="text-sm font-semibold text-pink-800 flex items-center">
                                <span class="material-icons text-pink-600 mr-2" style="font-size: 16px;">volunteer_activism</span>
                                Modul Asnaf & Kebajikan
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Asnaf Module -->
                            <div class="bg-white rounded-sm border border-pink-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-pink-600 mr-2" style="font-size: 14px;">mosque</span>
                                    Modul Asnaf (Zakat)
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Pengurusan bantuan zakat dengan 5 sub-modul:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Asnaf & Permohonan:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Data asnaf (8 kategori)</li>
                                            <li>• Permohonan Zakat dengan workflow</li>
                                            <li>• Approve/Reject permohonan</li>
                                            <li>• Agihan Zakat kepada asnaf</li>
                                        </ul>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-sm border border-green-200">
                                        <h4 class="text-xs font-medium text-green-800 mb-1">Laporan & Tetapan:</h4>
                                        <ul class="text-xs text-green-700 space-y-1">
                                            <li>• Laporan Zakat (view-only)</li>
                                            <li>• Tetapan Had Kifayah</li>
                                            <li>• Tetapan Had Bantuan</li>
                                            <li>• Workflow & Kategori settings</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Kebajikan Module -->
                            <div class="bg-white rounded-sm border border-pink-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-pink-600 mr-2" style="font-size: 14px;">favorite</span>
                                    Modul Kebajikan
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Pengurusan bantuan kebajikan umum dengan 6 sub-modul:</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="bg-purple-50 p-3 rounded-sm border border-purple-200">
                                        <h4 class="text-xs font-medium text-purple-800 mb-1">Program & Penerima:</h4>
                                        <ul class="text-xs text-purple-700 space-y-1">
                                            <li>• Program Kebajikan</li>
                                            <li>• Penerima Bantuan</li>
                                            <li>• Kategori penerima</li>
                                            <li>• Data management</li>
                                        </ul>
                                    </div>
                                    <div class="bg-orange-50 p-3 rounded-sm border border-orange-200">
                                        <h4 class="text-xs font-medium text-orange-800 mb-1">Permohonan & Bayaran:</h4>
                                        <ul class="text-xs text-orange-700 space-y-1">
                                            <li>• Permohonan Bantuan</li>
                                            <li>• Pembayaran Bantuan</li>
                                            <li>• Workflow system</li>
                                            <li>• Payment tracking</li>
                                        </ul>
                                    </div>
                                    <div class="bg-teal-50 p-3 rounded-sm border border-teal-200">
                                        <h4 class="text-xs font-medium text-teal-800 mb-1">Laporan & Tetapan:</h4>
                                        <ul class="text-xs text-teal-700 space-y-1">
                                            <li>• Laporan Kebajikan</li>
                                            <li>• Tetapan Had Bantuan</li>
                                            <li>• Tempoh Bantuan</li>
                                            <li>• Workflow & Kategori</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kewangan Section -->
                <div id="kewangan" class="mb-8">
                    <div class="bg-green-50 border border-green-200 rounded-sm overflow-hidden">
                        <div class="bg-green-100 px-4 py-3 border-b border-green-200">
                            <h2 class="text-sm font-semibold text-green-800 flex items-center">
                                <span class="material-icons text-green-600 mr-2" style="font-size: 16px;">account_balance</span>
                                Modul Kewangan
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Akaun Bank & Transaksi -->
                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">account_balance_wallet</span>
                                    Akaun Bank & Transaksi Kewangan
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Akaun Bank:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Pengurusan akaun bank masjid</li>
                                            <li>• Maklumat bank (nama, no akaun)</li>
                                            <li>• Baki awal dan semasa</li>
                                            <li>• Status akaun</li>
                                        </ul>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-sm border border-green-200">
                                        <h4 class="text-xs font-medium text-green-800 mb-1">Transaksi Kewangan:</h4>
                                        <ul class="text-xs text-green-700 space-y-1">
                                            <li>• 4 form Pendapatan (Kariah, Derma, Zakat, Lain)</li>
                                            <li>• 4 form Perbelanjaan (Utiliti, Penyelenggaraan, Gaji, Lain)</li>
                                            <li>• Kategori integration (Jenis Derma, Jenis Bil)</li>
                                            <li>• Historical balance calculation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Laporan Kewangan -->
                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">assessment</span>
                                    Laporan Kewangan (8 TABs)
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Laporan kewangan comprehensive dengan 8 TABs:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                    <div class="bg-purple-50 p-2 rounded-sm border border-purple-200 text-purple-700">
                                        <strong>1.</strong> Penyata Kewangan
                                    </div>
                                    <div class="bg-blue-50 p-2 rounded-sm border border-blue-200 text-blue-700">
                                        <strong>2.</strong> Laporan Pendapatan
                                    </div>
                                    <div class="bg-red-50 p-2 rounded-sm border border-red-200 text-red-700">
                                        <strong>3.</strong> Laporan Perbelanjaan
                                    </div>
                                    <div class="bg-teal-50 p-2 rounded-sm border border-teal-200 text-teal-700">
                                        <strong>4.</strong> Aliran Tunai
                                    </div>
                                    <div class="bg-green-50 p-2 rounded-sm border border-green-200 text-green-700">
                                        <strong>5.</strong> Penyata P&P
                                    </div>
                                    <div class="bg-orange-50 p-2 rounded-sm border border-orange-200 text-orange-700">
                                        <strong>6.</strong> Perbandingan Bulanan
                                    </div>
                                    <div class="bg-pink-50 p-2 rounded-sm border border-pink-200 text-pink-700">
                                        <strong>7.</strong> Laporan Kategori
                                    </div>
                                    <div class="bg-indigo-50 p-2 rounded-sm border border-indigo-200 text-indigo-700">
                                        <strong>8.</strong> Baki Bank
                                    </div>
                                </div>
                                <div class="mt-3 bg-yellow-50 p-3 rounded-sm border border-yellow-200">
                                    <h4 class="text-xs font-medium text-yellow-800 mb-1 flex items-center">
                                        <span class="material-icons text-yellow-600 mr-1" style="font-size: 12px;">info</span>
                                        Features:
                                    </h4>
                                    <ul class="text-xs text-yellow-700 space-y-1">
                                        <li>• Super Admin boleh filter by masjid</li>
                                        <li>• TAB-level permissions untuk granular access</li>
                                        <li>• Charts dan visualizations</li>
                                        <li>• Export capabilities</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Tetapan Kewangan -->
                            <div class="bg-white rounded-sm border border-green-200 p-4">
                                <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 14px;">settings</span>
                                    Tetapan Kewangan
                                </h3>
                                <p class="text-xs text-gray-700 mb-2">Konfigurasi kategori kewangan:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-sm border border-blue-200">
                                        <h4 class="text-xs font-medium text-blue-800 mb-1">Kategori Pendapatan:</h4>
                                        <ul class="text-xs text-blue-700 space-y-1">
                                            <li>• Derma Umum, Kutipan Jumaat</li>
                                            <li>• Zakat Fitrah, Yuran Kariah</li>
                                            <li>• Custom categories</li>
                                            <li>• Jenis Derma (sub-category)</li>
                                        </ul>
                                    </div>
                                    <div class="bg-red-50 p-3 rounded-sm border border-red-200">
                                        <h4 class="text-xs font-medium text-red-800 mb-1">Kategori Perbelanjaan:</h4>
                                        <ul class="text-xs text-red-700 space-y-1">
                                            <li>• Utiliti, Penyelenggaraan</li>
                                            <li>• Gaji & Elaun, Operasi</li>
                                            <li>• Custom categories</li>
                                            <li>• Jenis Bil (sub-category)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips & Best Practices -->
                <div class="mb-8">
                    <div class="bg-amber-50 border border-amber-200 rounded-sm overflow-hidden">
                        <div class="bg-amber-100 px-4 py-3 border-b border-amber-200">
                            <h2 class="text-sm font-semibold text-amber-800 flex items-center">
                                <span class="material-icons text-amber-600 mr-2" style="font-size: 16px;">lightbulb</span>
                                Tips & Best Practices
                            </h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-sm border border-amber-200 p-4">
                                    <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="material-icons text-amber-600 mr-2" style="font-size: 14px;">security</span>
                                        Keselamatan
                                    </h3>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Gunakan kata laluan yang kuat</li>
                                        <li>• Logout selepas selesai menggunakan</li>
                                        <li>• Jangan share login credentials</li>
                                        <li>• Report suspicious activities</li>
                                        <li>• Keep API keys confidential</li>
                                    </ul>
                                </div>
                                <div class="bg-white rounded-sm border border-amber-200 p-4">
                                    <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="material-icons text-amber-600 mr-2" style="font-size: 14px;">speed</span>
                                        Performance
                                    </h3>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Refresh page jika data tidak update</li>
                                        <li>• Clear browser cache jika ada issues</li>
                                        <li>• Use search function untuk cari data</li>
                                        <li>• Check Status Sistem untuk health</li>
                                        <li>• Report slow performance issues</li>
                                    </ul>
                                </div>
                                <div class="bg-white rounded-sm border border-amber-200 p-4">
                                    <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="material-icons text-amber-600 mr-2" style="font-size: 14px;">backup</span>
                                        Data Management
                                    </h3>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Backup important data regularly</li>
                                        <li>• Verify data sebelum delete</li>
                                        <li>• Use proper file formats untuk upload</li>
                                        <li>• Keep file sizes reasonable</li>
                                        <li>• Organize data systematically</li>
                                    </ul>
                                </div>
                                <div class="bg-white rounded-sm border border-amber-200 p-4">
                                    <h3 class="text-xs font-semibold text-gray-900 mb-2 flex items-center">
                                        <span class="material-icons text-amber-600 mr-2" style="font-size: 14px;">help</span>
                                        Mendapatkan Bantuan
                                    </h3>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li>• Check FAQ untuk soalan common</li>
                                        <li>• Use Status Sistem untuk troubleshoot</li>
                                        <li>• Contact support dengan error details</li>
                                        <li>• Check Nota Keluaran untuk updates</li>
                                        <li>• Join user community discussions</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="text-center">
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <span class="material-icons text-gray-600 mb-2 text-2xl">info</span>
                        <p class="text-xs font-semibold text-gray-800 mb-2">Panduan Lengkap E-Masjid v{{ \App\Models\Tetapan::getSystemVersion() }}</p>
                        <p class="text-xs text-gray-600 mb-2">Untuk maklumat lanjut, sila rujuk FAQ, Status Sistem, atau hubungi sokongan teknikal.</p>
                        <div class="flex justify-center space-x-4 text-xs">
                            <a href="{{ route('bantuan.faq') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                <span class="material-icons text-xs mr-1">help</span>FAQ
                            </a>
                            <a href="{{ route('bantuan.status-sistem') }}" class="text-green-600 hover:text-green-800 flex items-center">
                                <span class="material-icons text-xs mr-1">monitor_heart</span>Status Sistem
                            </a>
                            <a href="{{ route('bantuan.nota-keluaran') }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                                <span class="material-icons text-xs mr-1">new_releases</span>Nota Keluaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function userGuide() {
            return {
                scrollToSection(sectionId) {
                    const element = document.getElementById(sectionId);
                    if (element) {
                        element.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            }
        }
    </script>
</body>
</html>
