<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_notification_requests', function (Blueprint $table) {
            $table->id();

            // Source: webhook or manual (manual = from your UI form)
            $table->enum('source', ['webhook', 'manual'])->default('webhook');

            // Headers captured (for audit)
            $table->string('token_header', 255)->nullable();
            $table->string('authorization_header', 255)->nullable();

            // Normalized payload (only expected fields)
            $table->unsignedBigInteger('ApplicationID')->nullable();
            $table->unsignedBigInteger('LeadID')->nullable();
            $table->string('InvoiceNumber', 256)->nullable();
            $table->unsignedBigInteger('InvoiceID')->nullable();

            $table->decimal('ApprovalAmount', 12, 2)->nullable();
            $table->decimal('FundedAmount', 12, 2)->nullable();

            $table->string('ApplicationStatus', 64)->nullable();
            $table->string('LenderName', 128)->nullable();

            // Offer can be null or array/object depending on status
            $table->json('Offer')->nullable();

            // Raw payload snapshot (optional but helpful)
            $table->json('raw_payload')->nullable();

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_notification_requests');
    }
};
