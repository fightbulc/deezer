
define(function(require) {
  var $, pubsub, _, __private, __public;
  $ = require('jquery');
  _ = require('underscore');
  pubsub = require('pubsub');
  __private = {
    pageScrollPosition: {},
    currentPageId: null,
    moduleName: function() {
      return 'snakeface';
    },
    isLoggingEnabled: function() {
      if (_.isUndefined(console) === false && __public.getConfigByKey('logging') === true) {
        return true;
      }
    },
    setCurrentPageId: function(pageId) {
      return this.currentPageId = pageId;
    },
    saveScrollPosition: function() {
      if (this.currentPageId) {
        return this.pageScrollPosition[this.currentPageId] = $(window).scrollTop();
      }
    },
    restoreScrollPosition: function() {
      return $(window).scrollTop(this.pageScrollPosition[this.currentPageId]);
    },
    scrollToTop: function() {
      return $(window).scrollTop(0);
    }
  };
  __public = {
    log: function(args) {
      if (__private.isLoggingEnabled() === true) {
        return console.log(args);
      }
    },
    logError: function(args) {
      if (__private.isLoggingEnabled() === true) {
        return console.error(args);
      }
    },
    getConfig: function() {
      return window._appConfig;
    },
    getConfigByKey: function(key) {
      return this.getConfig()[key];
    },
    pageStateLoading: function(callerId) {
      this.log(['pageStateLoading', '@@@@@@@@@@@@@', callerId]);
      $('#pageLoading').show();
      return __private.saveScrollPosition();
    },
    pageStateLoaded: function(callerId) {
      this.log(['pageStateLoaded', '$$$$$$$$$$$$$$$$', callerId]);
      return $('#pageLoading').fadeOut(50);
    },
    hideAllPages: function() {
      return $('.page').hide();
    },
    showPage: function(pageId) {
      $("#" + pageId).removeClass('pageInvisible');
      $("#" + pageId).show();
      __private.scrollToTop();
      return pubsub.publish('showPage:ready', pageId);
    },
    switchPage: function(pageId) {
      __private.setCurrentPageId(pageId);
      this.hideAllPages();
      this.showPage(pageId);
      return __private.restoreScrollPosition();
    },
    jsonRequest: function(options) {
      var apiRoot, data, sessionId,
        _this = this;
      if (options.id == null) {
        options.id = 1;
      }
      if (options.params == null) {
        options.params = {};
      }
      if (!_.isUndefined(options.api)) {
        this.getConfigByKey('url').api = $.trim(this.getConfigByKey('url').api);
        apiRoot = "" + (this.getConfigByKey('url').api);
        if (options.mock === true) {
          apiRoot = "" + (this.getConfigByKey('url').api) + "/mock";
        }
        options.domain = options.api.toLowerCase().split('.').shift();
        options.url = apiRoot + '/' + options.domain + '/';
        if (_.isUndefined(options.success)) {
          options.success = function(data, status) {
            return _this.log([options.api, 'jsonRequest', 'SUCCESSHANDLER', data, status]);
          };
        }
        if (_.isUndefined(options.error)) {
          options.error = function(data, message, options) {
            return _this.logError([options.api, 'jsonRequest', 'ERRORHANDLER', message, data, options]);
          };
        }
        if ((sessionId = this.getConfig().sessionId) != null) {
          options.params._sessionId = sessionId;
        }
        data = {
          id: String(options.id),
          method: String(options.api),
          params: [options.params]
        };
        return $.ajax({
          url: options.url,
          type: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: JSON.stringify(data),
          timeout: 20000,
          success: function(data, status) {
            if (!_.isUndefined(data.result)) {
              if (!_.isEmpty(data.result) && !_.isUndefined(options.success)) {
                options.success(data.result, status);
                return pubsub.publish('jsonRequest:success', data.result);
              } else {
                return options.error(data, 'Either received empty result or missing success callback', options);
              }
            } else {
              return options.error(data, 'Request failed', options);
            }
          },
          error: function(jqXHR, response, errorThrown) {
            try {
              response = JSON.parse(jqXHR.responseText).result.error;
            } catch (error) {
              response = null;
            }
            if (!_.isUndefined(options.error)) {
              return options.error(response, errorThrown, options);
            } else {
              return _this.logError(["snakeface.jsonRequest", response, errorThrown, options]);
            }
          }
        });
      } else {
        return this.logError(["snakeface.jsonRequest", "options.api is undefined", options]);
      }
    }
  };
  return __public;
});
