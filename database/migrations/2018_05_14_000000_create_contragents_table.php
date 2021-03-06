<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContragentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contragents', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('company_id')->nullable()->unsigned()->comment('ID компании');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('contragent_id')->nullable()->unsigned()->comment('ID контрагента');
            $table->foreign('contragent_id')->references('id')->on('companies');

            $table->integer('client_status')->nullable()->unsigned()->comment('Статус клиента (Null или 1)');
            $table->integer('vendor_status')->nullable()->unsigned()->comment('Статус поставщика (Null или 1)');
            $table->integer('manufacturer_status')->nullable()->unsigned()->comment('Статус производителя (Null или 1)');
            $table->integer('dealer_status')->nullable()->unsigned()->comment('Статус дилера (Null или 1)');

            $table->integer('display')->nullable()->unsigned()->comment('Отображение на сайте');

            $table->integer('sort')->nullable()->unsigned()->index()->comment('Поле для сортировки');

            $table->integer('author_id')->nullable()->unsigned()->comment('Id создателя записи');
            $table->foreign('author_id')->references('id')->on('users');

            $table->integer('editor_id')->nullable()->unsigned()->comment('Id редактора записи');
            $table->integer('system_item')->nullable()->unsigned()->comment('Флаг системной записи: 1 или null');
            $table->timestamps();
            $table->integer('moderation')->nullable()->unsigned()->comment('На модерации');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contragents');
    }
}
