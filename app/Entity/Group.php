<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Group
 *
 * @property int $id
 * @property string $name
 * @property int $room_id 所属房间id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Group whereRoomId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{

  protected $fillable = [
    'room_id', 'name'
  ];
}
