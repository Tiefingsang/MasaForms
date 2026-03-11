@extends('layouts.app')

@section('title', 'Paramètres - ' . $form->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('forms.edit', $form) }}" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Paramètres du formulaire</h1>
        </div>
        <p class="mt-2 text-sm text-gray-600">
            Configurez les options avancées de votre formulaire "{{ $form->title }}"
        </p>
    </div>

    <form method="POST" action="{{ route('forms.settings.update', $form) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Paramètres généraux -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Paramètres généraux</h2>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Statut du formulaire</label>
                        <p class="text-xs text-gray-500">Activer ou désactiver la réception des réponses</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" {{ $form->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Accepter les réponses</label>
                        <p class="text-xs text-gray-500">Permettre aux utilisateurs de soumettre des réponses</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="accepts_responses" class="sr-only peer" {{ $form->accepts_responses ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Formulaire public</label>
                        <p class="text-xs text-gray-500">Rendre le formulaire accessible sans authentification</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_public" class="sr-only peer" {{ $form->is_public ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Barre de progression</label>
                        <p class="text-xs text-gray-500">Afficher une barre de progression aux répondants</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_progress_bar" class="sr-only peer" {{ $form->show_progress_bar ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-sm font-medium text-gray-700">CAPTCHA</label>
                        <p class="text-xs text-gray-500">Protéger le formulaire contre les robots</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="captcha_enabled" class="sr-only peer" {{ $form->captcha_enabled ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Limites et contraintes -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Limites et contraintes</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre maximum de réponses</label>
                    <p class="text-xs text-gray-500 mb-2">Laissez vide pour illimité</p>
                    <input type="number" name="max_responses" value="{{ $form->max_responses }}"
                           class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de début</label>
                        <p class="text-xs text-gray-500 mb-2">À partir de quand le formulaire est accessible</p>
                        <input type="datetime-local" name="start_date" value="{{ $form->start_date ? $form->start_date->format('Y-m-d\TH:i') : '' }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <p class="text-xs text-gray-500 mb-2">Jusqu'à quand le formulaire est accessible</p>
                        <input type="datetime-local" name="end_date" value="{{ $form->end_date ? $form->end_date->format('Y-m-d\TH:i') : '' }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe (optionnel)</label>
                    <p class="text-xs text-gray-500 mb-2">Protéger le formulaire par un mot de passe</p>
                    <input type="text" name="password" value="{{ $form->password }}"
                           class="block w-full max-w-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
            </div>
        </div>

        <!-- Message de remerciement -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Message de remerciement</h2>

            <div>
                <textarea name="thank_you_message" rows="3"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                          placeholder="Merci d'avoir répondu à ce formulaire !">{{ $form->thank_you_message }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Ce message s'affichera après la soumission du formulaire</p>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Notifications</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email de notification</label>
                    <p class="text-xs text-gray-500 mb-2">Recevoir un email à chaque nouvelle réponse</p>
                    <input type="email" name="notification_email" value="{{ $form->settings['notification_email'] ?? '' }}"
                           class="block w-full max-w-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="email@exemple.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Webhook URL</label>
                    <p class="text-xs text-gray-500 mb-2">Envoyer les données à une URL externe</p>
                    <input type="url" name="webhook_url" value="{{ $form->settings['webhook_url'] ?? '' }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="https://exemple.com/webhook">
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('forms.edit', $form) }}"
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Enregistrer les paramètres
            </button>
        </div>
    </form>

    <!-- Zone dangereuse -->
    <div class="mt-8 bg-red-50 shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-red-800 mb-4">Zone dangereuse</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="text-sm font-medium text-red-700">Supprimer le formulaire</label>
                    <p class="text-xs text-red-600">Cette action est irréversible. Toutes les données seront perdues.</p>
                </div>
                <form method="POST" action="{{ route('forms.destroy', $form) }}"
                      onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer ce formulaire ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
