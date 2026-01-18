<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_factor_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pricing_factor_request_id')
                ->constrained('pricing_factor_requests')
                ->onDelete('cascade');

            $table->integer('http_status')->nullable();
            $table->json('response')->nullable();
            $table->json('offers')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_factor_results');
    }
};
