<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('serial',50)->nullable();
            $table->tinyInteger('userindex')->default(0);
            $table->smallInteger('devicetype')->default(0);
            $table->float('packageid')->default(1);
            $table->float('hwversion')->default(1);
            $table->float('swversion')->default(1);
            $table->string('location')->default('home');
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
        Schema::dropIfExists('user_devices');
    }
}
