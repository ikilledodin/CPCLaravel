<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_winners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('challenge_id');
            $table->integer('user_id');
            $table->integer('rank');
            $table->string('name',150);
            $table->integer('steps');
            $table->string('photourl',300)->nullable();
            $table->integer('total')->default(0);
            $table->integer('participants')->default(0);
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
        Schema::dropIfExists('challenge_winners');
    }
}
