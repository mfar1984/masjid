@props([
    'route' => '',
    'title' => 'Lihat',
    'size' => 'desktop', // desktop or mobile
    'id' => null,
    'nama' => ''
])

@if($size === 'desktop')
    <a href="{{ $route }}" 
       class="text-gray-700 hover:text-gray-900 action-icon" 
       title="{{ $title }}" 
       aria-label="{{ $title }}">
        <span class="material-icons text-[8px]">visibility</span>
    </a>
@else
    <a href="{{ $route }}" 
       class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" 
       title="{{ $title }}">
        <span class="material-icons text-sm">visibility</span>
    </a>
@endif
