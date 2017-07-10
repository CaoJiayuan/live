<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
      $table->string('username', 120)->nullable()->unique()->comment('登陆用户名');
      $table->string('password')->nullable()->comment('登陆密码');
      $table->string('last_ip', 64)->nullable()->comment('上次登陆ip');
      $table->tinyInteger('status')->default(1)->comment('1-普通状态, 0-禁言');
      $table->unsignedInteger('level')->default(1)->comment('用户等级');
      $table->string('name', 50)->nullable()->comment('名称');
      $table->string('nickname', 50)->nullable()->comment('昵称');
      $table->integer('credits')->default(0)->comment('积分');
      $table->string('qq')->nullable()->comment('qq');
      $table->string('mobile')->nullable()->comment('手机号');
      $table->string('avatar')->nullable()->comment('头像');
      $table->unsignedTinyInteger('gender')->nullable()->comment('性别');
      $table->boolean('online')->default(false)->comment('在线');
      $table->boolean('enable')->default(true)->comment('是否启用');
      $table->string('tourist_token')->nullable()->comment('游客凭证session保存');
      $table->unsignedInteger('area_id')->index()->nullable()->comment('所属区域id');
      $table->unsignedInteger('inviter_id')->index()->nullable()->comment('推广人id');
      $table->unsignedInteger('master_id')->index()->nullable()->comment('主人id(不是机器人为null)');

      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
      $table->unsignedInteger('group_id')->index()->nullable()->comment('所属团队id');
      $table->unsignedInteger('agent_id')->index()->nullable()->comment('所属业务员id');
      $table->timestamp('last_login')->nullable()->comment('上次登陆时间');
      $table->rememberToken();
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
    Schema::dropIfExists('users');
  }
}
