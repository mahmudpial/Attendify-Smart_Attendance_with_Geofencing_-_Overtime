<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'overtime_hours')) {
                $table->decimal('overtime_hours', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('attendances', 'normal_hours')) {
                $table->decimal('normal_hours', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('attendances', 'overtime_start')) {
                $table->timestamp('overtime_start')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'overtime_hours')) {
                $table->dropColumn('overtime_hours');
            }
            if (Schema::hasColumn('attendances', 'normal_hours')) {
                $table->dropColumn('normal_hours');
            }
            if (Schema::hasColumn('attendances', 'overtime_start')) {
                $table->dropColumn('overtime_start');
            }
        });
    }
};