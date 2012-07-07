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
      data:_.extend({}, data, {output:'jsonp'})
    });
  };
  _module.deezer = deezer;

  var api = function(method, parameters){
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
  _module.api = api;

  // --

  _module.deezerSearchTracks = function(query){
    return deezer(['2.0', 'search'], {'q':query});
  };

  _module.getMoodsByTrackId = function(trackId){
    return api('Web.Moods.getByTrackId', [{trackId:trackId}]);
  };

  _module.getTracksByMoodName = function(moodName){
    return api('Web.Tracks.getByMoodName', [{moodName:moodName}]);
  };

  _module.getMemories = function(trackId, mood){
    
  };

  return _module;
});
