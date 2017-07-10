import VueRouter from "vue-router";
import VueScroller from "vue-scroller";
import routes from "./routes";
require('./bootstrap');
require('./app/extend');
require('malihu-custom-scrollbar-plugin');
require('./app/chat');
require('./main');
window.datef               = require('datef');
window.Vue                 = require('vue');
window.Vue.config.devtools = true;
window.Vue.use(VueRouter);
window.Vue.use(VueScroller);

window.axios.interceptors.response.use(undefined, error => {
  if (error.response.status === 401) {
    var delay = 0;
    var message = error.response.data.message;
    if (message  == 'relogin') {
      delay = 3000;
      toastrNotification('error', '您的帐号已在其他地方登陆,即将跳转到登陆页面')
    }
    setTimeout(() =>window.location.href = '/login', delay);
    return Promise.reject(error)
  }
  return Promise.reject(error)
});
const router = new VueRouter({
  routes
});
Vue.router   = router;

const app     = new Vue({
  router
});
window.vueApp = app;
axios.get('/user').then(response => {
  window.auth = response.data;
  // Vue.prototype.user = response.data;
  require('./app/socket');
  app.$mount('#app');
}).catch(error => {
  window.location.href = '/login';
});
$(window).ready(function () {
  var bg = localStorage.getItem('background');
  if (bg && !navigator.userAgent.match(/(iPhone|iPod|Android|ios|ipad|phone)/i)) {
    document.body.style.backgroundImage = "url('" + bg + "')";
  }
});
