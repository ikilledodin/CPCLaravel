<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->string('event_title');
            $table->binary('event_en_body')->nullable();
            $table->binary('event_ar_body')->nullable();
            $table->tinyInteger('ar_exist')->default(0);
            $table->string('invite_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('event_contents');
    }
}
