{{-- resources/views/payment/select-method.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Paiement sécurisé</h1>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium mb-4">{{ $plan->name }} -
            {{ $interval === 'monthly' ? 'Mensuel' : 'Annuel' }}
        </h2>

        <p class="text-3xl font-bold text-center mb-6">
            {{ number_format($interval === 'monthly' ? $plan->price_monthly : $plan->price_yearly, 0, ',', ' ') }} FCFA
        </p>

        <form action="{{ route('payment.initiate', $plan) }}" method="POST">
            @csrf
            <input type="hidden" name="interval" value="{{ $interval }}">

            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-3">Moyens de paiement acceptés :</p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <img src="{{ asset('images/orange-money.jpg') }}" class="h-8" alt="Orange Money">
                    <img src="{{ asset('images/moov.jpeg') }}" class="h-8" alt="Moov">
                    <img src="{{ asset('images/visa.jpg') }}" class="h-8" alt="Visa">
                    <img src="{{ asset('images/mastercard.jpg') }}" class="h-8" alt="Mastercard">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700">
                Payer avec PayDunya
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 mt-4">
            <i class="fas fa-lock mr-1"></i>
            Paiement sécurisé par PayDunya - Agréé par l'UEMOA
        </p>
    </div>
</div>
@endsection
