<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjVioTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_vio_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shortcode')->unique();
            $table->string('title');
            $table->longText('description');
            $table->tinyInteger('severity')->nullable()->comment('0=low, 1 = medium, 2=high');
            $table->boolean('published')->default('0');
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
        Schema::drop('aj_vio_types');
    }
}
