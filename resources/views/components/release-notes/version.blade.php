@props([
    'version' => 'v1.0',
    'title' => 'Release Title',
    'description' => 'Release description',
    'date' => null,
    'type' => 'minor',
    'bgColor' => 'bg-green-50',
    'badgeColor' => 'bg-green-100 text-green-800',
    'isLatest' => false
])

@php
    // Auto-determine colors based on isLatest flag
    if ($isLatest) {
        // Latest version gets blue color (special)
        $finalBgColor = 'bg-blue-50';
        $finalBadgeColor = 'bg-blue-100 text-blue-800';
    } else {
        // All older versions get green color
        $finalBgColor = 'bg-green-50';
        $finalBadgeColor = 'bg-green-100 text-green-800';
    }
@endphp

<div x-show="shouldShowRelease('{{ $type }}')"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform -translate-y-4"
     {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-sm overflow-hidden']) }}>

    <!-- Version Header -->
    <div class="{{ $finalBgColor }} px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center mb-2 sm:mb-0">
                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $finalBadgeColor }} mr-3">
                    {{ $version }}
                </span>
                <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            </div>
            <div class="text-xs text-gray-600">
                {{ $date ?? date('d/m/Y') }}
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">{{ $description }}</p>
    </div>

    <!-- Version Content -->
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
