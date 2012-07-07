
define(function(require) {
  var abstractView, base, pubsub, sf, template, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  abstractView = require('abstractView');
  base = require('base');
  template.addTemplate('PageAbout', require('text!app/tmpl/About/Page.mustache'));
  __private = {
    moduleName: function() {
      return 'view.PageAbout';
    }
  };
  return abstractView.extend({
    el: '#pageAbout',
    initialize: function() {
      sf.log([__private.moduleName(), '>>>', 'init']);
      return _.bindAll(this, 'remove', 'render');
    },
    render: function() {
      this.$el.html(template.render('PageAbout'));
      return this;
    }
  });
});
