<nav class="bg-white border-b border-gray-200" x-data="{
    weatherWidget() {
        return {
            showTooltip: false,
            temperature: '--',
            condition: 'Loading...',
            weatherIcon: 'wb_sunny',
            weatherIconColor: 'text-blue-500',
            current: {
                temperature: '--',
                condition: 'Loading...',
                feelsLike: '--',
                humidity: '--',
                windSpeed: '--',
                visibility: '--',
                pressure: '--',
                uvIndex: '--'
            },
            forecast: {
                date: '--',
                condition: 'Clear',
                weatherCode: 1000,
                temperature: { min: '--', max: '--' },
                precipitation: '--'
            },
            location: {
                city: 'Sibu',
                country: 'Malaysia',
                latitude: '2.2876',
                longitude: '111.8303',
                timezone: 'Asia/Kuala_Lumpur'
            },
            
            async fetchWeather() {
                try {
                    const response = await fetch('/weather');
                    const data = await response.json();
                    
                    if (data.success) {
                        // Update current weather
                        this.temperature = data.data.current.temperature;
                        this.condition = data.data.current.condition;
                        this.weatherIcon = this.getWeatherIcon(data.data.current.weatherCode);
                        this.weatherIconColor = this.getWeatherIconColor(data.data.current.weatherCode);
                        
                        // Update current details
                        this.current = {
                            temperature: data.data.current.temperature,
                            condition: data.data.current.condition,
                            feelsLike: data.data.current.feelsLike || '--',
                            humidity: data.data.current.humidity || '--',
                            windSpeed: data.data.current.windSpeed || '--',
                            visibility: data.data.current.visibility || '--',
                            pressure: data.data.current.pressure || '--',
                            uvIndex: data.data.current.uvIndex || '--'
                        };
                        
                        // Update forecast
                        if (data.data.forecast) {
                            this.forecast = {
                                date: this.formatDate(data.data.forecast.date),
                                condition: data.data.forecast.condition,
                                weatherCode: data.data.forecast.weatherCode,
                                temperature: {
                                    min: data.data.forecast.temperature.min || '--',
                                    max: data.data.forecast.temperature.max || '--'
                                },
                                precipitation: data.data.forecast.precipitation || '--'
                            };
                        }
                        
                        // Update location
                        this.location = data.data.location;
                    }
                } catch (error) {
                    console.error('Weather fetch error:', error);
                    this.temperature = '--';
                    this.condition = 'Error';
                }
            },
            
            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    month: 'short', 
                    day: 'numeric' 
                });
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
                if (code === 1001 || (code >= 1101 && code <= 1102)) return 'text-gray-500'; // Cloudy
                if (code === 2000) return 'text-gray-400'; // Fog
                return 'text-yellow-500'; // Clear/Sunny
            }
        }
    }
}">
    <!-- Top Navbar -->
    <div class="flex items-center justify-between px-20 h-13">
        <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.svg') }}" class="h-12 w-12 py-0" alt="Logo">
        </div>
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="material-icons text-[10px]">notifications</span>
                    <!-- Notification badge -->
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Notifikasi</h3>
                        <p class="text-[8px] text-gray-500">Anda mempunyai 3 notifikasi baharu</p>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <!-- Notification Item 1 -->
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
                        <!-- Notification Item 2 -->
                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="material-icons text-[10px] text-green-500">account_balance_wallet</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Sumbangan Diterima</p>
                                    <p class="text-[8px] text-gray-500">RM 500.00 sumbangan untuk program masjid</p>
                                    <p class="text-[8px] text-gray-400 mt-1">5 minit yang lalu</p>
                                </div>
                            </div>
                        </div>
                        <!-- Notification Item 3 -->
                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="material-icons text-[10px] text-purple-500">event</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Program Akan Datang</p>
                                    <p class="text-[8px] text-gray-500">Kuliah Maghrib esok jam 7:30 malam</p>
                                    <p class="text-[8px] text-gray-400 mt-1">10 minit yang lalu</p>
                                </div>
                            </div>
                        </div>
                        <!-- Notification Item 4 -->
                        <div class="px-4 py-3 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="material-icons text-[10px] text-orange-500">build</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Penyelenggaraan</p>
                                    <p class="text-[8px] text-gray-500">Sistem penyaman udara perlu diperbaiki</p>
                                    <p class="text-[8px] text-gray-400 mt-1">15 minit yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100">
                        <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Lihat semua notifikasi</a>
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
                            <!-- App 1 - Kariah -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-blue-500 mb-1">people</span>
                                <span class="text-[10px] text-gray-700">Kariah</span>
                            </a>
                            <!-- App 2 - Kewangan -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-green-500 mb-1">account_balance_wallet</span>
                                <span class="text-[10px] text-gray-700">Kewangan</span>
                            </a>
                            <!-- App 3 - Program -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-purple-500 mb-1">event</span>
                                <span class="text-[10px] text-gray-700">Program</span>
                            </a>
                            <!-- App 4 - AJK -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-orange-500 mb-1">admin_panel_settings</span>
                                <span class="text-[10px] text-gray-700">AJK</span>
                            </a>
                            <!-- App 5 - Aset -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-red-500 mb-1">inventory</span>
                                <span class="text-[10px] text-gray-700">Aset</span>
                            </a>
                            <!-- App 6 - Laporan -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-indigo-500 mb-1">analytics</span>
                                <span class="text-[10px] text-gray-700">Laporan</span>
                            </a>
                            <!-- App 7 - Komunikasi -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-teal-500 mb-1">message</span>
                                <span class="text-[10px] text-gray-700">Komunikasi</span>
                            </a>
                            <!-- App 8 - Dokumen -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-pink-500 mb-1">folder</span>
                                <span class="text-[10px] text-gray-700">Dokumen</span>
                            </a>
                            <!-- App 9 - Tetapan -->
                            <a href="#" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="material-icons text-[16px] text-yellow-500 mb-1">settings</span>
                                <span class="text-[10px] text-gray-700">Tetapan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Help -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <span class="material-icons text-[10px]">help_outline</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-3 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-xs font-medium text-gray-900">Bantuan & Sokongan</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <!-- Bantuan Pantas -->
                        <div class="space-y-2">
                            <h4 class="text-xs font-medium text-gray-800">Bantuan Pantas</h4>
                            <div class="space-y-1">
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-blue-500">article</span>
                                    Panduan Pengguna
                                </a>
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-green-500">video_library</span>
                                    Tutorial Video
                                </a>
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-purple-500">quiz</span>
                                    Soalan Lazim
                                </a>
                            </div>
                        </div>
                        
                        <!-- Sokongan -->
                        <div class="space-y-2">
                            <h4 class="text-xs font-medium text-gray-800">Sokongan</h4>
                            <div class="space-y-1">
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-orange-500">support_agent</span>
                                    Hubungi Sokongan
                                </a>
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-red-500">bug_report</span>
                                    Laporkan Masalah
                                </a>
                                <a href="#" class="flex items-center text-[10px] text-gray-600 hover:text-blue-600 hover:bg-gray-50 p-2 rounded">
                                    <span class="material-icons text-[10px] mr-2 text-indigo-500">feedback</span>
                                    Hantar Maklum Balas
                                </a>
                            </div>
                        </div>
                        
                        <!-- System Info -->
                        <div class="pt-2 border-t border-gray-100">
                            <div class="text-[9px] text-gray-500 space-y-1">
                                <div class="flex justify-between">
                                    <span>Versi:</span>
                                    <span>v1.2.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Kemaskini Terakhir:</span>
                                    <span>Dec 15, 2024</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="h-6 border-l border-gray-200 mx-2"></span>
            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-medium text-gray-700 hover:text-blue-500 focus:outline-none">
                    <span class="material-icons text-[10px] mr-2">account_circle</span>
                    {{ Auth::user()->name }}
                    <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                </button>
                <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                    <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Profil</a>
                    <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Tetapan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Log Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bottom Navbar -->
    <div class="bg-white border-t border-gray-100">
        <div class="flex space-x-6 px-20 h-12 items-center justify-between">
            <div class="flex space-x-6">
                <a href="{{ route('overview') }}" class="relative flex items-center text-xs font-normal text-gray-700 hover:text-blue-400">
                    <span class="material-icons text-[8px] mr-1 text-blue-600">dashboard</span>
                    Papan Pemuka
                </a>

                <!-- Pengurusan -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-green-600">fact_check</span>
                        Pengurusan
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Ahli Kariah</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">AJK</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Asnaf & Kebajikan</a>
                    </div>
                </div>

                <!-- Kewangan -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-teal-600">account_balance_wallet</span>
                        Kewangan
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Transaksi & Lejar Am</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Kutipan Dana</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Khairat Kematian</a>
                    </div>
                </div>

                <!-- Operasi -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-indigo-600">event</span>
                        Operasi
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Program & Pendidikan</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Fasiliti & Tempahan</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Pengurusan Jenazah</a>
                    </div>
                </div>

                <!-- Aset -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-gray-700">inventory_2</span>
                        Aset
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Aset & Inventori</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Penyelenggaraan</a>
                    </div>
                </div>

                <!-- Komunikasi -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-rose-600">campaign</span>
                        Komunikasi
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Siaran Mesej</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Pengumuman Laman</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Templat & Jadual Hebahan</a>
                    </div>
                </div>
                
                <!-- Laporan -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-orange-600">analytics</span>
                        Laporan
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-56 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Laporan Bersepadu</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Pengurusan Dokumen</a>
                    </div>
                </div>
                
                <!-- Pentadbiran Sistem -->
                <div class="relative" x-data="{ open: false }">
                    <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center text-xs font-normal text-gray-700 hover:text-blue-400 focus:outline-none">
                        <span class="material-icons text-[8px] mr-1 text-red-600">admin_panel_settings</span>
                        Pentadbiran Sistem
                        <span class="material-icons text-[6px] font-extralight ml-1" x-text="open ? 'expand_less' : 'expand_more'"></span>
                    </button>
                    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-md shadow-lg py-2 z-50">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Tetapan Umum</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Pengguna & Akses</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Integrasi</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Log Audit & Keselamatan</a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 relative hover:after:content-[''] hover:after:block hover:after:absolute hover:after:right-0 hover:after:top-0 hover:after:bottom-0 hover:after:w-1 hover:after:bg-blue-500">Bahasa & Tema</a>
                    </div>
                </div>
            </div>
            
            <!-- Weather Widget -->
            <div x-data="weatherWidget()" x-init="fetchWeather()" class="relative">
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
                     class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold" x-text="location.city">Sibu</h3>
                                <p class="text-xs opacity-90" x-text="location.country">Malaysia</p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold" x-text="current.temperature + '°C'">--°C</div>
                                <div class="text-xs opacity-90" x-text="current.condition">Loading...</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Weather Details -->
                    <div class="p-4 space-y-3">
                        <!-- Current Conditions -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">thermostat</span>
                                <div>
                                    <p class="text-xs text-gray-600">Suhu</p>
                                    <p class="text-xs font-medium" x-text="current.feelsLike + '°C'">--°C</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">opacity</span>
                                <div>
                                    <p class="text-xs text-gray-600">Kelembapan</p>
                                    <p class="text-xs font-medium" x-text="current.humidity + '%'">--%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">air</span>
                                <div>
                                    <p class="text-xs text-gray-600">Angin</p>
                                    <p class="text-xs font-medium" x-text="current.windSpeed + ' km/h'">-- km/h</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">visibility</span>
                                <div>
                                    <p class="text-xs text-gray-600">Jarak Penglihatan</p>
                                    <p class="text-xs font-medium" x-text="current.visibility + ' km'">-- km</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pressure & UV -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">speed</span>
                                <div>
                                    <p class="text-xs text-gray-600">Tekanan</p>
                                    <p class="text-xs font-medium" x-text="current.pressure + ' hPa'">-- hPa</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="material-icons text-xs text-gray-500">wb_sunny</span>
                                <div>
                                    <p class="text-xs text-gray-600">Indeks UV</p>
                                    <p class="text-xs font-medium" x-text="current.uvIndex">--</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <hr class="border-gray-200">
                        
                        <!-- Tomorrow's Forecast -->
                        <div class="space-y-2">
                            <h4 class="text-xs font-semibold text-gray-800">Ramalan Esok</h4>
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <div class="flex items-center space-x-3">
                                    <span class="material-icons text-sm" :class="getWeatherIconColor(forecast.weatherCode)" x-text="getWeatherIcon(forecast.weatherCode)">wb_sunny</span>
                                    <div>
                                        <p class="text-xs font-medium" x-text="forecast.condition">Cerah</p>
                                        <p class="text-xs text-gray-500" x-text="forecast.date">--</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-medium">
                                        <span x-text="forecast.temperature.max + '°'">--°</span>
                                        <span class="text-gray-400">/</span>
                                        <span x-text="forecast.temperature.min + '°'">--°</span>
                                    </div>
                                    <p class="text-xs text-gray-500" x-text="forecast.precipitation + '% hujan'">--% hujan</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Details -->
                        <div class="pt-2 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
                                <div>
                                    <span class="font-medium">Lat:</span>
                                    <span x-text="location.latitude">--</span>
                                </div>
                                <div>
                                    <span class="font-medium">Lon:</span>
                                    <span x-text="location.longitude">--</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium">Zon Masa:</span>
                                    <span x-text="location.timezone">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function weatherWidget() {
    return {
        showTooltip: false,
        temperature: '--',
        condition: 'Loading...',
        weatherIcon: 'wb_sunny',
        weatherIconColor: 'text-blue-500',
        current: {
            temperature: '--',
            condition: 'Loading...',
            feelsLike: '--',
            humidity: '--',
            windSpeed: '--',
            visibility: '--',
            pressure: '--',
            uvIndex: '--'
        },
        forecast: {
            date: '--',
            condition: 'Cerah',
            weatherCode: 1000,
            temperature: { min: '--', max: '--' },
            precipitation: '--'
        },
        location: {
            city: 'Sibu',
            country: 'Malaysia',
            latitude: '2.2876',
            longitude: '111.8303',
            timezone: 'Asia/Kuala_Lumpur'
        },
        
        async fetchWeather() {
            try {
                const response = await fetch('/weather');
                const data = await response.json();
                
                if (data.success) {
                    // Update current weather
                    this.temperature = data.data.current.temperature;
                    this.condition = data.data.current.condition;
                    this.weatherIcon = this.getWeatherIcon(data.data.current.weatherCode);
                    this.weatherIconColor = this.getWeatherIconColor(data.data.current.weatherCode);
                    
                    // Update current details
                    this.current = {
                        temperature: data.data.current.temperature,
                        condition: data.data.current.condition,
                        feelsLike: data.data.current.feelsLike || '--',
                        humidity: data.data.current.humidity || '--',
                        windSpeed: data.data.current.windSpeed || '--',
                        visibility: data.data.current.visibility || '--',
                        pressure: data.data.current.pressure || '--',
                        uvIndex: data.data.current.uvIndex || '--'
                    };
                    
                    // Update forecast
                    if (data.data.forecast) {
                        this.forecast = {
                            date: this.formatDate(data.data.forecast.date),
                            condition: data.data.forecast.condition,
                            weatherCode: data.data.forecast.weatherCode,
                            temperature: {
                                min: data.data.forecast.temperature.min || '--',
                                max: data.data.forecast.temperature.max || '--'
                            },
                            precipitation: data.data.forecast.precipitation || '--'
                        };
                    }
                    
                    // Update location
                    this.location = data.data.location;
                }
            } catch (error) {
                console.error('Weather fetch error:', error);
                this.temperature = '--';
                this.condition = 'Error';
            }
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                weekday: 'short', 
                month: 'short', 
                day: 'numeric' 
            });
        },
        
        getWeatherIcon(code) {
            const icons = {
                1000: 'wb_sunny',      // Clear
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
            if (code >= 1101 && code <= 1102) return 'text-gray-500'; // Cloudy
            if (code === 2000) return 'text-gray-400'; // Fog
            return 'text-yellow-500'; // Clear/Sunny
        }
    }
}
</script> 