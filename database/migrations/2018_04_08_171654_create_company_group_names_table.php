<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyGroupNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_group_names', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('groupid');
            $table->integer('clusterid')->default(0);
            $table->string('name');
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
        Schema::dropIfExists('company_group_names');
    }
}
