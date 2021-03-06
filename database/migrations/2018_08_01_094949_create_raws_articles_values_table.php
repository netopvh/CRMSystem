<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawsArticlesValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raws_articles_values', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('raws_article_id')->nullable()->unsigned()->comment('ID метрики');
            $table->foreign('raw_article_id')->references('id')->on('raws_articles');

            $table->integer('raws_articles_values_id')->nullable()->unsigned()->comment('Id сущности связанной с сырьем');
            $table->string('raws_articles_values_type')->index()->comment('Сущность обьекта');

            $table->string('value')->nullable()->comment('Значение');

                        // $table->integer('company_id')->nullable()->unsigned()->comment('ID компании');
            // $table->foreign('company_id')->references('id')->on('companies');

            // $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');

            // $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');

            // $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            // $table->foreign('author_id')->references('id')->on('users');

            // $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');
            // $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');

            // $table->timestamps();
            // $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raws_articles_values');
    }
}
