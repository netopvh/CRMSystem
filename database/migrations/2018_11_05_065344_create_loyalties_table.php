<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyaltiesTable extends Migration
{

    public function up()
    {
        Schema::create('loyalties', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('company_id')->unsigned()->nullable()->comment('Id компании');
            
            $table->string('name')->index()->comment('Уровень лояльности');
            $table->text('description')->comment('Описание');

            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');
            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');
            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');

            $table->timestamps();
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loyalties');
    }
}
