<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClanMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('clan_id');
            $table->unsignedInteger('user_id');
            $table->string('clan_message', 2000);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('clan_id')->references('id')->on('clans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clan_messages');
    }
}
