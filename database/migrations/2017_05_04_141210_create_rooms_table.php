<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('rooms', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('area_id')->comment('区域id')->index();
      $table->unsignedInteger('user_id')->comment('讲师id')->index();
      $table->string('web_title')->nullable()->comment('网页标题');
      $table->string('title')->comment('标题');
      $table->string('logo')->nullable()->comment('LOGO');
      $table->string('icon')->nullable()->comment('图标');
      $table->string('company_name')->comment('公司名称');
      $table->string('background')->nullable()->comment('背景');
      $table->unsignedInteger('register_capacity')->comment('注册人数上限');
      $table->unsignedInteger('online_capacity')->comment('在线人数上限');
      $table->unsignedInteger('video_id')->nullable()->index()->comment('当前视频');
      $table->unsignedInteger('main_id')->index()->comment('主房间id');
      $table->boolean('main')->default(false)->comment('是否是主房间');
      $table->boolean('permission')->default(false)->comment('是否放权');
      $table->boolean('tourist')->default(false)->comment('游客功能');
      $table->boolean('enable')->default(true)->comment('是否开启');
      $table->boolean('vote')->default(true)->comment('是否开启投票');
      $table->boolean('chat')->default(true)->comment('是否开启聊天');
      $table->boolean('popup')->default(true)->comment('是否显示弹窗');
      $table->unsignedInteger('modify_num')->default(0)->comment('在线人数编辑');
      $table->unsignedInteger('chat_interval')->default(0)->comment('发言间隔(s)');
      $table->unsignedInteger('max_length')->default(100)->comment('最大消息长度(字)');
      $table->unsignedTinyInteger('video_position')->default(1)->comment('视频位置,0-左,1-中,2-右');
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
    Schema::dropIfExists('rooms');
  }
}
