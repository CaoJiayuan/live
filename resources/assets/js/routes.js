/**
 * Created by d on 17-5-27.
 */

const routes = [
  {
    path: '/',
    component: resolve => {
      if(navigator.userAgent.match(/(iPhone|iPod|Android|ios|ipad|phone)/i)){
        require(['./components/Mobile.vue'], resolve)
      } else {
        require(['./components/Pc.vue'], resolve)
      }
    }
  },
];

export default routes;