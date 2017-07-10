var userlist = $("#J_userList");
var offset   = $('.tips').height() + 151;
var chat;
$(document).ready(function () {

  setMaxheight(userlist, offset);
  verifyForm($('#loginModal'));
  verifyForm($('#signinModal'));
  initChat();
  sendImg();
  initWs();
  setTimeout(initList, 500);
  $('#loginBtn').on('click', function () {
    $('#loginModal').modal('toggle')
  });
  register();
  previewFile();
  postForm($('#user-form'), $('#user-btn'));
  postForm($('#password-form'), $('#password-btn'))
});

// 在线人数区域高度适应
function setMaxheight(e, offset) {
  e.css('height', window.innerHeight - offset);
  e.panel({
    iWheelStep: 32
  });
}

function verifyForm(e) {
  var inputList = e.find("input:not(input[type=checkbox])"),
      checkbox  = e.find("input[type=checkbox]") || undefined;
  for (var i = 0; i < inputList.length; i++) {
    inputList.eq(i).bind('input propertychange', function () {
      if ($(this).val()) {
        e.find('.submit').removeAttr('disabled');
        inputList.eq(i).parents('.form-group').removeClass('has-error')
      }
    });
  }
  e.find('#login-btn').on('click', function () {
    var form = $('#login-form');
    $.ajax({
      url     : form.data('action'),
      type    : 'POST',
      dataType: 'json',
      data    : form.serialize(),
      success : function (data) {
        window.location.reload()
      },
      error   : function (xhr) {
        var ele = '<div id="login-error" class="form-group has-error text-center"><span class="help-block"><strong>%s</strong></span></div>';
        $('#login-error').remove();
        form.prepend(sprintf(ele, xhr.responseJSON.message))
      }
    });
    var checked = checkbox.is(':checked');
    for (var i = 0; i < inputList.length; i++) {
      if (inputList.eq(i).val()) {
        inputList.eq(i).parents('.form-group').removeClass('has-error')
      } else {
        inputList.eq(i).parents('.form-group').addClass('has-error')
        return false
      }
    }
    if (!checked && checkbox.length) {
      checkbox.parents('.form-group').addClass('has-error')
      return false
    }
    return true
  });
}

function initChat() {
  chat   = new DmsChat('room_' + room.id + '-' + datef('YYYY-MM-dd', new Date()));
  var msgId  = 0;
  var msgMax = 200;
  chat.onMessage(function (data, topic) {
    var avatar = data.user.avatar;
    avatar == null && (avatar = '/img/userimg.png');
    var ele = sprintf('<li class="clearfix" id="%s"><div class="user-img pull-left"><img src="%s" alt="">' +
      '</div><div class="user-desc pull-left"><span class="user-name">%s</span>' +
      '<span class="data">%s</span><div class="chat">%s</div></div></li>', 'msg-' + msgId, avatar, data.user.nickname, data.time, data.msg);
    $('.chat-list').append(ele);
    $('#msg-' + (msgId - msgMax)).remove();
    msgId++;
  });
  if (room != null && user != null) {
    $('#send').click(function () {
      var input = $('#chat-text').val();
      if (input.length > 0) {
        chat.send({
          user: user,
          type: 0,
          msg : input,
          time: datef('HH:mm:ss', new Date())
        });
        $('#chat-text').val('')
      }
    });
  }
}

function initList() {
  $.get('/api/chat/' + room.id + '/users', function (data) {
    $('#userCount').html(sprintf("(%s)", data.total));
    var list = '';

    data.data.forEach(function (item) {
      var icon = '';
      switch (item.role_name) {
        case 'service':
          icon = '<i class="service-icon"></i>';
          break;
        case 'lecturer':
          icon = '<i class="lecturer-icon"></i>';
          break;
        case 'admin':
          icon = '<i class="admin-icon"></i>';
          break;
        default :
          icon = ''
      }

      var format = '<li class="user-item"><img class="user-img" src="%s" alt="">' +
        '<span class="user-name">%s</span>%s</li>';
      var avatar = item.avatar;
      avatar == null && (avatar = '/img/userimg.png');
      var ele = sprintf(format, avatar, item.nickname, icon);
      list += ele;
    });
    $('#userList').html(list);
  });
}

function initWs() {
  if (room != null && user != null) {
    var ws    = new WebSocket('ws://' + WS_HOST + ':' + WS_PORT);
    ws.onopen = function () {
      ws.send(user.id + '|' + room.id)
    }
  }
}

function register() {
  var button = $('#register-btn'),
      form   = $('#register-form');
  button.click(function () {
    $.ajax({
      url     : form.data('action'),
      type    : 'POST',
      dataType: 'json',
      data    : form.serialize(),
      success : function (data) {
        window.location.reload()
      },
      error   : function (xhr) {
        var ele = '<div id="register-error" class="form-group has-error text-center"><span class="help-block"><strong>%s</strong></span></div>';
        $('#register-error').remove();
        form.prepend(sprintf(ele, xhr.responseJSON.message))
      }
    })
  });
}

function postForm(form, button) {
  button.click(function () {
    $.ajax({
      url     : form.data('action'),
      type    : 'POST',
      dataType: 'json',
      data    : form.serialize(),
      success : function (data) {
        window.location.reload()
      },
      error   : function (xhr) {
        var ele = '<div id="register-error" class="form-group has-error text-center"><span class="help-block"><strong>%s</strong></span></div>';
        $('#register-error').remove();
        form.prepend(sprintf(ele, xhr.responseJSON.message))
      }
    })
  });
}

function previewFile() {
  $('#selectImg').change(function () {
    var preview = document.getElementById('uCenterImg');
    var avatar = document.getElementById('avatar-data');

    upload('#selectImg', '/upload/img', 'file', re => {
      preview.src = re.url;
      avatar.value = re.url;
    });
  });

}

function sendImg() {
  $('#chat-img').change(function () {
    upload('#chat-img', '/upload/img', 'file', re => {
      var ele = sprintf('<img src="%s" class="img-responsive"/>', re.url);
      chat.send({
        user: user,
        type: 1,
        msg : ele,
        time: datef('HH:mm:ss', new Date())
      })
    });
  })
}

function upload(e, url, name, success, error) {
  typeof success == 'function' || (success = re => {});
  typeof error == 'function' || (error = re => {});
  name      = name || 'file';
  var input = $(e)[0],
      file  = input.files[0];
  var fd   = new FormData();
  fd.append(name, file);
  var xhr  = new XMLHttpRequest();
  xhr.open('POST', url);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.setRequestHeader("X-CSRF-TOKEN", window.Laravel.csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      var responseText = JSON.parse(xhr.responseText);
      if (xhr.status == 200) {
        success(responseText)
      } else {
        error(responseText)
      }
    }
  };

  xhr.send(fd);
}