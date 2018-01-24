<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClanDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan_donations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('clan_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('donation_amount');
            $table->unsignedInteger('donation_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clan_donations');
    }
}
