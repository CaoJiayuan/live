<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('notices', function (Blueprint $table) {
      $table->increments('id');
      $table->text('content')->comment('内容');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->boolean('enable')->default(true);
      $table->timestamp('published_at')->comment('发布时间');
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
    Schema::dropIfExists('notices');
  }
}
