<template>
  <div class="chat-content" @resize="resize">
    <ul class="nav nav-tabs">
      <li class="active">
        <a href="#chat" data-toggle="tab">聊天 </a>
      </li>
      <li>
        <a href="#newNotice" data-toggle="tab" v-if="banners.length > 0">最新公告 </a>
      </li>
      <li>
        <a href="#versions" data-toggle="tab">活动介绍 </a>
      </li>
      <li>
        <a href="#disclaimer" data-toggle="tab">金牌讲师 </a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade in active" id="chat">
        <div class="chat-list" ref="list">
          <scroller ref="chat_list">
            <ul ref="cl">
              <li class="clearfix" ref="msg" v-for="message in messages">
                <div class="user-desc pull-left">
                  <span class="user-time">
                    {{ message.time.substring(0, 5) }}
                  </span>
                  <span>
                    <img v-bind:src="'pc' != getUa(message.user) ? '/img/m-mobile.png' : '/img/m-pc.png'">
                  </span>
                  <span>
                    <img v-bind:src="message.user.level_img ? message.user.level_img : '/img/level/1.png'">
                  </span>
                  <span class="user-name ">
                    {{ message.user.nickname ? message.user.nickname : message.user.name }}
                  </span>
                </div>
                <div class="chat pull-left" v-html="message.msg"> </div>
              </li>
              <!--<message  ref="msg" :message="m"></message>-->
            </ul>
          </scroller>
        </div>
        <div class="tips" v-if="hasMasking">
          <span>你发送的消息包含敏感词，不允许发送</span>
        </div>
        <div ref="speak" class="speak" v-if="user.status != 0">
          <img class="emoji" src="/img/memoji.png" alt="">
          <input id="e-content" type="text" style="display:none">
          <input v-model="msg" type="text" placeholder="发个言呗。。。。。。">
          <button v-bind:disabled="canNotSend" @click="send"> {{ sendRemian > 0 ? '(' + sendRemian + 's)' : '发送' }}</button>
        </div>
      </div>
      <div class="tab-pane fade carousel slide" id="newNotice" data-ride="carousel" v-if="banners.length > 0">
        <div class="swiper">
          <ol class="carousel-indicators">
            <li data-target="#newNotice" v-for="(banner,index) in banners" v-bind:data-slide-to="index" v-bind:class="index==0 ? 'active': '' "></li>
          </ol>
  
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox" style="width: 100%;height: 100%;">
            <div v-bind:class="index==0 ? 'item active': 'item' " v-for="(banner,index) in banners" style="width: 100%;height: 100%;">
              <a v-bind:href="banner.url ? banner.url : '#'">
                <img v-bind:src="banner.img" width="100%" height="100%" alt="" style="width: 100%;height: 100%;">
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="versions">
        <div v-html="info.interactive" class="pane-text"></div>
      </div>
      <div class="tab-pane fade" id="disclaimer">
        <div v-html="info.gold_lecturer" class="pane-text"></div>
      </div>
    </div>
  </div>
</template>
<style>
@import "../../../static/css/jquery.emoji.css";



.user-desc {
  max-width: 100%;
  display: inline-block;
  padding-bottom: 3px;
}

.user-desc span {
  margin-right: 5px;
}

.user-desc span img {
  height: 20px;
  width: auto;
}

.user-desc .user-time {
  background-color: #003d80;
  padding: 2px 5px;
  border-radius: 3px;
  color: #fff;
}

.user-desc .user-name {
  background-color: #5babff;
  padding: 2px 5px;
  border-radius: 3px;
  color: #fff;
}


.chat {
  display: inline-block;
  max-width: 100%;
  background-color: #fff;
  border-radius: 5px;
  padding: 0 5px;
  /*margin-top: 5px;*/
  word-wrap: break-word;
}

.swiper {
  width: 100%;
  height: 100%;
  position: relative;
}

.speak button[disabled] {
  background-color: #bdbdbd;
}

.tips {
  position: fixed;
  bottom: 60px;
  width: 80%;
  left: 10%;
  padding: 10px 5px;
  text-align: center;
  color: red;
  background-color: #fff;
  border-radius: 5px;
}

.pane-text {
  padding: 5px 10px;
}

.pane-text img {
  max-width: 100%;
}

.fade {
  transition: inherit !important;
}
</style>
<script>
var MAX_MSG = 50;
require('../../../static/js/jquery.emoji');
import {
  htmlencode
} from '../../app/utils';

export default {
  created() {

    this.timer = setInterval(() => {
      this.now = new Date().getTime()
    }, 1)
  },
  data() {
    return {
      messages: [],
      chat: {},
      msg: '',
      roll: true,
      alias: {
        1: "[偷笑]",
        2: "[傲慢]",
        3: "[再见]",
        4: "[冷汗]",
        5: "[发呆]",
        6: "[可爱]",
        7: "[吐]",
        8: "[呵呵]",
        9: "[咒骂]",
        10: "[嘘]",
        11: "[困]",
        12: "[大兵]",
        13: "[大哭]",
        14: "[害羞]",
        15: "[尴尬]",
        16: "[左哼哼]",
        17: "[差劲]",
        18: "[得意]",
        19: "[怒]",
        20: "[惊恐]",
        21: "[惊讶]",
        22: "[抓狂]",
        23: "[折磨]",
        24: "[撇嘴]",
        25: "[擦汗]",
        26: "[晕]",
        27: "[流汗]",
        28: "[流泪]",
        29: "[爱心]",
        30: "[疑问]",
        31: "[白眼]",
        32: "[睡觉]",
        33: "[糗大了]",
        34: "[舔]",
        35: "[色]",
        36: "[衰]",
        37: "[调皮]",
        38: "[酷]",
        39: "[闭嘴]",
        40: "[难过]",
        41: "[龇牙]",
      },
      info: {},
      banners: [],
      maskings: [],
      now: new Date().getTime(),
      timer: null,
      speakTop: 0,
      msg_index: 0
    }
  },
  props: ['user'],
  components: {
    Message: require('./Message.vue')
  },
  computed: {
    canNotSend: function () {
      var lastSent = localStorage.getItem('MLS');
      lastSent || (lastSent = 0);
      return (this.msg.length > this.user.room.max_length) ||
        ((this.now - lastSent) < (this.user.room.chat_interval * 1000)) || this.hasMasking;
    },
    hasMasking: function () {
      var hasMasking = false;
      this.maskings.forEach(i => {
        var word = i.word.toLowerCase();
        if (this.msg.toLowerCase().indexOf(word) >= 0) {
          hasMasking = true;
        }
      });
      return hasMasking;
    },
    sendRemian: function () {
      var chatInterval = this.user.room.chat_interval;
      if (chatInterval <= 0) {
        return 0;
      }
      chatInterval = chatInterval * 1000;
      var lastSent = localStorage.getItem('MLS');
      lastSent || (lastSent = 0);
      var spend = this.now - lastSent;
      return Math.floor((chatInterval - spend) / 1000)
    }
  },
  methods: {
    initChat() {
      var roomId = this.user.room.id;
      var topic = 'chat-room-' + roomId;
      //      window.console.log('Join topic>>>>> ' + topic);
      this.getHistories(topic);
      this.chat = new window.DmsChat(topic);
      axios.get('/room/' + roomId + '/children').then(re => {
        re.data.forEach(r => {
          this.chat.join('chat-room-' + r.id)
        })
      });
      let self = this;
      self.chat.onMessage(data => {
        this.addMessage(data);
        var avatar = data.user.avatar;
        avatar == null && (data.user.avatar = '/img/userimg.png');
        if (self.messages.length > MAX_MSG) {
          self.messages.shift()
        }
      })
    },
    logout() {
      if (window.confirm('确认登出?')) {
        this.$refs.lo_form.submit()
      }
    },
    getUa(user) {
      return user.device ? user.device.toLowerCase() : 'pc';
    },
    getHistories(topic) {
      axios.get('/chat/' + topic + '/histories?page_size=30').then(re => {
        re.data.forEach(r => {
          this.addMessage(r);
        })
      });
    },
    addMessage(data) {
      data.msg = this.handleMsg(data);
      this.messages.push(data);
      //      var height = this.speakTop - 90 - window.screen.width * .5625;
      //      this.$refs.list.style.height = this.speakTop +'px';
      //      var str  = '<div class="user-desc pull-left"><div>' +
      //        '<span class="user-name">%s</span><span>' + '<img src="%s"  width="15" height="15"></span>' +
      //        '<span><img v-bind:src="%s"></span>' +
      //        '<span class="data">%s</span></div><div class="chat"> %s</div></div>';
      //      var node = window.document.createElement('li');
      //      node.setAttribute('class', 'clearfix');
      //      node.setAttribute('id', 'msg-' + this.msg_index);
      //      node.innerHTML = window.sprintf(str, data.user.nickname ? data.user.nickname : data.user.name,
      //        data.user.level_img ? data.user.level_img : '/img/level/1.png',
      //        data.user.ua ?  '/img/' + data.user.ua.toLowerCase()+ '.png' : '/img/pc.png',
      //        data.time,
      //        data.msg
      //      );
      //      this.$refs.cl.appendChild(node);
      //      this.roll && node.scrollIntoView();
      //      if (this.msg_index >= MAX_MSG) {
      //        var ch = window.document.getElementById('msg-' + (this.msg_index - MAX_MSG));
      //        ch.parentNode.removeChild(ch);
      //      }
      //      var h = node.offsetTop;
      //      window.console.log(this.$refs.cl)
      //      setTimeout(() => this.$refs.chat_list.scrollTo(0, h, false), 20)
      //      this.msg_index++;
    },
    handleScroll() {

    },
    handleMsg(r) {
      //      (r.user && r.user.id == this.user.id) && (r.user = this.user);

      return r.msg;
    },
    send() {
      this.msg = this.msg.trim();
      if (!this.msg || this.msg.length <= 0 || this.user.status == 0) {
        return;
      }
      this.handleSendMsg();
      this.chat.send(this.generateMessage(this.msg));
      this.msg = '';
      localStorage.setItem('MLS', new Date().getTime());
    },
    handleSendMsg() {
      this.msg = htmlencode(this.msg);
      window._.each(this.alias, (item, i) => {
        item = item.replace('[', '\\[');
        item = item.replace(']', '\\]');
        this.msg = this.msg.replace(new RegExp(item, 'g'), '<img width="21px" src="/img/emojis/' + i + '.png">');
      });
    },
    generateMessage(content) {
      var user = this.user;
      let {
          id,
        nickname,
        level_img,
        level,
        device,
        avatar,
        gender
        } = user;
      return {
        user: {
          id: id,
          nickname: nickname,
          level_img: level_img,
          level: level,
          ua: device,
          device: device,
          avatar: avatar,
          gender: gender
        },
        type: 0,
        msg: content,
        time: datef('HH:mm:ss', new Date())
      };
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
    getMaskings() {
      axios.get('/room/' + this.user.room.id + '/maskings').then(re => {
        this.maskings = re.data;
      })
    },
    resize() {

    }
  },
  mounted() {
    this.initChat();
    this.getInfo();
    this.getBanners();
    this.getMaskings();

    var self = this;

    this.speakTop = this.$refs.list.offsetHeight;
    this.$nextTick(() => {
      this.$refs.list.style.height = this.speakTop+'px';
    });
    $('#e-content').emoji({
      button: '.emoji',
      showTab: false,
      animation: 'none',
      icons: [{
        name: "表情",
        path: "/img/emojis/",
        maxNum: 41,
        file: ".png",
        placeholder: "{alias}",
        alias: this.alias,
        title: {
          1: "偷笑",
          2: "傲慢",
          3: "再见",
          4: "冷汗",
          5: "发呆",
          6: "可爱",
          7: "吐",
          8: "呵呵",
          9: "咒骂",
          10: "嘘",
          11: "困",
          12: "大兵",
          13: "大哭",
          14: "害羞",
          15: "尴尬",
          16: "左哼哼",
          17: "差劲",
          18: "得意",
          19: "怒",
          20: "惊恐",
          21: "惊讶",
          22: "抓狂",
          23: "折磨",
          24: "撇嘴",
          25: "擦汗",
          26: "晕",
          27: "流汗",
          28: "流泪",
          29: "爱心",
          30: "疑问",
          31: "白眼",
          32: "睡觉",
          33: "糗大了",
          34: "舔",
          35: "色",
          36: "衰",
          37: "调皮",
          38: "酷",
          39: "闭嘴",
          40: "难过",
          41: "龇牙",
        }
      }]
    });
    $('body').on('click', '.emoji_container a', function () {
      var code = $(this).data('emoji_code');
      self.msg += code;
    });

  },
  beforeDestroy: function () {
    clearInterval(this.timer)
  },
  watch: {
    user: function () {
      this.getMaskings();
    },
    messages: function () {
      this.$nextTick(() => {
        setTimeout(() => {
          var h = this.$refs.cl.scrollHeight;
          this.$refs.chat_list.scrollTo(0, h, false)
        }, 20)
      })
    }
  }
}
</script>