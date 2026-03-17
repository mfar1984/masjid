<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tiket #TKT-001 - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        .status-open { background: #FEF3C7; color: #92400E; }
        .status-in-progress { background: #DBEAFE; color: #1E40AF; }
        .status-resolved { background: #D1FAE5; color: #065F46; }
        .status-closed { background: #F3F4F6; color: #374151; }
        
        .priority-urgent { background: #FEE2E2; color: #991B1B; }
        .priority-high { background: #FED7AA; color: #9A3412; }
        .priority-medium { background: #DBEAFE; color: #1E40AF; }
        .priority-low { background: #D1FAE5; color: #065F46; }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #E5E7EB;
        }
        
        .timeline-item:last-child::before {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="auth()->user()" />

    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Ticket Detail Container -->
            <div class="bg-white shadow-lg border-x border-gray-200">
                <!-- Header Section - Ticket Detail Style -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <span class="material-icons text-2xl text-orange-600">confirmation_number</span>
                                <h1 class="text-xl font-semibold text-gray-900">Tiket #TKT-001</h1>
                            </div>
                        </div>

                        <!-- Status and Actions -->
                        <div class="flex items-center space-x-3">
                            <span class="status-open px-3 py-1 rounded-full text-sm font-medium">Terbuka</span>
                            <span class="priority-urgent px-3 py-1 rounded-full text-sm font-medium">Urgent</span>
                        </div>
                    </div>
                    <!-- Breadcrumb -->
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="/support/dashboard" class="text-sm text-gray-500 hover:text-gray-700">Dashboard Sokongan</a></li>
                            <li><span class="text-sm text-gray-400">/</span></li>
                            <li><span class="text-sm text-gray-900">Tiket #TKT-001</span></li>
                        </ol>
                    </nav>
                </div>

                <!-- Main Content Area -->
                <div class="px-6 py-6">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Ticket Header -->
                            <div class="bg-gray-50 rounded-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center mb-2">
                                    <h1 class="text-xl font-bold text-gray-900 mr-3" style="font-family: 'Poppins', sans-serif;">
                                        Sistem tidak boleh login
                                    </h1>
                                    <span class="status-open px-3 py-1 rounded-full text-sm font-medium">Terbuka</span>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span>Tiket #TKT-001</span>
                                    <span>•</span>
                                    <span>Dibuat 2 jam lalu</span>
                                    <span>•</span>
                                    <span>Dikemaskini 30 min lalu</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="priority-urgent px-3 py-1 rounded-full text-sm font-medium">Urgent</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <span class="material-icons">more_vert</span>
                                </button>
                            </div>
                        </div>

                        <!-- Ticket Description -->
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-700">
                                Assalamualaikum. Kami menghadapi masalah dengan sistem login. Apabila admin masjid cuba untuk log masuk, 
                                sistem menunjukkan error "Invalid credentials" walaupun password yang betul telah dimasukkan.
                            </p>
                            <p class="text-gray-700 mt-3">
                                Masalah ini bermula sejak pagi tadi sekitar jam 9:00 AM. Kami telah cuba:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 mt-2">
                                <li>Reset password beberapa kali</li>
                                <li>Clear browser cache dan cookies</li>
                                <li>Cuba dengan browser yang berbeza</li>
                                <li>Semak internet connection</li>
                            </ul>
                            <p class="text-gray-700 mt-3">
                                Mohon bantuan segera kerana kami ada mesyuarat penting hari ini dan perlu akses ke sistem.
                            </p>
                        </div>

                        <!-- Attachments -->
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Lampiran</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center p-3 bg-gray-50 rounded-sm border border-gray-200">
                                    <span class="material-icons text-red-600 mr-2">picture_as_pdf</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">error-screenshot.pdf</p>
                                        <p class="text-xs text-gray-500">245 KB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>

                            <!-- Conversation Thread -->
                            <div class="bg-gray-50 rounded-sm border border-gray-200">
                        <div class="border-b border-gray-200 p-4">
                            <h3 class="font-semibold text-gray-900" style="font-family: 'Poppins', sans-serif;">
                                Perbualan
                            </h3>
                        </div>
                        <div class="p-6">
                            <!-- Timeline -->
                            <div class="space-y-6">
                                <!-- Initial Ticket Creation -->
                                <div class="timeline-item relative pl-10">
                                    <div class="absolute left-0 top-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-blue-600 text-sm">confirmation_number</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <span class="font-medium text-gray-900">Ahmad Rahman</span>
                                            <span class="text-sm text-gray-500 ml-2">membuat tiket</span>
                                            <span class="text-sm text-gray-500 ml-auto">2 jam lalu</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Tiket telah dibuat dengan keutamaan Urgent</p>
                                    </div>
                                </div>

                                <!-- Support Response -->
                                <div class="timeline-item relative pl-10">
                                    <div class="absolute left-0 top-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-green-600 text-sm">support_agent</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <span class="font-medium text-gray-900">Sarah (Support)</span>
                                            <span class="text-sm text-gray-500 ml-2">membalas</span>
                                            <span class="text-sm text-gray-500 ml-auto">1 jam lalu</span>
                                        </div>
                                        <div class="bg-gray-50 rounded-sm p-4 mt-2">
                                            <p class="text-sm text-gray-700">
                                                Terima kasih atas laporan ini. Saya akan semak sistem login untuk Masjid Al-Ikhlas. 
                                                Boleh cuba akses semula dalam 10 minit? Saya sedang reset session untuk akaun anda.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Update -->
                                <div class="timeline-item relative pl-10">
                                    <div class="absolute left-0 top-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-yellow-600 text-sm">update</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <span class="font-medium text-gray-900">Sarah (Support)</span>
                                            <span class="text-sm text-gray-500 ml-2">mengubah status</span>
                                            <span class="text-sm text-gray-500 ml-auto">30 min lalu</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Status diubah dari <span class="font-medium">Terbuka</span> ke <span class="font-medium">Dalam Proses</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reply Form -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <form class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Balas Tiket</label>
                                        <textarea rows="4" 
                                                  placeholder="Tulis balasan anda di sini..."
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                                  style="font-family: 'Poppins', sans-serif;"></textarea>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <button type="button" class="text-sm text-gray-600 hover:text-gray-800">
                                                <span class="material-icons text-sm mr-1">attach_file</span>
                                                Lampir fail
                                            </button>
                                            <select class="text-sm border border-gray-300 rounded-sm px-2 py-1">
                                                <option>Balas sahaja</option>
                                                <option>Balas & Tutup</option>
                                                <option>Balas & Selesai</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-sm text-sm font-medium hover:bg-blue-700">
                                            <span class="material-icons text-sm mr-2">send</span>
                                            Hantar Balasan
                                        </button>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Ticket Info -->
                            <div class="bg-gray-50 rounded-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-family: 'Poppins', sans-serif;">
                            Maklumat Tiket
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Status</label>
                                <select class="mt-1 w-full text-sm border border-gray-300 rounded-sm px-2 py-1">
                                    <option>Terbuka</option>
                                    <option>Dalam Proses</option>
                                    <option>Menunggu Pelanggan</option>
                                    <option>Selesai</option>
                                    <option>Ditutup</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Keutamaan</label>
                                <select class="mt-1 w-full text-sm border border-gray-300 rounded-sm px-2 py-1">
                                    <option>Rendah</option>
                                    <option>Sederhana</option>
                                    <option>Tinggi</option>
                                    <option selected>Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Kategori</label>
                                <p class="text-sm text-gray-900 mt-1">Teknikal - Login Issues</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Ditugaskan kepada</label>
                                <select class="mt-1 w-full text-sm border border-gray-300 rounded-sm px-2 py-1">
                                    <option>Sarah (Support)</option>
                                    <option>Ahmad (Tech Lead)</option>
                                    <option>Siti (Senior Support)</option>
                                </select>
                            </div>
                        </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="bg-gray-50 rounded-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-family: 'Poppins', sans-serif;">
                            Maklumat Masjid
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="material-icons text-blue-600 text-sm">mosque</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Masjid Al-Ikhlas</p>
                                    <p class="text-sm text-gray-600">Kuala Lumpur</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Admin:</span>
                                        <span class="text-gray-900">Ahmad Rahman</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="text-gray-900">admin@al-ikhlas.my</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Telefon:</span>
                                        <span class="text-gray-900">03-1234 5678</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tiket Terdahulu:</span>
                                        <span class="text-gray-900">3 tiket</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-gray-50 rounded-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-family: 'Poppins', sans-serif;">
                            Tindakan Pantas
                        </h3>
                        <div class="space-y-2">
                            <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-sm">
                                <span class="material-icons text-sm mr-2">call</span>
                                Hubungi pelanggan
                            </button>
                            <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-sm">
                                <span class="material-icons text-sm mr-2">screen_share</span>
                                Remote assistance
                            </button>
                            <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-sm">
                                <span class="material-icons text-sm mr-2">escalator_warning</span>
                                Escalate tiket
                            </button>
                            <button class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-sm">
                                <span class="material-icons text-sm mr-2">close</span>
                                Tutup tiket
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />
</body>
</html>
