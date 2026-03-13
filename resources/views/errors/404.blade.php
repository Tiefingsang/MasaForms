{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <!-- Icône -->
        <div class="mb-8">
            <i class="fas fa-search text-8xl text-gray-300"></i>
        </div>

        <!-- Message -->
        <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-600 mb-4">Page non trouvée</h2>
        <p class="text-gray-500 mb-8 max-w-md">
            Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
        </p>

        <!-- Bouton retour -->
        <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-home mr-2"></i>
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
