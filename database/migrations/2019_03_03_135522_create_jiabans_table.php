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
        Schema::create('jiabans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('main_id')->comment('主编号');
            $table->string('applicant')->comment('申请人');
            $table->string('department')->comment('申请部门');
            $table->json('info');

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
        Schema::dropIfExists('jiabans');
    }
}
