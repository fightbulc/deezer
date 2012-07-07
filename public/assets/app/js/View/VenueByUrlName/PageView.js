
define(function(require) {
  var $, abstractView, base, eventDto, eventView, facebook, instagram, momentjs, pubsub, sexytime, sf, template, twitter, util, venueDto, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  util = require('util');
  pubsub = require('pubsub');
  template = require('template');
  facebook = require('facebook');
  twitter = require('twitter');
  instagram = require('instagram');
  abstractView = require('abstractView');
  momentjs = require('moment');
  sexytime = require('vendor/momentjs/sexytime');
  base = require('base');
  eventView = require('app/js/View/VenueByUrlName/EventView');
  template.addTemplate('PageVenueByUrlName', require('text!app/tmpl/VenueByUrlName/Page.mustache'));
  template.addTemplate('singleImage', require('text!app/tmpl/VenueByUrlName/Image.mustache'));
  eventDto = require('app/js/Dto/Event/EventVenueByUrlNameDto');
  venueDto = require('app/js/Dto/Venue/VenueVenueByUrlNameDto');
  __private = {
    moduleName: function() {
      return 'view.PageVenueByUrlName';
    }
  };
  return abstractView.extend({
    el: '#pageVenueByUrlName',
    events: {
      'click .picsBtn': 'getPictures',
      'click .eventsBtn': 'showEvents'
    },
    initialize: function() {
      sf.log([__private.moduleName(), '>>>', 'init']);
      _.bindAll(this, 'remove', 'render');
      this.bind("change", 'subscribeToInstagramResponse');
      this.model.on('destroy', this.remove);
      this.venuesCollection = base.getInstance('venuesCollection');
      return this.eventsCollection = base.getInstance('eventsCollection');
    },
    render: function() {
      var nextModel, prevModel, tmplData, vo,
        _this = this;
      sf.log([__private.moduleName(), '>>>', 'render', this.model]);
      pubsub.subscribe("EventsCollection:requestedEventByVenueUrlName:" + (this.model.getVo().getUrlName()), function(eventModels) {
        return _this.renderEvents(eventModels);
      });
      vo = this.model.getVo();
      this.uniqueName = vo.getUrlName();
      nextModel = this.venuesCollection.next(this.model);
      prevModel = this.venuesCollection.prev(this.model);
      tmplData = {
        nextUrlName: nextModel ? nextModel.getVo().getUrlName() : void 0,
        prevUrlName: prevModel ? prevModel.getVo().getUrlName() : void 0,
        venue: this.model.getVo()["export"](venueDto)
      };
      this.$el.html(template.render('PageVenueByUrlName', tmplData));
      this.getEvents();
      this.bind('pageReady', function() {
        return _this.$el.sexytime();
      });
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
        text: "Listen to the upcoming events at -> " + vo.getName(),
        onComplete: function(button) {
          return _this.$el.find('.tweet').html(button);
        }
      });
      twitter.loadTwitterButtons();
      return this;
    },
    getEvents: function() {
      return this.eventsCollection.getByVenueUrlName(this.model.getVo().getUrlName());
    },
    renderEvents: function(eventModels) {
      var event, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = eventModels.length; _i < _len; _i++) {
        event = eventModels[_i];
        _results.push(this.renderOne(event, eventModels));
      }
      return _results;
    },
    renderOne: function(model, eventModels) {
      var div, vo;
      vo = model.getVo();
      if (vo.getIsFinished()) {
        div = "#finished";
        this.injectEvent(model, div);
        this.$el.find(div).find(".header").show();
      }
      if (vo.getIsRunning() || vo.getIsUpcoming()) {
        div = "#upcoming";
        this.injectEvent(model, div);
        this.$el.find(div).find(".header").show();
      }
      if (_.isEqual(_.last(eventModels), model)) {
        pubsub.publish('pageVenueByUrlNameView:ready');
        return this.trigger('pageReady');
      }
    },
    injectEvent: function(model, div) {
      var container, view;
      container = "#eventsContainer " + div + " .eventGroup";
      view = new eventView({
        model: model
      });
      if (div === "#upcoming") {
        return this.$el.find(container).append(view.render().el);
      } else {
        return this.$el.find(container).prepend(view.render().el);
      }
    },
    showEvents: function() {
      this.$el.find("#picsContainer").hide();
      return this.$el.find("#eventsContainer").show();
    },
    getPictures: function() {
      var picArray;
      sf.log(["pics btn clicked"]);
      this.$el.find("#eventsContainer").hide();
      this.$el.find("#picsContainer").append('<div class="spinner"></div>');
      this.$el.find("#picsContainer").show();
      this.subscribeToInstagramResponse();
      if (base.hasInstance('instagramPics-' + this.uniqueName) === false) {
        this.$el.find(".pics").empty();
        return this.getPicturesByTerm();
      } else {
        picArray = base.getInstance('instagramPics-' + this.uniqueName);
        this.$el.find(".spinner").remove();
        return this.$el.find(".pics").show();
      }
    },
    subscribeToInstagramResponse: function() {
      var token,
        _this = this;
      return token = pubsub.subscribe('instagram:gotPics', function(picArray) {
        base.setInstance('instagramPics-' + _this.uniqueName, picArray);
        _this.displayPictures(picArray);
        return pubsub.unsubscribe(token);
      });
    },
    getPicturesByCoordinates: function() {
      var options;
      options = {
        lat: this.model.getVo().getLat(),
        lng: this.model.getVo().getLng()
      };
      return instagram.getImagesByCoordinates(options);
    },
    getPicturesByTerm: function() {
      var term;
      term = this.model.getVo().getUrlName();
      return instagram.getImagesByTerm(term);
    },
    displayPictures: function(pics) {
      var _this = this;
      sf.log([__private.moduleName(), 'displayPictures', pics]);
      if (pics.length) {
        return $.each(pics, function(i, v) {
          var tmplData;
          console.log(v);
          tmplData = {
            thumbUrl: v.images.thumbnail.url,
            lowUrl: v.images.low_resolution.url,
            standardUrl: v.images.standard_resolution.url,
            date: momentjs().unix(parseInt(v.created_time)).fromNow(),
            caption: v.caption,
            comments: v.comments
          };
          _this.$el.find(".pics").append(template.render('singleImage', tmplData));
          _this.$el.find(".spinner").remove();
          _this.$el.find(".pics").show();
          return _this.addLightbox();
        });
      } else {
        this.$el.find(".spinner").remove();
        return this.$el.find(".empty").show();
      }
    },
    addLightbox: function() {
      return sf.log(["place to add lightbox"]);
    }
  });
});
