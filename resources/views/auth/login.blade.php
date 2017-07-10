<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/toastr.min.css" rel="stylesheet">

  <style>
    html,
    body {
      height: 100%;
      padding: 0;
      width: 100%;
    }

    body {
      background: url('/img/loginbg.png') no-repeat;
      background-size: cover;
      position: relative;
    }

    .login {
      width: 100%;
      max-width: 480px;
      margin: 0 auto 0;
      padding: 0 10px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform:translate(-50%, -50%);
    }

    .login .center {
      padding: 0 20px;
      border: 12px solid rgba(255, 255, 255, .5);
      border-radius: 10px;
      background-color: rgba(255, 255, 255, .8);
    }

    h3 {
      margin: 55px 0;
    }

    .submit {
      background-color: #6b6485;
      width: 100%;
      border-radius: 5px;
      border: none;
      color: #fff;
      height: 46px;
      line-height: 46px;
      margin-top: 15px;
    }

    .form-group {
      position: relative;
    }

    .form-group img {
      position: absolute;
      left: 14px;
      top: 8px;
      width: 25px;
      z-index: 7;
    }

    .form-group input {
      height: 41px;
      padding-left: 53px;
    }

    .form-group a {
      color: #808080;
    }
  </style>
</head>
<body>

<div class="container-fuild">
  <div class="login">
    <div class="center">
      <h3 class="text-center ">登录账号</h3>
      <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
          <img src="/img/loginuser.png">
          <input type="tel" name="username" value="{{ old('username') }}" id="user-number" class="form-control " placeholder="请输入登陆帐号">
          @if ($errors->has('username'))
            <span class="help-block">
              <strong>{{ $errors->first('username') }}</strong>
            </span>
          @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <img src="/img/loginpwd.png">
          <input type="password" id="password" name="password" class="form-control" placeholder="请输入密码"
                 aria-describedby="inputPasswordStatus">
          @if ($errors->has('password'))
            <span class="help-block">
               <strong>{{ $errors->first('password') }}</strong>
            </span>
          @endif
        </div>
        @if($roomId = Request::get('room_id'))
        <input type="hidden" name="room_id" value="{{ $roomId }}">
        @endif
        {{--<div class="form-group "><a class="pull-right link" href=" ">忘记密码？</a></div>--}}
        <div class="form-group text-center">
          <button type="submit" class="submit">立即登录</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="/js/toastr.min.js"></script>
@if(Session::has('error'))
  <script>
    $(function () {
      toastrNotification('error','{{Session::get('error')}}')
    })
  </script>
@endif
</body>
</html>