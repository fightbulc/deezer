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
      data:_.extend({}, data, {output:'jsonp'}),
      success:function(response){
        if(_.has(response, 'error')){
          console.log(['error!', response]);
        }
      }
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
      }),
      success:function(response){
        console.log(['api', response]);
      }
    });
  };

  return _module;
});
