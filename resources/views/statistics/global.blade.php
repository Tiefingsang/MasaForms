{{-- resources/views/statistics/global.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistiques globales')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistiques globales</h1>
            <p class="mt-1 text-sm text-gray-600">
                Aperçu de votre activité sur la plateforme.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('statistics.export') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-download mr-2"></i>
                Exporter les données
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-wpforms text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total formulaires</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_forms'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-reply text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total réponses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_responses'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <i class="fas fa-check-circle text-white"></i>
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

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Moy. réponses/form</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['avg_responses_per_form'], 1) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique d'activité -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Activité des 30 derniers jours</h3>
        <div class="h-64" id="activity-chart"></div>
    </div>

    <!-- Top formulaires -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Top 5 formulaires</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($topForms as $form)
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $form->title }}</p>
                            <p class="text-sm text-gray-500">{{ $form->responses_count }} réponse(s)</p>
                        </div>
                        <a href="{{ route('forms.responses.index', $form) }}"
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            Voir les réponses
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    Aucun formulaire créé
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activity-chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($responsesByDay->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })) !!},
            datasets: [{
                label: 'Réponses',
                data: {!! json_encode($responsesByDay->pluck('total')) !!},
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
            }
        }
    });
</script>
@endpush
@endsection
