<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_request_id')
                ->constrained('invoice_requests')
                ->onDelete('cascade');

            $table->integer('http_status')->nullable();
            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_results');
    }
};
