<button {{ $attributes->merge(['type' => 'button', 'class' => 'text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button> 