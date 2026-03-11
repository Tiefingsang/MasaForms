@extends('layouts.guest')

@section('title', 'Tarifs et abonnements')

@section('content')
<div class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Des tarifs adaptés à vos besoins
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Choisissez le plan qui vous convient. Tous nos plans incluent les fonctionnalités essentielles pour créer et gérer vos formulaires.
            </p>
        </div>

        <!-- Plans -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
            @foreach($plans as $plan)
                <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden
                            {{ $plan->is_popular ? 'ring-2 ring-blue-500 transform scale-105 z-10' : '' }}">

                    @if($plan->is_popular)
                        <div class="absolute top-0 right-0 bg-blue-500 text-white px-4 py-1 text-sm font-medium rounded-bl-lg">
                            Populaire
                        </div>
                    @endif

                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-500 mb-6">{{ $plan->description }}</p>

                        <div class="mb-6">
                            <div class="flex items-baseline">
                                <span class="text-4xl font-extrabold text-gray-900">
                                    {{ $plan->price_monthly > 0 ? number_format($plan->price_monthly, 0, ',', ' ') : 'Gratuit' }}
                                </span>
                                @if($plan->price_monthly > 0)
                                    <span class="text-gray-500 ml-2">/mois</span>
                                @endif
                            </div>
                            @if($plan->price_yearly > 0)
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ number_format($plan->price_yearly, 0, ',', ' ') }} FCFA / an
                                    (économie de {{ number_format(($plan->price_monthly * 12) - $plan->price_yearly, 0, ',', ' ') }} FCFA)
                                </p>
                            @endif
                        </div>

                        <!-- Fonctionnalités -->
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">
                                    {{ $plan->max_forms === null ? 'Formulaires illimités' : $plan->max_forms . ' formulaires' }}
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">
                                    {{ $plan->max_responses_per_form === null ? 'Réponses illimitées' : $plan->max_responses_per_form . ' réponses/formulaire' }}
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">
                                    Export {{ $plan->has_export_excel ? 'CSV et Excel' : 'CSV' }}
                                </span>
                            </li>
                            @if($plan->has_advanced_stats)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Statistiques avancées</span>
                                </li>
                            @endif
                            @if($plan->has_custom_logo)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Logo personnalisé</span>
                                </li>
                            @endif
                            @if($plan->has_remove_masadigitale_logo)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Sans logo Masadigitale</span>
                                </li>
                            @endif
                            @if($plan->has_whatsapp_integration)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Intégration WhatsApp</span>
                                </li>
                            @endif
                            @if($plan->has_email_notifications)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Notifications email</span>
                                </li>
                            @endif
                            @if($plan->has_api_access)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span class="text-gray-600">Accès API</span>
                                </li>
                            @endif
                        </ul>

                        <!-- Bouton -->
                        @auth
                            @if($currentPlan && $currentPlan->id === $plan->id)
                                <button disabled
                                        class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg font-medium cursor-not-allowed">
                                    Plan actuel
                                </button>
                            @else
                                <a href="{{ route('plans.subscribe', $plan->slug) }}"
                                   class="block w-full text-center bg-{{ $plan->is_popular ? 'blue' : 'gray' }}-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-{{ $plan->is_popular ? 'blue' : 'gray' }}-700 transition duration-200">
                                    {{ $plan->price_monthly > 0 ? 'Choisir ce plan' : 'Commencer gratuitement' }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                               class="block w-full text-center bg-{{ $plan->is_popular ? 'blue' : 'gray' }}-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-{{ $plan->is_popular ? 'blue' : 'gray' }}-700 transition duration-200">
                                {{ $plan->price_monthly > 0 ? 'S\'inscrire' : 'Commencer gratuitement' }}
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FAQ ou garantie -->
        <div class="mt-20 text-center">
            <div class="inline-flex items-center justify-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    Paiement sécurisé
                </div>
                <div class="flex items-center">
                    <i class="fas fa-undo mr-2"></i>
                    Annulation à tout moment
                </div>
                <div class="flex items-center">
                    <i class="fas fa-headset mr-2"></i>
                    Support 24/7
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
