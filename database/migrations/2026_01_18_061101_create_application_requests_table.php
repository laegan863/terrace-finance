<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_requests', function (Blueprint $table) {
            $table->id();

            $table->string('FirstName', 16);
            $table->string('LastName', 16);

            $table->string('CellNumber', 10);
            $table->boolean('CellValidation');

            $table->string('Address', 100);
            $table->string('Address2', 50)->nullable();
            $table->string('City', 20);
            $table->string('State', 2);
            $table->string('Zip', 5);

            $table->string('Email', 50);
            $table->string('Fingerprint', 256);

            $table->boolean('Consent');

            $table->string('SSN', 9);

            // Dates stored exactly as the API format requires: MM/DD/YYYY
            $table->string('DOB', 10);
            $table->string('LastPayDate', 10);
            $table->string('NextPayDate', 10)->nullable();

            // Either GrossIncome or NetIncome will be present
            $table->decimal('GrossIncome', 12, 2)->nullable();
            $table->decimal('NetIncome', 12, 2)->nullable();

            $table->string('PayFrequency', 20);

            $table->string('ProductInformation', 100);

            $table->string('IdentificationDocumentID', 30)->nullable();

            $table->decimal('BestEstimate', 12, 2);

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_requests');
    }
};
