@extends('layouts.app')

@section('title', 'Préférences')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Préférences</h1>
        <p class="mt-1 text-sm text-gray-600">
            Personnalisez votre expérience sur la plateforme.
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
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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

    <!-- Formulaire des préférences -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('settings.preferences.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Préférences d'affichage</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Personnalisez l'apparence et le comportement de l'application.
                </p>
            </div>

            <div class="px-6 py-5 space-y-4">
                <!-- Graphiques dashboard -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="dashboard_charts"
                               id="dashboard_charts"
                               value="1"
                               {{ old('dashboard_charts', $preferences['dashboard_charts'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="dashboard_charts" class="font-medium text-gray-700">Afficher les graphiques</label>
                        <p class="text-gray-500">Afficher les graphiques d'activité sur le tableau de bord.</p>
                    </div>
                </div>

                <!-- Notifications email -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="email_notifications"
                               id="email_notifications"
                               value="1"
                               {{ old('email_notifications', $preferences['email_notifications'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="email_notifications" class="font-medium text-gray-700">Notifications par email</label>
                        <p class="text-gray-500">Recevoir des notifications par email pour les événements importants.</p>
                    </div>
                </div>

                <!-- Notifications sonores -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="sound_notifications"
                               id="sound_notifications"
                               value="1"
                               {{ old('sound_notifications', $preferences['sound_notifications'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="sound_notifications" class="font-medium text-gray-700">Notifications sonores</label>
                        <p class="text-gray-500">Jouer un son lors de la réception de nouvelles notifications.</p>
                    </div>
                </div>

                <!-- Mode compact -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="compact_mode"
                               id="compact_mode"
                               value="1"
                               {{ old('compact_mode', $preferences['compact_mode'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="compact_mode" class="font-medium text-gray-700">Mode compact</label>
                        <p class="text-gray-500">Afficher plus d'informations en réduisant les espaces.</p>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les préférences
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
