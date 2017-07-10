<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-27
 * Time: 下午3:58
 */

namespace App\Traits;


use App\Entity\Room;
use App\Entity\Timetable;
use App\Entity\TimetableShow;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

trait RoomHelper
{
  public function getAccessibleRoom($user = null)
  {
    /** @var User $user */
    $user || $user = \Auth::user();
    $columns = [
      'rooms.id',
      'rooms.title',
      'rooms.logo',
      'rooms.icon',
      'rooms.background',
      'rooms.vote',
      'rooms.chat',
      'rooms.popup',
      'rooms.main',
      'rooms.covered',
      'rooms.cover',
      'rooms.main_id',
      'rooms.qr_code',
      'rooms.web_title',
      'rooms.video_position',
      'rooms.chat_interval',
      'rooms.max_length',
      'rooms.permission',
      'videos.type',
      'videos.url',
      'videos.mobile_url',
    ];
    if ($user->hasRole(config('roles.super_admin.name')) ) {
      if ($roomId = \Request::get('room_id')) {
        $user->room_id = $roomId;
      }
    }

    if ($user->hasRole(config('roles.area_admin.name'))) {
      $id = $user->area_id;
      $builder = Room::where('area_id', $id)
        ->where('main', 1)->orderBy('rooms.id');
      $this->withVideo($builder);
      $room = $builder->select($columns)->first();
    } else {
      $builder = (new Room)->newQuery();
      $main = Room::where('id', $user->room_id)->where('main', true)->exists();
      $this->withVideo($builder, $main);
      $room = $builder->where('rooms.id', $user->room_id)->select($columns)->first();
    }
    if ($room) {
      $room->admin = false;
      if (\DB::table('role_user')->where('user_id', $user->id)->exists()) {
        $room->admin = true;
      }
      $mainId = $room->main_id;
      if ($room->main) {
        $mainId = $room->id;
      }
      $room->schedule =  TimetableShow::where('timetable_shows.room_id', $mainId)
        ->where('day', '=', Carbon::now()->dayOfWeek)
        ->where('hour', '=', Carbon::now()->hour)
        ->leftJoin('lecturers', 'lecturer_id', '=', 'lecturers.id')
        ->select([
          'timetable_shows.*',
          'lecturers.name as lecturer',
          'lecturers.credits',
        ])->first();
      if (!$room->permission && !$room->main) {
        $roomId = $room->main_id;
        $main = Room::find($roomId);
        if ($main) {
          $room->vote = $main->vote;
          $room->chat = $main->chat;
          $room->popup = $main->popup;
          $room->qr_code = $main->qr_code;
          $room->video_position = $main->video_position;
          $room->background = $main->background;
          $room->logo = $main->logo;
          $room->max_length = $main->max_length;
          $room->chat_interval = $main->chat_interval;
          $room->cover = $main->cover;
          $room->covered = $main->covered;
        }
      }
    }

    return $room;
  }

  /**
   * @param Builder $builder
   * @param bool $main
   */
  protected function withVideo($builder, $main = true)
  {
    $builder->where('rooms.enable', true);
    $builder->groupBy('rooms.id');
    if ($main) {
      $builder->leftJoin('videos', 'video_id', '=', 'videos.id');
    } else {
      $builder->leftJoin('rooms as mr', 'rooms.main_id', '=', 'mr.id');
      $builder->leftJoin('videos', 'mr.video_id', '=', 'videos.id')
        ->where('mr.enable', true);
    }
    $builder->leftJoin('masking_words', 'masking_words.room_id', '=', 'rooms.id');
  }
}