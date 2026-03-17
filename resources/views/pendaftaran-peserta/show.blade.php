<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Peserta - E-Masjid</title>
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
                        <a href="{{ route('pendaftaran-peserta.index') }}" class="mr-3 text-gray-500 hover:text-gray-700"><span class="material-icons">arrow_back</span></a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $pendaftaranPeserta->nama_peserta }}</h1>
                            <p class="text-xs text-gray-600">{{ $pendaftaranPeserta->program->nama_program ?? '-' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('pendaftaran-peserta.edit', $pendaftaranPeserta) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">
                        <span class="material-icons text-sm mr-1">edit</span>Edit
                    </a>
                </div>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-w-2xl">
                    <dl class="space-y-3">
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Nama Peserta</dt><dd class="text-xs font-medium text-gray-900">{{ $pendaftaranPeserta->nama_peserta }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Program</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->program->nama_program ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">No. IC</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->no_ic ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">No. Telefon</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->no_telefon ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Email</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->email ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Alamat</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->alamat ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Tarikh Daftar</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->tarikh_daftar->format('d/m/Y') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Jumlah Bayaran</dt><dd class="text-xs font-medium text-gray-900">RM {{ number_format($pendaftaranPeserta->jumlah_bayaran, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Status Bayaran</dt><dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $pendaftaranPeserta->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">{{ $pendaftaranPeserta->status_bayaran }}</span></dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Status Kehadiran</dt><dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $pendaftaranPeserta->status_kehadiran === 'Hadir' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $pendaftaranPeserta->status_kehadiran }}</span></dd></div>
                        @if($pendaftaranPeserta->catatan)<div class="flex justify-between"><dt class="text-xs text-gray-500">Catatan</dt><dd class="text-xs text-gray-900">{{ $pendaftaranPeserta->catatan }}</dd></div>@endif
                    </dl>
                </div>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
