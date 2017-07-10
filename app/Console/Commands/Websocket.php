<?php

namespace App\Console\Commands;

use App\Entity\OnlineCount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Workerman\Worker;

class Websocket extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'ws:start';

  protected $users = [];
  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Websocket';

  /**
   * @var Worker
   */
  protected $worker;

  public function handle()
  {
    global $argv;
    $port = config('websocket.port');
    $host = config('websocket.host');
    $argv[1] = 'start';
    Worker::$logFile = 'ws.log';

    $worker = new Worker("websocket://{$host}:{$port}");
    $worker->count = 4;
    $worker->onConnect = [$this, 'onConnect'];
    $worker->onWorkerStart = [$this, 'onStart'];
    $worker->onWorkerStop = [$this, 'onStop'];
    $worker->onClose = [$this, 'onClose'];
    $worker->onMessage = [$this, 'onMessage'];

    Worker::runAll();
  }

  /**
   * @param \Workerman\Connection\TcpConnection $con
   */
  public function onConnect($con)
  {
    $this->comment("\n{$con->getRemoteIp()} Connected..");
  }

  /**
   * @param Worker $worker
   */
  public function onStart($worker)
  {
    $this->worker = $worker;
  }


  /**
   * @param Worker $worker
   */
  public function onStop($worker)
  {

  }

  /**
   * @param \Workerman\Connection\TcpConnection $con
   */
  public function onClose($con)
  {
    $info = $this->users[$con->id];
    $userId = $info[0];
    $roomId = $info[1];
    $arr = [
      'user_id' => $userId,
      'room_id' => $roomId
    ];
    $this->info("User {$userId} leave room {$roomId}");
    \DB::table('user_rooms')->where($arr)->delete();

    unset($this->users[$con->id]);
    $this->comment("{$con->getRemoteIp()} Closed..\n");
  }

  /**
   * @param \Workerman\Connection\TcpConnection $con
   * @param string $data
   */
  public function onMessage($con, $data)
  {
    if (!str_contains($data, '|')) {
      return;
    }
    $info = explode('|', $data);
    $this->users[$con->id] = $info;
    $userId = $info[0];
    $roomId = $info[1];
    $this->info("User {$userId} Enter room {$roomId}");
    $arr = [
      'user_id' => $userId,
      'room_id' => $roomId
    ];
    if (!\DB::table('user_rooms')->where($arr)->exists()) {
      \DB::table('user_rooms')->insert($arr);
    }

    $online = OnlineCount::firstOrCreate([
      'date' => Carbon::now()->toDateString()
    ]);
    $now = \DB::table('user_rooms')->count();
    $this->info("Online user [$now]");

    if ($now > $online->num) {
      $online->update([
        'num' => $now
      ]);
    }
  }

  function __call($name, $arguments)
  {

  }
}
