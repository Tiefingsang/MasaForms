{{-- resources/views/pages/features.blade.php --}}
@extends('layouts.guest')

@section('title', 'Fonctionnalités')

@section('content')
<div class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Des fonctionnalités puissantes
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Tout ce dont vous avez besoin pour créer des formulaires professionnels et collecter des données efficacement.
            </p>
        </div>

        <!-- Grille des fonctionnalités -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Types de champs -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-input text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">10+ types de champs</h3>
                <p class="text-gray-600">Texte, email, téléphone, date, choix multiple, cases à cocher, liste déroulante et plus.</p>
            </div>

            <!-- Glisser-déposer -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-arrows-alt text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Glisser-déposer</h3>
                <p class="text-gray-600">Construisez vos formulaires intuitivement en glissant les champs.</p>
            </div>

            <!-- Templates -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-template text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Templates prêts à l'emploi</h3>
                <p class="text-gray-600">Gagnez du temps avec nos modèles de formulaires pré-conçus.</p>
            </div>

            <!-- Export Excel/CSV -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-file-excel text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Export Excel & CSV</h3>
                <p class="text-gray-600">Exportez vos données pour les analyser dans vos outils préférés.</p>
            </div>

            <!-- Statistiques -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-chart-pie text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Statistiques avancées</h3>
                <p class="text-gray-600">Visualisez vos résultats avec des graphiques interactifs.</p>
            </div>

            <!-- Notifications -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-bell text-indigo-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Notifications en temps réel</h3>
                <p class="text-gray-600">Soyez alerté à chaque nouvelle réponse par email ou WhatsApp.</p>
            </div>
        </div>
    </div>
</div>
@endsection
