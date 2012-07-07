
define(function(require) {
  var abstractCollection, abstractView, artistsContainerView, base, eventDto, facebook, momentjs, pubsub, sexytime, sf, template, twitter, venueDto, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  twitter = require('twitter');
  facebook = require('facebook');
  abstractView = require('abstractView');
  abstractCollection = require('abstractCollection');
  momentjs = require('moment');
  sexytime = require('vendor/momentjs/sexytime');
  base = require('base');
  artistsContainerView = require('app/js/View/EventByUrlName/ArtistsContainerView');
  template.addTemplate('PageEventByUrlName', require('text!app/tmpl/EventByUrlName/Page.mustache'));
  eventDto = require('app/js/Dto/Event/EventEventByUrlNameDto');
  venueDto = require('app/js/Dto/Venue/VenueEventByUrlNameDto');
  __private = {
    moduleName: function() {
      return 'view.PageEventByUrlName';
    }
  };
  return abstractView.extend({
    el: '#pageEventByUrlName',
    events: {
      'click .favBtn': 'favBtnClicked'
    },
    initialize: function() {
      sf.log([__private.moduleName(), '>>>', 'init', this.model]);
      _.bindAll(this, 'remove', 'render');
      this.venuesCollection = base.getInstance('venuesCollection');
      this.eventsCollection = base.getInstance('eventsCollection');
      return this.model.on('destroy', this.remove);
    },
    render: function() {
      var eventVo, nextModel, prevModel, tmplData, venueModel, venueVo,
        _this = this;
      venueModel = this.venuesCollection.get(this.model.getVo().getVenueUrlName());
      eventVo = this.model.getVo();
      venueVo = venueModel.getVo();
      nextModel = this.eventsCollection.next(this.model);
      prevModel = this.eventsCollection.prev(this.model);
      tmplData = {
        nextUrlName: nextModel ? nextModel.getVo().getUrlName() : void 0,
        prevUrlName: prevModel ? prevModel.getVo().getUrlName() : void 0,
        event: eventVo["export"](eventDto),
        venue: venueVo["export"](venueDto)
      };
      this.$el.html(template.render('PageEventByUrlName', tmplData));
      this.$el.sexytime();
      facebook.getLikeButton({
        href: window.location.href,
        action: 'like',
        colorscheme: 'dark',
        onComplete: function(button) {
          return _this.$el.find('.like').html(button);
        }
      });
      pubsub.publish('facebook:parsexfbml');
      twitter.getTweetButton({
        url: window.location.href,
        text: eventVo.getName() + " @ " + venueVo.getName(),
        onComplete: function(button) {
          return _this.$el.find('.tweet').html(button);
        }
      });
      twitter.loadTwitterButtons();
      this.renderArtists();
      return this;
    },
    renderArtists: function() {
      var view, vo;
      sf.log([__private.moduleName(), 'renderArtists', this.model.getVo().getArtistUrlNames(), this.model.attributes]);
      vo = this.model.getVo();
      view = new artistsContainerView({
        eventUrlName: vo.getUrlName(),
        artistUrlNames: vo.getArtistUrlNames()
      });
      return this.$el.find('#artistsContainer').html(view.render().el);
    },
    favBtnClicked: function() {
      return sf.log(["fav btn clicked"]);
    }
  });
});
