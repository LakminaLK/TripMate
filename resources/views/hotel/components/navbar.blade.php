<!-- Top Navbar -->
<div class="bg-white dark:bg-dark-100 h-20 px-6 flex justify-between items-center shadow-lg fixed top-0 w-full z-30 border-b border-gray-100 dark:border-dark-200 transition-colors duration-300">
    <!-- Logo + Menu Area -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 rounded-lg hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 dark:hover:from-dark-200 dark:hover:to-dark-300 transition-all duration-200" onclick="toggleSidebar()">
            <svg class="w-6 h-6 text-gray-700 dark:text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center">
                <img src="{{ asset('images/tm1.png') }}" alt="TripMate Logo" class="h-8 w-8">
            </div>
            <h1 class="text-2xl font-bold text-black dark:text-white">TripMate</h1>
            <span class="text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-1 rounded-full font-medium">Hotel</span>
        </div>
    </div>

    <!-- Right Side - Notifications and Profile -->
    <div class="flex items-center gap-3">
        <!-- Notifications Dropdown -->
        <div x-data="notificationDropdown()" x-cloak class="relative">
            <!-- Notification Icon Button -->
            <button @click="toggleDropdown()" 
                    class="relative inline-flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-100 dark:hover:bg-dark-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-dark-400 focus:ring-offset-2 transition-all duration-200">
                <i class="fas fa-bell text-gray-600 dark:text-dark-500 text-lg"></i>
                <!-- Notification Badge -->
                <span x-show="unreadCount > 0" 
                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                      class="absolute top-2 right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" 
                @click.away="open = false" 
                x-transition 
                class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-100 rounded-xl shadow-xl border border-gray-100 dark:border-dark-200 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-dark-700">Notifications</h3>
                        <p class="text-xs text-gray-500 dark:text-dark-400" x-text="unreadCount > 0 ? `You have ${unreadCount} new notifications` : 'No new notifications'"></p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="clearAllNotifications()" 
                                class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium"
                                title="Clear All">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
                
                <div class="max-h-80 overflow-y-auto" id="notifications-container">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="handleNotificationClick(notification)" 
                             :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read, 'bg-white dark:bg-dark-100': notification.is_read}"
                             class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-dark-200 transition-colors border-b border-gray-50 dark:border-dark-200 cursor-pointer">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                     :class="`bg-${notification.color}-100 dark:bg-${notification.color}-900`">
                                    <i :class="notification.icon" 
                                       :class="`text-${notification.color}-600 dark:text-${notification.color}-400`"
                                       class="text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-dark-700" x-text="notification.title"></p>
                                    <p class="text-xs text-gray-600 dark:text-dark-500" x-text="notification.message"></p>
                                    <p class="text-xs text-gray-400 dark:text-dark-400 mt-1" x-text="notification.time_ago"></p>
                                </div>
                                <div x-show="!notification.is_read" 
                                     :class="`bg-${notification.color}-500`"
                                     class="w-2 h-2 rounded-full flex-shrink-0"></div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Empty state -->
                    <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-dark-400">
                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                        <p class="text-sm">No notifications yet</p>
                    </div>

                    <!-- Loading state -->
                    <div x-show="loading" class="px-4 py-8 text-center text-gray-500 dark:text-dark-400">
                        <i class="fas fa-spinner fa-spin text-xl mb-2"></i>
                        <p class="text-sm">Loading notifications...</p>
                    </div>
                </div>
                
                <!-- Show scroll indicator if more than 5 notifications -->
                <div x-show="notifications.length > 5" class="px-4 py-2 border-t border-gray-100 dark:border-dark-200 bg-gray-50 dark:bg-dark-200 text-center">
                    <p class="text-xs text-gray-500 dark:text-dark-400">
                        <i class="fas fa-chevron-down text-xs mr-1"></i>
                        Scroll for more notifications
                    </p>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" x-cloak class="relative">
        <!-- Profile Icon Button -->
        <button @click="open = !open" 
                class="inline-flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-100 dark:hover:bg-dark-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-dark-400 focus:ring-offset-2 transition-all duration-200">
            <img src="{{ asset('images/Profile.png') }}" 
                alt="Profile" 
                class="w-8 h-8 rounded-full object-cover">
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
            @click.away="open = false" 
            x-transition 
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-100 rounded-xl shadow-xl border border-gray-100 dark:border-dark-200 z-50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200">
                <p class="text-sm font-medium text-gray-900 dark:text-dark-700">{{ auth('hotel')->user()->name ?? 'Hotel' }}</p>
            </div>
            
            <a href="{{ route('hotel.profile.edit') ?? '#' }}" 
            class="flex items-center px-4 py-3 text-gray-700 dark:text-dark-500 hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 dark:hover:from-dark-200 dark:hover:to-dark-300 transition-all duration-200">
                <i class="fas fa-user mr-3 text-gray-500 dark:text-dark-400"></i>
                Profile
            </a>
            
            <!-- Dark Mode Toggle -->
            <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-moon mr-3 text-gray-500 dark:text-dark-400"></i>
                        <span class="text-gray-700 dark:text-dark-500">Dark Mode</span>
                    </div>
                    <button @click="$store.darkMode._initialized && $store.darkMode.toggle(); console.log('Dark mode toggled:', $store.darkMode.on)" 
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            :class="$store.darkMode.on ? 'bg-green-600' : 'bg-gray-200'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                              :class="$store.darkMode.on ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('hotel.logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center text-left px-4 py-3 text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-dark-200 transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
            this.loadNotifications();
            // Poll for new notifications every 30 seconds
            setInterval(() => {
                this.loadNotifications();
            }, 30000);
        },
        
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.loadNotifications();
            }
        },
        
        async loadNotifications() {
            try {
                const response = await fetch('{{ route("hotel.notifications.index") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        },
        
        async handleNotificationClick(notification) {
            try {
                // Mark as read first
                await fetch(`{{ url('/hotel/notifications') }}/${notification.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });
                
                // Update local state
                notification.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                
                // Close dropdown
                this.open = false;
                
                // Redirect to action URL
                if (notification.action_url) {
                    window.location.href = notification.action_url;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
                // Still redirect even if marking as read fails
                if (notification.action_url) {
                    window.location.href = notification.action_url;
                }
            }
        },
        
        async clearAllNotifications() {
            try {
                const response = await fetch('{{ route("hotel.notifications.clear-all") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    this.notifications = [];
                    this.unreadCount = 0;
                }
            } catch (error) {
                console.error('Error clearing notifications:', error);
                alert('Error clearing notifications. Please try again.');
            }
        }
    }
}

// Auto-mark notifications as read when visiting specific pages
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    let notificationType = null;
    
    if (currentPath.includes('/bookings')) {
        notificationType = 'booking';
    } else if (currentPath.includes('/reviews')) {
        notificationType = 'review';
    }
    
    if (notificationType) {
        fetch(`{{ url('/hotel/notifications/mark-type-read') }}/${notificationType}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        }).catch(error => console.error('Error auto-marking notifications as read:', error));
    }
});
</script>
