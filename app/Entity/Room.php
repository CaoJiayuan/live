<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Entity\Room
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 讲师id
 * @property string $title 标题
 * @property string $lss_user_id 直播服务用户id
 * @property string $app_id 直播服务app id
 * @property string $stream 直播服务stream
 * @property string $cover 封面
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereAppId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereCover($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereLssUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereStream($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereUserId($value)
 * @property int $area_id 区域id
 * @property string $web_title 网页标题
 * @property string $logo LOGO
 * @property string $icon 图标
 * @property string $company_name 公司名称
 * @property string $background 背景
 * @property int $register_capacity 注册人数上限
 * @property int $online_capacity 在线人数上限
 * @property int $video_id 当前视频
 * @property int $main_id 主房间id
 * @property bool $main 是否是主房间
 * @property bool $permission 是否放权
 * @property bool $tourist 游客功能
 * @property bool $enable 是否开启
 * @property bool $vote 是否开启投票
 * @property bool $chat 是否开启聊天
 * @property bool $popup 是否显示弹窗
 * @property int $modify_num 在线人数编辑
 * @property int $chat_interval 发言间隔(s)
 * @property int $max_length 最大消息长度(字)
 * @property bool $video_position 视频位置,0-左,1-中,2-右
 * @property string $qr_code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\MaskingWord[] $maskings
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereAreaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereBackground($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereChat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereChatInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereCompanyName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereMain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereMainId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereMaxLength($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereModifyNum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereOnlineCapacity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room wherePermission($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room wherePopup($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereQrCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereRegisterCapacity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereTourist($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereVideoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereVideoPosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereVote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereWebTitle($value)
 * @property bool $covered
 * @property-read \App\Entity\Timetable $schedule
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereCovered($value)
 * @property string $remark
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Room whereRemark($value)
 */
class Room extends Model
{
  protected $fillable = [
    'area_id',
    'user_id',
    'title',
    'logo',
    'icon',
    'company_name',
    'background',
    'register_capacity',
    'online_capacity',
    'video_id',
    'main',
    'permission',
    'tourist',
    'enable',
    'vote',
    'chat',
    'popup',
    'modify_num',
    'chat_interval',
    'max_length',
    'video_position',
  ];

  protected $casts = [
    'covered' => 'bool'
  ];

  public function maskings()
  {
    return $this->hasMany(MaskingWord::class);
  }

  public function schedule()
  {
    /** @var Builder $hasOne */
    $hasOne = $this->hasOne(Timetable::class);

    $mainId = $this->main_id;
    if ($this->main) {
      $mainId = $this->id;
    }

    return $hasOne->where('timetables.room_id', $mainId)
      ->where('time', '>', Carbon::now()->addHour(-1))
      ->leftJoin('lecturers', 'lecturer_id', '=', 'lecturers.id')
      ->select([
        'timetables.*',
        'lecturers.name as lecturer',
      ])
      ->where('time', '<=', Carbon::now());
  }
}
