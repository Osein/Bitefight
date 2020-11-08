<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sender_id')->default(0);
            $table->unsignedInteger('receiver_id');
            $table->unsignedInteger('folder_id')->default(0);
            $table->unsignedTinyInteger('type')->default(1);
            $table->string('subject', 30);
            $table->string('message', 2000);
            $table->unsignedSmallInteger('status')->default(1);
            $table->timestamp('send_time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
