
define(function(require) {
  var abstractDto, _;
  _ = require('underscore');
  abstractDto = (function() {

    abstractDto.name = 'abstractDto';

    function abstractDto() {}

    abstractDto.prototype["export"] = function(vo) {
      var newData;
      newData = {};
      _.each(this.getObjects(), function(obj, key) {
        var value;
        value = vo[obj.vo]();
        if (!(value != null)) {
          value = obj["default"];
        } else if (_.isFunction(obj.format)) {
          value = obj.format(value);
        }
        return newData[key] = value;
      });
      return newData;
    };

    abstractDto.prototype.getObjects = function() {
      return {};
    };

    return abstractDto;

  })();
  return abstractDto;
});
