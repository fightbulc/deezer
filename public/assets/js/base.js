define(function(require){

  var _modules = {};

  return {
    has: function(key){
      return !!_modules[key];
    },
    get: function(key){
      return _modules[key];
    },
    set: function(key, value){
      _modules[key] = value;
    }
  };
});