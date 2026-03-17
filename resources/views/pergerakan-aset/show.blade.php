<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pergerakan Aset - E-Masjid</title>
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
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Pergerakan Aset</h1>
                        <p class="text-xs text-gray-600">{{ $pergerakanAset->no_pergerakan }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('pergerakan-aset.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('pergerakan_aset', 'update'))
                            <a href="{{ route('pergerakan-aset.edit', $pergerakanAset->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Workflow Actions -->
                @if($pergerakanAset->require_approval && !$pergerakanAset->diluluskan_oleh)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-800 mb-1">Menunggu Kelulusan</h3>
                            <p class="text-xs text-yellow-700">Pergerakan aset ke lokasi luaran memerlukan kelulusan.</p>
                        </div>
                        @if(auth()->user()->hasPermission('pergerakan_aset', 'approve'))
                        <form method="POST" action="{{ route('pergerakan-aset.lulus', $pergerakanAset->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                Luluskan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif

                @if($pergerakanAset->status_pulangan === 'Belum Pulang' && $pergerakanAset->diluluskan_oleh)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-blue-800 mb-1">Aset Belum Pulang</h3>
                            <p class="text-xs text-blue-700">Tandakan status pulangan aset.</p>
                        </div>
                        <div class="flex space-x-2">
                            @if(auth()->user()->hasPermission('pergerakan_aset', 'update'))
                            <button onclick="document.getElementById('modal-pulang').classList.remove('hidden')" class="px-4 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                Sudah Pulang
                            </button>
                            <form method="POST" action="{{ route('pergerakan-aset.lewat', $pergerakanAset->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-xs rounded hover:bg-orange-700">
                                    Lewat Pulang
                                </button>
                            </form>
                            <button onclick="document.getElementById('modal-hilang').classList.remove('hidden')" class="px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Hilang
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section 1: Maklumat Aset -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Aset</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Aset</label>
                            <p class="text-xs text-gray-900 font-semibold">
                                <a href="{{ route('senarai-aset.show', $pergerakanAset->senariAset->id) }}" class="text-blue-600 hover:underline">
                                    {{ $pergerakanAset->senariAset->no_aset }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Aset</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->senariAset->nama_aset }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->senariAset->kategoriAset->nama_kategori ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Asal</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->lokasi_asal }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Maklumat Pergerakan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pergerakan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Pergerakan</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->no_pergerakan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Pergerakan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->tarikh_pergerakan ? \Carbon\Carbon::parse($pergerakanAset->tarikh_pergerakan)->format('d/m/Y') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Pergerakan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->jenis_pergerakan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kondisi Sebelum</label>
                            <p class="text-xs">
                                @if($pergerakanAset->kondisi_sebelum === 'Baru' || $pergerakanAset->kondisi_sebelum === 'Baik')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $pergerakanAset->kondisi_sebelum }}</span>
                                @elseif($pergerakanAset->kondisi_sebelum === 'Sederhana')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $pergerakanAset->kondisi_sebelum }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $pergerakanAset->kondisi_sebelum }}</span>
                                @endif
                            </p>
                        </div>

                        @if($pergerakanAset->kondisi_selepas)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kondisi Selepas</label>
                            <p class="text-xs">
                                @if($pergerakanAset->kondisi_selepas === 'Baru' || $pergerakanAset->kondisi_selepas === 'Baik')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $pergerakanAset->kondisi_selepas }}</span>
                                @elseif($pergerakanAset->kondisi_selepas === 'Sederhana')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $pergerakanAset->kondisi_selepas }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $pergerakanAset->kondisi_selepas }}</span>
                                @endif
                            </p>
                        </div>
                        @endif

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sebab Pergerakan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->sebab_pergerakan }}</p>
                        </div>

                        @if($pergerakanAset->tempahan_fasiliti_id && $pergerakanAset->tempahanFasiliti)
                        <div class="md:col-span-2 pt-3 border-t border-blue-200">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta dari Tempahan</label>
                            <a href="{{ route('tempahan-fasiliti.show', $pergerakanAset->tempahan_fasiliti_id) }}" class="inline-flex items-center text-xs text-blue-600 hover:underline font-semibold">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">event</span>
                                {{ $pergerakanAset->tempahanFasiliti->no_tempahan }} - {{ $pergerakanAset->tempahanFasiliti->nama_penyewa }}
                            </a>
                        </div>
                        @endif

                        @if($pergerakanAset->kuantiti && $pergerakanAset->kuantiti > 1)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kuantiti</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->kuantiti }} unit</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 3: Lokasi Destinasi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Lokasi Destinasi</h2>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Lokasi</label>
                            <p class="text-xs">
                                @if($pergerakanAset->is_lokasi_luaran)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">Lokasi Luaran</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Lokasi Dalaman</span>
                                @endif
                            </p>
                        </div>

                        @if($pergerakanAset->is_lokasi_luaran)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Tempat</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->nama_tempat_luaran }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                            <p class="text-xs text-gray-900">
                                {{ $pergerakanAset->alamat_luaran_1 }}<br>
                                @if($pergerakanAset->alamat_luaran_2){{ $pergerakanAset->alamat_luaran_2 }}<br>@endif
                                {{ $pergerakanAset->poskod_luaran }} {{ $pergerakanAset->bandar_luaran }}<br>
                                {{ $pergerakanAset->negeri_luaran }}
                            </p>
                        </div>
                        @else
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Destinasi</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->lokasi_destinasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4: Maklumat Peminjam -->
                @if($pergerakanAset->nama_peminjam)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Peminjam</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Peminjam</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $pergerakanAset->nama_peminjam }}</p>
                        </div>

                        @if($pergerakanAset->no_ic_peminjam)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. IC</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->no_ic_peminjam }}</p>
                        </div>
                        @endif

                        @if($pergerakanAset->no_telefon_peminjam)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telefon</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->no_telefon_peminjam }}</p>
                        </div>
                        @endif

                        @if($pergerakanAset->organisasi_peminjam)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Organisasi</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->organisasi_peminjam }}</p>
                        </div>
                        @endif

                        @if($pergerakanAset->tarikh_jangka_pulangan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Jangka Pulangan</label>
                            <p class="text-xs text-gray-900">{{ \Carbon\Carbon::parse($pergerakanAset->tarikh_jangka_pulangan)->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        @if($pergerakanAset->tarikh_sebenar_pulangan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Sebenar Pulangan</label>
                            <p class="text-xs text-gray-900">{{ \Carbon\Carbon::parse($pergerakanAset->tarikh_sebenar_pulangan)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 5: Status & Kelulusan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Kelulusan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Pulangan</label>
                            <p class="text-xs">
                                @if($pergerakanAset->status_pulangan === 'Sudah Pulang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                @elseif($pergerakanAset->status_pulangan === 'Lewat')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                @elseif($pergerakanAset->status_pulangan === 'Hilang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $pergerakanAset->status_pulangan }}</span>
                                @endif
                            </p>
                        </div>

                        @if($pergerakanAset->require_approval)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Kelulusan</label>
                            <p class="text-xs">
                                @if($pergerakanAset->diluluskan_oleh)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Diluluskan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Kelulusan</span>
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($pergerakanAset->diluluskan_oleh)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Diluluskan Oleh</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->diluluskanOleh->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Diluluskan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->tarikh_diluluskan ? \Carbon\Carbon::parse($pergerakanAset->tarikh_diluluskan)->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($pergerakanAset->catatan_kelulusan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Catatan Kelulusan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->catatan_kelulusan }}</p>
                        </div>
                        @endif
                        @endif

                        @if($pergerakanAset->catatan)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 6: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->created_at ? $pergerakanAset->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($pergerakanAset->updated_at && $pergerakanAset->updated_at != $pergerakanAset->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $pergerakanAset->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Pulang -->
    <div id="modal-pulang" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Tandakan Aset Sudah Pulang</h3>
            <form method="POST" action="{{ route('pergerakan-aset.pulang', $pergerakanAset->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="kondisi_selepas_modal" class="block text-xs font-medium text-gray-700 mb-2">Kondisi Selepas *</label>
                    <select id="kondisi_selepas_modal" name="kondisi_selepas" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baru">Baru</option>
                        <option value="Baik">Baik</option>
                        <option value="Sederhana">Sederhana</option>
                        <option value="Teruk">Teruk</option>
                        <option value="Rosak">Rosak</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="catatan_modal" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea id="catatan_modal" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modal-pulang').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hilang -->
    <div id="modal-hilang" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Tandakan Aset Hilang</h3>
            <form method="POST" action="{{ route('pergerakan-aset.hilang', $pergerakanAset->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="catatan_hilang" class="block text-xs font-medium text-gray-700 mb-2">Catatan *</label>
                    <textarea id="catatan_hilang" name="catatan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" placeholder="Nyatakan butiran kehilangan aset"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modal-hilang').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                        Tandakan Hilang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />
</body>
</html>
