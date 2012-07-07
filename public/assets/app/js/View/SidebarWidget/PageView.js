
define(function(require) {
  var $, abstractView, base, pubsub, sf, template, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  abstractView = require('abstractView');
  base = require('base');
  template.addTemplate('PageSidebarWidget', require('text!app/tmpl/SidebarWidget/Page.mustache'));
  __private = {
    moduleName: function() {
      return 'view.PageSidebarWidget';
    }
  };
  return abstractView.extend({
    el: '#sidebarWidget',
    events: {
      'focus #searchField': 'hideDefaultText',
      'blur #searchField': 'showDefaultText'
    },
    initialize: function() {
      return sf.log([__private.moduleName(), '>>>', 'init']);
    },
    render: function() {
      this.$el.html(template.render('PageSidebarWidget'));
      return this;
    },
    hideDefaultText: function(e) {
      sf.log([__private.moduleName(), 'hideDefaultText', e]);
      if (e.target.value === e.target.defaultValue) {
        return $(e.currentTarget).val("");
      }
    },
    showDefaultText: function(e) {
      sf.log([__private.moduleName(), 'showDefaultText', e]);
      if (e.target.value === e.target.defaultValue || e.target.value === "") {
        return $(e.currentTarget).val(e.target.defaultValue);
      }
    }
  });
});
