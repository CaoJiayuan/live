<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('goods', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
      $table->string('title', 100);
      $table->string('img');
      $table->unsignedTinyInteger('status')->default(1);
      $table->unsignedMediumInteger('credits');
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
    Schema::dropIfExists('goods');
  }
}
