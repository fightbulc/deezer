define(function(require){

  var $ = require('jquery');
  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');
  var base = require('base');

  var StoryCollection = require('js/collection/storyCollection');

  var storyCollection = new StoryCollection({});

  var template = hogan.compile(require('text!templates/tagPage.mustache'));
  var templateTagPageHeader = hogan.compile(require('text!templates/tagPageHeader.mustache'));
  var templateRelatedTracks = hogan.compile(require('text!templates/tracksRelatedToMood.mustache'));
  var templateRelatedMoods = hogan.compile(require('text!templates/moodsRelatedToTrack.mustache'));
  var templateStory = hogan.compile(require('text!templates/storyOnTrackPage.mustache'));
  // ##########################################

  var tagPageView = abstractView.extend({
    el: '#tagPage',

    events: {
      'click #AddStoryButton': 'eventClickAddStoryButton'
    },

    // ----------------------------

    initialize: function(){
      this.$player = $('#deezerPlayer');

      this._tag = null;

      storyCollection.on('add', this.renderStoriesAdd, this);
      storyCollection.on('remove', this.renderStoriesReset, this);
      storyCollection.on('reset', this.renderStoriesReset, this);
    },

    // ----------------------------

    render: function(tag){
      this._tag = tag;
      this._trackId = null;

      var that = this;

      that.$el.html(template.render({}));
      this.$el.show();

      that.$('#memoriesHeader').html(templateTagPageHeader.render({
        tag: tag
      }));

      Data.getWebCollectionsGetByMoodTag(tag).done(function(response){
        if(_.has(response, 'result')){
          console.log(['the tag is == '+tag, 'tag collection', response]);

          // random track

          var randomTrack = response['result']['randomTrack'];

          that._trackId = randomTrack['id'];

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

          // deezer player

          DZ.promisePlayerOnLoad.done(function(){
            DZ.player.playTracks([randomTrack['id']], 0, function(response){
              var track = randomTrack['trackTitle'];
              var artist = randomTrack['artistName'];

              that.$player.addClass('active');
            });
          });

        }
      });
    },

    // ----------------------------

    // refactor to trackPage
    renderStoriesAdd: function(model){
      var rendered = templateStory.render(model.toJSON());

      this.$('#TrackStories ul').prepend(rendered);
    },

    // ----------------------------

    // refactor to trackPage
    renderStoriesReset: function(){
      var view = this;

      view.$('#TrackStories ul').empty();

      storyCollection.each(function(storyModel){
        var rendered = templateStory.render(storyModel.toJSON());

        view.$('#TrackStories ul').prepend(rendered);
      })
    },

    // ----------------------------

    // refactor to trackPage
    eventClickAddStoryButton: function(){
      if(this._trackId !== null){
        var accessToken = base.get('userWidgetView').accessToken;
        var trackId = this._trackId;
        var story = this.$('textarea#TrackStory').val();

        if((trackId !== null) && (accessToken !== null)){
          Data.createStory(accessToken, trackId, story).done(function(response){
            storyCollection.add(response['result']);
          });
        }
      }
      else{
        alert('track id is still null');
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

  return tagPageView;

});