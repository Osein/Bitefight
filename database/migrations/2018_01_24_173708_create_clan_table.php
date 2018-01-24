<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('race');
            $table->string('name', 32);
            $table->string('tag', 32);
            $table->unsignedTinyInteger('logo_bg');
            $table->unsignedTinyInteger('logo_sym');
            $table->string('website');
            $table->unsignedInteger('website_set_by');
            $table->unsignedInteger('website_counter');
            $table->unsignedTinyInteger('capital');
			$table->unsignedTinyInteger('stufe');
			$table->unsignedInteger('found_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clan');
    }
}
