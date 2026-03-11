{{-- resources/views/pages/contact.blade.php --}}
@extends('layouts.guest')

@section('title', 'Contact')

@section('content')
<div class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Contactez-nous
            </h1>
            <p class="text-xl text-gray-600">
                Une question ? Une suggestion ? Notre équipe est là pour vous aider.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Email -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Email</h3>
                <p class="text-gray-600">contact@masadigitale.com</p>
                <p class="text-gray-500 text-sm">Réponse sous 24h</p>
            </div>

            <!-- Téléphone -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone-alt text-green-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Téléphone</h3>
                <p class="text-gray-600">+225 07 07 07 07</p>
                <p class="text-gray-500 text-sm">Lun-Ven, 8h-18h</p>
            </div>

            <!-- Adresse -->
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-purple-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Bureau</h3>
                <p class="text-gray-600">Abidjan, Côte d'Ivoire</p>
                <p class="text-gray-500 text-sm">Cocody, Angré</p>
            </div>
        </div>

        <!-- Formulaire de contact -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                    <input type="text" name="subject" id="subject" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" id="message" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <button type="submit"
                        class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
