<template>
  <div>
    <div class="chat-cont col-xs-5">
      <div class="chat-block">
        <!--最新公告-->
        <div class="notice-m clearfix">
          <div class="notice-roll clearfix pull-left">
            <div class="pull-left">公告:
              <img src="/img/notice-mini.png" alt="">
            </div>
            <div class="pull-right">
              <ul id="noticeList">
                <li ref="noticelist">
                  <span v-for="item in notices" v-html='item.content'>
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!--<p>
                                                                                                                                                                                                                                                                                                                                <img src="/img/person.png" alt="">在线人数（{{ total }}）</p>-->
  
        <div id="chat-group">
          <button id="stop" @click="toggleScroll">{{ roll ? '暂停' : '开始' }}滚屏</button>
          <ul ref="cl">
            <template v-for="(message,index) in messages">
              <message :message="message" :roll="roll"></message>
            </template>
            <!--<li class="clearfix" v-for="message in messages">-->
            <!--<div class="user-img pull-left"><img v-bind:src="message.user.avatar" alt=""></div>-->
            <!--<div class="user-desc pull-left">-->
            <!--<div><span class="user-name">{{ message.user.nickname }} </span><span class="data">{{ message.time }} </span></div>-->
            <!--<div class="chat" v-html="message.msg"></div>-->
            <!--</div>-->
            <!--</li>-->
          </ul>
        </div>
        <div class="chat-action clearfix">
          <div class="qq-list pull-left clearfix" style="height: 35px;" v-if="services.length > 0">
            <a style="margin-left:0" class="qq-item">
              <img src="/img/jpkf.png"> </a>
            <a target="_blank" class="qq-item" v-for="service in services" v-bind:href="'http://wpa.qq.com/msgrd?v=3&uin='+service.qq+'&site=qq&menu=yes'">
              <img border="0" src="/img/counseling_style_52.gif" alt="点击这里给我发消息" title="点击这里给我发消息">
            </a>
          </div>
          <a class="qqmore pull-right" @click="showQQ()" v-if="services.length > 0">更多>></a>
          <div class="cheer">
            <img src="/img/xh.png" title="鲜花" alt="" @click="sendGif('xianhua-xg')">
            <img src="/img/geini.png" title="给力" alt="" @click="sendGif('geili-xg')">
            <img src="/img/guzhang.png" title="鼓掌" alt="" @click="sendGif('guzhang-xg')">
            <img src="/img/woquan.png" title="握拳" alt="" @click="sendGif('dingqi-xg')">
            <img src="/img/dianzan.png" title="点赞" alt="" @click="sendGif('dianzan-xg')">
          </div>
        </div>
        <div class="chat-speak">
          <div class="speak-up clearfix">
            <img class="emoji emoji1" id="emoji" src="/img/expression.png">
  
            <label class="chat-img" for="chat-img">
              <img class="" src="/img/img.png">
              <span class="js-fileapi-wrapper" style="position: relative; display: none;">
                <input type="file" id="chat-img" @change="sendImg">
              </span>
            </label>
            <img class="emoji" src="/img/gifts.png" alt="" v-if="user.room.schedule" @click="giftShow = !giftShow">
            <div class="btn-group dropup" v-if="rs.length > 0">
              <button type="button" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="false" @click="dropdown">
                <img class="" src="/img/robot.png">
              </button>
              <div class="dropdown-menu" role="menu">
                <div class="tooltip-arrow"></div>
                <ul>
                  <li @click="selectRobot(r)" v-bind:class="(robot && robot.id == r.id) ? 'active' : ''" v-for="r in rs">
                    <img v-bind:src="r.avatar ? r.avatar : '/img/userimg.png'" alt="">{{ r.name }}
                  </li>
                </ul>
              </div>
            </div>
            <div class="gifs" @mouseleave="hidegifts" v-if="giftShow">
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" v-for="(cate, index) in giftCates" :class="index == 0 ? 'active' : ''">
                  <a :href="'#gcate' + cate.id" :aria-controls="'gcate' + cate.id" role="tab" data-toggle="tab">{{ cate.name }}</a>
                </li>
              </ul>
              <div class="tab-content">
                <div role="tabpanel" v-for="(cate,index) in giftCates" :class="index == 0 ? 'tab-pane active' : 'tab-pane'" :id="'gcate' + cate.id">
                  <ul class="clearfix">
                    <li v-for="gift in cate.gifts" @click="sendGift(gift)">
                      <div class="gifs-desc">
                        <img :src="gift.img" alt="">
                        <i>{{ gift.name }}</i>
                        <span>{{ gift.credits }}积分</span>
                      </div>
                      <img :src="gift.img" alt="">
                    </li>
                  </ul>
                </div>
              </div>
  
            </div>
          </div>
          <div class="speak-center">
            <button id="send" class="pull-right btn" :class="sendRemian > 0 ? 'unsend' : ''" @click="send" v-bind:disabled="canNotSend">
              <label> {{ sendRemian > 0 ? sendRemian + 's' : '发送(Enter)' }}
              </label>
            </button>
            <textarea v-model="msg" id="editor" row="3" contenteditable="true"></textarea>
            <span style="margin-left: 15px;">({{msg.length}}/{{user.room.max_length}})</span>
            <span v-if="hasMasking" style="margin-left: 80px; ">发送内容包含敏感词汇!</span>
            <span v-if="this.msg.length > this.user.room.max_length" style="margin-left: 80px; color:red">发送字数超过限制！</span>
            <input type="text" id="e-content" style="display: none; ">
          </div>
          <p class="login-inside " v-if="user.status==0 ">该用户已被禁言,请联系管理员</p>
        </div>
      </div>
  
    </div>
    <div class="modal fade " id="QQmoreModal" tabindex="-1 " role="dialog " aria-labelledby="QQmoreModal ">
      <div class="modal-dialog modal-lg " role="document ">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button " class="close " data-dismiss="modal " aria-label="Close ">
            </button>
          </div>
          <div class="modal-body container-fluid ">
            <a target="_blank " class="qq-item " v-for="service in services " v-bind:href=" 'http://wpa.qq.com/msgrd?v=3&uin='+service.qq+ '&site=qq&menu=yes' ">
              <img border="0 " src="/img/counseling_style_52.gif " alt="点击这里给我发消息 " title="点击这里给我发消息 ">
            </a>
          </div>
  
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.emoji {
  margin-left: 20px;
}

.emoji:hover {
  cursor: pointer;
}

.list-complete-item {
  transition: all 1s;
  display: inline-block;
  margin-right: 10px;
}

.list-complete-enter,
.list-complete-leave-to
/* .list-complete-leave-active for <2.1.8 */

{
  opacity: 0;
  transform: translateY(30px);
}

.list-complete-leave-active {
  position: absolute;
}
</style>
<script>
var MAX_MSG = 200;
import {
  uploadFile,
  htmlencode
} from '../../app/utils';
import {
  notice
} from '../../main';

import ev from '../../app/event';
export default {
  created() {
    var roomId = this.user.room.id;
    var topic = 'chat-room-' + roomId;
    //    window.console.log('Join topic>>>>> ' + topic);
    this.getHistories(topic);
    this.chat = new window.DmsChat(topic);
    axios.get('/room/' + roomId + '/children').then(re => {
      re.data.forEach(r => {
        this.chat.join('chat-room-' + r.id)
      })
    });
    this.timer = setInterval(() => {
      this.now = new Date().getTime()
    }, 20);
    window.handleUpload = (re) => {
      this.handleUploadSuccess(re)
    };
    window.handleUploadErr = (er) => {
      this.handleUploadError(er)
    }
  },
  data() {
    return {
      messages: [],
      notices: [],
      chat: {},
      msg: '',
      roll: true,
      users: window.users,
      rs: [],
      robot: null,
      total: 0,
      services: [],
      maskings: [],
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
      last_sent: 0,
      now: new Date().getTime(),
      timer: null,
      msg_index: 0,
      giftCates: [],
      giftShow: false
    }
  },
  props: ['user'],
  components: {
    message: require('./Message.vue')
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
    },
    isFuckingIe: function () {
      return navigator.userAgent.match(/msie/i);
    }
  },
  methods: {
    initChat() {
      let self = this;
      self.chat.onMessage(data => {
        var avatar = data.user.avatar;
        avatar == null && (data.user.avatar = '/img/userimg.png');
        self.addMessage(data);
        //          var length = self.messages.length;
        //          if (length > MAX_MSG) {
        //            self.$nextTick(() => self.messages.shift())
        //          }
      })
    },
    noticesList(notices) {
      var result = '';
      notices.forEach(notice => {
        var gap = '&nbsp;'.repeat(128);
        result += notice.content + gap;
      });

      return result;
    },
    getHistories(topic) {
      axios.get('/chat/' + topic + '/histories?page_size=100').then(re => {
        re.data.forEach(r => {
          this.addMessage(r);
        })
      });
    },
    addMessage(data) {
      data.msg = this.handleMsg(data);
      //        this.messages.push(data);
      var str = '<div class="user-desc pull-left"><div style="margin-bottom:5px">' +
        '<span class="data">%s</span>' + '<span><img src="%s"></span>' +
        '<span><img src="%s"  width="18" height="18"></span>' +
        '<span class="user-name">%s</span>' + '</div></div><div class="chat"> %s</div>';
      var node = window.document.createElement('li');
      node.setAttribute('class', 'clearfix');
      node.setAttribute('id', 'msg-' + this.msg_index);
      node.innerHTML = window.sprintf(str,
        data.time,
        data.user.ua ? data.user.ua.toLowerCase() == 'pc' ? '/img/pc.png' : '/img/mobile.png' : '/img/pc.png',
        data.user.level_img ? data.user.level_img : '/img/level/1.png',
        data.user.nickname ? data.user.nickname : data.user.name,
        data.msg
      );
      this.$refs.cl.appendChild(node);
      this.roll && (this.$refs.cl.scrollTop = this.$refs.cl.scrollHeight);
      if (this.msg_index >= MAX_MSG) {
        var ch = window.document.getElementById('msg-' + (this.msg_index - MAX_MSG));
        ch.parentNode.removeChild(ch);
      }
      this.msg_index++;
    },
    handleMsg(r) {
      //      (r.user && r.user.id == this.user.id) && (r.user = this.user);

      return r.msg;
    },
    send() {
      if (this.canNotSend) {
        return;
      }
      this.msg = this.msg.trim();
      if (!this.msg || this.msg.length <= 0 || this.user.status == 0) {
        return;
      }
      var origin = this.msg;
      this.handleSendMsg();
      this.chat.send(this.generateMessage(this.msg, origin));
      localStorage.setItem('MLS', new Date().getTime());
      this.msg = ''
    },
    sendGif(name) {
      if (this.canNotSend) {
        return;
      }
      this.chat.send(this.generateMessage(sprintf('<img src="/img/gifs/%s.gif"/>', name)));
      localStorage.setItem('MLS', new Date().getTime());
    },
    handleSendMsg() {
      this.msg = htmlencode(this.msg);
      window._.each(this.alias, (item, i) => {
        item = item.replace('[', '\\[');
        item = item.replace(']', '\\]');
        this.msg = this.msg.replace(new RegExp(item, 'g'), '<img width="21" src="/img/emojis/' + i + '.png">');
      });
    },
    sendImgIe() {
      if (this.canNotSend) {
        return;
      }
      if (this.user.status == 0) {
        return;
      }
      document.getElementById('upload').contentWindow.openFile()
    },
    sendImg(e) {
      if (this.canNotSend) {
        return;
      }
      if (this.user.status == 0) {
        return;
      }
      uploadFile(e, '/upload/img', re => {
        this.handleUploadSuccess(re);
      }, error => {
        this.handleUploadError(error)
      })
    },
    handleUploadError(error, s, e) {
      window.console.log('UPload error!!!!');

      toastrNotification('error', '上传失败')
    },
    handleUploadSuccess(re) {

      this.chat.send(this.generateMessage(sprintf('<img src="%s" class="img-responsive"/>', re.url)));
      localStorage.setItem('MLS', new Date().getTime());
    },
    generateMessage(content, origin) {
      origin = origin || content;
      var user = this.robot ? this.robot : this.user;
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
        time: datef('HH:mm:ss', new Date()),
        origin: origin,
      };
    },
    toggleScroll() {
      this.roll = !this.roll;
    },
    getUsers() {
      axios.get('/room/' + this.user.room.id + '/users').then(re => {
        this.users = re.data;
      })
    },
    dropdown() {
      setTimeout(function () {
        $('.dropdown-toggle').dropdown()
      }, 200)
    },
    getRobots() {
      axios.get('/user/' + this.user.id + '/robots').then(re => {
        this.rs = re.data;
        setTimeout(function () {
          $('.dropdown-toggle').dropdown()
        }, 200)
      })
    },
    getServices() {
      axios.get('/room/' + this.user.room.id + '/services').then(re => {
        this.services = re.data;
      })
    },
    showQQ() {
      $('#QQmoreModal').modal('show');
    },
    selectRobot(robot) {
      if (this.robot && this.robot.id == robot.id) {
        this.robot = null
      } else {
        this.robot = robot;
      }
    },
    getMaskings() {
      axios.get('/room/' + this.user.room.id + '/maskings').then(re => {
        this.maskings = re.data;
      })
    },
    getNotice() {
      axios.get('/room/' + this.user.room.id + '/notice').then(response => {
        this.notices = response.data;
      })
    },
    getGifts() {
      axios.get('/room/' + this.user.room.id + '/gifts').then(response => {
        this.giftCates = response.data;
      })
    },
    sendGift(gift) {
      axios.post('/user/' + this.user.room.id + '/gift', {
        id: gift.id
      }).then(re => {
        gift.nickname = this.user.nickname;
        this.user.credits -= re.data.credits;
        gift.combo = 1;
        gift.delay = 5;
        gift.credits = re.data.credits;
        gift.avatar = this.user.avatar;
        gift.userId = this.user.id;
        window.socket.emit(ev.commands.GIFT, {
          roomId: this.user.room.id,
          mainId: this.user.room.main_id,
          gift: gift
        });
      }).catch(err =>   swal("赠送失败!",
        err.response.data.message,
        "error"));
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
    hidegifts() {
      this.giftShow = false;
    }
  },
  mounted() {
    vueApp.$on('chat.total', total => {
      this.total = total;
    });

    this.initChat();
    this.getRobots();
    this.getServices();
    this.getMaskings();
    this.getGifts();
    var self = this;
    document.onkeyup = function (e) {
      if (e.keyCode == 13) {
        self.send()
      }
    };
    $('#e-content').emoji({
      button: '#emoji',
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
    })
    this.getNotice();
    this.noticeanimate();
    vueApp.$on('reload.notice', this.getNotice)
  },
  beforeDestroy: function () {
    vueApp.$off('chat.total');
    vueApp.$off('reload.notice');
    clearInterval(this.timer)
  },
  watch: {
    user: function () {
      this.getMaskings();
    },
    messages: function () {
      if (this.roll) {
        this.$nextTick(() => {
          this.$refs.cl.scrollTop = this.$refs.cl.scrollHeight
        })
      }
    }
  }
}
</script>