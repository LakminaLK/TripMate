@props(['title', 'searchPlaceholder' => null, 'searchName' => 'q', 'searchValue' => null])

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
    </div>
    
    <div class="flex flex-wrap items-center gap-2 md:gap-3">
        @if($searchPlaceholder)
            <!-- Search -->
            <div class="flex items-center gap-3">
                <form method="GET" class="relative">
                    <input type="text" 
                           name="{{ $searchName }}" 
                           value="{{ $searchValue ?? request($searchName) }}" 
                           placeholder="{{ $searchPlaceholder }}"
                           class="pl-10 pr-4 py-2.5 w-full sm:w-64 rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 transition-colors duration-200 bg-white text-gray-900 placeholder-gray-500"
                    />
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </form>
            </div>
        @endif
    </div>
</div>
