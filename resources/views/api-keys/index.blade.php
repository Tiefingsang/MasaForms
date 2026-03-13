@extends('layouts.app')

@section('title', 'Clés API')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Clés API</h1>
            <p class="mt-1 text-sm text-gray-600">
                Gérez vos clés d'API pour intégrer vos formulaires avec d'autres applications.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('api-keys.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Créer une clé API
            </a>
        </div>
    </div>

    <!-- Documentation rapide -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Utilisez vos clés API pour authentifier vos requêtes. Incluez-la dans l'en-tête :
                    <code class="bg-blue-100 px-2 py-1 rounded">Authorization: Bearer VOTRE_CLE_API</code>
                </p>
            </div>
        </div>
    </div>

    <!-- Liste des clés API -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vos clés API</h3>
        </div>

        @if(session('plain_text_key'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 m-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Conservez cette clé précieusement !</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Votre nouvelle clé API est :
                            <code class="bg-yellow-100 px-2 py-1 rounded font-mono text-sm">{{ session('plain_text_key') }}</code>
                        </p>
                        <p class="text-xs text-yellow-600 mt-1">
                            Elle ne sera plus jamais affichée. Copiez-la dès maintenant.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="divide-y divide-gray-200">
            @forelse($apiKeys as $key)
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <p class="text-sm font-medium text-gray-900">{{ $key->name }}</p>
                                @if(!$key->is_active)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                        Inactive
                                    </span>
                                @endif
                                @if($key->isExpired())
                                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-full">
                                        Expirée
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1 flex items-center text-xs text-gray-500">
                                <span>Créée le {{ $key->created_at->format('d/m/Y') }}</span>
                                @if($key->last_used_at)
                                    <span class="mx-2">•</span>
                                    <span>Dernière utilisation : {{ $key->last_used_at->diffForHumans() }}</span>
                                @endif
                                @if($key->expires_at)
                                    <span class="mx-2">•</span>
                                    <span>Expire le {{ $key->expires_at->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <div class="mt-2">
                                <span class="text-xs text-gray-500">Permissions :</span>
                                @foreach($key->permissions as $permission)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-full">
                                        {{ $permission }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('api-keys.show', $key) }}"
                               class="text-gray-400 hover:text-gray-600"
                               title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('api-keys.edit', $key) }}"
                               class="text-gray-400 hover:text-gray-600"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="regenerateKey({{ $key->id }})"
                                    class="text-gray-400 hover:text-blue-600"
                                    title="Régénérer">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <form action="{{ route('api-keys.destroy', $key) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir révoquer cette clé API ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600" title="Révoquer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-key text-3xl mb-2"></i>
                    <p>Aucune clé API</p>
                    <p class="text-sm mt-1">Créez votre première clé pour commencer à utiliser l'API.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function regenerateKey(keyId) {
    if (confirm('Régénérer cette clé API ? L\'ancienne clé ne fonctionnera plus.')) {
        fetch(`/api-keys/${keyId}/regenerate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
