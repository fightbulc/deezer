define(function(require){

  var $ = require('jquery');
  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');
  var base = require('base');

  var StoryCollection = require('js/collection/storyCollection');

  var storyCollection = new StoryCollection({});

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  var templateTrackPageHeader = hogan.compile(require('text!templates/trackPageHeader.mustache'));
  var templateRelatedTracks = hogan.compile(require('text!templates/tracksRelatedToMood.mustache'));
  var templateRelatedMoods = hogan.compile(require('text!templates/moodsRelatedToTrack.mustache'));
  var templateStory = hogan.compile(require('text!templates/storyOnTrackPage.mustache'));
  // ##########################################

  var trackPageView = abstractView.extend({
    el: '#trackPage',

    events: {
      'click #AddStoryButton': 'eventClickAddStoryButton'
    },

    // ----------------------------

    initialize: function(){
      this.$player = $('#deezerPlayer');

      this._trackId = null;

      storyCollection.on('add', this.renderStoriesAdd, this);
      storyCollection.on('remove', this.renderStoriesReset, this);
      storyCollection.on('reset', this.renderStoriesReset, this);

    },

    // ----------------------------

    render: function(trackId){
      this._trackId = trackId;

      var that = this;

      that.$el.html(template.render({}));
      this.$el.show();


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

      Data.getWebCollectionsGetByTrackId(trackId).done(function(response){
          console.log(['the track is == '+trackId, 'track collection', response]);

          // stories

          storyCollection.add(response['result']['stories']);

          // moods

          var renderedRelatedMoods = templateRelatedMoods.render({
            'moods':response['result']['moods']
          });

          that.$('#RelatedMoods').html(renderedRelatedMoods);

          // tracks
          
          var renderedRelatedTracks = templateRelatedTracks.render({
            'tracks':response['result']['tracks']
          });

          that.$('#RelatedTracks').html(renderedRelatedTracks);


      });

      Data.getMoodsByTrackId(trackId).done(function(response){
        console.log(['getMoodsByTrackId', response]);

        var renderedRelatedMoods = templateRelatedMoods.render({
          'moods':response['result']
        });

        that.$('#RelatedMoods').html(renderedRelatedMoods);
      });
      
    },

    renderStoriesAdd: function(model){
      var rendered = templateStory.render(model.toJSON());

      this.$('#TrackStories ul').prepend(rendered);
    },

    renderStoriesReset: function(){
      var view = this;

      view.$('#TrackStories ul').html(null);

      storyCollection.each(function(storyModel){
        var rendered = templateStory.render(storyModel.toJSON());

        view.$('#TrackStories ul').prepend(rendered);
      })
    },

    // ----------------------------

    eventClickAddStoryButton: function(){
      var accessToken = base.get('userWidgetView').accessToken;
      var trackId = this._trackId;
      var story = this.$('textarea#TrackStory').val();

      if((trackId !== null) && (accessToken !== null)){
        Data.createStory(accessToken, trackId, story).done(function(response){
          storyCollection.add(response['result']);
        });
      }
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