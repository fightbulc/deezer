
define(function(require) {
  var abstractModel, modelVo, pubsub, sf, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractModel = require('abstractModel');
  modelVo = require('app/js/Vo/GenreVo');
  __private = {
    moduleName: function() {
      return 'model.genre';
    }
  };
  return abstractModel.extend({
    initialize: function() {
      return this.setVo(modelVo);
    }
  });
});
