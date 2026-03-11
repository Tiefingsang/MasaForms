<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('thank_you_message')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('primary_color')->default('#3B82F6');
            $table->string('background_color')->default('#FFFFFF');
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('accepts_responses')->default(true);
            $table->boolean('show_progress_bar')->default(true);
            $table->boolean('captcha_enabled')->default(false);
            $table->integer('max_responses')->nullable(); // null = illimité
            $table->integer('current_responses')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('password')->nullable(); // Pour protéger le formulaire
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forms');
    }
};
