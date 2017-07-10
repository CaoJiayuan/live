<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-15
 * Time: 上午11:49
 */

namespace App\Repository;

use App\Entity\Good;
use App\Entity\Order;
use App\Traits\ApiResponse;
use App\Traits\CreditsHelper;

class OrderRepository extends Repository
{

  use ApiResponse, CreditsHelper;

  public function order($id)
  {
    if ($good = Good::find($id, ['title', 'img', 'credits'])) {
      \DB::transaction(function () use ($good, $id) {
        $user = \Auth::user();
        Order::create([
          'user_id' => $user->id,
          'good_id' => $id,
        ]);

        $this->changeCredits($user, -$good->credits, '积分商品兑换');
      });

      return $good;
    }

    $this->respondMessage(404, '商品不存在');
  }
}