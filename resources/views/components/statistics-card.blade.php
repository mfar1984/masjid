@props([
    'title' => '',
    'value' => 0,
    'icon' => 'info',
    'color' => 'blue',
    'bgColor' => 'bg-blue-100',
    'textColor' => 'text-blue-600'
])

<div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
    <div class="flex items-center">
        <div class="w-12 h-12 {{ $bgColor }} rounded-lg flex items-center justify-center">
            <span class="material-icons {{ $textColor }} text-xl">{{ $icon }}</span>
        </div>
        <div class="ml-3">
            <p class="text-xs font-medium text-gray-600">{{ $title }}</p>
            <p class="text-lg font-semibold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>
