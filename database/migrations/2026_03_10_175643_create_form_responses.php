<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('respondent_name')->nullable();
            $table->string('respondent_email')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('location_data')->nullable(); // pays, ville, etc.
            $table->boolean('is_completed')->default(true);
            $table->integer('completion_time')->nullable(); // en secondes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_responses');
    }
};
