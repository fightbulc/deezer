
define(function(require) {
  var $, api, private, public, sf, _;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  private = {
    vars: {
      config: {},
      posts: [],
      handlers: {},
      pullParams: {}
    },
    getPostofficeConfig: function(key) {
      return this.vars.config[key];
    }
  };
  public = {
    init: function(options) {
      var config;
      if (options == null) options = {};
      config = {
        alive: true,
        id: options.id || new Date().getTime(),
        intervalMs: options.intervalMs || 5000,
        pullApi: options.pullApi || null
      };
      _.app().core.log(['POSTOFFICE', config.id, 'init', config]);
      _.app().module.mediator.subscribe({
        channel: 'postoffice:' + config.id + ':addHandler',
        callback: setHandler
      });
      _.app().module.mediator.subscribe({
        channel: 'postoffice:' + config.id + ':addPosts',
        callback: setPosts
      });
      _.app().module.mediator.subscribe({
        channel: 'postoffice:' + config.id + ':addParams',
        callback: setPullParams
      });
      return _.app().module.mediator.subscribe({
        channel: 'postoffice:' + config.id + ':stop',
        callback: turnOff
      });
    },
    setConfig: function(key, val) {
      return config[key] = val;
    },
    setintervalMs: function(seconds) {
      return setConfig('intervalMs', seconds);
    },
    setPullApi: function(url) {
      return setConfig('pullApi', url);
    },
    setPullParams: function(params) {
      var key, val, _results;
      _results = [];
      for (key in params) {
        val = params[key];
        _results.push(pullParams[key] = val);
      }
      return _results;
    },
    setPosts: function(post) {
      var posts;
      return posts = post;
    },
    setHandler: function(handler) {
      var group, worker, _results;
      _results = [];
      for (group in handler) {
        worker = handler[group];
        _results.push(handlers[group] = worker);
      }
      return _results;
    },
    getPullParams: function() {
      return pullParams;
    },
    resetPosts: function() {
      var posts;
      return posts = [];
    },
    turnOff: function() {
      return setConfig('alive', false);
    },
    runHandler: function(group, data) {
      if (!_u.isUndefined(handlers[group])) return handlers[group](data);
    },
    processPosts: function() {
      var _this = this;
      _u.each(posts, function(post) {
        var data, group, _results;
        _results = [];
        for (group in post) {
          data = post[group];
          _results.push(runHandler(group, data));
        }
        return _results;
      });
      resetPosts();
      return setTimeout(this.run, getConfig('intervalMs'));
    },
    run: function() {
      var _this = this;
      if (!getConfig('alive')) return;
      if (!_u.isEmpty(posts)) {
        return processPosts();
      } else {
        if (getConfig('pullApi') && getConfig('intervalMs') > 0) {
          return _.app().core.jsonRequest({
            api: getConfig('pullApi'),
            params: getPullParams(),
            success: function(response, status) {
              if (typeof response.postoffice !== 'undefined') {
                response = response.postoffice;
              }
              setPosts(response);
              return processPosts();
            }
          });
        } else {
          return _.app().core.log(['POSTOFFICE', getConfig('id'), 'Missing pullApi and/or intervalMs']);
        }
      }
    }
  };
  return api = {
    module: "lib.Postoffice",
    public: public
  };
});
