<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('vote_users', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id')->index();
      $table->unsignedInteger('vote_id')->index();
      $table->unsignedInteger('vote_option_id')->index();
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
    Schema::dropIfExists('vote_users');
  }
}
