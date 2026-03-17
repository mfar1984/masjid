<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Program - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Program</h1>
                        <p class="text-xs text-gray-600">Pengurusan program dan pendidikan masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('senarai_program', 'create'))
                            <a href="{{ route('senarai-program.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Program
                            </a>
                        @endif
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('senarai-program.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari nama program..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="jenis_program" :options="['Kuliah' => 'Kuliah', 'Ceramah' => 'Ceramah', 'Kursus' => 'Kursus', 'Bengkel' => 'Bengkel', 'Seminar' => 'Seminar', 'Kem' => 'Kem']" :selected="request('jenis_program')" placeholder="Semua Jenis" />
                            <x-filter-dropdown name="status" :options="['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif', 'Selesai' => 'Selesai']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('senarai-program.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Nama Program</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header">Kapasiti</th>
                                <th class="px-4 py-2 table-header text-right">Yuran (RM)</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($programList as $program)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $program->nama_program }}</div>
                                        <div class="text-2xs text-gray-500">{{ $program->kod_program ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $program->jenis_program }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $program->kategori }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $program->kapasiti ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-right">{{ number_format($program->yuran, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $program->status === 'Aktif' ? 'bg-green-100 text-green-800' : ($program->status === 'Selesai' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">{{ $program->status }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons 
                                            :showUrl="route('senarai-program.show', $program)"
                                            :editUrl="route('senarai-program.edit', $program)"
                                            :deleteUrl="route('senarai-program.destroy', $program)"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">school</span><p class="text-sm">Tiada program dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse($programList as $program)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $program->nama_program }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $program->jenis_program }} | {{ $program->kategori }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $program->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $program->status }}</span>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-action-icons :showUrl="route('senarai-program.show', $program)" :editUrl="route('senarai-program.edit', $program)" :deleteUrl="route('senarai-program.destroy', $program)" />
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8"><span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">school</span><p class="text-sm text-gray-500">Tiada program dijumpai</p></div>
                    @endforelse
                </div>
                @if($programList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Menunjukkan {{ $programList->firstItem() }} hingga {{ $programList->lastItem() }} daripada {{ $programList->total() }} rekod</div>
                    <div class="flex space-x-1">{{ $programList->appends(request()->query())->links('pagination::simple-tailwind') }}</div>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
