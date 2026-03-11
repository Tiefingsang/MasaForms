<?php
// database/seeders/PlanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run()
    {
        DB::table('plans')->insert([
            [
                'name' => 'Gratuit',
                'slug' => 'free',
                'description' => 'Pour démarrer avec des fonctionnalités de base',
                'max_forms' => 3,
                'max_responses_per_form' => 100,
                'has_advanced_stats' => false,
                'has_export_csv' => true,
                'has_export_excel' => false,
                'has_export_pdf' => false,
                'has_multi_users' => false,
                'has_automations' => false,
                'has_custom_logo' => false,
                'has_remove_masadigitale_logo' => false,
                'has_whatsapp_integration' => false,
                'has_email_notifications' => false,
                'has_api_access' => false,
                'has_templates' => false,
                'price_monthly' => 0,
                'price_yearly' => 0,
                'sort_order' => 1,
                'is_popular' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Pour les professionnels ayant besoin de plus de fonctionnalités',
                'max_forms' => 50,
                'max_responses_per_form' => 5000,
                'has_advanced_stats' => true,
                'has_export_csv' => true,
                'has_export_excel' => true,
                'has_export_pdf' => true,
                'has_multi_users' => false,
                'has_automations' => false,
                'has_custom_logo' => true,
                'has_remove_masadigitale_logo' => true,
                'has_whatsapp_integration' => true,
                'has_email_notifications' => true,
                'has_api_access' => false,
                'has_templates' => true,
                'price_monthly' => 19990, // 19.990 FCFA
                'price_yearly' => 199900, // 199.900 FCFA
                'sort_order' => 2,
                'is_popular' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Solution complète pour les entreprises',
                'max_forms' => null,
                'max_responses_per_form' => null,
                'has_advanced_stats' => true,
                'has_export_csv' => true,
                'has_export_excel' => true,
                'has_export_pdf' => true,
                'has_multi_users' => true,
                'has_automations' => true,
                'has_custom_logo' => true,
                'has_remove_masadigitale_logo' => true,
                'has_whatsapp_integration' => true,
                'has_email_notifications' => true,
                'has_api_access' => true,
                'has_templates' => true,
                'price_monthly' => 49990, // 49.990 FCFA
                'price_yearly' => 499900, // 499.900 FCFA
                'sort_order' => 3,
                'is_popular' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
