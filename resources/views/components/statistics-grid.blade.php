@props([
    'stats' => [],
    'fixed-cols' => null
])

@php
    // Determine grid columns
    $gridCols = $fixedCols ?? count($stats);

    // For senarai-masjid, always use 6 columns for proper alignment
    if ($gridCols == 6) {
        $gridClass = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6';
    } elseif ($gridCols == 5) {
        $gridClass = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5';
    } else {
        $gridClass = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-' . $gridCols;
    }
@endphp

<!-- Dynamic Statistics Cards - Full Width -->
<div class="grid {{ $gridClass }} gap-4 mb-6">
    @foreach($stats as $stat)
        <x-statistics-card
            :title="$stat['title']"
            :value="$stat['value']"
            :icon="$stat['icon']"
            :bg-color="'bg-' . $stat['color'] . '-100'"
            :text-color="'text-' . $stat['color'] . '-600'"
        />
    @endforeach
</div>
