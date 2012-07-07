
define(function(require) {
  var abstractVo;
  abstractVo = (function() {

    abstractVo.name = 'abstractVo';

    function abstractVo() {}

    abstractVo.prototype._data = {};

    abstractVo.prototype.setData = function(data) {
      return this._data = data;
    };

    abstractVo.prototype.getData = function() {
      return this._data;
    };

    abstractVo.prototype.getByKey = function(key) {
      var value;
      value = '';
      if (typeof this._data[key] !== 'undefined') {
        value = this._data[key];
      }
      return value;
    };

    abstractVo.prototype.setByKey = function(key, val) {
      return this._data[key] = val;
    };

    abstractVo.prototype["export"] = function(dto) {
      dto = new dto();
      return dto["export"](this);
    };

    return abstractVo;

  })();
  return abstractVo;
});
