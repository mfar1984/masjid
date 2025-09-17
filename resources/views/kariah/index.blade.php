<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahli Kariah - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .action-icon {
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .action-icon .material-icons {
            font-size: 16px !important;
            line-height: 1 !important;
        }
        .material-icons.text-\[11px\] {
            font-size: 14px !important;
            line-height: 1 !important;
        }
        /* Override global Material Icons CSS */
        .material-icons.text-\[10px\] {
            font-size: 18px !important;
            line-height: 1 !important;
        }
        .material-icons.text-xs {
            font-size: 12px !important;
            line-height: 1 !important;
        }
        .material-icons.text-sm {
            font-size: 14px !important;
            line-height: 1 !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Ahli Kariah</h1>
                        <p class="text-xs text-gray-600">Senarai ahli kariah yang berdaftar</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('kariah.create') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700">
                            <span class="material-icons text-[10px] mr-2">person_add</span>
                            Tambah Ahli
                        </a>
                        <a href="{{ route('kariah.export') }}" class="inline-flex items-center px-3 py-2 bg-green-100 text-gray-700 text-xs rounded-xs hover:bg-green-200">
                            <span class="material-icons text-[10px] mr-2">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('kariah.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / nombor IC / telefon..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500" />
                        </div>
                    </div>
                    <div>
                        <select name="zon" class="w-full py-2 px-3 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                            <option value="">Semua Zon</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone }}" {{ request('zon') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <select name="status" class="flex-1 py-2 px-3 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-red-300 text-red-700 text-xs rounded-xs hover:bg-red-400">Reset</button>
                    </div>
                </form>

                <!-- Table Wrapper -->
                <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 font-medium text-xs">Nama</th>
                                <th class="px-4 py-2 font-medium text-xs">Umur</th>
                                <th class="px-4 py-2 font-medium text-xs">No. IC</th>
                                <th class="px-4 py-2 font-medium text-xs">Telefon</th>
                                <th class="px-4 py-2 font-medium text-xs">Tarikh Keahlian</th>
                                <th class="px-4 py-2 font-medium text-xs">Tarikh Kemaskini</th>
                                <th class="px-4 py-2 font-medium text-xs">Status</th>
                                <th class="px-4 py-2 font-medium text-xs text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($kariah as $member)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-1">
                                    <div class="font-medium text-gray-900 text-xs">{{ $member->nama }}</div>
                                </td>
                                <td class="px-4 py-2 text-gray-900 text-xs">{{ $member->umur }}</td>
                                <td class="px-4 py-2 text-gray-900 text-xs">{{ $member->no_ic }}</td>
                                <td class="px-4 py-2 text-gray-900 text-xs">{{ $member->telefon }}</td>
                                <td class="px-4 py-2 text-gray-900 text-xs">{{ $member->tarikh_keahlian_formatted }}</td>
                                <td class="px-4 py-2 text-gray-900 text-xs">{{ $member->tarikh_kemaskini_formatted }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2 py-0 text-xs rounded-lg {{ $member->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-1 text-center space-x-1">
                                    <a href="{{ route('kariah.show', $member) }}" class="text-gray-700 hover:text-gray-900 action-icon" title="Lihat" aria-label="Lihat">
                                        <span class="material-icons text-[8px]">visibility</span>
                                    </a>
                                    <a href="{{ route('kariah.edit', $member) }}" class="text-blue-600 hover:text-blue-800 action-icon" title="Edit" aria-label="Edit">
                                        <span class="material-icons text-[8px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('kariah.destroy', $member) }}" class="inline" onsubmit="return confirm('Adakah anda pasti mahu memadamkan ahli kariah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 action-icon" title="Padam" aria-label="Padam">
                                            <span class="material-icons text-[8px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    Tiada data ahli kariah dijumpai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($kariah->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $kariah->firstItem() }} hingga {{ $kariah->lastItem() }} daripada {{ $kariah->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $kariah->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
