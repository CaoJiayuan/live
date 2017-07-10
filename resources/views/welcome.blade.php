<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
              Welcome
            </div>
        </div>



    </body>

    <script type="text/javascript" src="http://cdn.aodianyun.com/static/jquery/jquery-1.7.2.min.js"></script><!-- jquery地址也可使用您的地址 -->
    <script type="text/javascript" src="http://cdn.aodianyun.com/lss/aodianplay/player.js"></script>
    <script type="text/javascript">
      var objectPlayer= new aodianPlayer({
        container:'play',//播放器容器ID，必要参数
        rtmpUrl: 'rtmp://29765.lssplay.aodianyun.com/120106578/stream',//控制台开通的APP rtmp地址，必要参数
        hlsUrl: 'http://29765.hlsplay.aodianyun.com/120106578/stream.m3u8',//控制台开通的APP hls地址，必要参数
        /* 以下为可选参数*/
        width: '720',//播放器宽度，可用数字、百分比等
        height: '480',//播放器高度，可用数字、百分比等
        autostart: true,//是否自动播放，默认为false
        bufferlength: '1',//视频缓冲时间，默认为3秒。hls不支持！手机端不支持
        maxbufferlength: '2',//最大视频缓冲时间，默认为2秒。hls不支持！手机端不支持
        stretching: '1',//设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小,默认值为1。hls初始设置不支持，手机端不支持
        controlbardisplay: 'enable',//是否显示控制栏，值为：disable、enable默认为disable。
        //adveDeAddr: '',//封面图片链接
        //adveWidth: 320,//封面图宽度
        //adveHeight: 240,//封面图高度
        //adveReAddr: '',//封面图点击链接
        //isclickplay: false,//是否单击播放，默认为false
        isfullscreen: true//是否双击全屏，默认为true
      });
      /* rtmpUrl与hlsUrl同时存在时播放器优先加载rtmp*/
      /* 以下为Aodian Player支持的事件 */
      /* objectPlayer.startPlay();//播放 */
      /* objectPlayer.pausePlay();//暂停 */
      /* objectPlayer.stopPlay();//停止 hls不支持*/
      /* objectPlayer.closeConnect();//断开连接 */
      /* objectPlayer.setMute(true);//静音或恢复音量，参数为true|false */
      /* objectPlayer.setVolume(volume);//设置音量，参数为0-100数字 */
      /* objectPlayer.setFullScreenMode(1);//设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小,默认值为1。手机不支持 */
    </script>
</html>
