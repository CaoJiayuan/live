<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\RewordRecord
 *
 * @property int $id
 * @property int $credit_rule_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\RewordRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\RewordRecord whereCreditRuleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\RewordRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\RewordRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\RewordRecord whereUserId($value)
 * @mixin \Eloquent
 */
class RewordRecord extends Model
{
  protected $fillable = [
    'credit_rule_id',
    'user_id',
  ];
}
