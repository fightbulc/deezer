define(function(require){

  var $ = require('jquery');
  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  var templateTrackPageHeader = hogan.compile(require('text!templates/trackPageHeader.mustache'));
  var templateRelatedTracks = hogan.compile(require('text!templates/tracksRelatedToMood.mustache'));
  var templateRelatedMoods = hogan.compile(require('text!templates/moodsRelatedToTrack.mustache'));
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

      that.$el.html(template.render({}));

    	DZ.promisePlayerOnLoad.done(function(){
  			DZ.player.playTracks([trackId], 0, function(response){
          var track = response.tracks[0].title;
          var artist = response.tracks[0].artist.name;

          $('#memoriesHeader', that.$el).html(templateTrackPageHeader.render({
            track: track,
            artist: artist
          }));

          that.$el.show();
          that.$player.addClass('active');
  			});
    	});

      Data.getMoodsByTrackId('234234').done(function(response){
        console.log(['getMoodsByTrackId', response]);

        var renderedRelatedMoods = templateRelatedMoods.render({
          'moods':response['result']
        });

        $('#RelatedMoods').html(renderedRelatedMoods);
      });

      Data.getTracksByMoodName('happy').done(function(response){
        console.log(['getTracksByMoodName', response]);

        var renderedRelatedTracks = templateRelatedTracks.render({
          'tracks':response['result']
        });

        $('#RelatedTracks').html(renderedRelatedTracks);
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