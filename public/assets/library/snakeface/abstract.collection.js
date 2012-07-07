var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

define(function(require) {
  var Backbone, _;
  _ = require('underscore');
  Backbone = require('backbone');
  return Backbone.Collection.extend({
    reverseSorting: false,
    isLast: function(model) {
      var isLast;
      isLast = false;
      if (_.isEqual(this.last(), model)) {
        isLast = true;
      }
      return isLast;
    },
    getRandom: function() {
      return _.shuffle(this.models).pop();
    },
    addOrUpdate: function(data) {
      var id, idAttribute, model;
      idAttribute = 'id';
      if (this.model !== void 0) {
        idAttribute = this.model.prototype.idAttribute;
      }
      id = data[idAttribute];
      model = this.get(id);
      if (model != null) {
        model.set(data);
      } else {
        this.add(data);
        model = this.get(id);
      }
      return model;
    },
    smartReset: function(data) {
      var idsToKeep, item, _i, _len,
        _this = this;
      idsToKeep = [];
      for (_i = 0, _len = data.length; _i < _len; _i++) {
        item = data[_i];
        idsToKeep.push(item.id);
        if (!this.get(item.id)) {
          this.add(item);
        }
      }
      return this.each(function(model) {
        var _ref;
        if (_ref = model.getVo().getId(), __indexOf.call(idsToKeep, _ref) < 0) {
          return _this.remove(model);
        }
      });
    },
    sortBy: function() {
      var models;
      models = _.sortBy(this.models, this.comparator);
      if (this.reverseSorting) {
        models.reverse();
      }
      return models;
    },
    next: function(model) {
      var i;
      i = this.indexOf(model);
      if (void 0 === i || i < 0 || i >= (this.length - 1)) {
        return false;
      }
      return this.at(i + 1);
    },
    prev: function(model) {
      var i;
      i = this.indexOf(model);
      if (void 0 === i || i <= 0) {
        return false;
      }
      return this.at(i - 1);
    }
  });
});
