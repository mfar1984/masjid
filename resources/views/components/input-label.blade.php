@props(['value'])
 
<label {{ $attributes->merge(['class' => 'block text-xs font-medium text-gray-700']) }}>
    {{ $value ?? $slot }}
</label> 