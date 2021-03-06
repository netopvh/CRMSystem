<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{

    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('company_id')->unsigned()->nullable()->comment('Id компании');
            // $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('filial_id')->unsigned()->nullable()->comment('Id отдела');
            // $table->foreign('filial_id')->references('id')->on('departments');

            $table->integer('indicator_id')->nullable()->unsigned()->comment('ID индикатора');
            $table->integer('value')->nullable()->unsigned()->comment('Значение индикатора');

            $table->integer('year')->nullable()->unsigned()->comment('Год');
            $table->integer('month')->nullable()->unsigned()->comment('Месяц');
            $table->integer('week')->nullable()->unsigned()->comment('Неделя');
            $table->integer('day')->nullable()->unsigned()->comment('День');
            $table->integer('hour')->nullable()->unsigned()->comment('Час');
            $table->integer('minute')->nullable()->unsigned()->comment('Минута');
            $table->integer('second')->nullable()->unsigned()->comment('Секунда');

            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');
            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            $table->foreign('author_id')->references('id')->on('users');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');
            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');
            $table->timestamps();
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
