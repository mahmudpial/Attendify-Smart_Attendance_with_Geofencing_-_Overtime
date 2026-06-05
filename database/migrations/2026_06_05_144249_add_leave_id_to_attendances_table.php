<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('leave_request_id')->nullable()->constrained('leave_requests')->nullOnDelete();
            // Add status 'on_leave' to enum (you may need to change column definition)
        });
        // Change status enum to include 'on_leave'
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('present', 'absent', 'late', 'half-day', 'on_leave') DEFAULT 'present'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('present', 'absent', 'late', 'half-day') DEFAULT 'present'");
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['leave_request_id']);
            $table->dropColumn('leave_request_id');
        });
    }
};