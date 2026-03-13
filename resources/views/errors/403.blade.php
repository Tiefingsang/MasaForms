{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.guest')

@section('title', 'Accès interdit')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <i class="fas fa-lock text-6xl text-red-400 mb-4"></i>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">403 - Accès interdit</h1>
        <p class="text-gray-600 mb-4">Vous n'avez pas les permissions nécessaires.</p>
        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">Retour à l'accueil →</a>
    </div>
</div>
@endsection
