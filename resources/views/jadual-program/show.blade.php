<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Jadual Program - E-Masjid</title>
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
                        <a href="{{ route('jadual-program.index') }}" class="mr-3 text-gray-500 hover:text-gray-700"><span class="material-icons">arrow_back</span></a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $jadualProgram->program->nama_program ?? '-' }}</h1>
                            <p class="text-xs text-gray-600">{{ $jadualProgram->tarikh->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('jadual-program.edit', $jadualProgram) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-md hover:bg-yellow-600">
                        <span class="material-icons text-sm mr-1">edit</span>Edit
                    </a>
                </div>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 max-w-2xl">
                    <dl class="space-y-3">
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Program</dt><dd class="text-xs font-medium text-gray-900">{{ $jadualProgram->program->nama_program ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Tarikh</dt><dd class="text-xs text-gray-900">{{ $jadualProgram->tarikh->format('d/m/Y') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Masa</dt><dd class="text-xs text-gray-900">{{ $jadualProgram->masa_mula }} - {{ $jadualProgram->masa_tamat }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Lokasi</dt><dd class="text-xs text-gray-900">{{ $jadualProgram->lokasi ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Penceramah</dt><dd class="text-xs text-gray-900">{{ $jadualProgram->penceramah ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-xs text-gray-500">Status</dt><dd class="text-xs"><span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadualProgram->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ $jadualProgram->status }}</span></dd></div>
                        @if($jadualProgram->catatan)<div class="flex justify-between"><dt class="text-xs text-gray-500">Catatan</dt><dd class="text-xs text-gray-900">{{ $jadualProgram->catatan }}</dd></div>@endif
                    </dl>
                </div>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
