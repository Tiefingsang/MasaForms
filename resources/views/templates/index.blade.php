@extends('layouts.app')

@section('title', 'Templates de formulaires')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Templates de formulaires</h1>
            <p class="mt-2 text-sm text-gray-700">
                Gagnez du temps en utilisant nos templates prêts à l'emploi.
            </p>
        </div>
        @if(auth()->user()->can('create templates'))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('templates.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Nouveau template
            </a>
        </div>
        @endif
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-template text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total templates</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $templates->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-crown text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Premium</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $templates->where('is_premium', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-star text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Gratuits</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $templates->where('is_premium', false)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-chart-line text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Catégories</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $categories->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
        <form method="GET" action="{{ route('templates.index') }}" class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- Recherche -->
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher un template..."
                           class="w-full pl-9 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Filtre par catégorie -->
                <div class="sm:w-48">
                    <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Toutes catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre premium/gratuit -->
                <div class="sm:w-40">
                    <select name="premium" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="0" {{ request('premium') === '0' ? 'selected' : '' }}>Gratuits</option>
                        <option value="1" {{ request('premium') === '1' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>

                <!-- Bouton filtre -->
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>

                <!-- Reset -->
                @if(request()->anyFilled(['search', 'category', 'premium']))
                    <a href="{{ route('templates.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times mr-1"></i>
                        Effacer
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grille des templates -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <!-- Image/Thumbnail -->
                <a href="{{ route('templates.show', $template->slug) }}" class="block">
                    <div class="h-40 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                        @if($template->thumbnail)
                            <img src="{{ Storage::url($template->thumbnail) }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-template text-4xl text-white opacity-50"></i>
                            </div>
                        @endif

                        <!-- Badge Premium -->
                        @if($template->is_premium)
                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-crown mr-1"></i>
                                PREMIUM
                            </div>
                        @else
                            <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                GRATUIT
                            </div>
                        @endif

                        <!-- Badge catégorie -->
                        <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                            {{ $template->category }}
                        </div>
                    </div>
                </a>

                <!-- Contenu -->
                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <a href="{{ route('templates.show', $template->slug) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                            {{ $template->name }}
                        </a>
                        <span class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-download mr-1"></i>
                            {{ $template->usage_count }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ $template->description ?? 'Aucune description' }}
                    </p>

                    <!-- Tags/Features -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if(isset($template->structure['fields']))
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">
                                <i class="fas fa-input mr-1"></i>
                                {{ count($template->structure['fields']) }} champs
                            </span>
                        @endif
                        @if(isset($template->structure['has_file_upload']))
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-100 text-purple-700">
                                <i class="fas fa-upload mr-1"></i>
                                Upload
                            </span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <button onclick="previewTemplate('{{ $template->slug }}')"
                                class="text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-eye mr-1"></i>
                            Aperçu
                        </button>

                        @php
                            $canUse = !$template->is_premium || (auth()->user()->currentPlan()->first()?->has_templates ?? false);
                        @endphp

                        @if($canUse)
                            <form action="{{ route('templates.apply', $template->slug) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                    <i class="fas fa-plus mr-1"></i>
                                    Utiliser
                                </button>
                            </form>
                        @else
                            <a href="{{ route('plans.index') }}"
                               class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 text-sm rounded-md hover:bg-gray-200">
                                <i class="fas fa-lock mr-1"></i>
                                Passer en Premium
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-12 text-center">
                <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-template text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun template trouvé</h3>
                <p class="text-gray-500 mb-4">Essayez de modifier vos filtres de recherche</p>
                <a href="{{ route('templates.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-undo mr-1"></i>
                    Réinitialiser les filtres
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $templates->withQueryString()->links() }}
    </div>
</div>

<!-- Modal d'aperçu -->
<div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modalOverlay"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Aperçu du template
                            </h3>
                            <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="previewContent" class="max-h-96 overflow-y-auto p-4 bg-gray-50 rounded">
                            <!-- Le contenu sera chargé dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        onclick="closePreviewModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewTemplate(slug) {
    fetch(`/templates/${slug}/preview`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('previewContent').innerHTML = html;
            document.getElementById('previewModal').classList.remove('hidden');
        });
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Fermer avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// Fermer en cliquant sur l'overlay
document.getElementById('modalOverlay')?.addEventListener('click', function() {
    closePreviewModal();
});
</script>
@endpush
@endsection
