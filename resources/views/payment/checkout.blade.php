@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Finaliser votre abonnement
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Choisissez votre méthode de paiement pour activer votre abonnement {{ $plan->name }}.
            </p>
        </div>

        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <!-- Récapitulatif -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Récapitulatif</h4>
                <div class="flex justify-between text-sm">
                    <span class="text-blue-700">Plan {{ $plan->name }}</span>
                    <span class="font-medium text-blue-800">{{ number_format($plan->price_monthly, 0, ',', ' ') }} FCFA/mois</span>
                </div>
                <div class="border-t border-blue-200 mt-2 pt-2 flex justify-between text-sm">
                    <span class="font-medium text-blue-800">Total à payer</span>
                    <span class="font-bold text-blue-800">{{ number_format($plan->price_monthly, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <!-- Méthodes de paiement -->
            <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">

                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-700">Choisissez votre moyen de paiement</h4>

                    <!-- Orange Money -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_provider" value="orange_money" class="h-4 w-4 text-blue-600" required>
                        <input type="hidden" name="payment_method" value="mobile_money">
                        <div class="ml-4 flex items-center">
                            <img src="{{ asset('images/orange-money.png') }}" alt="Orange Money" class="h-8 w-auto">
                            <span class="ml-3 text-sm font-medium text-gray-700">Orange Money</span>
                        </div>
                    </label>

                    <!-- Moov Money -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_provider" value="moov" class="h-4 w-4 text-blue-600">
                        <input type="hidden" name="payment_method" value="mobile_money">
                        <div class="ml-4 flex items-center">
                            <img src="{{ asset('images/moov.png') }}" alt="Moov Money" class="h-8 w-auto">
                            <span class="ml-3 text-sm font-medium text-gray-700">Moov Money</span>
                        </div>
                    </label>

                    <!-- Wave -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_provider" value="wave" class="h-4 w-4 text-blue-600">
                        <input type="hidden" name="payment_method" value="mobile_money">
                        <div class="ml-4 flex items-center">
                            <img src="{{ asset('images/wave.png') }}" alt="Wave" class="h-8 w-auto">
                            <span class="ml-3 text-sm font-medium text-gray-700">Wave</span>
                        </div>
                    </label>

                    <!-- Carte bancaire -->
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_provider" value="card" class="h-4 w-4 text-blue-600">
                        <input type="hidden" name="payment_method" value="card">
                        <div class="ml-4 flex items-center">
                            <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                            <span class="ml-3 text-sm font-medium text-gray-700">Carte bancaire (Visa/Mastercard)</span>
                        </div>
                    </label>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('plans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour aux plans
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-lock mr-2"></i>
                        Payer {{ number_format($plan->price_monthly, 0, ',', ' ') }} FCFA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sécurité -->
    <div class="mt-4 text-center text-sm text-gray-500">
        <i class="fas fa-shield-alt mr-1"></i>
        Paiement 100% sécurisé
        <span class="mx-2">•</span>
        <i class="fas fa-clock mr-1"></i>
        Transaction instantanée
    </div>
</div>
@endsection
