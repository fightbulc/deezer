var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractVo, sf, util, voDefiniton, __private;
  sf = require('snakeface');
  util = require('util');
  abstractVo = require('abstractVo');
  __private = {
    moduleName: function() {
      return 'vo.event';
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

    voDefiniton.prototype.getCityId = function() {
      return this.getByKey('cityId');
    };

    voDefiniton.prototype.getName = function() {
      return this.getByKey('name');
    };

    voDefiniton.prototype.getUrlName = function() {
      return this.getByKey('id');
    };

    voDefiniton.prototype.getVenueUrlName = function() {
      return this.getByKey('venueUrlName');
    };

    voDefiniton.prototype.getDateStart = function() {
      return this.getByKey('dateStart');
    };

    voDefiniton.prototype.getDateEnd = function() {
      return this.getByKey('dateEnd');
    };

    voDefiniton.prototype.getGenres = function() {
      return this.getByKey('genres');
    };

    voDefiniton.prototype.getHasCosts = function() {
      return this.getByKey('hasCosts');
    };

    voDefiniton.prototype.getCostDoor = function() {
      return this.getByKey('costDoor');
    };

    voDefiniton.prototype.getArtistUrlNames = function() {
      return this.getByKey('artistUrlNames');
    };

    voDefiniton.prototype.getIsFinished = function() {
      var response;
      if (this._getHasFinished()) {
        response = true;
      } else {
        response = false;
      }
      return response;
    };

    voDefiniton.prototype.getIsUpcoming = function() {
      var response;
      if (!this._getHasStarted()) {
        response = true;
      } else {
        response = false;
      }
      return response;
    };

    voDefiniton.prototype.getIsRunning = function() {
      var response;
      if (this._getHasStarted() && !this._getHasFinished()) {
        response = true;
      } else {
        response = false;
      }
      return response;
    };

    voDefiniton.prototype._getHasStarted = function() {
      var now, response, start;
      start = new Date(this.getDateStart("dateStart"));
      now = util.getNow();
      response = false;
      if (start < now) {
        response = true;
      }
      return response;
    };

    voDefiniton.prototype._getHasFinished = function() {
      var end, now, response;
      end = new Date(this.getDateEnd("dateEnd"));
      now = util.getNow();
      response = false;
      if (end < now) {
        response = true;
      }
      return response;
    };

    return voDefiniton;

  })(abstractVo);
  return voDefiniton;
});
