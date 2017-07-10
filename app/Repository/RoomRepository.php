<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-9
 * Time: 上午10:55
 */

namespace App\Repository;


use App\Entity\CreditRule;
use App\Entity\GiftCategory;
use App\Entity\Good;
use App\Entity\MaskingWord;
use App\Entity\Notice;
use App\Entity\Popup;
use App\Entity\Room;
use App\Entity\RoomBackground;
use App\Entity\Service;
use App\Entity\Timetable;
use App\Entity\TimetableShow;
use App\Entity\UserLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

class RoomRepository
{


  protected $week = [
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday',
  ];

  public function join($roomId, $user)
  {
    if (!\DB::table('user_rooms')->where([
      'user_id' => $user->id,
      'room_id' => $roomId,
    ])->exists()
    ) {
      \DB::beginTransaction();
      \DB::table('user_rooms')->insert([
        'user_id' => $user->id,
        'room_id' => $roomId,
      ]);
      \DB::commit();
    }
    \DB::beginTransaction();
    $attributes = [
      'user_id' => $user->id,
      'type'    => 0,
      'ua'      => $user->ua,
    ];
    $attributes = array_merge($attributes, array_only($user->toArray(), [
      'area_id',
      'room_id',
      'group_id',
      'agent_id',
    ]));
    if ($log = UserLog::whereUserId($user->id)->where('logout_at')->whereType(0)->orderBy('created_at', 'desc')->first()) {
      $log->update([
        'logout_at' => Carbon::now(),
      ]);
    }
    UserLog::create($attributes);
    User::where('id', $user->id)->update([
      'online' => true,
    ]);
    \DB::commit();
  }

  public function leave($id, $userId, $loginId = null)
  {
    $arr = [
      'user_id' => $userId,
      'room_id' => $id,
    ];
    \DB::table('user_rooms')->where($arr)->delete();
    if ($user = User::find($userId)) {
      \DB::beginTransaction();

      if ($loginId == $user->login_id) {
        User::where('id', $userId)->update([
          'online' => false,
        ]);

        if ($log = UserLog::whereUserId($userId)->where('logout_at')->whereType(0)->orderBy('created_at', 'desc')->first()) {
          $log->update([
            'logout_at' => Carbon::now(),
          ]);
        }
      }

      \DB::commit();
    }
  }

  public function users($roomId, \Closure $closure = null)
  {
    $room = Room::find($roomId);
    $num = $room->modify_num;
    if (!$room->permission && !$room->main) {
      if ($main = Room::find($room->main_id)) {
        $num = $main->modify_num;
      }
    }


    $pre = \DB::getTablePrefix();

    $roomIds = [$roomId];
    $children = $this->children($roomId);

    foreach ($children as $child) {
      $roomIds[] = $child['id'];
    }

    $builder = User::leftJoin('user_rooms', 'user_rooms.user_id', '=', 'users.id')
      ->select([
        'users.id',
        'users.level',
        'users.nickname',
        'users.name',
        'users.avatar',
        'users.gender',
        \DB::raw("CONCAT('/img/level/', {$pre}users.level, '.png') AS level_img"),
      ])
      ->whereIn('user_rooms.room_id', $roomIds);

    $builder->orWhereIn('id', function ($b) use ($roomIds) {
      /** @var Builder $b */
      $b->from('users as r');
      $b->select('r.id');
      $b->whereIn('master_id', function ($bm) use ($roomIds) {
        /** @var Builder $bm */
        $bm->from('users as m');
        $bm->select(['m.id']);
        $bm->whereIn('id', function ($bmr) use ($roomIds) {
          /** @var Builder $bmr */
          $bmr->from('user_rooms as mr');
          $bmr->whereIn('mr.room_id', $roomIds);
          $bmr->select(['mr.user_id']);
        });
      });
    });

    $builder->groupBy('users.id')
      ->orderBy('users.level', 'desc');
    $closure && $closure($builder);
    $page = $builder->paginate(20);
    $page = $page->toArray();
    $page['total'] += (int)$num;
    return $page;
  }

  /**
   * @param Room $room
   * @param User $user
   * @return mixed|null
   */
  public function getService($room, $user)
  {
    $service = null;

    if ($room && $user) {
      if ($user->hasRole([array_keys(config('entrust.roles', []))])) {
        return $service;
      }
      $roomId = $room['id'];
      $builder = User::leftJoin('user_rooms', 'user_rooms.user_id', '=', 'users.id')
        ->select(['users.*', 'roles.name as role_name'])
        ->where('user_rooms.room_id', $roomId);
      $builder->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', config('entrust.roles.service.name', 'service'));
      $service = $builder->first();
    }
    return $service;
  }

  public function children($id)
  {
    return Room::where([
      'main_id' => $id,
      'enable'  => true,
    ])->get([
      'id',
    ])->toArray();
  }

  public function notice($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }
    return Notice::where('room_id', $roomId)
      ->orderBy('id', 'desc')
      ->where('enable', true)->get()->toArray();
  }

  public function backgrounds($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }

    return RoomBackground::where('room_id', $roomId)
      ->orderBy('id', 'desc')->get()->toArray();
  }

  public function services($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }
    return Service::where('room_id', $roomId)->get()->toArray();
  }

  public function maskings($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }
    return MaskingWord::whereRoomId($roomId)->get(['word'])->toArray();
  }

  public function popup($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }

    return Popup::where('enable', true)->where('room_id', $roomId)->first();
  }

  public function schedules($roomId)
  {

    $room = Room::find($roomId);

    if (!$room->main) {
      $roomId = $room->main_id;
    }

    $tables = TimetableShow::orderBy('hour')
      ->where('timetable_shows.room_id', $roomId)
      ->leftJoin('lecturers', 'lecturer_id', '=', 'lecturers.id')
      ->orderBy('day')->get([
        'timetable_shows.*',
        'lecturers.name as lecturer',
      ])->groupBy('hour');

    return $this->reformatTables($tables);
  }

  public function reformatTables($tables)
  {
    $ts = [];
    $tables->each(function ($row, $day) use (&$ts) {
      $ts[$day] = $row->groupBy('day');
    });
    $hours = range(9, 22);
    $days = range(1, 7);
    $result = [];
    foreach ($hours as $hour) {
      $row = array_get($ts, $hour, []);
      if ($row) {
        $r = [];
        foreach ($days as $day) {
          $cell = array_get($row, $day, [
            [
              'id'          => null,
              'title'       => null,
              'hour'        => $hour,
              'day'         => $day,
              'lecturer'    => null,
              'time'        => Carbon::createFromTimestamp(strtotime($this->week[$day] . ' this week'))->addHours($hour)->timestamp,
              'lecturer_id' => null,
            ],
          ]);
          $r[] = array_get($cell, 0, null);
        }
        $result[$hour] = $r;
      }
    }

    return $result;
  }

  public function creditRules($roomId)
  {
    $room = Room::find($roomId);
    if ($room && !$room->main) {
      $roomId = $room->main_id;
    }

    return CreditRule::whereRoomId($roomId)->whereNotIn('id', function ($builder) {
      /** @var Builder $builder */
      $builder->from('reword_records');
      $builder->select(['reword_records.credit_rule_id']);
      $builder->where('user_id', \Auth::id());
      $builder->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
    })->orderBy('minutes')->get(['id', 'minutes'])->toArray();
  }

  public function goods($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }

    return Good::where('status', 1)
      ->where('room_id', $roomId)
      ->paginate(8);
  }

  public function gifts($roomId)
  {
    $room = Room::find($roomId);
    if ($room && !$room->main) {
      $roomId = $room->main_id;
    }
    return GiftCategory::with(['gifts' => function ($builder) use ($roomId) {
      $builder->where('room_id', $roomId);
    }])->get()->toArray();
  }
}