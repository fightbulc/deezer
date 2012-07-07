define(function(require){

  var abstractCollection = require('abstractCollection');
  var Data = require('js/data/data');

  var memoryCollection = abstractCollection.extend({
    search: function(trackId, feeling){
      var collection = this;

      Data.getMemories(trackId, feeling).done(function(response){
        if(_.has(response, 'data')){
          collection.reset(response['data']);
        }
      });
    }
  });

  return memoryCollection;

});