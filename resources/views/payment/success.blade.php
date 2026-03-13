@extends('layouts.app')

@section('title', 'Paiement réussi')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white shadow rounded-lg p-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check-circle text-4xl text-green-600"></i>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement réussi !</h1>
        <p class="text-gray-600 mb-6">
            Votre abonnement a été activé avec succès. Vous avez maintenant accès à toutes les fonctionnalités de votre plan.
        </p>

        <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Ce qui va se passer maintenant :</h3>
            <ul class="text-sm text-blue-700 space-y-2">
                <li><i class="fas fa-check mr-2"></i> Votre abonnement est actif immédiatement</li>
                <li><i class="fas fa-check mr-2"></i> Vous recevrez un email de confirmation</li>
                <li><i class="fas fa-check mr-2"></i> Vous pouvez commencer à utiliser toutes les fonctionnalités</li>
            </ul>
        </div>

        <div class="space-x-4">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-home mr-2"></i>
                Aller au tableau de bord
            </a>
            <a href="{{ route('forms.create') }}"
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-plus mr-2"></i>
                Créer un formulaire
            </a>
        </div>
    </div>
</div>
@endsection
