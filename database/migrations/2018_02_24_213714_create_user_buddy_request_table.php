<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBuddyRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_buddy_request', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->text('message');
            $table->unsignedInteger('request_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_buddy_request');
    }
}
