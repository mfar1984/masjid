<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Sewa - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Sewa</h1>
                        <p class="text-xs text-gray-600">Senarai pembayaran sewa fasiliti</p>
                    </div>
                    @if(auth()->user()->hasPermission('pembayaran_sewa', 'create'))
                        <a href="{{ route('pembayaran-sewa.create') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                            Tambah Pembayaran
                        </a>
                    @endif
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-600 mb-1">Total Pembayaran</p>
                                <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                            <span class="material-icons text-blue-600" style="font-size: 32px !important;">receipt</span>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-600 mb-1">Sudah Bayar</p>
                                <p class="text-xl font-bold text-green-600">{{ $stats['sudah_bayar'] }}</p>
                            </div>
                            <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-600 mb-1">Belum Bayar</p>
                                <p class="text-xl font-bold text-red-600">{{ $stats['belum_bayar'] }}</p>
                            </div>
                            <span class="material-icons text-red-600" style="font-size: 32px !important;">pending</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-600 mb-1">Jumlah Terkumpul</p>
                                <p class="text-sm font-bold text-blue-600">RM {{ number_format($stats['jumlah_terkumpul'], 2) }}</p>
                            </div>
                            <span class="material-icons text-blue-600" style="font-size: 32px !important;">payments</span>
                        </div>
                    </div>
                </div>

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('pembayaran-sewa.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no. pembayaran, nama penyewa..."
                        />

                        <div class="flex gap-2">
                            <select name="fasiliti" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Fasiliti</option>
                                @foreach($fasilitiList as $fasiliti)
                                    <option value="{{ $fasiliti->id }}" {{ request('fasiliti') == $fasiliti->id ? 'selected' : '' }}>
                                        {{ $fasiliti->nama_fasiliti }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="kaedah" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Kaedah</option>
                                <option value="Tunai" {{ request('kaedah') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Cek" {{ request('kaedah') == 'Cek' ? 'selected' : '' }}>Cek</option>
                                <option value="Bank Transfer" {{ request('kaedah') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Online Banking" {{ request('kaedah') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                <option value="E-Wallet" {{ request('kaedah') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>

                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Belum Bayar' => 'Belum Bayar',
                                    'Sudah Bayar' => 'Sudah Bayar',
                                    'Deposit Dikembalikan' => 'Deposit Dikembalikan',
                                    'Dibatalkan' => 'Dibatalkan'
                                ]"
                                :selected="request('status')"
                                placeholder="Semua Status"
                            />

                            <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" placeholder="Tarikh Dari" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            
                            <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" placeholder="Tarikh Hingga" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">
                                Cari
                            </x-action-button>
                            <x-action-button
                                type="button"
                                icon="refresh"
                                color="red"
                                onclick="window.location.href='{{ route('pembayaran-sewa.index') }}'"
                            >
                                Reset
                            </x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Pembayaran</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">No. Tempahan</th>
                                <th class="px-4 py-2 table-header">Nama Penyewa</th>
                                <th class="px-4 py-2 table-header">Fasiliti</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pembayaranList as $pembayaran)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('pembayaran-sewa.show', $pembayaran->id) }}" class="text-blue-600 hover:underline font-semibold">
                                        {{ $pembayaran->no_pembayaran }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $pembayaran->tarikh_pembayaran ? \Carbon\Carbon::parse($pembayaran->tarikh_pembayaran)->format('d/m/Y') : '-' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('tempahan-fasiliti.show', $pembayaran->tempahanFasiliti->id) }}" class="text-blue-600 hover:underline">
                                        {{ $pembayaran->tempahanFasiliti->no_tempahan ?? '-' }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $pembayaran->tempahanFasiliti->nama_penyewa ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $pembayaran->senariFasiliti->nama_fasiliti ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-semibold">RM {{ number_format($pembayaran->jumlah_bayaran, 2) }}</td>
                                <td class="px-4 py-3 text-center">{{ $pembayaran->kaedah_bayaran }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($pembayaran->status_pembayaran === 'Sudah Bayar')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Bayar</span>
                                    @elseif($pembayaran->status_pembayaran === 'Belum Bayar')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Belum Bayar</span>
                                    @elseif($pembayaran->status_pembayaran === 'Deposit Dikembalikan')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Deposit Dikembalikan</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">{{ $pembayaran->status_pembayaran }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <x-action-icons 
                                        :showUrl="route('pembayaran-sewa.show', $pembayaran->id)"
                                        :editUrl="auth()->user()->hasPermission('pembayaran_sewa', 'update') ? route('pembayaran-sewa.edit', $pembayaran->id) : null"
                                        :deleteUrl="auth()->user()->hasPermission('pembayaran_sewa', 'delete') ? route('pembayaran-sewa.destroy', $pembayaran->id) : null"
                                        :itemName="$pembayaran->no_pembayaran"
                                    />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    Tiada pembayaran dijumpai
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @forelse($pembayaranList as $pembayaran)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <a href="{{ route('pembayaran-sewa.show', $pembayaran->id) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                    {{ $pembayaran->no_pembayaran }}
                                </a>
                                <p class="text-[10px] text-gray-500">{{ $pembayaran->tarikh_pembayaran ? \Carbon\Carbon::parse($pembayaran->tarikh_pembayaran)->format('d/m/Y') : '-' }} | {{ $pembayaran->kaedah_bayaran }}</p>
                            </div>
                            @if($pembayaran->status_pembayaran === 'Sudah Bayar')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Bayar</span>
                            @elseif($pembayaran->status_pembayaran === 'Belum Bayar')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Belum Bayar</span>
                            @elseif($pembayaran->status_pembayaran === 'Deposit Dikembalikan')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Deposit Dikembalikan</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">{{ $pembayaran->status_pembayaran }}</span>
                            @endif
                        </div>

                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">No. Tempahan:</span>
                                <span class="font-semibold text-gray-900">{{ $pembayaran->tempahanFasiliti->no_tempahan ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Penyewa:</span>
                                <span class="font-semibold text-gray-900">{{ $pembayaran->tempahanFasiliti->nama_penyewa ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Fasiliti:</span>
                                <span class="font-semibold text-gray-900">{{ $pembayaran->senariFasiliti->nama_fasiliti ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Jumlah:</span>
                                <span class="font-bold text-gray-900">RM {{ number_format($pembayaran->jumlah_bayaran, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <x-action-icons 
                                :showUrl="route('pembayaran-sewa.show', $pembayaran->id)"
                                :editUrl="auth()->user()->hasPermission('pembayaran_sewa', 'update') ? route('pembayaran-sewa.edit', $pembayaran->id) : null"
                                :deleteUrl="auth()->user()->hasPermission('pembayaran_sewa', 'delete') ? route('pembayaran-sewa.destroy', $pembayaran->id) : null"
                                :itemName="$pembayaran->no_pembayaran"
                            />
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 text-xs">
                        Tiada pembayaran dijumpai
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($pembayaranList->hasPages())
                <div class="mt-6">
                    {{ $pembayaranList->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
