<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return  void
   */
  public function up()
  {
    DB::beginTransaction();
    // Create table for storing roles
    Schema::create('roles', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 100)->unique();
      $table->string('display_name')->nullable();
      $table->string('description')->nullable();
      $table->timestamps();
    });

    // Create table for associating roles to users (Many-to-Many)
    Schema::create('role_user', function (Blueprint $table) {
      $table->integer('user_id')->unsigned();
      $table->integer('role_id')->unsigned();
      $table->primary(['user_id', 'role_id']);
    });

    // Create table for storing permissions
    Schema::create('permissions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 100)->unique();
      $table->string('display_name')->nullable();
      $table->string('description')->nullable();
      $table->string('path', 20)->nullable()->comment('菜单uri');
      $table->string('icon', 20)->nullable();
      $table->integer('parent_id')->default(0)->nullable();
      $table->unsignedTinyInteger('type')->default(0)->comment('类型(0-菜单,1-操作)');
      $table->timestamps();
    });

    // Create table for associating permissions to roles (Many-to-Many)
    Schema::create('permission_role', function (Blueprint $table) {
      $table->integer('permission_id')->unsigned();
      $table->integer('role_id')->unsigned();
      $table->primary(['permission_id', 'role_id']);
    });

    DB::commit();
  }

  /**
   * Reverse the migrations.
   *
   * @return  void
   */
  public function down()
  {
    Schema::dropIfExists('permission_role');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('role_user');
    Schema::dropIfExists('roles');
  }
}
