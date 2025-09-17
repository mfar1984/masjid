@props([
    'title' => 'Features',
    'icon' => 'add_circle',
    'iconColor' => 'text-green-600',
    'features' => []
])

<div {{ $attributes }}>
    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
        <span class="material-icons {{ $iconColor }} text-sm mr-2">{{ $icon }}</span>
        {{ $title }}
    </h4>
    
    @if(!empty($features))
        <ul class="space-y-2 text-xs text-gray-700">
            @foreach($features as $feature)
                <x-release-notes.feature-item 
                    :title="$feature['title'] ?? ''"
                    :description="$feature['description'] ?? ''"
                    :icon="$feature['icon'] ?? 'check_circle'"
                    :iconColor="$feature['iconColor'] ?? 'text-green-500'"
                />
            @endforeach
        </ul>
    @else
        <ul class="space-y-2 text-xs text-gray-700">
            {{ $slot }}
        </ul>
    @endif
</div>
