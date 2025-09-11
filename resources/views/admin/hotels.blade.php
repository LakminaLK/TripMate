@extends('admin.layouts.app')

@section('title', 'Hotels')

@push('flash-scripts')
{{-- Expose flash for toasts --}}
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
            <h1 class="text-3xl font-bold text-gray-800">Hotels Management</h1>
            <p class="text-gray-600">Manage hotel registrations, status and revenue tracking</p>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <!-- Filter pills -->
            <div class="flex items-center gap-2">
                @php $status = strtolower(request('status','all')); @endphp
                <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'all'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='all'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
                <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'active'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='active'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Active</a>
                <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'inactive'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='inactive'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Inactive</a>
            </div>
        </div>
        
        <!-- Search + Add button -->
        <div class="flex items-center gap-3">
            <form method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search hotels..."
                       class="pl-9 pr-3 py-2 w-full sm:w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </form>
            
            <button id="add-hotel-btn" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Hotel
        </div>
    </div>

    <!-- Table -->
    <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
        <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4 text-left">Hotel ID</th>
                <th class="px-6 py-4 text-left">Hotel Name</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Location</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-center">Bookings</th>
                <th class="px-6 py-4 text-left">Total Revenue</th>
                <th class="px-6 py-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 text-sm">
          @forelse($hotels as $h)
            <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
              <td class="px-6 py-4 font-medium">{{ 'H' . str_pad($h->id, 3, '0', STR_PAD_LEFT) }}</td>

              <td class="px-6 py-4 font-medium">{{ $h->name ?? $h->username }}</td>
              <td class="px-6 py-4">{{ $h->email ?? '—' }}</td>
              <td class="px-6 py-4">{{ $h->location?->name ?? '—' }}</td>

              <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                  {{ strtolower($h->status)==='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                  {{ $h->status }}
                </span>
              </td>

              <td class="px-6 py-4 text-center">
                <span class="font-medium text-blue-600">
                  {{ $h->bookings_count ?? 0 }}
                </span>
              </td>
              <td class="px-6 py-4 font-medium text-green-600">{{ $h->total_revenue ?? '$ 0.00' }}</td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button
                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm js-edit"
                    data-id="{{ $h->id }}"
                    data-name="{{ $h->name }}"
                    data-email="{{ $h->email }}"
                    data-status="{{ $h->status }}"
                    data-location="{{ $h->location_id }}"
                  >Edit</button>

                  <form action="{{ route('admin.hotels.destroy', $h->id) }}" method="POST"
                        class="inline js-delete-form" data-name="{{ $h->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm js-delete-btn">
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No hotels found.</td></tr>
          @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $hotels->links() }}</div>

  <!-- Add/Edit Modal -->
  <div id="hotel-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
    <div class="w-full h-full overflow-y-auto">
      <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/2 lg:w-1/3 mx-auto my-10"
           style="max-height:85vh; overflow-y:auto;">
        <h3 class="text-xl font-bold mb-4" id="modal-title">Add New Hotel</h3>

        <form id="hotel-form" action="{{ route('admin.hotels.store') }}" method="POST">
          @csrf
          <div id="method-field" class="hidden">@method('PUT')</div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Hotel Name</label>
            <input type="text" id="name" name="name"
                   class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
          </div>

          <!-- Email (editable on create, read-only on edit) -->
          <div class="mb-1">
            <label class="block text-sm font-medium text-gray-700">Hotel Email</label>
            <input type="email" id="email" name="email"
                   class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                   required>
            <p id="email-note" class="text-xs text-gray-500 mt-1 hidden">
              Email cannot be changed after creation.
            </p>
          </div>

          <div class="mb-4 mt-3">
            <label class="block text-sm font-medium text-gray-700">Location</label>
            <select id="location_id" name="location_id"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
              <option value="" hidden>Select a location</option>
              @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
              <option value="Active" selected>Active</option>
              <option value="Inactive">Inactive</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">
              On create, credentials will be emailed automatically.
            </p>
          </div>

          <button type="submit" id="submit-button"
                  class="mt-6 bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-black">
            Create Hotel
          </button>
        </form>

        <button id="close-modal-btn" class="text-gray-500 hover:underline mt-4">Cancel</button>
      </div>
    </div>
  </div>

  {{-- Custom Confirm Modal --}}
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
            <h3 class="text-base font-semibold text-gray-900">Delete hotel?</h3>
            <p class="text-sm text-gray-600 mt-1">
              Are you sure you want to delete <span id="confirm-name" class="font-medium text-gray-900">this hotel</span>?
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
    function lockScroll(lock) {
      const body = document.body, doc = document.documentElement;
      if (lock) {
        const sbw = window.innerWidth - doc.clientWidth;
        if (sbw > 0) body.style.paddingRight = sbw + 'px';
        body.style.overflow = 'hidden';
      } else { body.style.overflow = ''; body.style.paddingRight = ''; }
    }
    function openModal() { document.getElementById('hotel-modal').classList.remove('hidden'); lockScroll(true); }
    function closeModal(){ document.getElementById('hotel-modal').classList.add('hidden'); lockScroll(false); }

    const emailInput = document.getElementById('email');
    const emailNote  = document.getElementById('email-note');

    // Create
    document.getElementById('add-hotel-btn')?.addEventListener('click', () => {
      document.getElementById('modal-title').textContent = "Add New Hotel";
      document.getElementById('hotel-form').action = "{{ route('admin.hotels.store') }}";
      document.getElementById('hotel-form').reset();
      document.getElementById('submit-button').textContent = "Create Hotel";
      document.getElementById('method-field').innerHTML = "";
      document.getElementById('status').value = 'Active';

      // Email editable on create
      emailInput.readOnly = false;
      emailInput.classList.remove('bg-gray-100','cursor-not-allowed','opacity-70');
      emailNote.classList.add('hidden');

      openModal();
    });

    // Edit
    document.querySelectorAll('.js-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id   = btn.dataset.id;
        const name = btn.dataset.name || "";
        const email= btn.dataset.email || "";
        const status = btn.dataset.status || "Active";
        const loc  = btn.dataset.location || "";

        document.getElementById('modal-title').textContent = "Edit Hotel";
        document.getElementById('hotel-form').action = `/admin/hotels/${id}`;
        document.getElementById('submit-button').textContent = "Edit Hotel";
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('status').value = status;
        document.getElementById('location_id').value = loc;

        // Email read-only on edit
        emailInput.readOnly = true;
        emailInput.classList.add('bg-gray-100','cursor-not-allowed','opacity-70');
        emailNote.classList.remove('hidden');

        openModal();
      });
    });

    document.getElementById('close-modal-btn')?.addEventListener('click', closeModal);

    // Custom confirm delete
    (function () {
      let pendingForm = null;
      const modal     = document.getElementById('confirm-modal');
      const nameSpan  = document.getElementById('confirm-name');
      const btnYes    = document.getElementById('confirm-yes');
      const btnCancel = document.getElementById('confirm-cancel');

      function openConfirm(name) {
        nameSpan.textContent = name || 'this hotel';
        modal.classList.remove('hidden');
        btnCancel.focus();
        lockScroll(true);
      }
      function closeConfirm() { modal.classList.add('hidden'); lockScroll(false); pendingForm = null; }
      modal.addEventListener('click', (e) => { if (e.target === modal.firstElementChild) closeConfirm(); });

      document.querySelectorAll('.js-delete-btn').forEach(b => {
        b.addEventListener('click', () => {
          pendingForm = b.closest('form');
          const name = pendingForm?.dataset?.name || '';
          openConfirm(name);
        });
      });

      btnCancel?.addEventListener('click', closeConfirm);
      btnYes?.addEventListener('click', () => { if (pendingForm) pendingForm.submit(); });
    })();
  </script>
@endpush
