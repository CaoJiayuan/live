<!-- 个人中心 -->
<div class="modal fade" id="UcenterModal" tabindex="-1" role="dialog" aria-labelledby="UcenterModal">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content ">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true ">&times;</span></button>
        <h3 class="modal-title">个人中心</h3>
      </div>
      <div class="modal-body ">
        <div class="container-fluid">
          @if($u = Auth::user())
            <div class="row">
              <div class="u-center-nav pull-left">
                <div class="u-center-header">
                  <div class="u-center-img">
                    <img src="{{ $u->avatar ?: '/img/userimg.png' }}" alt="">
                  </div>
                  <p class="text-center">{{ $u->nickname }}</p>
                  <p class="text-center">等级：<img src="./img/platinum.png" alt=""></p>
                  <p class="text-center">积分：{{ $u->credits }}</p>
                </div>
                <ul class="nav nav-tabs nav-stacked ">
                  <li class="active text-center">
                    <a href="#Uinfo" data-toggle="tab">
                      个人资料
                    </a>
                  </li>
                  <li class="text-center">
                    <a href="#CPwd" data-toggle="tab">
                      修改密码
                    </a>
                  </li>
                </ul>
              </div>
              <div class="u-center-right pull-right">
                <div class="tab-content">
                  <div class="tab-pane fade in active" id="Uinfo">
                    <form class="form-horizontal" id="user-form" data-action="/user">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="col-sm-2 control-label">当前头像:</label>
                        <div class="col-sm-10">
                          <img id="uCenterImg" src='{{ $u->avatar ?: '/img/userimg.png' }}'>
                          <label class="select-img-btn" for="selectImg">编辑头像</label>
                          <input type="file" id="selectImg" accept="image/*"/>
                          <input type="hidden" name="avatar" id="avatar-data" value="{{ $u->avatar  }}">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="uCenterName" class="col-sm-2 control-label">昵称:</label>
                        <div class="col-sm-10">
                          <input class="form-row input-text" name="nickname" value="{{ $u->nickname }}" type="text">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">手机号码:</label>
                        <div class="col-sm-10">
                          <span class="form-row">{{ $u->mobile }}</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">客户经理:</label>
                        <div class="col-sm-10">
                          <span class="form-row">隔壁的</span>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" class="btn save" id="user-btn">保存</button>
                          <button class="btn cancel">取消</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade in " id="CPwd">
                    <form class="form-horizontal" data-action="/user/password" id="password-form">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label for="uCenterName" class="col-sm-2 control-label">原密码:</label>
                        <div class="col-sm-10">
                          <input class="form-row input-text" name="old_password" type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="uCenterName" class="col-sm-2 control-label">新密码:</label>
                        <div class="col-sm-10">
                          <input class="form-row input-text" name="password" type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="uCenterName" class="col-sm-2 control-label">确认密码:</label>
                        <div class="col-sm-10">
                          <input class="form-row input-text" name="password_confirmation" type="password">
                        </div>
                      </div>


                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" class="btn save" id="password-btn">保存</button>
                          <button class="btn cancel">取消</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

              </div>

            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>