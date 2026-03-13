<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->title }} - Masadigitale Forms</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen"
      :style="{ backgroundColor: formBackgroundColor }"
      x-data="publicForm()"
      x-init="init({{ $form->id }}, '{{ $form->slug }}', {{ json_encode($form->fields) }})"
      style="background-color: {{ $form->background_color }};">

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Logo (si pas supprimé) -->
            @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Masadigitale Forms" class="h-12 mx-auto">
                </div>
            @endif

            <!-- Formulaire -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden"
                 :style="{ borderTopColor: formPrimaryColor }"
                 style="border-top: 4px solid {{ $form->primary_color }};">

                <!-- En-tête -->
                <div class="px-6 py-8 text-center border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900" :style="{ color: formPrimaryColor }" style="color: {{ $form->primary_color }};">
                        {{ $form->title }}
                    </h1>
                    @if($form->description)
                        <p class="mt-4 text-gray-600">{{ $form->description }}</p>
                    @endif
                </div>

                <!-- Barre de progression -->
                @if($form->show_progress_bar)
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                            <span>Progression</span>
                            <span x-text="progress + '%'" class="font-medium" :style="{ color: formPrimaryColor }"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full transition-all duration-300"
                                 :style="'width: ' + progress + '%; background-color: ' + formPrimaryColor">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Messages -->
                <div x-show="message" x-cloak class="px-6 py-4 animate-slideIn"
                     :class="messageType === 'success' ? 'bg-green-50 border-b border-green-200' : 'bg-red-50 border-b border-red-200'">
                    <div class="flex items-center">
                        <i :class="messageType === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'" class="mr-2"></i>
                        <p class="text-sm" :class="messageType === 'success' ? 'text-green-700' : 'text-red-700'"
                           x-text="message"></p>
                    </div>
                </div>

                <!-- Champs du formulaire -->
                <form @submit.prevent="submitForm" class="px-6 py-8 space-y-6">
                    @csrf

                    <template x-for="(field, index) in fields" :key="field.id">
                        <div class="space-y-2">
                            <label :for="'field_' + field.id" class="block text-sm font-medium text-gray-700">
                                <span x-text="field.label"></span>
                                <span x-show="field.is_required" class="text-red-500 ml-1">*</span>
                            </label>

                            <!-- Texte court / Email / Téléphone / Nombre -->
                            <template x-if="['text', 'email', 'tel', 'number'].includes(field.type)">
                                <input :type="field.type"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :placeholder="field.placeholder"
                                       :required="field.is_required"
                                       :min="field.min_length"
                                       :max="field.max_length"
                                       @input="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       :class="{ 'border-red-300': errors[field.id] }">
                            </template>

                            <!-- Paragraphe -->
                            <template x-if="field.type === 'textarea'">
                                <textarea :id="'field_' + field.id"
                                          :name="'field_' + field.id"
                                          x-model="responses[field.id]"
                                          :placeholder="field.placeholder"
                                          :required="field.is_required"
                                          :minlength="field.min_length"
                                          :maxlength="field.max_length"
                                          @input="updateProgress"
                                          rows="4"
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          :class="{ 'border-red-300': errors[field.id] }"></textarea>
                            </template>

                            <!-- Date -->
                            <template x-if="field.type === 'date'">
                                <input type="date"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :required="field.is_required"
                                       @change="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       :class="{ 'border-red-300': errors[field.id] }">
                            </template>

                            <!-- Radio (choix unique) -->
                            <template x-if="field.type === 'radio'">
                                <div class="space-y-2 mt-2">
                                    <template x-for="(option, idx) in (field.options || [])" :key="idx">
                                        <div class="flex items-center">
                                            <input type="radio"
                                                   :id="'field_' + field.id + '_' + idx"
                                                   :name="'field_' + field.id"
                                                   :value="option"
                                                   x-model="responses[field.id]"
                                                   :required="field.is_required"
                                                   @change="updateProgress"
                                                   class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label :for="'field_' + field.id + '_' + idx"
                                                   class="ml-3 block text-sm text-gray-700"
                                                   x-text="option"></label>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Checkbox (cases à cocher) -->
                            <template x-if="field.type === 'checkbox'">
                                <div class="space-y-2 mt-2">
                                    <template x-for="(option, idx) in (field.options || [])" :key="idx">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   :id="'field_' + field.id + '_' + idx"
                                                   :name="'field_' + field.id + '[]'"
                                                   :value="option"
                                                   x-model="checkboxValues[field.id]"
                                                   @change="updateProgress"
                                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label :for="'field_' + field.id + '_' + idx"
                                                   class="ml-3 block text-sm text-gray-700"
                                                   x-text="option"></label>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Select (liste déroulante) -->
                            <template x-if="field.type === 'select'">
                                <select :id="'field_' + field.id"
                                        :name="'field_' + field.id"
                                        x-model="responses[field.id]"
                                        :required="field.is_required"
                                        @change="updateProgress"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        :class="{ 'border-red-300': errors[field.id] }">
                                    <option value="">Sélectionnez une option</option>
                                    <template x-for="(option, idx) in (field.options || [])" :key="idx">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>
                            </template>

                            <!-- Texte d'aide -->
                            <p x-show="field.help_text" class="mt-1 text-sm text-gray-500" x-text="field.help_text"></p>

                            <!-- Message d'erreur -->
                            <p x-show="errors[field.id]" class="mt-1 text-sm text-red-600" x-text="errors[field.id]"></p>
                        </div>
                    </template>

                    <!-- Bouton de soumission -->
                    <div class="pt-4">
                        <button type="submit"
                                :disabled="submitting"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity"
                                :style="{ backgroundColor: formPrimaryColor }">
                            <svg x-show="submitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Envoi en cours...' : 'Envoyer'"></span>
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                    <div class="px-6 py-4 bg-gray-50 text-center text-xs text-gray-500 border-t border-gray-200">
                        Propulsé par <a href="{{ url('/') }}" class="text-blue-600 hover:underline font-medium">Masadigitale Forms</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function publicForm() {
        return {
            // Données du formulaire
            formId: null,
            slug: '',
            fields: [],
            formPrimaryColor: '{{ $form->primary_color }}',
            formBackgroundColor: '{{ $form->background_color }}',

            // Réponses
            responses: {},
            checkboxValues: {},
            errors: {},

            // États
            submitting: false,
            message: '',
            messageType: 'success',
            progress: 0,

            /**
             * Initialisation du composant
             */
            init(formId, slug, fields) {
                this.formId = formId;
                this.slug = slug;

                // S'assurer que fields est un tableau
                try {
                    if (typeof fields === 'string') {
                        this.fields = JSON.parse(fields);
                    } else {
                        this.fields = fields || [];
                    }
                } catch (e) {
                    console.error('Erreur parsing fields:', e);
                    this.fields = [];
                }

                // Initialiser les réponses
                this.fields.forEach(field => {
                    if (field.type === 'checkbox') {
                        this.checkboxValues[field.id] = [];
                    } else {
                        this.responses[field.id] = '';
                    }
                });

                // Mettre à jour la progression initiale
                this.updateProgress();

                console.log('Formulaire initialisé avec succès', {
                    id: this.formId,
                    slug: this.slug,
                    fields: this.fields.length
                });
            },

            /**
             * Met à jour la barre de progression
             */
            updateProgress() {
                const requiredFields = this.fields.filter(f => f.is_required);
                const total = requiredFields.length;

                if (total === 0) {
                    this.progress = 100;
                    return;
                }

                let filled = 0;

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (this.checkboxValues[field.id] && this.checkboxValues[field.id].length > 0) {
                            filled++;
                        }
                    } else {
                        const value = this.responses[field.id];
                        if (value && value.toString().trim() !== '') {
                            filled++;
                        }
                    }
                });

                this.progress = Math.round((filled / total) * 100);
            },

            /**
             * Soumet le formulaire
             */
            async submitForm() {
                // Validation de base
                if (this.submitting) return;

                this.submitting = true;
                this.message = '';
                this.errors = {};

                try {
                    // Préparer les données
                    const formData = {};

                    this.fields.forEach(field => {
                        if (field.type === 'checkbox') {
                            formData['field_' + field.id] = this.checkboxValues[field.id] || [];
                        } else {
                            formData['field_' + field.id] = this.responses[field.id] || '';
                        }
                    });

                    console.log('Envoi des données vers /f/' + this.slug, formData);

                    // Envoyer la requête
                    const response = await fetch('/f/' + this.slug, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    // Analyser la réponse
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        console.error('Réponse non-JSON reçue');
                        throw new Error('Le serveur a répondu avec un format invalide');
                    }

                    if (response.ok && data.success) {
                        // Succès
                        this.message = data.message || 'Merci pour votre réponse !';
                        this.messageType = 'success';

                        // Vider le formulaire
                        this.fields.forEach(field => {
                            if (field.type === 'checkbox') {
                                this.checkboxValues[field.id] = [];
                            } else {
                                this.responses[field.id] = '';
                            }
                        });
                        this.updateProgress();

                        // Redirection si spécifiée
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 2000);
                        }
                    } else {
                        // Erreur
                        if (data.errors) {
                            this.errors = data.errors;
                        }
                        this.message = data.error || 'Une erreur est survenue lors de l\'envoi';
                        this.messageType = 'error';
                    }
                } catch (error) {
                    console.error('Erreur lors de la soumission:', error);
                    this.message = 'Erreur de connexion au serveur';
                    this.messageType = 'error';
                } finally {
                    this.submitting = false;
                }
            }
        }
    }
    </script>
</body>
</html>
