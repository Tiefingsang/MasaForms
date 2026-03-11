@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-2 text-sm text-gray-700">
                Bienvenue, {{ auth()->user()->name }} ! Voici un aperçu de votre activité.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus -ml-1 mr-2"></i>
                Nouveau formulaire
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total formulaires -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wpforms text-2xl text-blue-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total formulaires</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_forms'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-{{ $stats['forms_left'] === '∞' ? 'green' : ($stats['forms_left'] > 0 ? 'green' : 'red') }}-600">
                        {{ $stats['forms_left'] === '∞' ? 'Illimité' : $stats['forms_left'] . ' restants' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total réponses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-reply text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total réponses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_responses'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('forms.index') }}" class="font-medium text-blue-600 hover:text-blue-900">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulaires actifs -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Formulaires actifs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active_forms'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan actuel -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-crown text-2xl text-yellow-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Plan actuel</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ auth()->user()->currentPlan()->first()?->name ?? 'Gratuit' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('plans.index') }}" class="font-medium text-blue-600 hover:text-blue-900">
                        @if(auth()->user()->currentPlan()->first()?->slug === 'free')
                            Passer en Pro
                        @else
                            Gérer mon abonnement
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique d'activité -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Activité des 30 derniers jours</h3>
        <div class="h-64" id="activity-chart"></div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Formulaires récents -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Formulaires récents</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentForms as $form)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-wpforms text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('forms.edit', $form) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                        {{ $form->title }}
                                    </a>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs text-gray-500">
                                            Créé le {{ $form->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-xs text-gray-500">
                                            {{ $form->responses_count }} réponse(s)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ $form->public_url }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ route('forms.responses.index', $form) }}" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <i class="fas fa-wpforms text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Aucun formulaire créé</p>
                        <a href="{{ route('forms.create') }}" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-500">
                            Créer votre premier formulaire
                        </a>
                    </div>
                @endforelse
            </div>
            <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
                <a href="{{ route('forms.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Voir tous les formulaires →
                </a>
            </div>
        </div>

        <!-- Dernières réponses -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Dernières réponses</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentResponses as $response)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $response->respondent_email ?? 'Anonyme' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Formulaire : {{ $response->form->title }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $response->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('forms.responses.show', [$response->form, $response]) }}"
                               class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Aucune réponse pour le moment</p>
                    </div>
                @endforelse
            </div>
            @if($recentResponses->isNotEmpty())
                <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
                    <a href="{{ route('forms.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Voir toutes les réponses →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique d'activité
    const ctx = document.getElementById('activity-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Réponses',
                data: {!! json_encode($chartData ?? []) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
