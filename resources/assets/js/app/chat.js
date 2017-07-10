/**
 * Created by d on 17-5-9.
 */
(function () {
  function DmsChat(topic) {
    ROP.Enter(pubKey, subKey);
    this.topic = topic;
    ROP.Subscribe(topic);
    this.onMessageDispatcher = function () {}
  }

  DmsChat.prototype.join      = function (topic) {
    ROP.Subscribe(topic);
    return this;
  };
  DmsChat.prototype.onMessage = function (onMessage) {
    var wrapper = function (data, topic) {
      var d;
      try {
        d = JSON.parse(data);
      } catch (message) {
        d = data;
      }
      typeof onMessage == 'function' || (onMessage = function () {
      });
      onMessage(d, topic);
    };
    this.onMessageDispatcher = wrapper;
    return this.on('publish_data', wrapper);
  };

  DmsChat.prototype.onClose = function (onClose) {
    return this.on('losed', onClose);
  };

  DmsChat.prototype.onOffline = function (onOffline) {
    return this.on('offline', onOffline);
  };

  DmsChat.prototype.on = function (event, dispatcher) {
    dispatcher || (dispatcher = function () {
    });
    ROP.On(event, dispatcher);
    return this;
  };

  DmsChat.prototype.histories = function (page) {
    page = page || 1;
    var self = this;
    $.get('/api/chat/' + this.topic + '/histories?page='+ page, function (data) {
      data.forEach(function (item) {
        self.onMessageDispatcher(item, self.topic);
      })
    });
  };

  DmsChat.prototype.send = function (message) {
    typeof message == 'object' && (message = JSON.stringify(message));
    ROP.Publish(message, this.topic);
  };
  window.DmsChat         = DmsChat;
})(window);
