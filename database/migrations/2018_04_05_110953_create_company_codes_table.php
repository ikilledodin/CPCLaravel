<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('company_code',6)->unique();
            $table->string('phone',20)->nullable();
            $table->string('email')->nullable();
            $table->integer('web_theme')->nullable();
            $table->integer('app_theme_primary')->nullable();
            $table->integer('app_theme_secondary')->nullable();
            $table->string('comp_logo')->nullable();
            $table->string('app_program_logo')->nullable();
            $table->string('app_splash_screen')->nullable();
            $table->string('app_login_screen')->nullable();
            $table->integer('device_support')->nullable();
            $table->integer('feature_enable')->nullable();
            $table->tinyInteger('register_filter')->nullable();
            $table->string('email_filter')->nullable();
            $table->tinyInteger('uname_enable')->nullable();
            $table->integer('datashow')->nullable();
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
        Schema::dropIfExists('company_codes');
    }
}
