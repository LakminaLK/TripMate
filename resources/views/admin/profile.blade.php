@extends('admin.layouts.app')

@section('title', 'Admin Profile')

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
    @include('admin.components.page-header', [
        'title' => 'Admin Profile'
    ])

    <div class="max-w-7xl mx-auto">
        
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: stacked cards -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Account Details -->
          <section class="bg-white rounded border p-6 shadow">
            <h3 class="text-lg font-semibold mb-4">Account Details</h3>
            <dl class="text-sm divide-y divide-gray-100">
              <div class="flex items-center justify-between py-3">
                <dt class="text-gray-600">Current Username</dt>
                <dd class="font-medium">{{ $admin->username }}</dd>
              </div>
              <div class="flex items-center justify-between py-3">
                <dt class="text-gray-600">Admin ID</dt>
                <dd class="font-medium">#{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}</dd>
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

            <form action="{{ route('admin.profile.username.update') }}" method="POST" class="space-y-3" autocomplete="off">
              @csrf
              @method('PUT')
              <div>
                <label class="block text-sm text-gray-700 mb-1" for="username">New Username</label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  value="{{ old('username', $admin->username) }}"
                  class="w-full border border-gray-300 rounded px-3 py-2"
                  required
                  autocomplete="new-username"
                  autocapitalize="off"
                  spellcheck="false"
                >
                <p class="text-xs text-gray-500 mt-1">Must be unique among admins.</p>
              </div>
              <button type="submit"
                      class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                Update Username
              </button>
            </form>
          </section>
        </div>

        <!-- RIGHT: Change Password -->
        <section class="bg-white rounded border p-6 shadow lg:col-span-1">
          <h3 class="text-lg font-semibold mb-4">Change Password</h3>

          <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
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

<!-- Alpine -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Toast stack: below navbar, right side -->
<div x-data="toast()" x-init="boot()"
     class="fixed right-6 space-y-3" style="top: 82px; z-index: 9999;"></div>

<script>
  // Eye toggles
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-eye').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = document.querySelector(btn.dataset.toggle);
        if (target) target.type = target.type === 'password' ? 'text' : 'password';
      });
    });
  });

  // Chrome/Edge: ensure no input focused on initial paint or bfcache restore
  function defocus() {
    if (!document.body.hasAttribute('tabindex')) {
      document.body.setAttribute('tabindex', '-1');
    }
    document.body.focus({ preventScroll: true });
  }
  window.addEventListener('DOMContentLoaded', defocus);
  window.addEventListener('load', defocus);
  window.addEventListener('pageshow', (e) => { if (e.persisted) defocus(); });

  // Toasts – clean cards with icons + animations
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

        // enter animation
        el.style.opacity = '0';
        el.style.transform = 'translateY(-6px)';
        this.$root.appendChild(el);
        requestAnimationFrame(() => {
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        });

        // auto‑dismiss ~3s
        setTimeout(() => {
          el.style.opacity = '0';
          el.style.transform = 'translateY(-6px)';
          setTimeout(() => el.remove(), 180);
        }, 3000);
      }
    }
  }
@endsection

@push('scripts')
    <!-- Custom scripts for profile page -->
    <script>
        // Profile page specific JavaScript functionality
    </script>
@endpush
