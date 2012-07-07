
define(function(require) {
  var PageAboutView, PageEventByUrlNameView, PageEventsListingView, PageMapView, PageVenueByUrlNameView, PageVenuesListingView, abstractRouter, base, pubsub, sf, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractRouter = require('abstractRouter');
  base = require('base');
  PageEventsListingView = require('app/js/View/EventsListing/PageView');
  PageEventByUrlNameView = require('app/js/View/EventByUrlName/PageView');
  PageVenuesListingView = require('app/js/View/VenuesListing/PageView');
  PageVenueByUrlNameView = require('app/js/View/VenueByUrlName/PageView');
  PageMapView = require('app/js/View/Map/PageView');
  PageAboutView = require('app/js/View/About/PageView');
  __private = {
    moduleName: function() {
      return 'noAuthRouter';
    }
  };
  return abstractRouter.extend({
    routes: {
      "!/events/*": "eventsListing",
      "!/event/:urlName/*": "eventByUrlName",
      "!/venues/*": "venuesListing",
      "!/venue/:urlName/*": "venueByUrlName",
      "!/map/*": "map",
      "!/map/:venueName/*": "map",
      "!/about/*": "about",
      "*default": "eventsListing"
    },
    eventsListing: function() {
      sf.log([__private.moduleName(), 'eventsListing']);
      sf.pageStateLoading(__private.moduleName() + '/eventsListing');
      sf.hideAllPages();
      if (base.hasInstance('pageEventsListingView') === false) {
        base.setInstance('pageEventsListingView', new PageEventsListingView());
        base.getInstance('pageEventsListingView').render();
      }
      sf.showPage('pageEventsListing');
      return sf.pageStateLoaded(__private.moduleName() + '/eventsListing');
    },
    eventByUrlName: function(urlName) {
      var token,
        _this = this;
      sf.log([__private.moduleName(), 'eventByUrlName', urlName]);
      sf.pageStateLoading(__private.moduleName() + '/eventByUrlName');
      token = pubsub.subscribe("EventsCollection:requestedEvent:" + urlName, function(eventModel) {
        var view;
        if (base.hasInstance('pageEventByUrlNameView')) {
          view = base.getInstance('pageEventByUrlNameView');
          view.model = eventModel;
        } else {
          base.setInstance('pageEventByUrlNameView', new PageEventByUrlNameView({
            model: eventModel
          }));
          view = base.getInstance('pageEventByUrlNameView');
        }
        view.render();
        sf.switchPage('pageEventByUrlName');
        sf.pageStateLoaded(__private.moduleName() + '/eventByUrlName');
        return pubsub.unsubscribe(token);
      });
      return base.getInstance('eventsCollection').getByUrlName(urlName);
    },
    venuesListing: function() {
      sf.log([__private.moduleName(), 'venuesListing']);
      sf.pageStateLoading(__private.moduleName() + '/venuesListing');
      sf.hideAllPages();
      if (base.hasInstance('pageVenuesListingView') === false) {
        base.setInstance('pageVenuesListingView', new PageVenuesListingView());
        base.getInstance('pageVenuesListingView').render();
      }
      sf.showPage('pageVenuesListing');
      return sf.pageStateLoaded(__private.moduleName() + '/venuesListing');
    },
    venueByUrlName: function(urlName) {
      var venueModel, view;
      sf.log([__private.moduleName(), 'venueByUrlName', urlName]);
      sf.pageStateLoading(__private.moduleName() + '/venueByUrlName');
      sf.hideAllPages();
      venueModel = base.getInstance('venuesCollection').get(urlName);
      if (base.hasInstance('pageVenueByUrlNameView')) {
        view = base.getInstance('pageVenueByUrlNameView');
        view.model = venueModel;
      } else {
        base.setInstance('pageVenueByUrlNameView', new PageVenueByUrlNameView({
          model: venueModel
        }));
        view = base.getInstance('pageVenueByUrlNameView');
      }
      view.render();
      sf.showPage('pageVenueByUrlName');
      return sf.pageStateLoaded(__private.moduleName() + '/venueByUrlName');
    },
    map: function(venueId) {
      sf.log([__private.moduleName(), 'Map', venueId]);
      sf.pageStateLoading(__private.moduleName() + '/map');
      sf.hideAllPages();
      if (base.hasInstance('pageMap') === false) {
        sf.log(['NEW MAP PAGE INSTANCE']);
        base.setInstance('pageMap', new PageMapView());
      }
      if (venueId != null) {
        base.getInstance('pageMap').render(venueId);
        sf.log(["router", venueId]);
      } else {
        base.getInstance('pageMap').render();
      }
      sf.showPage('pageMap');
      return sf.pageStateLoaded(__private.moduleName() + '/map');
    },
    about: function() {
      sf.log([__private.moduleName(), 'About']);
      sf.pageStateLoading(__private.moduleName() + '/about');
      sf.hideAllPages();
      if (base.hasInstance('pageAbout') === false) {
        base.setInstance('pageAbout', new PageAboutView());
      }
      base.getInstance('pageAbout').render();
      sf.showPage('pageAbout');
      return sf.pageStateLoaded(__private.moduleName() + '/about');
    },
    "default": function() {
      return sf.log([__private.moduleName(), 'default']);
    }
  });
});
