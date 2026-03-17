<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Imam - E-Masjid</title>
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
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Jadual Imam</h1>
                        <p class="text-xs text-gray-600">Pengurusan jadual tugas imam</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('jadual-imam.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700">
                            <span class="material-icons text-sm mr-1">add</span>Tambah Jadual
                        </a>
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('jadual-imam.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari nama imam..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="waktu_solat" :options="['Subuh' => 'Subuh', 'Zohor' => 'Zohor', 'Asar' => 'Asar', 'Maghrib' => 'Maghrib', 'Isyak' => 'Isyak', 'Jumaat' => 'Jumaat', 'Tarawih' => 'Tarawih']" :selected="request('waktu_solat')" placeholder="Semua Waktu" />
                            <x-filter-dropdown name="status" :options="['Dijadual' => 'Dijadual', 'Selesai' => 'Selesai', 'Ganti' => 'Ganti', 'Batal' => 'Batal']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('jadual-imam.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Waktu Solat</th>
                                <th class="px-4 py-2 table-header">Nama Imam</th>
                                <th class="px-4 py-2 table-header">Nama Ganti</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jadualList as $jadual)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">{{ $jadual->tarikh->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $jadual->waktu_solat }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $jadual->nama_imam ?? ($jadual->ajk->nama ?? '-') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $jadual->nama_ganti ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status === 'Selesai' ? 'bg-green-100 text-green-800' : ($jadual->status === 'Batal' ? 'bg-red-100 text-red-800' : ($jadual->status === 'Ganti' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800')) }}">
                                            {{ $jadual->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons 
                                            :showUrl="route('jadual-imam.show', $jadual)"
                                            :editUrl="route('jadual-imam.edit', $jadual)"
                                            :deleteUrl="route('jadual-imam.destroy', $jadual)"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">event</span><p class="text-sm">Tiada jadual dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse($jadualList as $jadual)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $jadual->nama_imam ?? ($jadual->ajk->nama ?? '-') }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $jadual->tarikh->format('d/m/Y') }} - {{ $jadual->waktu_solat }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ $jadual->status }}</span>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-action-icons :showUrl="route('jadual-imam.show', $jadual)" :editUrl="route('jadual-imam.edit', $jadual)" :deleteUrl="route('jadual-imam.destroy', $jadual)" />
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8"><span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">event</span><p class="text-sm text-gray-500">Tiada jadual dijumpai</p></div>
                    @endforelse
                </div>
                @if($jadualList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Menunjukkan {{ $jadualList->firstItem() }} hingga {{ $jadualList->lastItem() }} daripada {{ $jadualList->total() }} rekod</div>
                    <div class="flex space-x-1">{{ $jadualList->appends(request()->query())->links('pagination::simple-tailwind') }}</div>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
