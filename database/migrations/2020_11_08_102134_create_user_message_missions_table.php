<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessageMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_message_missions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('type');
            $table->boolean('accepted');
            $table->unsignedInteger('accepted_time')->default(0);
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('count');
            $table->unsignedInteger('gold');
            $table->unsignedTinyInteger('frag');
            $table->unsignedTinyInteger('ap');
            $table->unsignedTinyInteger('heal');
            $table->unsignedTinyInteger('time');
            $table->unsignedTinyInteger('special');
            $table->unsignedTinyInteger('day');
            $table->unsignedTinyInteger('progress');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_message_missions');
    }
}
