@extends('layouts.app')

@section('title', 'Design - ' . $form->title)

@section('content')
<div class="max-w-7xl mx-auto" x-data="designManager()" x-init="init({{ json_encode($form) }})">
    <!-- En-tête -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.edit', $form) }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Personnaliser le design</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Personnalisez l'apparence de "{{ $form->title }}"
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="resetToDefault"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-undo mr-2"></i>
                    Réinitialiser
                </button>
                <button @click="saveDesign"
                        :disabled="saving"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50">
                    <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="saving ? 'Sauvegarde...' : 'Enregistrer'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    <template x-if="notification.show">
        <div class="mb-4 px-4 py-3 rounded-lg flex items-center"
             :class="notification.type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'">
            <i :class="notification.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" class="mr-2"></i>
            <span x-text="notification.message"></span>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panneau de configuration -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Couleurs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Couleurs</h3>

                <div class="space-y-4">
                    <!-- Couleur principale -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur principale
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color"
                                   x-model="form.primary_color"
                                   class="h-10 w-20 rounded-md border border-gray-300 cursor-pointer">
                            <input type="text"
                                   x-model="form.primary_color"
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono"
                                   placeholder="#3B82F6">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Utilisée pour les boutons et les titres</p>
                    </div>

                    <!-- Couleur de fond -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur de fond
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color"
                                   x-model="form.background_color"
                                   class="h-10 w-20 rounded-md border border-gray-300 cursor-pointer">
                            <input type="text"
                                   x-model="form.background_color"
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono"
                                   placeholder="#FFFFFF">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Arrière-plan du formulaire</p>
                    </div>

                    <!-- Couleur du texte -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur du texte
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color"
                                   x-model="form.text_color"
                                   class="h-10 w-20 rounded-md border border-gray-300 cursor-pointer">
                            <input type="text"
                                   x-model="form.text_color"
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono"
                                   placeholder="#374151">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Couleur du texte des questions</p>
                    </div>
                </div>

                <!-- Thèmes prédéfinis -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Thèmes rapides</h4>
                    <div class="grid grid-cols-4 gap-2">
                        <button @click="applyTheme('default')"
                                class="h-10 rounded-lg border-2 transition-all"
                                style="background: linear-gradient(135deg, #3B82F6 50%, #F3F4F6 50%)"
                                title="Défaut"></button>
                        <button @click="applyTheme('dark')"
                                class="h-10 rounded-lg border-2 transition-all"
                                style="background: linear-gradient(135deg, #1F2937 50%, #111827 50%)"
                                title="Sombre"></button>
                        <button @click="applyTheme('nature')"
                                class="h-10 rounded-lg border-2 transition-all"
                                style="background: linear-gradient(135deg, #10B981 50%, #ECFDF5 50%)"
                                title="Nature"></button>
                        <button @click="applyTheme('sunset')"
                                class="h-10 rounded-lg border-2 transition-all"
                                style="background: linear-gradient(135deg, #F59E0B 50%, #FFFBEB 50%)"
                                title="Sunset"></button>
                    </div>
                </div>
            </div>

            <!-- Logo et image -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Logo & Image</h3>

                <form id="logo-form" enctype="multipart/form-data">
                    @csrf
                    <!-- Logo -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo du formulaire</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="h-16 w-16 object-contain border border-gray-200 rounded-lg">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="h-16 w-16 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file"
                                       id="logo"
                                       name="logo"
                                       accept="image/*"
                                       @change="previewLogo"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, SVG (max. 2Mo)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image de couverture -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image de couverture</label>
                        <div class="mb-2">
                            <template x-if="coverPreview">
                                <img :src="coverPreview" class="h-32 w-full object-cover border border-gray-200 rounded-lg">
                            </template>
                        </div>
                        <input type="file"
                               id="cover_image"
                               name="cover_image"
                               accept="image/*"
                               @change="previewCover"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Image d'en-tête (1920x300px recommandé)</p>
                    </div>
                </form>
            </div>

            <!-- Typographie -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Typographie</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Police du titre</label>
                        <select x-model="form.font_title" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="font-sans">Inter (Sans-serif)</option>
                            <option value="font-serif">Georgia (Serif)</option>
                            <option value="font-mono">Monospace</option>
                            <option value="font-['Poppins']">Poppins</option>
                            <option value="font-['Roboto']">Roboto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Police du texte</label>
                        <select x-model="form.font_body" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="font-sans">Inter (Sans-serif)</option>
                            <option value="font-serif">Georgia (Serif)</option>
                            <option value="font-mono">Monospace</option>
                            <option value="font-['Open_Sans']">Open Sans</option>
                            <option value="font-['Lato']">Lato</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aperçu en direct -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 sticky top-24">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aperçu en direct</h3>

                <!-- Cadre de prévisualisation -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <!-- En-tête avec la couleur primaire -->
                    <div class="px-6 py-4 text-center" :style="{ backgroundColor: form.primary_color }">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="h-12 mx-auto mb-2 object-contain">
                        </template>
                        <h2 class="text-2xl font-bold text-white" x-text="form.title"></h2>
                        <p class="text-white opacity-90 text-sm" x-text="form.description" x-show="form.description"></p>
                    </div>

                    <!-- Corps du formulaire -->
                    <div class="p-6" :style="{ backgroundColor: form.background_color }">
                        <!-- Image de couverture -->
                        <template x-if="coverPreview">
                            <img :src="coverPreview" class="w-full h-32 object-cover rounded-lg mb-6">
                        </template>

                        <!-- Champs d'exemple -->
                        <div class="space-y-4">
                            <!-- Champ texte -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :style="{ color: form.text_color }">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       placeholder="Jean Dupont"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       :style="{ borderColor: form.primary_color }"
                                       disabled>
                            </div>

                            <!-- Champ email -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :style="{ color: form.text_color }">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       placeholder="jean@exemple.com"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       disabled>
                            </div>

                            <!-- Bouton de soumission -->
                            <div class="pt-4">
                                <button type="button"
                                        class="w-full py-3 px-4 rounded-md text-white font-medium transition-opacity hover:opacity-90"
                                        :style="{ backgroundColor: form.primary_color }">
                                    Envoyer
                                </button>
                            </div>
                        </div>

                        <!-- Note de bas de page -->
                        <div class="mt-4 text-center text-xs" :style="{ color: form.text_color }">
                            Propulsé par Masadigitale Forms
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-xs text-gray-500 text-center">
                    <i class="fas fa-eye mr-1"></i>
                    Aperçu en temps réel - Les modifications sont visibles avant sauvegarde
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function designManager() {
    return {
        form: {
            id: null,
            title: '',
            description: '',
            primary_color: '#3B82F6',
            background_color: '#FFFFFF',
            text_color: '#374151',
            font_title: 'font-sans',
            font_body: 'font-sans',
            logo_path: null,
            cover_image: null
        },
        logoPreview: null,
        coverPreview: null,
        saving: false,
        notification: {
            show: false,
            type: 'success',
            message: ''
        },

        init(formData) {
            this.form = formData;
            if (this.form.logo_path) {
                this.logoPreview = `/storage/${this.form.logo_path}`;
            }
            if (this.form.cover_image) {
                this.coverPreview = `/storage/${this.form.cover_image}`;
            }
        },

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewCover(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.coverPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        applyTheme(theme) {
            const themes = {
                default: {
                    primary_color: '#3B82F6',
                    background_color: '#FFFFFF',
                    text_color: '#374151'
                },
                dark: {
                    primary_color: '#1F2937',
                    background_color: '#111827',
                    text_color: '#F3F4F6'
                },
                nature: {
                    primary_color: '#10B981',
                    background_color: '#ECFDF5',
                    text_color: '#065F46'
                },
                sunset: {
                    primary_color: '#F59E0B',
                    background_color: '#FFFBEB',
                    text_color: '#92400E'
                }
            };

            if (themes[theme]) {
                this.form.primary_color = themes[theme].primary_color;
                this.form.background_color = themes[theme].background_color;
                this.form.text_color = themes[theme].text_color;
            }
        },

        resetToDefault() {
            if (confirm('Réinitialiser toutes les modifications ?')) {
                this.form.primary_color = '#3B82F6';
                this.form.background_color = '#FFFFFF';
                this.form.text_color = '#374151';
                this.form.font_title = 'font-sans';
                this.form.font_body = 'font-sans';
                this.logoPreview = this.form.logo_path ? `/storage/${this.form.logo_path}` : null;
                this.coverPreview = this.form.cover_image ? `/storage/${this.form.cover_image}` : null;
                document.getElementById('logo').value = '';
                document.getElementById('cover_image').value = '';
                this.showNotification('success', 'Design réinitialisé');
            }
        },

        async saveDesign() {
            this.saving = true;

            try {
                const formData = new FormData();

                // Ajouter les champs texte
                formData.append('primary_color', this.form.primary_color);
                formData.append('background_color', this.form.background_color);
                formData.append('text_color', this.form.text_color);
                formData.append('font_title', this.form.font_title);
                formData.append('font_body', this.form.font_body);

                // Ajouter les fichiers
                const logoFile = document.getElementById('logo').files[0];
                if (logoFile) {
                    formData.append('logo', logoFile);
                }

                const coverFile = document.getElementById('cover_image').files[0];
                if (coverFile) {
                    formData.append('cover_image', coverFile);
                }

                const response = await fetch(`/forms/${this.form.id}/design`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.showNotification('success', 'Design sauvegardé avec succès !');
                } else {
                    this.showNotification('error', data.message || 'Erreur lors de la sauvegarde');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('error', 'Erreur de connexion');
            } finally {
                this.saving = false;
            }
        },

        showNotification(type, message) {
            this.notification = {
                show: true,
                type: type,
                message: message
            };

            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        }
    }
}
</script>
@endpush
@endsection
