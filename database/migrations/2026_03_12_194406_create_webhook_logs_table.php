<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_webhook_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('event')->nullable();
            $table->json('payload');
            $table->json('response')->nullable();
            $table->string('status')->default('received');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhook_logs');
    }
};
