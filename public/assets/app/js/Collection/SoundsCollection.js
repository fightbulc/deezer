var __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

define(function(require) {
  var Collection, abstractCollection, collectionModel;
  abstractCollection = require('abstractCollection');
  collectionModel = require('app/js/Model/SoundModel');
  Collection = (function(_super) {

    __extends(Collection, _super);

    Collection.name = 'Collection';

    function Collection() {
      return Collection.__super__.constructor.apply(this, arguments);
    }

    Collection.prototype.model = collectionModel;

    Collection.prototype.reverseSorting = true;

    Collection.prototype.comparator = function(model) {
      return model.getVo().getId();
    };

    return Collection;

  })(abstractCollection);
  return new Collection();
});
