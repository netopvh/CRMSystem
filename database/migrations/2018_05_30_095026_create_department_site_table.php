<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_site', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('department_id')->nullable()->unsigned()->comment('Id филиала/отдела');
            $table->foreign('department_id')->references('id')->on('departments');
            
            $table->integer('site_id')->nullable()->unsigned()->comment('Id сайта');
            $table->foreign('site_id')->references('id')->on('sites');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_site');
    }
}
