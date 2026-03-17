<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urusan Jenazah - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Urusan Jenazah</h1>
                        <p class="text-xs text-gray-600">Pengurusan rekod urusan jenazah</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('urusan_jenazah', 'create'))
                            <a href="{{ route('urusan-jenazah.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Rekod
                            </a>
                        @endif
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('urusan-jenazah.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari nama simati, no rujukan..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status" :options="['Dalam Proses' => 'Dalam Proses', 'Selesai' => 'Selesai']" :selected="request('status')" placeholder="Semua Status" />
                            <x-filter-dropdown name="jantina" :options="['Lelaki' => 'Lelaki', 'Perempuan' => 'Perempuan']" :selected="request('jantina')" placeholder="Semua Jantina" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('urusan-jenazah.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Rujukan</th>
                                <th class="px-4 py-2 table-header">Nama Simati</th>
                                <th class="px-4 py-2 table-header">Tarikh Meninggal</th>
                                <th class="px-4 py-2 table-header">Jantina</th>
                                <th class="px-4 py-2 table-header">Nama Waris</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jenazahList as $jenazah)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data font-medium text-gray-900">{{ $jenazah->no_rujukan }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $jenazah->nama_simati }}</div>
                                        <div class="text-2xs text-gray-500">{{ $jenazah->umur ? $jenazah->umur . ' tahun' : '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">{{ $jenazah->tarikh_meninggal->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jenazah->jantina === 'Lelaki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">{{ $jenazah->jantina }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $jenazah->nama_waris }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jenazah->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">{{ $jenazah->status }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons 
                                            :showUrl="route('urusan-jenazah.show', $jenazah)"
                                            :editUrl="route('urusan-jenazah.edit', $jenazah)"
                                            :deleteUrl="route('urusan-jenazah.destroy', $jenazah)"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">sentiment_very_satisfied</span><p class="text-sm">Tiada rekod dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse($jenazahList as $jenazah)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $jenazah->nama_simati }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $jenazah->no_rujukan }} | {{ $jenazah->tarikh_meninggal->format('d/m/Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jenazah->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">{{ $jenazah->status }}</span>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-action-icons :showUrl="route('urusan-jenazah.show', $jenazah)" :editUrl="route('urusan-jenazah.edit', $jenazah)" :deleteUrl="route('urusan-jenazah.destroy', $jenazah)" />
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8"><span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">sentiment_very_satisfied</span><p class="text-sm text-gray-500">Tiada rekod dijumpai</p></div>
                    @endforelse
                </div>
                @if($jenazahList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Menunjukkan {{ $jenazahList->firstItem() }} hingga {{ $jenazahList->lastItem() }} daripada {{ $jenazahList->total() }} rekod</div>
                    <div class="flex space-x-1">{{ $jenazahList->appends(request()->query())->links('pagination::simple-tailwind') }}</div>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
