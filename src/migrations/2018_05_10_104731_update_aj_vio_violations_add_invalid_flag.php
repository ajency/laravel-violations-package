<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAjVioViolationsAddInvalidFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aj_vio_violations', function (Blueprint $table) {
            $table->boolean('invalid_flag')->default(false)->after('bcc_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aj_vio_violations', function (Blueprint $table) {
            $table->dropColumn('invalid_flag');
        });
    }
}
