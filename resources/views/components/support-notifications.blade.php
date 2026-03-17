<!-- Enhanced Support Notifications Dropdown -->
<div class="relative" x-data="{ open: false, notifications: [] }" x-init="loadNotifications()">
    <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
        <span class="material-icons text-[20px]">notifications</span>
        <!-- Dynamic notification badge -->
        <span x-show="notifications.filter(n => !n.read).length > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] rounded-full h-4 w-4 flex items-center justify-center"
              x-text="notifications.filter(n => !n.read).length">
        </span>
    </button>
    
    <div x-show="open" 
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute top-full right-0 mt-2 w-96 bg-white rounded-sm shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900" style="font-family: 'Poppins', sans-serif;">
                    Notifikasi Sokongan
                </h3>
                <div class="flex items-center space-x-2">
                    <button @click="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800">
                        Tandakan semua dibaca
                    </button>
                    <button @click="loadNotifications()" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-sm">refresh</span>
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1" x-text="`${notifications.filter(n => !n.read).length} notifikasi baharu`"></p>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer"
                     :class="{ 'bg-blue-50': !notification.read }"
                     @click="handleNotificationClick(notification)">
                    
                    <!-- Support Chat Notification -->
                    <template x-if="notification.type === 'support_chat'">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-green-600 text-sm">chat</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900" x-text="notification.masjid_name"></p>
                                    <div class="flex items-center">
                                        <span x-show="notification.priority === 'urgent'" 
                                              class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mr-2">Urgent</span>
                                        <span x-show="notification.priority === 'high'" 
                                              class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full mr-2">High</span>
                                        <span class="text-xs text-gray-500" x-text="notification.time_ago"></span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                                <div class="flex items-center mt-2">
                                    <span class="material-icons text-green-500 text-xs mr-1">circle</span>
                                    <span class="text-xs text-green-600">Chat aktif</span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Support Ticket Notification -->
                    <template x-if="notification.type === 'support_ticket'">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-blue-600 text-sm">confirmation_number</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900" x-text="`Tiket #${notification.ticket_id}`"></p>
                                    <div class="flex items-center">
                                        <span x-show="notification.priority === 'urgent'" 
                                              class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full mr-2">Urgent</span>
                                        <span x-show="notification.priority === 'high'" 
                                              class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full mr-2">High</span>
                                        <span class="text-xs text-gray-500" x-text="notification.time_ago"></span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1" x-text="notification.subject"></p>
                                <p class="text-xs text-gray-500 mt-1" x-text="notification.masjid_name"></p>
                                <div class="flex items-center mt-2">
                                    <span class="material-icons text-blue-500 text-xs mr-1">schedule</span>
                                    <span class="text-xs text-blue-600">Menunggu respons</span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- System Alert Notification -->
                    <template x-if="notification.type === 'system_alert'">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-red-600 text-sm">warning</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">System Alert</p>
                                    <span class="text-xs text-gray-500" x-text="notification.time_ago"></span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                                <div class="flex items-center mt-2">
                                    <span class="material-icons text-red-500 text-xs mr-1">error</span>
                                    <span class="text-xs text-red-600">Perlu tindakan</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="notifications.length === 0" class="px-4 py-8 text-center">
                <span class="material-icons text-gray-400 text-4xl mb-2">notifications_none</span>
                <p class="text-sm text-gray-500">Tiada notifikasi baharu</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <a href="{{ route('support.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat semua
                </a>
                <div class="flex items-center space-x-2">
                    <button @click="toggleSound()" class="text-xs text-gray-600 hover:text-gray-800">
                        <span class="material-icons text-sm" x-text="soundEnabled ? 'volume_up' : 'volume_off'"></span>
                    </button>
                    <button @click="openSettings()" class="text-xs text-gray-600 hover:text-gray-800">
                        <span class="material-icons text-sm">settings</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function supportNotifications() {
    return {
        notifications: [],
        soundEnabled: true,
        
        async loadNotifications() {
            try {
                const response = await fetch('/api/support/notifications');
                const data = await response.json();
                this.notifications = data.notifications || [];
            } catch (error) {
                console.error('Error loading notifications:', error);
                // Sample data for demo
                this.notifications = [
                    {
                        id: 1,
                        type: 'support_chat',
                        masjid_name: 'Masjid Al-Falah',
                        message: 'Saya ada masalah dengan upload dokumen...',
                        priority: 'urgent',
                        time_ago: '2 min ago',
                        read: false
                    },
                    {
                        id: 2,
                        type: 'support_ticket',
                        ticket_id: 'TKT-001',
                        subject: 'Sistem tidak boleh login',
                        masjid_name: 'Masjid Ar-Rahman',
                        priority: 'high',
                        time_ago: '30 min ago',
                        read: false
                    },
                    {
                        id: 3,
                        type: 'system_alert',
                        message: 'Multiple failed login attempts detected',
                        time_ago: '1 hour ago',
                        read: true
                    }
                ];
            }
        },
        
        async markAllAsRead() {
            try {
                await fetch('/api/support/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                this.notifications.forEach(n => n.read = true);
            } catch (error) {
                console.error('Error marking notifications as read:', error);
            }
        },
        
        handleNotificationClick(notification) {
            // Mark as read
            notification.read = true;
            
            // Navigate based on type
            if (notification.type === 'support_chat') {
                window.location.href = `/support/chat/${notification.id}`;
            } else if (notification.type === 'support_ticket') {
                window.location.href = `/support/ticket/${notification.ticket_id}`;
            }
        },
        
        toggleSound() {
            this.soundEnabled = !this.soundEnabled;
            localStorage.setItem('support_sound_enabled', this.soundEnabled);
        },
        
        openSettings() {
            // Open notification settings modal
            console.log('Open notification settings');
        }
    }
}
</script>
