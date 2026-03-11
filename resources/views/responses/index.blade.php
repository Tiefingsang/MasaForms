@extends('layouts.app')

@section('title', 'Réponses - ' . $form->title)

@section('content')
<div class="space-y-6" x-data="responseManager()" x-init="init({{ $form->id }})">
    <!-- En-tête -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.edit', $form) }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Réponses - {{ $form->title }}</h1>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                {{ $responses->total() }} réponse(s) reçue(s) au total
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <!-- Export dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-download mr-2"></i>
                    Exporter
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                    <div class="py-1">
                        <a href="{{ route('forms.responses.export.csv', $form) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-csv mr-2"></i>
                            Format CSV
                        </a>
                        <a href="{{ route('forms.responses.export.excel', $form) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-excel mr-2"></i>
                            Format Excel
                        </a>
                        <a href="{{ route('forms.responses.export.pdf', $form) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Format PDF
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('forms.responses.statistics', $form) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-chart-pie mr-2"></i>
                Statistiques
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('forms.responses.index', $form) }}" class="flex items-center space-x-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par email ou nom..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div>
                <select name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Toutes les dates</option>
                    <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Ce mois</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-search mr-2"></i>
                Filtrer
            </button>
            @if(request()->has('search') || request()->has('date'))
                <a href="{{ route('forms.responses.index', $form) }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>
                    Effacer les filtres
                </a>
            @endif
        </form>
    </div>

    <!-- Actions groupées -->
    <div x-show="selectedResponses.length > 0" class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
        <div class="text-sm text-blue-700">
            <span class="font-medium" x-text="selectedResponses.length"></span> réponse(s) sélectionnée(s)
        </div>
        <div class="flex items-center space-x-3">
            <button @click="exportSelected()" class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50">
                <i class="fas fa-download mr-2"></i>
                Exporter la sélection
            </button>
            <button @click="deleteSelected()" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                <i class="fas fa-trash mr-2"></i>
                Supprimer la sélection
            </button>
            <button @click="clearSelection()" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-times mr-1"></i>
                Annuler
            </button>
        </div>
    </div>

    <!-- Tableau des réponses -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                            <input type="checkbox" @click="toggleAll()" :checked="selectedAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Répondant
                        </th>
                        @foreach($form->fields as $field)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $field->label }}
                            </th>
                        @endforeach
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($responses as $response)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" value="{{ $response->id }}"
                                       x-model="selectedResponses"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $response->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $response->respondent_name ?? 'Anonyme' }}
                                </div>
                                @if($response->respondent_email)
                                    <div class="text-sm text-gray-500">
                                        {{ $response->respondent_email }}
                                    </div>
                                @endif
                            </td>
                            @foreach($form->fields as $field)
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    @php
                                        $value = $response->values->where('form_field_id', $field->id)->first();
                                    @endphp
                                    @if($value)
                                        @if($field->type == 'checkbox' && is_array(json_decode($value->value, true)))
                                            {{ implode(', ', json_decode($value->value, true)) }}
                                        @elseif($field->type == 'file')
                                            <a href="{{ Storage::url($value->value) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-file"></i> Voir
                                            </a>
                                        @else
                                            {{ Str::limit($value->value, 50) }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('forms.responses.show', [$form, $response]) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('forms.responses.destroy', [$form, $response]) }}"
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + $form->fields->count() }}" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Aucune réponse pour le moment</p>
                                <p class="text-sm text-gray-400 mt-1">Partagez votre formulaire pour commencer à collecter des réponses</p>
                                <a href="{{ route('forms.share', $form) }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-share-alt mr-2"></i>
                                    Partager le formulaire
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $responses->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script>
function responseManager() {
    return {
        formId: null,
        selectedResponses: [],

        init(formId) {
            this.formId = formId;
        },

        get selectedAll() {
            return this.selectedResponses.length === {{ $responses->total() }};
        },

        toggleAll() {
            if (this.selectedAll) {
                this.selectedResponses = [];
            } else {
                this.selectedResponses = {{ $responses->pluck('id') }};
            }
        },

        clearSelection() {
            this.selectedResponses = [];
        },

        async deleteSelected() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer les réponses sélectionnées ?')) {
                return;
            }

            try {
                const response = await fetch(`/forms/${this.formId}/responses/bulk-delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        responses: this.selectedResponses
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                }
            } catch (error) {
                alert('Erreur lors de la suppression');
            }
        },

        async exportSelected() {
            const response = await fetch(`/forms/${this.formId}/responses/bulk-export`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    responses: this.selectedResponses
                })
            });

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'reponses-selectionnees.csv';
            a.click();
        }
    }
}
</script>
@endpush
@endsection
