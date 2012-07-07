
define(function(require) {
  var artistsCollection, base, eventsCollection, facebook, pubsub, router, sf, sidebarWidgetView, soundplayerView, soundsCollection, venuesCollection;
  sf = require('snakeface');
  pubsub = require('pubsub');
  facebook = require('facebook');
  base = require('base');
  router = require('app/js/Router/noAuthRouter');
  eventsCollection = require('app/js/Collection/EventsCollection');
  artistsCollection = require('app/js/Collection/ArtistsCollection');
  venuesCollection = require('app/js/Collection/VenuesCollection');
  sidebarWidgetView = require('app/js/View/SidebarWidget/PageView');
  soundsCollection = require('app/js/Collection/SoundsCollection');
  soundplayerView = require('app/js/View/Soundplayer/SoundplayerView');
  return function() {
    console.log(['###################################']);
    console.log(['Hi there! --> Loading app matrix...']);
    console.log(['###################################']);
    sf.pageStateLoading('matrix');
    base.getInitialData({
      cityUrlName: 'berlin'
    });
    pubsub.subscribe('facebook:ready', function(data) {
      return sf.log(['facebook is loaded']);
    });
    facebook.init();
    return pubsub.subscribe('base:initialData', function(data) {
      sf.log(['base:initialData', data]);
      base.setInstance('venuesCollection', venuesCollection);
      base.getInstance('venuesCollection').reset(data.venues);
      base.setInstance('eventsCollection', eventsCollection);
      base.getInstance('eventsCollection').reset(data.events);
      base.setInstance('artistsCollection', artistsCollection);
      base.setInstance('sidebarWidgetView', new sidebarWidgetView());
      base.getInstance('sidebarWidgetView').render();
      base.setInstance('soundsCollection', soundsCollection);
      base.setInstance('soundplayerView', new soundplayerView());
      return new router();
    });
  };
});
