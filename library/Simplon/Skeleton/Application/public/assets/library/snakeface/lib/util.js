
define(function(require) {
  var $, api, public, sf, _;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  public = {
    imageLoadAsync: function(imageUrlSource, imageTargetElm, imageError) {
      var image;
      image = new Image();
      image.onload = function() {
        return imageTargetElm.attr('src', image.src);
      };
      image.onerror = function() {
        if (imageError) {
          return image.src = imageError;
        } else {
          return imageTargetElm.remove();
        }
      };
      return image.src = imageUrlSource;
    },
    arrayShrink: function(array, size, random) {
      if (size == null) size = 10;
      if (random == null) random = false;
      if (random) array = _.shuffle(array);
      return _u(array).first(size);
    },
    getUrlsFromText: function(text) {
      var ytre;
      ytre = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z0-9+&@#\/%=~_|])/ig;
      return text.match(ytre);
    }
  };
  return api = {
    module: 'lib.Util',
    public: public
  };
});
