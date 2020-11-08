<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('stern');
            $table->unsignedTinyInteger('model');
            $table->string('name');
            $table->unsignedMediumInteger('level');
			$table->unsignedInteger('gcost');
			$table->unsignedInteger('slcost');
			$table->unsignedInteger('scost');
			$table->mediumInteger('str');
			$table->mediumInteger('def');
			$table->mediumInteger('dex');
			$table->mediumInteger('end');
			$table->mediumInteger('cha');
			$table->integer('hpbonus');
			$table->mediumInteger('regen');
			$table->smallInteger('sbschc');
			$table->smallInteger('sbscdmg');
			$table->smallInteger('sbsctlnt');
			$table->smallInteger('sbnshc');
			$table->smallInteger('sbnsdmg');
			$table->smallInteger('sbnstlnt');
			$table->smallInteger('ebschc');
			$table->smallInteger('ebscdmg');
			$table->smallInteger('ebsctlnt');
			$table->smallInteger('ebnshc');
			$table->smallInteger('ebnsdmg');
			$table->smallInteger('ebnstlnt');
			$table->smallInteger('apup');
			$table->unsignedMediumInteger('cooldown');
			$table->unsignedInteger('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
