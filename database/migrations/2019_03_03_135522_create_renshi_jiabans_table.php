<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenshiJiabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renshi_jiaban_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('agent')->comment('代理申请人');
            $table->string('department')->comment('代理申请部门');
            $table->timestamps();
        });

        Schema::create('renshi_jiaban_subs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('applicant')->comment('申请人');
            $table->string('department')->comment('申请部门');
            $table->string('leibie')->comment('申请部门');
            $table->timestamps('kaishi_riqi')->comment('申请部门');
            $table->timestamps('jiesu_riqi')->comment('申请部门');
            $table->string('liyou')->comment('申请部门');
            $table->string('remark')->comment('申请部门');
            $table->timestamps();
        });

        Schema::create($tableNames['renshi_jiaban_sub_2_mains'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('main_id')->comment('主编号');
            $table->unsignedInteger('sub_id')->comment('副编号');;

            $table->foreign('main_id')
                ->references('id')
                ->on(renshi_jiaban_mains)
                ->onDelete('cascade');

            $table->foreign('sub_id')
                ->references('id')
                ->on('renshi_jiaban_subs')
                ->onDelete('cascade');

            $table->primary(['main_id', 'sub_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jiabans');
    }
}
