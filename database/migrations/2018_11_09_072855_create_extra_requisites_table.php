.<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraRequisitesTable extends Migration
{

    public function up()
    {
        Schema::create('extra_requisites', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('company_id')->unsigned()->nullable()->comment('Id компании под которой сделана запись');
            // $table->foreign('company_id')->references('id')->on('companies');

            $table->string('name')->nullable()->index()->comment('Название дополнительного реквизита');
            $table->text('description')->nullable()->comment('Описание');

            $table->string('alias')->nullable()->index()->comment('Алиас реквизита');

            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');
            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            // $table->foreign('author_id')->references('id')->on('users');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');
            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');
            $table->timestamps();
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');

        });
    }

    public function down()
    {
        Schema::dropIfExists('extra_requisites');
    }
}
