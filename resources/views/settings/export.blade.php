@extends('layouts.app')

@section('title', 'Export des données')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Export des données</h1>
        <p class="mt-1 text-sm text-gray-600">
            Téléchargez une copie de vos données personnelles.
        </p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Exporter mes données</h3>
            <p class="mt-1 text-sm text-gray-500">
                Conformément au RGPD, vous pouvez télécharger toutes les données que nous détenons sur vous.
            </p>
        </div>

        <div class="px-6 py-5">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            L'export peut prendre quelques minutes. Vous recevrez un email lorsque vos données seront prêtes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    L'export inclut :
                </p>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Vos informations de profil</li>
                    <li>Tous vos formulaires (y compris les champs)</li>
                    <li>Toutes vos réponses reçues</li>
                    <li>Votre historique de paiements</li>
                    <li>Vos préférences et paramètres</li>
                </ul>
            </div>

            <div class="mt-6">
                <form action="{{ route('settings.export-data.request') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-download mr-2"></i>
                        Demander l'export de mes données
                    </button>
                </form>
            </div>
        </div>

        <!-- Exports précédents -->
        @if(isset($previousExports) && $previousExports->count() > 0)
            <div class="px-6 py-5 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Exports récents</h4>
                <div class="space-y-2">
                    @foreach($previousExports as $export)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file-export text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600">
                                    {{ $export->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            @if($export->is_ready)
                                <a href="{{ $export->download_url }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-download mr-1"></i>
                                    Télécharger
                                </a>
                            @else
                                <span class="text-sm text-gray-400">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    En cours...
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
