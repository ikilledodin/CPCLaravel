<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserGoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
    {
        Schema::create('user_goal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('steps', 50)->nullable();
            $table->string('calories', 50)->nullable();
            $table->string('distance', 50)->nullable();
            $table->string('weight', 50)->nullable();
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
        Schema::dropIfExists('user_goal');
    }
}
