<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan {{ $permohonanZakat->no_permohonan }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Permohonan {{ $permohonanZakat->no_permohonan }}</h1>
                        <p class="text-xs text-gray-600">Butiran permohonan bantuan zakat - {{ $permohonanZakat->asnaf->nama }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('permohonan-zakat.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if($permohonanZakat->canBeEdited() && auth()->user()->hasPermission('asnaf', 'update'))
                            <a href="{{ route('permohonan-zakat.edit', $permohonanZakat) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($permohonanZakat->status == 'Diluluskan')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">check_circle</span>
                            {{ $permohonanZakat->status }}
                        </span>
                    @elseif($permohonanZakat->status == 'Menunggu')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">pending</span>
                            {{ $permohonanZakat->status }}
                        </span>
                    @elseif($permohonanZakat->status == 'Ditolak')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">close</span>
                            {{ $permohonanZakat->status }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">rate_review</span>
                            {{ $permohonanZakat->status }}
                        </span>
                    @endif
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- 1. Maklumat Permohonan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Permohonan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $permohonanZakat->no_permohonan }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->tarikh_permohonan->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Bantuan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permohonanZakat->jenis_bantuan }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori Bantuan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->kategori_bantuan }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Dipohon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-bold text-gray-900">RM {{ number_format($permohonanZakat->jumlah_dipohon, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Oleh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->createdBy->name ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->sebab_permohonan }}</div>
                            </div>
                            @if($permohonanZakat->dokumen_sokongan_path)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dokumen Sokongan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm">
                                    <a href="{{ Storage::url($permohonanZakat->dokumen_sokongan_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                        Lihat Dokumen
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Maklumat Pemohon (Asnaf) -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Pemohon (Asnaf)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $permohonanZakat->asnaf->nama }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->asnaf->no_ic }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kategori Asnaf</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $permohonanZakat->asnaf->kategori_asnaf }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Telefon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->asnaf->telefon }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pendapatan Bulanan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">RM {{ number_format($permohonanZakat->asnaf->pendapatan_bulanan, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bilangan Tanggungan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->asnaf->bilangan_tanggungan }} orang</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pendapatan Per Kapita</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-semibold text-gray-900">RM {{ number_format($permohonanZakat->asnaf->pendapatan_per_kapita, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status Asnaf</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    @if($permohonanZakat->asnaf->status == 'Diluluskan')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                            {{ $permohonanZakat->asnaf->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $permohonanZakat->asnaf->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanZakat->asnaf->alamat_kediaman }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <a href="{{ route('asnaf.show', $permohonanZakat->asnaf) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                    <span class="material-icons mr-1" style="font-size: 16px !important;">visibility</span>
                                    Lihat Profil Lengkap Asnaf
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Maklumat Kelulusan -->
                    @if($permohonanZakat->status == 'Diluluskan')
                    <div class="bg-green-50 rounded p-4 border border-green-200">
                        <h3 class="text-sm font-semibold text-green-900 mb-4">3. Maklumat Kelulusan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Jumlah Diluluskan</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm font-bold text-green-900">RM {{ number_format($permohonanZakat->jumlah_diluluskan, 2) }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Tarikh Kelulusan</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $permohonanZakat->tarikh_kelulusan->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Tarikh Mesyuarat</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $permohonanZakat->tarikh_mesyuarat->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">No Mesyuarat</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $permohonanZakat->no_mesyuarat }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Diluluskan Oleh</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $permohonanZakat->diluluskanOleh->name ?? '-' }}</div>
                            </div>
                            @if($permohonanZakat->minit_mesyuarat_path)
                            <div>
                                <label class="block text-xs font-medium text-green-700 mb-1">Minit Mesyuarat</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm">
                                    <a href="{{ Storage::url($permohonanZakat->minit_mesyuarat_path) }}" target="_blank" class="inline-flex items-center text-green-600 hover:text-green-800">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                        Lihat Minit Mesyuarat
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($permohonanZakat->catatan_kelulusan)
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-green-700 mb-1">Catatan Kelulusan</label>
                                <div class="p-2 bg-white border border-green-300 rounded text-sm text-green-900">{{ $permohonanZakat->catatan_kelulusan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- 3. Maklumat Penolakan -->
                    @if($permohonanZakat->status == 'Ditolak')
                    <div class="bg-red-50 rounded p-4 border border-red-200">
                        <h3 class="text-sm font-semibold text-red-900 mb-4">3. Maklumat Penolakan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-red-700 mb-1">Tarikh Penolakan</label>
                                <div class="p-2 bg-white border border-red-300 rounded text-sm text-red-900">{{ $permohonanZakat->tarikh_penolakan->format('d/m/Y') }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-red-700 mb-1">Sebab Penolakan</label>
                                <div class="p-2 bg-white border border-red-300 rounded text-sm text-red-900">{{ $permohonanZakat->sebab_penolakan }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if($permohonanZakat->canBeApproved() && auth()->user()->hasPermission('asnaf', 'update'))
                    <div class="flex gap-3 justify-end mt-6">
                        <button onclick="showApproveModal()" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">check_circle</span>
                            Luluskan
                        </button>
                        <button onclick="showRejectModal()" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                            Tolak
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Icon -->
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-green-100">
                    <span class="material-icons text-green-600 text-xl">check_circle</span>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Luluskan Permohonan Zakat</h3>
                
                <!-- Message -->
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Sila isi maklumat kelulusan untuk permohonan ini</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $permohonanZakat->no_permohonan }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        Permohonan akan ditukar status kepada "Diluluskan"
                    </p>
                </div>
                
                <!-- Form -->
                <form id="approveForm" method="POST" action="{{ route('permohonan-zakat.approve', $permohonanZakat) }}" enctype="multipart/form-data" class="mt-4 px-4">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Diluluskan (RM) *</label>
                            <input type="number" step="0.01" name="jumlah_diluluskan" value="{{ $permohonanZakat->jumlah_dipohon }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Mesyuarat *</label>
                            <input type="date" name="tarikh_mesyuarat" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">No Mesyuarat *</label>
                            <input type="text" name="no_mesyuarat" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Minit Mesyuarat (PDF/Image) *</label>
                            <input type="file" name="minit_mesyuarat" accept=".pdf,.jpg,.jpeg,.png" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, PNG (Max: 5MB)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="catatan_kelulusan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex items-center justify-center gap-3 mt-6 mb-2">
                        <button type="button" 
                                onclick="closeApproveModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Luluskan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Icon -->
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-red-100">
                    <span class="material-icons text-red-600 text-xl">cancel</span>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Tolak Permohonan Zakat</h3>
                
                <!-- Message -->
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Adakah anda pasti mahu menolak permohonan ini?</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">{{ $permohonanZakat->no_permohonan }}</p>
                    <p class="text-xs text-red-600 mt-2">
                        Permohonan akan ditukar status kepada "Ditolak"
                    </p>
                </div>
                
                <!-- Form -->
                <form id="rejectForm" method="POST" action="{{ route('permohonan-zakat.reject', $permohonanZakat) }}" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Penolakan *</label>
                        <textarea name="sebab_penolakan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Nyatakan sebab penolakan..."></textarea>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" 
                                onclick="closeRejectModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function showApproveModal() {
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>
</body>
</html>
