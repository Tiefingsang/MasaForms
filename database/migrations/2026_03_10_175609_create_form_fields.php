<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('name')->unique(); // Identifiant unique pour le champ
            $table->string('type'); // text, textarea, email, number, tel, date, radio, checkbox, select, file, etc.
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable(); // Pour les champs avec options (radio, select, checkbox)
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->string('default_value')->nullable();
            $table->json('validation_rules')->nullable(); // ['min:3', 'max:255', etc.]
            $table->json('conditional_logic')->nullable(); // Pour les champs conditionnels
            $table->string('file_types')->nullable(); // Pour les champs file
            $table->integer('max_file_size')->nullable(); // en KB
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
};
