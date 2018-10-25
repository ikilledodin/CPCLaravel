<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->string('height_pref', 10)->nullable();
            $table->string('weight_pref', 10)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('birthdate', 20)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('city', 90)->nullable();
            $table->string('timezone_name', 50)->nullable();
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
