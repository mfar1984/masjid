<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Tempahan Fasiliti - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Tempahan Fasiliti</h1>
                        <p class="text-xs text-gray-600">{{ $tempahanFasiliti->no_tempahan }} - {{ $tempahanFasiliti->nama_penyewa }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('tempahan-fasiliti.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') && !in_array($tempahanFasiliti->status_tempahan, ['Lulus', 'Ditolak', 'Selesai']))
                            <a href="{{ route('tempahan-fasiliti.edit', $tempahanFasiliti->id) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Workflow Buttons -->
                @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') || auth()->user()->hasPermission('tempahan_fasiliti', 'approve'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-3">Tindakan Workflow</h2>
                    <div class="flex flex-wrap gap-2">
                        @if($tempahanFasiliti->status_tempahan === 'Baharu' && auth()->user()->hasPermission('tempahan_fasiliti', 'update'))
                            <form action="{{ route('tempahan-fasiliti.semak', $tempahanFasiliti->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center h-[32px] px-4 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                                    Semak
                                </button>
                            </form>
                        @endif

                        @if($tempahanFasiliti->status_tempahan === 'Dalam Semakan' && auth()->user()->hasPermission('tempahan_fasiliti', 'approve'))
                            <form action="{{ route('tempahan-fasiliti.lulus', $tempahanFasiliti->id) }}" method="POST" class="inline" onsubmit="return confirm('Adakah anda pasti untuk meluluskan tempahan ini? Pembayaran Sewa akan dicipta secara automatik.')">
                                @csrf
                                <button type="submit" class="inline-flex items-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">check_circle</span>
                                    Lulus
                                </button>
                            </form>
                        @endif

                        @if(in_array($tempahanFasiliti->status_tempahan, ['Baharu', 'Dalam Semakan']) && auth()->user()->hasPermission('tempahan_fasiliti', 'update'))
                            <button type="button" onclick="showTolakModal()" class="inline-flex items-center h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                                Tolak
                            </button>
                        @endif

                        @if(!in_array($tempahanFasiliti->status_tempahan, ['Lulus', 'Ditolak', 'Dibatalkan', 'Selesai']) && auth()->user()->hasPermission('tempahan_fasiliti', 'delete'))
                            <button type="button" onclick="showBatalModal()" class="inline-flex items-center h-[32px] px-4 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">block</span>
                                Batal
                            </button>
                        @endif

                        @if($tempahanFasiliti->status_tempahan === 'Lulus' && $tempahanFasiliti->tarikh_tamat < now() && auth()->user()->hasPermission('tempahan_fasiliti', 'update'))
                            <form action="{{ route('tempahan-fasiliti.selesai', $tempahanFasiliti->id) }}" method="POST" class="inline" onsubmit="return confirm('Tandakan tempahan ini sebagai selesai?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">done_all</span>
                                    Tandakan Selesai
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 1: Maklumat Tempahan -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tempahan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Tempahan</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $tempahanFasiliti->no_tempahan }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Tempahan</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->tarikh_tempahan ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_tempahan)->format('d/m/Y') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh & Masa Mula</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $tempahanFasiliti->tarikh_mula ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_mula)->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh & Masa Tamat</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $tempahanFasiliti->tarikh_tamat ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_tamat)->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tempoh Sewa</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->tempoh_sewa }} {{ $tempahanFasiliti->unit_tempoh }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            <p class="text-xs">
                                @if($tempahanFasiliti->status_tempahan === 'Lulus')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                @elseif($tempahanFasiliti->status_tempahan === 'Baharu')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Baharu</span>
                                @elseif($tempahanFasiliti->status_tempahan === 'Dalam Semakan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Dalam Semakan</span>
                                @elseif($tempahanFasiliti->status_tempahan === 'Ditolak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @elseif($tempahanFasiliti->status_tempahan === 'Selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $tempahanFasiliti->status_tempahan }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 1.5: Senarai Item Tempahan -->
                @if($tempahanFasiliti->items && $tempahanFasiliti->items->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Senarai Item Tempahan ({{ $tempahanFasiliti->items->count() }} item)</h2>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Bil</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Fasiliti/Aset</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Jenis</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Kuantiti</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-700">Harga/Unit</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-700">Subtotal</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Status</th>
                                    @can('operasi', 'delete')
                                        @if($tempahanFasiliti->status_tempahan === 'Lulus' || $tempahanFasiliti->status_tempahan === 'Baharu')
                                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Tindakan</th>
                                        @endif
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tempahanFasiliti->items as $index => $item)
                                <tr class="{{ $item->status_item === 'Dibatalkan' ? 'bg-red-50 opacity-60' : '' }}">
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('senarai-fasiliti.show', $item->senariFasiliti->id) }}" class="text-xs text-blue-600 hover:underline font-semibold">
                                            {{ $item->senariFasiliti->nama_fasiliti }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ $item->senariFasiliti->jenis_fasiliti }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900 text-center font-semibold">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900 text-right">RM {{ number_format($item->harga_per_unit, 2) }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900 text-right font-semibold">RM {{ number_format($item->subtotal, 2) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->status_item === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                        @endif
                                    </td>
                                    @can('operasi', 'delete')
                                        @if($tempahanFasiliti->status_tempahan === 'Lulus' || $tempahanFasiliti->status_tempahan === 'Baharu')
                                            <td class="px-4 py-3 text-center">
                                                @if($item->status_item === 'Aktif')
                                                    <button onclick="openBatalItemModal({{ $item->id }}, '{{ $item->senariFasiliti->nama_fasiliti }}')" class="text-red-600 hover:text-red-800" title="Batal Item">
                                                        <span class="material-icons" style="font-size: 16px !important;">cancel</span>
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    @endcan
                                </tr>
                                @if($item->status_item === 'Dibatalkan' && $item->sebab_batal_item)
                                <tr class="bg-red-50">
                                    <td colspan="7" class="px-4 py-2 text-xs text-red-600">
                                        <span class="font-semibold">Sebab Batal:</span> {{ $item->sebab_batal_item }}
                                        @if($item->dibatalkanOleh)
                                            <span class="text-gray-600">- oleh {{ $item->dibatalkanOleh->name }} pada {{ $item->tarikh_dibatalkan ? \Carbon\Carbon::parse($item->tarikh_dibatalkan)->format('d/m/Y H:i') : '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-xs font-bold text-gray-900 text-right">JUMLAH:</td>
                                    <td class="px-4 py-3 text-xs font-bold text-blue-900 text-right">RM {{ number_format($tempahanFasiliti->activeItems->sum('subtotal'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach($tempahanFasiliti->items as $index => $item)
                        <div class="bg-white border {{ $item->status_item === 'Dibatalkan' ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded p-3">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold text-gray-900">Item {{ $index + 1 }}</span>
                                <div class="flex items-center gap-2">
                                    @if($item->status_item === 'Aktif')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @can('operasi', 'delete')
                                            @if($tempahanFasiliti->status_tempahan === 'Lulus' || $tempahanFasiliti->status_tempahan === 'Baharu')
                                                <button onclick="openBatalItemModal({{ $item->id }}, '{{ $item->senariFasiliti->nama_fasiliti }}')" class="text-red-600 hover:text-red-800" title="Batal Item">
                                                    <span class="material-icons" style="font-size: 16px !important;">cancel</span>
                                                </button>
                                            @endif
                                        @endcan
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs"><span class="font-semibold">Fasiliti:</span> 
                                    <a href="{{ route('senarai-fasiliti.show', $item->senariFasiliti->id) }}" class="text-blue-600 hover:underline">
                                        {{ $item->senariFasiliti->nama_fasiliti }}
                                    </a>
                                </p>
                                <p class="text-xs"><span class="font-semibold">Jenis:</span> {{ $item->senariFasiliti->jenis_fasiliti }}</p>
                                <p class="text-xs"><span class="font-semibold">Kuantiti:</span> {{ $item->quantity }}</p>
                                <p class="text-xs"><span class="font-semibold">Harga/Unit:</span> RM {{ number_format($item->harga_per_unit, 2) }}</p>
                                <p class="text-xs"><span class="font-semibold">Subtotal:</span> <span class="font-bold text-blue-600">RM {{ number_format($item->subtotal, 2) }}</span></p>
                            </div>
                            @if($item->status_item === 'Dibatalkan' && $item->sebab_batal_item)
                            <div class="mt-2 pt-2 border-t border-red-200">
                                <p class="text-xs text-red-600"><span class="font-semibold">Sebab Batal:</span> {{ $item->sebab_batal_item }}</p>
                                @if($item->dibatalkanOleh)
                                    <p class="text-xs text-gray-600 mt-1">Oleh {{ $item->dibatalkanOleh->name }} pada {{ $item->tarikh_dibatalkan ? \Carbon\Carbon::parse($item->tarikh_dibatalkan)->format('d/m/Y H:i') : '-' }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                        <div class="bg-gray-100 border border-gray-300 rounded p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-900">JUMLAH:</span>
                                <span class="text-xs font-bold text-blue-900">RM {{ number_format($tempahanFasiliti->activeItems->sum('subtotal'), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section 2: Maklumat Penyewa -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Penyewa</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Penyewa</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $tempahanFasiliti->nama_penyewa }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. IC</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->no_ic_penyewa }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">No. Telefon</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->no_telefon_penyewa }}</p>
                        </div>

                        @if($tempahanFasiliti->emel_penyewa)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Emel</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->emel_penyewa }}</p>
                        </div>
                        @endif

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat</label>
                            <p class="text-xs text-gray-900">
                                {{ $tempahanFasiliti->alamat_penyewa_1 }}<br>
                                @if($tempahanFasiliti->alamat_penyewa_2){{ $tempahanFasiliti->alamat_penyewa_2 }}<br>@endif
                                {{ $tempahanFasiliti->poskod_penyewa }} {{ $tempahanFasiliti->bandar_penyewa }}<br>
                                {{ $tempahanFasiliti->negeri_penyewa }}
                            </p>
                        </div>

                        @if($tempahanFasiliti->organisasi_penyewa)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Organisasi</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->organisasi_penyewa }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 3: Tujuan & Acara -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Tujuan & Acara</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tujuan Tempahan</label>
                            <p class="text-xs text-gray-900 whitespace-pre-line">{{ $tempahanFasiliti->tujuan_tempahan }}</p>
                        </div>

                        @if($tempahanFasiliti->jenis_acara)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Acara</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->jenis_acara }}</p>
                        </div>
                        @endif

                        @if($tempahanFasiliti->bilangan_jangka_peserta)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bilangan Jangka Peserta</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->bilangan_jangka_peserta }} orang</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4: Lokasi Destinasi -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Lokasi Destinasi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Lokasi</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->is_lokasi_luaran ? 'Luaran (Luar Masjid)' : 'Dalaman (Dalam Masjid)' }}</p>
                        </div>

                        @if(!$tempahanFasiliti->is_lokasi_luaran && $tempahanFasiliti->lokasi_destinasi)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lokasi Dalam Masjid</label>
                            <p class="text-xs text-gray-900 font-semibold">{{ $tempahanFasiliti->lokasi_destinasi }}</p>
                        </div>
                        @endif

                        @if($tempahanFasiliti->is_lokasi_luaran)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Lokasi Luaran</label>
                            <p class="text-xs text-gray-900">
                                @if($tempahanFasiliti->nama_tempat_luaran)<strong>{{ $tempahanFasiliti->nama_tempat_luaran }}</strong><br>@endif
                                {{ $tempahanFasiliti->alamat_luaran_1 ?? '' }}<br>
                                @if($tempahanFasiliti->alamat_luaran_2){{ $tempahanFasiliti->alamat_luaran_2 }}<br>@endif
                                {{ $tempahanFasiliti->poskod_luaran ?? '' }} {{ $tempahanFasiliti->bandar_luaran ?? '' }}<br>
                                {{ $tempahanFasiliti->negeri_luaran ?? '' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4.5: Status Pemulangan -->
                @if($tempahanFasiliti->status_tempahan === 'Lulus' || $tempahanFasiliti->status_tempahan === 'Selesai')
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-gray-900">Status Pemulangan</h2>
                        @if($tempahanFasiliti->status_tempahan === 'Lulus' && $tempahanFasiliti->status_pemulangan !== 'Sudah Pulang' && auth()->user()->hasPermission('tempahan_fasiliti', 'update'))
                            <button type="button" onclick="showPulangModal()" class="inline-flex items-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">assignment_return</span>
                                Rekod Pulangan
                            </button>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status Pulangan</label>
                            <p class="text-xs">
                                @if($tempahanFasiliti->status_pemulangan === 'Sudah Pulang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                @elseif($tempahanFasiliti->status_pemulangan === 'Lewat')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                @elseif($tempahanFasiliti->status_pemulangan === 'Sebahagian')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Sebahagian</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Pulang</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Jangka Pulangan</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->tarikh_tamat ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_tamat)->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($tempahanFasiliti->tarikh_sebenar_pulangan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Sebenar Pulangan</label>
                            <p class="text-xs text-gray-900">{{ \Carbon\Carbon::parse($tempahanFasiliti->tarikh_sebenar_pulangan)->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 4.6: Pergerakan Aset Berkaitan -->
                @if($tempahanFasiliti->pergerakanAset && $tempahanFasiliti->pergerakanAset->count() > 0)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Pergerakan Aset Berkaitan ({{ $tempahanFasiliti->pergerakanAset->count() }} rekod)</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">No. Pergerakan</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Aset</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Kuantiti</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tempahanFasiliti->pergerakanAset as $pergerakan)
                                <tr>
                                    <td class="px-4 py-3 text-xs text-blue-600 font-semibold">
                                        <a href="{{ route('pergerakan-aset.show', $pergerakan->id) }}" class="hover:underline">
                                            {{ $pergerakan->no_pergerakan }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ $pergerakan->senariAset->nama_aset ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900 text-center">{{ $pergerakan->kuantiti ?? 1 }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($pergerakan->status_pulangan === 'Sudah Pulang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                        @elseif($pergerakan->status_pulangan === 'Lewat')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Pulang</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('pergerakan-aset.show', $pergerakan->id) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                            <span class="material-icons" style="font-size: 18px;">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Section 5: Harga & Bayaran -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Harga & Bayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Harga Sewa</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($tempahanFasiliti->harga_sewa, 2) }}</p>
                        </div>

                        @if($tempahanFasiliti->deposit)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Deposit</label>
                            <p class="text-xs text-gray-900 font-semibold">RM {{ number_format($tempahanFasiliti->deposit, 2) }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Bayaran</label>
                            <p class="text-xs text-gray-900 font-bold text-blue-600">RM {{ number_format($tempahanFasiliti->jumlah_bayaran, 2) }}</p>
                        </div>
                    </div>

                    @if($tempahanFasiliti->pembayaranSewa)
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <label class="block text-xs font-medium text-gray-500 mb-2">Pembayaran Sewa</label>
                        <a href="{{ route('pembayaran-sewa.show', $tempahanFasiliti->pembayaranSewa->id) }}" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                            <span class="material-icons mr-1" style="font-size: 16px !important;">receipt</span>
                            {{ $tempahanFasiliti->pembayaranSewa->no_pembayaran }} - {{ $tempahanFasiliti->pembayaranSewa->status_pembayaran }}
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Section 5: Dokumen -->
                @if($tempahanFasiliti->surat_permohonan_path || $tempahanFasiliti->salinan_ic_path || $tempahanFasiliti->surat_sokongan_path || $tempahanFasiliti->dokumen_lain)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Dokumen</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($tempahanFasiliti->surat_permohonan_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Surat Permohonan</label>
                            <a href="{{ Storage::url($tempahanFasiliti->surat_permohonan_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        @if($tempahanFasiliti->salinan_ic_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Salinan IC</label>
                            <a href="{{ Storage::url($tempahanFasiliti->salinan_ic_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">badge</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        @if($tempahanFasiliti->surat_sokongan_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Surat Sokongan</label>
                            <a href="{{ Storage::url($tempahanFasiliti->surat_sokongan_path) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                <span class="material-icons mr-1" style="font-size: 16px !important;">description</span>
                                Lihat Dokumen
                            </a>
                        </div>
                        @endif

                        @if($tempahanFasiliti->dokumen_lain)
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-2">Dokumen Lain</label>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $dokumenArray = is_array($tempahanFasiliti->dokumen_lain) 
                                        ? $tempahanFasiliti->dokumen_lain 
                                        : json_decode($tempahanFasiliti->dokumen_lain, true) ?? [];
                                @endphp
                                @foreach($dokumenArray as $index => $dokumen)
                                    <a href="{{ Storage::url($dokumen) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:underline">
                                        <span class="material-icons mr-1" style="font-size: 16px !important;">attach_file</span>
                                        Dokumen {{ $index + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Section 6: Workflow Timeline -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Workflow Timeline</h2>
                    
                    <div class="space-y-3">
                        <!-- Dicipta -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                    <span class="material-icons text-blue-600" style="font-size: 16px !important;">add_circle</span>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-900">Dicipta</p>
                                <p class="text-[10px] text-gray-600">{{ $tempahanFasiliti->createdBy->name ?? '-' }} pada {{ $tempahanFasiliti->created_at ? $tempahanFasiliti->created_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>

                        <!-- Disemak -->
                        @if($tempahanFasiliti->disemak_oleh)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                    <span class="material-icons text-yellow-600" style="font-size: 16px !important;">search</span>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-900">Disemak</p>
                                <p class="text-[10px] text-gray-600">{{ $tempahanFasiliti->disemakOleh->name ?? '-' }} pada {{ $tempahanFasiliti->tarikh_disemak ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_disemak)->format('d/m/Y H:i') : '-' }}</p>
                                @if($tempahanFasiliti->catatan_semakan)
                                <p class="text-[10px] text-gray-600 mt-1">Catatan: {{ $tempahanFasiliti->catatan_semakan }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Diluluskan -->
                        @if($tempahanFasiliti->diluluskan_oleh)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                    <span class="material-icons text-green-600" style="font-size: 16px !important;">check_circle</span>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-900">Diluluskan</p>
                                <p class="text-[10px] text-gray-600">{{ $tempahanFasiliti->diluluskanOleh->name ?? '-' }} pada {{ $tempahanFasiliti->tarikh_diluluskan ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_diluluskan)->format('d/m/Y H:i') : '-' }}</p>
                                @if($tempahanFasiliti->catatan_kelulusan)
                                <p class="text-[10px] text-gray-600 mt-1">Catatan: {{ $tempahanFasiliti->catatan_kelulusan }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Ditolak -->
                        @if($tempahanFasiliti->ditolak_oleh)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                    <span class="material-icons text-red-600" style="font-size: 16px !important;">cancel</span>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-900">Ditolak</p>
                                <p class="text-[10px] text-gray-600">{{ $tempahanFasiliti->ditolakOleh->name ?? '-' }} pada {{ $tempahanFasiliti->tarikh_ditolak ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_ditolak)->format('d/m/Y H:i') : '-' }}</p>
                                @if($tempahanFasiliti->sebab_tolak)
                                <p class="text-[10px] text-red-600 mt-1 font-semibold">Sebab: {{ $tempahanFasiliti->sebab_tolak }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Dibatalkan -->
                        @if($tempahanFasiliti->dibatalkan_oleh)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-orange-100">
                                    <span class="material-icons text-orange-600" style="font-size: 16px !important;">block</span>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-900">Dibatalkan</p>
                                <p class="text-[10px] text-gray-600">{{ $tempahanFasiliti->dibatalkanOleh->name ?? '-' }} pada {{ $tempahanFasiliti->tarikh_dibatalkan ? \Carbon\Carbon::parse($tempahanFasiliti->tarikh_dibatalkan)->format('d/m/Y H:i') : '-' }}</p>
                                @if($tempahanFasiliti->sebab_batal)
                                <p class="text-[10px] text-orange-600 mt-1 font-semibold">Sebab: {{ $tempahanFasiliti->sebab_batal }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 7: Catatan -->
                @if($tempahanFasiliti->catatan)
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Catatan</h2>
                    <p class="text-xs text-gray-900 whitespace-pre-line">{{ $tempahanFasiliti->catatan }}</p>
                </div>
                @endif

                <!-- Section 8: Maklumat Sistem -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Sistem</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Masjid</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->masjid->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dicipta Oleh</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->createdBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dicipta</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->created_at ? $tempahanFasiliti->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        @if($tempahanFasiliti->updated_at && $tempahanFasiliti->updated_at != $tempahanFasiliti->created_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dikemaskini Oleh</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->updatedBy->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarikh Dikemaskini</label>
                            <p class="text-xs text-gray-900">{{ $tempahanFasiliti->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Modal Tolak -->
    <div id="tolakModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Tolak Tempahan</h3>
            <form action="{{ route('tempahan-fasiliti.tolak', $tempahanFasiliti->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Tolak <span class="text-red-500">*</span></label>
                    <textarea name="sebab_tolak" required rows="4" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideTolakModal()" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                        Tolak Tempahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Batal -->
    <div id="batalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Batal Tempahan</h3>
            <form action="{{ route('tempahan-fasiliti.batal', $tempahanFasiliti->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Batal <span class="text-red-500">*</span></label>
                    <textarea name="sebab_batal" required rows="4" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideBatalModal()" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Tutup
                    </button>
                    <button type="submit" class="h-[32px] px-4 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700">
                        Batal Tempahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Batal Item -->
    <div id="batalItemModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Batal Item</h3>
            <form id="batalItemForm" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-xs text-gray-700 mb-2">Anda pasti mahu membatalkan item: <span id="itemName" class="font-bold"></span>?</p>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Batal Item <span class="text-red-500">*</span></label>
                    <textarea name="sebab_batal_item" required rows="4" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Item rosak, tidak diperlukan lagi, dll"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideBatalItemModal()" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Tutup
                    </button>
                    <button type="submit" class="h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                        Batal Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pulang -->
    <div id="pulangModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Rekod Pemulangan</h3>
            <form action="{{ route('tempahan-fasiliti.pulang', $tempahanFasiliti->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kondisi Selepas Pulang <span class="text-red-500">*</span></label>
                    <select name="kondisi_selepas" required class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Rosak Ringan">Rosak Ringan</option>
                        <option value="Rosak Teruk">Rosak Teruk</option>
                        <option value="Hilang">Hilang</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Pulangan</label>
                    <textarea name="catatan_pulangan" rows="3" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan tambahan..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hidePulangModal()" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                        Rekod Pulangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTolakModal() {
            document.getElementById('tolakModal').classList.remove('hidden');
        }
        function hideTolakModal() {
            document.getElementById('tolakModal').classList.add('hidden');
        }
        function showBatalModal() {
            document.getElementById('batalModal').classList.remove('hidden');
        }
        function hideBatalModal() {
            document.getElementById('batalModal').classList.add('hidden');
        }
        function openBatalItemModal(itemId, itemName) {
            const modal = document.getElementById('batalItemModal');
            const form = document.getElementById('batalItemForm');
            const nameSpan = document.getElementById('itemName');
            
            form.action = `/tempahan-fasiliti/{{ $tempahanFasiliti->id }}/item/${itemId}/batal`;
            nameSpan.textContent = itemName;
            modal.classList.remove('hidden');
        }
        function hideBatalItemModal() {
            document.getElementById('batalItemModal').classList.add('hidden');
        }
        function showPulangModal() {
            document.getElementById('pulangModal').classList.remove('hidden');
        }
        function hidePulangModal() {
            document.getElementById('pulangModal').classList.add('hidden');
        }
    </script>
</body>
</html>
