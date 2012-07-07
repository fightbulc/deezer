define(function(require){
  var _module = {};

  var _ = require('underscore');
  var $ = require('jquery');

  var deezer = function(parts, data){
    if(_.isUndefined(data)){
      data = {};
    }

    var url = 'http://api.deezer.com/'+parts.join('/');
    return $.ajax({
      url:url,
      dataType:'jsonp',
      data:_.extend({}, data, {output:'jsonp'}),
      success:function(data){
        if(_.has(data, 'error')){
          console.log(['error!', data]);
        }
      }
    });
  };
  _module.deezer = deezer;

  return _module;
});
