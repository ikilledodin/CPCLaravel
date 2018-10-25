<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSteplengthToUserprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('user_profiles', function (Blueprint $table) {
        //     //
        // });

        if(Schema::hasColumn('user_profiles', 'steplength')) {

        } else {
            Schema::table('user_profiles', function (Blueprint $table) {
                // $table->string('post_type')->nullable();
                $table->float('steplength')->nullable();
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
        // Schema::table('user_profiles', function (Blueprint $table) {
        //     //
        // });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('steplength');
        });
    }
}
