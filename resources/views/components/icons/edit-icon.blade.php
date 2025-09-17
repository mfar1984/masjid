@props([
    'route' => '',
    'title' => 'Edit',
    'size' => 'desktop', // desktop or mobile
    'id' => null,
    'nama' => ''
])

@if($size === 'desktop')
    <a href="{{ $route }}" 
       class="text-blue-600 hover:text-blue-800 action-icon" 
       title="{{ $title }}" 
       aria-label="{{ $title }}">
        <span class="material-icons text-[8px]">edit</span>
    </a>
@else
    <a href="{{ $route }}" 
       class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" 
       title="{{ $title }}">
        <span class="material-icons text-sm">edit</span>
    </a>
@endif
