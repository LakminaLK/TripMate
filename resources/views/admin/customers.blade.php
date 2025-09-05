@extends('admin.layouts.app')

@section('title', 'Customer List')

@push('flash-scripts')
<!-- Expose flash for toasts -->
<script>
  window.FLASH = {
    success: @json(session('success')),
    errors:  @json($errors->all()),
  };
</script>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Customer Management</h1>
        </div>
        
        <!-- Search + Add button -->
        <div class="flex items-center gap-3">
            <form method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customers..."
                       class="pl-9 pr-3 py-2 w-full sm:w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </form>
        </div>
    </div>

    
    @include('admin.components.success-message')

    <!-- Customer Table Container -->
    <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
        <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4 text-left">Customer ID</th>
                <th class="px-6 py-4 text-left">Name</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Mobile</th>
                <th class="px-6 py-4 text-left">Location</th>
                <th class="px-6 py-4 text-left">Bookings</th>
                <th class="px-6 py-4 text-left">Total Spent</th>
                <th class="px-6 py-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 text-sm">
                    @forelse($customers as $customer)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">{{ $customer->customer_id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $customer->name }}</td>
                            <td class="px-6 py-4">{{ $customer->email }}</td>
                            <td class="px-6 py-4">{{ $customer->mobile ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $customer->location ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $customer->bookings_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-green-600">{{ $customer->total_spent }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.customers.delete', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $customers->withQueryString()->links() }}
            </div>
@endsection