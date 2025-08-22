<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotels | TripMate</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">
    <div class="mt-16"> <!-- Added wrapper with top margin -->

  {{-- Expose flash for toasts --}}
  <script>
    window.FLASH = {
      success: @json(session('success')),
      errors:  @json($errors->all()),
    };
  </script>

  <!-- Top Navbar -->
      <!-- Top Navbar -->
    <div class="bg-white h-16 px-6 flex justify-between items-center shadow fixed top-0 w-full z-30">
    <!-- Logo + Menu Area -->
    <div class="flex items-center gap-4">
      <!-- Mobile Menu Button -->
      <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleSidebar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      
      <div class="flex items-center gap-2">
        <img src="{{ asset('images/tm1.png') }}" alt="TripMate Logo" class="h-8 w-8">
        <h1 class="text-2xl font-bold">TripMate</h1>
      </div>
    </div>

    <!-- Profile Dropdown -->
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open"
              class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 focus:outline-none">
        <img src="{{ asset('images/Profile.png') }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
      </button>

      <div x-show="open" x-transition @click.away="open = false"
           class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 text-red-700 hover:bg-gray-100">Logout</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast stack -->
  <div x-data="toast()" x-init="boot()"
       class="fixed right-6 space-y-3" style="top:82px; z-index:9999;"></div>

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-200 fixed top-0 left-0 h-full p-4 space-y-4 text-sm font-medium pt-20 overflow-y-auto hidden md:block">
      <a href="{{ route('admin.dashboard') }}"
         class="{{ request()->routeIs('admin.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Dashboard
      </a>

      <a href="{{ route('admin.customers') }}"
         class="{{ request()->routeIs('admin.customers') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Customers
      </a>

      <a href="{{ route('admin.activities.index') }}"
         class="{{ request()->routeIs('admin.activities.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Activities
      </a>

      <a href="{{ route('admin.locations.index') }}"
         class="{{ request()->routeIs('admin.locations.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Locations
      </a>

      <a href="{{ route('admin.hotels.index') }}"
         class="{{ request()->routeIs('admin.hotels.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Hotels
      </a>

      <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
      <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <button class="md:hidden fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg" onclick="toggleSidebar()">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()">
      <div class="w-64 bg-gray-200 h-full p-4 space-y-4 text-sm font-medium pt-20" onclick="event.stopPropagation()">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
          Dashboard
        </a>
        <a href="{{ route('admin.customers') }}"
           class="{{ request()->routeIs('admin.customers') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
          Customers
        </a>
        <a href="{{ route('admin.activities.index') }}"
           class="{{ request()->routeIs('admin.activities.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
          Activities
        </a>
        <a href="{{ route('admin.locations.index') }}"
           class="{{ request()->routeIs('admin.locations.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
          Locations
        </a>
        <a href="{{ route('admin.hotels.index') }}"
           class="{{ request()->routeIs('admin.hotels.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
          Hotels
        </a>
        <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
        <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
      </div>
    </div>

    <!-- Content -->
    <div class="flex-1 md:ml-64 p-4 md:p-10 pt-32">
      <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm mb-6">
          <h2 class="text-xl md:text-2xl font-bold">Hotels Management</h2>
          <div class="flex flex-wrap items-center gap-2 md:gap-3">
          <!-- Filter pills -->
          <div class="flex flex-wrap items-center gap-2 md:gap-3">
            @php $status = strtolower(request('status','all')); @endphp
            <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'all'])) }}"
               class="text-sm px-4 md:px-5 py-2 rounded-full {{ $status==='all'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">All</a>
            <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'active'])) }}"
               class="text-sm px-4 md:px-5 py-2 rounded-full {{ $status==='active'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Active</a>
            <a href="{{ route('admin.hotels.index', array_merge(request()->except('page'), ['status'=>'inactive'])) }}"
               class="text-sm px-4 md:px-5 py-2 rounded-full {{ $status==='inactive'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Inactive</a>
          </div>

          <!-- Search + Add button -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <form method="GET" class="relative flex-1 sm:flex-none">
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Hotel..."
                     class="w-full sm:w-64 pl-9 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
              />
              <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                <path d="m21 21-3.5-3.5M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"
                      stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </form>

            <button id="add-hotel-btn" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Hotel
            </button>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
          <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
            <tr>
              <th class="px-4 md:px-6 py-4 text-left">Hotel ID</th>
              <th class="px-4 md:px-6 py-4 text-left">Hotel Name</th>
              <th class="hidden md:table-cell px-6 py-4 text-left">Email</th>
              <th class="hidden md:table-cell px-6 py-4 text-left">Location</th>
              <th class="px-4 md:px-6 py-4 text-left">Status</th>
              <th class="hidden md:table-cell px-6 py-4 text-left">Bookings</th>
              <th class="hidden md:table-cell px-6 py-4 text-left">Total Revenue($)</th>
              <th class="px-4 md:px-6 py-4 text-left">Actions</th>
            </tr>
          </thead>
        <tbody class="text-gray-800 text-sm">
          @forelse($hotels as $h)
            <tr class="border-t hover:bg-gray-50 transition">
              <td class="px-6 py-4 font-medium">{{ 'H' . str_pad($h->id, 3, '0', STR_PAD_LEFT) }}</td>

              <td class="px-6 py-4 font-medium">{{ $h->name ?? $h->username }}</td>
              <td class="px-6 py-4">{{ $h->email ?? '—' }}</td>
              <td class="px-6 py-4">{{ $h->location?->name ?? '—' }}</td>

              <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                  {{ strtolower($h->status)==='active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                  {{ $h->status }}
                </span>
              </td>

              <td class="px-6 py-4">{{ $h->bookings_count ?? 0 }}</td>
              <td class="px-6 py-4">{{ number_format($h->total_revenue ?? 0, 2) }}</td>

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
            <tr><td colspan="8" class="px-6 py-6 text-center text-gray-500">No hotels found.</td></tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-4">{{ $hotels->links() }}</div>
    </div>
  </div>

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

  <!-- Alpine -->
  <script src="//unpkg.com/alpinejs" defer></script>

  <!-- Page JS -->
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
    document.getElementById('add-hotel-btn').addEventListener('click', () => {
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

    document.getElementById('close-modal-btn').addEventListener('click', closeModal);

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

      btnCancel.addEventListener('click', closeConfirm);
      btnYes.addEventListener('click', () => { if (pendingForm) pendingForm.submit(); });
    })();

    // Mobile Sidebar Toggle
    function toggleSidebar() {
      const sidebar = document.getElementById('mobileSidebar');
      sidebar.classList.toggle('hidden');
    }

    // Toasts
    function toast() {
      return {
        boot() {
          if (window.FLASH?.success) this.push(window.FLASH.success, 'success');
          if (Array.isArray(window.FLASH?.errors) && window.FLASH.errors.length) {
            window.FLASH.errors.forEach(e => this.push(e, 'error'));
          }
          window.pushToast = (msg, type='success') => this.push(msg, type);
        },
        push(message, type='success') {
          const id = 't' + Math.random().toString(36).slice(2);
          const colors = type === 'error'
            ? {bar:'bg-red-500', icon:'#ef4444'} : {bar:'bg-emerald-500', icon:'#10b981'};
          const el = document.createElement('div');
          el.id = id;
          el.className = 'group relative w-[360px] max-w-[90vw] bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden transition transform duration-200';
          el.innerHTML = `
            <div class="absolute left-0 top-0 h-full w-1 ${colors.bar}"></div>
            <div class="p-3 pl-4 pr-10 flex items-start gap-3">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" fill="${colors.icon}" opacity=".12"/>
                <path d="M12 8v5M12 16h.01" stroke="${colors.icon}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <div class="text-sm text-gray-800 leading-snug">${message}</div>
              <button onclick="document.getElementById('${id}')?.remove()" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18 6L6 18M6 6l12 12"/></svg>
              </button>
            </div>`;
          el.style.opacity='0'; el.style.transform='translateY(-6px)';
          this.$root.appendChild(el);
          requestAnimationFrame(()=>{ el.style.opacity='1'; el.style.transform='translateY(0)'; });
          setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(-6px)'; setTimeout(()=>el.remove(),180); }, 3000);
        }
      }
    }
  </script>
  </div> <!-- Close wrapper -->
</body>
</html>
