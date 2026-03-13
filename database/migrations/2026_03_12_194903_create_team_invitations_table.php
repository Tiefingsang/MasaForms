<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_team_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('role')->default('viewer');
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->enum('status', ['pending', 'accepted', 'declined', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['email', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_invitations');
    }
};
