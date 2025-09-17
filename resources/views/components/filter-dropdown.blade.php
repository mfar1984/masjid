@props([
    'name' => '',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Pilih...',
    'minWidth' => 'min-w-[140px]'
])

<select name="{{ $name }}"
        class="h-[32px] px-3 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white {{ $minWidth }}">
    <option value="">{{ $placeholder }}</option>
    @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
