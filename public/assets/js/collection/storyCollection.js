define(function(require){

  var abstractCollection = require('abstractCollection');
  var Data = require('js/data/data');

  var storyCollection = abstractCollection.extend({
    // search: function(trackId){
    //   alert('storyCollection search should not be called');
    //   var collection = this;

    //   Data.getStories(trackId).done(function(response){
    //     console.log(['getStories', response]);

    //     if(_.has(response, 'result')){
    //       collection.reset(response['result']);
    //     }
    //   });
    // }
  });

  return storyCollection;

});