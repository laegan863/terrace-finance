<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_status_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_status_request_id')
                ->constrained('application_status_requests')
                ->onDelete('cascade');

            $table->integer('http_status')->nullable();
            $table->json('response')->nullable();
            $table->json('offers')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_results');
    }
};
