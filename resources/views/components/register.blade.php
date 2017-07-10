<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="register-title">
  <div class="modal-dialog modal-md " role="document">
    <div class="modal-content ">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true ">&times;</span></button>
        <h3 class="modal-title" id="register-title"><img src="./img/modal-logo.png " alt="金生金 "></h3>
      </div>
      <div class="modal-body ">
        <h3 class="text-center ">注册账号</h3>
        <form class="form-horizontal " id="register-form" data-action="{{ route('register') }}">
          {{ csrf_field() }}
          <div class="form-group  has-feedback ">
            <label for="Rnumber" class="control-label col-xs-3 text-right ">手机号码</label>
            <div class="col-xs-8 ">
              <div class="input-group">
                <input type="tel" id="Rnumber" name="mobile" pattern="^1[3|4|5|7|8][0-9]{9}$" title="11位手机号码" aria-describedby="RnumberStatus" placeholder="请输入手机号码"
                       class="form-control ">
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback " aria-hidden="false"></span>
              <span id="RnumberStatus" class="sr-only">(error)</span>
            </div>

          </div>
          <div class="form-group  has-feedback">
            <label for="inputVerify " class="control-label col-xs-3 text-right ">验证码</label>
            <div class="col-xs-5 ">
              <div class="input-group">
                <input type="text" name="code" id="inputVerify" class="form-control " placeholder="6位数字验证码" aria-describedby="inputVerifyStatus">
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback " aria-hidden="false"></span>
              <span id="inputVerifyStatus" class="sr-only">(error)</span>
            </div>
            <div class="col-xs-3 ">
              <button type="button" name="verify" id="verify">获取验证码</button>
            </div>
          </div>
          <div class="form-group  has-feedback ">
            <label for="Rpassword" class="control-label col-xs-3 text-right ">设置密码</label>
            <div class="col-xs-8 ">
              <div class="input-group">
                <input name="password" type="password" pattern="^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$" title="6-12数字和字母组合" id="Rpassword" aria-describedby="RpasswordStatus"
                       placeholder="6-12数字或字母组合" class="form-control ">
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback " aria-hidden="false"></span>
              <span id="RpasswordStatus" class="sr-only">(error)</span>
            </div>
          </div>
          <div class="form-group text-center">
            <div class="checkbox">
              <label class="agreement">
                <input type="checkbox" id="agreement"> 我已阅读并同意<a href="" target="_blank">《《用户注册协议》》</a>
              </label>
            </div>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="submit" disabled id="register-btn">立即注册</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>