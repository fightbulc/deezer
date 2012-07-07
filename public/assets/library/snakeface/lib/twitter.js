
define(function(require) {
  var $, pubsub, sf, twitter, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  __private = {
    moduleName: function() {
      return 'lib.Twitter';
    }
  };
  twitter = (function() {

    twitter.name = 'twitter';

    function twitter() {
      this.loadSdk();
    }

    twitter.prototype.loadSdk = function() {
      var script;
      sf.log([__private.moduleName(), 'loadSdk']);
      script = window.document.createElement('script');
      script.id = "twitter-wjs";
      script.src = "" + window.location.protocol + "//platform.twitter.com/widgets.js";
      return $('body').append(script);
    };

    twitter.prototype.loadTwitterButtons = function() {
      var _this = this;
      try {
        return twttr.widgets.load();
      } catch (error) {
        this.loadSdk();
        return setTimeout((function() {
          return _this.loadTwitterButtons();
        }), 3000);
      }
    };

    twitter.prototype.getTweetButton = function(options) {
      var params, tweetButton;
      if (!_.isUndefined(options.url)) {
        params = [];
        if (options.onComplete == null) {
          options.onComplete = function(button) {
            return sf.log([__private.moduleName(), 'getTweetButton:onComplete', button]);
          };
        }
        if (options.url == null) {
          options.url = "http://www.goanteup.com/!#/bet/{{betData.id}}";
        }
        if (options.text == null) {
          options.text = "Check this out on beatguide";
        }
        if (options.via == null) {
          options.via = "Beatguide";
        }
        if (options.related == null) {
          options.related = "";
        }
        if (options.count == null) {
          options.count = "horizontal";
        }
        if (options.lang == null) {
          options.lang = "en";
        }
        if (options.counturl == null) {
          options.counturl = "";
        }
        if (options.hashtags == null) {
          options.hashtags = "#Berlin";
        }
        if (options.size == null) {
          options.size = "";
        }
        sf.log([__private.moduleName(), "Tweet Button Options", options]);
        tweetButton = '<a href="https://twitter.com/share"\
                      class="twitter-share-button"\
                      data-url="' + options.url + '"\
                      data-text="' + options.text + '"\
                      data-via="' + options.via + '"\
                      data-related="' + options.related + '"\
                      data-count="' + options.count + '"\
                      data-lang="' + options.lang + '"\
                      data-counturl="' + options.counturl + '"\
                      data-hashtags="' + options.hashtags + '"\
                      data-size="' + options.size + '"\
                      ></a>';
        return options.onComplete(tweetButton);
      } else {
        return sf.logError([__private.moduleName(), 'getTweetButton', 'missing options.URL']);
      }
    };

    return twitter;

  })();
  return new twitter;
});
