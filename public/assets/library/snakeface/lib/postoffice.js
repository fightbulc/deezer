
define(function(require) {
  var sf, _, __private, __public;
  _ = require('underscore');
  sf = require('snakeface');
  __private = {
    moduleName: function() {
      return 'lib.Postoffice';
    },
    services: {},
    timers: {},
    defaultOptions: {
      intervalMs: 5000,
      params: {},
      posts: [],
      handlers: {}
    },
    options: {}
  };
  return __public = {
    register: function(options) {
      __private.services[options.api] = _.clone(__private.defaultOptions);
      if (options.params != null) {
        __private.services[options.api].params = options.params;
      }
      if (options.intervalMs != null) {
        __private.services[options.api].intervalMs = options.intervalMs;
      }
      return sf.log([__private.moduleName(), options.api, __private.services[options.api].intervalMs]);
    },
    setParams: function(api, params) {
      return __private.services[api].params = params;
    },
    setPosts: function(api, post) {
      return __private.services[api].posts = post;
    },
    setHandler: function(api, group, worker) {
      return __private.services[api].handlers[group] = worker;
    },
    getApi: function(api) {
      return __private.services[api].api;
    },
    getParams: function(api) {
      return __private.services[api].params;
    },
    runHandler: function(api, group, data) {
      if (__private.services[api].handlers[group] != null) {
        return __private.services[api].handlers[group](data);
      }
    },
    processPosts: function(api) {
      var data, group, _ref,
        _this = this;
      _ref = __private.services[api].posts;
      for (group in _ref) {
        data = _ref[group];
        this.runHandler(api, group, data);
      }
      this.resetPosts(api);
      return __private.timers[api] = setTimeout((function() {
        return _this.run(api);
      }), __private.services[api].intervalMs);
    },
    run: function(api) {
      var _this = this;
      if (__private.timers[api] != null) {
        clearTimeout(__private.timers[api]);
      }
      if (__private.services[api] != null) {
        if (__private.services[api].posts.length > 0) {
          return this.processPosts(api);
        } else {
          if (__private.services[api].intervalMs > 0) {
            return sf.jsonRequest({
              api: api,
              params: __private.services[api].params,
              success: function(response, status) {
                if (response.postoffice != null) {
                  response = response.postoffice;
                }
                _this.setPosts(api, response);
                return _this.processPosts(api);
              },
              error: function() {
                return _this.processPosts(api);
              }
            });
          }
        }
      }
    },
    resetPosts: function(api) {
      if (__private.services[api] != null) {
        return __private.services[api].posts = [];
      }
    },
    remove: function(api) {
      sf.log([__private.moduleName(), 'remove', api]);
      if (__private.services[api] != null) {
        return delete __private.services[api];
      }
    },
    reset: function() {
      sf.log([__private.moduleName(), 'reset']);
      return __private.services = {};
    }
  };
});
