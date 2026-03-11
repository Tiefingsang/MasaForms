{{-- resources/views/welcome.blade.php --}}
@extends('layouts.guest')

@section('title', 'Accueil')

@section('content')
<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <div class="relative pt-16 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6">
                    Créez des formulaires <br>
                    <span class="text-blue-600">simples et puissants</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    La solution de formulaires en ligne adaptée au marché africain.
                    Collectez des données, gérez vos inscriptions et analysez les réponses facilement.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition">
                        Commencer gratuitement
                    </a>
                    <a href="{{ route('features') }}" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg text-lg font-medium hover:bg-gray-50 transition">
                        Découvrir
                    </a>
                </div>
                <p class="mt-4 text-sm text-gray-500">
                    Sans carte bancaire • 3 formulaires gratuits • 100 réponses/mois
                </p>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">+1000</div>
                    <div class="text-gray-600">Utilisateurs</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">+5000</div>
                    <div class="text-gray-600">Formulaires créés</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">+50K</div>
                    <div class="text-gray-600">Réponses collectées</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">24/7</div>
                    <div class="text-gray-600">Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fonctionnalités -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Pourquoi choisir Masadigitale Forms ?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Simple et rapide</h3>
                    <p class="text-gray-600">Créez vos formulaires en quelques minutes sans compétences techniques.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Responsive</h3>
                    <p class="text-gray-600">Vos formulaires s'adaptent parfaitement à tous les écrans (mobile, tablette).</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Analyses détaillées</h3>
                    <p class="text-gray-600">Visualisez et exportez vos données pour mieux les analyser.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-blue-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                Prêt à créer vos formulaires ?
            </h2>
            <p class="text-blue-100 mb-8 text-lg">
                Rejoignez des milliers d'utilisateurs qui nous font confiance
            </p>
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-medium hover:bg-gray-100 transition inline-block">
                Créer un compte gratuit
            </a>
        </div>
    </div>
</div>
@endsection
