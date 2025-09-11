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
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Customer Management</h1>
            <p class="text-gray-600">Manage and view all registered customers</p>
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
                <th class="px-6 py-4 text-center">Bookings</th>
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
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-blue-600">
                                    {{ $customer->bookings_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-green-600">{{ $customer->total_spent }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.customers.delete', $customer->id) }}" method="POST" class="inline js-delete-form" data-name="{{ $customer->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm js-delete-btn">
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

    <!-- Custom Confirm Modal (centered with slight dim) -->
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden">
      <div class="absolute inset-0 bg-black bg-opacity-20 z-10"></div>
      <div class="absolute inset-0 flex items-center justify-center z-20">
        <div class="bg-white rounded-xl shadow-2xl ring-1 ring-black/5 overflow-hidden w-[90%] max-w-md">
          <div class="px-5 py-4 flex items-start gap-3">
            <svg class="w-6 h-6 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none">
              <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" fill="currentColor" opacity=".12"/>
              <path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
              <h3 class="text-base font-semibold text-gray-900">Delete customer?</h3>
              <p class="text-sm text-gray-600 mt-1">
                Are you sure you want to delete <span id="confirm-name" class="font-medium text-gray-900">this customer</span>?
                This action cannot be undone.
              </p>
            </div>
          </div>
          <div class="px-5 pb-5 pt-2 flex items-center justify-end gap-2">
            <button id="confirm-cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
            <button id="confirm-yes" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Delete</button>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
    // ===== Custom Confirm (Delete) =====
    (() => {
      const modal = document.getElementById('confirm-modal');
      const overlay = modal?.querySelector('.absolute.inset-0.bg-black');
      const btnCancel = document.getElementById('confirm-cancel');
      const btnYes = document.getElementById('confirm-yes');
      const confirmName = document.getElementById('confirm-name');

      let pendingForm = null;

      function openConfirm(name) {
        if (confirmName) confirmName.textContent = name || 'this customer';
        modal?.classList.remove('hidden');
      }

      function closeConfirm() {
        modal?.classList.add('hidden');
        pendingForm = null;
      }

      // Close on background click
      overlay?.addEventListener('click', (e) => {
        if (e.target === overlay) closeConfirm();
      });

      // bind delete buttons
      document.querySelectorAll('.js-delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          pendingForm = btn.closest('form');
          const name = pendingForm?.dataset?.name || '';
          openConfirm(name);
        });
      });

      btnCancel?.addEventListener('click', closeConfirm);
      btnYes?.addEventListener('click', () => {
        if (pendingForm) pendingForm.submit();
      });
    })();
</script>
@endpush