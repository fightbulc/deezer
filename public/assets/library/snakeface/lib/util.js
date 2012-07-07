
define(function(require) {
  var $, momentjs, sf, _, __private, __public;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  momentjs = require('moment');
  __private = {
    moduleName: function() {
      return 'lib.Util';
    }
  };
  return __public = {
    imageLoadAsync: function(options) {
      var image;
      if (!(options.src != null) && options.target) {
        options.src = options.target.data('load-async');
        if (!(options.src != null)) {
          sf.logError([__private.moduleName(), 'imageLoadAsync', options, 'options.src is a mandatory option for imageLoadAsync!']);
        }
      }
      image = new Image();
      image.onload = function() {
        if (options.target) {
          options.target.attr('src', image.src);
          options.target.removeData('load-async');
        }
        if (typeof options.onSuccess === 'function') {
          return options.onSuccess(image.src);
        }
      };
      image.onerror = function() {
        if (options.target && options.errorSrc) {
          options.target.attr('src', options.errorSrc);
        }
        if (typeof options.onError === 'function') {
          return options.onError(image.src);
        }
      };
      return image.src = options.src;
    },
    lazyLoadViewport: function(viewportElm) {
      var _this = this;
      viewportElm.scroll(function() {
        var $img, $imgs, imageHeight, imageRelativeOffset, img, viewportHeight, viewportOffset, _i, _len, _results;
        viewportOffset = viewportElm.offset().top;
        viewportHeight = viewportElm.height();
        $imgs = viewportElm.find('img[data-load-async]');
        _results = [];
        for (_i = 0, _len = $imgs.length; _i < _len; _i++) {
          img = $imgs[_i];
          $img = $(img);
          imageRelativeOffset = $img.offset().top - viewportOffset;
          imageHeight = $img.height();
          if (!img._flaggedForLoading && (imageRelativeOffset + imageHeight) >= 0 && imageRelativeOffset <= viewportHeight) {
            img._flaggedForLoading = true;
            _results.push(_this.imageLoadAsync({
              target: $img
            }));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
      return viewportElm.scroll();
    },
    arrayShrink: function(array, size, random) {
      if (size == null) {
        size = 10;
      }
      if (random == null) {
        random = false;
      }
      if (random) {
        array = _.shuffle(array);
      }
      return _u(array).first(size);
    },
    getUrlRexExp: function() {
      return /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z0-9+&@#\/%=~_|])/igm;
    },
    getUrlsFromText: function(text) {
      return text.match(this.getUrlRexExp());
    },
    getNow: function() {
      var now;
      now = new Date(momentjs().format('YYYY-MM-DD') + 'T21:05:00');
      now = momentjs().local();
      return now;
    },
    getStartOfDay: function() {
      var now;
      now = momentjs().sod();
      return now;
    }
  };
});
