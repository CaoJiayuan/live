<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Area
 *
 * @property int $id
 * @property string $name
 * @property bool $enable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Area whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Area whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Area whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
  protected $fillable = [
    'name','enable'
  ];
}
