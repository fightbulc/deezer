define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');
  var Data = require('js/data/data');

  var TrackSearchCollection = require('js/collection/trackSearchCollection');

  var template = hogan.compile(require('text!templates/homePage.mustache'));

  var templateSearchResults = hogan.compile(require('text!templates/trackSearchResults.mustache'));

  var bubbleView = require('js/view/bubbleView');

  // ##########################################

  var trackSearchCollection = new TrackSearchCollection({});

  var homePageView = abstractView.extend({
    el: '#homePage',

    events: {
      'keyup #QueryTrack': 'eventTrackInputChange',
      'change #QueryTrack': 'eventTrackInputChange',

      'keyup #QueryFeeling': 'eventFeelingInputChange',
      'change #QueryFeeling': 'eventFeelingInputChange'
    },

    initialize: function(){
      trackSearchCollection.on('add', this.renderSearchResults, this);
      trackSearchCollection.on('remove', this.renderSearchResults, this);
      trackSearchCollection.on('reset', this.renderSearchResults, this);

      this.eventTrackInputChange = _.debounce(this.eventTrackInputChange, 500);
      this.eventFeelingInputChange = _.debounce(this.eventFeelingInputChange, 500);
    },

    eventTrackInputChange: function(){

      var query = this.$('#QueryTrack').val();

      console.log(['eventTrackInputChange', query]);

      trackSearchCollection.search(query);

    },

    eventFeelingInputChange: function(){

      var inputValue = this.$('#QueryFeeling').val();

      console.log(['eventTrackInputChange', inputValue]);

    },

    // ----------------------------

    render: function(){
      this.$el.html(template.render());

      //this.renderBubbles();

      return this;
    },

    renderSearchResults: function(){

      this.$('#QueryTrackResults').html(templateSearchResults.render({
        'tracks':trackSearchCollection.toJSON()
      }));

      return this;
    },

    // ----------------------------

    renderBubbles: function(){
      var i, view;
      for(i=0; i<100; i+=1){
        view = new bubbleView;

        this.$('.bubbles').append(view.render().el);
      }
    },
  });

  return homePageView;

});