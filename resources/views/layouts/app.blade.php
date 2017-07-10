<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
  <meta name="keywords" content="{{ $meta['title'] }}">
  <title>{{ $meta['title'] }}</title>

  <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/index.css" type="text/css" rel="stylesheet">
  @yield('css')
  <script>
    window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    window.WS_HOST = '{{ config('websocket.host')}}';
    window.WS_PORT = '{{ config('websocket.port')}}';
  </script>
</head>

<body>
@yield('content')
</body>
<script>
  window.room   = {!!  json_encode($room)  !!};
  window.user   = {!!  json_encode($user)  !!};
  window.pubKey = '{{ config('aodian.dms.pub_key') }}';
  window.subKey = '{{ config('aodian.dms.sub_key') }}';
</script>
<script type="text/javascript" src="http://cdn.aodianyun.com/lss/aodianplay/player.js"></script>
<script type="text/javascript" src="http://cdn.aodianyun.com/dms/rop_client.js"></script>
<script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="/js/live.js"></script>
@yield('js')
</html>