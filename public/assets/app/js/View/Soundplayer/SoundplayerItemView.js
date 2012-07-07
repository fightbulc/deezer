
define(function(require) {
  var $, abstractView, pubsub, __private;
  $ = require('jquery');
  pubsub = require('pubsub');
  abstractView = require('abstractView');
  __private = {
    moduleName: function() {
      return 'view.soundplayerItem';
    }
  };
  return abstractView.extend({
    events: {},
    initialize: function() {}
  });
});
