<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;

class Tourist
{
  /**
   * @var Auth
   */
  private $auth;

  public function __construct(Auth $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $this->authenticate($request);

    return $next($request);
  }

  public function getToken()
  {
    return str_replace('.', '', uniqid('tourist_', true));
  }

  protected function authenticate($request)
  {
    try {
      $this->auth->authenticate();
    } catch (AuthenticationException $exception) {
      $this->loginTourist($request);
    }
  }

  protected function loginTourist($request)
  {
    $sToken = \Session::get('tourist_token');
    if (!$sToken || (!$user = User::whereTouristToken($sToken)->first())) {
      $token = $this->getToken();
      $user = User::create([
        'tourist_token' => $token,
      ]);
      $user->update([
        'last_ip'  => $request->getClientIp(),
        'nickname' => '游客-' . $user->id,
        'online'   => true,
      ]);
      \Session::put('tourist_token', $token);
    } else {
      $user->update([
        'last_ip' => $request->getClientIp(),
        'online'  => true,
      ]);
    }

    return $this->auth->login($user, true);
  }
}
