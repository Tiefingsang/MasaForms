@extends('layouts.app')

@section('title', 'Mes formulaires')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes formulaires</h1>
            <p class="mt-2 text-sm text-gray-700">
                Gérez tous vos formulaires et accédez à leurs réponses.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2"></i>
                Nouveau formulaire
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total formulaires</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $forms->total() }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total réponses</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $forms->sum('responses_count') }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Taux de réponse moyen</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ $forms->avg('responses_count') ? number_format($forms->avg('responses_count'), 0) : 0 }}
                </dd>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('forms.index') }}" class="flex items-center space-x-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un formulaire..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-search mr-2"></i>
                Filtrer
            </button>
        </form>
    </div>

    <!-- Liste des formulaires -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($forms as $form)
                <li>
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <!-- Status indicator -->
                                <div class="flex-shrink-0 mr-3">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full {{ $form->is_active ? 'bg-green-400' : 'bg-gray-300' }}"></span>
                                </div>

                                <!-- Info principale -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center">
                                        <a href="{{ route('forms.edit', $form) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900 truncate">
                                            {{ $form->title }}
                                        </a>
                                        @if(!$form->is_public)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-lock mr-1"></i> Privé
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex items-center text-xs text-gray-500">
                                        <span>Créé le {{ $form->created_at->format('d/m/Y') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $form->responses_count }} réponse(s)</span>
                                        @if($form->max_responses)
                                            <span class="mx-2">•</span>
                                            <span>Max: {{ $form->max_responses }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Stats -->
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium text-gray-900">{{ $form->current_responses }}</span>
                                    <span>/</span>
                                    <span>{{ $form->max_responses ?? '∞' }}</span>
                                </div>

                                <!-- Lien public -->
                                <a href="{{ $form->public_url }}" target="_blank"
                                   class="text-gray-400 hover:text-gray-600" title="Voir le formulaire public">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>

                                <!-- Réponses -->
                                <a href="{{ route('forms.responses.index', $form) }}"
                                   class="text-gray-400 hover:text-gray-600" title="Voir les réponses">
                                    <i class="fas fa-chart-bar"></i>
                                </a>

                                <!-- Menu d'actions -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false"
                                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                         x-transition>
                                        <div class="py-1">
                                            <a href="{{ route('forms.edit', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-edit mr-2"></i> Modifier
                                            </a>
                                            <a href="{{ route('forms.settings', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-cog mr-2"></i> Paramètres
                                            </a>
                                            <a href="{{ route('forms.design', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-paint-brush mr-2"></i> Design
                                            </a>
                                            <a href="{{ route('forms.share', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-share-alt mr-2"></i> Partager
                                            </a>
                                            <form method="POST" action="{{ route('forms.duplicate', $form) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-copy mr-2"></i> Dupliquer
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('forms.destroy', $form) }}"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formulaire ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-12 text-center">
                    <i class="fas fa-wpforms text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun formulaire</h3>
                    <p class="text-gray-500 mb-4">Commencez par créer votre premier formulaire</p>
                    <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Créer un formulaire
                    </a>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $forms->links() }}
    </div>
</div>
@endsection
