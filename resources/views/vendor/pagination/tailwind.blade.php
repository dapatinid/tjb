@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" 
         class="flex flex-col sm:flex-row items-center sm:justify-between gap-2 w-full">
        
        {{-- Info jumlah data --}}
        <div class="text-sm text-gray-600">
            {!! __('Menampilkan') !!}
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            {!! __('hingga') !!}
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            {!! __('dari total') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
            {!! __('data') !!}
        </div>
        
        {{-- Pagination buttons (scrollable di mobile) --}}
        <div class="w-full sm:w-auto overflow-x-auto scrollbar-hide flex justify-center">
            <div class="flex flex-wrap gap-1 items-center py-1 pe-1 justify-center">
                {{-- Tombol Prev --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 border border-gray-300 bg-gray-100 rounded-md cursor-default">
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.707 4.293a1 1 0 010 1.414L4.414 9H16a1 1 0 110 2H4.414l3.293 3.293a1 1 0 11-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>                                                
                        {{-- Prev --}}
                    </span>
                @else
                    <button wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 active:bg-gray-100 transition">
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.707 4.293a1 1 0 010 1.414L4.414 9H16a1 1 0 110 2H4.414l3.293 3.293a1 1 0 11-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>  
                        {{-- Prev --}}
                    </button>
                @endif

                {{-- Angka halaman (scrollable) --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-gray-50 border border-gray-300 rounded-md select-none">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-400 border border-yellow-400 rounded-md cursor-default">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 active:bg-gray-100 transition">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 active:bg-gray-100 transition">
                        {{-- Next --}}
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.293 15.707a1 1 0 010-1.414L15.586 11H4a1 1 0 110-2h11.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <span
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-default">
                        {{-- Next --}}
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.293 15.707a1 1 0 010-1.414L15.586 11H4a1 1 0 110-2h11.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
