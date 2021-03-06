<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index()->comment('Название населенного пункта');
            $table->string('alias')->nullable()->index()->comment('Алиас населенного пункта');
            $table->integer('area_id')->unsigned()->nullable()->comment('Район населенного пункта');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->integer('region_id')->unsigned()->nullable()->comment('Область населенного пункта');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->integer('code')->unsigned()->nullable()->comment('Код населенного пункта');
            $table->integer('vk_external_id')->unique()->unsigned()->nullable()->comment('Внешний Id (из базы vk)');


            // Общие настройки
            $table->integer('company_id')->unsigned()->nullable()->comment('Id компании');
            // $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');
            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');
            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            $table->foreign('author_id')->references('id')->on('users');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');

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
        Schema::dropIfExists('cities');
    }
}
