<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_position', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('widget_id')->unsigned()->comment('ID виджета');
            // $table->foreign('charge_id')->references('id')->on('charges');

            $table->integer('position_id')->unsigned()->comment('ID должности');
            // $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_position');
    }
}
