
define(function(require) {
  var Backbone, pubsub, sf, _;
  _ = require('underscore');
  Backbone = require('backbone');
  sf = require('snakeface');
  pubsub = require('pubsub');
  Backbone.Model.prototype.sync = function(method, model, options) {
    options.error = function(response, errorThrown, options) {
      return pubsub.publish({
        channel: 'model:error',
        data: [
          {
            error: errorThrown,
            response: response,
            options: options
          }
        ]
      });
    };
    return sf.jsonRequest(options);
  };
  return Backbone.Model.extend({
    setVo: function(vo) {
      var _this = this;
      this.vo = new vo();
      this.vo.setData(this.getData());
      return this.on('change', function() {
        return _this.vo.setData(_this.getData());
      });
    },
    getVo: function() {
      return this.vo;
    },
    getData: function() {
      return this.toJSON();
    },
    getByKey: function(key) {
      return this.get(key);
    },
    request: function(options) {
      return this.fetch(options);
    }
  });
});
