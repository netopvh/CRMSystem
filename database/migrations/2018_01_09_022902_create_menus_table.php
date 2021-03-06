<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menus', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->nullable()->comment('Имя категории меню');
      $table->string('icon')->nullable()->comment('Имя иконки меню');
      $table->string('alias')->nullable()->comment('Ссылка на страницу');
      $table->string('tag')->nullable()->comment('Ключ для поиска');

      $table->integer('parent_id')->unsigned()->nullable()->comment('Id родителя пункта меню');
      // $table->foreign('parent_id')->references('id')->on('menus');

      $table->integer('navigation_id')->unsigned()->nullable()->comment('Id названия меню');
      $table->foreign('navigation_id')->references('id')->on('navigations');

      $table->integer('page_id')->unsigned()->nullable()->comment('Id страницы пункта меню');
      $table->foreign('page_id')->references('id')->on('pages');

      $table->integer('company_id')->nullable()->unsigned()->comment('ID компании');
      $table->foreign('company_id')->references('id')->on('companies');

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

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('menus');
  }
}
