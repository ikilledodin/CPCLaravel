<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserThirdpartytokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_thirdpartytokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('accesstoken',300)->nullable();;
            $table->string('tokensecret',300)->nullable();
            $table->string('type',100)->nullable();
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
        Schema::dropIfExists('user_thirdpartytokens');
    }
}
