@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- En-tête -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Mon profil
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Gérez vos informations personnelles et vos préférences.
            </p>
        </div>

        <!-- Avatar -->
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    @if(auth()->user()->avatar)
                        <img class="h-20 w-20 rounded-full object-cover"
                             src="{{ Storage::url(auth()->user()->avatar) }}"
                             alt="{{ auth()->user()->name }}">
                    @else
                        <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <form action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <label for="avatar" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-camera mr-2"></i>
                            Changer la photo
                        </label>
                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                    </form>
                    @if(auth()->user()->avatar)
                        <form action="{{ route('profile.avatar.remove') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-2 text-sm text-red-600 hover:text-red-900">
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ auth()->user()->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ auth()->user()->email }}
                        @if(!auth()->user()->email_verified_at)
                            <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Non vérifié</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ auth()->user()->company_name ?? 'Non renseigné' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ auth()->user()->phone ?? 'Non renseigné' }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Membre depuis</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ auth()->user()->created_at->format('d/m/Y') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Plan actuel</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="font-medium">{{ auth()->user()->currentPlan()->first()?->name ?? 'Gratuit' }}</span>
                        <a href="{{ route('plans.index') }}" class="ml-4 text-blue-600 hover:text-blue-900">
                            Changer de plan
                        </a>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-end space-x-3">
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>
                Modifier le profil
            </a>
            <a href="{{ route('profile.password') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-key mr-2"></i>
                Changer le mot de passe
            </a>
        </div>
    </div>
</div>
@endsection
