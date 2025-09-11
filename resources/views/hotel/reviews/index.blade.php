@extends('hotel.layouts.app')

@section('title', 'Customer Reviews')

@section('content')
    <!-- Compact Header like Room Management -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Customer Reviews</h1>
            <p class="text-gray-600">View and manage customer feedback and ratings</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Period Filter -->
            <div class="relative">
                <select id="periodFilter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <option value="all" {{ $periodFilter == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $periodFilter == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ $periodFilter == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ $periodFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
            </div>
        </div>
    </div>

    <div class="space-y-6">
    <!-- Review Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Reviews -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Reviews</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Rating</p>
                    <div class="flex items-center gap-2">
                        <p class="text-2xl font-bold text-amber-600">{{ $stats['average_rating'] }}</p>
                        <div class="text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($stats['average_rating']))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $stats['average_rating'])
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="bg-amber-100 p-3 rounded-full">
                    <i class="fas fa-star-half-alt text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recent (30 days)</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['recent_reviews']) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-calendar text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Highest Rating -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Highest Rating</p>
                    <div class="flex items-center gap-1">
                        <p class="text-2xl font-bold text-green-600">5</p>
                        <div class="text-green-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-trophy text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating Distribution</h3>
        <div class="space-y-3">
            @for($rating = 5; $rating >= 1; $rating--)
                @php
                    $count = $stats['rating_distribution'][$rating];
                    $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">{{ $rating }}</span>
                        <i class="fas fa-star text-amber-400 text-xs"></i>
                    </div>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">{{ $count }}</span>
                    <span class="text-sm text-gray-500 w-12">{{ number_format($percentage, 1) }}%</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <!-- Rating Filter -->
                <div class="relative">
                    <select id="ratingFilter" class="appearance-none bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm">
                        <option value="all" {{ $ratingFilter == 'all' ? 'selected' : '' }}>All Ratings</option>
                        <option value="5" {{ $ratingFilter == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ $ratingFilter == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ $ratingFilter == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ $ratingFilter == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ $ratingFilter == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-2 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
            
            <!-- Search -->
            <div class="relative">
                <form method="GET" class="flex">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search reviews..."
                           class="pl-9 pr-3 py-2 w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                    />
                    <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <!-- Hidden inputs to preserve other filters -->
                    <input type="hidden" name="rating" value="{{ $ratingFilter }}">
                    <input type="hidden" name="period" value="{{ $periodFilter }}">
                </form>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reviews ({{ $reviews->total() }})</h3>
            
            @forelse($reviews as $review)
                <div class="border-b border-gray-200 last:border-b-0 py-6 first:pt-0 last:pb-0">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-3 md:w-48 flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $review->tourist->name ?? 'Anonymous' }}</p>
                                <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <div class="flex-1">
                            <!-- Rating and Title -->
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <h4 class="font-semibold text-gray-900">{{ $review->title }}</h4>
                            </div>
                            
                            <!-- Review Description -->
                            <p class="text-gray-700 mb-3">{{ $review->description }}</p>
                            
                            <!-- Booking Reference -->
                            @if($review->booking)
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-bookmark mr-1"></i>
                                    Booking Reference: {{ $review->booking->booking_reference ?? 'B' . str_pad($review->booking->id, 3, '0', STR_PAD_LEFT) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <i class="fas fa-star text-4xl mb-4 opacity-50"></i>
                        <p class="text-lg font-medium">No reviews found</p>
                        <p class="text-sm">{{ request('q') ? 'Try adjusting your search terms.' : 'Reviews from customers will appear here.' }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Period filter change
    document.getElementById('periodFilter').addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('period', this.value);
        window.location.href = url.toString();
    });

    // Rating filter change
    document.getElementById('ratingFilter').addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('rating', this.value);
        window.location.href = url.toString();
    });
});
</script>
@endpush
@endsection
