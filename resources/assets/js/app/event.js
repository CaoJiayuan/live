/**
 * Created by d on 17-6-29.
 */
const ev          = {
  commands: {
    RELOAD: 'command:reload',
    NOTICE: 'command:notice',
    GIFT  : 'command:gift'
  },
  events  : {
    LOGIN     : 'login',
    DISCONNECT: 'disconnect',
    USERS     : 'users',
    RELOAD    : 'reload',
    NOTICE    : 'notice',
    RECONNECT : 'reconnect',
    PING      : 'ping',
    GIFT      : 'gift'
  }
};

export default ev;