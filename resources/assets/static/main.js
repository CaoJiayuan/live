var qqH = $('.qq-list').height() || 0;
var J_userList = $("#J_userList");
var voteH = $('.vote').height() || 0;
var QRcode = $('.QRcode').innerHeight() + 10 || 0;
var oldel = $('.bg-list .bg-active');
var auto = true;
$(document).ready(function () {
  $(".dropdown-toggle").dropdown();
  // setMaxheight(J_userList, voteH + QRcode + 155);
  // setMaxheight($('#chat-group'), qqH + 314);
  verifyForm($('#loginModal'))
  verifyForm($('#signinModal'))


  $('#chat-group').mCustomScrollbar("scrollTo", 80, {
    moveDragger: true
  });
  // $('#loginBtn').on('click', function () {
  //   $('#loginModal').modal('toggle')
  // })

  //  背景切换
  $('.bg-list li').on('click', function () {
    changeBg($(this))
  })
  //背景保存
  $('#saveBg').on('click', function () {
    oldel = $('.bg-list .bg-active');
    $('#backgroundModal').modal('hide')
  })
  //取消切换
  $('#backgroundModal').on('hidden.bs.modal', function () {
    $('.bg-list li').removeClass('bg-active');
    oldel.addClass('bg-active');
    $('body').css({
      background: 'url(' + oldel.children().attr('src') + ')',
      backgroundSize: 'cover'
    });
  })

  // 在线用户
  $("#J_userList").mCustomScrollbar({
    callbacks: {
      //滚动到底部
      onTotalScroll: function () {
        console.log('滚动到底部')

      }
    }
  });
  $('#stop').on('click', function () {
    event.preventDefault()
    $('#stop').text(auto ? "开始滚屏" : "暂停滚屏");
    auto = !auto;

  })
  // 聊天
  $('#chat-group').mCustomScrollbar({
    callbacks: {
      onUpdate: function () {
        if (auto) {
          $("#chat-group").mCustomScrollbar("scrollTo", "bottom", {
            callbacks: false
          });
        }
      }
    }

  });
  //添加dom 
  // var st = setInterval(function () {
  //   $("#mCSB_2_container").append("<p >阿斯达大</p>");

  // }, 1000)
  var test = document.getElementsByTagName('html')[0].outerHTML;
  $('#saveDesktop').on('click', function () {
    export_raw('test.html', test);
  })

});
$(window).resize(function () {
  qqH = $('.qq-list').height() || 0;
  // setMaxheight($('#chat-group'), qqH + 314);
  // setMaxheight(J_userList, voteH + QRcode + 155);
});
// 高度适应
function setMaxheight(e, offset) {
  e.css('height', window.innerHeight - offset);
}

function changeBg(e) {
  var newSrc = e.children().attr('src');
  e.parent().find('li').removeClass('bg-active');
  e.addClass('bg-active');
  $('body').css({
    background: 'url(' + newSrc + ')',
    backgroundSize: 'cover'
  });
}


function verifyForm(e) {
  var inputList = e.find("input:not(input[type=checkbox])"),
    checkbox = e.find("input[type=checkbox]") || undefined;
  for (var i = 0; i < inputList.length; i++) {
    inputList.eq(i).bind('input propertychange', function () {
      if ($(this).val()) {
        e.find('.submit').removeAttr('disabled');
        inputList.eq(i).parents('.form-group').removeClass('has-error')
      }
    });
  }
  e.find('.submit').on('click', function () {
    var checked = checkbox.is(':checked');
    for (var i = 0; i < inputList.length; i++) {
      if (inputList.eq(i).val()) {
        inputList.eq(i).parents('.form-group').removeClass('has-error')
        $.ajax({
          type: "POST",
          url: "url",
          data: "data",
          dataType: "json",
          success: function (response) {

          },
          error: function (response) {

          }
        });
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

//图片上传预览 
function previewFile() {
  var preview = document.getElementById('uCenterImg');
  var file = document.getElementById('selectImg').files[0];
  var reader = new FileReader();

  reader.addEventListener("load", function () {
    preview.src = reader.result;
  }, false);

  if (file) {
    reader.readAsDataURL(file);
  }
}



function notice(x, index) {
  var noticeList = $('#noticeList');
  var span = noticeList.find('li span');
  var timer = setInterval(function () {
    spanWidth = span.eq(index).width() + noticeList.width();
    span.eq(index).animate({
      'left': -span.eq(index).width()
    }, spanWidth * 1000 / x, 'linear', function () {
      $(this).css('left', '100%');

    });
  }, 100)
}

function saveCode(obj) {
  var winname = window.open('', '_blank', 'top=10000');
  winname.document.open('text/html', 'replace');
  winname.document.write(obj.value);
  winname.document.execCommand('saveas', '', 'test.htm');
  winname.close();
}

//下载网页
function export_raw(name, data) {
  var urlObject = window.URL || window.webkitURL || window;

  var export_blob = new Blob([data]);

  var save_link = document.createElementNS("http://locahost:3000/index.html", "a")
  save_link.href = urlObject.createObjectURL(export_blob);
  save_link.download = name;
  fake_click(save_link);
}

function fake_click(obj) {
  var ev = document.createEvent("MouseEvents");
  ev.initMouseEvent(
    "click", true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null
  );
  obj.dispatchEvent(ev);
}