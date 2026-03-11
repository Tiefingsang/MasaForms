<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser le cache des rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Permissions pour les formulaires
            'view forms',
            'create forms',
            'edit forms',
            'delete forms',
            'duplicate forms',

            // Permissions pour les réponses
            'view responses',
            'export responses',
            'delete responses',

            // Permissions pour les plans
            'view plans',
            'subscribe to plans',

            // Permissions pour les templates
            'view templates',
            'use templates',
            'create templates',

            // Permissions pour les intégrations
            'view integrations',
            'manage integrations',

            // Permissions admin
            'manage users',
            'manage all forms',
            'view analytics',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Créer les rôles
        $roleUtilisateur = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        // Assigner les permissions au rôle user
        $roleUtilisateur->givePermissionTo([
            'view forms',
            'create forms',
            'edit forms',
            'delete forms',
            'duplicate forms',
            'view responses',
            'export responses',
            'delete responses',
            'view plans',
            'subscribe to plans',
            'view templates',
            'use templates',
            'view integrations',
            'manage integrations',
        ]);

        // Assigner toutes les permissions au rôle admin
        $roleAdmin->givePermissionTo(Permission::all());

        // Créer un admin par défaut
        $admin = User::where('email', 'admin@masadigitale.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }
    }
}
