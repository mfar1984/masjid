<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agihan {{ $agihanZakat->no_agihan }} - E-Masjid</title>
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
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Agihan {{ $agihanZakat->no_agihan }}</h1>
                        <p class="text-xs text-gray-600">Butiran agihan zakat - {{ $agihanZakat->asnaf->nama }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('agihan-zakat.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if($agihanZakat->canBeEdited() && auth()->user()->hasPermission('agihan_zakat', 'update'))
                            <a href="{{ route('agihan-zakat.edit', $agihanZakat) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($agihanZakat->status == 'Sudah Bayar')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">check_circle</span>
                            {{ $agihanZakat->status }}
                        </span>
                    @elseif($agihanZakat->status == 'Belum Bayar')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">pending</span>
                            {{ $agihanZakat->status }}
                        </span>
                    @elseif($agihanZakat->status == 'Dibatalkan')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">cancel</span>
                            {{ $agihanZakat->status }}
                        </span>
                    @endif
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- 1. Maklumat Agihan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Agihan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Agihan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $agihanZakat->no_agihan }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Agihan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->tarikh_agihan->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Diagihkan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-bold text-gray-900">RM {{ number_format($agihanZakat->jumlah_diagihkan, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Bayaran</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $agihanZakat->kaedah_bayaran }}
                                    </span>
                                </div>
                            </div>
                            @if($agihanZakat->no_rujukan)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Rujukan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->no_rujukan }}</div>
                            </div>
                            @endif
                            @if($agihanZakat->nama_bank)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->nama_bank }}</div>
                            </div>
                            @endif
                            @if($agihanZakat->no_akaun)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Akaun</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->no_akaun }}</div>
                            </div>
                            @endif
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Oleh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->createdBy->name ?? '-' }}</div>
                            </div>
                            @if($agihanZakat->catatan)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->catatan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Maklumat Permohonan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Permohonan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">
                                    <a href="{{ route('permohonan-zakat.show', $agihanZakat->permohonanZakat) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $agihanZakat->permohonanZakat->no_permohonan }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->permohonanZakat->tarikh_permohonan->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Bantuan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->permohonanZakat->jenis_bantuan }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Diluluskan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">RM {{ number_format($agihanZakat->permohonanZakat->jumlah_diluluskan, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Maklumat Asnaf -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">3. Maklumat Asnaf</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $agihanZakat->asnaf->nama }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->asnaf->no_ic }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori Asnaf</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $agihanZakat->asnaf->kategori_asnaf }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Telefon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $agihanZakat->asnaf->telefon }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <a href="{{ route('asnaf.show', $agihanZakat->asnaf) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                    <span class="material-icons mr-1" style="font-size: 16px !important;">visibility</span>
                                    Lihat Profil Lengkap Asnaf
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Maklumat Bayaran (if Sudah Bayar) -->
                    @if($agihanZakat->status == 'Sudah Bayar')
                    <div class="bg-green-50 rounded p-4 border border-green-200">
                        <h3 class="text-sm font-semibold text-green-900 mb-4">4. Maklumat Bayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Tarikh Bayaran</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $agihanZakat->tarikh_bayaran->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Dibayar Oleh</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $agihanZakat->dibayarOleh->name ?? '-' }}</div>
                            </div>
                            @if($agihanZakat->bukti_bayaran_path)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-green-700 mb-1">Bukti Bayaran</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm">
                                    <a href="{{ Storage::url($agihanZakat->bukti_bayaran_path) }}" target="_blank" class="inline-flex items-center text-green-600 hover:text-green-800">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                        Lihat Bukti Bayaran
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if($agihanZakat->canBePaid() && auth()->user()->hasPermission('agihan_zakat', 'update'))
                    <div class="flex gap-3 justify-end mt-6">
                        <button onclick="showBayarModal()" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">check_circle</span>
                            Tandakan Sudah Bayar
                        </button>
                        <button onclick="showBatalModal()" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                            Batal Agihan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Bayar Modal -->
    <div id="bayarModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-green-100">
                    <span class="material-icons text-green-600 text-xl">check_circle</span>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Tandakan Sudah Bayar</h3>
                
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Sila isi maklumat bayaran untuk agihan ini</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $agihanZakat->no_agihan }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        Agihan akan ditukar status kepada "Sudah Bayar"
                    </p>
                </div>
                
                <form id="bayarForm" method="POST" action="{{ route('agihan-zakat.bayar', $agihanZakat) }}" enctype="multipart/form-data" class="mt-4 px-4">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Bayaran *</label>
                            <input type="date" name="tarikh_bayaran" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bukti Bayaran (PDF/Image) *</label>
                            <input type="file" name="bukti_bayaran" accept=".pdf,.jpg,.jpeg,.png" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG (Max: 5MB)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan_bayaran" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center gap-3 mt-6 mb-2">
                        <button type="button" onclick="closeBayarModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Tandakan Sudah Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Batal Modal -->
    <div id="batalModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-red-100">
                    <span class="material-icons text-red-600 text-xl">cancel</span>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Batal Agihan Zakat</h3>
                
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Adakah anda pasti mahu membatalkan agihan ini?</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $agihanZakat->no_agihan }}</p>
                    <p class="text-xs text-red-600 mt-2">
                        Agihan akan ditukar status kepada "Dibatalkan"
                    </p>
                </div>
                
                <form id="batalForm" method="POST" action="{{ route('agihan-zakat.batal', $agihanZakat) }}" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Pembatalan *</label>
                        <textarea name="sebab_pembatalan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Nyatakan sebab pembatalan..."></textarea>
                    </div>
                    
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeBatalModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Batalkan Agihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function showBayarModal() {
            document.getElementById('bayarModal').classList.remove('hidden');
        }

        function closeBayarModal() {
            document.getElementById('bayarModal').classList.add('hidden');
        }

        function showBatalModal() {
            document.getElementById('batalModal').classList.remove('hidden');
        }

        function closeBatalModal() {
            document.getElementById('batalModal').classList.add('hidden');
        }

        document.getElementById('bayarModal').addEventListener('click', function(e) {
            if (e.target === this) closeBayarModal();
        });

        document.getElementById('batalModal').addEventListener('click', function(e) {
            if (e.target === this) closeBatalModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBayarModal();
                closeBatalModal();
            }
        });
    </script>
</body>
</html>
