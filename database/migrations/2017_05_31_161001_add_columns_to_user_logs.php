<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserLogs extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_logs', function (Blueprint $table) {
      $table->unsignedInteger('area_id')->index()->nullable()->comment('所属区域id');
      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
      $table->unsignedInteger('group_id')->index()->nullable()->comment('所属团队id');
      $table->unsignedInteger('agent_id')->index()->nullable()->comment('所属业务员id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('user_logs', function (Blueprint $table) {
      $table->dropColumn(['area_id', 'room_id','group_id','agent_id']);
    });
  }
}
