<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropClusteridFromCompanyclusternamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('company_cluster_names', 'clusterid')) {
            Schema::table('company_cluster_names', function (Blueprint $table) {
                // $table->string('post_type')->nullable();
                $table->dropColumn('clusterid');
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
        Schema::table('company_cluster_names', function (Blueprint $table) {
            //
        });
    }
}
