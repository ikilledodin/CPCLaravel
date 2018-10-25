<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnNameClusteridFromCompanygroupnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('company_group_names', 'clusterid')) {
            Schema::table('company_group_names', function (Blueprint $table) {
                $table->renameColumn('clusterid', 'cluster_id');
            });
        }
        // Schema::table('company_group_names', function (Blueprint $table) {
        //     //
        // });
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
