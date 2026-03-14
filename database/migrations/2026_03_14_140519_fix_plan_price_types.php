<?php
// database/migrations/xxxx_xx_xx_xxxxxx_fix_plan_price_types.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            // Convertir les colonnes price_* en decimal
            $table->decimal('price_monthly', 10, 2)->change();
            $table->decimal('price_yearly', 10, 2)->change();
            $table->decimal('price_monthly_usd', 10, 2)->nullable()->change();
            $table->decimal('price_yearly_usd', 10, 2)->nullable()->change();
            $table->decimal('price_monthly_eur', 10, 2)->nullable()->change();
            $table->decimal('price_yearly_eur', 10, 2)->nullable()->change();
        });
    }

    public function down()
    {
        // Optionnel : revenir en arrière
    }
};
