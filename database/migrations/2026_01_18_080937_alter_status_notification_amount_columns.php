<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE status_notification_requests MODIFY FundedAmount DECIMAL(18,2) NULL");
        DB::statement("ALTER TABLE status_notification_requests MODIFY ApprovalAmount DECIMAL(18,2) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE status_notification_requests MODIFY FundedAmount DECIMAL(12,2) NULL");
        DB::statement("ALTER TABLE status_notification_requests MODIFY ApprovalAmount DECIMAL(12,2) NULL");
    }
};
