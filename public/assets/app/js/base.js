
define(function(require) {
  var postoffice, pubsub, sf, _, __private, __public;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  postoffice = require('postoffice');
  __private = {
    moduleName: function() {
      return 'base';
    },
    instances: {}
  };
  __public = {
    setInstance: function(key, instance) {
      sf.log([__private.moduleName(), '>>>', 'setInstance', key]);
      return __private.instances[key] = instance;
    },
    hasInstance: function(key) {
      return _.isUndefined(__private.instances[key]) === false;
    },
    getInstance: function(key) {
      return __private.instances[key];
    },
    getRouter: function() {
      return this.getInstance('router');
    },
    getInitialData: function(options) {
      var _this = this;
      return sf.jsonRequest({
        api: 'Web.Base.getInitialData',
        params: options,
        success: function(response) {
          return pubsub.publish('base:initialData', response);
        }
      });
    },
    reset: function() {
      sf.log([__private.moduleName(), 'resetInstances']);
      _.each(__private.instances, function(instance, instanceKey) {
        if (/View$/.test(instanceKey)) {
          instance.undelegateEvents();
          return instance.$el.empty();
        }
      });
      __private.instances = {};
      return postoffice.reset();
    }
  };
  return __public;
});
