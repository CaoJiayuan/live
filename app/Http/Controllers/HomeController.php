<?php

namespace App\Http\Controllers;

use App\Repository\MetaRepository;
use App\Repository\RoomRepository;
use App\Traits\RoomHelper;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

  use RoomHelper;

  public function index(RoomRepository $repository, MetaRepository $metaRepository, Request $request)
  {
    $user = Auth::user();
    if ($user) {
      $user->addHidden([
        'mobile',
        'password',
        'last_ip',
        'status',
        'credits',
        'email',
        'online',
        'tourist_token',
        'updated_at',
        'created_at',
      ]);
    }

    $room = $repository->join($request->get('room'));
    $service = $repository->getService($room, $user);
    $data = [
      'room'    => $room,
      'user'    => $user ? $user->toArray() : null,
      'service' => $service,
    ];
    $metas = $metaRepository->getMetaData([
      'disclaimer', 'copyright',
    ]);
    $data = array_merge($data, $metas);
    return view('live.index', $data);
  }

  public function home()
  {
    $room = $this->getAccessibleRoom();

    if (!$room) {
      return redirect('/login')->withErrors(['mobile' => trans('auth.failed')]);
    }
    return view('index', [
      'room' => $room,
    ]);
  }

  public function download()
  {
    $url = url('/');
    $filename = str_replace(['http://','https://'], ['',''], $url) .'.html';
    $content = sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="1;url=%1$s" />

        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

    return response($content, 200, [
      'Content-Type'        => 'application/octet-stream',
      'Content-Disposition' => 'attachment; filename=' . $filename,
    ]);
  }

  public function admin()
  {
    return redirect(env('ADMIN_ADDRESS', 'http://liveadmin.honc.tech/login'));
  }
}
