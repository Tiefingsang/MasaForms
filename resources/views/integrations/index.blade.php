@extends('layouts.app')

@section('title', 'Intégrations')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Intégrations</h1>
        <p class="mt-1 text-sm text-gray-600">
            Connectez vos formulaires avec vos applications préférées pour automatiser vos workflows.
        </p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-plug text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Intégrations disponibles</p>
                    <p class="text-xl font-semibold text-gray-900">{{ count($availableIntegrations) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Intégrations actives</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $integrations->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-crown text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Intégrations premium</p>
                    <p class="text-xl font-semibold text-gray-900">{{ collect($availableIntegrations)->where('premium', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes intégrations -->
    @if($integrations->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Mes intégrations actives</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($integrations as $integration)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ $integration->type }} text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $integration->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ ucfirst($integration->type) }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>
                                Actif
                            </span>
                        </div>

                        <div class="text-sm text-gray-600 mb-3">
                            Connecté depuis le {{ $integration->created_at->format('d/m/Y') }}
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <button onclick="testIntegration({{ $integration->id }})"
                                    class="text-sm text-gray-600 hover:text-gray-900">
                                <i class="fas fa-vial mr-1"></i>
                                Tester
                            </button>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('integrations.edit', $integration) }}"
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('integrations.destroy', $integration) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cette intégration ?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Intégrations disponibles -->
    <h2 class="text-lg font-medium text-gray-900 mb-4">Intégrations disponibles</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($availableIntegrations as $key => $integration)
            @php
                $userPlan = auth()->user()->currentPlan()->first();
                $isPremium = $integration['premium'];
                $hasAccess = !$isPremium || ($userPlan && $userPlan->has_integrations);
                $isConfigured = $integrations->where('type', $key)->first();
            @endphp

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition {{ !$hasAccess ? 'opacity-75' : '' }}">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                @if($key == 'whatsapp')
                                    <i class="fab fa-whatsapp text-2xl text-green-600"></i>
                                @elseif($key == 'slack')
                                    <i class="fab fa-slack text-2xl text-purple-600"></i>
                                @elseif($key == 'mailchimp')
                                    <i class="fab fa-mailchimp text-2xl text-yellow-600"></i>
                                @elseif($key == 'google_sheets')
                                    <i class="fab fa-google text-2xl text-green-600"></i>
                                @else
                                    <i class="fas fa-plug text-2xl text-gray-600"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $integration['name'] }}</h3>
                                <p class="text-xs text-gray-500">{{ ucfirst($key) }}</p>
                            </div>
                        </div>
                        @if($integration['premium'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-crown mr-1"></i>
                                Premium
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Gratuit
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        {{ $integration['description'] }}
                    </p>

                    @if($isConfigured)
                        <div class="bg-green-50 border border-green-200 rounded-md p-2 mb-3">
                            <p class="text-xs text-green-700 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>
                                Déjà configuré
                            </p>
                        </div>
                    @endif

                    @if($hasAccess)
                        @if($isConfigured)
                            <a href="{{ route('integrations.edit', $isConfigured) }}"
                               class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier
                            </a>
                        @else
                            <a href="{{ route('integrations.configure', $key) }}"
                               class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Configurer
                            </a>
                        @endif
                    @else
                        <a href="{{ route('plans.index') }}"
                           class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                            <i class="fas fa-crown mr-2"></i>
                            Passer en Premium
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Documentation -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-blue-800">Documentation des intégrations</h3>
                <p class="mt-1 text-sm text-blue-600">
                    Besoin d'aide pour configurer vos intégrations ? Consultez notre documentation ou contactez notre support.
                </p>
                <div class="mt-3 flex space-x-3">
                    <a href="#" class="text-sm text-blue-800 underline hover:text-blue-600">
                        <i class="fas fa-book mr-1"></i>
                        Voir la documentation
                    </a>
                    <a href="{{ route('contact') }}" class="text-sm text-blue-800 underline hover:text-blue-600">
                        <i class="fas fa-headset mr-1"></i>
                        Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de test -->
<div id="testModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modalOverlay"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-vial text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Test d'intégration
                        </h3>
                        <div class="mt-2" id="testResult">
                            <p class="text-sm text-gray-500">Test en cours...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        onclick="closeTestModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testIntegration(id) {
    const modal = document.getElementById('testModal');
    const result = document.getElementById('testResult');

    modal.classList.remove('hidden');
    result.innerHTML = '<p class="text-sm text-gray-500">Test en cours...</p>';

    fetch(`/integrations/${id}/test`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            result.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                    <p class="text-sm text-green-700 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        ${data.message || 'Intégration fonctionnelle'}
                    </p>
                </div>
            `;
        } else {
            result.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-sm text-red-700 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${data.message || 'Erreur de connexion'}
                    </p>
                </div>
            `;
        }
    })
    .catch(error => {
        result.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-3">
                <p class="text-sm text-red-700 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Erreur de connexion
                </p>
            </div>
        `;
    });
}

function closeTestModal() {
    document.getElementById('testModal').classList.add('hidden');
}

// Fermer avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTestModal();
    }
});

// Fermer en cliquant sur l'overlay
document.getElementById('modalOverlay')?.addEventListener('click', function() {
    closeTestModal();
});
</script>
@endpush
@endsection
