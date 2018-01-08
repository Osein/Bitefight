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
            $table->increments('id');
            $table->string('name', 32);
			$table->string('password', 64);
            $table->string('email')->unique();
			$table->rememberToken();
            $table->tinyInteger('race');
            $table->tinyInteger('gender');
            $table->tinyInteger('image_type');
            $table->integer('clan_id')->unsigned();
            $table->integer('clan_rank')->unsigned();
			$table->integer('clan_dtime')->unsigned();
			$table->integer('clan_btime')->unsigned();
			$table->integer('exp')->unsigned();
			$table->integer('battle_value')->unsigned();
			$table->integer('gold')->unsigned();
			$table->integer('hellstone')->unsigned();
			$table->integer('fragment')->unsigned();
			$table->float('ap_now');
			$table->integer('ap_max')->unsigned();
			$table->float('hp_now');
			$table->integer('hp_max')->unsigned();
			$table->integer('str')->unsigned();
			$table->integer('def')->unsigned();
			$table->integer('dex')->unsigned();
			$table->integer('end')->unsigned();
			$table->integer('cha')->unsigned();
			$table->integer('s_booty')->unsigned();
			$table->integer('s_fight')->unsigned();
			$table->integer('s_victory')->unsigned();
			$table->integer('s_defeat')->unsigned();
			$table->integer('s_draw')->unsigned();
			$table->integer('s_gold_captured')->unsigned();
			$table->integer('s_gold_lost')->unsigned();
			$table->integer('s_damage_caused')->unsigned();
			$table->integer('s_hp_lost')->unsigned();
			$table->integer('talent_points')->unsigned();
			$table->integer('talent_resets')->unsigned();
			$table->integer('h_treasure')->unsigned();
			$table->integer('h_royal')->unsigned();
			$table->integer('h_gargoyle')->unsigned();
			$table->integer('h_book')->unsigned();
			$table->integer('h_domicile')->unsigned();
			$table->integer('h_wall')->unsigned();
			$table->integer('h_path')->unsigned();
			$table->integer('h_land')->unsigned();
			$table->integer('last_activity')->unsigned();
			$table->integer('name_change')->unsigned();
			$table->integer('vacation')->unsigned();
			$table->tinyInteger('show_picture')->unsigned();
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
