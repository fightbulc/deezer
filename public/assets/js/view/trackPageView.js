define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  // ##########################################

  var trackPageView = abstractView.extend({
    el: '#trackPage',

    // ----------------------------

    render: function(){
      console.log('routed to track page');

      this.$el.html(template.render());

      return this;
    },

    playTrack: function(trackId){
    	DZ.promisePlayerOnLoad.done(function(){    		
			DZ.player.playTracks([trackId], 0, function(response){
				console.log("track list", response.tracks);
			});
    	});
    }
  });

  return trackPageView;

});