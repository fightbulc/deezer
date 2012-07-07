define(function(require){
  var _module = {};

  var _ = require('underscore');
  var $ = require('jquery');

  _module.deezer = function(parts, data){
    if(_.isUndefined(data)){
      data = {};
    }

    var url = 'http://api.deezer.com/'+parts.join('/');
    return $.ajax({
      url:url,
      dataType:'jsonp',
      data:_.extend({}, data, {output:'jsonp'})
    });
  };

  _module.api = function(method, parameters){
    return $.ajax({
      url:'/api/v1/web/',
      type:'POST',
      contentType:'application/json',
      dataType:'json',
      data: JSON.stringify({
        "id":_.uniqueId(),
        "method":method,
        "params":parameters
      })
    });
  };

  return _module;
});
