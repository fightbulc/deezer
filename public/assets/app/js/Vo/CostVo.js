var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var abstractVo, voDefiniton, __private;
  abstractVo = require('abstractVo');
  __private = {
    moduleName: function() {
      return 'vo.cost';
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

    voDefiniton.prototype.getName = function() {
      return this.getByKey('name');
    };

    return voDefiniton;

  })(abstractVo);
  return voDefiniton;
});
