<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Generate Jadual Imam & Bilal - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Auto-Generate Jadual Imam & Bilal</h1>
                        <p class="text-xs text-gray-600">Jana jadual secara automatik untuk tempoh tertentu</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-sm text-xs">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('jadual-imam-bilal.auto-generate.store') }}" method="POST">
                    @csrf
                    
                    <!-- Row 1: Tempoh & Corak Giliran -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Tempoh Jadual -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px;">date_range</span>
                                Tempoh Jadual
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tempoh <span class="text-red-500">*</span></label>
                                    <select id="tempoh" name="tempoh" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="minggu" {{ old('tempoh') == 'minggu' ? 'selected' : '' }}>Mingguan (7 hari)</option>
                                        <option value="bulan" {{ old('tempoh', 'bulan') == 'bulan' ? 'selected' : '' }}>Bulanan (1 bulan penuh)</option>
                                    </select>
                                </div>
                                
                                <!-- Tarikh Mula - untuk Mingguan -->
                                <div id="tarikh-section">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Mula <span class="text-red-500">*</span></label>
                                    <input type="date" name="tarikh_mula" value="{{ old('tarikh_mula', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Bulan & Tahun - untuk Bulanan -->
                                <div id="bulan-section" class="grid grid-cols-2 gap-3" style="display: none;">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Bulan <span class="text-red-500">*</span></label>
                                        <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="1" {{ old('bulan', now()->month) == 1 ? 'selected' : '' }}>Januari</option>
                                            <option value="2" {{ old('bulan', now()->month) == 2 ? 'selected' : '' }}>Februari</option>
                                            <option value="3" {{ old('bulan', now()->month) == 3 ? 'selected' : '' }}>Mac</option>
                                            <option value="4" {{ old('bulan', now()->month) == 4 ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ old('bulan', now()->month) == 5 ? 'selected' : '' }}>Mei</option>
                                            <option value="6" {{ old('bulan', now()->month) == 6 ? 'selected' : '' }}>Jun</option>
                                            <option value="7" {{ old('bulan', now()->month) == 7 ? 'selected' : '' }}>Julai</option>
                                            <option value="8" {{ old('bulan', now()->month) == 8 ? 'selected' : '' }}>Ogos</option>
                                            <option value="9" {{ old('bulan', now()->month) == 9 ? 'selected' : '' }}>September</option>
                                            <option value="10" {{ old('bulan', now()->month) == 10 ? 'selected' : '' }}>Oktober</option>
                                            <option value="11" {{ old('bulan', now()->month) == 11 ? 'selected' : '' }}>November</option>
                                            <option value="12" {{ old('bulan', now()->month) == 12 ? 'selected' : '' }}>Disember</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                                        <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @for($y = now()->year; $y <= now()->year + 2; $y++)
                                                <option value="{{ $y }}" {{ old('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Corak Giliran -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-purple-600" style="font-size: 18px;">sync</span>
                                Corak Giliran <span class="text-red-500 ml-1">*</span>
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 bg-white border border-gray-200 rounded cursor-pointer hover:bg-purple-50">
                                    <input type="radio" name="corak_giliran" value="harian" {{ old('corak_giliran', 'harian') == 'harian' ? 'checked' : '' }} class="border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <span class="text-xs font-medium text-gray-700">Harian</span>
                                        <p class="text-[10px] text-gray-500">Bertukar setiap hari</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 bg-white border border-gray-200 rounded cursor-pointer hover:bg-purple-50">
                                    <input type="radio" name="corak_giliran" value="3_hari" {{ old('corak_giliran') == '3_hari' ? 'checked' : '' }} class="border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <span class="text-xs font-medium text-gray-700">Setiap 3 Hari</span>
                                        <p class="text-[10px] text-gray-500">Bertukar setiap 3 hari</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 bg-white border border-gray-200 rounded cursor-pointer hover:bg-purple-50">
                                    <input type="radio" name="corak_giliran" value="mingguan" {{ old('corak_giliran') == 'mingguan' ? 'checked' : '' }} class="border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <span class="text-xs font-medium text-gray-700">Mingguan</span>
                                        <p class="text-[10px] text-gray-500">Bertukar setiap minggu</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 bg-white border border-gray-200 rounded cursor-pointer hover:bg-purple-50">
                                    <input type="radio" name="corak_giliran" value="berpasangan" {{ old('corak_giliran') == 'berpasangan' ? 'checked' : '' }} class="border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <span class="text-xs font-medium text-gray-700">Berpasangan</span>
                                        <p class="text-[10px] text-gray-500">Imam 1 + Bilal 1 sentiasa bersama</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Waktu Solat -->
                    <div class="mb-6">
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-green-600" style="font-size: 18px;">schedule</span>
                                Waktu Solat <span class="text-red-500 ml-1">*</span>
                            </h3>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                                @foreach(['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat'] as $waktu)
                                    <label class="flex items-center justify-center gap-3 p-4 bg-white border border-gray-200 rounded cursor-pointer hover:bg-green-50">
                                        <input type="checkbox" name="waktu_solat[]" value="{{ $waktu }}" {{ in_array($waktu, old('waktu_solat', ['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak'])) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="text-xs text-gray-700">{{ $waktu }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-3 text-[10px] text-gray-500">* Jumaat hanya dijana pada hari Jumaat</p>
                        </div>
                    </div>

                    <!-- Row 3: Giliran Imam & Bilal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Giliran Imam -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-purple-600" style="font-size: 18px;">person</span>
                                Giliran Imam <span class="text-red-500 ml-1">*</span>
                            </h3>
                            <p class="text-[10px] text-gray-500 mb-4">Pilih Imam untuk giliran (hanya AJK dengan jawatan Imam)</p>
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($imamList as $imam)
                                    <label class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded cursor-pointer hover:bg-purple-50">
                                        <input type="checkbox" name="imam_rotation[]" value="{{ $imam->id }}" {{ in_array($imam->id, old('imam_rotation', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div>
                                            <span class="text-xs font-medium text-gray-700">Imam {{ $imam->nama }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700">
                                        <span class="material-icons mr-1 align-middle" style="font-size: 16px;">warning</span>
                                        Tiada Imam aktif. Sila <a href="{{ route('ajk.create') }}" class="underline font-medium">tambah AJK dengan jawatan Imam</a> terlebih dahulu.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Giliran Bilal -->
                        <div class="bg-gray-50 p-4 rounded-sm border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-orange-600" style="font-size: 18px;">record_voice_over</span>
                                Giliran Bilal <span class="text-red-500 ml-1">*</span>
                            </h3>
                            <p class="text-[10px] text-gray-500 mb-4">Pilih Bilal untuk giliran (hanya AJK dengan jawatan Bilal)</p>
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($bilalList as $bilal)
                                    <label class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded cursor-pointer hover:bg-orange-50">
                                        <input type="checkbox" name="bilal_rotation[]" value="{{ $bilal->id }}" {{ in_array($bilal->id, old('bilal_rotation', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                        <div>
                                            <span class="text-xs font-medium text-gray-700">Bilal {{ $bilal->nama }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700">
                                        <span class="material-icons mr-1 align-middle" style="font-size: 16px;">warning</span>
                                        Tiada Bilal aktif. Sila <a href="{{ route('ajk.create') }}" class="underline font-medium">tambah AJK dengan jawatan Bilal</a> terlebih dahulu.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('jadual-imam-bilal.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">autorenew</span>
                            Jana Jadual
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tempohSelect = document.getElementById('tempoh');
            const tarikhSection = document.getElementById('tarikh-section');
            const bulanSection = document.getElementById('bulan-section');

            function toggleSections() {
                if (tempohSelect.value === 'minggu') {
                    tarikhSection.style.display = 'block';
                    bulanSection.style.display = 'none';
                } else {
                    tarikhSection.style.display = 'none';
                    bulanSection.style.display = 'grid';
                }
            }

            tempohSelect.addEventListener('change', toggleSections);
            toggleSections();
        });
    </script>
</body>
</html>
