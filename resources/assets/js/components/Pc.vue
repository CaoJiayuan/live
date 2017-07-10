<template>
  <div>
    <top :user="user" :info="info"></top>
    <left :user="user"></left>
    <right :u="user" :i="info"></right>
    <div class="modal fade" id="popup-modal" tabindex="-1" role="dialog" aria-labelledby="popup-modal">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
          <div class="modal-header ">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true ">&times;
          </span></button>
          </div>
          <div class="modal-body container-fluid">
            <a v-bind:href="popup ? popup.url : ''" target="_blank">
              <img v-bind:src="popup ? popup.img : ''" alt="" class="img-responsive img-responsive center-block">
            </a>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>
<style>
  @import "../../../../public/css/index.css";
  @import "../../../../public/css/toastr.min.css";
  @import "../../../../public/css/jquery.mCustomScrollbar.css";
  @import "../../../../public/css/jquery.emoji.css";
  @import "../../../../public/css/sweetalert.css";
</style>
<script>
  import {setMaxheight, notice} from '../main';
  require('../../../../public/js/jquery.emoji');
  require('sweetalert');
  export default{
    created(){

    },
    data(){
      return {
        user  : window.auth,
        popup : null,
        info  : {},
        timers: [],
      }
    },
    components   : {
      Top  : require('./pc/Top.vue'),
      Left : require('./pc/Left.vue'),
      Right: require('./pc/Right.vue')
    },
    methods      : {
      reloadUser(){
        axios.get('/user').then(response => {
          this.user = response.data;
        }).catch(error => {
          window.location.href = '/login';
        });
      },
      getPopup(){
        if (this.user.room.popup) {
          axios.get('/room/' + this.user.room.id + '/popup').then(re => {
            this.popup = re.data;
            re.data && $('#popup-modal').modal('show');
          })
        }
      },
      getInfo() {
        axios.get('/room/' + this.user.room.id + '/info').then(response => {
          this.info = response.data;
        })
      },
      reloadRt() {
        this.reloadUser();
        this.getInfo();
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
    mounted(){
      vueApp.$on('reload.user', this.reloadRt);
      this.getCreditRules();
      this.getInfo();
      this.getPopup();
    },
    beforeDestroy: function () {
      vueApp.$off('reload.user')
    }
  }
</script>