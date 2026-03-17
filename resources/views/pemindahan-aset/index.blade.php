<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemindahan Aset - E-Masjid</title>
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
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pemindahan Aset</h1>
                        <p class="text-xs text-gray-600">Rekod pemindahan aset dari satu lokasi ke lokasi lain</p>
                    </div>
                    @if(auth()->user()->hasPermission('pemindahan_aset', 'create'))
                    <a href="{{ route('pemindahan-aset.create') }}" class="mt-4 md:mt-0 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                        Tambah Pemindahan
                    </a>
                    @endif
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    @foreach($stats as $stat)
                    <div class="bg-{{ $stat['color'] }}-50 border border-{{ $stat['color'] }}-200 rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-{{ $stat['color'] }}-600 mb-1">{{ $stat['title'] }}</p>
                                <p class="text-xl font-bold text-{{ $stat['color'] }}-900">{{ $stat['value'] }}</p>
                            </div>
                            <span class="material-icons text-{{ $stat['color'] }}-600" style="font-size: 32px !important;">{{ $stat['icon'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('pemindahan-aset.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. rujukan, lokasi..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded text-xs">
                        
                        <select name="senarai_aset_id" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Aset</option>
                            @foreach($asetList as $aset)
                                <option value="{{ $aset->id }}" {{ request('senarai_aset_id') == $aset->id ? 'selected' : '' }}>
                                    {{ $aset->nama_aset }}
                                </option>
                            @endforeach
                        </select>

                        <select name="jenis_pemindahan" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Jenis</option>
                            <option value="Pemindahan Dalaman" {{ request('jenis_pemindahan') == 'Pemindahan Dalaman' ? 'selected' : '' }}>Dalaman</option>
                            <option value="Pemindahan Luaran" {{ request('jenis_pemindahan') == 'Pemindahan Luaran' ? 'selected' : '' }}>Luaran</option>
                        </select>

                        <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Dari">
                        <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" class="px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Hingga">

                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                            Cari
                        </button>
                        
                        <a href="{{ route('pemindahan-aset.index') }}" class="inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">No. Rujukan</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">Tarikh</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">Aset</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">Jenis</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">Lokasi Asal</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700">Lokasi Baru</th>
                                    <th class="px-4 py-3 text-xs font-medium text-gray-700 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pemindahanAset as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs text-gray-900 font-medium">{{ $item->no_pergerakan }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $item->tarikh_pergerakan ? $item->tarikh_pergerakan->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ $item->senariAset->nama_aset ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-1 rounded text-xs {{ $item->jenis_pergerakan == 'Pemindahan Dalaman' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ str_replace('Pemindahan ', '', $item->jenis_pergerakan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $item->lokasi_asal ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-900">{{ $item->lokasi_destinasi ?? '-' }}</td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('pemindahan-aset.show', $item)"
                                        :edit-route="route('pemindahan-aset.edit', $item)"
                                        module="aset"
                                        layout="desktop"
                                    />
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">swap_horiz</span>
                                        <p class="text-sm">Tiada rekod pemindahan aset dijumpai</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($pemindahanAset->hasPages())
                <div class="mt-4">
                    {{ $pemindahanAset->links() }}
                </div>
                @endif

                <!-- Record Count -->
                <div class="mt-4 text-xs text-gray-500">
                    Menunjukkan {{ $pemindahanAset->firstItem() ?? 0 }} - {{ $pemindahanAset->lastItem() ?? 0 }} daripada {{ $pemindahanAset->total() }} rekod
                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
