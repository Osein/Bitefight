<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClanRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan_rank', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('clan_id');
			$table->string('rank_name', 32);
			$table->boolean('read_message')->default(0);
			$table->boolean('write_message')->default(0);
			$table->boolean('read_clan_message')->default(0);
			$table->boolean('add_members')->default(0);
			$table->boolean('delete_message')->default(0);
			$table->boolean('send_clan_message')->default(0);
			$table->boolean('spend_gold')->default(0);
			$table->boolean('war_minister')->default(0);
			$table->boolean('vocalise_ritual')->default(0);
        });

        \Illuminate\Support\Facades\DB::table('clan_rank')->insert([
			[
				'clan_id' => 0,
				'rank_name' => 'Master',
				'read_message' => 1,
				'write_message' => 1,
				'read_clan_message' => 1,
				'add_members' => 1,
				'delete_message' => 1,
				'send_clan_message' => 1,
				'spend_gold' => 1,
				'war_minister' => 1,
				'vocalise_ritual' => 1,
			],
			[
				'clan_id' => 0,
				'rank_name' => 'Admin',
				'read_message' => 1,
				'write_message' => 1,
				'read_clan_message' => 1,
				'add_members' => 1,
				'delete_message' => 1,
				'send_clan_message' => 1,
				'spend_gold' => 1,
				'war_minister' => 1,
				'vocalise_ritual' => 1,
			],
			[
				'clan_id' => 0,
				'rank_name' => 'Biter',
				'read_message' => 0,
				'write_message' => 0,
				'read_clan_message' => 0,
				'add_members' => 0,
				'delete_message' => 0,
				'send_clan_message' => 0,
				'spend_gold' => 0,
				'war_minister' => 0,
				'vocalise_ritual' => 0,
			],
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clan_rank');
    }
}
