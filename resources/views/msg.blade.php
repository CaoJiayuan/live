@extends('layouts.app')
@section('css')
  <style>
    #chat_show {
      height: 200px;
      background-color: #e6e0ed;
      overflow-y: scroll;
    }
  </style>
@endsection
@section('content')
  <div class="container">
    <div id="chat_show">

    </div>
    <div class="form-group">
      <div class="form-group">
        <input type="text" class="form-control" id="msg">
        <button onclick="publish()">发送</button>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
    var topic = 'test';
    //登陆成功
    ROP.On("enter_suc",
        function () {
          ShowMsg("EnterSuc");
        })
    //重连中
    ROP.On("reconnect",
        function () {
          ShowMsg("reconnect:");
        })
    //离线状态，之后会重连
    ROP.On("offline",
        function (err) {
          ShowMsg("offline:" + err);
        })
    //登陆失败
    ROP.On("enter_fail",
        function (err) {
          ShowMsg("EnterFail:" + err);
        })
    //收到消息
    ROP.On("publish_data",
        function (data, topic) {
          ShowMsg("recv at " + topic + " -> " + data);
        })
    //彻底断线了
    ROP.On("losed",
        function () {
          ShowMsg("Losed");
        })
    function ShowMsg(str) {
      document.getElementById("chat_show").innerHTML = str + '<br/>' + document.getElementById("chat_show").innerHTML
    }
    function publish() {
      var body = document.getElementById("msg").value;
      var msg  = {
        msg    : body,
        user_id: 1
      };
      ROP.Publish(JSON.stringify(msg), topic);
    }
    function enter() {
      ROP.Enter('pub_212d1939df5f1e6e68d261d59be6f7a1', 'sub_4cf077d88ed4ca4b7efb0a68886756ea');
    }
    function join() {
      ROP.Subscribe(topic);
    }
    function OnUnJoin() {
      ROP.UnSubscribe(document.getElementById("idgroup").value);
    }
    function Clear() {
      document.getElementById("chat_show").value = ""
    }
    enter();
    join();
  </script>
@endsection