<?php

use Illuminate\Database\Seeder;

class EntrustSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $roles = config('entrust.roles', [
      'service' => [
        'name'         => 'service',
        'display_name' => '客服',
      ],
      'admin'   => [
        'name'         => 'admin',
        'display_name' => '管理员',
      ],
      'lecturer' => [
        'name'         => 'lecturer',
        'display_name' => '讲师',
      ],
    ]);
    DB::table('roles')->truncate();
    DB::table('permissions')->truncate();
    DB::table('permission_role')->truncate();
    \App\Entity\Role::insert($roles);
    factory(\App\User::class, 3)
      ->create()
      ->each(function ($user) {
        /** @var \App\User $user */
        $user->attachRole(1);
      });
  }
}
