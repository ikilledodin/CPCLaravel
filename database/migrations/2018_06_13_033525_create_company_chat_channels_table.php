<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyChatChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_chat_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name',50)->nullable();
            $table->string('description',100)->nullable();
            $table->string('photourl')->nullable();
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
        Schema::dropIfExists('company_chat_channels');
    }
}
