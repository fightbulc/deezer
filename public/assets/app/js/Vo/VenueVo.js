var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractVo, voDefiniton, __private;
  abstractVo = require('abstractVo');
  __private = {
    moduleName: function() {
      return 'vo.venue';
    }
  };
  voDefiniton = (function(_super) {

    __extends(voDefiniton, _super);

    voDefiniton.name = 'voDefiniton';

    function voDefiniton() {
      return voDefiniton.__super__.constructor.apply(this, arguments);
    }

    voDefiniton.prototype.getId = function() {
      return this.getByKey('id');
    };

    voDefiniton.prototype.getCityId = function() {
      return this.getByKey('cityId');
    };

    voDefiniton.prototype.getName = function() {
      return this.getByKey('name');
    };

    voDefiniton.prototype.getUrlName = function() {
      return this.getByKey('urlName');
    };

    voDefiniton.prototype.getAddress = function() {
      return this.getByKey('address');
    };

    voDefiniton.prototype.getDistrict = function() {
      return this.getByKey('district');
    };

    voDefiniton.prototype.getUrlWebsite = function() {
      return this.getByKey('urlWebsite');
    };

    voDefiniton.prototype.getLat = function() {
      return this.getByKey('lat');
    };

    voDefiniton.prototype.getLng = function() {
      return this.getByKey('lng');
    };

    voDefiniton.prototype.getDateUpdated = function() {
      return this.getByKey('updated');
    };

    return voDefiniton;

  })(abstractVo);
  return voDefiniton;
});
