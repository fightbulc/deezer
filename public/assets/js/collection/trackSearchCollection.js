define(function(require){

  var abstractCollection = require('abstractCollection');
  var Data = require('js/data/data');

  var trackSearchCollection = abstractCollection.extend({
    search: function(query){
      var collection = this;

      Data.deezerSearchTracks(query).done(function(response){
        if(_.has(response, 'data')){
          collection.reset(response['data']);
        }
      });
    }
  });

  return trackSearchCollection;

});