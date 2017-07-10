<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-8
 * Time: 下午4:29
 */

namespace App\Util;


use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class DmsClient
{

  const API_URL = 'http://api.dms.aodianyun.com/';

  public static function getClient($config = [])
  {
    $default = [
      'headers'     => [
        'Authorization' => 'dms ' . config('aodian.dms.s_key')
      ],
      'http_errors' => false
    ];
    $config = array_merge($config, $default);
    return new Client($config);
  }

  public static function history($topic, $page = 1, $num = 20)
  {
    $page > 0 || $page = 1;
    $skip = ($page - 1) * 20;

    return self::get('v2/historys', [
      'topic' => $topic,
      'num'   => $num,
      'skip'   => $skip,
    ]);
  }

  public static function get($uri, $data = [])
  {
    $uri = ltrim($uri, '/');
    $response = static::getClient()->get(static::API_URL . $uri, ['query' => $data]);

    return self::toIlluminateResponse($response);
  }

  public static function toIlluminateResponse($response)
  {
    /** @var \Illuminate\Http\Response $res */
    $res = response($response->getBody(), $response->getStatusCode(), array_except($response->getHeaders(), 'Transfer-Encoding'));
    $statusTexts = Response::$statusTexts;
    (!$res->isSuccessful() && env('APP_ENV') == 'local') && \Log::error(array_get($statusTexts, $res->getStatusCode(), 'Unknown') . ':' . $res->getContent());

    return $res;
  }


}