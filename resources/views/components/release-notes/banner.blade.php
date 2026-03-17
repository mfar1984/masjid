@props([
    'version' => 'v3.0',
    'title' => 'Versi Semasa',
    'description' => 'Kemaskini Major - Complete Kewangan, Asnaf & Kebajikan Modules',
    'date' => null,
    'gradient' => 'from-blue-500 to-purple-600'
])

<div {{ $attributes->merge(['class' => "mb-8 bg-gradient-to-r {$gradient} rounded-sm p-6 text-white"]) }}>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="text-center sm:text-left mb-4 sm:mb-0">
            <div class="flex items-center justify-center sm:justify-start mb-2">
                <span class="material-icons text-lg mr-2">new_releases</span>
                <h2 class="text-lg font-bold">{{ $title }}</h2>
            </div>
            <div class="text-2xl font-bold mb-1">{{ $version }}</div>
            <p class="text-sm opacity-90">{{ $description }}</p>
        </div>
        <div class="text-center sm:text-right">
            <div class="text-sm opacity-90 mb-1">Dikeluarkan pada</div>
            <div class="text-lg font-semibold">{{ $date ?? date('d/m/Y') }}</div>
        </div>
    </div>
</div>
