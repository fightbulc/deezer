define(function(require){

  var $ = require('jquery');
  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  // ##########################################

  var trackPageView = abstractView.extend({
    el: '#trackPage',

    // ----------------------------

    initialize: function(){
      this.$player = $('#deezerPlayer');
    },

    // ----------------------------

    render: function(trackId){

      var that = this;

    	DZ.promisePlayerOnLoad.done(function(){
  			DZ.player.playTracks([trackId], 0, function(response){
          var track = response.tracks[0].title;
          var artist = response.tracks[0].artist.name;

          that.$el.html(template.render({
            track: track,
            artist: artist
          }));

          that.$el.show();
          that.$player.addClass('active');
  			});
    	});
    },

    // ----------------------------

    stop: function(){
      this.$player.removeClass('active');

      DZ.promisePlayerOnLoad.done(function(){
        DZ.player.pause();
      });
    }
  });

  return trackPageView;

});