<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Locations | TripMate</title>

    {{-- Tailwind --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">
    <div class="mt-16"> <!-- Added wrapper with top margin -->

    <!-- Expose flash for toasts -->
    <script>
      window.FLASH = {
        success: @json(session('success')),
        errors:  @json($errors->all()),
      };
    </script>

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

    <!-- Toast stack (below navbar, right) -->
    <div x-data="toast()" x-init="boot()"
         class="fixed right-6 space-y-3" style="top: 82px; z-index: 9999;"></div>

    <!-- Main Layout -->
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-200 fixed top-0 left-0 h-full p-4 space-y-4 text-sm font-medium pt-20 overflow-y-auto hidden md:block">
      <a href="{{ route('admin.dashboard') }}"
         class="{{ request()->routeIs('admin.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Dashboard
      </a>            <a href="{{ route('admin.customers') }}"
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

        <!-- Locations Content -->
        <div class="flex-1 md:ml-64 p-4 md:p-10 pt-32">
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm mb-6">
                    <h2 class="text-xl md:text-2xl font-bold">Locations Management</h2>
                    <div class="flex flex-wrap items-center gap-2 md:gap-3">
                    <!-- Filter pills -->
                    <div class="flex items-center gap-3">
                        @php $status = strtolower(request('status','all')); @endphp
                        <a href="{{ route('admin.locations.index', array_merge(request()->except('page'), ['status'=>'all'])) }}"
                           class="px-5 py-2 rounded-full {{ $status==='all'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">All</a>
                        <a href="{{ route('admin.locations.index', array_merge(request()->except('page'), ['status'=>'active'])) }}"
                           class="px-5 py-2 rounded-full {{ $status==='active'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Active</a>
                        <a href="{{ route('admin.locations.index', array_merge(request()->except('page'), ['status'=>'inactive'])) }}"
                           class="px-5 py-2 rounded-full {{ $status==='inactive'?'bg-gray-400 text-white':'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">Inactive</a>
                    </div>

                    <!-- Search + Add button -->
                    <div class="flex items-center gap-3">
                        <form method="GET" class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Location..."
                                   class="pl-9 pr-3 py-2 w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                            />
                            <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </form>

                        <button id="add-location-btn" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Location
                        </button>
                    </div>
                </div>
            </div>

            <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Location ID</th>
                        <th class="px-6 py-4 text-left">Main Image</th>
                        <th class="px-6 py-4 text-left">Location</th>
                        <th class="px-6 py-4 text-left">Activities</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm">
                    @forelse($locations as $location)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">{{ 'L' . str_pad($location->id, 3, '0', STR_PAD_LEFT) }}</td>

                            <td class="px-6 py-4">
                                @if($location->main_image)
                                    <img src="{{ asset('storage/'.$location->main_image) }}" class="w-24 h-16 object-cover rounded border">
                                @else
                                    <span class="text-xs text-gray-400">No image</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-medium">{{ $location->name }}</td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($location->activities as $act)
                                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">{{ $act->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">No activities linked</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center align-middle" style="width: 150px;">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        id="edit-location-btn-{{ $location->id }}"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                        data-description="{{ $location->description }}"
                                        data-activities='@json($location->activities->pluck("id"))'
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                        Edit
                                    </button>

                                    {{-- Custom-confirm delete (no native confirm()) --}}
                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" class="inline js-delete-form" data-name="{{ $location->name }}">
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
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No locations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $locations->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal (scrollable) -->
    <div id="location-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
        <div class="w-full h-full overflow-y-auto">
            <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/2 lg:w-1/3 mx-auto my-10" style="max-height:85vh; overflow-y:auto;">
                <h3 class="text-xl font-bold mb-4" id="modal-title">Add New Location</h3>

                <form id="location-form" action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div id="method-field" class="hidden">@method('PUT')</div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Location Name</label>
                        <input type="text" id="name" name="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="activities" class="block text-sm font-medium text-gray-700">Activities</label>
                        <select id="activities" name="activities[]" multiple class="mt-1 p-2 w-full border border-gray-300 rounded-md" size="8">
                            @foreach($activities as $a)
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or ⌘ (Mac) to select multiple.</p>
                    </div>

                    {{-- Main image --}}
                    <div class="mb-4">
                        <label for="main_image" class="block text-sm font-medium text-gray-700">Main Image</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                    </div>

                    {{-- New main selection preview --}}
                    <div id="selected-main-wrap" class="mt-2 hidden">
                        <div class="text-sm text-gray-700 mb-1">Selected Main Image (preview)</div>
                        <img id="selected-main-img" src="" class="w-24 h-16 object-cover rounded border" alt="">
                    </div>

                    {{-- Current MAIN image (edit only) --}}
                    <div id="current-main-wrap" class="mt-3 hidden">
                        <div class="text-sm font-medium mb-2">Current Main Image</div>
                        <div class="inline-block relative">
                            <img id="current-main-img" src="" class="w-24 h-16 object-cover rounded border" alt="">
                            <button type="button" id="delete-main-btn" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded px-1.5 py-0.5">×</button>
                        </div>
                    </div>

                    {{-- Gallery images --}}
                    <div class="mb-2 mt-4">
                        <label for="gallery" class="block text-sm font-medium text-gray-700">Additional Images</label>
                        <input type="file" id="gallery" name="gallery[]" multiple accept="image/*" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">You can select multiple images.</p>
                    </div>

                    {{-- New additional selections preview --}}
                    <div id="selected-gallery-wrap" class="mt-2 hidden">
                        <div class="text-sm text-gray-700 mb-1">Selected Additional Images (preview)</div>
                        <div id="selected-gallery-grid" class="flex flex-wrap gap-3"></div>
                    </div>

                    {{-- Existing ADDITIONAL images (edit only) --}}
                    <div id="existing-gallery" class="mt-3 space-y-2 hidden">
                        <div class="text-sm font-medium">Additional Images</div>
                        <div class="flex flex-wrap gap-3" id="existing-gallery-grid"></div>
                    </div>

                    <button type="submit" class="mt-6 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" id="submit-button">
                        Create Location
                    </button>
                </form>

                <button id="close-modal-btn" class="text-gray-500 hover:underline mt-4">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Custom Confirm Modal (centered with slight dim) --}}
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden">
      <!-- Slightly dimmed overlay (blocks clicks) -->
      <div class="absolute inset-0 bg-black bg-opacity-20 z-10"></div>

      <!-- Centered dialog -->
      <div class="absolute inset-0 flex items-center justify-center z-20">
        <div class="bg-white rounded-xl shadow-2xl ring-1 ring-black/5 overflow-hidden w-[90%] max-w-md">
          <div class="px-5 py-4 flex items-start gap-3">
            <svg class="w-6 h-6 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none">
              <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" fill="currentColor" opacity=".12"/>
              <path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
              <h3 class="text-base font-semibold text-gray-900">Delete location?</h3>
              <p class="text-sm text-gray-600 mt-1">
                Are you sure you want to delete <span id="confirm-name" class="font-medium text-gray-900">this location</span>?
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

    {{-- Data maps for images --}}
    <script>
        const LOCATION_MAIN = @json(
            $locations->mapWithKeys(fn($loc) => [
                $loc->id => $loc->main_image ? asset('storage/'.$loc->main_image) : null
            ])
        );

        const LOCATION_GALLERIES = @json(
            $locations->mapWithKeys(fn($loc) => [
                $loc->id => $loc->images->map(fn($img) => [
                    'id'  => $img->id,
                    'url' => asset('storage/'.$img->path),
                ])
            ])
        );

        const GALLERY_DELETE_URL = "{{ route('admin.locations.images.destroy', ':id') }}";
        const MAIN_DELETE_URL    = "{{ route('admin.locations.main.destroy', ':loc') }}";
        const CSRF_TOKEN         = "{{ csrf_token() }}";
    </script>

    <script>
        // Lock/unlock page scroll WITHOUT layout jump (compensate scrollbar width)
        function lockScroll(lock) {
            const body = document.body;
            const doc  = document.documentElement;
            if (lock) {
                const sbw = window.innerWidth - doc.clientWidth; // scrollbar width
                if (sbw > 0) body.style.paddingRight = sbw + 'px';
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
                body.style.paddingRight = '';
            }
        }

        // Add/Edit modal helpers
        function openModal() {
            document.getElementById('location-modal').classList.remove('hidden');
            lockScroll(true);
        }
        function closeModal() {
            document.getElementById('location-modal').classList.add('hidden');
            lockScroll(false);
        }

        // New selection previews
        const mainInput            = document.getElementById('main_image');
        const selectedMainWrap     = document.getElementById('selected-main-wrap');
        const selectedMainImg      = document.getElementById('selected-main-img');

        const galleryInput         = document.getElementById('gallery');
        const selectedGalleryWrap  = document.getElementById('selected-gallery-wrap');
        const selectedGalleryGrid  = document.getElementById('selected-gallery-grid');

        function renderGallerySelection(files) {
            selectedGalleryGrid.innerHTML = '';
            if (!files || !files.length) {
                selectedGalleryWrap.classList.add('hidden');
                return;
            }
            selectedGalleryWrap.classList.remove('hidden');
            Array.from(files).forEach(f => {
                if (!f.type.startsWith('image/')) return;
                const url = URL.createObjectURL(f);
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-20 h-14 object-cover rounded border';
                selectedGalleryGrid.appendChild(img);
            });
        }

        mainInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const url = URL.createObjectURL(this.files[0]);
                selectedMainImg.src = url;
                selectedMainWrap.classList.remove('hidden');
            } else {
                selectedMainImg.src = '';
                selectedMainWrap.classList.add('hidden');
            }
        });

        galleryInput.addEventListener('change', function () {
            renderGallerySelection(this.files);
        });

        // Create
        document.getElementById('add-location-btn').addEventListener('click', function () {
            document.getElementById('modal-title').textContent = "Add New Location";
            document.getElementById('location-form').action = "{{ route('admin.locations.store') }}";
            document.getElementById('location-form').reset();
            document.getElementById('submit-button').textContent = "Create Location";
            document.getElementById('method-field').innerHTML = ""; // clear PUT

            // clear activities
            const mult = document.getElementById('activities');
            Array.from(mult.options).forEach(o => o.selected = false);

            // hide existing images sections
            document.getElementById('current-main-wrap').classList.add('hidden');
            document.getElementById('current-main-img').src = '';
            document.getElementById('existing-gallery').classList.add('hidden');
            document.getElementById('existing-gallery-grid').innerHTML = '';

            // reset new selection previews
            mainInput.value = ''; selectedMainImg.src = ''; selectedMainWrap.classList.add('hidden');
            galleryInput.value = ''; renderGallerySelection(null);

            openModal();
        });

        // Edit
        document.querySelectorAll('[id^="edit-location-btn-"]').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name || "";
                const description = this.dataset.description || "";
                const activities = JSON.parse(this.dataset.activities || "[]").map(Number);

                document.getElementById('modal-title').textContent = "Edit Location";
                document.getElementById('name').value = name;
                document.getElementById('description').value = description;
                document.getElementById('location-form').action = `/admin/locations/${id}`;
                document.getElementById('submit-button').textContent = "Edit Location";
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                const mult = document.getElementById('activities');
                Array.from(mult.options).forEach(o => {
                    o.selected = activities.includes(parseInt(o.value));
                });

                // Reset new selections
                mainInput.value = ''; selectedMainImg.src = ''; selectedMainWrap.classList.add('hidden');
                galleryInput.value = ''; renderGallerySelection(null);

                // MAIN image (current from DB)
                const mainWrap = document.getElementById('current-main-wrap');
                const mainImg  = document.getElementById('current-main-img');
                const delMain  = document.getElementById('delete-main-btn');
                const mainUrl  = LOCATION_MAIN[id] || null;

                if (mainUrl) {
                    mainWrap.classList.remove('hidden');
                    mainImg.src = mainUrl;
                    delMain.onclick = async () => {
                        const url = MAIN_DELETE_URL.replace(':loc', id);
                        await fetch(url, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                            body: new URLSearchParams({ _method: 'DELETE' })
                        }).catch(() => {});
                        mainWrap.classList.add('hidden');
                        mainImg.src = '';
                        LOCATION_MAIN[id] = null;
                        window.pushToast?.('Main image removed.');
                    };
                } else {
                    mainWrap.classList.add('hidden');
                    mainImg.src = '';
                }

                // ADDITIONAL images (current from DB)
                const wrap = document.getElementById('existing-gallery');
                const grid = document.getElementById('existing-gallery-grid');
                grid.innerHTML = '';
                const gallery = LOCATION_GALLERIES[id] || [];
                if (gallery.length) {
                    wrap.classList.remove('hidden');
                    gallery.forEach(img => {
                        const card = document.createElement('div');
                        card.className = 'relative';
                        card.innerHTML = `
                            <img src="${img.url}" class="w-20 h-14 object-cover rounded border" />
                            <button type="button"
                                    data-img="${img.id}"
                                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded px-1.5 py-0.5">×</button>
                        `;
                        card.querySelector('button').onclick = async (e) => {
                            const imgId = e.currentTarget.dataset.img;
                            const url   = GALLERY_DELETE_URL.replace(':id', imgId);
                            await fetch(url, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                                body: new URLSearchParams({ _method: 'DELETE' })
                            }).catch(() => {});
                            // remove from DOM + local map
                            card.remove();
                            const list = LOCATION_GALLERIES[id] || [];
                            const idx  = list.findIndex(x => x.id == imgId);
                            if (idx >= 0) list.splice(idx, 1);
                            if (!list.length) wrap.classList.add('hidden');
                            window.pushToast?.('Additional image removed.');
                        };
                        grid.appendChild(card);
                    });
                } else {
                    wrap.classList.add('hidden');
                }

                openModal();
            });
        });

        // Close Add/Edit modal
        document.getElementById('close-modal-btn').addEventListener('click', closeModal);

        // ===== Custom Confirm (Delete) =====
        (function () {
          let pendingForm = null;
          const modal      = document.getElementById('confirm-modal');
          const nameSpan   = document.getElementById('confirm-name');
          const btnYes     = document.getElementById('confirm-yes');
          const btnCancel  = document.getElementById('confirm-cancel');

          function openConfirm(name) {
            nameSpan.textContent = name || 'this location';
            modal.classList.remove('hidden');
            btnCancel.focus();
            lockScroll(true);  // lock background without layout shift
          }
          function closeConfirm() {
            modal.classList.add('hidden');
            lockScroll(false);
            pendingForm = null;
          }

          // Close when clicking the overlay
          modal.addEventListener('click', (e) => {
            const overlay = modal.firstElementChild; // the dimmed overlay
            if (e.target === overlay) closeConfirm();
          });

          // Hook all Delete buttons
          document.querySelectorAll('.js-delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
              pendingForm = btn.closest('form');
              const name = pendingForm?.dataset?.name || '';
              openConfirm(name);
            });
          });

          btnCancel.addEventListener('click', closeConfirm);
          btnYes.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
            // let navigation happen; no need to close explicitly
          });
        })();
    </script>

    <!-- Alpine.js -->
        <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Mobile Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>

    <!-- Toast component -->
    <script>
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
              ? {bar:'bg-red-500', icon:'#ef4444'}
              : {bar:'bg-emerald-500', icon:'#10b981'};

            const el = document.createElement('div');
            el.id = id;
            el.className =
              'group relative w-[360px] max-w-[90vw] bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden ' +
              'transition transform duration-200';
            el.innerHTML = `
              <div class="absolute left-0 top-0 h-full w-1 ${colors.bar}"></div>
              <div class="p-3 pl-4 pr-10 flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                  <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" fill="${colors.icon}" opacity=".12"/>
                  <path d="M12 8v5M12 16h.01" stroke="${colors.icon}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-sm text-gray-800 leading-snug">${message}</div>
                <button onclick="document.getElementById('${id}')?.remove()"
                        class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18 6L6 18M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            `;

            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            this.$root.appendChild(el);
            requestAnimationFrame(() => {
              el.style.opacity = '1';
              el.style.transform = 'translateY(0)';
            });

            setTimeout(() => {
              el.style.opacity = '0';
              el.style.transform = 'translateY(-6px)';
              setTimeout(() => el.remove(), 180);
            }, 3000);
          }
        }
      }
    </script>
    </div> <!-- Close wrapper -->
</body>
</html>
