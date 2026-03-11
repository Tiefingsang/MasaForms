<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('response_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('form_field_id')->constrained()->onDelete('cascade');
            $table->text('value')->nullable();
            $table->json('file_paths')->nullable(); // Pour les uploads multiples
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('response_values');
    }
};
