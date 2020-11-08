<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talent', function (Blueprint $table) {
            $table->id();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talent');
    }
}
