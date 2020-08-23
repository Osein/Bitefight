<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUserAndEmailValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_email_activation', function(Blueprint $table) {
            $table->boolean('activated')->default(false)->after('expire');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('email_activated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_email_activation', function(Blueprint $table) {
            $table->dropColumn('activated');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->boolean('email_activated')->default(0)->after('remember_token');
        });
    }
}
