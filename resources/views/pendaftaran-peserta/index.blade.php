<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Peserta - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pendaftaran Peserta</h1>
                        <p class="text-xs text-gray-600">Pengurusan pendaftaran peserta program</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('pendaftaran_peserta', 'create'))
                            <a href="{{ route('pendaftaran-peserta.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Peserta
                            </a>
                        @endif
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('pendaftaran-peserta.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari nama peserta..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status_bayaran" :options="['Belum Bayar' => 'Belum Bayar', 'Sudah Bayar' => 'Sudah Bayar', 'Percuma' => 'Percuma']" :selected="request('status_bayaran')" placeholder="Status Bayaran" />
                            <x-filter-dropdown name="status_kehadiran" :options="['Belum Hadir' => 'Belum Hadir', 'Hadir' => 'Hadir', 'Tidak Hadir' => 'Tidak Hadir']" :selected="request('status_kehadiran')" placeholder="Status Kehadiran" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('pendaftaran-peserta.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Nama Peserta</th>
                                <th class="px-4 py-2 table-header">Program</th>
                                <th class="px-4 py-2 table-header">No. Telefon</th>
                                <th class="px-4 py-2 table-header">Tarikh Daftar</th>
                                <th class="px-4 py-2 table-header text-center">Bayaran</th>
                                <th class="px-4 py-2 table-header text-center">Kehadiran</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pesertaList as $peserta)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $peserta->nama_peserta }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $peserta->program->nama_program ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $peserta->no_telefon ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data">{{ $peserta->tarikh_daftar->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $peserta->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : ($peserta->status_bayaran === 'Percuma' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">{{ $peserta->status_bayaran }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $peserta->status_kehadiran === 'Hadir' ? 'bg-green-100 text-green-800' : ($peserta->status_kehadiran === 'Tidak Hadir' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ $peserta->status_kehadiran }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons :showUrl="route('pendaftaran-peserta.show', $peserta)" :editUrl="route('pendaftaran-peserta.edit', $peserta)" :deleteUrl="route('pendaftaran-peserta.destroy', $peserta)" />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">people</span><p class="text-sm">Tiada peserta dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pesertaList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Menunjukkan {{ $pesertaList->firstItem() }} hingga {{ $pesertaList->lastItem() }} daripada {{ $pesertaList->total() }} rekod</div>
                    <div class="flex space-x-1">{{ $pesertaList->appends(request()->query())->links('pagination::simple-tailwind') }}</div>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
