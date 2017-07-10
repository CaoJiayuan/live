<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-16
 * Time: 下午3:56
 */

namespace App\Repository;


use App\Entity\CreditRule;
use App\Entity\Gift;
use App\Entity\Lecturer;
use App\Entity\Meta;
use App\Entity\RewordRecord;
use App\Entity\Room;
use App\Entity\UserSign;
use App\Traits\ApiResponse;
use App\Traits\CreditsHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;

class UserRepository extends Repository
{
  use ApiResponse, CreditsHelper;

  public function edit($data)
  {
    $user = \Auth::user();

    $user->update(array_remove_empty($data));
  }

  public function password($data)
  {
    $user = \Auth::user();

    if (!\Hash::check($data['old_password'], $user->password)) {
      return $this->respondMessage(403, '原密码不正确');
    }
    $user->update([
      'password' => bcrypt($data['password']),
    ]);

    return $this->respondSuccess();
  }

  public function getRobots($userId)
  {
    $pre = \DB::getTablePrefix();

    return User::where('master_id', $userId)->get([
      'id',
      'name',
      'avatar',
      'level',
      \DB::raw("CONCAT('/img/level/', {$pre}users.level, '.png') AS level_img"),
      'name as nickname',
    ])->toArray();
  }

  public function sign($roomId)
  {
    $user = \Auth::user();
    if (UserSign::whereUserId($user->id)->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()->addSecond(-1)])->exists()) {
      $this->respondMessage(403, '今天您已经签过到了,明天再来吧');
    }
    return \DB::transaction(function () use ($user, $roomId) {
      $id = $user->id;
      $room = Room::find($roomId);
      if (!$room->permission && !$room->main) {
        $roomId = $room->main_id;
      }
      Meta::$roomId = $roomId;
      $reword = (int)Meta::getItem('sing_credit');
      $this->changeCredits($user, $reword, '签到奖励');
      $sign = UserSign::create([
        'user_id' => $id,
        'reword'  => $reword,
      ]);
      return $sign->toArray();
    });
  }

  public function reword($minutes, $roomId, $user)
  {
    $room = Room::find($roomId);
    if ($room && !$room->main) {
      $roomId = $room->main_id;
    }

    $reword = [
      'reword'  => 0,
      'minutes' => $minutes,
    ];
    if ($rule = CreditRule::whereRoomId($roomId)->where('minutes', $minutes)->first()) {
      $record = RewordRecord::whereUserId($user->id)->orderBy('created_at', 'desc')->first();
      if (RewordRecord::whereUserId($user->id)->where('credit_rule_id', $rule->id)->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()])->orderBy('created_at', 'desc')->exists()) {
        return $reword;
      }
      $pre = CreditRule::whereRoomId($roomId)->where('minutes', '<', $minutes)->orderBy('minutes', 'desc')->first();
      $m = 0;
      if ($pre) {
        $m = $minutes - $pre->minutes;
      }
      if ($record && $record->created_at && $record->created_at->timestamp > Carbon::now()->addMinutes(-$m)->timestamp) {
        return $reword;
      }
      $reword['reword'] = $rule->credits;
      \DB::transaction(function () use ($user, $rule) {
        $this->changeCredits($user, $rule->credits, '在线奖励');
        RewordRecord::create([
          'credit_rule_id' => $rule->id,
          'user_id'        => $user->id,
        ]);
      });

    }

    return $reword;
  }

  public function gift($id, $roomId)
  {
    $room = Room::find($roomId);
    if ($room && !$room->main) {
      $roomId = $room->main_id;
    }
    $lecturer = Lecturer::rightJoin('timetable_shows', function (JoinClause $clause) use ($roomId) {
      $clause->on('timetable_shows.status', '=',  \DB::raw("1"));
      $clause->on('timetable_shows.room_id', '=', \DB::raw("'$roomId'"));
      $clause->on('timetable_shows.lecturer_id', '=', 'lecturers.id');
    })->where('day', '=', Carbon::now()->dayOfWeek)
      ->where('hour', '=', Carbon::now()->hour)
      ->where('lecturers.id', '!=', null)
      ->first(['lecturers.*']);
    if (!$lecturer) {
      return $this->respondMessage(404, '当前没有讲师!');
    }
    $gift = Gift::find($id);
    \DB::beginTransaction();
    $this->changeCredits(\Auth::user(), -$gift->credits, '礼物送出');
    $lecturer->credits += $gift->credits;
    $lecturer->save();
    \DB::commit();
    return $gift;
  }
}