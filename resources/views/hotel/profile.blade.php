<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotel Profile | TripMate</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen antialiased">

<!-- Flash for toasts -->
<script>
  window.FLASH = {
    success: @json(session('success')),
    errors:  @json($errors->all()),
  };
</script>

<!-- ========== TOP NAVBAR (same as dashboard) ========== -->
<div class="bg-white h-16 px-6 flex justify-between items-center shadow fixed top-0 w-full z-30">
  <!-- Left: burger + logo -->
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

  <!-- Right: Profile Dropdown -->
  <div x-data="{ open:false }" class="relative">
    <button @click="open=!open"
            class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 focus:outline-none">
      <img src="{{ asset('images/Profile.png') }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
    </button>

    <div x-show="open" @click.away="open=false" x-transition
         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
      <a href="{{ route('hotel.profile.edit') }}" @click="open=false"
         class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
      <form method="POST" action="{{ route('hotel.logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-red-700 hover:bg-gray-100">Logout</button>
      </form>
    </div>
  </div>
</div>
<!-- /TOP NAVBAR -->

<div class="mt-16"><!-- offset for fixed navbar -->
  <div class="flex min-h-screen">
    <!-- ========== SIDEBAR (same as dashboard; added Profile item) ========== -->
    <div class="w-64 bg-gray-200 fixed left-0 h-full p-4 space-y-4 text-sm font-medium pt-4 overflow-y-auto hidden md:block">
      <a href="{{ route('hotel.dashboard') }}"
         class="{{ request()->routeIs('hotel.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Dashboard
      </a>
      <a href="#"
         class="block px-2 py-1 hover:bg-gray-100 rounded">
        My Hotel Info
      </a>
      <a href="#"
         class="block px-2 py-1 hover:bg-gray-100 rounded">
        Bookings
      </a>
      <a href="#"
         class="block px-2 py-1 hover:bg-gray-100 rounded">
        Messages
      </a>
      <a href="#"
         class="block px-2 py-1 hover:bg-gray-100 rounded">
        Reviews
      </a>
    </div>

    <!-- ========== MOBILE SIDEBAR (same as dashboard) ========== -->
    <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()">
      <div class="w-64 bg-gray-200 h-full p-4 space-y-4 text-sm font-medium pt-20" onclick="event.stopPropagation()">
        <a href="{{ route('hotel.dashboard') }}"
           class="block px-2 py-1 hover:bg-gray-100 rounded">
          Dashboard
        </a>
        <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">My Hotel Info</a>
        <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Bookings</a>
        <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Messages</a>
        <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Reviews</a>
        <a href="{{ route('hotel.profile.edit') }}"
           class="bg-white font-semibold block px-2 py-1 hover:bg-gray-100 rounded">
          Profile
        </a>
      </div>
    </div>

    <!-- ========== PAGE CONTENT (profile forms) ========== -->
    <main class="flex-1 md:ml-64 p-4 md:p-10">
      <div class="max-w-7xl mx-auto">
        <div class="mb-6 mt-2">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl md:text-2xl font-bold">Hotel Profile</h2>
            <p class="text-sm text-gray-600">Manage your account details and password.</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- LEFT column: Account Details + Change Username -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Account Details -->
            <section class="bg-white rounded border p-6 shadow">
              <h3 class="text-lg font-semibold mb-4">Account Details</h3>
              <dl class="text-sm divide-y divide-gray-100">
                <div class="flex items-center justify-between py-3">
                  <dt class="text-gray-600">Current Username</dt>
                  <dd class="font-medium">{{ $hotel->username }}</dd>
                </div>
                <div class="flex items-center justify-between py-3">
                  <dt class="text-gray-600">Hotel ID</dt>
                  <dd class="font-medium">#{{ str_pad($hotel->id, 3, '0', STR_PAD_LEFT) }}</dd>
                </div>
              </dl>
            </section>

            <!-- Change Username -->
            <section class="bg-white rounded border p-6 shadow">
              <h3 class="text-lg font-semibold mb-4">Change Username</h3>

              @if ($errors->has('username'))
                <div class="mb-3 text-sm bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded">
                  {{ $errors->first('username') }}
                </div>
              @endif

              <form action="{{ route('hotel.profile.username.update') }}" method="POST" class="space-y-3" autocomplete="off">
                @csrf
                @method('PUT')
                <div>
                  <label class="block text-sm text-gray-700 mb-1" for="username">New Username</label>
                  <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username', $hotel->username) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    required
                    autocomplete="new-username"
                    autocapitalize="off"
                    spellcheck="false"
                  >
                  <p class="text-xs text-gray-500 mt-1">Must be unique among hotels.</p>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                  Update Username
                </button>
              </form>
            </section>
          </div>

          <!-- RIGHT column: Change Password -->
          <section class="bg-white rounded border p-6 shadow lg:col-span-1">
            <h3 class="text-lg font-semibold mb-4">Change Password</h3>

            <form action="{{ route('hotel.profile.password.update') }}" method="POST" class="space-y-4">
              @csrf
              @method('PUT')

              <div>
                <label class="block text-sm text-gray-700 mb-1" for="current_password">Current Password</label>
                <div class="relative">
                  <input type="password" id="current_password" name="current_password"
                         class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                  <button type="button" data-toggle="#current_password"
                          class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm text-gray-700 mb-1" for="password">New Password</label>
                <div class="relative">
                  <input type="password" id="password" name="password"
                         class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                  <button type="button" data-toggle="#password"
                          class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">At least 8 characters.</p>
              </div>

              <div>
                <label class="block text-sm text-gray-700 mb-1" for="password_confirmation">Confirm New Password</label>
                <div class="relative">
                  <input type="password" id="password_confirmation" name="password_confirmation"
                         class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                  <button type="button" data-toggle="#password_confirmation"
                          class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                    <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                </div>
              </div>

              <button type="submit"
                      class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                Update Password
              </button>
            </form>
          </section>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Toast stack -->
<div x-data="toast()" x-init="boot()"
     class="fixed right-6 space-y-3" style="top: 82px; z-index: 9999;"></div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    if (sidebar) sidebar.classList.toggle('hidden');
  }

  // Eye toggles
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-eye').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = document.querySelector(btn.dataset.toggle);
        if (target) target.type = target.type === 'password' ? 'text' : 'password';
      });
    });
  });

  // Toasts
  function toast() {
    return {
      boot() {
        if (window.FLASH?.success) this.push(window.FLASH.success, 'success');
        if (Array.isArray(window.FLASH?.errors) && window.FLASH.errors.length) {
          window.FLASH.errors.forEach(e => this.push(e, 'error'));
        }
      },
      push(message, type='success') {
        const id = 't' + Math.random().toString(36).slice(2);
        const colors = type === 'error'
          ? {bar:'bg-red-500', icon:'#ef4444'}
          : {bar:'bg-emerald-500', icon:'#10b981'};

        const el = document.createElement('div');
        el.id = id;
        el.className =
          'group relative w-[360px] max-w-[90vw] bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden transition transform duration-200';
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
        document.querySelector('[x-data="toast()"]')?.appendChild(el);
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

<!-- Mobile Sidebar container (placed at end so toggle works) -->
<div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()">
  <div class="w-64 bg-gray-200 h-full p-4 space-y-4 text-sm font-medium pt-20" onclick="event.stopPropagation()">
    <a href="{{ route('hotel.dashboard') }}" class="block px-2 py-1 hover:bg-gray-100 rounded">Dashboard</a>
    <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">My Hotel Info</a>
    <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Bookings</a>
    <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Messages</a>
    <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Reviews</a>
    <a href="{{ route('hotel.profile.edit') }}" class="bg-white font-semibold block px-2 py-1 hover:bg-gray-100 rounded">Profile</a>
  </div>
</div>

</body>
</html>
