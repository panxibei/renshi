<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJiabansTable extends Migration
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
            $table->string('main_id')->unique()->comment('主编号');
            $table->string('agent')->comment('代理申请人');
            $table->string('department')->comment('代理申请部门');
            $table->timestamps();
        });

        Schema::create('renshi_jiaban_subs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sub_id')->unique()->comment('主编号');
            $table->string('agent')->comment('代理申请人');
            $table->string('department')->comment('代理申请部门');
            $table->json('info');

            $table->timestamps();
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
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
