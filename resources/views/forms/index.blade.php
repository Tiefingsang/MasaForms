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
            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Nouveau formulaire
            </a>
        </div>
    </div>

    <!-- Statistiques compactes -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-wpforms text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $forms->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-reply text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Réponses</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $forms->sum('responses_count') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Moyenne</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $forms->avg('responses_count') ? number_format($forms->avg('responses_count'), 0) : 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche compacte -->
    <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
        <form method="GET" action="{{ route('forms.index') }}" class="flex items-center space-x-2">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher un formulaire..."
                       class="w-full pl-9 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <select name="status" class="w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Tous</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm border border-gray-300">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Liste des formulaires en mode tableau -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Réponses</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Limite</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($forms as $form)
                <tr class="hover:bg-gray-50">
                    <!-- Statut -->
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $form->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                            {{ $form->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        @if(!$form->is_public)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-lock mr-1"></i> Privé
                            </span>
                        @endif
                    </td>

                    <!-- Titre -->
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <i class="fas fa-wpforms text-gray-400 mr-2"></i>
                            <a href="{{ route('forms.edit', $form) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                {{ Str::limit($form->title, 30) }}
                            </a>
                        </div>
                    </td>

                    <!-- Date -->
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ $form->created_at->format('d/m/Y') }}
                    </td>

                    <!-- Réponses -->
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $form->responses_count }}
                        </span>
                    </td>

                    <!-- Limite -->
                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-500">
                        @if($form->max_responses)
                            {{ $form->max_responses }}
                        @else
                            <span class="text-gray-400">∞</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- Lien public -->
                            <a href="{{ $form->public_url }}" target="_blank"
                               class="p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition"
                               title="Voir le formulaire public">
                                <i class="fas fa-external-link-alt"></i>
                            </a>

                            <!-- Réponses -->
                            <a href="{{ route('forms.responses.index', $form) }}"
                               class="p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition"
                               title="Voir les réponses">
                                <i class="fas fa-chart-bar"></i>
                            </a>

                            <!-- Modifier -->
                            <a href="{{ route('forms.edit', $form) }}"
                               class="p-1 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Menu déroulant complet -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        @click.away="open = false"
                                        class="p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition"
                                        title="Plus d'actions">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <!-- Dropdown avec TOUTES les actions -->
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">

                                    <div class="py-1">
                                        <!-- Paramètres -->
                                        <a href="{{ route('forms.settings', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog w-5 text-gray-500 mr-3"></i>
                                            Paramètres
                                        </a>

                                        <!-- Design -->
                                        <a href="{{ route('forms.design', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-paint-brush w-5 text-gray-500 mr-3"></i>
                                            Design
                                        </a>

                                        <!-- Partager -->
                                        <a href="{{ route('forms.share', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-share-alt w-5 text-gray-500 mr-3"></i>
                                            Partager
                                        </a>

                                        <!-- Dupliquer -->
                                        <form method="POST" action="{{ route('forms.duplicate', $form) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-copy w-5 text-gray-500 mr-3"></i>
                                                Dupliquer
                                            </button>
                                        </form>

                                        <!-- Statistiques -->
                                        <a href="{{ route('forms.statistics', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-chart-pie w-5 text-gray-500 mr-3"></i>
                                            Statistiques
                                        </a>

                                        <!-- Exporter CSV -->
                                        <a href="{{ route('forms.responses.export.csv', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-csv w-5 text-gray-500 mr-3"></i>
                                            Exporter CSV
                                        </a>

                                        <!-- Exporter Excel -->
                                        <a href="{{ route('forms.responses.export.excel', $form) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-excel w-5 text-gray-500 mr-3"></i>
                                            Exporter Excel
                                        </a>

                                        <!-- Ligne de séparation -->
                                        <div class="border-t border-gray-100 my-1"></div>

                                        <!-- Archiver (soft delete) -->
                                        @if(!$form->trashed())
                                            <form method="POST" action="{{ route('forms.destroy', $form) }}"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir archiver ce formulaire ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-orange-600 hover:bg-gray-100">
                                                    <i class="fas fa-archive w-5 text-orange-500 mr-3"></i>
                                                    Archiver
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Restaurer (si dans corbeille) -->
                                        @if($form->trashed())
                                            <form method="POST" action="{{ route('forms.restore', $form) }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                                    <i class="fas fa-undo w-5 text-green-500 mr-3"></i>
                                                    Restaurer
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Supprimer définitivement -->
                                        @if($form->trashed())
                                            <form method="POST" action="{{ route('forms.force-delete', $form) }}"
                                                  onsubmit="return confirm('Suppression définitive ? Cette action est irréversible.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    <i class="fas fa-trash w-5 text-red-500 mr-3"></i>
                                                    Supprimer définitivement
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-wpforms text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Aucun formulaire</h3>
                        <p class="text-sm text-gray-500 mb-3">Commencez par créer votre premier formulaire</p>
                        <a href="{{ route('forms.create') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i>
                            Créer
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination compacte -->
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Page {{ $forms->currentPage() }} sur {{ $forms->lastPage() }}
        </div>
        <div class="flex space-x-2">
            @if($forms->previousPageUrl())
                <a href="{{ $forms->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif
            @if($forms->nextPageUrl())
                <a href="{{ $forms->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<style>
/* Ajustements pour que tout tienne sur une ligne */
@media (min-width: 1024px) {
    .table-auto {
        table-layout: auto;
    }
    .whitespace-nowrap {
        white-space: nowrap;
    }
}
</style>
@endsection
