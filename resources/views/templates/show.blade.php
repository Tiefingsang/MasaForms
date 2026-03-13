@extends('layouts.app')

@section('title', $template->name . ' - Template')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Template • {{ $template->category }} • {{ $template->usage_count }} utilisations
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $canUse = !$template->is_premium || (auth()->user()->currentPlan()->first()?->has_templates ?? false);
                @endphp

                @if($canUse)
                    <form action="{{ route('templates.apply', $template->slug) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Utiliser ce template
                        </button>
                    </form>
                @else
                    <a href="{{ route('plans.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                        <i class="fas fa-crown mr-2"></i>
                        Passer en Premium
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne de gauche : Informations -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Image/Thumbnail -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                    @if($template->thumbnail)
                        <img src="{{ Storage::url($template->thumbnail) }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-template text-5xl text-white opacity-50"></i>
                        </div>
                    @endif

                    <!-- Badge Premium -->
                    @if($template->is_premium)
                        <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-bold flex items-center">
                            <i class="fas fa-crown mr-2"></i>
                            PREMIUM
                        </div>
                    @else
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                            GRATUIT
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-2">À propos de ce template</h3>
                    <p class="text-sm text-gray-600">
                        {{ $template->description ?? 'Aucune description disponible.' }}
                    </p>
                </div>
            </div>

            <!-- Caractéristiques -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                <h3 class="font-medium text-gray-900 mb-3">Caractéristiques</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Catégorie</dt>
                        <dd class="font-medium text-gray-900">{{ $template->category }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Nombre de champs</dt>
                        <dd class="font-medium text-gray-900">{{ count($template->structure['fields'] ?? []) }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Utilisations</dt>
                        <dd class="font-medium text-gray-900">{{ $template->usage_count }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Créé le</dt>
                        <dd class="font-medium text-gray-900">{{ $template->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Types de champs utilisés -->
            @if(isset($template->structure['fields']))
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                <h3 class="font-medium text-gray-900 mb-3">Champs inclus</h3>
                <div class="space-y-2">
                    @php
                        $fieldTypes = collect($template->structure['fields'])->groupBy('type');
                    @endphp

                    @foreach($fieldTypes as $type => $fields)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                @switch($type)
                                    @case('text')
                                        <i class="fas fa-font text-blue-500 w-5"></i>
                                        @break
                                    @case('textarea')
                                        <i class="fas fa-paragraph text-green-500 w-5"></i>
                                        @break
                                    @case('email')
                                        <i class="fas fa-envelope text-yellow-500 w-5"></i>
                                        @break
                                    @case('tel')
                                        <i class="fas fa-phone text-purple-500 w-5"></i>
                                        @break
                                    @case('number')
                                        <i class="fas fa-calculator text-red-500 w-5"></i>
                                        @break
                                    @case('date')
                                        <i class="fas fa-calendar text-indigo-500 w-5"></i>
                                        @break
                                    @case('radio')
                                        <i class="fas fa-dot-circle text-pink-500 w-5"></i>
                                        @break
                                    @case('checkbox')
                                        <i class="fas fa-check-square text-teal-500 w-5"></i>
                                        @break
                                    @case('select')
                                        <i class="fas fa-list text-orange-500 w-5"></i>
                                        @break
                                    @default
                                        <i class="fas fa-input text-gray-500 w-5"></i>
                                @endswitch
                                {{ ucfirst($type) }}
                            </span>
                            <span class="font-medium text-gray-900">{{ count($fields) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tags -->
            @if(isset($template->structure['tags']))
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                <h3 class="font-medium text-gray-900 mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($template->structure['tags'] as $tag)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne de droite : Aperçu du formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aperçu du formulaire</h3>

                <!-- Style personnalisé du template -->
                <style>
                    .template-preview {
                        background-color: {{ $template->structure['background_color'] ?? '#FFFFFF' }};
                        color: {{ $template->structure['text_color'] ?? '#374151' }};
                    }
                    .template-preview .preview-button {
                        background-color: {{ $template->structure['primary_color'] ?? '#3B82F6' }};
                    }
                </style>

                <!-- Aperçu -->
                <div class="template-preview border border-gray-200 rounded-lg overflow-hidden">
                    <!-- En-tête -->
                    @if(isset($template->structure['cover_image']))
                    <div class="h-32 overflow-hidden">
                        <img src="{{ Storage::url($template->structure['cover_image']) }}" alt="Cover" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <div class="p-6">
                        <!-- Logo -->
                        @if(isset($template->structure['logo_path']))
                        <div class="flex justify-center mb-4">
                            <img src="{{ Storage::url($template->structure['logo_path']) }}" alt="Logo" class="h-12 object-contain">
                        </div>
                        @endif

                        <!-- Titre et description -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold" style="color: {{ $template->structure['primary_color'] ?? '#3B82F6' }}">
                                {{ $template->name }}
                            </h2>
                            @if($template->description)
                            <p class="mt-2 text-sm opacity-90">{{ $template->description }}</p>
                            @endif
                        </div>

                        <!-- Champs du formulaire -->
                        <div class="space-y-4">
                            @forelse($template->structure['fields'] ?? [] as $field)
                                <div>
                                    <label class="block text-sm font-medium mb-1">
                                        {{ $field['label'] }}
                                        @if($field['is_required'] ?? false)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($field['type'] === 'textarea')
                                        <textarea rows="3"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                  placeholder="{{ $field['placeholder'] ?? 'Réponse...' }}"
                                                  disabled></textarea>

                                    @elseif($field['type'] === 'select')
                                        <select class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" disabled>
                                            <option value="">Sélectionnez une option</option>
                                            @if(is_array($field['options']))
                                                @foreach($field['options'] as $option)
                                                    <option>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    @elseif($field['type'] === 'radio' && is_array($field['options']))
                                        <div class="space-y-2">
                                            @foreach($field['options'] as $option)
                                                <div class="flex items-center">
                                                    <input type="radio" class="h-4 w-4 border-gray-300 text-blue-600" disabled>
                                                    <span class="ml-2 text-sm">{{ $option }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($field['type'] === 'checkbox' && is_array($field['options']))
                                        <div class="space-y-2">
                                            @foreach($field['options'] as $option)
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600" disabled>
                                                    <span class="ml-2 text-sm">{{ $option }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($field['type'] === 'date')
                                        <input type="date"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               disabled>

                                    @else
                                        <input type="text"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="{{ $field['placeholder'] ?? 'Réponse...' }}"
                                               disabled>
                                    @endif

                                    @if($field['help_text'] ?? false)
                                        <p class="mt-1 text-xs text-gray-500">{{ $field['help_text'] }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Ce template ne contient aucun champ</p>
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
            </div>
        </div>
    </div>

    <!-- Templates similaires -->
    @if(isset($similarTemplates) && $similarTemplates->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Templates similaires</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($similarTemplates as $similar)
                <a href="{{ route('templates.show', $similar->slug) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-32 bg-gradient-to-r from-gray-500 to-gray-600 relative">
                        @if($similar->thumbnail)
                            <img src="{{ Storage::url($similar->thumbnail) }}" alt="{{ $similar->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-template text-3xl text-white opacity-50"></i>
                            </div>
                        @endif

                        @if($similar->is_premium)
                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-crown mr-1"></i>
                                Premium
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h4 class="font-medium text-gray-900 text-sm">{{ $similar->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $similar->category }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
