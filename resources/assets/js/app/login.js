/**
 * Created by d on 17-5-11.
 */
(function ($, window) {
  function Login() {

  }
  Login.action = function (form, onSuccess, onError) {
     $('button.submit').click(function () {
      $.ajax($(form).data('action'), {
        type    : 'POST',
        dataType: 'json',
        data    : $(form).serialize(),
        success : onSuccess,
        error   : onError
      })
    });
  };
  window.Login = Login;
})(jQuery, window);