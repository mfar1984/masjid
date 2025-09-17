<nav class="bg-white border-b border-gray-200" x-data="navbarData()" x-init="closeAllDropdowns()">

    <!-- Top Navbar -->
    <div class="flex items-center justify-between px-4 md:px-20 h-13 relative">
        <!-- Left Side - Hamburger Menu (Mobile) & Logo (Desktop) -->
        <div class="flex items-center">
            <!-- Hamburger Menu Button - Mobile Only -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="material-icons text-xl" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                </button>
            </div>
            
            <!-- Logo - Desktop Only -->
            <div class="hidden md:flex items-center space-x-3">
                <img src="{{ asset('images/logo.svg') }}" class="h-12 w-12 py-0" alt="Logo">
            </div>
        </div>
        
        <!-- Centered Logo for Mobile -->
        <div class="absolute left-1/2 transform -translate-x-1/2 md:hidden">
            <img src="{{ asset('images/logo.svg') }}" class="h-12 w-12 py-0" alt="Logo">
        </div>
        
        <!-- Mobile Right Side - Notifications & Help -->
        <div class="md:hidden flex items-center space-x-2">
            <!-- Help Button for Mobile -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="material-icons text-lg">help_outline</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    <div class="px-5 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Bantuan & Sokongan</h3>
                        <p class="text-2xs text-gray-500 mt-1">Dapatkan bantuan dan maklumat sistem</p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('bantuan.panduan-pengguna') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-blue-500">article</span>
                            Panduan Pengguna
                        </a>
                        <a href="{{ route('bantuan.faq') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-green-500">quiz</span>
                            Soalan Lazim (FAQ)
                        </a>
                        <a href="{{ route('bantuan.hubungi-sokongan') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-purple-500">support_agent</span>
                            Hubungi Sokongan
                        </a>
                        <a href="{{ route('bantuan.status-sistem') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-orange-500">monitor_heart</span>
                            Status Sistem
                        </a>
                        <a href="{{ route('bantuan.nota-keluaran') }}" class="flex items-center px-5 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-indigo-500">new_releases</span>
                            Nota Keluaran
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notifications for Mobile -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="material-icons text-lg">notifications</span>
                    <!-- Notification badge -->
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-2 z-50 max-h-96 overflow-y-auto">
                    <!-- Notification content -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Notifikasi</h3>
                        <p class="text-[8px] text-gray-500">Anda mempunyai 3 notifikasi baharu</p>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <!-- Notification items -->
                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="material-icons text-[10px] text-blue-500">person_add</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Ahli Kariah Baru</p>
                                    <p class="text-[8px] text-gray-500">Ahmad bin Ali telah mendaftar sebagai ahli</p>
                                    <p class="text-[8px] text-gray-400 mt-1">2 minit yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-4">
            <!-- Desktop Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="material-icons text-[10px]">notifications</span>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-2 z-50 max-h-96 overflow-y-auto">
                    <!-- Notification content -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Notifikasi</h3>
                        <p class="text-[8px] text-gray-500">Anda mempunyai 3 notifikasi baharu</p>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <!-- Notification items -->
                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="material-icons text-[10px] text-blue-500">person_add</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Ahli Kariah Baru</p>
                                    <p class="text-[8px] text-gray-500">Ahmad bin Ali telah mendaftar sebagai ahli</p>
                                    <p class="text-[8px] text-gray-400 mt-1">2 minit yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Apps Grid -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <span class="material-icons text-[10px]">apps</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-3 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Aplikasi Pantas</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-3 gap-3">
                            <a href="{{ route('kariah.index') }}" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-blue-500 mb-1">people</span>
                                <span class="text-[10px] text-gray-700">Kariah</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Help -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <span class="material-icons text-[18px]">help_outline</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-3 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900">Bantuan & Sokongan</h3>
                        <p class="text-2xs text-gray-500 mt-1">Dapatkan bantuan dan maklumat sistem</p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('bantuan.panduan-pengguna') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-blue-500">article</span>
                            Panduan Pengguna
                        </a>
                        <a href="{{ route('bantuan.faq') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-green-500">quiz</span>
                            Soalan Lazim (FAQ)
                        </a>
                        <a href="{{ route('bantuan.hubungi-sokongan') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-purple-500">support_agent</span>
                            Hubungi Sokongan
                        </a>
                        <a href="{{ route('bantuan.status-sistem') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-orange-500">monitor_heart</span>
                            Status Sistem
                        </a>
                        <a href="{{ route('bantuan.nota-keluaran') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                            <span class="material-icons text-[16px] mr-3 text-indigo-500">new_releases</span>
                            Nota Keluaran
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-xs font-medium text-gray-700 hover:text-blue-500 focus:outline-none">
                    <span class="material-icons text-[10px] mr-2">account_circle</span>
                    {{ Auth::user()->name }}
                    <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                    <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Profil</a>
                    <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Tetapan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Log Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Navbar - Hidden on Mobile -->
    <div class="hidden md:block bg-white border-t border-gray-100">
        <div class="flex space-x-6 px-20 h-12 items-center justify-between">
            <div class="flex space-x-6">
                <a href="{{ route('overview') }}" class="relative flex items-center text-xs font-normal text-gray-700 hover:text-blue-400">
                    <span class="material-icons text-[8px] mr-1 text-blue-600">dashboard</span>
                    Papan Pemuka
                </a>

                <!-- Pengurusan -->
                <div class="relative" x-data="{ pengurusanOpen: false, pengurusanTimeout: null }" @click.away="pengurusanOpen = false">
                    <button @mouseenter="clearTimeout(pengurusanTimeout); pengurusanOpen = true" @mouseleave="pengurusanTimeout = setTimeout(() => pengurusanOpen = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-green-600">fact_check</span>
                        Pengurusan
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="pengurusanOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="pengurusanOpen" @mouseenter="clearTimeout(pengurusanTimeout); pengurusanOpen = true" @mouseleave="pengurusanTimeout = setTimeout(() => pengurusanOpen = false, 200)" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="{{ route('kariah.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Ahli Kariah
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            AJK
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Asnaf & Kebajikan
                            <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                        </a>
                    </div>
                </div>

                <!-- Kewangan -->
                <div class="relative" x-data="{
                    kewanganOpen: false,
                    kewanganTimeout: null
                }"
                @click.away="kewanganOpen = false">
                    <button @mouseenter="clearTimeout(kewanganTimeout); kewanganOpen = true" @mouseleave="kewanganTimeout = setTimeout(() => kewanganOpen = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-teal-600">account_balance_wallet</span>
                        Kewangan
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="kewanganOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="kewanganOpen" @mouseenter="clearTimeout(kewanganTimeout); kewanganOpen = true" @mouseleave="kewanganTimeout = setTimeout(() => kewanganOpen = false, 200)" class="absolute top-full left-0 mt-1 w-72 bg-white rounded-md shadow-lg py-2 z-50">

                        <!-- Operasi Harian -->
                        <div class="relative" x-data="{ subOpen: false }">
                            <button @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative flex items-center justify-between">
                                <span>Operasi Harian</span>
                                <span class="material-icons text-[8px] text-gray-400">chevron_right</span>
                            </button>
                            <div x-show="subOpen" @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="absolute top-0 left-full ml-1 w-56 bg-white rounded-md shadow-lg py-2 z-60">
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Transaksi Harian
                                    <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Kutipan Dana
                                    <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Khairat Kematian
                                    <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                                </a>
                            </div>
                        </div>

                        <!-- Perakaunan -->
                        <div class="relative" x-data="{ subOpen: false }">
                            <button @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative flex items-center justify-between">
                                <span>Perakaunan</span>
                                <span class="material-icons text-[8px] text-gray-400">chevron_right</span>
                            </button>
                            <div x-show="subOpen" @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="absolute top-0 left-full ml-1 w-56 bg-white rounded-md shadow-lg py-2 z-60">
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Lejar Am
                                    <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Laporan Transaksi
                                    <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Laporan Jurnal
                                    <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Kunci Kira-Kira
                                    <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Akaun Untung Rugi
                                    <div class="absolute top-0 right-0 w-1 h-full bg-teal-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Imbangan Duga
                                    <div class="absolute top-0 right-0 w-1 h-full bg-pink-500"></div>
                                </a>
                            </div>
                        </div>

                        <!-- Pembelian -->
                        <div class="relative" x-data="{ subOpen: false }">
                            <button @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative flex items-center justify-between">
                                <span>Pembelian</span>
                                <span class="material-icons text-[8px] text-gray-400">chevron_right</span>
                            </button>
                            <div x-show="subOpen" @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="absolute top-0 left-full ml-1 w-56 bg-white rounded-md shadow-lg py-2 z-60">
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Pesanan Pembelian
                                    <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Nota Penerimaan Barang
                                    <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Invois Pembekal
                                    <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Pembelian Tunai
                                    <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Baucar Pembayaran
                                    <div class="absolute top-0 right-0 w-1 h-full bg-teal-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Pulangan Barang
                                    <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Nota Kredit Pembekal
                                    <div class="absolute top-0 right-0 w-1 h-full bg-yellow-500"></div>
                                </a>
                            </div>
                        </div>

                        <!-- Analisis & Dashboard -->
                        <div class="relative" x-data="{ subOpen: false }">
                            <button @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative flex items-center justify-between">
                                <span>Analisis & Dashboard</span>
                                <span class="material-icons text-[8px] text-gray-400">chevron_right</span>
                            </button>
                            <div x-show="subOpen" @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="absolute top-0 left-full ml-1 w-56 bg-white rounded-md shadow-lg py-2 z-60">
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Papan Pemuka Kewangan
                                    <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                                </a>
                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                                    Analisis Trend
                                    <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operasi -->
                <div class="relative" x-data="{ operasiOpen: false, operasiTimeout: null }" @click.away="operasiOpen = false">
                    <button @mouseenter="clearTimeout(operasiTimeout); operasiOpen = true" @mouseleave="operasiTimeout = setTimeout(() => operasiOpen = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-indigo-600">event</span>
                        Operasi
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="operasiOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="operasiOpen" @mouseenter="clearTimeout(operasiTimeout); operasiOpen = true" @mouseleave="operasiTimeout = setTimeout(() => operasiOpen = false, 200)" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Program & Pendidikan
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Fasiliti & Tempahan
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pengurusan Jenazah
                            <div class="absolute top-0 right-0 w-1 h-full bg-gray-500"></div>
                        </a>
                    </div>
                </div>

                <!-- Aset -->
                <div class="relative" x-data="{ open: false, timeout: null }">
                    <button @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-gray-700">inventory_2</span>
                        Aset
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Daftar Aset
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Penyelenggaraan
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pergerakan Aset
                            <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Penilaian & Susut Nilai
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Laporan Aset
                            <div class="absolute top-0 right-0 w-1 h-full bg-teal-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pelupusan Aset
                            <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                        </a>
                    </div>
                </div>

                <!-- Komunikasi -->
                <div class="relative" x-data="{ open: false, timeout: null }">
                    <button @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-rose-600">campaign</span>
                        Komunikasi
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Siaran Mesej
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Kandungan Website
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pengumuman & Berita
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                    </div>
                </div>
                
                <!-- Fail -->
                <div class="relative" x-data="{ open: false, timeout: null }">
                    <button @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-orange-600">folder</span>
                        Fail
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="clearTimeout(timeout); open = true" @mouseleave="timeout = setTimeout(() => open = false, 200)" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Pengurusan Dokumen
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Perpustakaan Digital
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Arkib & Rekod
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                    </div>
                </div>
                
                <!-- Pentadbiran Sistem -->
                <div class="relative" x-data="{ pentadbiranOpen: false, pentadbiranTimeout: null }" @click.away="pentadbiranOpen = false">
                    <button @mouseenter="clearTimeout(pentadbiranTimeout); pentadbiranOpen = true" @mouseleave="pentadbiranTimeout = setTimeout(() => pentadbiranOpen = false, 200)" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-red-600">admin_panel_settings</span>
                        Sistem
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="pentadbiranOpen ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="pentadbiranOpen" @mouseenter="clearTimeout(pentadbiranTimeout); pentadbiranOpen = true" @mouseleave="pentadbiranTimeout = setTimeout(() => pentadbiranOpen = false, 200)" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="{{ route('senarai-masjid.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Tetapan Umum
                            <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        </a>
                        @if(auth()->user()->hasPermission('masjids', 'read'))
                        <a href="{{ route('senarai-masjid.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Senarai Masjid
                            <div class="absolute top-0 right-0 w-1 h-full bg-purple-500"></div>
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('users', 'read'))
                        <a href="{{ route('senarai-pengguna.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Senarai Pengguna
                            <div class="absolute top-0 right-0 w-1 h-full bg-green-500"></div>
                        </a>
                        @endif

                        @if(auth()->user()->hasPermission('roles', 'read'))
                        <a href="{{ route('senarai-kumpulan.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Senarai Kumpulan
                            <div class="absolute top-0 right-0 w-1 h-full bg-teal-500"></div>
                        </a>
                        @endif
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Integrasi
                            <div class="absolute top-0 right-0 w-1 h-full bg-orange-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Log Audit
                            <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative">
                            Log Keselamatan
                            <div class="absolute top-0 right-0 w-1 h-full bg-yellow-500"></div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Weather Widget - Hidden on Mobile -->
            <div x-data="weatherWidget()" x-init="fetchWeather()" class="relative hidden md:block">
                <div @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="flex items-center space-x-2 text-xs text-gray-600 cursor-pointer">
                    <span class="material-icons text-[8px]" :class="weatherIconColor" x-text="weatherIcon">wb_sunny</span>
                    <span x-text="temperature + '°C'">--°C</span>
                    <span class="text-gray-400">|</span>
                    <span x-text="condition">Loading...</span>
                </div>
                
                <!-- Weather Tooltip -->
                <div x-show="showTooltip" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full right-0 mt-3 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    
                    <!-- Weather Header -->
                    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Cuaca Sibu, Malaysia</h3>
                                <p class="text-xs text-gray-600" x-text="new Date().toLocaleDateString('ms-MY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })">Loading...</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900" x-text="temperature + '°C'">--°C</div>
                                <p class="text-xs text-gray-600" x-text="condition">Loading...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Weather Details -->
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <span class="material-icons text-2xl text-blue-500 mb-2" :class="weatherIconColor" x-text="weatherIcon">wb_sunny</span>
                                <p class="text-xs text-gray-600">Keadaan</p>
                                <p class="text-sm font-medium text-gray-900" x-text="condition">Loading...</p>
                            </div>
                            <div class="text-center">
                                <span class="material-icons text-2xl text-gray-400 mb-2">thermostat</span>
                                <p class="text-xs text-gray-600">Suhu</p>
                                <p class="text-sm font-medium text-gray-900" x-text="temperature + '°C'">--°C</p>
                            </div>
                        </div>
                        
                        <!-- Current Weather Details Grid -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-medium text-gray-800 mb-3 text-center">Maklumat Semasa</h4>
                        <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Terasa Seperti:</span>
                                    <span class="font-medium text-gray-900" x-text="current.feelsLike + '°C'">--°C</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Kelembapan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.humidity + '%'">--%</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Kelajuan Angin:</span>
                                    <span class="font-medium text-gray-900" x-text="current.windSpeed + ' km/j'">-- km/j</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Tekanan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.pressure + ' hPa'">-- hPa</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Jarak Penglihatan:</span>
                                    <span class="font-medium text-gray-900" x-text="current.visibility + ' km'">-- km</span>
                            </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-600">Indeks UV:</span>
                                    <span class="font-medium text-gray-900" x-text="current.uvIndex">--</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Forecast Section -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-medium text-gray-800 mb-3 text-center">Ramalan Hari Ini</h4>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-red-400">thermostat</span>
                                        <span class="text-xs text-gray-600">Min</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.minTemp + '°C'">--°C</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-blue-400">thermostat</span>
                                        <span class="text-xs text-gray-600">Max</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.maxTemp + '°C'">--°C</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1 mb-1">
                                        <span class="material-icons text-sm text-blue-500">opacity</span>
                                        <span class="text-xs text-gray-600">Hujan</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900" x-text="forecast.precipitation + '%'">--%</p>
                                </div>
                            </div>
                            
                            <!-- Additional Forecast Info -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span>Kelembapan:</span>
                                    <span x-text="forecast.humidity + '%'">--%</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-600 mt-1">
                                    <span>Keadaan:</span>
                                    <span x-text="forecast.condition">Loading...</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs text-gray-600">
                                <span>Dikemas kini:</span>
                                <span x-text="new Date().toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' })">--:--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-2 space-y-1">
            <a href="{{ route('overview') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">Papan Pemuka</a>
            <a href="{{ route('kariah.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">Ahli Kariah</a>

            <!-- Pentadbiran Sistem Section -->
            <div class="border-t border-gray-100 pt-2 mt-2">
                <p class="px-3 py-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Pentadbiran Sistem</p>
                @if(auth()->user()->hasPermission('masjids', 'read'))
                <a href="{{ route('senarai-masjid.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                    <span class="material-icons text-sm mr-2 text-purple-600">mosque</span>
                    Senarai Masjid
                </a>
                @endif

                @if(auth()->user()->hasPermission('users', 'read'))
                <a href="{{ route('senarai-pengguna.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                    <span class="material-icons text-sm mr-2 text-green-600">people</span>
                    Senarai Pengguna
                </a>
                @endif

                @if(auth()->user()->hasPermission('roles', 'read'))
                <a href="{{ route('senarai-kumpulan.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                    <span class="material-icons text-sm mr-2 text-teal-600">groups</span>
                    Senarai Kumpulan
                </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
function navbarData() {
    return {
        userDropdownOpen: false,
        mobileMenuOpen: false,
        
        // Close all dropdowns when page loads or navigation occurs
        closeAllDropdowns() {
            this.userDropdownOpen = false;
            this.mobileMenuOpen = false;
            
            // Close any other dropdowns
            document.querySelectorAll('[x-data*="open"]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0] && typeof el._x_dataStack[0].open !== 'undefined') {
                    el._x_dataStack[0].open = false;
                }
            });
        }
    }
}

function weatherWidget() {
    return {
        showTooltip: false,
        temperature: '--',
        condition: 'Loading...',
        weatherIcon: 'wb_sunny',
        weatherIconColor: 'text-blue-500',
        current: {
            humidity: '--',
            windSpeed: '--',
            feelsLike: '--',
            pressure: '--',
            visibility: '--',
            uvIndex: '--'
        },
        forecast: {
            minTemp: '--',
            maxTemp: '--',
            condition: 'Loading...',
            precipitation: '--',
            humidity: '--'
        },
        
        async fetchWeather() {
            try {
                const response = await fetch('/weather');
                const data = await response.json();
                
                if (data.success) {
                    this.temperature = data.data.current.temperature;
                    this.condition = data.data.current.condition;
                    this.weatherIcon = this.getWeatherIcon(data.data.current.weatherCode);
                    this.weatherIconColor = this.getWeatherIconColor(data.data.current.weatherCode);
                    
                    // Add current weather details
                    if (data.data.current) {
                        this.current.humidity = data.data.current.humidity || '--';
                        this.current.windSpeed = data.data.current.windSpeed || '--';
                        this.current.feelsLike = data.data.current.feelsLike || '--';
                        this.current.pressure = data.data.current.pressure || '--';
                        this.current.visibility = data.data.current.visibility || '--';
                        this.current.uvIndex = data.data.current.uvIndex || '--';
                    }
                    
                    // Add forecast data
                    if (data.data.forecast) {
                        this.forecast.minTemp = data.data.forecast.temperature.min;
                        this.forecast.maxTemp = data.data.forecast.temperature.max;
                        this.forecast.condition = data.data.forecast.condition;
                        this.forecast.precipitation = data.data.forecast.precipitation;
                        this.forecast.humidity = data.data.forecast.humidity;
                    }
                } else {
                    // Set default values if API fails
                    this.temperature = '24';
                    this.condition = 'Cerah';
                    this.weatherIcon = 'wb_sunny';
                    this.weatherIconColor = 'text-yellow-500';
                    this.current = {
                        humidity: '70',
                        windSpeed: '5',
                        feelsLike: '26',
                        pressure: '1013',
                        visibility: '10',
                        uvIndex: '5'
                    };
                    this.forecast = {
                        minTemp: '22',
                        maxTemp: '28',
                        condition: 'Cerah',
                        precipitation: '10',
                        humidity: '75'
                    };
                }
            } catch (error) {
                // Set fallback values
                this.temperature = '24';
                this.condition = 'Cerah';
                this.weatherIcon = 'wb_sunny';
                this.weatherIconColor = 'text-yellow-500';
                this.current = {
                    humidity: '70',
                    windSpeed: '5',
                    feelsLike: '26',
                    pressure: '1013',
                    visibility: '10',
                    uvIndex: '5'
                };
                this.forecast = {
                    minTemp: '22',
                    maxTemp: '28',
                    condition: 'Cerah',
                    precipitation: '10',
                    humidity: '75'
                };
            }
        },
        
        getWeatherIcon(code) {
            const icons = {
                1000: 'wb_sunny',      // Clear
                1001: 'cloud',         // Cloudy
                1100: 'wb_sunny',      // Mostly Clear
                1101: 'cloud',         // Partly Cloudy
                1102: 'cloud',         // Mostly Cloudy
                2000: 'cloud',         // Fog
                4000: 'grain',         // Light Rain
                4001: 'rainy',         // Rain
                4200: 'grain',         // Light Rain
                4201: 'rainy',         // Heavy Rain
                5000: 'ac_unit',       // Snow
                5001: 'ac_unit',       // Flurries
                5100: 'ac_unit',       // Light Snow
                5101: 'ac_unit',       // Heavy Snow
                6000: 'grain',         // Freezing Drizzle
                6200: 'grain',         // Light Freezing Rain
                6201: 'rainy',         // Freezing Rain
                7000: 'ac_unit',       // Ice Pellets
                7101: 'ac_unit',       // Heavy Ice Pellets
                7102: 'ac_unit',       // Light Ice Pellets
                8000: 'thunderstorm'   // Thunderstorm
            };
            return icons[code] || 'wb_sunny';
        },
        
        getWeatherIconColor(code) {
            if (code >= 4000 && code <= 4201) return 'text-blue-500'; // Rain
            if (code >= 5000 && code <= 5101) return 'text-gray-400'; // Snow
            if (code >= 6000 && code <= 6201) return 'text-blue-400'; // Freezing
            if (code >= 7000 && code <= 7102) return 'text-gray-400'; // Ice
            if (code === 8000) return 'text-yellow-500'; // Thunderstorm
            if (code >= 1101 && code <= 1102) return 'text-gray-400'; // Cloudy
            if (code === 2000) return 'text-gray-400'; // Fog
            return 'text-yellow-500'; // Clear/Sunny
        }
    }
}
</script>

<!-- Suppress Vite Development Logs -->
<script>
// Override console methods to suppress Vite/HMR messages globally
(function() {
    const originalLog = console.log;
    const originalInfo = console.info;
    const originalWarn = console.warn;

    console.log = function(...args) {
        const message = args.join(' ');
        if (message.includes('[vite]') ||
            message.includes('[HMR]') ||
            message.includes('connecting') ||
            message.includes('connected') ||
            message.includes('client:') ||
            message.includes('[DOM]') ||
            message.includes('autocomplete')) {
            return; // Suppress Vite development and DOM messages
        }
        return originalLog.apply(console, args);
    };

    console.info = function(...args) {
        const message = args.join(' ');
        if (message.includes('[vite]') ||
            message.includes('[HMR]') ||
            message.includes('connecting') ||
            message.includes('connected') ||
            message.includes('client:') ||
            message.includes('[DOM]') ||
            message.includes('autocomplete')) {
            return; // Suppress Vite development and DOM messages
        }
        return originalInfo.apply(console, args);
    };

    console.warn = function(...args) {
        const message = args.join(' ');
        if (message.includes('[vite]') ||
            message.includes('[HMR]') ||
            message.includes('connecting') ||
            message.includes('connected') ||
            message.includes('client:') ||
            message.includes('[DOM]') ||
            message.includes('autocomplete')) {
            return; // Suppress Vite development and DOM messages
        }
        return originalWarn.apply(console, args);
    };
})();
</script>
