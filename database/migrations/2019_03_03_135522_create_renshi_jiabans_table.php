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
        Schema::create('renshi_jiabans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->comment('编号');
            $table->string('uid_of_agent')->comment('代理申请人UID');
            $table->string('agent')->comment('代理申请人');
            $table->string('department_of_agent')->comment('代理申请人部门');
            $table->string('uid_of_auditor')->comment('申请人UID');
            $table->string('auditor')->comment('申请人');
            $table->string('department_of_auditor')->comment('申请人部门');
            $table->jsonb('application')->comment('申请信息');
            // $table->string('applicant')->comment('申请人');
            // $table->string('department_of_applicant')->comment('申请部门');
            // $table->string('category')->comment('类别');
            // $table->timestamp('start_date')->comment('开始日期');
            // $table->timestamp('end_date')->comment('结束日期');
            // $table->integer('duration')->comment('期间');
            $table->integer('status')->comment('状态');
            $table->text('reason')->comment('事由');
            $table->text('remark')->nullable()->comment('备注');
            $table->jsonb('auditing')->nullable()->comment('审核信息');
            $table->boolean('archived')->nullable()->comment('是否归档');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::create('renshi_jiaban_subs', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('applicant')->comment('申请人');
        //     $table->string('department')->comment('申请部门');
        //     $table->string('category')->comment('类别');
        //     $table->timestamp('start_date')->comment('开始日期');
        //     $table->timestamp('end_date')->comment('结束日期');
        //     $table->integer('duration')->comment('期间');
        //     $table->text('reason')->comment('事由');
        //     $table->text('remark')->comment('备注');
        //     $table->timestamps();
        // });

        // Schema::create('renshi_jiaban_sub_2_mains', function (Blueprint $table) {
        //     $table->unsignedInteger('main_id')->comment('主编号');
        //     $table->unsignedInteger('sub_id')->comment('副编号');

        //     $table->foreign('main_id')
        //         ->references('id')
        //         ->on('renshi_jiaban_mains')
        //         ->onDelete('cascade');

        //     $table->foreign('sub_id')
        //         ->references('id')
        //         ->on('renshi_jiaban_subs')
        //         ->onDelete('cascade');

        //     $table->primary(['main_id', 'sub_id']);
        // });
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
