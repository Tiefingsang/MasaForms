{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.guest')

@section('title', 'Vérification email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Masadigitale Forms" class="h-12 mx-auto">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Vérifiez votre email
            </h2>
        </div>

        <div class="bg-white py-8 px-6 shadow rounded-lg">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-center">
                <!-- Icône -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-envelope-open-text text-3xl text-blue-600"></i>
                </div>

                <!-- Message -->
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Vérifiez votre adresse email
                </h3>

                <p class="text-gray-600 mb-6">
                    Nous avons envoyé un lien de vérification à l'adresse :
                    <br>
                    <span class="font-medium text-blue-600">{{ auth()->user()->email }}</span>
                </p>

                <p class="text-sm text-gray-500 mb-6">
                    Cliquez sur le lien dans l'email pour vérifier votre compte.
                    Si vous n'avez pas reçu l'email, vérifiez vos spams ou demandez un nouveau lien.
                </p>

                <!-- Actions -->
                <div class="space-y-3">
                    <form action="{{ route('verification.resend') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-redo-alt mr-2"></i>
                            Renvoyer le lien de vérification
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aide -->
        <p class="mt-4 text-center text-xs text-gray-500">
            Vous pouvez fermer cette page et revenir plus tard.
        </p>
    </div>
</div>
@endsection
