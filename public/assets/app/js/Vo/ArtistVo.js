var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractVo, artistTracksVo, voDefiniton, __private;
  abstractVo = require('abstractVo');
  artistTracksVo = require('app/js/Vo/ArtistTracksVo');
  __private = {
    moduleName: function() {
      return 'vo.artist';
    }
  };
  voDefiniton = (function(_super) {

    __extends(voDefiniton, _super);

    voDefiniton.name = 'voDefiniton';

    function voDefiniton() {
      return voDefiniton.__super__.constructor.apply(this, arguments);
    }

    voDefiniton.prototype.getId = function() {
      return this.getByKey('xid');
    };

    voDefiniton.prototype.getName = function() {
      return this.getByKey('name');
    };

    voDefiniton.prototype.getUrlName = function() {
      return this.getByKey('id');
    };

    voDefiniton.prototype.getGenres = function() {
      return this.getByKey('genres');
    };

    voDefiniton.prototype.getUrlSoundcloud = function() {
      return this.getByKey('urlSoundcloud');
    };

    voDefiniton.prototype.getUrlFacebook = function() {
      return this.getByKey('urlFacebook');
    };

    voDefiniton.prototype.getUrlAvatar = function() {
      return this.getByKey('urlAvatar');
    };

    voDefiniton.prototype.getTracks = function() {
      return this.getByKey('tracks');
    };

    voDefiniton.prototype.getTracksVo = function() {
      var track, trackVo, tracksRaw, tracksVo, _i, _len;
      tracksRaw = this.getTracks();
      tracksVo = [];
      for (_i = 0, _len = tracksRaw.length; _i < _len; _i++) {
        track = tracksRaw[_i];
        trackVo = new artistTracksVo();
        trackVo.setData(track);
        tracksVo.push(trackVo);
      }
      return tracksVo;
    };

    return voDefiniton;

  })(abstractVo);
  return voDefiniton;
});
