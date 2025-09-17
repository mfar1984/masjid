@props([
    'filters' => [
        'all' => ['label' => 'Semua Versi', 'color' => 'blue'],
        'major' => ['label' => 'Major', 'color' => 'red'],
        'minor' => ['label' => 'Minor', 'color' => 'yellow'],
        'initial' => ['label' => 'Initial', 'color' => 'green']
    ]
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
        @foreach($filters as $key => $filter)
            <button @click="filterType = '{{ $key }}'"
                    :class="filterType === '{{ $key }}' ? 'bg-{{ $filter['color'] }}-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-3 py-1 rounded-sm text-xs font-medium transition-colors">
                {{ $filter['label'] }}
            </button>
        @endforeach
    </div>
</div>
