@props([
    'title' => 'Nota Keluaran',
    'description' => 'Sejarah kemaskini dan pembangunan sistem',
    'currentVersion' => 'v1.1',
    'currentTitle' => 'Kemaskini Minor',
    'currentDescription' => 'Status Sistem & Penambahbaikan UI'
])

<div x-data="{
    filterType: 'all',
    shouldShowRelease(type) {
        return this.filterType === 'all' || this.filterType === type;
    }
}" {{ $attributes->merge(['class' => 'bg-white shadow-lg border-x border-gray-200 p-6']) }}>

    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $title }}</h1>
        <p class="text-xs text-gray-600">{{ $description }}</p>
    </div>

    <!-- Current Version Banner -->
    <x-release-notes.banner 
        :version="$currentVersion"
        :title="'Versi Semasa'"
        :description="$currentTitle . ' - ' . $currentDescription"
    />

    <!-- Version Filter -->
    <x-release-notes.filter />

    <!-- Release Notes Content -->
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
