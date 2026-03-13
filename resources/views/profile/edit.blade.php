@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- En-tête -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center">
                <a href="{{ route('profile.show') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Modifier mon profil
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Mettez à jour vos informations personnelles.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('profile.update') }}" method="POST" class="divide-y divide-gray-200">
            @csrf
            @method('PUT')

            <!-- Informations personnelles -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nom complet -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if(!auth()->user()->email_verified_at)
                            <p class="mt-2 text-sm text-yellow-600">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Email non vérifié.
                                <a href="{{ route('verification.notice') }}" class="text-blue-600 hover:underline">
                                    Renvoyer la vérification
                                </a>
                            </p>
                        @endif
                    </div>

                    <!-- Entreprise -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom de l'entreprise
                        </label>
                        <input type="text"
                               name="company_name"
                               id="company_name"
                               value="{{ old('company_name', auth()->user()->company_name) }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('company_name') border-red-500 @enderror"
                               placeholder="Votre entreprise (optionnel)">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro de téléphone
                        </label>
                        <input type="tel"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', auth()->user()->phone) }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone') border-red-500 @enderror"
                               placeholder="+225 XX XX XX XX">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Langue préférée -->
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">
                            Langue préférée
                        </label>
                        <select name="language" id="language"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="fr" {{ (old('language', auth()->user()->language ?? 'fr') == 'fr') ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ (old('language', auth()->user()->language ?? 'fr') == 'en') ? 'selected' : '' }}>English</option>
                        </select>
                    </div>

                    <!-- Fuseau horaire -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">
                            Fuseau horaire
                        </label>
                        <select name="timezone" id="timezone"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Africa/Abidjan" {{ (old('timezone', auth()->user()->timezone ?? 'Africa/Abidjan') == 'Africa/Abidjan') ? 'selected' : '' }}>Afrique/Abidjan (GMT+0)</option>
                            <option value="Africa/Dakar" {{ (old('timezone', auth()->user()->timezone ?? 'Africa/Abidjan') == 'Africa/Dakar') ? 'selected' : '' }}>Afrique/Dakar (GMT+0)</option>
                            <option value="Africa/Casablanca" {{ (old('timezone', auth()->user()->timezone ?? 'Africa/Abidjan') == 'Africa/Casablanca') ? 'selected' : '' }}>Afrique/Casablanca (GMT+1)</option>
                            <option value="Africa/Johannesburg" {{ (old('timezone', auth()->user()->timezone ?? 'Africa/Abidjan') == 'Africa/Johannesburg') ? 'selected' : '' }}>Afrique/Johannesburg (GMT+2)</option>
                            <option value="Europe/Paris" {{ (old('timezone', auth()->user()->timezone ?? 'Africa/Abidjan') == 'Europe/Paris') ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Préférences de communication -->
            <div class="px-4 py-5 sm:p-6 bg-gray-50">
                <h4 class="text-base font-medium text-gray-900 mb-4">Préférences de communication</h4>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="notify_email_responses"
                               id="notify_email_responses"
                               value="1"
                               {{ old('notify_email_responses', auth()->user()->settings['notify_email_responses'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="notify_email_responses" class="ml-3 text-sm text-gray-700">
                            M'envoyer un email pour chaque nouvelle réponse
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="notify_email_subscription"
                               id="notify_email_subscription"
                               value="1"
                               {{ old('notify_email_subscription', auth()->user()->settings['notify_email_subscription'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="notify_email_subscription" class="ml-3 text-sm text-gray-700">
                            Recevoir des notifications sur mon abonnement
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="notify_newsletter"
                               id="notify_newsletter"
                               value="1"
                               {{ old('notify_newsletter', auth()->user()->settings['notify_newsletter'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="notify_newsletter" class="ml-3 text-sm text-gray-700">
                            Recevoir la newsletter et les actualités
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('profile.show') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Zone de danger (suppression de compte) -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg border border-red-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-red-600 mb-2">Zone de danger</h3>
            <p class="text-sm text-gray-500 mb-4">
                Une fois votre compte supprimé, toutes vos données seront définitivement effacées.
                Cette action est irréversible.
            </p>
            <button type="button"
                    onclick="confirmDelete()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-trash mr-2"></i>
                Supprimer mon compte
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modalOverlay"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Supprimer le compte
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer votre compte ? Toutes vos données
                                (formulaires, réponses, statistiques) seront définitivement effacées.
                            </p>
                        </div>

                        <!-- Formulaire de confirmation avec mot de passe -->
                        <form id="deleteAccountForm" action="{{ route('profile.delete') }}" method="POST" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <div>
                                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirmez avec votre mot de passe
                                </label>
                                <input type="password"
                                       name="password"
                                       id="delete_password"
                                       required
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                       placeholder="Votre mot de passe">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit"
                        form="deleteAccountForm"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Supprimer définitivement
                </button>
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer la modal si on clique sur l'overlay
document.getElementById('modalOverlay')?.addEventListener('click', function() {
    closeDeleteModal();
});

// Fermer la modal avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
