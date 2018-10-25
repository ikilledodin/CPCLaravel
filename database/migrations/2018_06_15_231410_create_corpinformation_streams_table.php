<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorpinformationStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corpinformation_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('title',150);
            $table->string('message',300)->nullable();
            $table->string('photourl',100)->nullable();
            $table->tinyInteger('state')->default(1);
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
        Schema::dropIfExists('corpinformation_streams');
    }
}
