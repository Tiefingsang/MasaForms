<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci ! - {{ $form->title }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <!-- Logo -->
            @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                <div class="mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Masadigitale Forms" class="h-12 mx-auto">
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-lg p-8">
                <!-- Icône de succès -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>

                <!-- Message -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Merci !</h1>

                <p class="text-gray-600 mb-6">
                    {{ $form->thank_you_message ?? 'Votre réponse a bien été enregistrée.' }}
                </p>

                <!-- Bouton retour -->
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-home mr-2"></i>
                    Retour à l'accueil
                </a>
            </div>

            <!-- Footer -->
            @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                <p class="mt-8 text-xs text-gray-500">
                    Propulsé par <a href="{{ url('/') }}" class="text-blue-600 hover:underline">Masadigitale Forms</a>
                </p>
            @endif
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
