<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_api_keys_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('key', 64)->unique();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('permissions')->nullable();
            $table->timestamps();

            $table->index('key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
};
