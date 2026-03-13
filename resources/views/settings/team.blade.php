@extends('layouts.app')

@section('title', 'Gestion de l\'équipe')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Gestion de l'équipe</h1>
        <p class="mt-1 text-sm text-gray-600">
            Invitez des collaborateurs et gérez leurs accès.
        </p>
    </div>

    <!-- Navigation des onglets -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('settings.index') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Général
            </a>
            <a href="{{ route('settings.preferences') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Préférences
            </a>
            <a href="{{ route('settings.notifications') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notifications
            </a>
            <a href="{{ route('settings.team') }}"
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Équipe
            </a>
            <a href="{{ route('settings.api') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                API
            </a>
            <a href="{{ route('settings.billing') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Facturation
            </a>
        </nav>
    </div>

    @php
        $plan = auth()->user()->currentPlan()->first();
        $hasTeamAccess = $plan && $plan->has_multi_users;
    @endphp

    @if(!$hasTeamAccess)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        La gestion d'équipe est disponible uniquement sur le plan Business.
                        <a href="{{ route('plans.index') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                            Passez au plan Business
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Invitation -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Inviter un membre</h3>
            <p class="mt-1 text-sm text-gray-500">
                Envoyez une invitation à un collaborateur pour rejoindre votre équipe.
            </p>
        </div>

        <form action="{{ route('settings.team.invite') }}" method="POST" class="px-6 py-5">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           placeholder="email@exemple.com"
                           {{ !$hasTeamAccess ? 'disabled' : '' }}
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ !$hasTeamAccess ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                </div>
                <div>
                    <label for="role" class="sr-only">Rôle</label>
                    <select name="role"
                            id="role"
                            {{ !$hasTeamAccess ? 'disabled' : '' }}
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ !$hasTeamAccess ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                        <option value="admin">Admin</option>
                        <option value="editor">Éditeur</option>
                        <option value="viewer">Lecteur</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                        {{ !$hasTeamAccess ? 'disabled' : '' }}
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ !$hasTeamAccess ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer l'invitation
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des membres -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Membres de l'équipe</h3>
            <p class="mt-1 text-sm text-gray-500">
                Gérez les accès des membres de votre équipe.
            </p>
        </div>

        <div class="divide-y divide-gray-200">
            <!-- Propriétaire -->
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            Propriétaire
                        </span>
                    </div>
                </div>
            </div>

            <!-- Autres membres (exemple) -->
            @forelse($teamMembers ?? [] as $member)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <select class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="admin" {{ $member->pivot->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ $member->pivot->role == 'editor' ? 'selected' : '' }}>Éditeur</option>
                                <option value="viewer" {{ $member->pivot->role == 'viewer' ? 'selected' : '' }}>Lecteur</option>
                            </select>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p>Aucun autre membre dans l'équipe</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
