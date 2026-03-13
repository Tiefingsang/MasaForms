@extends('layouts.app')

@section('title', 'Gestion des API')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">API</h1>
        <p class="mt-1 text-sm text-gray-600">
            Gérez vos clés d'API pour intégrer vos formulaires avec d'autres applications.
        </p>
    </div>

    <!-- Navigation des onglets -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('settings.index') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Général
            </a>
            <a href="{{ route('settings.preferences') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Préférences
            </a>
            <a href="{{ route('settings.notifications') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notifications
            </a>
            <a href="{{ route('settings.team') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Équipe
            </a>
            <a href="{{ route('settings.api') }}"
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                API
            </a>
            <a href="{{ route('settings.billing') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Facturation
            </a>
        </nav>
    </div>

    @php
        $plan = auth()->user()->currentPlan()->first();
        $hasApiAccess = $plan && $plan->has_api_access;
    @endphp

    @if(!$hasApiAccess)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        L'accès à l'API est disponible uniquement sur le plan Business.
                        <a href="{{ route('plans.index') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                            Passez au plan Business
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Documentation API -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Documentation API</h3>
            <p class="mt-1 text-sm text-gray-500">
                Intégrez Masadigitale Forms avec vos applications.
            </p>
        </div>

        <div class="px-6 py-5">
            <div class="prose prose-sm max-w-none">
                <p>Utilisez notre API REST pour interagir avec vos formulaires et réponses.</p>

                <h4 class="font-medium mt-4">Base URL</h4>
                <pre class="bg-gray-100 p-2 rounded text-sm">{{ url('/api/v1') }}</pre>

                <h4 class="font-medium mt-4">Authentification</h4>
                <pre class="bg-gray-100 p-2 rounded text-sm">Authorization: Bearer {votre_clé_api}</pre>

                <h4 class="font-medium mt-4">Exemples</h4>
                <pre class="bg-gray-100 p-2 rounded text-sm">
// Récupérer tous vos formulaires
GET /api/v1/forms

// Récupérer les réponses d'un formulaire
GET /api/v1/forms/{id}/responses

// Soumettre une réponse
POST /api/v1/forms/{id}/responses</pre>
            </div>

            <div class="mt-4">
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Voir la documentation complète
                </a>
            </div>
        </div>
    </div>

    <!-- Créer une nouvelle clé API -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Créer une clé API</h3>
            <p class="mt-1 text-sm text-gray-500">
                Générez une nouvelle clé pour authentifier vos applications.
            </p>
        </div>

        <form action="{{ route('settings.api.keys.create') }}" method="POST" class="px-6 py-5">
            @csrf
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="key_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom de la clé
                    </label>
                    <input type="text"
                           name="name"
                           id="key_name"
                           placeholder="Ex: Application mobile, Site web..."
                           {{ !$hasApiAccess ? 'disabled' : '' }}
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ !$hasApiAccess ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                </div>
                <button type="submit"
                        {{ !$hasApiAccess ? 'disabled' : '' }}
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ !$hasApiAccess ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-plus mr-2"></i>
                    Générer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des clés API -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vos clés API</h3>
            <p class="mt-1 text-sm text-gray-500">
                Ces clés donnent accès à votre compte. Gardez-les secrètes.
            </p>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($apiKeys ?? [] as $key)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $key->name }}</p>
                            <div class="flex items-center mt-1">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $key->key }}</code>
                                <span class="ml-2 text-xs text-gray-500">Créée le {{ $key->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="copyToClipboard('{{ $key->key }}')"
                                    class="text-gray-400 hover:text-gray-600"
                                    title="Copier">
                                <i class="fas fa-copy"></i>
                            </button>
                            <form action="{{ route('settings.api.keys.revoke', $key) }}" method="POST"
                                  onsubmit="return confirm('Révoquer cette clé API ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Révoquer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-key text-3xl mb-2"></i>
                    <p>Aucune clé API</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Clé copiée dans le presse-papier');
    }, function() {
        alert('Erreur lors de la copie');
    });
}
</script>
@endpush
@endsection
