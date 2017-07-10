<div class="topBar clearfix">
  <div class="logo pull-left">
    <img src="/img/logo.png" alt="金生金">
  </div>
  <div class="top-right pull-left ">
    <div class="col-xs-3 pull-right clearfix">
      <!--注释部分为游客界面-->
      @if(Auth::guest())
        <div class="login pull-right">
          <a data-toggle="modal" data-target="#loginModal">登录 </a> |
          <a data-toggle="modal" data-target="#signinModal">注册 </a>
        </div>
      @else
        @if(\Auth::user()->mobile)
          <div class="login pull-right">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
              <input type="hidden" name="room_id" value="{{ $room ? $room['id'] : 0 }}">
            </form>
          </div>
          <span class="user pull-right">

        <label >
          <a data-toggle="modal" data-target="#UcenterModal">
            <img src="{{ \Auth::user()->avatar ?: '/img/userimg.png' }}" alt="">
          </a>
        </label> {{ \Auth::user()->nickname }}
        </span>
        @else
          <div class="login pull-right">
            <a data-toggle="modal" data-target="#loginModal">登录 </a> |
            <a data-toggle="modal" data-target="#signinModal">注册 </a>
          </div>
          <span class="user pull-right">
         {{ \Auth::user()->nickname }}
      </span>
        @endif
      @endif
    </div>
    <div class="col-xs-9 clearfix">
      <button class="pull-left save-desktop"><img src="./img/desktop.png" alt="">保存到桌面</button>
      <ul class="nav nav-pills pull-right">
        <li role="presentation"><a href="#">基本信息管理</a></li>
        <li role="presentation"><a href="#">管理员管理</a></li>
        <li role="presentation"><a href="#">分公司管理</a></li>
        <li role="presentation"><a href="#">用户管理</a></li>
        <li role="presentation" class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
             aria-expanded="false">
            新闻资讯管理 <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="text-center" role="presentation"><a href="#">独家专栏</a></li>
            <li class="text-center" role="presentation"><a href="#">广告管理</a></li>
            <li class="text-center" role="presentation"><a href="#">公告管理</a></li>
            <li class="text-center" role="presentation"><a href="#">课程表编辑</a></li>

          </ul>
        </li>
        <li role="presentation"><a href="#">数据管理</a></li>

      </ul>
    </div>
  </div>
</div>
@include('components.login')
@include('components.register')
@include('components.ucenter')
