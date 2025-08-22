<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activities | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-300 min-h-screen">

    <!-- Expose flash for toasts -->
    <script>
      window.FLASH = {
        success: @json(session('success')),
        errors:  @json($errors->all()),
      };
    </script>

    <!-- Top Navbar -->
    <div class="bg-white py-4 px-6 flex justify-between items-center shadow">
        <h1 class="text-2xl font-bold">TripMate</h1>
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

    <!-- Toasts (below navbar, right) -->
    <div x-data="toast()" x-init="boot()"
         class="fixed right-6 space-y-3" style="top: 82px; z-index: 9999;"></div>

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

            <a href="{{ route('admin.hotels.index') }}"
            class="{{ request()->routeIs('admin.hotels.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
            Hotels
          </a>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Activities Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Activities Management</h2>

            <button id="add-activity-btn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                Add New Activity
            </button>

            <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Activity ID</th>
                        <th class="px-6 py-4 text-left">Activity Name</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm">
                    @forelse($activities as $activity)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">{{ 'A' . str_pad($activity->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">{{ $activity->name }}</td>
                            <td class="px-6 py-4 capitalize">{{ $activity->status }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button
                                    id="edit-activity-btn-{{ $activity->id }}"
                                    data-activity='@json($activity)'
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                    Edit
                                </button>

                                {{-- Custom-confirm delete (no native confirm()) --}}
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}"
                                      method="POST" class="inline js-delete-form"
                                      data-name="{{ $activity->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm js-delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No activities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Activity Modal (scrollable) -->
    <div id="activity-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
        <div class="w-full h-full overflow-y-auto flex items-start justify-center">
            <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/2 lg:w-1/3 mx-auto my-10"
                 style="max-height:85vh; overflow-y:auto;">
                <h3 class="text-xl font-bold mb-4" id="modal-title">Add New Activity</h3>

                <form id="activity-form" action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div id="method-field" class="hidden">@method('PUT')</div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Activity Name</label>
                        <input type="text" id="name" name="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Activity Image</label>
                        <input type="file" id="image" name="image" accept="image/*" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                    </div>

                    {{-- New image selection preview --}}
                    <div id="selected-image-wrap" class="mt-2 hidden">
                        <div class="text-sm text-gray-700 mb-1">Selected Image (preview)</div>
                        <img id="selected-image" src="" class="w-28 h-20 object-cover rounded border" alt="">
                    </div>

                    {{-- Current image (edit only) --}}
                    <div id="current-image-wrap" class="mt-3 hidden">
                        <div class="text-sm font-medium mb-2">Current Image</div>
                        <div class="inline-block relative">
                            <img id="current-image" src="" class="w-28 h-20 object-cover rounded border" alt="">
                            <button type="button" id="delete-current-image"
                                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded px-1.5 py-0.5">×</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" id="submit-button">
                        Create Activity
                    </button>
                </form>

                <button id="close-modal-btn" class="text-gray-500 hover:underline mt-4">Cancel</button>
            </div>
        </div>
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
              <h3 class="text-base font-semibold text-gray-900">Delete activity?</h3>
              <p class="text-sm text-gray-600 mt-1">
                Are you sure you want to delete <span id="confirm-name" class="font-medium text-gray-900">this activity</span>?
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

    {{-- Robust image map + endpoints (safe even if route missing) --}}
    @php
    $ACTIVITY_IMAGE = [];
    foreach ($activities as $a) {
        $raw = $a->image;
        if (!$raw) { $ACTIVITY_IMAGE[$a->id] = null; continue; }

        $p = ltrim(str_replace('\\','/',$raw), '/');

        if (preg_match('#^https?://#', $p)) {
            $url = $p; // full URL
        } elseif (str_starts_with($p, 'storage/')) {
            $url = asset($p); // already a web path
        } elseif (str_starts_with($p, 'public/')) {
            $url = asset('storage/' . substr($p, 7));
        } else {
            // "images/..." or "activities/..." etc saved on public disk
            $url = asset('storage/' . $p);
        }

        $ACTIVITY_IMAGE[$a->id] = $url;
    }

    $hasDestroyRoute = \Illuminate\Support\Facades\Route::has('admin.activities.image.destroy');
    $destroyUrl = $hasDestroyRoute ? route('admin.activities.image.destroy', ':act') : null;
@endphp


    <script>
        // Map: activity_id -> image URL or null
        const ACTIVITY_IMAGE = @json($ACTIVITY_IMAGE);

        // If you have the destroy route, this is a URL with a placeholder ":act"
        const ACT_IMAGE_DELETE_URL = @json($destroyUrl);

        const CSRF_TOKEN = "{{ csrf_token() }}";
        const APP_URL      = "{{ rtrim(asset(''), '/') }}";
        const STORAGE_BASE = "{{ rtrim(asset('storage'), '/') }}";
    </script>

    <script>
        // Scroll-lock without layout jump
        function lockScroll(lock) {
            const body = document.body, doc = document.documentElement;
            if (lock) {
                const sbw = window.innerWidth - doc.clientWidth;
                if (sbw > 0) body.style.paddingRight = sbw + 'px';
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
                body.style.paddingRight = '';
            }
        }

        // ===== Activity modal helpers =====
        function openActivityModal() {
            document.getElementById('activity-modal').classList.remove('hidden');
            lockScroll(true);
        }
        function closeActivityModal() {
            document.getElementById('activity-modal').classList.add('hidden');
            lockScroll(false);
        }

        // Elements for image preview sections
        const fileInput           = document.getElementById('image');
        const selectedWrap        = document.getElementById('selected-image-wrap');
        const selectedImg         = document.getElementById('selected-image');
        const currentWrap         = document.getElementById('current-image-wrap');
        const currentImg          = document.getElementById('current-image');
        const deleteCurrentButton = document.getElementById('delete-current-image');

        // Show preview when selecting a new image
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0] && this.files[0].type.startsWith('image/')) {
                const url = URL.createObjectURL(this.files[0]);
                selectedImg.src = url;
                selectedWrap.classList.remove('hidden');
            } else {
                selectedImg.src = '';
                selectedWrap.classList.add('hidden');
            }
        });

        // Open modal for create
        document.getElementById('add-activity-btn').addEventListener('click', function () {
            document.getElementById('modal-title').textContent = "Add New Activity";
            document.getElementById('activity-form').action = "{{ route('admin.activities.store') }}";
            document.getElementById('activity-form').reset();
            document.getElementById('submit-button').textContent = "Create Activity";
            document.getElementById('method-field').innerHTML = ""; // clear method override

            // hide current & selected previews
            currentWrap.classList.add('hidden'); currentImg.src = '';
            fileInput.value = ''; selectedImg.src = ''; selectedWrap.classList.add('hidden');

            openActivityModal();
        });

        // Open modal for edit
        document.querySelectorAll('[id^="edit-activity-btn-"]').forEach(button => {
            button.addEventListener('click', function () {
                const activity = JSON.parse(this.dataset.activity || "{}");

                document.getElementById('modal-title').textContent = "Edit Activity";
                document.getElementById('name').value = activity.name || '';
                document.getElementById('description').value = activity.description || '';
                document.getElementById('status').value = activity.status || 'active';
                document.getElementById('activity-form').action = `/admin/activities/${activity.id}`;
                document.getElementById('submit-button').textContent = "Edit Activity";
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // reset new selection preview
                fileInput.value = '';
                selectedImg.src = '';
                selectedWrap.classList.add('hidden');

                // show current image if exists (from server map)
                let imgUrl = ACTIVITY_IMAGE[activity.id] || null;
                if (!imgUrl) {
                    // Fallback: build from raw value present on the activity JSON
                    const raw = activity.image || '';
                    if (raw) {
                        let p = raw.startsWith('public/') ? raw.slice(7) : raw;
                        imgUrl = (/^https?:\/\//.test(p) || p.startsWith('/'))
                            ? p
                            : (p.startsWith('storage/') ? `${APP_URL}/${p}` : `${STORAGE_BASE}/${p}`);
                    }
                }

                if (imgUrl) {
                    currentWrap.classList.remove('hidden');
                    currentImg.src = imgUrl;

                    deleteCurrentButton.onclick = async () => {
                        if (!ACT_IMAGE_DELETE_URL) {
                            // If no route exists, just hide locally so UI doesn't break
                            currentWrap.classList.add('hidden');
                            currentImg.src = '';
                            ACTIVITY_IMAGE[activity.id] = null;
                            window.pushToast?.('Activity image removed (UI only).');
                            return;
                        }
                        const url = ACT_IMAGE_DELETE_URL.replace(':act', activity.id);
                        await fetch(url, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                            body: new URLSearchParams({ _method: 'DELETE' })
                        }).catch(() => {});
                        currentWrap.classList.add('hidden');
                        currentImg.src = '';
                        ACTIVITY_IMAGE[activity.id] = null;
                        window.pushToast?.('Activity image removed.');
                    };
                } else {
                    currentWrap.classList.add('hidden');
                    currentImg.src = '';
                }

                openActivityModal();
            });
        });

        // Close modal
        document.getElementById('close-modal-btn').addEventListener('click', closeActivityModal);

        // ===== Custom Confirm (Delete) =====
        (function () {
          let pendingForm = null;
          const modal      = document.getElementById('confirm-modal');
          const nameSpan   = document.getElementById('confirm-name');
          const btnYes     = document.getElementById('confirm-yes');
          const btnCancel  = document.getElementById('confirm-cancel');

          function openConfirm(name) {
            nameSpan.textContent = name || 'this activity';
            modal.classList.remove('hidden');
            btnCancel.focus();
            lockScroll(true);
          }
          function closeConfirm() {
            modal.classList.add('hidden');
            lockScroll(false);
            pendingForm = null;
          }

          // close on overlay click
          modal.addEventListener('click', (e) => {
            const overlay = modal.firstElementChild;
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

          btnCancel.addEventListener('click', closeConfirm);
          btnYes.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
          });
        })();
    </script>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

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
</body>
</html>
