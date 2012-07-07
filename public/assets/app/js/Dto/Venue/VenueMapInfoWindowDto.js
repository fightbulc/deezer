var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractDto, dtoDefinition, __private;
  abstractDto = require('abstractDto');
  __private = {
    moduleName: function() {
      return 'dto.venueMapInfoWindow';
    }
  };
  dtoDefinition = (function(_super) {

    __extends(dtoDefinition, _super);

    dtoDefinition.name = 'dtoDefinition';

    function dtoDefinition() {
      return dtoDefinition.__super__.constructor.apply(this, arguments);
    }

    dtoDefinition.prototype.getObjects = function() {
      var data;
      return data = {
        name: {
          vo: 'getName',
          "default": null
        },
        urlName: {
          vo: 'getUrlName',
          "default": null
        },
        address: {
          vo: 'getAddress',
          "default": null
        },
        district: {
          vo: 'getDistrict',
          "default": null
        },
        lat: {
          vo: 'getLat',
          "default": null
        },
        lng: {
          vo: 'getLng',
          "default": null
        }
      };
    };

    return dtoDefinition;

  })(abstractDto);
  return dtoDefinition;
});
