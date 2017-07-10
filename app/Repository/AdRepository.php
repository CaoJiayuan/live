<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-6-2
 * Time: 上午9:30
 */

namespace App\Repository;


use App\Entity\Advertisement;
use App\Entity\Room;

class AdRepository extends Repository
{

  public function banners($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }
    return Advertisement::where('room_id', $roomId)
      ->whereStatus(true)
      ->orderBy('id', 'desc')
      ->take(3)->get()->toArray();
  }
}