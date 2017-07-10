<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('advertisements', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title', 50)->comment('广告标题');
      $table->string('img')->comment('广告图片');
      $table->string('url')->nullable()->comment('广告链接');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->unsignedTinyInteger('status')->default(1)->comment('0-已删除,1-可用');
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
    Schema::dropIfExists('advertisements');
  }
}
