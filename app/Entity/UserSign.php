<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\UserSign
 *
 * @property int $id
 * @property int $user_id
 * @property int $reword
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserSign whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserSign whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserSign whereReword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserSign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\UserSign whereUserId($value)
 * @mixin \Eloquent
 */
class UserSign extends Model
{

  protected $fillable = [
    'user_id', 'reword',
  ];
  protected $hidden = ['user_id'];
}
