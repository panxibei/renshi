<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->nullable()->comment('申请人工号');
            $table->string('name')->unique()->comment('申请人姓名');
            $table->string('department')->comment('申请人部门');
            $table->jsonb('applicant_group')->nullable()->comment('批量申请人员组信息');
            $table->jsonb('auditing')->nullable()->comment('审核信息');
            $table->string('ldapname')->nullable()->comment('ldap用户名');
            $table->string('email')->nullable();
            $table->string('displayname')->nullable();
            $table->string('password');
			$table->timestamp('login_time')->comment('登录时间');
			$table->integer('login_ttl')->default(0)->comment('登录有效时间');
			$table->string('login_ip',15)->default(null)->comment('登录ip');
			$table->integer('login_counts')->default(0)->comment('登录次数');
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();
			// $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
