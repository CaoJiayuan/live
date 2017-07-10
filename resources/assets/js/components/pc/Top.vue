<template>
  <div>
    <input style="display:none">
    <input type="password" style="display:none">
    <div class="topBar clearfix">
      <div class="logo pull-left">
        <img class="img-reponisve" v-bind:src="user.room.logo || '/img/logo.png'">
      </div>
      <ul class="pull-left nav nav-pills">
        <li role="presentation" v-if="info.calendar">
          <a data-target="#calendarModal" data-toggle="modal" @click="getCal">
            <img src="/img/calendar.png" alt="">财经日历</a>
        </li>
        <li role="presentation">
          <a @click="openCModal">
            <img src="/img/course.png" alt="">课程安排</a>
        </li>
        <li role="presentation" v-if="info.interactive">
          <a data-target="#activityModal" data-toggle="modal" @click="getAct">
            <img src="/img/activity.png" alt="">活动介绍</a>
        </li>
        <li role="presentation">
          <a data-target="#teacherModal" data-toggle="modal" @click="getGold">
            <img src="/img/teacher.png" alt="">金牌讲师</a>
        </li>
        <li role="presentation">
          <a data-target="#shopModal" data-toggle="modal" v-if="goods.length > 0">
            <img src="/img/shop.png" alt="">积分商城</a>
        </li>
      </ul>
      <div class="top-right pull-right clearfix ">
        <!--游客界面-->
        <!--<span class="login pull-right"><a data-toggle="modal" data-target="#loginModal">会员登录 </a></span>-->
        <!-- 登陆后 -->

        <div class="user pull-right" v-if="user.room.admin">
          <div class="user-c">
            <a target="_blank" href="/admin">进入管理页面</a>
          </div>
        </div>
        <div class="user pull-right">
          <div class="user-c" id="user-c">
            <a>
              <img v-bind:src="user.avatar || '/img/userimg.png'" alt="">{{ user.nickname }}</a>
            <div class="user-hover clearfix">
              <img class="user-hover-img pull-left" v-bind:src="user.avatar || '/img/userimg.png'" alt="">
              <div class="user-info pull-right">
                <span>昵称：{{ user.nickname }}</span>
                <span>等级：
                  <img :src="user.level_img" alt="">
                </span>
                <span>积分：{{ user.credits }}</span>
                <span class="user-action clearfix">
                  <label :class="user.signed ? 'clicked pull-left' : 'pull-left'" @click="sign">{{ user.signed ? '已' : '' }}签到</label>
                  <form ref="lo_form" action="/logout" method="post">
                    <input type="hidden" name="_token" v-model="csrf">
                  </form>
                  <button @click="logout" type="submit" class="pull-right">退出</button>
                  <i class="line pull-right"></i>
                  <button @click="openUModal" class="pull-right">设置 </button>
                </span>
              </div>
            </div>
          </div>
        </div>
        <a class="pull-right save-desktop" style="color: white;text-decoration: none" id="saveDesktop" href="/download">
          <img src="/img/desktop.png" alt="">保存到桌面</a>
        <button class="pull-right save-desktop" id="changeBg-btn" @click="openBgModal" v-if="backgrounds.length > 0">
          <img src="/img/bg-btn.png" alt="">背景切换
        </button>
      </div>
    </div>

    <!--背景-->
    <div class="modal fade" id="backgroundModal" tabindex="-1" role="dialog" aria-labelledby="backgroundModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true ">&times;
              </span>
            </button>
            <h3 class="modal-title ">切换背景</h3>
          </div>
          <div class="modal-body container-fluid">
            <ul class="bg-list row">
              <li class="col-xs-4" v-bind:class="background == bg.background ? 'bg-active col-xs-4' : 'col-xs-4'" v-for="bg in backgrounds"
                @click="changeBackground(bg.background)">
                <img v-bind:src="bg.background" alt="">
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--个人中心-->
    <div class="modal fade" id="UcenterModal" tabindex="-1" role="dialog" aria-labelledby="UcenterModal">
      <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true ">&times;
              </span>
            </button>
            <h3 class="modal-title">个人中心</h3>
          </div>
          <div class="modal-body ">
            <div class="container-fluid">
              <div class="row">
                <div class="u-center-nav pull-left">
                  <div class="u-center-header">
                    <div class="u-center-img">
                      <img v-bind:src="user.avatar || '/img/userimg.png'" alt="" />
                    </div>
                    <p class="text-center">{{ user.nickname }}</p>
                    <p class="text-center">等级：
                      <img v-bind:src="user.level_img" alt="" />
                    </p>
                    <p class="text-center">积分：{{ user.credits }}</p>
                  </div>
                  <ul class="nav nav-tabs nav-stacked ">
                    <li class="active text-center">
                      <a href="#Uinfo" data-toggle="tab">个人资料 </a>
                    </li>
                    <li class="text-center">
                      <a href="#CPwd" data-toggle="tab">修改密码 </a>
                    </li>
                  </ul>
                </div>

                <div class="u-center-right pull-right">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="Uinfo">
                      <form class="form-horizontal">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">当前头像:</label>
                          <div class="col-sm-10">
                            <img id="uCenterImg" v-bind:src="avatar || '/img/userimg.png'">
                            <label class="select-img-btn" for="selectImg">编辑头像</label>
                            <input type="file" id="selectImg" @change="upload" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="uCenterName" class="col-sm-2 control-label">昵称:</label>
                          <div class="col-sm-10">
                            <input id="uCenterName" v-model="nickname" autocomplete="off" class="form-row input-text" type="text">
                            <small style="color: grey;">&nbsp; {{ isEditable ? '不超过10个字' : '昵称过长' }}</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">手机号码:</label>
                          <div class="col-sm-10">
                            <span class="form-row">{{ user.mobile }}</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn save" v-bind:disabled="!isEditable" type="button" @click="saveUser">保存
                            </button>
                            <button class="btn cancel" type="button" data-dismiss="modal">取消</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade in " id="CPwd">
                      <form class="form-horizontal">
                        <div class="form-group">
                          <label class="col-sm-2 control-label">原密码:</label>
                          <div class="col-sm-10">
                            <input v-model="password.old_password" class="form-row input-text" type="password">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">新密码:</label>
                          <div class="col-sm-10">
                            <input v-model="password.password" class="form-row input-text" type="password">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">确认密码:</label>
                          <div class="col-sm-10">
                            <input v-model="password.password_confirmation" class="form-row input-text" type="password">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn save" type="button" @click="savePassword">保存</button>
                            <button class="btn cancel" data-dismiss="modal">取消</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--课程安排-->
    <div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body container-fluid">
            <div class="modal-body-title text-center">
              <h1>课程安排</h1>
            </div>
            <div class="course-content">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>上课时间</th>
                    <th>星期一</th>
                    <th>星期二</th>
                    <th>星期三</th>
                    <th>星期四</th>
                    <th>星期五</th>
                    <th>星期六</th>
                    <th>星期天</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(schedule, hour) in schedules">
                    <th>{{ hour }}:00- {{ parseInt(hour) + 1 }}:00</th>
                    <th v-for="item in schedule">{{ item.title }} {{ item.lecturer ? '(' +item.lecturer+')' : '' }}</th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--财经日历-->
    <div class="modal fade pubmodal" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            <h3 class="modal-title" v-html="pubModal.title"></h3>
          </div>
          <div class="modal-body container-fluid" v-html="pubModal.content">
          </div>
        </div>
      </div>
    </div>
    <!--活动介绍-->
    <div class="modal fade pubmodal" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="activityModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body container-fluid">
            <div class="activity-center" v-html="pubModal.content">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--讲师介绍-->
    <div class="modal fade pubmodal" id="teacherModal" tabindex="-1" role="dialog" aria-labelledby="teacherModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body container-fluid">
            <h3 v-html="pubModal.title"></h3>
            <div class="teacher-center" v-html="pubModal.content">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--积分商城-->
    <div class="modal fade" id="shopModal" tabindex="-1" role="dialog" aria-labelledby="shopModal">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body container-fluid">
            <transition :name="cname">
              <div v-if="!loading" class="shop-list clearfix" ref="shoplist">
                <div class="row">
                  <div class="col-xs-3 shop-item" v-for="good in goods">
                    <div class="shop-item-content">
                      <div class="shop-item-img">
                        <img :src="good.img" alt="">
                      </div>
                      <div style="background-color:#fff">
                        <p class="shop-item-name">{{ good.title }}</p>
                        <div class="shop-item-desc clearfix">
                          <span class="shop-item-integral">
                             <i>{{ good.credits }}</i>积分</span>
                          <button class="btn" @click="order(good.id)">立即兑换</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </transition>

          </div>
          <div class="modal-footer">
            <nav aria-label="Page navigation">
              <page :url="goodsUrl" :onPageChanges="goodPage" :beforePageChanges="fadeout"></page>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
  #PubModal .modal-body iframe {
    width: 100%;
    height: 500px;
    border: none;
  }

  .user-c {
    position: relative;
    height: 56px;
  }

  #user-c:hover .user-hover {
    display: block;
  }

  .user-hover {
    display: none;
    position: absolute;
    top: 56px;
    left: -245px;
    padding: 12px 18px;
    width: 330px;
    height: 140px;
    border-radius: 5px;
    z-index: 999;
    background-color: #f5f5f5;
  }

  .user-hover-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-right: 20px;
  }

  .user-info {
    width: 194px;
    line-height: 0;
  }

  .user-info span {
    line-height: 24px;
    display: inline-block;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #696969;
  }

  .user-info span img {
    width: 16px;
    height: 16px;
  }

  .user-info .user-action {
    margin-top: 12px;
  }

  .user-info .user-action label {
    font-weight: 400;
    background-color: #ff7f00;
    padding: 2px 12px;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
  }

  .user-info .user-action .clicked {
    cursor: not-allowed;
    background-color: #e5e5e5;
    color: #696969;
    border: 1px solid #dadada;
  }

  .user-info .user-action button {
    border: 0;
    outline: 0;
    padding: 2px 8px;
    display: inline-block;
    background-color: transparent;
  }

  .user-info .user-action .line {
    display: inline-block;
    width: 2px;
    height: 18px;
    font-style: normal;
    margin-top: 5px;
    background-color: #6a6a6a;
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity .5s
  }

  .fade-enter,
  .fade-leave-to
  /* .fade-leave-active in <2.1.8 */

  {
    opacity: 0
  }

  .slide-left-enter-active,
  .slide-left-leave-active,
  .slide-right-enter-active,
  .slide-right-leave-active {
    transition: all 500ms ease;
  }

  .slide-left-enter {
    transform: translateX(-100%);
  }

  .slide-left-leave-to {
    transform: translateX(100%);
  }

  .slide-right-enter {
    transform: translateX(100%);
  }

  .slide-right-leave-to {
    transform: translateX(-100%);
  }
</style>
<script>
  import {
    uploadFile
  } from '../../app/utils'
  var NICKNAME_LENGTH = 10;
  export default {
    created() {

    },
    data() {
      return {
        token: '',
        password: {},
        backgrounds: [],
        background: localStorage.getItem('background'),
        csrf: Laravel.csrfToken,
        schedules: [],
        pubModal: {
          title: '',
          content: ''
        },
        nickname: '',
        avatar: '',
        goodsUrl: '/room/' + this.user.room.id + '/goods',
        goods: [],
        loading: false,
        cpage: 0,
        cname: 'slide-right'
      }
    },
    props: ['user', 'info'],
    components: {
      Page: require('./Paginator.vue')
    },
    computed: {
      isEditable: function () {
        return this.nickname.length <= NICKNAME_LENGTH;
      }
    },
    methods: {
      logout() {
        var self = this;
        swal({
          title: "确认退出?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "退出!",
          cancelButtonText: "取消",
          closeOnConfirm: false,
          html: false
        }, function () {
          self.$refs.lo_form.submit()
        });
      },
      upload(e) {
        uploadFile(e, '/upload/img', re => {
          this.avatar = re.url
        }, error => {
          toastrNotification('error', error.message)
        })
      },
      saveUser() {
        if (!this.isEditable) {
          return;
        }

        axios.post('/user', {
          nickname: this.nickname,
          avatar: this.avatar,
        }).then(response => {
          this.user.nickname = this.nickname;
          this.user.avatar = this.avatar;
          window.toastrNotification('success', '保存成功');
          $('#UcenterModal').modal('hide')
        }).catch(error => toastrNotification('error', error.response.data.message))
      },
      openUModal() {
        this.nickname = this.user.nickname;
        this.avatar = this.user.avatar;
        $('#UcenterModal').modal('show');
      },
      openCModal() {
        $('#courseModal').modal('show');
        this.getSchedules();
      },
      savePassword() {
        axios.post('/user/password', this.password).then(response => {
          this.password = {};
          window.toastrNotification('success', '保存成功');
          $('#UcenterModal').modal('hide');
        }).catch(error => toastrNotification('error', error.response.data.message))
      },
      getBackGrounds() {
        axios.get('/room/' + this.user.room.id + '/backgrounds').then(re => {
          this.backgrounds = re.data;
        })
      },
      getSchedules() {
        axios.get('/room/' + this.user.room.id + '/schedules').then(re => {
          this.schedules = re.data;
        })
      },
      changeBackground(bg) {
        this.background = bg;
        if (!bg || bg.length <= 0) {
          return;
        }
        localStorage.setItem('background', bg);
        document.body.style.backgroundImage = "url('" + this.background + "')";
      },
      saveBackground() {
        if (!this.background || this.background.length <= 0) {
          return;
        }
        localStorage.setItem('background', this.background);
        document.body.style.backgroundImage = "url('" + this.background + "')";
        $('#backgroundModal').modal('hide')
      },
      openBgModal() {
        $('#backgroundModal').modal('show')
      },
      getCal() {
        var url = this.info.calendar;
        this.pubModal = {
          title: '财经日历',
          content: `<iframe src="${url}"></iframe>`
        }
      },
      getAct() {
        this.pubModal = {
          title: '活动介绍',
          content: this.info.interactive
        }
      },
      getGold() {
        this.pubModal = {
          title: this.info.gold_lecturer_name,
          content: this.info.gold_lecturer
        }
      },
      download() {
        axios.get('/download').then(re => {})
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
        }).catch(er => toastrNotification('error', er.response.data.message));
      },
      goodPage(goods) {
        this.goods = goods;
        this.loading = !this.loading;
      },
      order(id) {
        var self = this;
        swal({
          title: "确认兑换?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "兑换!",
          cancelButtonText: "取消",
          closeOnConfirm: false,
          html: false
        }, function () {
          axios.post('/order/' + id + '/order').then(response => {
            var credits = response.data.credits;
            self.user.credits -= credits;
            swal("兑换成功!",
              '兑换成功,消耗' + credits + '积分!请联系管理员获取商品',
              "success");
          }).catch(er => {
            swal("兑换失败!",
              er.response.data.message,
              "error");
          });
        });
      },
      fadeout(page) {
        var c = '';
        window.console.log(page);
        if (page < this.cpage) {
          c = 'slide-left'
        } else {
          c = 'slide-right'
        }
        this.cname = c;
        this.cpage = page;
        this.$nextTick(() => {
          this.loading = !this.loading;
        })
      }

    },
    mounted() {
      //      this.user  = window.auth;
      this.token = window.Laravel.csrfToken;
      this.getBackGrounds();

    }
  }
</script>