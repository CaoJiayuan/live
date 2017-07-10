<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('gifts', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 50);
      $table->unsignedMediumInteger('credits');
      $table->string('img');
      $table->unsignedInteger('gift_category_id')->index();
      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
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
    Schema::dropIfExists('gifts');
  }
}
