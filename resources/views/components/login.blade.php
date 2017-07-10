<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content ">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true ">&times;</span></button>
        <h3 class="modal-title " ><img src="./img/modal-logo.png " alt="金生金 "></h3>
      </div>
      <div class="modal-body ">
        <h3 class="text-center ">登录账号</h3>
        <form class="form-horizontal"  data-action="{{ route('login') }}" id="login-form">
          {{ csrf_field() }}

          <div class="form-group  has-feedback ">
            <label for="user-number" class="control-label col-xs-3 text-right ">手机号码</label>
            <div class="col-xs-8 ">
              <div class="input-group">
                <input type="tel" name="mobile" id="user-number" pattern="^1[3|4|5|7|8][0-9]{9}$" title="请输入手机号码"  aria-describedby="inputNnberStatus" placeholder="请输入手机号码" class="form-control ">
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback " aria-hidden="false"></span>
              <span id="inputMunberStatus" class="sr-only">(error)</span>
            </div>
          </div>
          <div class="form-group  has-feedback">
            <label for="password " class="control-label col-xs-3 text-right ">密码</label>
            <div class="col-xs-8 ">
              <div class="input-group">
                <input type="password" id="password" name='password' class="form-control" placeholder="请输入密码" aria-describedby="inputPasswordStatus">
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback " aria-hidden="false"></span>
              <span id="inputPasswordStatus" class="sr-only">(error)</span>
            </div>
          </div>
          <div class="form-group ">
            <a class="col-xs-offset-9 link" href=" ">忘记密码？</a>
          </div>
          <div class="form-group text-center">
            <button type="button" class="submit" id="login-btn" disabled>立即登录</button>
          </div>
          <div class="form-group text-center">
            <a class="link" href="/tourist">游客登录</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>