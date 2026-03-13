@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div class="max-w-4xl mx-auto" x-data="notificationManager()" x-init="init()">
    <!-- En-tête -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes notifications</h1>
            <p class="mt-1 text-sm text-gray-600">
                Gérez toutes vos notifications et restez informé de votre activité.
            </p>
        </div>

        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <button @click="markAllAsRead()"
                    x-show="unreadCount > 0"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-check-double mr-2"></i>
                Tout marquer comme lu
            </button>

            <button @click="showFilters = !showFilters"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-filter mr-2"></i>
                Filtrer
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div x-show="showFilters" x-cloak class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select x-model="filters.status" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="all">Toutes</option>
                    <option value="unread">Non lues</option>
                    <option value="read">Lues</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select x-model="filters.type" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="all">Tous les types</option>
                    <option value="response">Réponses</option>
                    <option value="subscription">Abonnements</option>
                    <option value="form">Formulaires</option>
                    <option value="template">Templates</option>
                    <option value="system">Système</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select x-model="filters.period" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="all">Toutes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                </select>
            </div>
        </div>
        <div class="mt-3 flex justify-end">
            <button @click="applyFilters()" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                Appliquer les filtres
            </button>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-bell text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total || 0"></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Lues</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.read || 0"></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                    <i class="fas fa-envelope text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Non lues</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.unread || 0"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- En-tête de liste -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox"
                           @click="toggleSelectAll"
                           :checked="selectedNotifications.length === filteredNotifications.length && filteredNotifications.length > 0"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-3 text-sm text-gray-700">
                        <span x-text="selectedNotifications.length"></span> sélectionnée(s)
                    </span>
                </div>
                <div class="flex items-center space-x-2" x-show="selectedNotifications.length > 0">
                    <button @click="markSelectedAsRead" class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-check mr-1"></i> Marquer comme lues
                    </button>
                    <span class="text-gray-300">|</span>
                    <button @click="deleteSelected" class="text-sm text-red-600 hover:text-red-800">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="divide-y divide-gray-200">
            <template x-for="notification in filteredNotifications" :key="notification.id">
                <div class="px-6 py-4 hover:bg-gray-50 transition"
                     :class="{ 'bg-blue-50': !notification.read_at }">

                    <div class="flex items-start">
                        <!-- Checkbox -->
                        <div class="flex-shrink-0 pt-1">
                            <input type="checkbox"
                                   :value="notification.id"
                                   x-model="selectedNotifications"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        </div>

                        <!-- Icone selon le type -->
                        <div class="flex-shrink-0 ml-3">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center"
                                 :class="{
                                     'bg-blue-100': notification.data.type === 'response',
                                     'bg-green-100': notification.data.type === 'subscription_activated',
                                     'bg-yellow-100': notification.data.type === 'subscription_expiring',
                                     'bg-red-100': notification.data.type === 'subscription_expired',
                                     'bg-purple-100': notification.data.type === 'new_template',
                                     'bg-orange-100': notification.data.type === 'form_limit',
                                     'bg-gray-100': !notification.data.type
                                 }">
                                <i class="text-lg"
                                   :class="{
                                       'fas fa-reply text-blue-600': notification.data.type === 'response',
                                       'fas fa-crown text-green-600': notification.data.type === 'subscription_activated',
                                       'fas fa-clock text-yellow-600': notification.data.type === 'subscription_expiring',
                                       'fas fa-times-circle text-red-600': notification.data.type === 'subscription_expired',
                                       'fas fa-template text-purple-600': notification.data.type === 'new_template',
                                       'fas fa-chart-line text-orange-600': notification.data.type === 'form_limit',
                                       'fas fa-bell text-gray-600': !notification.data.type
                                   }"></i>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1 ml-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900"
                                   x-text="notification.data.title"></p>
                                <span class="text-xs text-gray-400" x-text="formatDate(notification.created_at)"></span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600" x-text="notification.data.message"></p>

                            <!-- Métadonnées supplémentaires -->
                            <div class="mt-2 flex items-center space-x-4 text-xs">
                                <span class="text-gray-400">
                                    <i class="far fa-clock mr-1"></i>
                                    <span x-text="timeAgo(notification.created_at)"></span>
                                </span>

                                <!-- Lien d'action si présent -->
                                <template x-if="notification.data.action_url">
                                    <a :href="notification.data.action_url"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <span x-text="notification.data.action_text || 'Voir'"></span>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 ml-4 flex items-start space-x-2">
                            <template x-if="!notification.read_at">
                                <button @click="markAsRead(notification.id)"
                                        class="text-gray-400 hover:text-blue-600"
                                        title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </template>
                            <button @click="deleteNotification(notification.id)"
                                    class="text-gray-400 hover:text-red-600"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Message si aucune notification -->
            <div x-show="filteredNotifications.length === 0" class="px-6 py-12 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
                <p class="text-gray-500 mb-4">Vous n'avez pas encore de notifications.</p>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-home mr-2"></i>
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4" x-show="notifications.length > 0">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Page <span x-text="currentPage"></span> sur <span x-text="lastPage"></span>
            </div>
            <div class="flex space-x-2">
                <button @click="prevPage"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button @click="nextPage"
                        :disabled="currentPage === lastPage"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function notificationManager() {
    return {
        notifications: @json($notifications->items()),
        total: {{ $notifications->total() }},
        currentPage: {{ $notifications->currentPage() }},
        lastPage: {{ $notifications->lastPage() }},
        perPage: {{ $notifications->perPage() }},

        showFilters: false,
        filters: {
            status: 'all',
            type: 'all',
            period: 'all'
        },

        selectedNotifications: [],
        unreadCount: {{ auth()->user()->unreadNotifications->count() }},

        get stats() {
            return {
                total: this.notifications.length,
                read: this.notifications.filter(n => n.read_at).length,
                unread: this.notifications.filter(n => !n.read_at).length
            };
        },

        get filteredNotifications() {
            return this.notifications.filter(notification => {
                // Filtre par statut
                if (this.filters.status === 'unread' && notification.read_at) return false;
                if (this.filters.status === 'read' && !notification.read_at) return false;

                // Filtre par type
                if (this.filters.type !== 'all' && notification.data.type !== this.filters.type) return false;

                // Filtre par période
                if (this.filters.period !== 'all') {
                    const date = new Date(notification.created_at);
                    const now = new Date();

                    if (this.filters.period === 'today') {
                        if (date.toDateString() !== now.toDateString()) return false;
                    } else if (this.filters.period === 'week') {
                        const weekAgo = new Date(now.setDate(now.getDate() - 7));
                        if (date < weekAgo) return false;
                    } else if (this.filters.period === 'month') {
                        const monthAgo = new Date(now.setMonth(now.getMonth() - 1));
                        if (date < monthAgo) return false;
                    }
                }

                return true;
            });
        },

        init() {
            console.log('Notification manager initialized');
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        timeAgo(date) {
            const now = new Date();
            const past = new Date(date);
            const diffInSeconds = Math.floor((now - past) / 1000);

            if (diffInSeconds < 60) return 'à l\'instant';
            if (diffInSeconds < 3600) return `il y a ${Math.floor(diffInSeconds / 60)} min`;
            if (diffInSeconds < 86400) return `il y a ${Math.floor(diffInSeconds / 3600)} h`;
            if (diffInSeconds < 2592000) return `il y a ${Math.floor(diffInSeconds / 86400)} j`;
            return this.formatDate(date);
        },

        toggleSelectAll() {
            if (this.selectedNotifications.length === this.filteredNotifications.length) {
                this.selectedNotifications = [];
            } else {
                this.selectedNotifications = this.filteredNotifications.map(n => n.id);
            }
        },

        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const notification = this.notifications.find(n => n.id === id);
                    if (notification) {
                        notification.read_at = new Date().toISOString();
                        this.unreadCount--;
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    this.notifications.forEach(n => n.read_at = new Date().toISOString());
                    this.unreadCount = 0;
                    this.selectedNotifications = [];
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        },

        async markSelectedAsRead() {
            for (const id of this.selectedNotifications) {
                await this.markAsRead(id);
            }
            this.selectedNotifications = [];
        },

        async deleteNotification(id) {
            if (!confirm('Supprimer cette notification ?')) return;

            try {
                const response = await fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    if (!this.notifications.find(n => n.id === id)?.read_at) {
                        this.unreadCount--;
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        },

        async deleteSelected() {
            if (!confirm(`Supprimer ${this.selectedNotifications.length} notification(s) ?`)) return;

            for (const id of [...this.selectedNotifications]) {
                await this.deleteNotification(id);
            }
            this.selectedNotifications = [];
        },

        applyFilters() {
            this.showFilters = false;
            // La logique de filtrage est réactive via filteredNotifications
        },

        prevPage() {
            if (this.currentPage > 1) {
                window.location.href = `?page=${this.currentPage - 1}`;
            }
        },

        nextPage() {
            if (this.currentPage < this.lastPage) {
                window.location.href = `?page=${this.currentPage + 1}`;
            }
        }
    }
}
</script>
@endpush
@endsection
