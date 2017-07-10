<template>
  <div class="player col-xs-7">
    <!--视频-->
    <div class="player-center">
      <div class="countdown">
        <div class="col-xs-8" style="padding-left:10px">
          {{ user.room.schedule ? '讲师：' + user.room.schedule.lecturer + '(' + user.room.schedule.credits +'分)': ''}}
        </div>
        <div class="pull-right text-right">
          <span class="refresh ">
            <span id="J_refresh" @click="refresh">
              <img class="r" src="/img/reset.png" alt="">刷新
            </span>
          </span>
        </div>
      </div>
      <div class="show-gifts clearfix">
        <transition-group name="slide-fade">
          <div class="gifts-item" v-for="(gift, index) in gifts" :key="index">
            <img class="pull-left gifts-user_img" :src="gift.avatar || '/img/userimg.png'" alt="">
            <div class="pull-left">
              <span class="gifts-user_name">{{gift.nickname}}</span>
              <span class="gifts-name">{{gift.name}}</span>
            </div>
            <img :src="gift.img" alt="" class="gifts-img">
            <i class="gifts-cont">{{ gift.combo && gift.combo > 1 ? 'x' + gift.combo : '' }}</i>
          </div>
        </transition-group>
      </div>
      <template v-if="user.room.covered">
        <img v-bind:src="user.room.cover" alt="" v-if="user.room.cover" style="width: 100%;height: 100%;">
      </template>
      <template v-if="!user.room.covered">
        <div id="live-player" style="width: 100%; height: 100%;z-index:0;" v-if="user.room.type==1"></div>
        <embed v-if="user.room.type==0" width="100%" height="100%" allownetworking="all" wmode="transparent" allowscriptaccess="always" v-bind:src="user.room.url" quality="high" bgcolor="#000" allowfullscreen="true" allowFullScreenInteractive="true" type="application/x-shockwave-flash">
      </template>
      <p class="text-center" v-if="!user.room.url || user.room.url.length <= 0">
        <img src="/img/null.png">
        <br>暂无直播
        <!--<form>
                                                    <input type="BUTTON" name="FullScreen" value="全屏显示" onClick="window.open(document.location, 'big', 'fullscreen=yes')">
                                                  </form>
                                                  <embed width="800" height="500" allownetworking="all" allowscriptaccess="always" src="http://liveshare.huya.com/xiaohuan1994/huyacoop.swf"
                                                    quality="high" bgcolor="#000" wmode="window" allowfullscreen="true" allowFullScreenInteractive="true" type="application/x-shockwave-flash">
                                                  <iframe style="margin-top:-50px;width:100%;" src="http://m.huya.com/xiaohuan1994"></iframe>-->
  
      </p>
    </div>
    <!--版权说明-->
    <div class="advertisement">
      <ul id="advertisement" class="nav nav-tabs">
        <li class="active" v-if="banners.length > 0">
          <a href="#newNotice" data-toggle="tab">最新公告 </a>
        </li>
        <li v-bind:class="banners.length < 1 ? 'active' : ''">
          <a href="#versions" data-toggle="tab">版权说明 </a>
        </li>
        <li>
          <a href="#disclaimer" data-toggle="tab">免责声明 </a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade in active carousel slide" id="newNotice" data-ride="carousel" v-if="banners.length > 0">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#newNotice" v-for="(banner,index) in banners" v-bind:data-slide-to="index" v-bind:class="index==0 ? 'active': '' "></li>
          </ol>
  
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox" style="width: 100%;height: 100%;">
            <div v-bind:class="index==0 ? 'item active': 'item' " v-for="(banner,index) in banners" style="width: 100%;height: 100%;">
              <a v-bind:href="banner.url ? banner.url : '#'" target="_blank">
                <img v-bind:src="banner.img" width="100%" height="100%" alt="" style="width: 100%;height: 100%;">
              </a>
            </div>
          </div>
  
          <!-- Controls -->
          <a class="left carousel-control" href="#newNotice" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#newNotice" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
  
          <!--<img src="./img/banner.png" width="100%" height="100%" class="img-responsive" alt="">-->
        </div>
        <div class="tab-pane fade" v-bind:class="banners.length < 1 ? 'tab-pane fade in active' : 'tab-pane fade'" id="versions" v-html="info.copyright">
        </div>
        <div class="tab-pane fade" id="disclaimer" v-html="info.disclaimer">
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.show-gifts {
  position: absolute;
  top: 50%;
  left: 10px;
  width: auto;
  height: auto;
  transform: translateY(-50%);
}

.show-gifts .gifts-item {
  position: relative;
  width: 180px;
  left: 0;
  height: 50px;
  margin-bottom: 20px;
  border-radius: 25px;
  background-color: rgba(0, 0, 0, .5);
  opacity: 1;
}


.show-gifts .gifts-item .gifts-user_img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

.show-gifts .gifts-item .gifts-user_name {
  color: #fff;
  display: block;
  width: 60px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.show-gifts .gifts-item .gifts-name {
  color: #5babff;
  display: block;
}

.show-gifts .gifts-item .gifts-img {
  position: absolute;
  right: -5px;
  top: -20px;
  width: 60px;
}

.show-gifts .gifts-item .gifts-cont {
  color: #ff2f00;
  position: absolute;
  left: 195px;
  top: -10px;
  font-size: 20px;
  font-weight: 700;
  font-style: normal;
}

.slide-fade-enter-active {
  transition: all .5s ease;
}

.slide-fade-leave-active {
  transition: all .5s ease;
}

.slide-fade-enter,
.slide-fade-leave-active {
  transform: translateX(-180px);
  opacity: 0;
}
</style>
<script>
export default {
  created() {

  },
  data() {
    return {
      playerId: 0,
      notices: [],
      //      info: {},
      banners: [],
      gifts: []
    }
  },
  props: ['user', 'info'],
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
        isfullscreen: true //是否双击全屏，默认为true
      });
      ++this.playerId
    },
    initPlayer() {
      if (this.user.room.type == 1 && !this.user.room.covered) {
        this.$nextTick(() => this.initAodian())
      }
    },
    noticesList(notices) {
      var result = '';
      notices.forEach(notice => {
        var gap = '&nbsp;'.repeat(128);
        result += notice.content + gap;
      });

      return result;
    },
    refresh() {
      axios.get('/user').then(response => {
        this.user = response.data;
        setTimeout(this.initPlayer, 500);
        //this.getNotice();
      }).catch(error => {
        window.location.href = '/login';
      });
    },
    getNotice() {
      axios.get('/room/' + this.user.room.id + '/notice').then(response => {
        this.notices = response.data;
      })
    },
    getInfo() {
      axios.get('/room/' + this.user.room.id + '/info').then(response => {
        this.info = response.data;
      })
    },
    getBanners() {
      axios.get('/room/' + this.user.room.id + '/banners').then(response => {
        this.banners = response.data;
        setTimeout(function () {
          $('.carousel').carousel()
        }, 200)
      })
    },
    sendGift(gift) {
      if (!this.makeCombo(gift)) {
        this.gifts.push(gift);
      }
      if (this.gifts.length > 4) {
        this.gifts.shift()
      }
      this.user.room.schedule && (this.user.room.schedule.credits += gift.credits)
    },
    makeCombo(gift) {
      var c = false,
        i = 0;
      this.gifts.forEach(g => {
        if (g.id == gift.id && gift.userId == g.userId) {
          this.gifts[i].combo++;
          this.gifts[i].delay += .5;
          c = true;
        }
        i++;
      });
      return c;
    },
    setGiftTimer() {
      setInterval(() => {
        var i = 0;
        this.gifts.forEach(g => {
          this.gifts[i].delay--;
          if (this.gifts[i].delay <= 0) {
            this.gifts.splice(i, 1);
          }
          i++;
        })
      }, 1000)
    }
  },

  mounted() {
    this.initPlayer();
    this.setGiftTimer();
    //this.getNotice();
    //    this.getInfo();
    this.getBanners();
    vueApp.$on('send.gift', this.sendGift);
    //    vueApp.$on('reload.notice', this.getNotice)
  },
  beforeDestroy: function () {
    //    vueApp.$off('reload.notice');
    vueApp.$off('send.gift');
  },
  watch: {
    user: function () {
      this.initPlayer();
    }
  },
}
</script>