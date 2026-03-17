<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod Pelupusan - E-Masjid</title>
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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Rekod Pelupusan</h1>
                    <p class="text-xs text-gray-600">Sejarah aset yang telah dilupuskan</p>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('rekod-pelupusan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari no. rujukan, aset..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="kaedah_pelupusan" :options="['Jualan' => 'Jualan', 'Derma' => 'Derma', 'Buang' => 'Buang', 'Tukar Ganti' => 'Tukar Ganti']" :selected="request('kaedah_pelupusan')" placeholder="Semua Kaedah" />
                            @if(count($years) > 0)
                            <select name="tahun" class="px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('rekod-pelupusan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-purple-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Rujukan</th>
                                <th class="px-4 py-2 table-header">Aset</th>
                                <th class="px-4 py-2 table-header">Tarikh Pelupusan</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header text-right">Nilai (RM)</th>
                                <th class="px-4 py-2 table-header">Diluluskan Oleh</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($rekodPelupusan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_rujukan }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $item->senariAset->nama_aset ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->senariAset->no_siri ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_pelupusan ? $item->tarikh_pelupusan->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_pelupusan }}</td>
                                    <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($item->nilai_pelupusan, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->diluluskanOleh->name ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <a href="{{ route('rekod-pelupusan.show', $item) }}" class="p-1 text-blue-600 hover:text-blue-800" title="Lihat">
                                            <span class="material-icons" style="font-size: 18px !important;">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">history</span>
                                        <p class="text-sm">Tiada rekod pelupusan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($rekodPelupusan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->no_rujukan }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->senariAset->nama_aset ?? '-' }}</p>
                            </div>
                            <a href="{{ route('rekod-pelupusan.show', $item) }}" class="p-1 text-blue-600">
                                <span class="material-icons" style="font-size: 18px !important;">visibility</span>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_pelupusan ? $item->tarikh_pelupusan->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Nilai</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->nilai_pelupusan, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">history</span>
                        <p class="text-sm text-gray-500">Tiada rekod pelupusan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                @if($rekodPelupusan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $rekodPelupusan->firstItem() }} hingga {{ $rekodPelupusan->lastItem() }} daripada {{ $rekodPelupusan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $rekodPelupusan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
