<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<!-- UC强制全屏 -->
{{--<meta name="full-screen" content="yes">--}}
{{--<!-- QQ强制全屏 -->--}}
{{--<meta name="x5-fullscreen" content="true">--}}
{{--<!-- UC应用模式 -->--}}
{{--<meta name="browsermode" content="application">--}}
{{--<!-- QQ应用模式 -->--}}
{{--<meta name="x5-page-mode" content="app">--}}
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui">
  <title>{{ $room->web_title ?: $room->title }}</title>
  <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

  @yield('css')
  <script>
    window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    window.WS_HOST = '{{ config('websocket.host')}}';
    window.WS_PORT = '{{ config('websocket.port')}}';
  </script>
</head>

<body @if($room->background && !preg_match('/(ios|ipad|iphone|Android|iPod)/i',Request::header('User-Agent'))) style="background-image: url('{{ $room->background }}');" @endif>

<div id="app" style="width:100%;height:100%;top: 0;position: absolute;">
<router-view></router-view>
</div>
</body>
<script>
  window.pubKey = '{{ config('aodian.dms.pub_key') }}';
  window.subKey = '{{ config('aodian.dms.sub_key') }}';
</script>
<script type="text/javascript" src="http://cdn.aodianyun.com/lss/aodianplay/player.js"></script>
<script type="text/javascript" src="http://cdn.aodianyun.com/dms/rop_client.js"></script>
<script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/iScroll/5.2.0/iscroll.min.js"></script>

<script>window.FileAPI = {
    staticPath: '/js/fileapi/dist/',

    flashUrl: '/js/fileapi/dist/FileAPI.flash.swf',

    flashImageUrl: '/js/fileapi/dist/FileAPI.flash.image.swf'
};
</script>
<script src="/js/toastr.min.js"></script>
<script src="/js/socket.io.js"></script>
<script src="/js/sprintf.js"></script>
<script src="/js/alert7.min.js"></script>
<script src="/js/live.js"></script>
<script src="/js/fileapi/dist/FileAPI.js"></script>
@yield('js')
</html>