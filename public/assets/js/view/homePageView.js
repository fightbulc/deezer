define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');
  var base = require('base')

  var TrackSearchCollection = require('js/collection/trackSearchCollection');

  var template = hogan.compile(require('text!templates/homePage.mustache'));

  var templateSearchResults = hogan.compile(require('text!templates/trackSearchResults.mustache'));
  var templateMoodResults = hogan.compile(require('text!templates/moodSearchResults.mustache'));

  var bubbleView = require('js/view/bubbleView');

  // ##########################################

  var trackSearchCollection = new TrackSearchCollection({});

  var homePageView = abstractView.extend({
    el: '#homePage',

    // ----------------------------

    events: {
      'focus #QueryTrack': 'eventTrackInputChange',
      'keyup #QueryTrack': 'eventTrackInputChange',
      'blur #QueryTrack': 'eventTrackInputBlur',

      'click #QueryTrackResults li': 'selectTrack',

      'focus #QueryFeeling': 'showMoods',
      'blur #QueryFeeling': 'hideMoods',

      'click #QueryFeelingResults li': 'selectMood',

      'click .btn-go': 'saveMood'
    },

    // ----------------------------

    initialize: function(){
      trackSearchCollection.on('add', this.renderSearchResults, this);
      trackSearchCollection.on('remove', this.renderSearchResults, this);
      trackSearchCollection.on('reset', this.renderSearchResults, this);

      this.eventTrackInputChange = _.debounce(this.eventTrackInputChange, 500);
      //this.eventFeelingInputChange = _.debounce(this.eventFeelingInputChange, 500);
    },

    // ----------------------------

    eventTrackInputChange: function(){

      this.selectedTrack = 0;
      var query = this.$('#QueryTrack').val();

      console.log(['eventTrackInputChange', query]);

      trackSearchCollection.search(query);

    },

    // ----------------------------

    eventFeelingInputChange: function(){

      var inputValue = this.$('#QueryFeeling').val();
      console.log(['eventTrackInputChange', inputValue]);

    },

    // ----------------------------

    eventTrackInputBlur: function(){
      var that = this;
      setTimeout(function(){
        that.$('#QueryTrackResults').stop().hide();
        if(that.selectedTrack === 0){
          console.log(['no track selected', that.$('#QueryTrackResults li').first()]);
          that.$('#QueryTrackResults li').first().click();
        }
      }, 300);
    },

    // ----------------------------

    render: function(){
      this.$el.html(template.render());
      this.$el.show();

      this.selectedTrack = 0;
      this.renderedMoods = false;

      return this;
    },

    // ----------------------------

    renderSearchResults: function(){

      var results = trackSearchCollection.toJSON();
      if(results.length){

        this.$('#QueryTrackResults').html(templateSearchResults.render({
          'tracks':trackSearchCollection.toJSON()
        })).css(this.getFieldPosition('QueryTrack')).fadeIn().show();

      }else{
        this.$('#QueryTrackResults').hide();
      }

      return this;
    },

    // ----------------------------

    selectTrack: function(e){
      var $item = $(e.currentTarget);
      this.selectedTrack = Number($item.data('track-id'));
      this.selectedTrackName = $item.data('track-name');
      this.selectedTrackArtist = $item.data('track-artist');
      this.$('#QueryTrack').val($item.data('track-artist')+' - '+$item.data('track-name'));
      this.getMoods(this.selectedTrack);
    },

    // ----------------------------

    getMoods: function(trackId){

      var that = this;

      Data.getMoodsByTrackId(trackId).done(function(response){
        if(response.result.length){
          that.renderedMoods = true;
          that.$('#QueryFeeling').focus();
          that.$('#QueryFeelingResults').html(templateMoodResults.render(response)).css(that.getFieldPosition('QueryFeeling')).fadeIn().show();
        }else{
          that.renderedMoods = false;
        }
      });
    },

    // ----------------------------

    selectMood: function(e){
      var $item = $(e.currentTarget);
      this.$('#QueryFeeling').val($item.data('mood'));
    },

    // ----------------------------

    getFieldPosition: function(id){
      var offset = this.$('#'+id).offset();
      var pageOffset = this.$el.offset();

      return {
        left: offset.left - pageOffset.left,
        top: offset.top - pageOffset.top + 30
      };
    },

    // ----------------------------

    renderBubbles: function(){
      var i, view;
      for(i=0; i<100; i+=1){
        view = new bubbleView;

        this.$('.bubbles').append(view.render().el);
      }
    },

    // ----------------------------

    showMoods: function(){
      if(this.renderedMoods){
        this.$('#QueryFeelingResults').stop().fadeIn().show();
      }
    },

    // ----------------------------

    hideMoods: function(){
      var that = this;

      setTimeout(function(){
        that.$('#QueryFeelingResults').hide();
      }, 300);
    },

    // ----------------------------

    saveMood: function(){
      var userWidgetView = base.get('userWidgetView');
      var that = this;
      if(userWidgetView.accessToken === null){
        userWidgetView.login();
      }else{
        Data.api('Web.Memories.createMemory', [{
          'accessToken': userWidgetView.accessToken,
          'moodTag': $('#QueryFeeling').val(),
          'trackId': this.selectedTrack,
          'artistName': this.selectedTrackArtist,
          'trackTitle': this.selectedTrackName
        }]).done(function(){
          base.get('router').redirect('track/'+that.selectedTrack);
        });
      }
    }
  });

  return homePageView;

});