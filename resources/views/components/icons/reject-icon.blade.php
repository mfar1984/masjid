@props([
    'title' => 'Tolak',
    'size' => 'desktop', // desktop or mobile
    'id' => null,
    'nama' => ''
])

@if($size === 'desktop')
    <button type="button" 
            onclick="showRejectModal('{{ $id }}', '{{ $nama }}')" 
            class="text-red-600 hover:text-red-800 action-icon" 
            title="{{ $title }}" 
            aria-label="{{ $title }}">
        <span class="material-icons text-[8px]">close</span>
    </button>
@else
    <button type="button" 
            onclick="showRejectModal('{{ $id }}', '{{ $nama }}')" 
            class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" 
            title="{{ $title }}">
        <span class="material-icons text-sm">close</span>
    </button>
@endif
