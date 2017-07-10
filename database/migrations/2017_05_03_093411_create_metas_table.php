<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('metas', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->string('key', 150);
      $table->text('value')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('metas');
  }
}
