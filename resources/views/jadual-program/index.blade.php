<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Program - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Jadual Program</h1>
                        <p class="text-xs text-gray-600">Pengurusan jadual program dan pendidikan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('jadual_program', 'create'))
                            <a href="{{ route('jadual-program.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Jadual
                            </a>
                        @endif
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('jadual-program.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari program, penceramah..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status" :options="['Dijadual' => 'Dijadual', 'Sedang Berlangsung' => 'Sedang Berlangsung', 'Selesai' => 'Selesai', 'Batal' => 'Batal']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('jadual-program.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Program</th>
                                <th class="px-4 py-2 table-header">Masa</th>
                                <th class="px-4 py-2 table-header">Lokasi</th>
                                <th class="px-4 py-2 table-header">Penceramah</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jadualList as $jadual)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">{{ $jadual->tarikh->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $jadual->program->nama_program ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data">{{ $jadual->masa_mula }} - {{ $jadual->masa_tamat }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $jadual->lokasi ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $jadual->penceramah ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status === 'Selesai' ? 'bg-green-100 text-green-800' : ($jadual->status === 'Batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">{{ $jadual->status }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons :showUrl="route('jadual-program.show', $jadual)" :editUrl="route('jadual-program.edit', $jadual)" :deleteUrl="route('jadual-program.destroy', $jadual)" />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">event</span><p class="text-sm">Tiada jadual dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
