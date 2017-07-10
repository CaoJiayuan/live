<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::beginTransaction();

    Schema::create('votes', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('room_id')->nullable()->index()->comment('所属房间id');
      $table->string('title');
      $table->boolean('enable')->default(false);
      $table->timestamps();
    });

    Schema::create('vote_options', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('vote_id')->index()->comment('所属投票id');
      $table->string('name');
      $table->integer('modify')->default(0)->comment('加减投票数');
    });

    DB::commit();
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('votes');
    Schema::dropIfExists('vote_options');
  }
}
