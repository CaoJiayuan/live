<template>
  <li class="clearfix">
    <div class="user-img pull-left">
      <img v-bind:src="message.user.avatar  || '/img/userimg.png'" alt="">
    </div>
    <div class="user-desc pull-left">
      <span class="user-name">{{ message.user.nickname ? message.user.nickname : message.user.name }} </span>
      <div class="chat" v-html="message.msg"> </div>
    </div>
  </li>
</template>
<style>

</style>
<script>

export default {
  created() {

  },
  props: {
    message: {}
  },
  data() {
    return {}
  },
  components: {},
  methods: {},
  mounted() {
    //    this.$el.scrollIntoView()
  }
}
</script>