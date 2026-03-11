@extends('layouts.app')

@section('title', 'Créer un formulaire')

@section('content')
<div class="max-w-3xl mx-auto" x-data="formCreator()">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Créer un nouveau formulaire</h1>
        <p class="mt-2 text-sm text-gray-600">
            Commencez par donner un titre à votre formulaire. Vous pourrez ajouter des champs ensuite.
        </p>
    </div>

    <!-- Formulaire de base -->
    <form @submit.prevent="createForm" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg p-6">
            <!-- Titre -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Titre du formulaire <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="title"
                       x-model="formData.title"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                       placeholder="Ex: Formulaire de contact, Inscription à l'événement..."
                       required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description (optionnelle)
                </label>
                <textarea id="description"
                          x-model="formData.description"
                          rows="3"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Expliquez le but de ce formulaire..."></textarea>
            </div>

            <!-- Couleurs -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Couleur principale
                    </label>
                    <input type="color"
                           x-model="formData.primary_color"
                           class="block w-full h-10 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Couleur de fond
                    </label>
                    <input type="color"
                           x-model="formData.background_color"
                           class="block w-full h-10 rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('forms.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Annuler
            </a>
            <button type="submit"
                    :disabled="loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="loading ? 'Création en cours...' : 'Créer le formulaire'"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function formCreator() {
    return {
        loading: false,
        formData: {
            title: '',
            description: '',
            primary_color: '#3B82F6',
            background_color: '#FFFFFF',
            settings: {}
        },

        async createForm() {
            this.loading = true;

            try {
                const response = await fetch('{{ route("forms.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Une erreur est survenue');
                }

                if (data.success) {
                    window.location.href = data.redirect;
                }
            } catch (error) {
                alert(error.message);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
