@extends('layouts.app')

@section('title', 'Changer mon mot de passe')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- En-tête -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center">
                <a href="{{ route('profile.show') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Changer mon mot de passe
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Assurez la sécurité de votre compte en utilisant un mot de passe fort.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('profile.password.update') }}" method="POST" class="divide-y divide-gray-200">
            @csrf
            @method('PUT')

            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-6">
                    <!-- Message de sécurité -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Conseils de sécurité</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Utilisez au moins 8 caractères</li>
                                        <li>Mélangez lettres majuscules et minuscules</li>
                                        <li>Ajoutez des chiffres et caractères spéciaux</li>
                                        <li>Ne réutilisez pas vos anciens mots de passe</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mot de passe actuel -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Mot de passe actuel <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="current_password"
                                   id="current_password"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('current_password') border-red-500 @enderror"
                                   required>
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="new_password"
                                   id="new_password"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('new_password') border-red-500 @enderror"
                                   required>
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Force du mot de passe (indicateur) -->
                    <div x-data="passwordStrength()" x-init="init">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Force du mot de passe</span>
                            <span class="text-sm" :class="strengthClass" x-text="strengthText"></span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300"
                                 :style="'width: ' + strengthPercentage + '%'"
                                 :class="strengthBarClass"></div>
                        </div>
                        <ul class="mt-2 text-xs text-gray-500 space-y-1">
                            <li :class="{'text-green-600': checks.minLength}">
                                <i :class="checks.minLength ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                Au moins 8 caractères
                            </li>
                            <li :class="{'text-green-600': checks.hasUpperCase}">
                                <i :class="checks.hasUpperCase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                Au moins une majuscule
                            </li>
                            <li :class="{'text-green-600': checks.hasLowerCase}">
                                <i :class="checks.hasLowerCase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                Au moins une minuscule
                            </li>
                            <li :class="{'text-green-600': checks.hasNumber}">
                                <i :class="checks.hasNumber ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                Au moins un chiffre
                            </li>
                            <li :class="{'text-green-600': checks.hasSpecial}">
                                <i :class="checks.hasSpecial ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                                Au moins un caractère spécial (!@#$%^&*)
                            </li>
                        </ul>
                    </div>

                    <!-- Confirmation du nouveau mot de passe -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmer le nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="new_password_confirmation"
                                   id="new_password_confirmation"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   required>
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Indicateur de correspondance -->
                    <div x-data="{ password: '', confirm: '' }">
                        <div class="flex items-center space-x-2 text-sm"
                             x-show="password.length > 0 && confirm.length > 0">
                            <template x-if="password === confirm">
                                <span class="text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Les mots de passe correspondent
                                </span>
                            </template>
                            <template x-if="password !== confirm">
                                <span class="text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Les mots de passe ne correspondent pas
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('profile.show') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-key mr-2"></i>
                    Changer le mot de passe
                </button>
            </div>
        </form>
    </div>

    <!-- Dernière connexion -->
    <div class="mt-4 text-center text-sm text-gray-500">
        <i class="fas fa-history mr-1"></i>
        Dernière connexion : {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y à H:i') : 'Première connexion' }}
        <span class="mx-2">•</span>
        <i class="fas fa-network-wired mr-1"></i>
        IP : {{ auth()->user()->last_login_ip ?? 'Non enregistrée' }}
    </div>
</div>

@push('scripts')
<script>
function passwordStrength() {
    return {
        password: '',
        checks: {
            minLength: false,
            hasUpperCase: false,
            hasLowerCase: false,
            hasNumber: false,
            hasSpecial: false
        },

        init() {
            this.$watch('password', value => {
                this.checks.minLength = value.length >= 8;
                this.checks.hasUpperCase = /[A-Z]/.test(value);
                this.checks.hasLowerCase = /[a-z]/.test(value);
                this.checks.hasNumber = /[0-9]/.test(value);
                this.checks.hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
            });

            // Observer l'input du nouveau mot de passe
            document.getElementById('new_password').addEventListener('input', (e) => {
                this.password = e.target.value;
            });
        },

        get strengthPercentage() {
            const validChecks = Object.values(this.checks).filter(Boolean).length;
            return (validChecks / 5) * 100;
        },

        get strengthText() {
            const validChecks = Object.values(this.checks).filter(Boolean).length;
            if (validChecks <= 2) return 'Faible';
            if (validChecks <= 4) return 'Moyen';
            return 'Fort';
        },

        get strengthClass() {
            const validChecks = Object.values(this.checks).filter(Boolean).length;
            if (validChecks <= 2) return 'text-red-600';
            if (validChecks <= 4) return 'text-yellow-600';
            return 'text-green-600';
        },

        get strengthBarClass() {
            const validChecks = Object.values(this.checks).filter(Boolean).length;
            if (validChecks <= 2) return 'bg-red-500';
            if (validChecks <= 4) return 'bg-yellow-500';
            return 'bg-green-500';
        }
    }
}
</script>
@endpush
@endsection
