
define(function(require) {
  var $, pubsub, sf, _, __private, __public;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  __private = {
    moduleName: function() {
      return 'lib.Facebook';
    },
    isConnected: false,
    getFacebookConfig: function() {
      var conf;
      conf = sf.getConfigByKey('facebook');
      if (_.isUndefined(conf.forceRoundtrip)) {
        conf.forceRoundtrip = false;
      }
      if (_.isUndefined(conf.redirectAuthUrl)) {
        conf.redirectAuthUrl = 'https://www.facebook.com/dialog/oauth?client_id={{appId}}&scope={{scope}}&redirect_uri={{callbackUrl}}&response_type=token';
      }
      if (_.isUndefined(conf.callbackUrl)) {
        conf.callbackUrl = '/auth/facebook/';
      }
      return conf;
    }
  };
  __public = {
    init: function() {
      var _this = this;
      window.fbAsyncInit = function() {
        FB.init({
          appId: __private.getFacebookConfig()['appId'],
          status: true,
          cookie: true,
          oauth: true,
          xfbml: true,
          frictionlessRequests: true
        });
        return _this.initEventListeners();
      };
      this.loadSdk(window.document);
      return pubsub.subscribe('facebook:parsexfbml', function() {
        return FB.XFBML.parse();
      });
    },
    loadSdk: function(d) {
      var id, js;
      sf.log([__private.moduleName(), 'loadSDK']);
      $('body').prepend($('<div/>').attr('id', 'fb-root'));
      id = 'facebook-jssdk';
      if (d.getElementById(id)) {
        return false;
      }
      js = d.createElement('script');
      js.id = id;
      js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      return d.getElementsByTagName('head')[0].appendChild(js);
    },
    initEventListeners: function() {
      var initCallback,
        _this = this;
      sf.log([__private.moduleName(), 'initEventListeners']);
      initCallback = function(response) {
        sf.log([__private.moduleName(), 'event:getLoginStatus', response]);
        pubsub.subscribe('facebook:authHasSession', _this.setConnectionState);
        pubsub.subscribe('facebook:authNoSession', _this.setConnectionState);
        FB.Event.subscribe('auth.prompt', function(response) {
          sf.log([__private.moduleName(), 'event:auth.prompt']);
          return pubsub.publish('facebook:authPrompt', response);
        });
        FB.Event.subscribe('auth.sessionChange', function(response) {
          sf.log([__private.moduleName(), 'event:auth.sessionChange']);
          return _this.handleSessionRequestResponse(response);
        });
        FB.Event.subscribe('auth.statusChange', function(response) {
          sf.log([__private.moduleName(), 'event:auth.statusChange']);
          return _this.handleSessionRequestResponse(response);
        });
        FB.Event.subscribe('edge.create', function(response) {
          return pubsub.publish('facebook:createdLike', response);
        });
        FB.Event.subscribe('edge.remove', function(response) {
          return pubsub.publish('facebook:removedLike', response);
        });
        FB.Event.subscribe('comment.create', function(response) {
          return pubsub.publish('facebook:createdComment', response);
        });
        FB.Event.subscribe('comment.remove', function(response) {
          return pubsub.publish('facebook:removedComment', response);
        });
        FB.Event.subscribe('message.send', function(response) {
          return pubsub.publish('facebook:sentMessage', response);
        });
        pubsub.subscribe('facebook:loginViaRedirect', _this.loginViaRedirect);
        pubsub.subscribe('facebook:loginViaPopup', _this.loginViaPopup);
        pubsub.subscribe('facebook:logout', _this.logout);
        pubsub.publish('facebook:ready', true);
        return _this.handleSessionRequestResponse(response);
      };
      return FB.getLoginStatus(initCallback, __private.getFacebookConfig('forceRoundtrip'));
    },
    handleSessionRequestResponse: function(response) {
      sf.log([__private.moduleName(), 'handleSessionRequestResponse', response]);
      switch (response.status) {
        case 'connected':
          sf.log([__private.moduleName(), 'authHasSession']);
          pubsub.publish('facebook:authHasSession', response);
          break;
        case 'not_authorized':
          sf.log([__private.moduleName(), 'authNotAuthorized']);
          pubsub.publish('facebook:authNotAuthorized', response);
          break;
        default:
          sf.log([__private.moduleName(), 'authNoSession']);
          pubsub.publish('facebook:authNoSession', response);
      }
      return pubsub.publish('facebook:hasConnectionState', response);
    },
    setConnectionState: function(state) {
      return __private.isConnected = state;
    },
    hasConnection: function() {
      return __private.isConnected;
    },
    getLoginUrl: function() {
      var authUrl, key, params, val;
      sf.log([__private.moduleName(), 'getLoginUrl']);
      params = {
        appId: __private.getFacebookConfig()['appId'],
        scope: __private.getFacebookConfig()['permissions'].join(','),
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
      sf.log([__private.moduleName(), 'loginViaRedirect']);
      return window.location.href = this.getLoginUrl();
    },
    loginViaPopup: function(callback) {
      var cancelHandle;
      sf.log([__private.moduleName(), 'loginViaPopup']);
      sf.pageStateLoading(__private.moduleName() + '/loginViaPopup');
      cancelHandle = function(response) {
        if (response.authResponse === null) {
          return sf.pageStateLoaded(__private.moduleName() + '/loginViaPopup');
        }
      };
      return FB.login(cancelHandle, {
        scope: __private.getFacebookConfig()['permissions'].join(',')
      });
    },
    logout: function(callback) {
      sf.log([__private.moduleName(), 'logout']);
      return FB.logout(function(response) {
        pubsub.publish('facebook:authNoSession', response);
        if (callback !== void 0) {
          return callback();
        }
      });
    },
    hasSessionCookie: function() {
      var state;
      state = new RegExp(['\\b', 'fbsr_', __private.getFacebookConfig()['appId'], '\\b'].join('')).test(document.cookie);
      sf.log([__private.moduleName(), 'hasSessionCookie', state]);
      return state;
    },
    getPermissions: function(responseCallback) {
      sf.log([__private.moduleName(), 'getPermissions']);
      return FB.api('/me/permissions', responseCallback);
    },
    requestPermissions: function(permissions, callback) {
      sf.log([__private.moduleName(), 'requestPermissions', permissions]);
      return FB.login(callback, {
        scope: permissions.join(',')
      });
    },
    sendAppRequest: function(options) {
      var requestOptions;
      sf.log([__private.moduleName(), 'sendAppRequest', options]);
      requestOptions = {
        method: 'apprequests',
        message: options.message || 'Invite'
      };
      if (!_.isEmpty(options.fbUserIds)) {
        requestOptions.to = options.fbUserIds;
      }
      if (_.isUndefined(options.callback)) {
        options.callback = function(response) {
          return sf.log([__private.moduleName(), 'sendAppRequest:callback', response]);
        };
      }
      return FB.ui(requestOptions, options.callback);
    },
    deleteAppRequest: function(options) {
      sf.log([__private.moduleName(), 'deleteAppRequest', options]);
      if (!_.isUndefined(options.requestId) && !_.isUndefined(options.recipientFbUserId)) {
        if (_.isUndefined(options.callback)) {
          options.callback = function(response) {
            return sf.log([__private.moduleName(), 'deleteAppRequest:callback', response]);
          };
        }
        return FB.api("" + options.requestId + "_" + options.recipientFbUserId, 'DELETE', options.callback);
      } else {
        return sf.logError([__private.moduleName(), 'deleteAppRequest', 'missing params: requestId and/or recipientFbUserId']);
      }
    },
    getAppRequestDialog: function(options) {
      sf.log([__private.moduleName(), 'getAppRequestDialog', options]);
      options.fbUserIds = [];
      return this.sendAppRequest(options);
    },
    getLikeButton: function(options) {
      var fbButton, params;
      sf.log([__private.moduleName(), 'getLikeButton', JSON.stringify(options)]);
      if (!_.isUndefined(options.href)) {
        params = [];
        if (options.onComplete == null) {
          options.onComplete = function(button) {
            return sf.log([__private.moduleName(), 'getLikeButton:onComplete', button]);
          };
        }
        if (options.layout == null) {
          options.layout = 'button_count';
        }
        if (options.show_faces == null) {
          options.show_faces = false;
        }
        if (options.font == null) {
          options.font = 'lucida grande';
        }
        if (options.action == null) {
          options.action = 'like';
        }
        if (options.colorscheme == null) {
          options.colorscheme = 'dark';
        }
        if (options.send == null) {
          options.send = false;
        }
        if (options.width == null) {
          options.width = '50';
        }
        fbButton = '<div class="fb-like"\
                    data-href="' + options.href + '"\
                    data-send="' + options.send + '"\
                    data-layout="' + options.layout + '"\
                    data-width="' + options.width + '"\
                    data-show-faces="' + options.show_faces + '"\
                    data-colorscheme="' + options.colorscheme + '""\
                    data-font="' + options.font + '""\
                    data-action="' + options.action + '">\
                  </div>';
        return options.onComplete(fbButton);
      } else {
        return sf.logError([__private.moduleName(), 'getLikeButton', 'missing options.href']);
      }
    },
    createStreamPost: function(options) {
      var params;
      sf.log([__private.moduleName(), 'createStreamPost', options]);
      params = {
        link: options.link,
        name: options.name
      };
      if (!_.isUndefined(options.picture)) {
        params.picture;
      }
      if (!_.isUndefined(options.message)) {
        params.message;
      }
      if (!_.isUndefined(options.caption)) {
        params.caption;
      }
      if (!_.isUndefined(options.description)) {
        params.description;
      }
      return FB.api('/me/feed', 'POST', params, function(response) {
        return sf.log([__private.moduleName(), 'createStreamPost:callback', response]);
      });
    },
    deleteStreamPost: function(postId) {
      sf.log([__private.moduleName(), 'deleteStreamPost', postId]);
      return FB.api(postId, 'DELETE', function(response) {
        if (!_.isEmpty(postId)) {
          return sf.log([__private.moduleName(), 'deleteStreamPost:callback', response]);
        }
      });
    },
    getUserImage: function(fbUserId, size, ssl) {
      var url;
      if (size == null) {
        size = 'square';
      }
      if (ssl == null) {
        ssl = false;
      }
      url = 'http://graph.facebook.com/' + fbUserId + '/picture?type=' + size;
      if (ssl === true) {
        url = (url + '&return_ssl_resources=1').replace('http://', 'https://');
      }
      return url;
    }
  };
  return __public;
});
