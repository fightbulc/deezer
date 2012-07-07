
define(function(require) {
  var $, api, private, public, _;
  $ = require('jquery');
  _ = require('underscore');
  private = {
    isLoggingEnabled: function() {
      if (_.isUndefined(console) === false && public.getConfigByKey('logging') === true) {
        return true;
      }
    }
  };
  public = {
    log: function(args) {
      if (private.isLoggingEnabled() === true) return console.log(args);
    },
    logError: function(args) {
      if (private.isLoggingEnabled() === true) return console.error(args);
    },
    getConfig: function() {
      return window._appConfig;
    },
    getConfigByKey: function(key) {
      return this.getConfig()[key];
    },
    pageStateLoading: function() {
      $('#page-loaded').hide();
      return $('#page-loading').show();
    },
    pageStateLoaded: function() {
      $('#page-loading').hide();
      return $('#page-loaded').show();
    },
    jsonRequest: function(options) {
      var apiRoot,
        _this = this;
      if (options.id == null) options.id = 1;
      if (options.params == null) options.params = {};
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
            return _this.log([api.module, 'jsonRequest', 'SUCCESSHANDLER', data, status]);
          };
        }
        if (_.isUndefined(options.error)) {
          options.error = function(data, message, options) {
            return _this.logError([api.module, 'jsonRequest', 'ERRORHANDLER', message, data, options]);
          };
        }
        return $.ajax({
          url: options.url,
          type: 'POST',
          contentType: 'application/json',
          dataType: 'json',
          data: '{"id": "' + options.id + '", "method": "' + options.api + '", "params": [' + JSON.stringify(options.params) + ']}',
          timeout: 20000,
          success: function(data, status) {
            if (!_.isUndefined(data.result)) {
              if (!_.isEmpty(data.result) && !_.isUndefined(options.success)) {
                return options.success(data.result, status);
              } else {
                return options.error(data, 'Either received empty result or missing success callback', options);
              }
            } else {
              return options.error(data, 'Request failed', options);
            }
          },
          error: function(jqXHR, response, errorThrown) {
            options.rawResponse = jqXHR.responseText;
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
  return api = {
    module: 'snakeface',
    public: public
  };
});
