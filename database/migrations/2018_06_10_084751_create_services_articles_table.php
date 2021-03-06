<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_articles', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('company_id')->nullable()->unsigned()->comment('ID компании');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('services_product_id')->nullable()->unsigned()->comment('ID товара');
            $table->foreign('services_product_id')->references('id')->on('services_products');

            $table->string('name')->nullable()->comment('Имя артикула');

            $table->text('description')->nullable()->comment('Описание артикула услуги');

            $table->string('internal')->nullable()->comment('Имя генерируемого артикула');

            $table->integer('metrics_count')->nullable()->unsigned()->index()->comment('Количество метрик у артикула');
            $table->integer('compositions_count')->nullable()->unsigned()->index()->comment('Количество состава у артикула');

            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');

            $table->integer('archive')->nullable()->unsigned()->comment('Статус архива');

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_articles');
    }
}
