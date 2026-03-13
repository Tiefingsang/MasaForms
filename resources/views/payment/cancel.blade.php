@extends('layouts.app')

@section('title', 'Paiement annulé')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white shadow rounded-lg p-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
            <i class="fas fa-exclamation-triangle text-4xl text-yellow-600"></i>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement annulé</h1>
        <p class="text-gray-600 mb-6">
            Votre paiement a été annulé. Aucun montant n'a été débité.
        </p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">
                Si vous avez rencontré des difficultés, vous pouvez réessayer ou contacter notre support.
            </p>
        </div>

        <div class="space-x-4">
            <a href="{{ route('plans.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-redo mr-2"></i>
                Réessayer
            </a>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-headset mr-2"></i>
                Contacter le support
            </a>
        </div>
    </div>
</div>
@endsection
