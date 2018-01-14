<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
        	$table->charset = 'utf8mb4';
        	$table->collation = 'utf8mb4_bin';

            $table->increments('id');
			$table->string('name', 32);
			$table->string('email', 128)->unique();
			$table->string('password', 64);
			$table->rememberToken();
			$table->unsignedTinyInteger('race');
			$table->unsignedTinyInteger('gender');
			$table->unsignedTinyInteger('image_type');
			$table->unsignedInteger('clan_id')->default(0);
			$table->unsignedInteger('clan_rank')->default(0);
			$table->unsignedInteger('clan_dtime')->default(0);
			$table->unsignedInteger('clan_btime')->default(0);
			$table->unsignedInteger('exp');
			$table->unsignedInteger('battle_value');
			$table->unsignedInteger('gold');
			$table->unsignedInteger('hellstone');
			$table->unsignedInteger('fragment');
			$table->float('ap_now')->unsigned();
			$table->unsignedInteger('ap_max');
			$table->float('hp_now')->unsigned();
			$table->unsignedInteger('hp_max');
			$table->unsignedInteger('str');
			$table->unsignedInteger('def');
			$table->unsignedInteger('dex');
			$table->unsignedInteger('end');
			$table->unsignedInteger('cha');
			$table->unsignedInteger('s_booty');
			$table->unsignedInteger('s_fight');
			$table->unsignedInteger('s_victory');
			$table->unsignedInteger('s_defeat');
			$table->unsignedInteger('s_draw');
			$table->unsignedInteger('s_gold_captured');
			$table->unsignedInteger('s_gold_lost');
			$table->unsignedInteger('s_damage_caused');
			$table->unsignedInteger('s_hp_lost');
			$table->unsignedInteger('talent_points');
			$table->unsignedInteger('talent_resets');
			$table->unsignedInteger('h_treasure');
			$table->unsignedInteger('h_royal');
			$table->unsignedInteger('h_gargoyle');
			$table->unsignedInteger('h_book');
			$table->unsignedInteger('h_domicile');
			$table->unsignedInteger('h_wall');
			$table->unsignedInteger('h_path');
			$table->unsignedInteger('h_land');
			$table->unsignedInteger('last_activity')->default(0);
			$table->unsignedSmallInteger('name_change');
			$table->boolean('vacation');
			$table->boolean('show_picture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
