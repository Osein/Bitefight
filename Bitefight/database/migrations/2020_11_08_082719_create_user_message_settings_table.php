<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_message_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->enum('setting', [
            	'work','attacked','got_attacked','clan_wars',
				'grotto','adventure','mission','clan_founded',
				'left_clan','disbanded_clan','clan_disbanded',
				'clan_mail','clan_app_rejected','clan_app_accepted',
				'clan_member_left','report_answer']);
            $table->unsignedInteger('folder_id')->default(0);
            $table->boolean('mark_read')->default(0);
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
        Schema::dropIfExists('user_message_settings');
    }
}
