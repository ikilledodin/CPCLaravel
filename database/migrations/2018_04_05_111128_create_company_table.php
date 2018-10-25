<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
             $table->string('name')->unique();
            $table->string('shortname')->unique();
            $table->string('description')->nullable();
            $table->tinyInteger('group_mode')->nullable();
            $table->tinyInteger('cluster_mode')->nullable();
            $table->string('group_alias',50)->nullable();
            $table->string('cluster_alias',50)->nullable();
            $table->string('tzname',50)->nullable();
            $table->dateTime('program_startdate')->nullable();
            $table->dateTime('program_enddate')->nullable();
            $table->tinyInteger('license_type')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
