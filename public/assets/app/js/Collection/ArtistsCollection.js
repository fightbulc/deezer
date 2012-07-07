var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var Collection, abstractCollection, artistsCollection, collectionModel, pubsub,
    _this = this;
  abstractCollection = require('abstractCollection');
  collectionModel = require('app/js/Model/ArtistModel');
  pubsub = require('pubsub');
  Collection = (function(_super) {

    __extends(Collection, _super);

    Collection.name = 'Collection';

    function Collection() {
      return Collection.__super__.constructor.apply(this, arguments);
    }

    Collection.prototype.model = collectionModel;

    Collection.prototype.comparator = function(model) {
      return model.getVo().getName();
    };

    Collection.prototype.getByUrlName = function(urlName) {
      var model,
        _this = this;
      model = this.get(urlName);
      model = null;
      if (!(model != null)) {
        return sf.jsonRequest({
          api: 'Web.Artists.getByUrlName',
          params: {
            urlName: urlName
          },
          success: function(response) {
            _this.add(response);
            return _this.publishRequestedArtist(urlName);
          }
        });
      } else {
        return this.publishRequestedArtist(urlName);
      }
    };

    Collection.prototype.publishRequestedArtist = function(urlName) {
      return pubsub.publish("ArtistsCollection:requestedArtist:" + urlName, this.get(urlName));
    };

    return Collection;

  })(abstractCollection);
  artistsCollection = new Collection();
  pubsub.subscribe('ArtistsCollection:addToCollection', function(artists) {
    var artist, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = artists.length; _i < _len; _i++) {
      artist = artists[_i];
      if (!artistsCollection.get(artist.id)) {
        _results.push(artistsCollection.add(artist));
      } else {
        _results.push(void 0);
      }
    }
    return _results;
  });
  return artistsCollection;
});
