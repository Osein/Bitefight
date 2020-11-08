<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClanRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan_ranks', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();

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
        Schema::dropIfExists('clan_ranks');
    }
}
