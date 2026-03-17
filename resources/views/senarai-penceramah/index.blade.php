<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Penceramah - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Penceramah</h1>
                        <p class="text-xs text-gray-600">Pengurusan data penceramah masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('senarai_penceramah', 'create'))
                            <a href="{{ route('senarai-penceramah.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Penceramah
                            </a>
                        @endif
                    </div>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('senarai-penceramah.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari nama, telefon, no sijil..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status" :options="['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif']" :selected="request('status')" placeholder="Semua Status" />
                            <x-filter-dropdown name="negara" :options="['Malaysia' => 'Malaysia', 'Luar Negara' => 'Luar Negara']" :selected="request('negara')" placeholder="Semua Negara" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('senarai-penceramah.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Nama</th>
                                <th class="px-4 py-2 table-header">No. Telefon</th>
                                <th class="px-4 py-2 table-header">Negara</th>
                                <th class="px-4 py-2 table-header">No. Sijil Tauliah</th>
                                <th class="px-4 py-2 table-header">Bidang</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($penceramahList as $penceramah)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $penceramah->nama }}</div>
                                        <div class="text-2xs text-gray-500">{{ $penceramah->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $penceramah->no_telefon ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $penceramah->negara === 'Malaysia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $penceramah->negara }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $penceramah->no_sijil_tauliah ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $penceramah->bidang_kepakaran ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $penceramah->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $penceramah->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons 
                                            :showUrl="route('senarai-penceramah.show', $penceramah)"
                                            :editUrl="route('senarai-penceramah.edit', $penceramah)"
                                            :deleteUrl="route('senarai-penceramah.destroy', $penceramah)"
                                            deleteMessage="Padam penceramah {{ $penceramah->nama }}?"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">record_voice_over</span>
                                        <p class="text-sm">Tiada penceramah dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($penceramahList as $penceramah)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $penceramah->nama }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $penceramah->no_telefon ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $penceramah->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $penceramah->status }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $penceramah->bidang_kepakaran ?? '-' }}</span>
                            <x-action-icons 
                                :showUrl="route('senarai-penceramah.show', $penceramah)"
                                :editUrl="route('senarai-penceramah.edit', $penceramah)"
                                :deleteUrl="route('senarai-penceramah.destroy', $penceramah)"
                            />
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">record_voice_over</span>
                        <p class="text-sm text-gray-500">Tiada penceramah dijumpai</p>
                    </div>
                    @endforelse
                </div>

                @if($penceramahList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $penceramahList->firstItem() }} hingga {{ $penceramahList->lastItem() }} daripada {{ $penceramahList->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $penceramahList->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
