<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Add foreign key column
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('leave_request_id')->nullable()->constrained('leave_requests')->nullOnDelete();
        });

        // For PostgreSQL: safely alter the status column to include 'on_leave'
        // First ensure column is text/varchar
        DB::statement("ALTER TABLE attendances ALTER COLUMN status TYPE VARCHAR(20) USING status::VARCHAR(20)");

        // Drop existing check constraint if exists
        DB::statement("ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check");

        // Add new check constraint with 'on_leave'
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('present', 'absent', 'late', 'half-day', 'on_leave'))");

        // Set default
        DB::statement("ALTER TABLE attendances ALTER COLUMN status SET DEFAULT 'present'");
    }

    public function down()
    {
        // Remove foreign key
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['leave_request_id']);
            $table->dropColumn('leave_request_id');
        });

        // Revert status constraint (remove 'on_leave')
        DB::statement("ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check");
        DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('present', 'absent', 'late', 'half-day'))");
    }
};