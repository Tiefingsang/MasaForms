@extends('layouts.app')

@section('title', 'Éditer ' . $form->title)

@section('content')
<div class="max-w-7xl mx-auto" x-data="formBuilder()" x-init="init({{ $form->id }}, {{ json_encode($form) }}, {{ json_encode($form->fields) }})">

    <!-- Styles pour les animations -->
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .notification-enter {
            animation: slideIn 0.3s ease-out forwards;
        }
        .notification-leave {
            animation: slideOut 0.3s ease-in forwards;
        }
    </style>

    <!-- Notification -->
    <template x-if="notification.show">
        <div class="fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg z-50 min-w-[300px]"
             :class="notification.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
             x-init="$el.classList.add('notification-enter')"
             x-transition:leave="notification-leave">
            <div class="flex items-center">
                <i :class="notification.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" class="mr-2"></i>
                <span x-text="notification.message"></span>
            </div>
        </div>
    </template>

    <!-- Navigation -->
    <div class="mb-6">
        <nav class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('forms.index') }}" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900" x-text="form.title"></h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="form.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                    <span x-text="form.is_active ? 'Actif' : 'Inactif'"></span>
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <a :href="form.public_url" target="_blank"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Aperçu
                </a>
                <button @click="saveForm()"
                        :disabled="saving"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="saving ? 'Sauvegarde...' : 'Sauvegarder'"></span>
                </button>
            </div>
        </nav>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Sidebar avec les types de champs -->
        <div class="col-span-3">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Types de champs</h3>
                <div class="space-y-2">
                    <button @click="addField('text')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-font w-5 text-gray-400"></i>
                        <span class="ml-2">Texte court</span>
                    </button>
                    <button @click="addField('textarea')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-paragraph w-5 text-gray-400"></i>
                        <span class="ml-2">Paragraphe</span>
                    </button>
                    <button @click="addField('email')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="ml-2">Email</span>
                    </button>
                    <button @click="addField('tel')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="ml-2">Téléphone</span>
                    </button>
                    <button @click="addField('number')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-calculator w-5 text-gray-400"></i>
                        <span class="ml-2">Nombre</span>
                    </button>
                    <button @click="addField('date')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-calendar w-5 text-gray-400"></i>
                        <span class="ml-2">Date</span>
                    </button>
                    <button @click="addField('radio')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-dot-circle w-5 text-gray-400"></i>
                        <span class="ml-2">Choix unique</span>
                    </button>
                    <button @click="addField('checkbox')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-check-square w-5 text-gray-400"></i>
                        <span class="ml-2">Cases à cocher</span>
                    </button>
                    <button @click="addField('select')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <i class="fas fa-list w-5 text-gray-400"></i>
                        <span class="ml-2">Liste déroulante</span>
                    </button>
                </div>

                <!-- Paramètres du formulaire -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Paramètres</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="form.accepts_responses" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Accepter les réponses</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="form.is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Formulaire actif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="form.is_public" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Public</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="form.show_progress_bar" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Afficher la barre de progression</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone de construction -->
        <div class="col-span-9">
            <div class="bg-white shadow rounded-lg p-6">
                <!-- Aperçu du titre -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900" x-text="form.title"></h2>
                    <p class="mt-2 text-gray-600" x-text="form.description" x-show="form.description"></p>
                </div>

                <!-- Liste des champs -->
                <div class="space-y-4" id="fields-container">
                    <template x-for="(field, index) in fields" :key="field.id">
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors"
                             :class="{ 'border-blue-500': selectedField === index }"
                             @click="selectedField = index">

                            <!-- En-tête du champ -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center flex-1">
                                    <i class="fas fa-grip-vertical text-gray-400 cursor-move mr-2"></i>
                                    <span class="text-sm font-medium text-gray-900" x-text="field.label || 'Nouveau champ'"></span>
                                    <span class="ml-2 text-xs text-red-500" x-show="field.is_required">*</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button @click="editField(index)" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="duplicateField(index)" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button @click="deleteField(index)" class="text-gray-400 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Aperçu du champ -->
                            <div class="ml-7">
                                <div x-show="field.type === 'text' || field.type === 'email' || field.type === 'tel' || field.type === 'number'">
                                    <input type="text"
                                           :placeholder="field.placeholder || 'Réponse...'"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           disabled>
                                </div>

                                <div x-show="field.type === 'textarea'">
                                    <textarea rows="3"
                                              :placeholder="field.placeholder || 'Réponse...'"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                              disabled></textarea>
                                </div>

                                <div x-show="field.type === 'date'">
                                    <input type="date"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           disabled>
                                </div>

                                <div x-show="field.type === 'radio' || field.type === 'select' || field.type === 'checkbox'">
                                    <template x-if="field.options && field.options.length > 0">
                                        <div class="mt-2 space-y-2">
                                            <template x-for="(option, optIndex) in field.options" :key="optIndex">
                                                <div class="flex items-center">
                                                    <template x-if="field.type === 'radio'">
                                                        <input type="radio" disabled class="h-4 w-4 border-gray-300 text-blue-600">
                                                    </template>
                                                    <template x-if="field.type === 'checkbox'">
                                                        <input type="checkbox" disabled class="h-4 w-4 rounded border-gray-300 text-blue-600">
                                                    </template>
                                                    <span class="ml-2 text-sm text-gray-600" x-text="option"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Message si aucun champ -->
                    <div x-show="fields.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                        <i class="fas fa-arrow-up text-3xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">Cliquez sur un type de champ pour commencer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'édition de champ -->
    <div x-show="showFieldModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <span x-text="editingFieldIndex === null ? 'Ajouter un champ' : 'Modifier le champ'"></span>
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <!-- Label -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label du champ</label>
                            <input type="text" x-model="editingField.label"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Placeholder -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Placeholder (optionnel)</label>
                            <input type="text" x-model="editingField.placeholder"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Texte d'aide -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Texte d'aide (optionnel)</label>
                            <input type="text" x-model="editingField.help_text"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Options pour radio/checkbox/select -->
                        <template x-if="['radio', 'checkbox', 'select'].includes(editingField.type)">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Options</label>
                                <div class="mt-2 space-y-2">
                                    <template x-for="(option, index) in editingField.options" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" x-model="editingField.options[index]"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <button @click="editingField.options.splice(index, 1)" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="editingField.options.push('')"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-plus mr-1"></i>
                                        Ajouter une option
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Champ obligatoire -->
                        <div class="flex items-center">
                            <input type="checkbox" x-model="editingField.is_required"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Champ obligatoire</span>
                        </div>

                        <!-- Validation longueur -->
                        <template x-if="editingField.type === 'text' || editingField.type === 'textarea'">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Longueur min</label>
                                    <input type="number" x-model="editingField.min_length"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Longueur max</label>
                                    <input type="number" x-model="editingField.max_length"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Boutons -->
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse bg-gray-50">
                    <button @click="saveField()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Enregistrer
                    </button>
                    <button @click="showFieldModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
function formBuilder() {
    return {
        formId: null,
        form: {
            title: '',
            description: '',
            is_active: true,
            is_public: true,
            accepts_responses: true,
            show_progress_bar: true,
            public_url: ''
        },
        fields: [],
        selectedField: null,
        showFieldModal: false,
        editingField: null,
        editingFieldIndex: null,
        saving: false,
        notification: {
            show: false,
            type: 'success',
            message: ''
        },

        init(formId, formData, fieldsData) {
            this.formId = formId;
            this.form = formData;
            this.fields = fieldsData;
            setTimeout(() => this.initSortable(), 100);
        },

        initSortable() {
            const container = document.getElementById('fields-container');
            if (!container) return;

            new Sortable(container, {
                animation: 150,
                handle: '.fa-grip-vertical',
                onEnd: (evt) => {
                    const item = this.fields.splice(evt.oldIndex, 1)[0];
                    this.fields.splice(evt.newIndex, 0, item);
                }
            });
        },

        addField(type) {
            this.editingField = {
                id: Date.now(),
                type: type,
                label: '',
                placeholder: '',
                help_text: '',
                is_required: false,
                options: type === 'radio' || type === 'checkbox' || type === 'select' ? ['Option 1'] : null,
                min_length: null,
                max_length: null
            };
            this.editingFieldIndex = null;
            this.showFieldModal = true;
        },

        editField(index) {
            this.editingField = {...this.fields[index]};
            this.editingFieldIndex = index;
            this.showFieldModal = true;
        },

        saveField() {
            if (!this.editingField.label) {
                this.showNotification('error', 'Le label est obligatoire');
                return;
            }

            if (this.editingFieldIndex === null) {
                this.fields.push(this.editingField);
            } else {
                this.fields[this.editingFieldIndex] = this.editingField;
            }
            this.showFieldModal = false;
        },

        duplicateField(index) {
            const field = {...this.fields[index]};
            field.id = Date.now();
            field.label = field.label + ' (copie)';
            this.fields.splice(index + 1, 0, field);
            this.showNotification('success', 'Champ dupliqué');
        },

        deleteField(index) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                this.fields.splice(index, 1);
                this.showNotification('success', 'Champ supprimé');
            }
        },

        async saveForm() {
            if (this.saving) return;

            this.saving = true;
            try {
                const response = await fetch(`/forms/${this.formId}/fields`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        fields: this.fields,
                        settings: this.form
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.showNotification('success', '✅ Formulaire sauvegardé avec succès !');
                } else {
                    this.showNotification('error', data.message || '❌ Erreur lors de la sauvegarde');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('error', '❌ Erreur de connexion');
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
