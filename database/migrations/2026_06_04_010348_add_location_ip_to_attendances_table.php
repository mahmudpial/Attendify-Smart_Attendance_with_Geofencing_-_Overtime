<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Punch in location & IP
            $table->decimal('punch_in_lat', 10, 7)->nullable();
            $table->decimal('punch_in_lng', 10, 7)->nullable();
            $table->string('punch_in_ip', 45)->nullable(); // IPv4/IPv6

            // Punch out location & IP
            $table->decimal('punch_out_lat', 10, 7)->nullable();
            $table->decimal('punch_out_lng', 10, 7)->nullable();
            $table->string('punch_out_ip', 45)->nullable();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'punch_in_lat',
                'punch_in_lng',
                'punch_in_ip',
                'punch_out_lat',
                'punch_out_lng',
                'punch_out_ip'
            ]);
        });
    }
};