<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMessageTableForExtraColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function(Blueprint $table) {
            $table->unsignedInteger('report_id')->default(0);
            $table->unsignedInteger('gy_reward')->default(0);
            $table->unsignedInteger('gy_exp')->default(0);
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function(Blueprint $table) {
            $table->dropColumn('report_id');
            $table->dropColumn('gy_reward');
            $table->dropColumn('gy_exp');
            $table->unsignedTinyInteger('type')->default(1);
        });
    }
}
