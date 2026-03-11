<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ===========================================
// CONTROLLERS AUTH
// ===========================================
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ProfileController;

// ===========================================
// CONTROLLERS PRINCIPAUX
// ===========================================
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\WebhookController;

// ===========================================
// ROUTES PUBLIQUES (Accessibles à tous)
// ===========================================

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Page de présentation des fonctionnalités
Route::view('/features', 'pages.features')->name('features');

// Page de tarifs
Route::get('/pricing', [PlanController::class, 'public'])->name('pricing');

// Page à propos
Route::view('/about', 'pages.about')->name('about');

// Page de contact
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Page des templates publics
Route::get('/templates/public', [TemplateController::class, 'public'])->name('templates.public');
Route::get('/templates/public/{slug}', [TemplateController::class, 'publicShow'])->name('templates.public.show');

// ===========================================
// ROUTES DES FORMULAIRES PUBLICS
// ===========================================
Route::prefix('f')->name('forms.public.')->group(function () {
    // Afficher un formulaire public
    Route::get('/{slug}', [PublicFormController::class, 'show'])->name('show');

    // Soumettre un formulaire public
    Route::post('/{slug}', [PublicFormController::class, 'submit'])->name('submit');

    // Page de remerciement
    Route::get('/{slug}/thank-you', [PublicFormController::class, 'thankYou'])->name('thank-you');

    // Upload de fichier pour formulaire public
    Route::post('/{slug}/upload', [PublicFormController::class, 'upload'])->name('upload');
});

// ===========================================
// ROUTES D'AUTHENTIFICATION (Non connectés)
// ===========================================
Route::middleware('guest')->group(function () {

    // Inscription
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Mot de passe oublié
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Réinitialisation du mot de passe
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ===========================================
// ROUTES PROTÉGÉES (Utilisateurs connectés)
// ===========================================
Route::middleware(['auth', 'check.subscription'])->group(function () {

    // =======================================
    // DÉCONNEXION
    // =======================================
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // =======================================
    // DASHBOARD
    // =======================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');

    // =======================================
    // GESTION DES FORMULAIRES (CRUD complet)
    // =======================================
    Route::resource('forms', FormController::class)->except(['show']);
    Route::get('/forms/trashed', [FormController::class, 'trashed'])->name('forms.trashed');
    Route::post('/forms/{form}/restore', [FormController::class, 'restore'])->name('forms.restore');
    Route::delete('/forms/{form}/force-delete', [FormController::class, 'forceDelete'])->name('forms.force-delete');

    // Construction du formulaire (champs)
    Route::prefix('forms/{form}')->name('forms.')->group(function () {
        // Sauvegarder les champs
        Route::post('/fields', [FormController::class, 'saveFields'])->name('fields.save');

        // Gestion des champs individuels
        Route::post('/fields/order', [FormController::class, 'updateFieldsOrder'])->name('fields.order');
        Route::delete('/fields/{field}', [FormController::class, 'deleteField'])->name('fields.delete');

        // Dupliquer le formulaire
        Route::post('/duplicate', [FormController::class, 'duplicate'])->name('duplicate');

        // Changer le statut
        Route::patch('/toggle-status', [FormController::class, 'toggleStatus'])->name('toggle-status');

        // Paramètres du formulaire
        Route::get('/settings', [FormController::class, 'settings'])->name('settings');
        Route::put('/settings', [FormController::class, 'updateSettings'])->name('settings.update');

        // Design du formulaire
        Route::get('/design', [FormController::class, 'design'])->name('design');
        Route::post('/design', [FormController::class, 'updateDesign'])->name('design.update');

        // Partager le formulaire
        Route::get('/share', [FormController::class, 'share'])->name('share');
        Route::post('/share/regenerate-link', [FormController::class, 'regenerateLink'])->name('regenerate-link');
    });

    // =======================================
    // GESTION DES RÉPONSES
    // =======================================
    Route::prefix('forms/{form}/responses')->name('forms.responses.')->group(function () {
        // Liste des réponses
        Route::get('/', [ResponseController::class, 'index'])->name('index');

        // Vue d'une réponse spécifique
        Route::get('/{response}', [ResponseController::class, 'show'])->name('show');

        // Supprimer une réponse
        Route::delete('/{response}', [ResponseController::class, 'destroy'])->name('destroy');

        // Exports
        Route::get('/export/csv', [ResponseController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/excel', [ResponseController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ResponseController::class, 'exportPdf'])->name('export.pdf');

        // Actions groupées
        Route::post('/bulk-delete', [ResponseController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-export', [ResponseController::class, 'bulkExport'])->name('bulk-export');

        // Statistiques du formulaire
        Route::get('/statistics', [StatisticController::class, 'formStats'])->name('statistics');
    });

    // =======================================
    // GESTION DES PLANS ET ABONNEMENTS
    // =======================================
    Route::prefix('plans')->name('plans.')->group(function () {
        // Liste des plans
        Route::get('/', [PlanController::class, 'index'])->name('index');

        // Détail d'un plan
        Route::get('/{slug}', [PlanController::class, 'show'])->name('show');

        // S'abonner à un plan
        Route::post('/{slug}/subscribe', [PlanController::class, 'subscribe'])->name('subscribe');

        // Changer de plan
        Route::post('/{slug}/switch', [PlanController::class, 'switch'])->name('switch');

        // Annuler l'abonnement
        Route::post('/cancel', [PlanController::class, 'cancel'])->name('cancel');

        // Réactiver l'abonnement
        Route::post('/resume', [PlanController::class, 'resume'])->name('resume');

        // Factures
        Route::get('/invoices', [PlanController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}/download', [PlanController::class, 'downloadInvoice'])->name('invoice.download');
    });

    // =======================================
    // GESTION DES PAIEMENTS
    // =======================================
    Route::prefix('payment')->name('payment.')->group(function () {
        // Checkout
        Route::get('/checkout/{subscription}', [PaymentController::class, 'checkout'])->name('checkout');

        // Traitement du paiement
        Route::post('/process', [PaymentController::class, 'process'])->name('process');

        // Confirmation
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');

        // Méthodes de paiement
        Route::get('/methods', [PaymentController::class, 'methods'])->name('methods');
        Route::post('/methods/add', [PaymentController::class, 'addMethod'])->name('methods.add');
        Route::delete('/methods/{method}', [PaymentController::class, 'removeMethod'])->name('methods.remove');

        // Historique
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
    });

    // =======================================
    // GESTION DES TEMPLATES
    // =======================================
    Route::prefix('templates')->name('templates.')->group(function () {
        // Mes templates
        Route::get('/', [TemplateController::class, 'index'])->name('index');

        // Créer un template à partir d'un formulaire
        Route::get('/create', [TemplateController::class, 'create'])->name('create');
        Route::post('/', [TemplateController::class, 'store'])->name('store');

        // Afficher un template
        Route::get('/{slug}', [TemplateController::class, 'show'])->name('show');

        // Appliquer un template
        Route::post('/{slug}/apply', [TemplateController::class, 'apply'])->name('apply');

        // Modifier un template
        Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [TemplateController::class, 'update'])->name('update');

        // Supprimer un template
        Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
    });

    // =======================================
    // GESTION DES INTÉGRATIONS
    // =======================================
    Route::prefix('integrations')->name('integrations.')->group(function () {
        // Liste des intégrations
        Route::get('/', [IntegrationController::class, 'index'])->name('index');

        // Configurer une intégration
        Route::get('/configure/{type}', [IntegrationController::class, 'configure'])->name('configure');
        Route::post('/store', [IntegrationController::class, 'store'])->name('store');

        // Gérer une intégration spécifique
        Route::prefix('{integration}')->group(function () {
            Route::get('/edit', [IntegrationController::class, 'edit'])->name('edit');
            Route::put('/', [IntegrationController::class, 'update'])->name('update');
            Route::post('/test', [IntegrationController::class, 'test'])->name('test');
            Route::post('/toggle', [IntegrationController::class, 'toggle'])->name('toggle');
            Route::delete('/', [IntegrationController::class, 'destroy'])->name('destroy');
        });

        // Webhooks
        Route::get('/webhooks', [WebhookController::class, 'index'])->name('webhooks');
        Route::post('/webhooks', [WebhookController::class, 'store'])->name('webhooks.store');
        Route::delete('/webhooks/{webhook}', [WebhookController::class, 'destroy'])->name('webhooks.destroy');
    });

    // =======================================
    // GESTION DU PROFIL UTILISATEUR
    // =======================================
    Route::prefix('profile')->name('profile.')->group(function () {
        // Afficher le profil
        Route::get('/', [ProfileController::class, 'show'])->name('show');

        // Modifier le profil
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        // Changer le mot de passe
        Route::get('/password', [ProfileController::class, 'passwordForm'])->name('password');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

        // Avatar
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');
        Route::delete('/avatar', [ProfileController::class, 'removeAvatar'])->name('avatar.remove');

        // Notification settings
        Route::get('/notifications', [ProfileController::class, 'notificationSettings'])->name('notifications');
        Route::put('/notifications', [ProfileController::class, 'updateNotifications'])->name('notifications.update');
    });

    // =======================================
    // GESTION DES PARAMÈTRES
    // =======================================
    Route::prefix('settings')->name('settings.')->group(function () {
        // Paramètres généraux
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');

        // Préférences
        Route::get('/preferences', [SettingController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [SettingController::class, 'updatePreferences'])->name('preferences.update');

        // Équipe (pour les plans Business)
        Route::middleware('can:manage-team')->group(function () {
            Route::get('/team', [TeamController::class, 'index'])->name('team');
            Route::post('/team/invite', [TeamController::class, 'invite'])->name('team.invite');
            Route::delete('/team/{user}', [TeamController::class, 'remove'])->name('team.remove');
            Route::patch('/team/{user}/role', [TeamController::class, 'updateRole'])->name('team.role');
        });

        // API Keys
        Route::get('/api', [ApiKeyController::class, 'index'])->name('api');
        Route::post('/api', [ApiKeyController::class, 'store'])->name('api.store');
        Route::delete('/api/{key}', [ApiKeyController::class, 'destroy'])->name('api.destroy');

        // Facturation
        Route::get('/billing', [SettingController::class, 'billing'])->name('billing');
        Route::post('/billing/update-card', [SettingController::class, 'updateCard'])->name('billing.card');

        // Export des données
        Route::get('/export-data', [SettingController::class, 'exportData'])->name('export-data');
        Route::post('/export-data', [SettingController::class, 'requestDataExport'])->name('export-data.request');

        // Suppression du compte
        Route::delete('/delete-account', [SettingController::class, 'deleteAccount'])->name('delete-account');
    });

    // =======================================
    // NOTIFICATIONS
    // =======================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // =======================================
    // STATISTIQUES GLOBALES
    // =======================================
    Route::get('/statistics', [StatisticController::class, 'global'])->name('statistics');
    Route::get('/statistics/export', [StatisticController::class, 'export'])->name('statistics.export');
});

// ===========================================
// ROUTES ADMIN (pour les administrateurs)
// ===========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard admin
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/impersonate', [App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestion des plans
    Route::resource('plans', App\Http\Controllers\Admin\PlanController::class);
    Route::post('/plans/{plan}/toggle', [App\Http\Controllers\Admin\PlanController::class, 'toggle'])->name('plans.toggle');

    // Gestion des templates
    Route::resource('templates', App\Http\Controllers\Admin\TemplateController::class);
    Route::post('/templates/{template}/toggle-premium', [App\Http\Controllers\Admin\TemplateController::class, 'togglePremium'])->name('templates.toggle-premium');

    // Gestion des paiements
    Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/refund', [App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('payments.refund');

    // Statistiques globales
    Route::get('/statistics', [App\Http\Controllers\Admin\StatisticController::class, 'index'])->name('statistics');

    // Configuration système
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Logs système
    Route::get('/logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');
    Route::get('/logs/{file}', [App\Http\Controllers\Admin\LogController::class, 'show'])->name('logs.show');
    Route::delete('/logs/{file}', [App\Http\Controllers\Admin\LogController::class, 'destroy'])->name('logs.destroy');
});

// ===========================================
// ROUTES API (pour les intégrations externes)
// ===========================================
Route::prefix('api/v1')->name('api.')->group(function () {

    // Webhooks publics (pas d'authentification)
    Route::post('/webhooks/{provider}', [WebhookController::class, 'handle'])->name('webhooks.handle');

    // API authentifiée par clé API
    Route::middleware('auth:api')->group(function () {
        // Récupérer les formulaires
        Route::get('/forms', [App\Http\Controllers\Api\FormController::class, 'index']);
        Route::get('/forms/{form}', [App\Http\Controllers\Api\FormController::class, 'show']);
        Route::get('/forms/{form}/responses', [App\Http\Controllers\Api\ResponseController::class, 'index']);

        // Soumettre une réponse via API
        Route::post('/forms/{form}/responses', [App\Http\Controllers\Api\PublicFormController::class, 'submit']);
    });
});

// ===========================================
// ROUTES DE TEST (à supprimer en production)
// ===========================================
if (app()->environment('local')) {
    Route::get('/test/mail', function () {
        return view('test.mail');
    });
}

// ===========================================
// FALLBACK ROUTE (page 404 personnalisée)
// ===========================================
Route::fallback(function () {
    return view('errors.404');
});
