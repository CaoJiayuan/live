<?php

namespace App\Http\Controllers\Auth;

use App\Entity\IpBan;
use App\Entity\UserLog;
use App\Http\Controllers\Controller;
use App\Repository\RoomRepository;
use App\Traits\RoomHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers, RoomHelper;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function username()
  {
    return 'username';
  }

  public function logout(Request $request)
  {
    $user = $this->guard()->user();
    if ($user) {
      $room = $this->getAccessibleRoom($user);
      $room && (new RoomRepository())->leave($room->id, $user->id, $user->login_id);
    }
    $this->guard()->logout();

    $request->session()->flush();

    $request->session()->regenerate();

    return redirect('/login');
  }

  public function tourist(Request $request)
  {
    $this->loginTourist($request);
    return redirect('/');
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

    return $this->guard()->login($user, true);
  }

  protected function getToken()
  {
    return str_replace('.', '', uniqid('tourist_', true));
  }

  /**
   * @param Request $request
   * @param User $user
   * @return \Illuminate\Http\RedirectResponse|mixed
   */
  protected function authenticated(Request $request, $user)
  {
    $errors = null;

    if (!$room = $this->getAccessibleRoom($user)) {
      $errors = [$this->username() => '该房间未开启'];
    }

    if (!$user->enable) {
      $errors = [$this->username() => '该用户已被禁用,请联系管理员'];
    }
    $ip = $request->ip();

    if (IpBan::where('ip', $ip)->exists()) {
      $errors = [$this->username() => '该IP被禁用,请联系管理员'];
    }

    if ($errors) {
      $this->guard()->logout();
      $request->session()->regenerate();
      return redirect()->back()->withErrors($errors);
    }
    $ua = $request->header('User-Agent');
    $device = 'PC';
    if (preg_match('/Android/i', $ua)) {
      $device = 'Android';
    }
    if (preg_match('/(ios|ipad|iphone|ipod)/i', $ua)) {
      $device = 'ios';
    }
    $user->ua = $device;
    $user->update([
      'online'     => true,
      'last_login' => Carbon::now(),
      'last_ip'    => $request->getClientIp(),
      'ua'         => $device,
      'login_id'   => $request->session()->getId(),
    ]);
    (new RoomRepository())->join($room->id, $user);

    return redirect()->intended($this->redirectPath());
  }
}
