<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-16
 * Time: 下午3:55
 */

namespace App\Http\Controllers;


use App\Entity\IpBan;
use App\Entity\UserSign;
use App\Repository\UserRepository;
use App\Traits\RoomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{

  use RoomHelper;

  public function editUser(UserRepository $repository)
  {
    $data = $this->getValidatedData([
      'nickname' => 'required',
      'avatar',
    ]);
    $repository->edit($data);
    return $this->respondSuccess();
  }

  public function editPassword(UserRepository $repository)
  {
    $data = $this->getValidatedData([
      'old_password' => 'required',
      'password'     => 'required|string|min:6|confirmed',
    ]);

    return $repository->password($data);
  }

  public function getUser(Request $request)
  {
    $ip = $request->ip();
    if (IpBan::where('ip', $ip)->exists()) {
      return $this->respondMessage(403, 'IP已被禁用');
    }
    $user = \Auth::user();
    $user->level_img = "/img/level/{$user->level}.png";
    if (!$user->room = $this->getAccessibleRoom()) {
      return $this->respondMessage(403, '该房间未开启');
    }
    if (!$user->enable) {
      return $this->respondMessage(403, '用户已被禁用');
    }
    $user->ip = $user->last_ip;
    $user->signed = UserSign::whereUserId($user->id)->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()->addSecond(-1)])->exists();
    $ua = $request->header('User-Agent');
    $device = 'PC';
    if (preg_match('/Android/i', $ua)) {
      $device = 'Android';
    }

    if (preg_match('/(ios|ipad|iphone|ipod)/i', $ua)) {
      $device = 'ios';
    }
    $user->device = $user->ua = $device;
    $user->nickname = $user->nickname ?: $user->name;
    return $user->setVisible([
      'id',
      'username',
      'level',
      'level_img',
      'mobile',
      'level',
      'nickname',
      'credits',
      'avatar',
      'gender',
      'room_id',
      'ip',
      'device',
      'room',
      'status',
      'signed',
      'ua',
      'login_id',
    ]);
  }

  public function getRobots(UserRepository $repository, $userId)
  {
    return $repository->getRobots($userId);
  }

  public function sign(UserRepository $repository, $roomId)
  {
    return $repository->sign($roomId);
  }

  public function reword(UserRepository $repository, $roomId)
  {
    $data = $this->getValidatedData([
      'minutes',
    ]);
    $user = \Auth::user();
    return $repository->reword($data['minutes'], $roomId, $user);
  }

  public function gift(UserRepository $repository, $roomId)
  {
    $data = $this->getValidatedData([
      'id' => 'required',
    ]);
    return $repository->gift($data['id'], $roomId);
  }
}