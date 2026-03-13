{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes notifications</h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ auth()->user()->unreadNotifications->count() }} notification(s) non lue(s)
            </p>
        </div>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-check-double mr-2"></i>
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 last:border-0 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                @if(is_null($notification->read_at))
                                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                @endif
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $notification->data['message'] ?? 'Vous avez une nouvelle notification' }}
                            </p>
                            <p class="mt-2 text-xs text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            @if(is_null($notification->read_at))
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-blue-600" title="Marquer comme lu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('notifications.destroy', $notification->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer cette notification ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Aucune notification</p>
                <p class="text-sm text-gray-400 mt-1">Vous n'avez pas encore de notifications</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
