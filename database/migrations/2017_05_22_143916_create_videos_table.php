<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('videos', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->string('url')->comment('流url');
      $table->unsignedInteger('type')->comment('类型');
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
    Schema::dropIfExists('videos');
  }
}
