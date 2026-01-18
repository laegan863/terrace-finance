<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_requests', function (Blueprint $table) {
            $table->id();

            $table->string('InvoiceNumber', 256);
            $table->string('InvoiceDate', 10);   // mm-dd-yyyy
            $table->string('DeliveryDate', 10);  // mm-dd-yyyy

            $table->unsignedBigInteger('ApplicationID')->nullable();
            $table->unsignedBigInteger('LeadID')->nullable();

            $table->decimal('Discount', 12, 2)->default(0);
            $table->decimal('DownPayment', 12, 2)->default(0);
            $table->decimal('Shipping', 12, 2)->default(0);
            $table->decimal('Tax', 12, 2)->default(0);

            $table->string('ReturnURL', 2048)->nullable();
            $table->char('InvoiceVersion', 1)->nullable(); // C or R or null

            // Store items as JSON for logging
            $table->json('Items');

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_requests');
    }
};
