
define(function(require) {
  var abstractModel, modelVo, pubsub, sf, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractModel = require('abstractModel');
  modelVo = require('app/js/Vo/ArtistVo');
  __private = {
    moduleName: function() {
      return 'model.artist';
    }
  };
  return abstractModel.extend({
    initialize: function() {
      return this.setVo(modelVo);
    },
    getTracksData: function() {
      var tracks,
        _this = this;
      tracks = this.getVo().getTracks();
      if (_.isEmpty(tracks)) {
        return sf.jsonRequest({
          api: 'Web.Artists.getTracksDataByUrlName',
          params: {
            urlName: this.getVo().getUrlName()
          },
          success: function(response) {
            return _this.setTracksData(response);
          }
        });
      } else {
        return this.publishTracksData();
      }
    },
    setTracksData: function(tracks) {
      this.set({
        tracks: tracks
      });
      return this.publishTracksData();
    },
    publishTracksData: function() {
      return pubsub.publish('PageEventByUrlName:artistTracksDataReady:' + this.getVo().getId(), this.getVo().getTracks());
    }
  });
});
