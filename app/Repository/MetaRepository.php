<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-15
 * Time: 上午11:49
 */

namespace App\Repository;


use App\Entity\Meta;

class MetaRepository
{

  public function getMetaData($keys)
  {
    if (is_array($keys)) {
      $result = [];
      $keys = array_unique($keys);
      foreach ($keys as $key) {
        $result[$key] = Meta::getItem($key);
      }
      return $result;
    } else {
      return Meta::getItem($keys);
    }
  }
}