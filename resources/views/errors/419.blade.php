{{-- resources/views/errors/419.blade.php --}}
@extends('layouts.guest')

@section('title', 'Session expirée')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <i class="fas fa-clock text-6xl text-orange-400 mb-4"></i>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">419 - Session expirée</h1>
        <p class="text-gray-600 mb-4">Votre session a expiré. Veuillez vous reconnecter.</p>
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Se reconnecter →</a>
    </div>
</div>
@endsection
