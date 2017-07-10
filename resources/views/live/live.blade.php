<div class="center-right container-fluid">
  <div class="player col-xs-9">
    <!--刷新-->
    <div class="header-action clearfix">
      <span>视频直播</span>
      <span class="refresh pull-right">
            <img src="./img/reset.png" alt="">
            <button id="J_refresh">刷新</button>
          </span>
    </div>
    <!--最新公告-->
    <div class="notice-m">
      <img src="./img/notice.png" alt="">公告
    </div>
    <!--视频-->

    @include('live.player')
    <!--版权说明-->
    <div class="notice">
      <ul id="notice" class="nav nav-tabs">
        <li class="active">
          <a href="#newNotice" data-toggle="tab">
            最新公告
          </a>
        </li>
        <li>
          <a href="#versions" data-toggle="tab">
            版权说明
          </a>
        </li>
        <li>
          <a href="#disclaimer" data-toggle="tab">
            免责声明
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="newNotice">
          <!--<img src="./img/banner.png" width="100%" height="100%" class="img-responsive" alt="">-->
          <img src="./img/banner.png" width="100%" height="100%" alt="">

        </div>
        <div class="tab-pane fade" id="versions">
          {!! $copyright !!}
        </div>
        <div class="tab-pane fade" id="disclaimer">
          {!! $disclaimer !!}
        </div>
      </div>
    </div>
  </div>

    <div class="chat-cont col-xs-3">
      <ul class="chat-list"></ul>
      @if(Auth::user())
      <div class="chat-speak">
        <div class="speak-up clearfix">
          <button title="表情"><img src="./img/expression.png">表情</button>

          <label class="chat-img" for="chat-img"><img src="./img/img.png">图片 <input type="file" id="chat-img"></label>
        </div>
        <div class="speak-center">
          <button id="send" class="pull-right">发送</button>
          <textarea maxlength="200" id="chat-text"></textarea>
        </div>
      </div>
      @endif
    </div>

</div>
