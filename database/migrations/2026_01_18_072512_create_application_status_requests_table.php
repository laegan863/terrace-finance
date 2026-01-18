<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_status_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ApplicationID');

            // store which example schema got used (optional but helpful)
            $table->string('scenario', 30)->default('core');

            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_requests');
    }
};
