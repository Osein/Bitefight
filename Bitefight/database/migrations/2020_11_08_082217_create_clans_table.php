<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clans', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('race')->default(1);
            $table->string('name', 32)->default('');
            $table->string('tag', 32)->default('');
            $table->unsignedTinyInteger('logo_bg')->default(1);
            $table->unsignedTinyInteger('logo_sym')->default(1);
            $table->string('website')->default('');
            $table->unsignedInteger('website_set_by')->default(0);
            $table->unsignedInteger('website_counter')->default(0);
            $table->unsignedInteger('capital')->default(0);
			$table->unsignedTinyInteger('stufe')->default(0);
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
        Schema::dropIfExists('clans');
    }
}
