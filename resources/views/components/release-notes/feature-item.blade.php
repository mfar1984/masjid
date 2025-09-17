@props([
    'title' => '',
    'description' => '',
    'icon' => 'check_circle',
    'iconColor' => 'text-green-500'
])

<li {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <span class="material-icons {{ $iconColor }} text-xs mr-2">{{ $icon }}</span>
    <span>
        @if($title)
            <strong>{{ $title }}:</strong> {{ $description }}
        @else
            {{ $slot }}
        @endif
    </span>
</li>
