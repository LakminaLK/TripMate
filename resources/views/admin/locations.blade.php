<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Locations | TripMate</title>

    {{-- Tailwind (same as Activities page) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">

    <!-- Top Navbar -->
    <div class="bg-white py-4 px-6 flex justify-between items-center shadow">
        <h1 class="text-2xl font-bold">TripMate</h1>
        <div class="flex items-center space-x-4">
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-200 h-screen p-4 space-y-4 text-sm font-medium">
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

            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Hotels (coming soon)</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Locations Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Locations Management</h2>

            <!-- Flash -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add button -->
            <button id="add-location-btn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                Add New Location
            </button>

            <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Location ID</th>
                        <th class="px-6 py-4 text-left">Main Image</th>
                        <th class="px-6 py-4 text-left">Location</th>  {{-- NEW --}}
                        <th class="px-6 py-4 text-left">Activities</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm">
                    @forelse($locations as $location)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">{{ 'L' . str_pad($location->id, 3, '0', STR_PAD_LEFT) }}</td>

                            <!-- Main Image -->
                            <td class="px-6 py-4">
                                @if($location->main_image)
                                    <img src="{{ asset('storage/'.$location->main_image) }}"
                                        class="w-24 h-16 object-cover rounded border">
                                @else
                                    <span class="text-xs text-gray-400">No image</span>
                                @endif
                            </td>

                            <!-- Location name only -->
                            <td class="px-6 py-4 font-medium">{{ $location->name }}</td>                            

                            <!-- Activities -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($location->activities as $act)
                                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                            {{ $act->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">No activities linked</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Actions (center aligned) -->
                            <td class="px-6 py-4 text-center align-middle" style="width: 150px;">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        id="edit-location-btn-{{ $location->id }}"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                        data-description="{{ $location->description }}"
                                        data-activities='@json($location->activities->pluck("id"))'
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm"
                                    >
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this location?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
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

    <!-- Modal (scrollable) -->
    <div id="location-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
        <!-- viewport scroll container -->
        <div class="w-full h-full overflow-y-auto">
            <!-- modal card -->
            <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/2 lg:w-1/3 mx-auto my-10"
                 style="max-height:85vh; overflow-y:auto;">
                <h3 class="text-xl font-bold mb-4" id="modal-title">Add New Location</h3>

                <!-- Location Form (Create/Update) -->
                <form id="location-form" action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Method Spoofing (only when editing) -->
                    <div id="method-field" class="hidden">
                        @method('PUT')
                    </div>

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

                    {{-- Main image upload --}}
                    <div class="mb-4">
                        <label for="main_image" class="block text-sm font-medium text-gray-700">Main Image</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                    </div>

                    {{-- New main selection preview --}}
                    <div id="selected-main-wrap" class="mt-2 hidden">
                        <div class="text-sm text-gray-700 mb-1">Selected Main Image (preview)</div>
                        <img id="selected-main-img" src="" class="w-24 h-16 object-cover rounded border" alt="">
                    </div>

                    {{-- Current MAIN image (edit only) – with red "×" overlay like gallery --}}
                    <div id="current-main-wrap" class="mt-3 hidden">
                        <div class="text-sm font-medium mb-2">Current Main Image</div>
                        <div class="inline-block relative">
                            <img id="current-main-img" src="" class="w-24 h-16 object-cover rounded border" alt="">
                            <button type="button"
                                    id="delete-main-btn"
                                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded px-1.5 py-0.5">×</button>
                        </div>
                    </div>

                    {{-- Gallery images upload --}}
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

    {{-- Data maps for images --}}
    <script>
        // location_id -> main image URL or null
        const LOCATION_MAIN = @json(
            $locations->mapWithKeys(fn($loc) => [
                $loc->id => $loc->main_image ? asset('storage/'.$loc->main_image) : null
            ])
        );

        // location_id -> [{id, url}]
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
        // Helpers to lock/unlock page scroll when modal is open
        function openModal() {
            document.getElementById('location-modal').classList.remove('hidden');
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('location-modal').classList.add('hidden');
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        }

        // ---- New selection previews ----
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
                        };
                        grid.appendChild(card);
                    });
                } else {
                    wrap.classList.add('hidden');
                }

                openModal();
            });
        });

        // Close
        document.getElementById('close-modal-btn').addEventListener('click', function () {
            closeModal();
        });
    </script>

</body>
</html>
