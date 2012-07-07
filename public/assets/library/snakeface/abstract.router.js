
define(function(require) {
  var Backbone, pubsub, sf, _, __private;
  _ = require('underscore');
  Backbone = require('backbone');
  sf = require('snakeface');
  pubsub = require('pubsub');
  __private = {
    moduleName: function() {
      return 'abstract.router';
    }
  };
  return Backbone.Router.extend({
    initialize: function() {
      sf.log([__private.moduleName(), 'init', sf.getConfig()]);
      Backbone.history.stop();
      Backbone.history.start({
        root: sf.getConfigByKey('url')["public"]
      });
      pubsub.subscribe('router:redirect', this.redirect);
      return pubsub.subscribe('router:update', this.updateUrl);
    },
    redirect: function(route) {
      sf.log([__private.moduleName(), 'redirect', route]);
      return Backbone.history.navigate("!/" + route, {
        trigger: true
      });
    },
    updateUrl: function(route) {
      sf.log([__private.moduleName(), 'updateUrl', route]);
      return Backbone.history.navigate("!/" + route);
    },
    getCurrentRoute: function() {
      sf.log([__private.moduleName(), 'getCurrentRoute']);
      return Backbone.history.fragment.replace('!/', '');
    }
  });
});
