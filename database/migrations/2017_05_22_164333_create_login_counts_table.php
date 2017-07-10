<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginCountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('login_counts', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date')->comment('日期');
      $table->unsignedInteger('num')->default(0);
      $table->timestamp('time')->nullable();
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');

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
    Schema::dropIfExists('login_counts');
  }
}
