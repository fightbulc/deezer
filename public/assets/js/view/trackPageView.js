define(function(require){

  var $ = require('jquery');
  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');

  var StoryCollection = require('js/collection/storyCollection');

  var storyCollection = new StoryCollection({});
  GLOBAL_storyCollection = storyCollection;

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  var templateTrackPageHeader = hogan.compile(require('text!templates/trackPageHeader.mustache'));
  var templateRelatedTracks = hogan.compile(require('text!templates/tracksRelatedToMood.mustache'));
  var templateRelatedMoods = hogan.compile(require('text!templates/moodsRelatedToTrack.mustache'));
  var templateStories = hogan.compile(require('text!templates/storiesOnTrackPage.mustache'));
  // ##########################################

  var trackPageView = abstractView.extend({
    el: '#trackPage',

    events: {
      'click #AddStoryButton': 'eventClickAddStoryButton'
    },

    // ----------------------------

    initialize: function(){
      this.$player = $('#deezerPlayer');

      storyCollection.on('add', this.renderStories, this);
      storyCollection.on('remove', this.renderStories, this);
      storyCollection.on('reset', this.renderStories, this);

    },

    // ----------------------------

    render: function(trackId){

      var that = this;

      that.$el.html(template.render({}));

    	DZ.promisePlayerOnLoad.done(function(){
  			DZ.player.playTracks([trackId], 0, function(response){
          var track = response.tracks[0].title;
          var artist = response.tracks[0].artist.name;

          that.$('#memoriesHeader').html(templateTrackPageHeader.render({
            track: track,
            artist: artist
          }));

          that.$el.show();
          that.$player.addClass('active');
  			});
    	});

      Data.getMoodsByTrackId(trackId).done(function(response){
        console.log(['getMoodsByTrackId', response]);

        var renderedRelatedMoods = templateRelatedMoods.render({
          'moods':response['result']
        });

        that.$('#RelatedMoods').html(renderedRelatedMoods);
      });

      // Data.getTracksByMoodName('happy').done(function(response){
      //   console.log(['getTracksByMoodName', response]);

      //   var renderedRelatedTracks = templateRelatedTracks.render({
      //     'tracks':response['result']
      //   });

      //   $('#RelatedTracks').html(renderedRelatedTracks);
      // });

      storyCollection.search(trackId);

    },

    renderStories: function(){
        var renderedStories = templateStories.render({
          'stories':storyCollection.toJSON()
        });

        this.$('#TrackStories').html(renderedStories);
    },

    // ----------------------------

    eventClickAddStoryButton: function(){
      var story = this.$('textarea#TrackStory').val();

      Data.createStory({

      }).done(function(){

      });
      
      console.log(['eventClickAddStoryButton', story]);
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