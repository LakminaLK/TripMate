<!-- Toast Container -->
<div x-data="toast()" x-init="boot()" class="fixed top-4 right-4 z-50">
    <template x-for="toast in toasts" :key="toast.id">
        <div class="group relative w-[360px] max-w-[90vw] bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden transition transform duration-200"
             x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90">
            <div class="absolute left-0 top-0 h-full w-1" :class="toast.type === 'error' ? 'bg-red-500' : 'bg-emerald-500'"></div>
            <div class="p-3 pl-4 pr-10 flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" :fill="toast.type === 'error' ? '#ef4444' : '#10b981'" opacity=".12"/>
                    <path d="M12 8v5M12 16h.01" :stroke="toast.type === 'error' ? '#ef4444' : '#10b981'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-sm text-gray-800 leading-snug" x-text="toast.message"></div>
                <button @click="removeToast(toast.id)" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>

<script>
    function toast() {
        return {
            toasts: [],
            boot() {
                @if(session('success'))
                    this.push("{{ session('success') }}", 'success');
                @endif
                @if(session('error'))
                    this.push("{{ session('error') }}", 'error');
                @endif
            },
            push(message, type = 'success') {
                const id = 't' + Math.random().toString(36).slice(2);
                const toast = {
                    id,
                    message,
                    type,
                    visible: true
                };
                
                this.toasts.push(toast);
                
                setTimeout(() => {
                    this.removeToast(id);
                }, 3000);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index > -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        };
    }
</script>
