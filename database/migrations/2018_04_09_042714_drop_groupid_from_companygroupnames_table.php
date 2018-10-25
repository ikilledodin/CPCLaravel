<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropGroupidFromCompanygroupnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('company_group_names', 'groupid')) {
            Schema::table('company_group_names', function (Blueprint $table) {
                // $table->string('post_type')->nullable();
                $table->dropColumn('groupid');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_group_names', function (Blueprint $table) {
            //
        });
    }
}
