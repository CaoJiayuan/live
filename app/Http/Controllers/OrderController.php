<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-6-28
 * Time: 下午3:36
 */

namespace App\Http\Controllers;


use App\Repository\OrderRepository;

class OrderController extends Controller
{

  public function order(OrderRepository $repository, $id)
  {
    return $repository->order($id);
  }
}