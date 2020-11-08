<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talents', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active');
            $table->unsignedMediumInteger('level');
            $table->unsignedInteger('pair');
            $table->unsignedInteger('duration');
            $table->smallInteger('attack');
            $table->smallInteger('eattack');
			$table->mediumInteger('str');
			$table->mediumInteger('def');
			$table->mediumInteger('dex');
			$table->mediumInteger('end');
			$table->mediumInteger('cha');
			$table->mediumInteger('estr');
			$table->mediumInteger('edef');
			$table->mediumInteger('edex');
			$table->mediumInteger('eend');
			$table->mediumInteger('echa');
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
        });

		Artisan::call( 'import:talents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talents');
    }
}
