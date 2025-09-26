@if ($paginator->hasPages())
    <nav class="flex flex-col bg-white rounded-xl shadow-md border border-emerald-100 p-3 sm:p-4" aria-label="Pagination Navigation">


        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            {{-- Results Info --}}
            <div class="flex items-center text-xs text-slate-600">
                <div class="w-6 h-6 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-2 shadow-sm">
                    <span class="text-white font-bold text-sm">📊</span>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 text-xs">
                        Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }}
                    </p>
                    <p class="text-slate-600 text-xs">
                        dari {{ number_format($paginator->total()) }} produk AgriConnect
                    </p>
                </div>
            </div>

            {{-- Pagination Controls --}}
            <div class="flex items-center space-x-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center justify-center w-8 h-8 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="group flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg class="w-3 h-3 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="flex items-center justify-center w-8 h-8 text-slate-400">
                            <span class="text-sm font-bold">...</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Current Page --}}
                                <span class="flex items-center justify-center w-8 h-8 text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg font-bold shadow-md transform scale-105 text-sm">
                                    {{ $page }}
                                </span>
                            @else
                                {{-- Other Pages --}}
                                <a href="{{ $url }}"
                                   class="flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 font-semibold text-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="group flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <span class="flex items-center justify-center w-8 h-8 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Compact Quick Jump --}}
            <div class="flex items-center text-xs text-slate-600">
                <div class="text-right">
                    <p class="font-semibold text-slate-800 text-xs">Loncat ke halaman</p>
                    <div class="flex items-center mt-0.5">
                        <input type="number"
                               min="1"
                               max="{{ $paginator->lastPage() }}"
                               value="{{ $paginator->currentPage() }}"
                               class="w-10 px-1 py-0.5 text-center text-xs border border-slate-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                               onchange="jumpToPage(this.value, {{ $paginator->lastPage() }})"
                               onkeypress="if(event.key === 'Enter') { jumpToPage(this.value, {{ $paginator->lastPage() }}); this.blur(); }">
                        <span class="ml-1 text-slate-500 text-xs">/ {{ $paginator->lastPage() }}</span>
                    </div>
                </div>
                <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center ml-2 shadow-sm">
                    <span class="text-white font-bold text-sm">⚡</span>
                </div>
            </div>
        </div>

        <script>
        function jumpToPage(page, maxPage) {
            const pageNum = parseInt(page);
            if (pageNum >= 1 && pageNum <= maxPage) {
                const url = new URL(window.location);
                url.searchParams.set('page', pageNum);
                window.location.href = url.toString();
            }
        }
        </script>

        {{-- Mobile Pagination Controls --}}
        <div class="flex sm:hidden justify-center gap-2 w-full">
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed text-sm min-w-0 flex-1 max-w-[100px]">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="truncate">Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-sm text-sm min-w-0 flex-1 max-w-[100px]">
                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="truncate">Sebelumnya</span>
                </a>
            @endif

            {{-- Mobile Page Jump --}}
            <div class="flex items-center justify-center bg-slate-50 rounded-lg px-2 py-2 border min-w-[60px]">
                <input type="number"
                       min="1"
                       max="{{ $paginator->lastPage() }}"
                       value="{{ $paginator->currentPage() }}"
                       class="w-6 text-center text-sm border-0 bg-transparent focus:ring-0 p-0 font-medium"
                       onchange="jumpToPage(this.value, {{ $paginator->lastPage() }})"
                       onkeypress="if(event.key === 'Enter') { jumpToPage(this.value, {{ $paginator->lastPage() }}); this.blur(); }">
                <span class="text-slate-500 text-sm font-medium">/{{ $paginator->lastPage() }}</span>
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-sm text-sm min-w-0 flex-1 max-w-[100px]">
                    <span class="truncate">Selanjutnya</span>
                    <svg class="w-3 h-3 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="flex items-center justify-center px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed text-sm min-w-0 flex-1 max-w-[100px]">
                    <span class="truncate">Selanjutnya</span>
                    <svg class="w-3 h-3 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
