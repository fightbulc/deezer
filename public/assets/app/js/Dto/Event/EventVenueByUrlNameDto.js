var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractDto, dtoDefinition;
  abstractDto = require('abstractDto');
  dtoDefinition = (function(_super) {

    __extends(dtoDefinition, _super);

    dtoDefinition.name = 'dtoDefinition';

    function dtoDefinition() {
      return dtoDefinition.__super__.constructor.apply(this, arguments);
    }

    dtoDefinition.prototype.getObjects = function() {
      var data;
      return data = {
        id: {
          vo: 'getId',
          "default": null
        },
        name: {
          vo: 'getName',
          "default": null
        },
        genres: {
          vo: 'getGenres',
          "default": null,
          format: function(data) {
            return data.split(',').sort().join(', ');
          }
        },
        dateStart: {
          vo: 'getDateStart',
          "default": null
        },
        dateEnd: {
          vo: 'getDateEnd',
          "default": null
        },
        cost: {
          vo: 'getCostDoor',
          "default": 'FREE',
          format: function(cost) {
            if (/\.00/.test(cost)) {
              cost = cost.replace('.00', '');
            }
            return "&euro; " + cost;
          }
        },
        isFinished: {
          vo: 'getIsFinished',
          "default": null
        },
        isUpcoming: {
          vo: 'getIsUpcoming',
          "default": null
        },
        isRunning: {
          vo: 'getIsRunning',
          "default": null
        }
      };
    };

    return dtoDefinition;

  })(abstractDto);
  return dtoDefinition;
});
