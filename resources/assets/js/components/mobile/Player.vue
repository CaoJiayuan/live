<template>
  <div class="player" id="live-player">
    <div class="magic">
      <div class="header clearfix">
        <span class=" notice">公告：
          <img src="/img/notice-mini.png" alt="">
          <ul id="noticeList">
            <li ref="noticelist">
              <span v-for="item in notices" v-html='item.content'>
              </span>
            </li>
          </ul>
        </span>
        <div class="u-center">
          <img @click.stop="ucenter = !ucenter" v-bind:src="user.avatar  || '/img/userimg.png'" alt="">
          <div class="u-center-alert" v-bind:class="{'fade-in' :ucenter}">
            <span>昵称：{{ user.nickname }}</span>
            <span>等级：
              <img v-bind:src="user.level_img ? user.level_img : '/img/level/1.png'" width="15" height="15">
            </span>
            <span>积分：{{ user.credits }}</span>
            <button :class="user.signed ? 'clicked' : ''" @click="sign">{{ user.signed ? '已' : '' }}签到</button>
            <button @click="logout">登出</button>
          </div>
        </div>
      </div>
    </div>
    <template v-if="user.room.covered">
      <img v-bind:src="user.room.cover" alt="" v-if="user.room.cover" style="width: 100%;height: 100%;">
    </template>
    <template v-if="!user.room.covered">
      <div class="iframe-content">
        <video style="z-index: 1;" id="html5Media" autoplay="true" controls="" width="100%" preload="auto" height="100%" webkit-playsinline="true" playsinline="true" type="application/x-mpegURL" :src="user.room.mobile_url" v-if="user.room.type == 1"></video>
        <iframe v-if="user.room.type==0" width="100%" name="demo" scrolling="no" height="300" v-bind:src="'http://m.huya.com/'+user.room.mobile_url" frameborder="0"></iframe>
      </div>
    </template>
    <form ref="lo_form" action="/logout" method="post" style="display: none;  ">
      <input type="hidden" name="_token" v-model="csrf">
    </form>
  </div>
</template>
<style>
.magic {
  position: absolute;
  left: 0;
  width: 100%;
  top: 0;
  height: 70px;
  background-color: transparent;
  z-index: 999;
}

.header {
  background-color: #6b6485;
  color: #fff;
  padding: 13px 5px;
  position: relative;
  top: 0;
  width: 100%;
  z-index: 999;
}

.notice {
  display: inline-block;
  width: calc(100% - 35px);
  overflow: hidden;
}

.notice ul {
  padding: 0;
  margin: 0;
  display: inline-block;
  width: calc(100% - 80px);
  list-style: none;
  height: 20px;
  vertical-align: middle;
  position: relative;
  overflow: hidden;
}

.notice ul li {
  position: absolute;
  display: block;
  height: 20px;
  width: auto;
  left: 100%;
}

.notice ul li span {
  white-space: nowrap;
  margin-right: 100px;
  line-height: 20px;
}

.notice ul li span:last-child() {
  margin-right: 0;
}

.u-center {
  display: inline-block;
  width: 25px;
  height: 25px;
  position: relative;
}

.u-center img {
  width: 25px;
  height: 25px;
  border-radius: 50%;
}

.u-center .fade-in {
  opacity: 1;
  display: inherit;
}

.u-center-alert {
  position: absolute;
  right: 0;
  top: 35px;
  z-index: 999;
  width: 160px;
  padding: 10px 10px 20px 10px;
  background-color: #fff;
  color: #696969;
  border-radius: 5px;
  opacity: 0;
  display: none;
}

.u-center-alert span {
  line-height: 1.5;
  width: 100%;
  display: inline-block;
  overflow: hidden;
  text-overflow: ellipsis;
  word-break: normal;
}

.u-center-alert span img {
  width: 15px;
  height: 15px;
  vertical-align: text-bottom;
  border-radius: 0;
}

.u-center-alert button {
  color: #fff;
  border-radius: 4px;
  background-color: #ed8721;
  width: 60px;
  height: 24px;
  line-height: 24px;
  text-align: center;
  border: none;
  margin: 10px 0 0 0;
  padding: 0;
  outline: none;
  box-shadow: 0 5px 5px 0 rgba(255, 127, 0, 0.4);
}


.u-center-alert button:last-child {
  margin-left: 16px;
}

.u-center-alert .clicked {

  background-color: #e5e5e5;
  color: #696969;
  box-shadow: 0 5px 5px 0 #dadada;
}

.iframe-content {
  height: 100%;

  overflow: hidden;
}
</style>
<script>
import {
  setMaxheight,
  notice
} from '../../main';

export default {
  created() {

  },
  data() {
    return {
      playerId: 0,
      ucenter: false,
      notices: [],
      csrf: window.Laravel.csrfToken,
      width: '100%'
    }
  },
  props: ['user'],
  components: {},
  methods: {
    initAodian() {
      $('#vvMedia' + this.playerId).remove();
      let playerId = 'live-player';
      let player = window.$('#' + playerId);
      new aodianPlayer({
        container: playerId, //播放器容器ID，必要参数
        rtmpUrl: this.user.room.url, //控制台开通的APP rtmp地址，必要参数
        hlsUrl: this.user.room.mobile_url, //控制台开通的APP hls地址，必要参数
        /* 以下为可选参数*/
        width: player.width(), //播放器宽度，可用数字、百分比等
        height: player.height(), //播放器高度，可用数字、百分比等
        autostart: true, //是否自动播放，默认为false
        bufferlength: '1', //视频缓冲时间，默认为3秒。hls不支持！手机端不支持
        maxbufferlength: '2', //最大视频缓冲时间，默认为2秒。hls不支持！手机端不支持
        stretching: '1', //设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小,默认值为1。hls初始设置不支持，手机端不支持
        controlbardisplay: 'enable', //是否显示控制栏，值为：disable、enable默认为disable。
        adveDeAddr: '/img/playergb.jpeg', //封面图片链接
        //adveWidth: 320,//封面图宽度
        //adveHeight: 240,//封面图高度
        //adveReAddr: '',//封面图点击链接
        //isclickplay: false,//是否单击播放，默认为false
        isfullscreen: false //是否双击全屏，默认为true
      });
      let ele = window.document.getElementById(playerId);
      ele.attributes['webkit-playsinline'] = 'true';
      ele.attributes['playsinline'] = 'true';
      ++this.playerId
    },
    noticesList(notices) {
      var result = '';
      notices.forEach(notice => {
        var gap = '&nbsp;'.repeat(128);
        result += notice.content + gap;
      });

      return result;
    },
    initPlayer() {
      if (this.user.room.type == 1 && !this.user.room.covered) {
        //        this.$nextTick(() => this.initAodian())
      }
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
    getNotice() {
      axios.get('/room/' + this.user.room.id + '/notice').then(response => {
        this.notices = response.data;
        this.$nextTick(() => {
          this.noticeanimate();
        })
      })
    },
    noticeanimate() {
      var self = this;
      var nwidth = this.$refs.noticelist.clientWidth;
      var ntimer = setInterval(function () {
        self.$refs.noticelist.style.left = (self.$refs.noticelist.offsetLeft - 1) + 'px'
        if (self.$refs.noticelist.offsetLeft < -(nwidth)) {
          self.$refs.noticelist.style.left = nwidth + 'px';
        }
      }, 50)
    },
    sign() {
      if (this.user.signed) {
        return;
      }
      axios.post('/user/' + this.user.room.id + '/sign').then(response => {
        var reword = response.data.reword;
        this.user.signed = true;
        this.user.credits += reword;
        toastrNotification('success', '签到成功,奖励' + reword + '积分!')
      }).catch(er => toastrNotification('error', er.response.data.message))
    }
  },
  mounted() {
    var self = this;
    this.initPlayer();
    this.getNotice();

    vueApp.$on('reload.notice', this.getNotice)
    document.onclick = function () {
      self.ucenter = false;
    }
  },
  beforeDestroy: function () {
    vueApp.$off('reload.notice');
  },
  watch: {
    user: function () {
      this.initPlayer();
    }
  },
}
</script>