{{-- resources/views/errors/500.blade.php --}}
@extends('layouts.guest')

@section('title', 'Erreur serveur')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <i class="fas fa-exclamation-triangle text-6xl text-yellow-400 mb-4"></i>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">500 - Erreur serveur</h1>
        <p class="text-gray-600 mb-4">Une erreur inattendue s'est produite.</p>
        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">Retour à l'accueil →</a>
    </div>
</div>
@endsection
