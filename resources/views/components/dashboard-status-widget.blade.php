@props([
    'title' => 'Status Sistem',
    'showTitle' => true
])

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-sm overflow-hidden']) }}>
    @if($showTitle)
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                <span class="material-icons text-sm mr-2">monitor_heart</span>
                {{ $title }}
            </h3>
        </div>
    @endif
    
    <x-system-status compact="true" show-refresh="true" />
</div>
