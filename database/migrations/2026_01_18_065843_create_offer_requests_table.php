<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ApplicationID');
            $table->string('Offer', 64);

            // BankDetails saved as JSON (nullable)
            $table->json('BankDetails')->nullable();

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_requests');
    }
};
