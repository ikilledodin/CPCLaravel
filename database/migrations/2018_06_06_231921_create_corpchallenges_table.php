<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorpchallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corpchallenges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->dateTime('startdate')->nullable();
            $table->dateTime('enddate')->nullable();
            $table->integer('challenge_unit')->nullable();
            $table->integer('challenge_user')->default(1);
            $table->integer('challenge_limit')->default(100);
            $table->string('challenge_title')->nullable();
            $table->string('challenge_header')->nullable();
            $table->text('challenge_text')->nullable();
            $table->string('challenge_imageurl')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('challenge_cat')->nullable();
            $table->tinyInteger('isprocessed')->default(0);
            $table->integer('challenge_template')->nullable();
            $table->tinyInteger('isweb')->nullable();
            $table->tinyInteger('isphone')->nullable();
            $table->integer('threshold')->nullable();
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
        Schema::dropIfExists('corpchallenges');
    }
}
