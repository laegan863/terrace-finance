<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_notification_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('status_notification_request_id');

            $table->integer('http_status')->nullable();
            $table->json('response')->nullable();

            $table->timestamps();

            $table->foreign('status_notification_request_id', 'snr_req_fk')
                ->references('id')
                ->on('status_notification_requests')
                ->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('status_notification_results');
    }
};
