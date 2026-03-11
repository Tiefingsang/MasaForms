@extends('layouts.app')

@section('title', 'Détail de la réponse - ' . $form->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.responses.index', $form) }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Détail de la réponse</h1>
            </div>
            <div class="flex items-center space-x-3">
                <form method="POST" action="{{ route('forms.responses.destroy', [$form, $response]) }}"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Informations de la réponse -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Métadonnées -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Date de soumission</p>
                    <p class="text-sm font-medium text-gray-900">{{ $response->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">IP du répondant</p>
                    <p class="text-sm font-medium text-gray-900">{{ $response->ip_address ?? 'Non disponible' }}</p>
                </div>
                @if($response->respondent_name)
                <div>
                    <p class="text-xs text-gray-500">Nom du répondant</p>
                    <p class="text-sm font-medium text-gray-900">{{ $response->respondent_name }}</p>
                </div>
                @endif
                @if($response->respondent_email)
                <div>
                    <p class="text-xs text-gray-500">Email du répondant</p>
                    <p class="text-sm font-medium text-gray-900">{{ $response->respondent_email }}</p>
                </div>
                @endif
                @if($response->user_agent)
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">User Agent</p>
                    <p class="text-sm text-gray-600">{{ $response->user_agent }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Réponses aux champs -->
        <div class="px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Réponses fournies</h2>

            <dl class="divide-y divide-gray-200">
                @foreach($form->fields as $field)
                    @php
                        $value = $response->values->where('form_field_id', $field->id)->first();
                    @endphp
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ $field->label }}
                            @if($field->is_required)
                                <span class="text-red-500 ml-1">*</span>
                            @endif
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($value && $value->value)
                                @if($field->type == 'file')
                                    <a href="{{ Storage::url($value->value) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-download mr-2"></i>
                                        Télécharger le fichier
                                    </a>
                                @elseif($field->type == 'checkbox' && is_array(json_decode($value->value, true)))
                                    <ul class="list-disc list-inside">
                                        @foreach(json_decode($value->value, true) as $option)
                                            <li>{{ $option }}</li>
                                        @endforeach
                                    </ul>
                                @elseif($field->type == 'radio' || $field->type == 'select')
                                    {{ $value->value }}
                                @else
                                    {{ $value->value }}
                                @endif
                            @else
                                <span class="text-gray-400 italic">Non renseigné</span>
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</div>
@endsection
