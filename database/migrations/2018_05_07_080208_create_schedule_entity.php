<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleEntity extends Migration
{

    public function up()
    {
        Schema::create('schedule_entity', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('schedule_id')->nullable()->unsigned()->comment('Id графика работ (расписания)');
            $table->foreign('schedule_id')->references('id')->on('schedules');
            
            $table->integer('schedule_entities_id')->nullable()->unsigned()->comment('Id сущности связанной с расписанием');
            $table->string('schedule_entities_type')->index()->comment('Сущность обьекта');

            $table->string('mode')->index()->nullable()->comment('Режим');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_entity');
    }
}
