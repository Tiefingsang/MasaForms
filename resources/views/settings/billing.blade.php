@extends('layouts.app')

@section('title', 'Facturation')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Facturation</h1>
        <p class="mt-1 text-sm text-gray-600">
            Gérez votre abonnement et vos factures.
        </p>
    </div>

    <!-- Navigation des onglets -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('settings.index') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Général
            </a>
            <a href="{{ route('settings.preferences') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Préférences
            </a>
            <a href="{{ route('settings.notifications') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Notifications
            </a>
            <a href="{{ route('settings.team') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Équipe
            </a>
            <a href="{{ route('settings.api') }}"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                API
            </a>
            <a href="{{ route('settings.billing') }}"
               class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Facturation
            </a>
        </nav>
    </div>

    @php
        $user = auth()->user();
        $plan = $user->currentPlan()->first();
    @endphp

    <!-- Plan actuel -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Abonnement actuel</h3>
        </div>

        <div class="px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Plan</p>
                    <p class="text-lg font-medium text-gray-900">{{ $plan->name ?? 'Gratuit' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prix</p>
                    <p class="text-lg font-medium text-gray-900">
                        @if($plan && $plan->price_monthly > 0)
                            {{ number_format($plan->price_monthly, 0, ',', ' ') }} FCFA/mois
                        @else
                            Gratuit
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    @php
                        $subscription = $user->activeSubscription()->first();
                    @endphp
                    @if($subscription && $subscription->status == 'active')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            Actif
                        </span>
                        @if($subscription->ends_at)
                            <p class="text-xs text-gray-500 mt-1">Expire le {{ $subscription->ends_at->format('d/m/Y') }}</p>
                        @endif
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            Inactif
                        </span>
                    @endif
                </div>
                <div>
                    <a href="{{ route('plans.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Changer de plan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Méthode de paiement -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Méthode de paiement</h3>
        </div>

        <div class="px-6 py-5">
            @if($user->paymentMethods()->count() > 0)
                @foreach($user->paymentMethods as $method)
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-2xl text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">•••• •••• •••• {{ $method->last4 }}</p>
                                <p class="text-xs text-gray-500">Expire {{ $method->exp_month }}/{{ $method->exp_year }}</p>
                            </div>
                            @if($method->is_default)
                                <span class="ml-3 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    Par défaut
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-6">
                    <i class="fas fa-credit-card text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Aucune méthode de paiement enregistrée</p>
                </div>
            @endif

            <div class="mt-4">
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une carte
                </button>
            </div>
        </div>
    </div>

    <!-- Historique des factures -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Historique des factures</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices ?? [] as $invoice)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $invoice->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($invoice->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $invoice->status == 'paid' ? 'Payée' : 'En attente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('settings.billing.invoice.download', $invoice) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-file-invoice text-3xl mb-2"></i>
                                <p>Aucune facture disponible</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $invoices->links() ?? '' }}
        </div>
    </div>
</div>
@endsection
