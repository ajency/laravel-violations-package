<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjVioViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_vio_violations', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->unique();
            $table->string('type');
            $table->integer('who_id');
            $table->string('who_type');
            $table->text('who_meta')->nullable();
            $table->integer('whom_id')->nullable();
            $table->string('whom_type')->nullable();
            $table->text('whom_meta')->nullable();
            $table->text('cc_list')->nullable();
            $table->text('bcc_list')->nullable();
            $table->timestamps();
        });
    }

     * Reverse the migrations.
     *
     /**
     * @return void
     */
    public function down()
    {
        Schema::drop('aj_vio_violations');
    }
}
