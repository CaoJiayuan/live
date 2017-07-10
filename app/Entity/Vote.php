<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Vote
 *
 * @property int $id
 * @property int $room_id 所属房间id
 * @property string $title
 * @property bool $enable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Vote whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\VoteOption[] $options
 */
class Vote extends Model
{
  protected $fillable = [
    'room_id',
    'title',
    'enable',
  ];

  protected $casts = [
    'voted' => 'bool',
    'num' => 'int',
  ];

  public function options()
  {
    return $this->hasMany(VoteOption::class);
  }
}
