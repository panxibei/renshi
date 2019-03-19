<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenshiEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renshi_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->comment('工号');
            $table->string('applicant')->comment('申请人姓名');
            $table->string('department')->comment('申请部门');
            $table->string('category')->comment('类别');
            $table->timestamp('start_date')->comment('开始时间');
            $table->timestamp('end_date')->comment('结束时间');
            $table->integer('duration')->comment('时长');



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
        Schema::dropIfExists('renshi_employees');
    }
}
