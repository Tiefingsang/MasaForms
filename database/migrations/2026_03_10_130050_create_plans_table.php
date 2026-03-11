<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('max_forms')->nullable(); // null = illimité
            $table->integer('max_responses_per_form')->nullable(); // null = illimité
            $table->boolean('has_advanced_stats')->default(false);
            $table->boolean('has_export_csv')->default(true);
            $table->boolean('has_export_excel')->default(false);
            $table->boolean('has_export_pdf')->default(false);
            $table->boolean('has_multi_users')->default(false);
            $table->boolean('has_automations')->default(false);
            $table->boolean('has_custom_logo')->default(false);
            $table->boolean('has_remove_masadigitale_logo')->default(false);
            $table->boolean('has_whatsapp_integration')->default(false);
            $table->boolean('has_email_notifications')->default(false);
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_templates')->default(false);
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
