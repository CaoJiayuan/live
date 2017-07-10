<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-31
 * Time: 上午11:13
 */

namespace App\Repository;


use App\Entity\Room;
use App\Entity\Vote;
use App\Entity\VoteOption;
use App\Entity\VoteUser;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class VoteRepository extends Repository
{
  use ApiResponse;
  public function getVotes($roomId)
  {
    $room = Room::find($roomId);

    if (!$room->permission && !$room->main) {
      $roomId = $room->main_id;
    }
    $pre = \DB::getTablePrefix();
    $user = \Auth::user();
    $raw = <<<SQL
MAX((SELECT COUNT({$pre}vote_users.id) FROM {$pre}vote_users WHERE {$pre}vote_users.vote_option_id = {$pre}vote_options.id AND {$pre}vote_users.vote_id = {$pre}votes.id) + ({$pre}vote_options.modify)) AS num
SQL;

    $builder = Vote::with(['options' => function ($builder) use ($user) {
      /** @var Builder $builder */
      $builder->leftJoin('vote_users', 'vote_users.vote_option_id', '=', 'vote_options.id')
        ->leftJoin('vote_users as vu', function (JoinClause $clause) use ($user) {
          $clause->on('vu.vote_option_id', '=', 'vote_options.id');
          $clause->on('vu.user_id', '=', \DB::raw("'{$user->id}'"));
        });
      $pre = \DB::getTablePrefix();
      $builder->select([
        'vote_options.id',
        'vote_options.name',
        'vote_options.vote_id',
        \DB::raw("COUNT({$pre}vote_users.id) + vote_options.modify  AS option_num"),
        \DB::raw("CASE WHEN {$pre}vu.id IS NULL THEN false ELSE true END AS voted"),
      ])
//        ->orderBy('option_num', 'desc')
        ->having('vote_options.id','>', 0)
        ->groupBy('vote_options.id');
    }])
      ->where('room_id', $roomId)
      ->leftJoin('vote_users', function (JoinClause $clause)  use ($user) {
          $clause->on('vote_users.vote_id', '=', 'votes.id');
          $clause->on('vote_users.user_id', '=',  \DB::raw("'{$user->id}'"));
      })
      ->leftJoin('vote_options', 'vote_options.vote_id', '=', 'votes.id')
      ->groupBy('votes.id')
      ->where('enable', true)->select([
        'votes.id',
        'votes.title',
        \DB::raw("CASE WHEN {$pre}vote_users.id IS NULL THEN false ELSE true END AS voted"),
        \DB::raw($raw),
      ]);


    return $builder->get();
  }

  public function vote($data)
  {
    if (VoteUser::where([
      'vote_id' => $data['vote_id'],
      'user_id' => $data['user_id']
    ])->exists()) {
      return $this->respondMessage(403, '你已经投过票了!');
    }
    VoteUser::firstOrCreate($data, $data);
  }
}