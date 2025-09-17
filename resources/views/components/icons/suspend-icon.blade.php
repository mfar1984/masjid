@props([
    'title' => 'Gantung',
    'size' => 'desktop', // desktop or mobile
    'id' => null,
    'nama' => ''
])

@if($size === 'desktop')
    <button type="button" 
            onclick="showSuspendModal('{{ $id }}', '{{ $nama }}')" 
            class="text-orange-600 hover:text-orange-800 action-icon" 
            title="{{ $title }}" 
            aria-label="{{ $title }}">
        <span class="material-icons text-[8px]">pause_circle</span>
    </button>
@else
    <button type="button" 
            onclick="showSuspendModal('{{ $id }}', '{{ $nama }}')" 
            class="p-2 text-orange-600 hover:text-orange-800 rounded-full hover:bg-orange-50" 
            title="{{ $title }}">
        <span class="material-icons text-sm">pause_circle</span>
    </button>
@endif
