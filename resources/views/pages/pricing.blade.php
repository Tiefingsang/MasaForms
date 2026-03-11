{{-- resources/views/pages/pricing.blade.php --}}
@extends('layouts.guest')

@section('title', 'Tarifs')

@section('content')
<div class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Des tarifs adaptés à vos besoins
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Choisissez le plan qui vous convient. Tous nos plans incluent les fonctionnalités essentielles.
            </p>
        </div>

        <!-- Plans -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden {{ $plan->is_popular ? 'ring-2 ring-blue-500 transform scale-105' : '' }}">
                    @if($plan->is_popular)
                        <div class="bg-blue-500 text-white text-center py-2 text-sm font-medium">
                            Le plus populaire
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
                                    <span class="text-gray-500 ml-2">FCFA/mois</span>
                                @endif
                            </div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>{{ $plan->max_forms === null ? 'Formulaires illimités' : $plan->max_forms . ' formulaires' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <span>{{ $plan->max_responses_per_form === null ? 'Réponses illimitées' : $plan->max_responses_per_form . ' réponses/formulaire' }}</span>
                            </li>
                            @if($plan->has_export_excel)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                    <span>Export Excel</span>
                                </li>
                            @endif
                        </ul>

                        <a href="{{ route('register') }}"
                           class="block w-full text-center py-3 px-4 rounded-lg font-medium transition
                                  {{ $plan->is_popular
                                      ? 'bg-blue-600 text-white hover:bg-blue-700'
                                      : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            {{ $plan->price_monthly > 0 ? 'Commencer' : 'S\'inscrire gratuitement' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
