var host          = WS_HOST,
    port          = WS_PORT,
    user          = window.auth;
window.SOCKETOPEN = false;
import ev from './event';
var socket        = io(`http://${host}:${port}`);
socket.on('connect', () => {
  window.SOCKETOPEN = true;
});

socket.on('reconnect', () => {
  window.SOCKETOPEN = true;
  socket.emit(ev.events.LOGIN, {
    id    : user.id,
    roomId: user.room.id,
    mainId : user.room.main_id,
    loginId : user.login_id
  });
});

socket.emit(ev.events.LOGIN, {
  id    : user.id,
  roomId: user.room.id,
  mainId : user.room.main_id,
  loginId : user.login_id
});

socket.on(ev.events.USERS, data => {
  if (user.room.id == data.roomId || user.room.id == data.mainId) {
    vueApp.$emit('reload.users')
  }
});
socket.on(ev.events.RELOAD, data => {
  var {id, ip,roomId} = data;
  if (ip == user.ip) {
    vueApp.$emit('reload.user')
  }
  else if (user.id == id) {
    vueApp.$emit('reload.user')
  }
  else {
    if (roomId == user.room.id || roomId == user.room.main_id) {
      vueApp.$emit('reload.user')
    }
  }
});

socket.on(ev.events.GIFT, data => {
  if (user.room.id == data.roomId || user.room.id == data.mainId) {
    vueApp.$emit('send.gift', data.gift)
  }
});

socket.on(ev.events.NOTICE, data => {
  var {roomId} = data;
  if (roomId == user.room.id || roomId == user.room.main_id) {
    vueApp.$emit('reload.notice')
  }
});

window.socket = socket;

function reloadUser() {
  axios.get('/user').then(response => {
    window.auth = response.data;
    vueApp.user = response.data;
  }).catch(error => {
    window.location.href = '/login';
  });
}