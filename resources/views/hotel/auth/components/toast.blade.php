<!-- Toast stack -->
<div x-data="toast()" x-init="boot()"
     class="fixed right-6 space-y-3" style="top:82px; z-index:9999;"></div>

<script>
    function toast() {
        return {
            notifications: [],
            boot() {
                @if(session('toast'))
                    this.show(@json(session('toast')));
                @endif
            },
            show(notification) {
                notification.id = Date.now();
                this.notifications.push(notification);
                
                if (notification.duration) {
                    setTimeout(() => {
                        this.dismiss(notification.id);
                    }, notification.duration);
                }
            },
            dismiss(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
            },
            getTypeClasses(type) {
                switch (type) {
                    case 'success':
                        return 'bg-green-500 text-white';
                    case 'error':
                        return 'bg-red-500 text-white';
                    case 'warning':
                        return 'bg-yellow-500 text-white';
                    default:
                        return 'bg-gray-800 text-white';
                }
            },
            getIconClass(type) {
                switch (type) {
                    case 'success':
                        return 'fas fa-check-circle';
                    case 'error':
                        return 'fas fa-exclamation-circle';
                    case 'warning':
                        return 'fas fa-exclamation-triangle';
                    default:
                        return 'fas fa-info-circle';
                }
            }
        };
    }
</script>

<template x-if="notifications.length > 0">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             :class="getTypeClasses(notification.type)"
             class="rounded-lg p-4 shadow-lg flex items-center space-x-3 min-w-[300px]">
            <i :class="getIconClass(notification.type)" class="text-lg"></i>
            <p class="flex-1" x-text="notification.message"></p>
            <button @click="dismiss(notification.id)" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </template>
</template>
