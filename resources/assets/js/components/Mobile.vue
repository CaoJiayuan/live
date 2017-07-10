<template>
  <div>
  
    <player :user="user"></player>
    <chat :user="user"></chat>
    <!--<div @click="logout" ref="touch" @touchstart="touchstart" @touchmove="touchmove" id="touchBar" class="thouch-bar">-->
    <!--<img src="/img/logout.png" class="img-responisve" draggable="true" id="drag1" alt="">-->
    <!--</div>-->
    <!--<form ref="lo_form" action="/logout" method="post" style="display: none;  ">-->
    <!--<input type="hidden" name="_token" v-model="csrf">-->
    <!--</form>-->
  </div>
</template>
<style>
@import "../../../../public/css/toastr.min.css";
html,
body {
  height: 100%;
  overflow: hidden;
}




/*视频开始*/

.player {
  height: calc(100vw * 0.5625 + 45px);
  overflow: hidden;
  background-color: #000;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
}

.player iframe {
  display: block;
  width: 100%;
  height: 100vh;
}









/*视频结束*/

.chat-content {
  position: fixed;
  top: calc(100vw * 0.5625 + 45px);
  width: 100%;
  left: 0;
  height: calc(100% - 100vw * 0.5625 - 45px);
}









/*tab切换*/

.chat-content .nav {
  border: none;
  width: 100%;
  position: relative;
  z-index: 10;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
}

.nav-tabs>li.active>a,
.nav-tabs>li.active>a:focus,
.nav-tabs>li.active>a:hover {
  color: #ff7f00;
  border-top: 2px solid transparent;
  border-left: none;
  border-right: none;
  border-bottom: 2px solid #ff7f00;
  border-radius: 0;
}

.nav {
  display: flex;
  justify-content: space-between;
  background-color: #fff;
}

.nav li a {
  padding: 10px;
  color: #000;
  border-bottom: 2px solid transparent;
}

.chat-content .nav li {
  border: none;
  text-align: center;
  padding: 0;
  margin: 0;
  font-size: 14px;
  outline: none;
}

.chat-content .nav li p {
  margin: 0;
}

.tab-content {
  height: calc(100% - 40px);
  width: 100%;
  position: relative;
}

.tab-content .tab-pane {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  overflow-y: scroll;
  display: inherit !important;
  visibility: hidden;
}

.tab-content .active {
  /*display: inherit !important;*/
  visibility: inherit;
}

.thouch-bar {
  position: fixed;
  left: 80%;
  top: 80%;
  display: block;
  width: 32px;
  height: 32px;
}

.thouch-bar img {
  width: 32px;
  height: 32px;
}









/*对话列表*/

.chat-list {
  background-color: #eaeaea;
  height: calc(100% - 46px);
  overflow: auto;
  position: absolute;
  z-index: 1;
  top: 0;
  width: 100%;
  -webkit-overflow-scrolling: touch;
}

.chat-list ul {
  padding: 5px 10px;
  list-style: none;
  position: relative;
  margin-bottom: 5px;
}

.chat-list ul li {
  text-align: left;
  display: block;
  line-height: 1.5;
  /*padding: 5px 0;*/
  margin-bottom: 3px;
  position: relative;
}















/*消息输入*/

.speak {
  position: absolute;
  left: 0;
  bottom: 0;
  padding: 5px 0px;
  background-color: #fff;
  border-top: 1px solid #eee;
  width: 100vw;
  display: flex;
  z-index: 999;
  height: 50px;
  justify-content: space-between;
}

.speak input {
  display: inline-block;
  height: 35px;
  border-radius: 0;
  width: 60%;
  border: 1px solid #eaeaea;
  padding: 0 5px;
}

.speak img {
  height: 35px;
  width: auto;
  padding-left: 10px;
  vertical-align: bottom;
}

.speak button {
  display: inline-block;
  width: 20%;
  margin-right: 5px;
  margin-left: 5px;
  border-radius: 5px;
  border: none;
  background-color: #6b6485;
  height: 35px;
  line-height: 35px;
  color: #fff;
  vertical-align: bottom;
}

.speak .logout {
  display: inline-block;
  margin-right: 5px;
  padding-left: 0;
}
</style>
<script>
export default {
  created() {

  },
  data() {
    return {
      user: window.auth,
      startX: 0,
      startY: 0,
      sX: 0,
      sY: 0,
      moveX: 0,
      moveY: 0,
      leftX: 0,
      rightX: 0,
      topY: 0,
      bottomY: 0,
      winW: document.body.offsetWidth,
      winH: document.body.offsetHeight,
      contW: 32,
      contH: 32,
      csrf: window.Laravel.csrfToken,
      timers: []
    }
  },
  components: {
    Player: require('./mobile/Player.vue'),
    Chat: require('./mobile/Chat.vue'),
  },
  methods: {
    reloadUser() {
      axios.get('/user').then(response => {
        this.user = response.data;
      }).catch(error => {
        window.location.href = '/login';
      });
    },
    logout() {
      var self = this;
      Alert7.confirm("确认登出?", "",
        "确认",
        function () {
          self.$refs.lo_form.submit()
        },
        "取消",
        function () { }
      );
    },
    touchstart: function (e) {
      var touchBar = this.$refs.touch;
      this.startX = e.targetTouches[0].pageX; //获取点击点的X坐标
      this.startY = e.targetTouches[0].pageY; //获取点击点的Y坐标
      this.sX = touchBar.offsetLeft; //相对于当前窗口X轴的偏移量
      this.sY = touchBar.offsetTop; //相对于当前窗口Y轴的偏移量
      this.leftX = this.startX - this.sX; //鼠标所能移动的最左端是当前鼠标距div左边距的位置
      this.rightX = this.winW - this.contW + this.leftX; //鼠标所能移动的最右端是当前窗口距离减去鼠标距div最右端位置
      this.topY = this.startY - this.sY; //鼠标所能移动最上端是当前鼠标距div上边距的位置
      this.bottomY = this.winH - this.contH + this.topY; //鼠标所能移动最下端是当前窗口距离减去鼠标距div最下端位置
    },
    touchmove: function (e) {
      e.preventDefault();
      var touchBar = this.$refs.touch;
      this.moveX = e.targetTouches[0].pageX; //移动过程中X轴的坐标
      this.moveY = e.targetTouches[0].pageY; //移动过程中Y轴的坐标
      if (this.moveX < this.leftX) {
        this.moveX = this.leftX;
      }
      if (this.moveX > this.rightX) {
        this.moveX = this.rightX;
      }
      if (this.moveY < this.topY) {
        this.moveY = this.topY;
      }
      if (this.moveY > this.bottomY) {
        this.moveY = this.bottomY;
      }
      touchBar.style.top = this.moveY + this.sY - this.startY + 'px';
      touchBar.style.left = this.moveX + this.sX - this.startX + 'px';
    },
    getCreditRules(){
      axios.get('/room/' + this.user.room.id + '/credits').then(response => {
        var rules = response.data;
        this.timers.forEach(t => {
          clearTimeout(t);
        });
        rules.forEach(r => {
          var timer = setTimeout(() => {
            axios.post('/user/' + this.user.room.id + '/reword', {
              minutes : r.minutes
            }).then(re => {
              var reword = re.data.reword;
              reword > 0 && toastrNotification('success', '在线' + re.data.minutes + '分钟,获取' + reword + '积分!');
              this.user.credits += reword;
            })
          }, r.minutes * 60 * 1000);
          this.timers.push(timer);
        })
      })
    }
  },
  mounted() {
    vueApp.$on('reload.user', this.reloadUser);
    this.getCreditRules();
  },
  beforeDestroy: function () {
    vueApp.$off('reload.user')
  },
  watch: {

  }
}
</script>