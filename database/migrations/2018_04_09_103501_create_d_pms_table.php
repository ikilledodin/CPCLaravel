<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDPmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_pms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('numberofsteps');
            $table->timestamp('datetimestamp');
            $table->float('met')->default(0);
            $table->float('calcalories')->default(0);
            $table->float('height')->nullable();
            $table->tinyInteger('isnew')->default(1);
            $table->string('serialnumber',50)->nullable();
            $table->tinyInteger('isaerobic')->default(1);
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
        Schema::dropIfExists('d_pms');
    }
}
