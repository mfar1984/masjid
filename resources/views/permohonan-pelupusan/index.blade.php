<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Pelupusan - E-Masjid</title>
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
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Permohonan Pelupusan</h1>
                        <p class="text-xs text-gray-600">Pengurusan permohonan pelupusan aset</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('permohonan_pelupusan', 'create'))
                            <a href="{{ route('permohonan-pelupusan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Permohonan
                            </a>
                        @endif
                    </div>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('permohonan-pelupusan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari no. rujukan, aset..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="kaedah_pelupusan" :options="['Jualan' => 'Jualan', 'Derma' => 'Derma', 'Buang' => 'Buang', 'Tukar Ganti' => 'Tukar Ganti']" :selected="request('kaedah_pelupusan')" placeholder="Semua Kaedah" />
                            <x-filter-dropdown name="status" :options="['Menunggu' => 'Menunggu', 'Diluluskan' => 'Diluluskan', 'Ditolak' => 'Ditolak', 'Selesai' => 'Selesai']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('permohonan-pelupusan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Rujukan</th>
                                <th class="px-4 py-2 table-header">Aset</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header text-right">Nilai (RM)</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($permohonanPelupusan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_rujukan }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $item->senariAset->nama_aset ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->senariAset->no_siri ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_permohonan->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_pelupusan }}</td>
                                    <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($item->nilai_pelupusan, 2) }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Menunggu</span>
                                        @elseif($item->status === 'Diluluskan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Diluluskan</span>
                                        @elseif($item->status === 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons module="permohonan_pelupusan" :record="$item" route-prefix="permohonan-pelupusan" :can-edit="$item->status === 'Menunggu'" :can-delete="$item->status === 'Menunggu'" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">delete_forever</span>
                                        <p class="text-sm">Tiada permohonan pelupusan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($permohonanPelupusan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->no_rujukan }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->senariAset->nama_aset ?? '-' }}</p>
                            </div>
                            <x-action-icons module="permohonan_pelupusan" :record="$item" route-prefix="permohonan-pelupusan" :mobile="true" :can-edit="$item->status === 'Menunggu'" :can-delete="$item->status === 'Menunggu'" />
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kaedah</p>
                                <span class="mobile-data text-gray-900">{{ $item->kaedah_pelupusan }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Nilai</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->nilai_pelupusan, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">delete_forever</span>
                        <p class="text-sm text-gray-500">Tiada permohonan pelupusan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                @if($permohonanPelupusan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $permohonanPelupusan->firstItem() }} hingga {{ $permohonanPelupusan->lastItem() }} daripada {{ $permohonanPelupusan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $permohonanPelupusan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
    <x-delete-modal id="deleteModal" title="Padam Permohonan Pelupusan" message="Adakah anda pasti ingin memadam permohonan ini?" :route="'permohonan-pelupusan.destroy'" />
</body>
</html>
