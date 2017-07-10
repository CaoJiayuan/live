<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTableShowsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('timetable_shows', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('lecturer_id')->index();
      $table->string('title')->nullable();
      $table->unsignedTinyInteger('hour');
      $table->unsignedTinyInteger('day');
      $table->timestamp('time')->nullable();
      $table->unsignedTinyInteger('status')->default(true);
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
    Schema::dropIfExists('timetable_shows');
  }
}
