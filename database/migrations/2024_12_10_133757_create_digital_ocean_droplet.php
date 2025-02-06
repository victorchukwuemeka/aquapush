<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('digital_ocean_droplet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('api_token');
            $table->string('droplet_id');
            //$table->string('droplet_size');
            //$table->string('droplet_name');
            //$table->string('region')->nullable();
            //$table->string('repository');
            //$table->string('image')->nullable();
            //$table->string('ip_address');
            //$table->enum('status', ['pending', 'inprogress', 'active', 'failed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_ocean_droplet');
    }
};
