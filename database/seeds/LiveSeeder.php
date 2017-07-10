<?php

use Illuminate\Database\Seeder;

class LiveSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = \Faker\Factory::create('zh_CN');

    \App\Entity\Room::create([
      'user_id'     => factory(\App\User::class)->create()->id,
      'title'       => $faker->company,
      'lss_user_id' => config('aodian.lss.user_id'),
      'app_id'      => '120106578',
      'stream'      => 'stream',
      'cover'       => $faker->imageUrl(),
    ]);
  }
}
