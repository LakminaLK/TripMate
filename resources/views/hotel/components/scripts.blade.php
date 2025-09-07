<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Mobile Sidebar Script -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        sidebar.classList.toggle('hidden');
    }
</script>

<!-- Base Toast Component -->
<script>
    function toast() {
        return {
            boot() {
                // Handle window.FLASH data if available (from pages that expose it)
                if (window.FLASH) {
                    if (window.FLASH.success) this.push(window.FLASH.success, 'success');
                    if (Array.isArray(window.FLASH.errors) && window.FLASH.errors.length) {
                        window.FLASH.errors.forEach(e => this.push(e, 'error'));
                    }
                } else {
                    // Only handle Laravel session messages if window.FLASH is not available
                    @if(session('success'))
                        this.push("{{ session('success') }}", 'success');
                    @endif
                    @if(session('error'))
                        this.push("{{ session('error') }}", 'error');
                    @endif
                }
                
                // Make push function globally available for manual calls
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
