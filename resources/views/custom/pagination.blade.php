@if ($paginator->hasPages())
    <nav class="flex items-center justify-between bg-white rounded-xl shadow-md border border-emerald-100 p-4" aria-label="Pagination Navigation">
        {{-- Mobile Pagination Info --}}
        <div class="flex justify-between flex-1 sm:hidden">
            <div class="text-xs text-slate-600">
                Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            </div>
            <div class="text-xs text-slate-600">
                {{ number_format($paginator->total()) }} total produk
            </div>
        </div>

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
                    </span>
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
                               onchange="if(this.value >= 1 && this.value <= {{ $paginator->lastPage() }}) {
                                   const url = new URL(window.location);
                                   url.searchParams.set('page', this.value);
                                   window.location.href = url.toString();
                               }">
                        <span class="ml-1 text-slate-500 text-xs">/ {{ $paginator->lastPage() }}</span>
                    </div>
                </div>
                <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center ml-2 shadow-sm">
                    <span class="text-white font-bold text-sm">⚡</span>
                </div>
            </div>
        </div>

        {{-- Mobile Pagination Controls --}}
        <div class="flex sm:hidden justify-between w-full mt-3">
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed text-sm">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-sm text-sm">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-sm text-sm">
                    Selanjutnya
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="flex items-center justify-center px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed text-sm">
                    Selanjutnya
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
