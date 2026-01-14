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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('FirstName', 16);
            $table->string('LastName', 16);
            $table->string('PhoneNumber', 10);
            $table->string('Address', 100);
            $table->string('City', 20);
            $table->string('State', 2);
            $table->string('Zip', 5);
            $table->string('Email', 50);
            $table->string('Fingerprint', 256)->nullable();
            $table->string('ProductInformation', 100);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
