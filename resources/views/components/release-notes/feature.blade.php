@props([
    'title' => '',
    'description' => '',
    'icon' => 'check_circle',
    'iconColor' => 'text-green-600'
])

<div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
    <div class="flex-shrink-0 mt-0.5">
        <span class="material-icons text-lg {{ $iconColor }}">{{ $icon }}</span>
    </div>
    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $title }}</h4>
        <p class="text-xs text-gray-600 leading-relaxed">{{ $description }}</p>
    </div>
</div>
