@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Paramètres</h1>
        <p class="mt-1 text-sm text-gray-600">
            Gérez vos préférences et configurez votre compte.
        </p>
    </div>

    <!-- Navigation des onglets -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('settings.index') }}"
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                API
            </a>
            <a href="{{ route('settings.billing') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Facturation
            </a>
        </nav>
    </div>

    <!-- Formulaire des paramètres généraux -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Paramètres généraux</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Configurez vos préférences générales pour l'application.
                </p>
            </div>

            <div class="px-6 py-5 space-y-6">
                <!-- Fuseau horaire -->
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">
                        Fuseau horaire
                    </label>
                    <select name="timezone" id="timezone"
                            class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Africa/Abidjan" {{ old('timezone', $settings['timezone'] ?? 'Africa/Abidjan') == 'Africa/Abidjan' ? 'selected' : '' }}>Afrique/Abidjan (GMT+0)</option>
                        <option value="Africa/Dakar" {{ old('timezone', $settings['timezone'] ?? '') == 'Africa/Dakar' ? 'selected' : '' }}>Afrique/Dakar (GMT+0)</option>
                        <option value="Africa/Casablanca" {{ old('timezone', $settings['timezone'] ?? '') == 'Africa/Casablanca' ? 'selected' : '' }}>Afrique/Casablanca (GMT+1)</option>
                        <option value="Africa/Johannesburg" {{ old('timezone', $settings['timezone'] ?? '') == 'Africa/Johannesburg' ? 'selected' : '' }}>Afrique/Johannesburg (GMT+2)</option>
                        <option value="Europe/Paris" {{ old('timezone', $settings['timezone'] ?? '') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Définit le fuseau horaire pour les dates et heures affichées.</p>
                </div>

                <!-- Langue -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-1">
                        Langue de l'interface
                    </label>
                    <select name="language" id="language"
                            class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="fr" {{ old('language', $settings['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ old('language', $settings['language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <!-- Format de date -->
                <div>
                    <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">
                        Format de date
                    </label>
                    <select name="date_format" id="date_format"
                            class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>31/12/2024</option>
                        <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>2024-12-31</option>
                        <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>12/31/2024</option>
                    </select>
                </div>

                <!-- Éléments par page -->
                <div>
                    <label for="items_per_page" class="block text-sm font-medium text-gray-700 mb-1">
                        Éléments par page
                    </label>
                    <select name="items_per_page" id="items_per_page"
                            class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="10" {{ old('items_per_page', $settings['items_per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ old('items_per_page', $settings['items_per_page'] ?? '') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ old('items_per_page', $settings['items_per_page'] ?? '') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ old('items_per_page', $settings['items_per_page'] ?? '') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Nombre d'éléments affichés par page dans les listes.</p>
                </div>
            </div>

            <!-- Boutons -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
