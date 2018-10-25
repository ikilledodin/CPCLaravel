<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDDistfloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_distfloors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->float('distmeters');
            $table->integer('floorcnt')->default(0);
            $table->timestamp('datetimestamp');
            $table->string('serialnumber',50)->nullable();
            $table->tinyInteger('isprocessed')->default(0);
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
        Schema::dropIfExists('d_distfloors');
    }
}
