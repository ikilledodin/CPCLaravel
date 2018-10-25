<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasColumn('user_devices', 'lasttransmission')) {

        } else {
            // drop userindex first
            Schema::table('user_devices', function (Blueprint $table) {
                // $table->string('post_type')->nullable();

                $table->dropColumn('userindex');
            });
            Schema::table('user_devices', function (Blueprint $table) {
                $table->smallinteger('userindex')->default(0);
                $table->timestamp('lasttransmission')->nullable();
                $table->timestamp('lastseen')->nullable();
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
        //
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropColumn('lasttransmission');
            $table->dropColumn('lastseen');
        });
    }
}
