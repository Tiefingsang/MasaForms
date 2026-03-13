@extends('layouts.app')

@section('title', 'Partager - ' . $form->title)

@section('content')
<div class="max-w-4xl mx-auto" x-data="shareManager()" x-init="init('{{ $form->public_url }}')">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.edit', $form) }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Partager le formulaire</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Partagez "{{ $form->title }}" avec vos répondants
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    {{ $form->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <i class="fas fa-globe mr-1"></i>
                    {{ $form->is_public ? 'Public' : 'Privé' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Si le formulaire est privé -->
    @if(!$form->is_public)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Ce formulaire est privé.</strong> Pour le partager, vous devez d'abord le rendre public.
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('forms.settings', $form) }}"
                           class="text-sm text-yellow-700 underline hover:text-yellow-600">
                            Modifier les paramètres →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cartes de partage -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Carte: Lien direct -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-link text-blue-600"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-green-100 text-green-800 rounded-full">Recommandé</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Lien direct</h3>
            <p class="text-sm text-gray-600 mb-4">Partagez ce lien par email, SMS ou sur vos réseaux sociaux.</p>
            <div class="flex items-center space-x-2">
                <input type="text"
                       id="public-link"
                       value="{{ $form->public_url }}"
                       class="flex-1 rounded-md border-gray-300 bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500"
                       readonly>
                <button onclick="copyToClipboard('public-link')"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Carte: QR Code -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-qrcode text-purple-600"></i>
                </div>
                <button @click="downloadQRCode"
                        class="text-xs font-medium px-3 py-1 bg-purple-100 text-purple-800 rounded-full hover:bg-purple-200">
                    <i class="fas fa-download mr-1"></i>
                    Télécharger
                </button>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">QR Code</h3>
            <p class="text-sm text-gray-600 mb-4">Scannez pour accéder au formulaire depuis un mobile.</p>
            <div class="flex justify-center">
                <div id="qrcode" class="p-2 bg-white border border-gray-200 rounded-lg"></div>
            </div>
        </div>

        <!-- Carte: Intégration -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-code text-green-600"></i>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-gray-100 text-gray-800 rounded-full">Site web</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Intégration</h3>
            <p class="text-sm text-gray-600 mb-4">Intégrez le formulaire directement sur votre site.</p>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Largeur</label>
                    <select x-model="iframeWidth" class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="100%">100% (responsive)</option>
                        <option value="800px">800 pixels</option>
                        <option value="600px">600 pixels</option>
                        <option value="400px">400 pixels</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Hauteur</label>
                    <select x-model="iframeHeight" class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="600px">600 pixels</option>
                        <option value="500px">500 pixels</option>
                        <option value="400px">400 pixels</option>
                        <option value="800px">800 pixels</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="text"
                           id="embed-code"
                           x-model="embedCode"
                           class="flex-1 rounded-md border-gray-300 bg-gray-50 text-xs font-mono focus:ring-blue-500 focus:border-blue-500"
                           readonly>
                    <button onclick="copyToClipboard('embed-code')"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Options de partage social -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Partager sur les réseaux sociaux</h3>
        <div class="flex flex-wrap gap-3">
            <!-- WhatsApp -->
            <a href="https://wa.me/?text={{ urlencode('Répondez à ce formulaire : ' . $form->public_url) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                <i class="fab fa-whatsapp mr-2"></i>
                WhatsApp
            </a>

            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($form->public_url) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fab fa-facebook mr-2"></i>
                Facebook
            </a>

            <!-- Twitter/X -->
            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Répondez à ce formulaire : ') }}&url={{ urlencode($form->public_url) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                <i class="fab fa-x-twitter mr-2"></i>
                X (Twitter)
            </a>

            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($form->public_url) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition">
                <i class="fab fa-linkedin mr-2"></i>
                LinkedIn
            </a>

            <!-- Email -->
            <a href="mailto:?subject={{ urlencode('Formulaire : ' . $form->title) }}&body={{ urlencode('Répondez à ce formulaire : ' . $form->public_url) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-envelope mr-2"></i>
                Email
            </a>
        </div>
    </div>

    <!-- Statistiques et options avancées -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Statistiques rapides -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques de partage</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Vues du formulaire</span>
                        <span class="font-medium text-gray-900">{{ $form->views_count ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Taux de conversion</span>
                        <span class="font-medium text-gray-900">
                            @php
                                $views = $form->views_count ?? 0;
                                $responses = $form->responses_count ?? 0;
                                $rate = $views > 0 ? round(($responses / $views) * 100) : 0;
                            @endphp
                            {{ $rate }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Les statistiques se mettent à jour en temps réel.
            </div>
        </div>

        <!-- Gestion du lien -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Gestion du lien</h3>
            <div class="space-y-4">
                <!-- Régénérer le lien -->
                <form action="{{ route('forms.regenerate-link', $form) }}" method="POST"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir générer un nouveau lien ? L\'ancien lien ne fonctionnera plus et les statistiques seront réinitialisées.')">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Régénérer le lien
                    </button>
                </form>

                <!-- Désactiver le formulaire -->
                @if($form->is_active)
                    <form action="{{ route('forms.toggle-status', $form) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                            <i class="fas fa-stop-circle mr-2"></i>
                            Désactiver temporairement
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
function shareManager() {
    return {
        iframeWidth: '100%',
        iframeHeight: '600px',
        formUrl: '',

        init(url) {
            this.formUrl = url;
            this.generateQRCode();
        },

        get embedCode() {
            return `<iframe src="${this.formUrl}" width="${this.iframeWidth}" height="${this.iframeHeight}" frameborder="0"></iframe>`;
        },

        generateQRCode() {
            new QRCode(document.getElementById('qrcode'), {
                text: this.formUrl,
                width: 150,
                height: 150,
                colorDark: '#1f2937',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        },

        downloadQRCode() {
            const canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = 'qrcode-{{ $form->slug }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
        }
    }
}

// Fonction globale pour copier dans le presse-papier
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');

    // Feedback utilisateur
    const button = event.currentTarget;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i>';
    button.classList.add('bg-green-500', 'text-white');
    button.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-50');

    setTimeout(() => {
        button.innerHTML = originalHtml;
        button.classList.remove('bg-green-500', 'text-white');
        button.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-50');
    }, 2000);
}
</script>
@endpush
@endsection
