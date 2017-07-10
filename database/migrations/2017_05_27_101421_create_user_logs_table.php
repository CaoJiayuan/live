<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_logs', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->string('ua')->nullable();
      $table->unsignedTinyInteger('type')->default(0)->comment('0-登陆');
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
    Schema::dropIfExists('user_logs');
  }
}
