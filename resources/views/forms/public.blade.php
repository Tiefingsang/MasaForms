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
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased"
      style="background-color: {{ $form->background_color }};">

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8"
         x-data="publicForm()"
         x-init="init({{ $form->id }}, {{ $form->fields }})">

        <div class="max-w-3xl mx-auto">
            <!-- Logo (si pas supprimé) -->
            @if(!auth()->check() || !auth()->user()?->currentPlan()?->first()?->has_remove_masadigitale_logo)
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Masadigitale Forms" class="h-12 mx-auto">
                </div>
            @endif

            <!-- Formulaire -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden"
                 style="border-top: 4px solid {{ $form->primary_color }};">

                <!-- En-tête -->
                <div class="px-6 py-8 text-center border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900" style="color: {{ $form->primary_color }};">
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
                            <span x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300"
                                 :style="'width: ' + progress + '%'"
                                 :style="{ backgroundColor: '{{ $form->primary_color }}' }">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Messages flash -->
                <div x-show="message" x-cloak class="px-6 py-4"
                     :class="messageType === 'success' ? 'bg-green-50' : 'bg-red-50'">
                    <p class="text-sm"
                       :class="messageType === 'success' ? 'text-green-800' : 'text-red-800'"
                       x-text="message"></p>
                </div>

                <!-- Champs du formulaire -->
                <form @submit.prevent="submitForm" class="px-6 py-8 space-y-6">
                    @csrf

                    <template x-for="(field, index) in fields" :key="field.id">
                        <div class="space-y-2" x-show="isFieldVisible(field)">
                            <label :for="'field_' + field.id" class="block text-sm font-medium text-gray-700">
                                <span x-text="field.label"></span>
                                <span x-show="field.is_required" class="text-red-500 ml-1">*</span>
                            </label>

                            <!-- Texte court -->
                            <template x-if="field.type === 'text'">
                                <input type="text"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :placeholder="field.placeholder"
                                       :required="field.is_required"
                                       @input="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </template>

                            <!-- Paragraphe -->
                            <template x-if="field.type === 'textarea'">
                                <textarea :id="'field_' + field.id"
                                          :name="'field_' + field.id"
                                          x-model="responses[field.id]"
                                          :placeholder="field.placeholder"
                                          :required="field.is_required"
                                          @input="updateProgress"
                                          rows="4"
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </template>

                            <!-- Email -->
                            <template x-if="field.type === 'email'">
                                <input type="email"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :placeholder="field.placeholder"
                                       :required="field.is_required"
                                       @input="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </template>

                            <!-- Téléphone -->
                            <template x-if="field.type === 'tel'">
                                <input type="tel"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :placeholder="field.placeholder"
                                       :required="field.is_required"
                                       @input="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </template>

                            <!-- Nombre -->
                            <template x-if="field.type === 'number'">
                                <input type="number"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :placeholder="field.placeholder"
                                       :required="field.is_required"
                                       :min="field.min_length"
                                       :max="field.max_length"
                                       @input="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </template>

                            <!-- Date -->
                            <template x-if="field.type === 'date'">
                                <input type="date"
                                       :id="'field_' + field.id"
                                       :name="'field_' + field.id"
                                       x-model="responses[field.id]"
                                       :required="field.is_required"
                                       @change="updateProgress"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </template>

                            <!-- Radio (choix unique) -->
                            <template x-if="field.type === 'radio'">
                                <div class="space-y-2">
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
                                                   class="ml-3 text-sm text-gray-700"
                                                   x-text="option"></label>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Checkbox (cases à cocher) -->
                            <template x-if="field.type === 'checkbox'">
                                <div class="space-y-2">
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
                                                   class="ml-3 text-sm text-gray-700"
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
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50"
                                :style="{ backgroundColor: '{{ $form->primary_color }}' }">
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
                    <div class="px-6 py-4 bg-gray-50 text-center text-xs text-gray-500">
                        Propulsé par <a href="{{ url('/') }}" class="text-blue-600 hover:underline">Masadigitale Forms</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function publicForm() {
        return {
            formId: null,
            fields: [],
            responses: {},
            checkboxValues: {},
            errors: {},
            submitting: false,
            message: '',
            messageType: 'success',
            progress: 0,

            init(formId, fields) {
                this.formId = formId;
                this.fields = JSON.parse(JSON.stringify(fields));

                // Initialiser les réponses
                this.fields.forEach(field => {
                    if (field.type === 'checkbox') {
                        this.checkboxValues[field.id] = [];
                    } else {
                        this.responses[field.id] = '';
                    }
                });

                this.updateProgress();
            },

            isFieldVisible(field) {
                // Ici vous pouvez ajouter la logique des champs conditionnels
                return true;
            },

            updateProgress() {
                let total = this.fields.filter(f => f.is_required).length;
                let filled = 0;

                this.fields.forEach(field => {
                    if (field.is_required) {
                        if (field.type === 'checkbox') {
                            if (this.checkboxValues[field.id] && this.checkboxValues[field.id].length > 0) {
                                filled++;
                            }
                        } else {
                            if (this.responses[field.id] && this.responses[field.id].toString().trim() !== '') {
                                filled++;
                            }
                        }
                    }
                });

                this.progress = total > 0 ? Math.round((filled / total) * 100) : 0;
            },

            async submitForm() {
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

                    const response = await fetch('/f/' + this.formId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.message = data.message || 'Merci pour votre réponse !';
                        this.messageType = 'success';

                        // Redirection si spécifiée
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Réinitialiser le formulaire après 3 secondes
                            setTimeout(() => {
                                this.fields.forEach(field => {
                                    if (field.type === 'checkbox') {
                                        this.checkboxValues[field.id] = [];
                                    } else {
                                        this.responses[field.id] = '';
                                    }
                                });
                                this.message = '';
                                this.updateProgress();
                            }, 3000);
                        }
                    } else if (data.errors) {
                        // Gérer les erreurs de validation
                        Object.keys(data.errors).forEach(key => {
                            const fieldId = key.replace('field_', '');
                            this.errors[fieldId] = data.errors[key][0];
                        });
                        this.message = 'Veuillez corriger les erreurs';
                        this.messageType = 'error';
                    } else {
                        this.message = data.error || 'Une erreur est survenue';
                        this.messageType = 'error';
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    this.message = 'Erreur de connexion';
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
