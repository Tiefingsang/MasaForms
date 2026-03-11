<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masadigitale Forms')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Masadigitale" class="h-8 w-auto">
                            <span class="text-xl font-bold text-gray-900">Forms</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('features') }}" class="text-gray-700 hover:text-gray-900">Fonctionnalités</a>
                        <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-gray-900">Tarifs</a>
                        <a href="{{ route('templates.public') }}" class="text-gray-700 hover:text-gray-900">Templates</a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Connexion</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Inscription gratuite
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white/80 backdrop-blur-sm mt-20">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Produit</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="{{ route('features') }}" class="text-gray-500 hover:text-gray-900">Fonctionnalités</a></li>
                            <li><a href="{{ route('pricing') }}" class="text-gray-500 hover:text-gray-900">Tarifs</a></li>
                            <li><a href="{{ route('templates.public') }}" class="text-gray-500 hover:text-gray-900">Templates</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Support</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-500 hover:text-gray-900">Documentation</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-gray-900">Guide</a></li>
                            <li><a href="{{ route('contact') }}" class="text-gray-500 hover:text-gray-900">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Légal</h3>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-gray-500 hover:text-gray-900">Confidentialité</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-gray-900">Conditions</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Suivez-nous</h3>
                        <div class="mt-4 flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <i class="fab fa-facebook h-6 w-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <i class="fab fa-twitter h-6 w-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <i class="fab fa-linkedin h-6 w-6"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Masadigitale Forms. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
