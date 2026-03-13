@extends('layouts.app')

@section('title', 'Paramètres des notifications')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        <p class="mt-1 text-sm text-gray-600">
            Gérez comment et quand vous souhaitez être notifié.
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
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Préférences
            </a>
            <a href="{{ route('settings.notifications') }}"
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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

    <!-- Formulaire des notifications -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('settings.notifications.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Notifications en temps réel</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Configurez les notifications que vous souhaitez recevoir dans l'application.
                </p>
            </div>

            <div class="px-6 py-5 space-y-4">
                <!-- Nouvelles réponses -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="new_response"
                               id="new_response"
                               value="1"
                               {{ old('new_response', $notifications['new_response'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="new_response" class="font-medium text-gray-700">Nouvelles réponses</label>
                        <p class="text-gray-500">Être notifié à chaque nouvelle réponse sur vos formulaires.</p>
                    </div>
                </div>

                <!-- Abonnement bientôt expiré -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="subscription_expiring"
                               id="subscription_expiring"
                               value="1"
                               {{ old('subscription_expiring', $notifications['subscription_expiring'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="subscription_expiring" class="font-medium text-gray-700">Abonnement bientôt expiré</label>
                        <p class="text-gray-500">Recevoir un rappel avant l'expiration de votre abonnement.</p>
                    </div>
                </div>

                <!-- Limite de formulaires -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                               name="form_limit"
                               id="form_limit"
                               value="1"
                               {{ old('form_limit', $notifications['form_limit'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="form_limit" class="font-medium text-gray-700">Limite de formulaires</label>
                        <p class="text-gray-500">Être averti quand vous approchez de la limite de formulaires.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rapports périodiques</h3>

                <div class="space-y-4">
                    <!-- Rapport hebdomadaire -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="weekly_report"
                                   id="weekly_report"
                                   value="1"
                                   {{ old('weekly_report', $notifications['weekly_report'] ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="weekly_report" class="font-medium text-gray-700">Rapport hebdomadaire</label>
                            <p class="text-gray-500">Recevoir un résumé de votre activité chaque semaine.</p>
                        </div>
                    </div>

                    <!-- Rapport mensuel -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   name="monthly_report"
                                   id="monthly_report"
                                   value="1"
                                   {{ old('monthly_report', $notifications['monthly_report'] ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="monthly_report" class="font-medium text-gray-700">Rapport mensuel</label>
                            <p class="text-gray-500">Recevoir un rapport détaillé de votre activité chaque mois.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end">
                <a href="{{ route('settings.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-3">
                    Annuler
                </a>
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
