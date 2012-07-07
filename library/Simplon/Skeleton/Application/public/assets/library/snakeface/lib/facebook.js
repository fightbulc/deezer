
define(function(require) {
  var $, api, private, public, pubsub, sf, _;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  private = {
    getFacebookConfig: function() {
      var conf;
      conf = sf.public.getConfigByKey('facebook');
      if (_.isUndefined(conf.forceRoundtrip)) conf.forceRoundtrip = false;
      if (_.isUndefined(conf.redirectAuthUrl)) {
        conf.redirectAuthUrl = 'https://www.facebook.com/dialog/oauth?client_id={{appId}}&scope={{scope}}&redirect_uri={{callbackUrl}}&response_type=token';
      }
      if (_.isUndefined(conf.callbackUrl)) conf.callbackUrl = '/auth/facebook/';
      return conf;
    }
  };
  public = {
    init: function() {
      var _this = this;
      window.fbAsyncInit = function() {
        FB.init({
          appId: private.getFacebookConfig()['appId'],
          status: true,
          cookie: true,
          oauth: true,
          xfbml: true,
          frictionlessRequests: true
        });
        return _this.initEventListeners();
      };
      return this.loadSdk(window.document);
    },
    loadSdk: function(d) {
      var id, js;
      sf.public.log([api.module, 'loadSDK']);
      $('body').prepend($('<div/>').attr('id', 'fb-root'));
      id = 'facebook-jssdk';
      if (d.getElementById(id)) return false;
      js = d.createElement('script');
      js.id = id;
      js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      return d.getElementsByTagName('head')[0].appendChild(js);
    },
    initEventListeners: function() {
      var initCallback,
        _this = this;
      sf.public.log([api.module, 'initEventListeners']);
      initCallback = function(response) {
        sf.public.log([api.module, 'event:getLoginStatus', response]);
        FB.Event.subscribe('auth.prompt', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:authPrompt',
            data: [response]
          });
        });
        FB.Event.subscribe('auth.sessionChange', function(response) {
          sf.public.log([api.module, 'event:auth.sessionChange']);
          return _this.handleSessionRequestResponse(response);
        });
        FB.Event.subscribe('auth.statusChange', function(response) {
          sf.public.log([api.module, 'event:auth.statusChange']);
          return _this.handleSessionRequestResponse(response);
        });
        FB.Event.subscribe('edge.create', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:createdLike',
            data: [response]
          });
        });
        FB.Event.subscribe('edge.remove', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:removedLike',
            data: [response]
          });
        });
        FB.Event.subscribe('comment.create', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:createdComment',
            data: [response]
          });
        });
        FB.Event.subscribe('comment.remove', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:removedComment',
            data: [response]
          });
        });
        FB.Event.subscribe('message.send', function(response) {
          return pubsub.public.publish({
            channel: 'facebook:sentMessage',
            data: [response]
          });
        });
        pubsub.public.subscribe({
          channel: 'facebook:loginViaRedirect',
          callback: _this.loginViaRedirect
        });
        pubsub.public.subscribe({
          channel: 'facebook:loginViaPopup',
          callback: _this.loginViaPopup
        });
        pubsub.public.subscribe({
          channel: 'facebook:logout',
          callback: _this.logout
        });
        pubsub.public.publish({
          channel: 'facebook:ready',
          data: [true]
        });
        return _this.handleSessionRequestResponse(response);
      };
      return FB.getLoginStatus(initCallback, private.getFacebookConfig('forceRoundtrip'));
    },
    handleSessionRequestResponse: function(response) {
      sf.public.log([api.module, 'handleSessionRequestResponse']);
      switch (response.status) {
        case 'connected':
          sf.public.log([api.module, 'authHasSession']);
          return pubsub.public.publish({
            channel: 'facebook:authHasSession',
            data: [response]
          });
        case 'not_authorized':
          sf.public.log([api.module, 'authNotAuthorized']);
          return pubsub.public.publish({
            channel: 'facebook:authNotAuthorized',
            data: [response]
          });
        default:
          sf.public.log([api.module, 'authNoSession']);
          return pubsub.public.publish({
            channel: 'facebook:authNoSession',
            data: [response]
          });
      }
    },
    getLoginUrl: function() {
      var authUrl, key, params, val;
      sf.public.log([api.module, 'getLoginUrl']);
      params = {
        appId: private.getFacebookConfig()['appId'],
        scope: private.getFacebookConfig()['permissions'].join(','),
        callbackUrl: encodeURIComponent([sf.getFacebookConfig()['permissions'].join(','), sf.getFacebookConfig()['callbackUrl']].join(''))
      };
      authUrl = getConf().authUrl;
      for (key in params) {
        val = params[key];
        authUrl = authUrl.replace("{{" + key + "}}", val);
      }
      return authUrl;
    },
    loginViaRedirect: function() {
      sf.public.log([api.module, 'loginViaRedirect']);
      return window.location.href = this.getLoginUrl();
    },
    loginViaPopup: function(callback) {
      sf.public.log([api.module, 'loginViaPopup']);
      return FB.login((function(response) {}), {
        scope: private.getFacebookConfig()['permissions'].join(',')
      });
    },
    logout: function() {
      sf.public.log([api.module, 'logout']);
      return FB.logout(function(response) {
        return pubsub.public.publish({
          channel: 'facebook:authLogout',
          data: [response]
        });
      });
    },
    hasSessionCookie: function() {
      var state;
      state = new RegExp(['\\b', 'fbsr_', private.getFacebookConfig()['appId'], '\\b'].join('')).test(document.cookie);
      sf.public.log([api.module, 'hasSessionCookie', state]);
      return state;
    },
    getPermissions: function(responseCallback) {
      sf.public.log([api.module, 'getPermissions']);
      return FB.api('/me/permissions', responseCallback);
    },
    requestPermissions: function(permissions, callback) {
      sf.public.log([api.module, 'requestPermissions', permissions]);
      return FB.login(callback, {
        scope: permissions.join(',')
      });
    },
    sendAppRequest: function(options) {
      var requestOptions;
      sf.public.log([api.module, 'sendAppRequest', options]);
      requestOptions = {
        method: 'apprequests',
        message: options.message || 'Invite'
      };
      if (!_.isEmpty(options.fbUserIds)) requestOptions.to = options.fbUserIds;
      if (_.isUndefined(options.callback)) {
        options.callback = function(response) {
          return sf.public.log([api.module, 'sendAppRequest:callback', response]);
        };
      }
      return FB.ui(requestOptions, options.callback);
    },
    deleteAppRequest: function(options) {
      sf.public.log([api.module, 'deleteAppRequest', options]);
      if (!_.isUndefined(options.requestId) && !_.isUndefined(options.recipientFbUserId)) {
        if (_.isUndefined(options.callback)) {
          options.callback = function(response) {
            return sf.public.log([api.module, 'deleteAppRequest:callback', response]);
          };
        }
        return FB.api("" + options.requestId + "_" + options.recipientFbUserId, 'DELETE', options.callback);
      } else {
        return sf.public.logError([api.module, 'deleteAppRequest', 'missing params: requestId and/or recipientFbUserId']);
      }
    },
    getAppRequestDialog: function(options) {
      sf.public.log([api.module, 'getAppRequestDialog', options]);
      options.fbUserIds = [];
      return this.sendAppRequest(options);
    },
    getLikeButton: function(options) {
      var fbButton, key, params, val;
      sf.public.log([api.module, 'getLikeButton', options]);
      if (!_.isUndefined(options.href)) {
        params = [];
        if (options.onComplete == null) {
          options.onComplete = function(button) {
            return sf.public.log([api.module, 'getLikeButton:onComplete', button]);
          };
        }
        if (options.layout == null) options.layout = 'button_count';
        if (options.show_faces == null) options.show_faces = false;
        if (options.font == null) options.font = 'tahoma';
        if (options.action == null) options.action = 'like';
        if (options.colorscheme == null) options.colorscheme = 'light';
        for (key in options) {
          val = options[key];
          params.push([key, val].join('='));
        }
        fbButton = '<fb:like ' + params.join(' ') + '></fb:like>';
        return options.onComplete(fbButton);
      } else {
        return sf.public.logError([api.module, 'getLikeButton', 'missing options.href']);
      }
    },
    createStreamPost: function(options) {
      var params;
      sf.public.log([api.module, 'createStreamPost', options]);
      params = {
        link: options.link,
        name: options.name
      };
      if (!_.isUndefined(options.picture)) params.picture;
      if (!_.isUndefined(options.message)) params.message;
      if (!_.isUndefined(options.caption)) params.caption;
      if (!_.isUndefined(options.description)) params.description;
      return FB.api('/me/feed', 'POST', params, function(response) {
        return sf.public.log([api.module, 'createStreamPost:callback', response]);
      });
    },
    deleteStreamPost: function(postId) {
      sf.public.log([api.module, 'deleteStreamPost', postId]);
      return FB.api(postId, 'DELETE', function(response) {
        if (!_.isEmpty(postId)) {
          return sf.public.log([api.module, 'deleteStreamPost:callback', response]);
        }
      });
    }
  };
  return api = {
    module: 'lib.Facebook',
    public: public
  };
});
