<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-9
 * Time: 下午5:17
 */

namespace App\Http\Controllers;


use App\Entity\UserLog;
use App\Repository\RoomRepository;
use App\Util\DmsClient;

class ChatController extends Controller
{
  public function histories($topic)
  {
    $response = DmsClient::history($topic, \Request::get('page'),  \Request::get('page_size'));
    $body = $response->getContent();
    $msgs = json_decode($body, true);
    $result = [];

    foreach ($msgs as $msg) {
      $result[] = $this->transformMsg($msg);
    }
    return array_reverse($result);
  }

  protected function transformMsg($msg)
  {
    $message = array_get($msg, 'msg', $msg);
    return json_decode($message) ?: $message;
  }

  public function users(RoomRepository $repository, $roomId)
  {
    return $repository->users($roomId);
  }

  public function leave(RoomRepository $repository, $roomId)
  {
    $user = \Auth::user();
    return $repository->leave($user, $roomId);
  }
}