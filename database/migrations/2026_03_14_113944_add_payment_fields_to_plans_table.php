<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_fields_to_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            // Prix internationaux
            $table->decimal('price_monthly_usd', 10, 2)->nullable()->after('price_yearly');
            $table->decimal('price_yearly_usd', 10, 2)->nullable()->after('price_monthly_usd');
            $table->decimal('price_monthly_eur', 10, 2)->nullable()->after('price_yearly_usd');
            $table->decimal('price_yearly_eur', 10, 2)->nullable()->after('price_monthly_eur');

            // IDs des gateways
            $table->string('stripe_price_id_monthly')->nullable()->after('price_yearly_eur');
            $table->string('stripe_price_id_yearly')->nullable()->after('stripe_price_id_monthly');
            $table->string('paypal_plan_id')->nullable()->after('stripe_price_id_yearly');

            // Devise par défaut
            $table->string('currency')->default('XOF')->after('paypal_plan_id');
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'price_monthly_usd',
                'price_yearly_usd',
                'price_monthly_eur',
                'price_yearly_eur',
                'stripe_price_id_monthly',
                'stripe_price_id_yearly',
                'paypal_plan_id',
                'currency',
            ]);
        });
    }
};
