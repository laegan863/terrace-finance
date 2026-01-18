<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('offer_request_id')
                ->constrained('offer_requests')
                ->onDelete('cascade');

            $table->integer('http_status')->nullable();
            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_results');
    }
};
