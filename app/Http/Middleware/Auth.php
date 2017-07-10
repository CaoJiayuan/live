<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-6-23
 * Time: 上午10:29
 */

namespace App\Http\Middleware;


use App\Entity\UserLog;
use App\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class Auth extends Authenticate
{
  public function handle($request, Closure $next, ...$guards)
  {
    $this->authenticate($guards);
    $user = \Auth::user();
//    dd($user->login_id, $request->session()->getId());
    if ($user->login_id != $request->session()->getId()) {
      $message = 'relogin';
      \Session::flash('error', $message);
      throw new AuthenticationException($message, $guards);
    }

    return $next($request);
  }
}