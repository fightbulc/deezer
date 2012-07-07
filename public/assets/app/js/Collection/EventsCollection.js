var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var Collection, abstractCollection, base, collectionModel, pubsub, sf;
  sf = require('snakeface');
  abstractCollection = require('abstractCollection');
  collectionModel = require('app/js/Model/EventModel');
  pubsub = require('pubsub');
  base = require('base');
  Collection = (function(_super) {

    __extends(Collection, _super);

    Collection.name = 'Collection';

    function Collection() {
      return Collection.__super__.constructor.apply(this, arguments);
    }

    Collection.prototype.model = collectionModel;

    Collection.prototype.comparator = function(model) {
      return model.getVo().getDateStart();
    };

    Collection.prototype.getByUrlName = function(urlName) {
      var model,
        _this = this;
      this.remove(urlName);
      model = this.get(urlName);
      if (!(model != null)) {
        return sf.jsonRequest({
          api: 'Web.Events.getByUrlName',
          params: {
            urlName: urlName
          },
          success: function(response) {
            var newModel;
            newModel = _this.add(response.event);
            sf.log(['FETCH', newModel, response.event]);
            _this.publishAddToArtistsCollection(response.artists);
            return _this.publishRequestedEvent(urlName);
          }
        });
      } else {
        return this.publishRequestedEvent(urlName);
      }
    };

    Collection.prototype.publishRequestedEvent = function(urlName) {
      return pubsub.publish("EventsCollection:requestedEvent:" + urlName, this.get(urlName));
    };

    Collection.prototype.publishAddToArtistsCollection = function(artists) {
      return pubsub.publish("ArtistsCollection:addToCollection", artists);
    };

    Collection.prototype.getByVenueUrlName = function(venueUrlName) {
      var eventsCollection,
        _this = this;
      eventsCollection = base.getInstance('eventsCollection');
      return sf.jsonRequest({
        api: 'Web.Events.getByVenueUrlName',
        params: {
          venueUrlName: venueUrlName
        },
        success: function(response) {
          var event, _i, _len;
          for (_i = 0, _len = response.length; _i < _len; _i++) {
            event = response[_i];
            eventsCollection.add(event);
          }
          return _this.publishRequestedEventsByVenueUrlName(venueUrlName);
        }
      });
    };

    Collection.prototype.publishRequestedEventsByVenueUrlName = function(venueUrlName) {
      var eventModels;
      eventModels = this.where({
        venueUrlName: venueUrlName
      });
      return pubsub.publish("EventsCollection:requestedEventByVenueUrlName:" + venueUrlName, eventModels);
    };

    return Collection;

  })(abstractCollection);
  return new Collection();
});
