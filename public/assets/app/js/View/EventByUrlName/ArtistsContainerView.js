
define(function(require) {
  var abstractView, artistView, base, pubsub, sf, template, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  abstractView = require('abstractView');
  base = require('base');
  artistView = require('app/js/View/EventByUrlName/ArtistView');
  template.addTemplate('ArtistsEventByUrlName', require('text!app/tmpl/EventByUrlName/ArtistsContainer.mustache'));
  __private = {
    moduleName: function() {
      return 'view.ArtistsContainerEventByUrlName';
    }
  };
  return abstractView.extend({
    initialize: function() {
      sf.log([__private.moduleName(), this.options]);
      _.bindAll(this, 'render', 'addAll', 'addOne');
      return this.artistsCollection = base.getInstance('artistsCollection');
    },
    render: function() {
      this.$el.html(template.render('ArtistsEventByUrlName'));
      this.addAll();
      return this;
    },
    addAll: function() {
      var artistUrlName, model, _i, _len, _ref, _results;
      _ref = this.options.artistUrlNames;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        artistUrlName = _ref[_i];
        model = this.artistsCollection.get(artistUrlName);
        _results.push(this.addOne(model));
      }
      return _results;
    },
    addOne: function(model) {
      var view;
      sf.log(['renderArtist', model]);
      view = new artistView({
        model: model,
        eventUrlName: this.options.eventUrlName
      });
      return this.$el.find('ul').append(view.render().el);
    }
  });
});
