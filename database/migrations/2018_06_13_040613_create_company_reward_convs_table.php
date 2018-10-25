<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyRewardConvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_reward_convs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('merchant_id');
            $table->integer('reason_code')->nullable();
            $table->integer('range_from')->nullable();
            $table->integer('range_to')->nullable();
            $table->integer('num_miles')->nullable();
            $table->string('description',100)->nullable();
            $table->smallInteger('conv_type')->default(1);
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
        Schema::dropIfExists('company_reward_convs');
    }
}
