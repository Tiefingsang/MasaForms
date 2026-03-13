<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire fermé - {{ $form->title }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Masadigitale Forms" class="h-12 mx-auto">
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <!-- En-tête avec la couleur du formulaire -->
                <div class="px-6 py-4" style="background-color: {{ $form->primary_color }};">
                    <h1 class="text-xl font-bold text-white text-center">
                        {{ $form->title }}
                    </h1>
                </div>

                <!-- Contenu -->
                <div class="p-8 text-center">
                    <!-- Icône selon la raison de fermeture -->
                    @if(!$form->is_active)
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                            <i class="fas fa-ban text-4xl text-red-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Formulaire désactivé</h2>
                        <p class="text-gray-600 mb-4">
                            Ce formulaire a été désactivé par son propriétaire.
                        </p>

                    @elseif(!$form->is_public)
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-100 mb-6">
                            <i class="fas fa-lock text-4xl text-yellow-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Formulaire privé</h2>
                        <p class="text-gray-600 mb-4">
                            Ce formulaire n'est pas accessible publiquement.
                        </p>

                    @elseif($form->start_date && $form->start_date->isFuture())
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 mb-6">
                            <i class="fas fa-clock text-4xl text-blue-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Formulaire pas encore ouvert</h2>
                        <p class="text-gray-600 mb-2">
                            Ce formulaire sera accessible à partir du :
                        </p>
                        <p class="text-lg font-semibold text-blue-600 mb-4">
                            {{ $form->start_date->format('d/m/Y à H:i') }}
                        </p>

                    @elseif($form->end_date && $form->end_date->isPast())
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-orange-100 mb-6">
                            <i class="fas fa-hourglass-end text-4xl text-orange-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Formulaire expiré</h2>
                        <p class="text-gray-600 mb-2">
                            La période de soumission est terminée depuis le :
                        </p>
                        <p class="text-lg font-semibold text-orange-600 mb-4">
                            {{ $form->end_date->format('d/m/Y à H:i') }}
                        </p>

                    @elseif($form->max_responses && $form->current_responses >= $form->max_responses)
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-purple-100 mb-6">
                            <i class="fas fa-chart-line text-4xl text-purple-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Nombre maximum de réponses atteint</h2>
                        <p class="text-gray-600 mb-4">
                            Ce formulaire a atteint le nombre maximum de réponses autorisées ({{ $form->max_responses }}).
                        </p>

                    @else
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-question-circle text-4xl text-gray-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Formulaire indisponible</h2>
                        <p class="text-gray-600 mb-4">
                            Ce formulaire n'accepte pas les réponses pour le moment.
                        </p>
                    @endif

                    <!-- Informations supplémentaires -->
                    @if($form->description)
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "{{ $form->description }}"
                            </p>
                        </div>
                    @endif

                    <!-- Bouton retour -->
                    <div class="mt-8">
                        <a href="{{ url('/') }}"
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-home mr-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                    <div class="px-6 py-4 bg-gray-50 text-center text-xs text-gray-500 border-t border-gray-200">
                        Propulsé par <a href="{{ url('/') }}" class="text-blue-600 hover:underline">Masadigitale Forms</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
