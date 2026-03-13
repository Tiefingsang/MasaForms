@extends('layouts.app')

@section('title', 'Détails - ' . $form->title)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.index') }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $form->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Détails et aperçu du formulaire
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('forms.edit', $form) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('forms.responses.index', $form) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Voir les réponses
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2">
                    <i class="fas fa-reply text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Réponses</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $form->responses_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2">
                    <i class="fas fa-eye text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Vues</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $form->views_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $form->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2">
                    <i class="fas fa-shield-alt text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-500">Statut</p>
                    <p class="text-xl font-semibold">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $form->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne de gauche : Informations et paramètres -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Titre</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $form->title }}</dd>
                    </div>

                    @if($form->description)
                    <div>
                        <dt class="text-sm text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-700">{{ $form->description }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm text-gray-500">Identifiant unique</dt>
                        <dd class="text-sm font-mono text-gray-600">{{ $form->slug }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Dernière modification</dt>
                        <dd class="text-sm text-gray-700">{{ $form->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Paramètres -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres</h3>

                <ul class="space-y-3">
                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Accepter les réponses</span>
                        <span class="text-sm">
                            @if($form->accepts_responses)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Oui</span>
                            @else
                                <span class="text-red-600"><i class="fas fa-times-circle"></i> Non</span>
                            @endif
                        </span>
                    </li>

                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Formulaire actif</span>
                        <span class="text-sm">
                            @if($form->is_active)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Oui</span>
                            @else
                                <span class="text-red-600"><i class="fas fa-times-circle"></i> Non</span>
                            @endif
                        </span>
                    </li>

                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Public</span>
                        <span class="text-sm">
                            @if($form->is_public)
                                <span class="text-green-600"><i class="fas fa-globe"></i> Public</span>
                            @else
                                <span class="text-red-600"><i class="fas fa-lock"></i> Privé</span>
                            @endif
                        </span>
                    </li>

                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Barre de progression</span>
                        <span class="text-sm">
                            @if($form->show_progress_bar)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Oui</span>
                            @else
                                <span class="text-red-600"><i class="fas fa-times-circle"></i> Non</span>
                            @endif
                        </span>
                    </li>

                    @if($form->max_responses)
                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Limite de réponses</span>
                        <span class="text-sm font-medium">{{ $form->max_responses }}</span>
                    </li>
                    @endif

                    @if($form->start_date)
                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Date de début</span>
                        <span class="text-sm">{{ $form->start_date->format('d/m/Y H:i') }}</span>
                    </li>
                    @endif

                    @if($form->end_date)
                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Date de fin</span>
                        <span class="text-sm">{{ $form->end_date->format('d/m/Y H:i') }}</span>
                    </li>
                    @endif

                    @if($form->password)
                    <li class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Protégé par mot de passe</span>
                        <span class="text-sm text-green-600"><i class="fas fa-lock"></i> Oui</span>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>

                <div class="space-y-3">
                    <a href="{{ route('forms.share', $form) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-share-alt mr-2"></i>
                        Partager le formulaire
                    </a>

                    <a href="{{ route('forms.design', $form) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-paint-brush mr-2"></i>
                        Personnaliser le design
                    </a>

                    <a href="{{ $form->public_url }}" target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir la version publique
                    </a>

                    <form action="{{ route('forms.duplicate', $form) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-copy mr-2"></i>
                            Dupliquer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne de droite : Aperçu du formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aperçu du formulaire</h3>

                <!-- Style personnalisé -->
                <style>
                    .preview-form {
                        background-color: {{ $form->background_color ?? '#FFFFFF' }};
                        color: {{ $form->text_color ?? '#374151' }};
                    }
                    .preview-form .preview-button {
                        background-color: {{ $form->primary_color ?? '#3B82F6' }};
                    }
                </style>

                <!-- Aperçu -->
                <div class="preview-form border border-gray-200 rounded-lg overflow-hidden">
                    <!-- En-tête -->
                    @if($form->cover_image)
                    <div class="h-32 overflow-hidden">
                        <img src="{{ Storage::url($form->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <div class="p-6">
                        <!-- Logo -->
                        @if($form->logo_path)
                        <div class="flex justify-center mb-4">
                            <img src="{{ Storage::url($form->logo_path) }}" alt="Logo" class="h-16 object-contain">
                        </div>
                        @endif

                        <!-- Titre et description -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold" style="color: {{ $form->primary_color ?? '#3B82F6' }}">
                                {{ $form->title }}
                            </h2>
                            @if($form->description)
                            <p class="mt-2 text-sm opacity-90">{{ $form->description }}</p>
                            @endif
                        </div>

                        <!-- Champs du formulaire -->
                        <div class="space-y-4">
                            @forelse($form->fields as $field)
                                <div>
                                    <label class="block text-sm font-medium mb-1">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($field->type === 'textarea')
                                        <textarea rows="3"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="{{ $field->placeholder ?? 'Réponse...' }}"
                                                  disabled></textarea>

                                    @elseif($field->type === 'select')
                                        <select class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" disabled>
                                            <option value="">Sélectionnez une option</option>
                                            @if(is_array($field->options))
                                                @foreach($field->options as $option)
                                                    <option>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    @elseif($field->type === 'radio' && is_array($field->options))
                                        <div class="space-y-2">
                                            @foreach($field->options as $option)
                                                <div class="flex items-center">
                                                    <input type="radio" class="h-4 w-4 border-gray-300 text-blue-600" disabled>
                                                    <span class="ml-2 text-sm">{{ $option }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($field->type === 'checkbox' && is_array($field->options))
                                        <div class="space-y-2">
                                            @foreach($field->options as $option)
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600" disabled>
                                                    <span class="ml-2 text-sm">{{ $option }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($field->type === 'date')
                                        <input type="date"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               disabled>

                                    @else
                                        <input type="text"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="{{ $field->placeholder ?? 'Réponse...' }}"
                                               disabled>
                                    @endif

                                    @if($field->help_text)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field->help_text }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-wpforms text-3xl mb-2"></i>
                                    <p>Aucun champ dans ce formulaire</p>
                                    <a href="{{ route('forms.edit', $form) }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                                        Ajouter des champs
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="mt-6">
                            <button type="button"
                                    class="preview-button w-full py-3 px-4 rounded-md text-white font-medium transition-opacity hover:opacity-90">
                                Envoyer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Message de remerciement -->
                @if($form->thank_you_message)
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="text-sm font-medium text-blue-800 mb-1">Message de remerciement</h4>
                    <p class="text-sm text-blue-600">{{ $form->thank_you_message }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Dernières réponses -->
    @if($form->responses()->count() > 0)
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Dernières réponses</h3>
            <a href="{{ route('forms.responses.index', $form) }}" class="text-sm text-blue-600 hover:text-blue-800">
                Voir toutes les réponses <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        @foreach($form->fields->take(3) as $field)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ $field->label }}</th>
                        @endforeach
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($form->responses()->latest()->take(5)->get() as $response)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $response->created_at->format('d/m/Y H:i') }}
                            </td>
                            @foreach($form->fields->take(3) as $field)
                                @php
                                    $value = $response->values->where('form_field_id', $field->id)->first();
                                @endphp
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $value->value ?? '-' }}
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('forms.responses.show', [$form, $response]) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
