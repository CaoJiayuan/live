<template>
  <li class="clearfix">
    <div class="user-desc">
      <div style="margin-bottom:5px">
        <span class="data">{{ message.time }} </span>
  
        <span>
          <img v-bind:src="message.user.level_img ? message.user.level_img : '/img/level/1.png'" width="15" height="15">
        </span>
        <span>
          <img v-bind:src="'pc' != getUa(message.user) ? '/img/mobile.png' : '/img/pc.png'">
        </span>
        <span class="user-name">{{ message.user.nickname ? message.user.nickname : message.user.name }} </span>
      </div>
      <div class="chat" v-html="message.msg"></div>
    </div>
  </li>
</template>
<style>
.data {
  padding: 2px 5px;
  background-color: #003d80;
  border-radius: 4px;
}
</style>
<script>

export default {
  props: {
    message: {},
    roll: {
      default: true,
      type: Boolean
    }
  },
  created() {

  },
  data() {
    return {}
  },
  components: {},
  methods: {
    getUa(user) {
      return user.ua ? user.ua.toLowerCase() : 'pc';
    }
  },
  mounted() {
    //      this.roll && this.$el.scrollIntoView()
  }
}
</script>