<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan {{ $permohonanBantuan->no_permohonan }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Permohonan {{ $permohonanBantuan->no_permohonan }}</h1>
                        <p class="text-xs text-gray-600">Butiran permohonan bantuan - {{ $permohonanBantuan->penerimaBantuan->nama_penuh }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('permohonan-bantuan.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(in_array($permohonanBantuan->status_permohonan, ['Baharu', 'Dalam Semakan']) && auth()->user()->hasPermission('permohonan_bantuan', 'update'))
                            <a href="{{ route('permohonan-bantuan.edit', $permohonanBantuan) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($permohonanBantuan->status_permohonan == 'Lulus')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">check_circle</span>
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @elseif($permohonanBantuan->status_permohonan == 'Baharu')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">fiber_new</span>
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @elseif($permohonanBantuan->status_permohonan == 'Dalam Semakan')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">rate_review</span>
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @elseif($permohonanBantuan->status_permohonan == 'Lawatan Rumah')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">home</span>
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @elseif($permohonanBantuan->status_permohonan == 'Ditolak')
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                            <span class="material-icons mr-1" style="font-size: 14px !important;">cancel</span>
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $permohonanBantuan->status_permohonan }}
                        </span>
                    @endif

                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium ml-2 
                        @if($permohonanBantuan->keutamaan == 'Kecemasan') bg-red-100 text-red-800
                        @elseif($permohonanBantuan->keutamaan == 'Tinggi') bg-orange-100 text-orange-800
                        @elseif($permohonanBantuan->keutamaan == 'Sederhana') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ $permohonanBantuan->keutamaan }}
                    </span>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-xs">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- 1. Maklumat Permohonan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">1. Maklumat Permohonan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $permohonanBantuan->no_permohonan }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->tarikh_permohonan->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Program Kebajikan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->programKebajikan->nama_program }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Bantuan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permohonanBantuan->jenis_bantuan }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Dipohon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm font-bold text-gray-900">
                                    @if($permohonanBantuan->jumlah_dipohon)
                                        RM {{ number_format($permohonanBantuan->jumlah_dipohon, 2) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dicipta Oleh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->creator->name ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tujuan Permohonan</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->tujuan_permohonan }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Maklumat Penerima Bantuan -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">2. Maklumat Penerima Bantuan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900 font-semibold">{{ $permohonanBantuan->penerimaBantuan->nama_penuh }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. IC</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->penerimaBantuan->no_kp }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Telefon</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">{{ $permohonanBantuan->penerimaBantuan->no_telefon }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status Penerima</label>
                                <div class="p-2 bg-white border border-gray-300 rounded text-sm text-gray-900">
                                    @if($permohonanBantuan->penerimaBantuan->status_penerima == 'Aktif')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                            {{ $permohonanBantuan->penerimaBantuan->status_penerima }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $permohonanBantuan->penerimaBantuan->status_penerima }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <a href="{{ route('penerima-bantuan.show', $permohonanBantuan->penerimaBantuan) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                    <span class="material-icons mr-1" style="font-size: 16px !important;">visibility</span>
                                    Lihat Profil Lengkap Penerima
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Workflow Timeline -->
                    <div class="bg-gray-50 rounded p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">3. Status Workflow</h3>
                        <div class="space-y-3">
                            <!-- Baharu -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="material-icons text-blue-600" style="font-size: 16px !important;">fiber_new</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Permohonan Baharu</p>
                                    <p class="text-xs text-gray-500">{{ $permohonanBantuan->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            @if($permohonanBantuan->tarikh_disemak)
                            <!-- Dalam Semakan -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="material-icons text-yellow-600" style="font-size: 16px !important;">rate_review</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Dalam Semakan</p>
                                    <p class="text-xs text-gray-500">{{ $permohonanBantuan->tarikh_disemak->format('d/m/Y H:i') }} - {{ $permohonanBantuan->penyemak->name ?? '-' }}</p>
                                    @if($permohonanBantuan->catatan_semakan)
                                        <p class="text-xs text-gray-600 mt-1">{{ $permohonanBantuan->catatan_semakan }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($permohonanBantuan->tarikh_lawatan)
                            <!-- Lawatan Rumah -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="material-icons text-purple-600" style="font-size: 16px !important;">home</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Lawatan Rumah</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($permohonanBantuan->tarikh_lawatan)->format('d/m/Y') }} {{ $permohonanBantuan->masa_lawatan }}</p>
                                    <p class="text-xs text-gray-600">Pegawai: {{ $permohonanBantuan->pegawai_lawatan }}</p>
                                    @if($permohonanBantuan->laporan_lawatan)
                                        <p class="text-xs text-gray-600 mt-1">{{ $permohonanBantuan->laporan_lawatan }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($permohonanBantuan->status_permohonan == 'Lulus')
                            <!-- Lulus -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="material-icons text-green-600" style="font-size: 16px !important;">check_circle</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Diluluskan</p>
                                    <p class="text-xs text-gray-500">{{ $permohonanBantuan->tarikh_diluluskan->format('d/m/Y H:i') }} - {{ $permohonanBantuan->pelulus->name ?? '-' }}</p>
                                    <p class="text-xs font-bold text-green-600 mt-1">Jumlah: RM {{ number_format($permohonanBantuan->jumlah_diluluskan, 2) }}</p>
                                    @if($permohonanBantuan->catatan_kelulusan)
                                        <p class="text-xs text-gray-600 mt-1">{{ $permohonanBantuan->catatan_kelulusan }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($permohonanBantuan->status_permohonan == 'Ditolak')
                            <!-- Ditolak -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <span class="material-icons text-red-600" style="font-size: 16px !important;">cancel</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Ditolak</p>
                                    <p class="text-xs text-gray-500">{{ $permohonanBantuan->tarikh_ditolak->format('d/m/Y H:i') }} - {{ $permohonanBantuan->penolak->name ?? '-' }}</p>
                                    @if($permohonanBantuan->sebab_tolak)
                                        <p class="text-xs text-red-600 mt-1">{{ $permohonanBantuan->sebab_tolak }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($permohonanBantuan->status_permohonan == 'Dibatalkan')
                            <!-- Dibatalkan -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <span class="material-icons text-gray-600" style="font-size: 16px !important;">block</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs font-medium text-gray-900">Dibatalkan</p>
                                    <p class="text-xs text-gray-500">{{ $permohonanBantuan->tarikh_dibatalkan->format('d/m/Y H:i') }} - {{ $permohonanBantuan->pembatal->name ?? '-' }}</p>
                                    @if($permohonanBantuan->sebab_batal)
                                        <p class="text-xs text-gray-600 mt-1">{{ $permohonanBantuan->sebab_batal }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if(auth()->user()->hasPermission('permohonan_bantuan', 'approve'))
                    <div class="flex gap-3 justify-end mt-6">
                        @if($permohonanBantuan->status_permohonan == 'Baharu')
                            <button onclick="showSemakModal()" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-xs font-medium rounded hover:bg-yellow-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">rate_review</span>
                                Semak
                            </button>
                        @endif

                        @if($permohonanBantuan->status_permohonan == 'Dalam Semakan')
                            <button onclick="showLawatanModal()" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">home</span>
                                Jadual Lawatan
                            </button>
                        @endif

                        @if(in_array($permohonanBantuan->status_permohonan, ['Dalam Semakan', 'Lawatan Rumah']))
                            <button onclick="showLulusModal()" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">check_circle</span>
                                Lulus
                            </button>
                            <button onclick="showTolakModal()" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                                Tolak
                            </button>
                        @endif

                        @if(in_array($permohonanBantuan->status_permohonan, ['Baharu', 'Dalam Semakan', 'Lawatan Rumah']))
                            <button onclick="showBatalModal()" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">block</span>
                                Batal
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Semak Modal -->
    <div id="semakModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-yellow-100">
                    <span class="material-icons text-yellow-600 text-xl">rate_review</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Semak Permohonan</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Permohonan akan ditukar status kepada "Dalam Semakan"</p>
                </div>
                <form method="POST" action="{{ route('permohonan-bantuan.semak', $permohonanBantuan) }}" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Semakan</label>
                        <textarea name="catatan_semakan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-xs"></textarea>
                    </div>
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeSemakModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs font-medium rounded hover:bg-yellow-700">Semak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lawatan Modal -->
    <div id="lawatanModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-purple-100">
                    <span class="material-icons text-purple-600 text-xl">home</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Jadual Lawatan Rumah</h3>
                <form method="POST" action="{{ route('permohonan-bantuan.lawatan', $permohonanBantuan) }}" class="mt-4 px-4">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Lawatan *</label>
                            <input type="date" name="tarikh_lawatan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masa Lawatan *</label>
                            <input type="time" name="masa_lawatan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pegawai Lawatan *</label>
                            <input type="text" name="pegawai_lawatan" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-6 mb-2">
                        <button type="button" onclick="closeLawatanModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700">Jadualkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lulus Modal -->
    <div id="lulusModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-green-100">
                    <span class="material-icons text-green-600 text-xl">check_circle</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Lulus Permohonan</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Permohonan akan ditukar status kepada "Lulus"</p>
                </div>
                <form method="POST" action="{{ route('permohonan-bantuan.lulus', $permohonanBantuan) }}" class="mt-4 px-4">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Diluluskan (RM) *</label>
                            <input type="number" step="0.01" name="jumlah_diluluskan" value="{{ $permohonanBantuan->jumlah_dipohon }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Kelulusan</label>
                            <textarea name="catatan_kelulusan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-xs"></textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-3 mt-6 mb-2">
                        <button type="button" onclick="closeLulusModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">Lulus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tolak Modal -->
    <div id="tolakModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-red-100">
                    <span class="material-icons text-red-600 text-xl">cancel</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Tolak Permohonan</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Permohonan akan ditukar status kepada "Ditolak"</p>
                </div>
                <form method="POST" action="{{ route('permohonan-bantuan.tolak', $permohonanBantuan) }}" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Penolakan *</label>
                        <textarea name="sebab_tolak" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs"></textarea>
                    </div>
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeTolakModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Batal Modal -->
    <div id="batalModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-gray-100">
                    <span class="material-icons text-gray-600 text-xl">block</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Batal Permohonan</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500">Permohonan akan ditukar status kepada "Dibatalkan"</p>
                </div>
                <form method="POST" action="{{ route('permohonan-bantuan.batal', $permohonanBantuan) }}" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Pembatalan *</label>
                        <textarea name="sebab_batal" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs"></textarea>
                    </div>
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeBatalModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function showSemakModal() { document.getElementById('semakModal').classList.remove('hidden'); }
        function closeSemakModal() { document.getElementById('semakModal').classList.add('hidden'); }
        function showLawatanModal() { document.getElementById('lawatanModal').classList.remove('hidden'); }
        function closeLawatanModal() { document.getElementById('lawatanModal').classList.add('hidden'); }
        function showLulusModal() { document.getElementById('lulusModal').classList.remove('hidden'); }
        function closeLulusModal() { document.getElementById('lulusModal').classList.add('hidden'); }
        function showTolakModal() { document.getElementById('tolakModal').classList.remove('hidden'); }
        function closeTolakModal() { document.getElementById('tolakModal').classList.add('hidden'); }
        function showBatalModal() { document.getElementById('batalModal').classList.remove('hidden'); }
        function closeBatalModal() { document.getElementById('batalModal').classList.add('hidden'); }

        // Close modals when clicking outside
        ['semakModal', 'lawatanModal', 'lulusModal', 'tolakModal', 'batalModal'].forEach(modalId => {
            document.getElementById(modalId).addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSemakModal();
                closeLawatanModal();
                closeLulusModal();
                closeTolakModal();
                closeBatalModal();
            }
        });
    </script>
</body>
</html>
