<template>
  <div class="center-right container-fluid">
    <template v-if="user.room.video_position == 1">
      <player :user="user" :info="info"></player>
      <chat :user="user"></chat>
    </template>
    <template v-if="user.room.video_position == 2">
      <chat :user="user"></chat>
      <player :user="user" :info="info"></player>
    </template>
  </div>
</template>
<style>

</style>
<script>

  export default{
    created(){

    },
    data(){
      return {

      }
    },
    props : ['u','i'],
    components: {
      Player : require('./Player.vue'),
      Chat : require('./Chat.vue'),
    },
    methods   : {},
    mounted(){

    },
    computed : {
      user : function () {
        return this.u;
      },
      info : function () {
        return this.i;
      }
    }
  }
</script>