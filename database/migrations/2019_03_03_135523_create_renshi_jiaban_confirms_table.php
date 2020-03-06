<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenshiJiabanConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renshi_jiaban_confirms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->comment('编号');
            $table->string('id_of_agent')->comment('代理申请人ID');
            $table->string('uid_of_agent')->comment('代理申请人UID');
            $table->string('agent')->comment('代理申请人');
            $table->string('department_of_agent')->comment('代理申请人部门');
            $table->integer('index_of_auditor')->comment('审核人顺序');
            $table->string('id_of_auditor')->comment('审核人ID');
            $table->string('uid_of_auditor')->comment('审核人UID');
            $table->string('auditor')->comment('审核人');
            $table->string('department_of_auditor')->comment('审核人部门');
            $table->jsonb('application')->comment('申请信息');
            $table->integer('progress')->comment('确认进度');
            $table->integer('status')->comment('确认状态');
            $table->text('reason')->comment('事由');
            $table->text('camera_imgurl')->nullable()->comment('验证摄像');
            $table->text('remark')->nullable()->comment('备注');
            $table->jsonb('auditing')->nullable()->comment('审核信息');
            $table->boolean('archived')->default(false)->comment('是否归档');
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
        Schema::dropIfExists('renshi_jiaban_confirms');
    }
}
