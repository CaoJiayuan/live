<template>
  <div>
    <div class="center-left">
      <!--投票-->
      <div ref="votes" v-if="user.room.vote == 1 && votes.length > 0" style="padding-bottom:10px">
        <div class="vote container-fluid" v-for="vote in votes" v-if="user.room.vote == 1 && vote.options.length > 0">
          <div class="vote-title">
            <img src="/img/vote.png" alt="">{{ vote.title }}</div>
          <div class="vote-center">
            <ul>
              <li class="clearfix" v-for="option in vote.options">
                <span class="col-xs-4 vote-item-name">{{ option.name }}</span>
                <template v-if="!vote.voted">
                  <span class="col-xs-3 text-center" :title="option.option_num">{{ option.option_num }}</span>
                  <span class="col-xs-5 vote-btn">
                    <button @click="vt(vote, option)">投票</button>
                  </span>
                </template>
                <template v-if="vote.voted">
                  <span class="col-xs-5 vote-progress" :title="option.option_num">
                    <i :style="{ width: (option.option_num/vote.num)*100+ '%'}"></i>
                  </span>
                  <span class="col-xs-3 text-center" :title="option.option_num">{{ option.option_num }}</span>
                </template>
  
              </li>
            </ul>
          </div>
        </div>
      </div>
  
      <!--在线客户-->
      <div class="user-list">
        <header>
          <img src="/img/person.png" alt=""> 在线人数 ({{users.total}})</header>
        <div id="J_userList" ref="users">
          <ul>
            <li class="user-item" v-for="u in users.data">
              <template v-if="u.id == user.id">
                <img class="user-img" v-bind:src="user.avatar ? user.avatar : '/img/userimg.png'" alt="">
                <span class="user-name">{{ user.nickname ? user.nickname : user.name }}</span>
                <i>
                  <img v-bind:src="user.level_img" alt="">
                </i>
              </template>
              <template v-else="u.id != user.id">
                <img class="user-img" v-bind:src="u.avatar ? u.avatar : '/img/userimg.png'" alt="">
                <span class="user-name">{{ u.nickname ? u.nickname : u.name }}</span>
                <i>
                  <img v-bind:src="u.level_img" alt="">
                </i>
              </template>
            </li>
          </ul>
          <div class="more text-center" v-if="users.next_page_url" @click="loadMore(users.next_page_url)">加载更多</div>
        </div>
      </div>
      <!--扫码-->
      <div class="QRcode container-fluid" ref="qrcode" v-if="user.room.qr_code && user.room.qr_code.length > 0">
        <div class="row">
          <div class="col-xs-6 text-center" data-toggle="modal" data-target="#wechatModal">
            <img v-bind:src="user.room.qr_code" alt="">
            <p>
              <img src="/img/wechat.png" alt="">微信扫码 </p>
          </div>
          <div class="col-xs-6 text-center" data-toggle="modal" data-target="#QQModal">
            <img v-bind:src="user.room.qr_code" alt="">
            <p>
              <img src="/img/qq.png" alt="">QQ扫码 </p>
          </div>
        </div>
      </div>
      <!--展开/关闭按钮-->
    </div>
  
    <div class="modal fade" id="wechatModal" tabindex="-1" role="dialog" aria-labelledby="wechatModal">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true ">&times;
              </span>
            </button>
            <h3 class="modal-title ">
              <img src="/img/wechat.png ">微信扫码</h3>
          </div>
          <div class="modal-body ">
            <img v-bind:src="user.room.qr_code" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="QQModal" tabindex="-1" role="dialog" aria-labelledby="QQModal">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true ">&times;
              </span>
            </button>
            <h3 class="modal-title ">
              <img src="/img/qq.png ">QQ扫码</h3>
          </div>
          <div class="modal-body ">
            <img v-bind:src="user.room.qr_code" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.vote-center ul li:nth-child(1) .vote-progress i {
  background-color: #ff7f00;
}

.vote-center ul li:nth-child(2) .vote-progress i {
  background-color: #ff9934;
}

.vote-center ul li:nth-child(3) .vote-progress i {
  background-color: #ffab58;
}

.vote-center ul li:nth-child(4) .vote-progress i {
  background-color: #ffbf80;
}

.vote-item-name {
  padding-right: 0;
  overflow: hidden;
  text-overflow: ellipsis;
}

.vote-btn {
  padding-left: 0;
  text-align: right;
}

.vote-progress {
  padding: 0;
}

.vote-progress i {
  font-style: normal;
  display: inline-block;
  width: 100%;
  height: 15px;
  vertical-align: middle;
  /*background-color: #ff7f00;*/
  border-radius: 20px;
}
</style>
<script>
export default {
  created() {

  },
  data() {
    return {
      votes: [],
      users: {},
      voteHeight: 0
    }
  },
  props: ['user'],
  computed: {
    getListClass() {
      var i = 0,
        user = this.user,
        votes = this.votes;

      if (user.room.vote != 1 || votes.length < 1) {
        i++;
      }
      if (!user.room.qr_code || user.room.qr_code.length < 1) {
        i += 2;
      }
      switch (i) {
        case 1:
          return 'hideV';
        case 2:
          return 'hideC';
        case 3:
          return 'hideVC';
      }
      return '';
    }

  },
  components: {},
  methods: {
    getVotes() {
      axios.get('/room/' + this.user.room.id + '/votes').then(re => {
        this.votes = re.data;
      })
    },
    getUsers() {
      axios.get('/room/' + this.user.room.id + '/users').then(re => {
        this.users = re.data;
        vueApp.$emit('chat.total', re.data.total)
      })
    },
    vt(vote, option) {
      vote.voted = true;
      axios.post('/room/vote', {
        vote_id: option.vote_id,
        vote_option_id: option.id,
      }).then(re => {
        this.getVotes()
      }).catch(e => {
        toastrNotification('error', e.response.data.message);
        this.getVotes();
      });
    },
    bindHeight() {
      var vote = document.getElementById('vote-list');
      this.voteHeight = vote.offsetHeight || 0;
    },
    loadMore(url) {
      axios.get(url).then(re => {
        this.users.next_page_url = re.data.next_page_url;
        re.data.data.forEach(i => {
          this.users.data.push(i)
        })
      })
    },
    setProgressW(optionnum, num) {
      return (optionnum / num) * 100
    }
  },
  mounted() {
    this.getVotes();
    setTimeout(() => window.SOCKETOPEN || this.getUsers(), 1000);
    vueApp.$on('reload.users', this.getUsers)
  },
  beforeDestroy: function () {
    vueApp.$off('reload.users');
  },
  watch: {
    user: function () {
      this.getVotes();
      this.getUsers();
    }
  },
  updated: function () {
    var body = window.document.body.offsetHeight;

    var vote = this.$refs.votes ? this.$refs.votes.offsetHeight : 0;
    var qr = this.$refs.qrcode ? this.$refs.qrcode.offsetHeight + 10 : 0;
    var offset = (vote + qr + 116) + 'px';
    // this.$refs.users.style.height = (body - vote - qr - 116) + 'px'
    this.$refs.users.style.height = 'calc(100vh - ' + offset + ')'
  }
}
</script>