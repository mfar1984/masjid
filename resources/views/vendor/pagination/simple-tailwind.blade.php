@if ($paginator->hasPages())
    <nav class="flex items-center justify-center space-x-2">
        {{-- First Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1 text-[11px] text-gray-400 cursor-not-allowed">
                &lt;&lt;
            </span>
        @else
            <a href="{{ $paginator->url(1) }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors" title="Halaman Pertama">
                &lt;&lt;
            </a>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1 text-[11px] text-gray-400 cursor-not-allowed">
                &lt;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors" title="Halaman Sebelum">
                &lt;
            </a>
        @endif

        {{-- Smart Pagination Elements --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $onEachSide = 1; // Number of pages to show on each side of current page
        @endphp

        {{-- Always show first page --}}
        @if ($currentPage > 1 + $onEachSide)
            <a href="{{ $paginator->url(1) }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors">1</a>
            
            {{-- Show ellipsis if there's a gap --}}
            @if ($currentPage > 2 + $onEachSide)
                <span class="px-2 py-1 text-[11px] text-gray-500">...</span>
            @endif
        @endif

        {{-- Show pages around current page --}}
        @for ($page = max(1, $currentPage - $onEachSide); $page <= min($lastPage, $currentPage + $onEachSide); $page++)
            @if ($page == $currentPage)
                <span class="w-7 h-7 flex items-center justify-center text-[11px] text-white bg-blue-600 rounded-full">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors">{{ $page }}</a>
            @endif
        @endfor

        {{-- Always show last page --}}
        @if ($currentPage < $lastPage - $onEachSide)
            {{-- Show ellipsis if there's a gap --}}
            @if ($currentPage < $lastPage - 1 - $onEachSide)
                <span class="px-2 py-1 text-[11px] text-gray-500">...</span>
            @endif
            
            <a href="{{ $paginator->url($lastPage) }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors">{{ $lastPage }}</a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors" title="Halaman Seterus">
                &gt;
            </a>
        @else
            <span class="px-2 py-1 text-[11px] text-gray-400 cursor-not-allowed">
                &gt;
            </span>
        @endif

        {{-- Last Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="px-2 py-1 text-[11px] text-gray-600 hover:text-gray-700 transition-colors" title="Halaman Terakhir">
                &gt;&gt;
            </a>
        @else
            <span class="px-2 py-1 text-[11px] text-gray-400 cursor-not-allowed">
                &gt;&gt;
            </span>
        @endif
    </nav>
@endif
