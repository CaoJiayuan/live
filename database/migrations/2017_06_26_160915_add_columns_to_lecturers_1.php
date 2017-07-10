<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLecturers1 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('lecturers', function (Blueprint $table) {
      $table->unsignedInteger('credits')->default(0)->comment('积分');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('lecturers', function (Blueprint $table) {
      $table->dropColumn('credits');
    });
  }
}
