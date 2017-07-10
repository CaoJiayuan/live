export function notice(x) {
  var noticeList = $('#noticeList');
  var span = noticeList.find('li span');
  var i = 0;
  var timer = setInterval(function () {
    var spanWidth = span.eq(i).width() + noticeList.width();
    span.eq(i).animate({
      'left': -span.eq(i).width()
    }, spanWidth * 1000 / x, 'linear', function () {
      $(this).css('left', '100%');
      // $(this).parent('li').css('display', 'none')
      // $(this).parent('li').next().css('display', 'list-item')
    })
  }, 100)
}