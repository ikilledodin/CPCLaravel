<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropHeightFromDpmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('d_pms', 'height')) {
            Schema::table('d_pms', function (Blueprint $table) {
                // $table->string('post_type')->nullable();
                $table->dropColumn('height');
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
        Schema::table('d_pms', function (Blueprint $table) {
            //
        });
    }
}
