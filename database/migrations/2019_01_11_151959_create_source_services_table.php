<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourceServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_services', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->index()->comment('Название');
            $table->string('alias')->nullable()->index()->comment('Алиас');
            $table->string('domain')->nullable()->index()->comment('Домашняя страница сервиса');

            $table->text('description')->nullable()->comment('Description для типа трафика');

            $table->integer('source_id')->unsigned()->nullable()->comment('Id компании');
            // $table->foreign('source_id')->references('id')->on('sources');

            $table->integer('company_id')->unsigned()->nullable()->comment('Id компании');
            // $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            // $table->foreign('author_id')->references('id')->on('users');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');

            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');
            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');
            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_services');
    }
}
