<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lecturers', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 20);
      $table->text('desc')->nullable()->comment('讲师介绍');
      $table->string('qq')->nullable()->comment('qq');
      $table->string('mobile')->nullable()->comment('手机号');
      $table->string('avatar')->nullable()->comment('头像');
      $table->unsignedTinyInteger('gender')->default(0)->nullable()->comment('性别');
      $table->boolean('enable')->default(true)->comment('是否启用');
      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
      $table->unsignedInteger('group_id')->index()->nullable()->comment('所属团队id');
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
    Schema::dropIfExists('lecturers');
  }
}
