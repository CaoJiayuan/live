<?php

namespace App;

use App\Entity\Room;
use App\Entity\UserLog;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Role[] $roles
 * @mixin \Eloquent
 * @property int $id
 * @property string $mobile 登陆手机号
 * @property string $password 登陆密码
 * @property string $last_ip 上次登陆ip
 * @property bool $status 1-普通状态, 0-禁言
 * @property int $level 用户等级
 * @property string $nickname 昵称
 * @property int $credits 积分
 * @property string $email 邮箱
 * @property string $avatar 头像
 * @property bool $gender 性别
 * @property bool $online 在线
 * @property bool $enable 是否启用
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCredits($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereOnline($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @property string $tourist_token 游客凭证session保存
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTouristToken($value)
 * @property string $username 登陆用户名
 * @property string $name 名称
 * @property string $qq qq
 * @property int $area_id 所属区域id
 * @property int $inviter_id 推广人id
 * @property int $master_id 主人id(不是机器人为null)
 * @property int $room_id 所属房间id
 * @property int $group_id 所属团队id
 * @property int $agent_id 所属业务员id
 * @property string $last_login 上次登陆时间
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAgentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAreaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereInviterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereMasterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereQq($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @property string $ua
 * @property string $login_id
 * @property-read \App\Entity\UserLog $log
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLoginId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUa($value)
 */
class User extends Authenticatable
{
  use Notifiable, EntrustUserTrait;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username',
    'password',
    'last_ip',
    'status',
    'level',
    'name',
    'nickname',
    'credits',
    'email',
    'avatar',
    'gender',
    'online',
    'enable',
    'tourist_token',
    'area_id',
    'inviter_id',
    'master_id',
    'room_id',
    'group_id',
    'agent_id',
    'last_login',
    'ua',
    'login_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];


  public function log()
  {
    return $this->hasOne(UserLog::class)->orderBy('created_at', 'desc');
  }
}
