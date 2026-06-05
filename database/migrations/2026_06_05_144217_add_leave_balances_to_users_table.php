<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('annual_leave_balance')->default(20);
            $table->integer('sick_leave_balance')->default(12);
            $table->integer('casual_leave_balance')->default(10);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['annual_leave_balance', 'sick_leave_balance', 'casual_leave_balance']);
        });
    }
};