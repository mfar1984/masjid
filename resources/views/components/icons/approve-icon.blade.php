@props([
    'title' => 'Terima',
    'size' => 'desktop', // desktop or mobile
    'id' => null,
    'nama' => ''
])

@if($size === 'desktop')
    <button type="button" 
            onclick="showApproveModal('{{ $id }}', '{{ $nama }}')" 
            class="text-green-600 hover:text-green-800 action-icon" 
            title="{{ $title }}" 
            aria-label="{{ $title }}">
        <span class="material-icons text-[8px]">check</span>
    </button>
@else
    <button type="button" 
            onclick="showApproveModal('{{ $id }}', '{{ $nama }}')" 
            class="p-2 text-green-600 hover:text-green-800 rounded-full hover:bg-green-50" 
            title="{{ $title }}">
        <span class="material-icons text-sm">check</span>
    </button>
@endif
