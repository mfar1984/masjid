<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Penceramah - E-Masjid</title>
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
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <a href="{{ route('senarai-penceramah.index') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                            <span class="material-icons">arrow_back</span>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $senaraiPenceramah->nama }}</h1>
                            <p class="text-xs text-gray-600">Maklumat penceramah</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('senarai-penceramah.edit', $senaraiPenceramah) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">
                            <span class="material-icons text-sm mr-1">edit</span>Edit
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Peribadi</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Nama</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $senaraiPenceramah->nama }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">No. IC</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->no_ic ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">No. Telefon</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->no_telefon ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Email</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->email ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Alamat</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->alamat ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tauliah</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Negara</dt>
                                <dd class="text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $senaraiPenceramah->negara === 'Malaysia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $senaraiPenceramah->negara }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Negeri</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->negeri ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">No. Sijil Tauliah</dt>
                                <dd class="text-xs font-medium text-gray-900">{{ $senaraiPenceramah->no_sijil_tauliah ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Tarikh Tamat</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->tarikh_tamat_tauliah ? $senaraiPenceramah->tarikh_tamat_tauliah->format('d/m/Y') : '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Pihak Pengeluar</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->pihak_pengeluar ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Bidang Kepakaran</dt>
                                <dd class="text-xs text-gray-900">{{ $senaraiPenceramah->bidang_kepakaran ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-xs text-gray-500">Status</dt>
                                <dd class="text-xs">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $senaraiPenceramah->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $senaraiPenceramah->status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($senaraiPenceramah->catatan)
                <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Catatan</h3>
                    <p class="text-xs text-gray-600">{{ $senaraiPenceramah->catatan }}</p>
                </div>
                @endif

                @if($senaraiPenceramah->jadualCeramah && $senaraiPenceramah->jadualCeramah->count() > 0)
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Sejarah Ceramah Terkini</h3>
                    <div class="overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-blue-100 text-gray-600">
                                <tr>
                                    <th class="px-4 py-2 table-header">Tarikh</th>
                                    <th class="px-4 py-2 table-header">Tajuk</th>
                                    <th class="px-4 py-2 table-header">Jenis</th>
                                    <th class="px-4 py-2 table-header text-right">Bayaran</th>
                                    <th class="px-4 py-2 table-header text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($senaraiPenceramah->jadualCeramah as $jadual)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">{{ $jadual->tarikh->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">{{ $jadual->tajuk_ceramah }}</td>
                                    <td class="px-4 py-2 table-data">{{ $jadual->jenis_ceramah }}</td>
                                    <td class="px-4 py-2 table-data text-right">RM {{ number_format($jadual->kadar_bayaran, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $jadual->status_bayaran }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
