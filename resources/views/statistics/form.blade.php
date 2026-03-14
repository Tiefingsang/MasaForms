{{-- resources/views/statistics/form.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistiques - ' . $form->title)

@section('content')
<div class="max-w-7xl mx-auto" x-data="statisticsManager()" x-init="init({{ json_encode($stats) }})">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('forms.responses.index', $form) }}" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Statistiques - {{ $form->title }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Analyse détaillée des réponses de votre formulaire
                </p>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-reply text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total réponses</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['total_responses'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Taux de complétion</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['completion_rate'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Temps moyen</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['avg_completion_time'] ?? 'N/A' }}s</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Moyenne/jour</p>
                    <p class="text-xl font-semibold text-gray-900">
                        {{ $stats['responses_by_day']->count() > 0 ? round($stats['total_responses'] / max($stats['responses_by_day']->count(), 1), 1) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des réponses</h3>
        <div class="h-64" id="responses-chart"></div>
    </div>

    <!-- Distribution horaire -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réponses par heure</h3>
            <div class="h-48" id="hourly-chart"></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top 5 des heures</h3>
            <div class="space-y-2">
                @foreach($stats['responses_by_hour']->sortByDesc('total')->take(5) as $hour)
                    <div class="flex items-center">
                        <span class="w-16 text-sm text-gray-600">{{ $hour->hour }}h - {{ $hour->hour+1 }}h</span>
                        <div class="flex-1 mx-2">
                            <div class="bg-blue-100 rounded-full h-2">
                                @php
                                    $maxHour = $stats['responses_by_hour']->max('total');
                                    $width = $maxHour > 0 ? ($hour->total / $maxHour) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 rounded-full h-2" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $hour->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Analyse par champ -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Analyse par champ</h3>

        <div class="space-y-6">
            @foreach($fieldStats as $stat)
                <div class="border-t border-gray-100 pt-4 first:border-t-0 first:pt-0">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-900">{{ $stat['field']->label }}</h4>
                        <span class="text-sm text-gray-500">
                            {{ $stat['filled'] }} / {{ $stat['total'] }} réponses
                            ({{ $stat['total'] > 0 ? round(($stat['filled'] / $stat['total']) * 100) : 0 }}%)
                        </span>
                    </div>

                    <!-- Barre de remplissage -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                        @php $fillRate = $stat['total'] > 0 ? ($stat['filled'] / $stat['total']) * 100 : 0; @endphp
                        <div class="bg-green-600 rounded-full h-2" style="width: {{ $fillRate }}%"></div>
                    </div>

                    <!-- Distribution pour les champs à choix -->
                    @if(isset($stat['distribution']) && $stat['distribution']->count() > 0)
                        <div class="mt-3 space-y-2">
                            @foreach($stat['distribution'] as $dist)
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 w-32 truncate">{{ $dist->value ?: 'Non rempli' }}</span>
                                    <div class="flex-1 mx-2">
                                        <div class="bg-blue-100 rounded-full h-2">
                                            @php
                                                $maxDist = $stat['distribution']->max('total');
                                                $distWidth = $maxDist > 0 ? ($dist->total / $maxDist) * 100 : 0;
                                            @endphp
                                            <div class="bg-blue-600 rounded-full h-2" style="width: {{ $distWidth }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $dist->total }}</span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        ({{ $stat['total'] > 0 ? round(($dist->total / $stat['total']) * 100) : 0 }}%)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function statisticsManager() {
    return {
        init(stats) {
            this.initResponsesChart(stats.responses_by_day);
            this.initHourlyChart(stats.responses_by_hour);
        },

        initResponsesChart(data) {
            const ctx = document.getElementById('responses-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
                    }).reverse(),
                    datasets: [{
                        label: 'Réponses',
                        data: data.map(item => item.total).reverse(),
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
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        },

        initHourlyChart(data) {
            const ctx = document.getElementById('hourly-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.hour + 'h'),
                    datasets: [{
                        label: 'Réponses',
                        data: data.map(item => item.total),
                        backgroundColor: '#3B82F6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    }
}
</script>
@endpush
@endsection
