<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditRulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('credit_rules', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->index()->nullable()->comment('所属房间id');
      $table->unsignedSmallInteger('minutes');
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
    Schema::dropIfExists('credit_rules');
  }
}
