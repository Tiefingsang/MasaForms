{{-- resources/views/pages/about.blade.php --}}
@extends('layouts.guest')

@section('title', 'À propos')

@section('content')
<div class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                À propos de Masadigitale
            </h1>
            <p class="text-xl text-gray-600">
                Notre mission : rendre la collecte de données accessible à toutes les entreprises africaines.
            </p>
        </div>

        <!-- Notre histoire -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Notre histoire</h2>
            <p class="text-gray-600 mb-4">
                Masadigitale est née d'un constat simple : les solutions de formulaires existantes ne sont pas adaptées au marché africain.
                Entre les prix en devises étrangères et les fonctionnalités trop complexes, les entreprises locales peinent à trouver l'outil idéal.
            </p>
            <p class="text-gray-600">
                Nous avons donc créé Masadigitale Forms, une plateforme simple, abordable et parfaitement adaptée aux besoins locaux.
            </p>
        </div>

        <!-- Nos valeurs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hand-holding-heart text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Accessibilité</h3>
                <p class="text-gray-600">Des prix adaptés au pouvoir d'achat local</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Simplicité</h3>
                <p class="text-gray-600">Une interface intuitive, sans courbe d'apprentissage</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">Support</h3>
                <p class="text-gray-600">Une équipe locale à votre écoute</p>
            </div>
        </div>

        <!-- L'équipe -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Notre équipe</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name=Jean+Konan&size=80&background=3B82F6&color=fff"
                         alt="Jean Konan" class="w-20 h-20 rounded-full">
                    <div>
                        <h3 class="font-semibold">Jean Konan</h3>
                        <p class="text-gray-600 text-sm">Fondateur & CEO</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name=Marie+Kouassi&size=80&background=3B82F6&color=fff"
                         alt="Marie Kouassi" class="w-20 h-20 rounded-full">
                    <div>
                        <h3 class="font-semibold">Marie Kouassi</h3>
                        <p class="text-gray-600 text-sm">Directrice Technique</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
