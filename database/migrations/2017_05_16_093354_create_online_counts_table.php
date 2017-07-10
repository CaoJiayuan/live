<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('online_counts', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date')->comment('日期');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');

      $table->unsignedInteger('num')->default(0);
      $table->timestamp('time')->nullable();
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
    Schema::dropIfExists('online_counts');
  }
}
