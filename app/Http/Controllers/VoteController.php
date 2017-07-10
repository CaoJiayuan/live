<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-31
 * Time: 上午11:13
 */

namespace App\Http\Controllers;


use App\Repository\VoteRepository;

class VoteController extends Controller
{
  public function getVotes(VoteRepository $repository, $roomId)
  {
    return $repository->getVotes($roomId);
  }

  public function postVote(VoteRepository $repository)
  {
    $data = $this->getValidatedData([
      'vote_id'        => 'required',
      'vote_option_id' => 'required',
    ]);
    $data['user_id'] = \Auth::user()->id;
    $repository->vote($data);
  }
}