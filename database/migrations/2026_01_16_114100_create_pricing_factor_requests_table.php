<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_factor_requests', function (Blueprint $table) {
            $table->id();

            $table->string('FirstName', 16);
            $table->string('LastName', 16);
            $table->string('PhoneNumber', 10);

            $table->string('Address', 100);
            $table->string('City', 20);
            $table->string('State', 2);
            $table->string('Zip', 5);

            $table->string('Email', 50);

            $table->string('SSN', 9)->nullable();
            $table->string('DOB', 10)->nullable(); // MM/DD/YYYY
            $table->decimal('GrossIncome', 12, 2)->nullable();

            $table->string('ProductInformation', 100);

            $table->string('Fingerprint', 256)->nullable();

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_factor_requests');
    }
};
