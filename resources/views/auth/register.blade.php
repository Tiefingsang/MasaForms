@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Créez votre compte</h2>
            <p class="mt-2 text-sm text-gray-600">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Connectez-vous
                </a>
            </p>
        </div>

        <div class="bg-white py-8 px-6 shadow rounded-lg">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Entreprise (optionnel)</label>
                    <div class="mt-1">
                        <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone (optionnel)</label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        J'accepte les <a href="#" class="font-medium text-blue-600 hover:text-blue-500">conditions d'utilisation</a> et la <a href="#" class="font-medium text-blue-600 hover:text-blue-500">politique de confidentialité</a>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Créer mon compte
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <p class="text-xs text-center text-gray-500">
                    En créant un compte, vous bénéficiez automatiquement du plan gratuit avec 3 formulaires et 100 réponses par formulaire.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
