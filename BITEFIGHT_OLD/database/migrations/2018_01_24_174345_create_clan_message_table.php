<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClanMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan_message', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('clan_id');
            $table->unsignedInteger('user_id');
            $table->string('clan_message', 2000);
            $table->unsignedInteger('message_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clan_message');
    }
}
