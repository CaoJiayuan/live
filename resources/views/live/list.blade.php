<div class="center-left">
  <!--工具菜单-->
  <div class="tips ">
    <ul class="clearfix">
      <li class="tips-item text-center">
        <a href=""><img src="/img/calendar.png" alt="">
          <p>财经日历</p>
        </a>

      </li>
      <li class="tips-item text-center">
        <a href=""><img src="/img/tactics.png" alt="">
          <p>交易策略</p>
        </a>
      </li>
      <li class="tips-item text-center">
        <a href=""><img src="/img/course.png" alt="">
          <p>课程表</p>
        </a>
      </li>

    </ul>
  </div>
  <!--在线客户&客服-->
  <div class="user-list">
    <header>
      <ul id="count" class="nav nav-tabs">
        <li class="active">
          <a href="#userBlock" data-toggle="tab">
            在线人数
            <p id="userCount">(0)</p>
          </a>
        </li>
        <li>
          <a href="#service" data-toggle="tab">
            我的客服
            <p>({{ $service ? 1 : 0 }})</p>
          </a>
        </li>
      </ul>
    </header>
    <div id="J_userList" class="tab-content">
      <div class="tab-pane fade in active" id="userBlock">
        <ul  id="userList">
        </ul>
      </div>
      <div class="tab-pane fade in" id="service">
        <ul>
          @if($service)
            <li class="user-item">
              <img class="user-img" src="{{ $service->avatar ?: '/img/userimg.png' }}" alt="">
              <span class="user-name">{{ $service->nickname }}</span>
            </li>
          @endif
        </ul>
      </div>
    </div>

  </div>

  <!--展开/关闭按钮-->

</div>
