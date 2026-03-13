<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masadigitale Forms') - {{ config('app.name') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Styles personnalisés -->
    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full" x-data="{ sidebarOpen: false }">
        <!-- Navigation mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-cloak>
            <div class="fixed inset-0 bg-gray-900/80"></div>
            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">

                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center">
                            <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Masadigitale">
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        <x-sidebar-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                            <i class="fas fa-home w-6"></i>
                                            Tableau de bord
                                        </x-sidebar-item>
                                        <x-sidebar-item :href="route('forms.index')" :active="request()->routeIs('forms.*')">
                                            <i class="fas fa-wpforms w-6"></i>
                                            Formulaires
                                        </x-sidebar-item>
                                        <x-sidebar-item :href="route('templates.index')" :active="request()->routeIs('templates.*')">
                                            <i class="fas fa-template w-6"></i>
                                            Templates
                                        </x-sidebar-item>
                                        <x-sidebar-item :href="route('integrations.index')" :active="request()->routeIs('integrations.*')">
                                            <i class="fas fa-plug w-6"></i>
                                            Intégrations
                                        </x-sidebar-item>
                                    </ul>
                                </li>
                                <li class="mt-auto">
                                    <ul role="list" class="-mx-2 space-y-1">
                                        <x-sidebar-item :href="route('plans.index')" :active="request()->routeIs('plans.*')">
                                            <i class="fas fa-crown w-6"></i>
                                            Abonnement
                                        </x-sidebar-item>
                                        <x-sidebar-item :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                                            <i class="fas fa-cog w-6"></i>
                                            Paramètres
                                        </x-sidebar-item>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Masadigitale">
                    <span class="ml-2 text-xl font-bold text-gray-900">Forms</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <x-sidebar-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    <i class="fas fa-home w-6"></i>
                                    Tableau de bord
                                </x-sidebar-item>
                                <x-sidebar-item :href="route('forms.index')" :active="request()->routeIs('forms.*')">
                                    <i class="fas fa-wpforms w-6"></i>
                                    Formulaires
                                </x-sidebar-item>
                                <x-sidebar-item :href="route('templates.index')" :active="request()->routeIs('templates.*')">
                                    <i class="fas fa-template w-6"></i>
                                    Templates
                                </x-sidebar-item>
                                <x-sidebar-item :href="route('integrations.index')" :active="request()->routeIs('integrations.*')">
                                    <i class="fas fa-plug w-6"></i>
                                    Intégrations
                                </x-sidebar-item>
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <ul role="list" class="-mx-2 space-y-1">
                                <x-sidebar-item :href="route('plans.index')" :active="request()->routeIs('plans.*')">
                                    <i class="fas fa-crown w-6"></i>
                                    Abonnement
                                </x-sidebar-item>
                                <x-sidebar-item :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                                    <i class="fas fa-cog w-6"></i>
                                    Paramètres
                                </x-sidebar-item>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="lg:pl-72">
            <!-- Header -->
            <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Ouvrir le menu</span>
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <!-- Search -->
                    <div class="relative flex flex-1 items-center">
                        <form class="flex w-full" action="#" method="GET">
                            <label for="search-field" class="sr-only">Rechercher</label>
                            <div class="relative w-full">
                                <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"></i>
                                <input id="search-field" class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Rechercher..." type="search" name="search">
                            </div>
                        </form>
                    </div>

                    <!-- Menu utilisateur -->
                    <div class="flex items-center gap-x-4 lg:gap-x-6" x-data="{ open: false }">
                        <!-- Notifications -->
                        {{-- <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative">
                            <span class="sr-only">Voir les notifications</span>
                            <i class="fas fa-bell h-6 w-6"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                            @endif
                        </button> --}}
                        {{-- resources/views/layouts/app.blade.php --}}
                        <!-- Remplacer le bouton des notifications par : -->

                        <div class="relative" x-data="{ notificationsOpen: false }">
                            <button @click="notificationsOpen = !notificationsOpen"
                                    type="button"
                                    class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative">
                                <span class="sr-only">Voir les notifications</span>
                                <i class="fas fa-bell h-6 w-6"></i>
                                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Dropdown des notifications récentes -->
                            <div x-show="notificationsOpen"
                                @click.away="notificationsOpen = false"
                                class="absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg overflow-hidden z-50"
                                style="display: none;">

                                <div class="p-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">
                                        Voir tout
                                    </a>
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                        <div class="p-3 border-b border-gray-100 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3">
                                                    @php
                                                        $icon = match($notification->data['type'] ?? 'default') {
                                                            'subscription_activated' => '🎉',
                                                            'subscription_expiring' => '⚠️',
                                                            'subscription_expired' => '❌',
                                                            'form_limit' => '📊',
                                                            'new_template' => '✨',
                                                            'inactive_form' => '💤',
                                                            default => '🔔'
                                                        };
                                                    @endphp
                                                    <span class="text-xl">{{ $icon }}</span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] }}</p>
                                                    <p class="text-xs text-gray-600">{{ Str::limit($notification->data['message'], 60) }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-sm text-gray-500">
                                            <span class="text-3xl mb-2 block">🔔</span>
                                            Aucune nouvelle notification
                                        </div>
                                    @endforelse
                                </div>

                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <div class="p-2 bg-gray-50 border-t border-gray-200">
                                        <form action="{{ route('notifications.read-all') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full text-center text-xs text-blue-600 hover:text-blue-800 py-1">
                                                <i class="fas fa-check-double mr-1"></i>
                                                Marquer tout comme lu
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Séparateur -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200"></div>

                        <!-- Profil dropdown -->
                        <div class="relative" @click.away="open = false">
                            <button type="button" @click="open = !open" class="-m-1.5 flex items-center p-1.5">
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                @if(auth()->user()->avatar)
                                    <img class="h-8 w-8 rounded-full bg-gray-50" src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-2 h-5 w-5 text-gray-400"></i>
                                </span>
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                <a href="{{ route('profile.show') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <a href="{{ route('settings.index') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-2"></i> Paramètres
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Messages flash -->
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
