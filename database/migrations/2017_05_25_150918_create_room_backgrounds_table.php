<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomBackgroundsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('room_backgrounds', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->string('background')->nullable()->comment('背景');
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
    Schema::dropIfExists('room_backgrounds');
  }
}
