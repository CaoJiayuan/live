<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\UserLog
 *
 * @property int $id
 * @property int $user_id
 * @property bool $type 0-登陆
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereUserId($value)
 * @mixin \Eloquent
 * @property int $area_id 所属区域id
 * @property int $room_id 所属房间id
 * @property int $group_id 所属团队id
 * @property int $agent_id 所属业务员id
 * @property string $ua
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereAgentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereAreaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereUa($value)
 * @property string $logout_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserLog whereLogoutAt($value)
 */
class UserLog extends Model
{
  protected $fillable = [
    'area_id',
    'room_id',
    'group_id',
    'agent_id',
    'user_id',
    'logout_at',
    'type',
    'ua',
  ];
}
