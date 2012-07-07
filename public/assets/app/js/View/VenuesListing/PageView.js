
define(function(require) {
  var $, abstractView, base, pubsub, sf, template, venueView, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  abstractView = require('abstractView');
  base = require('base');
  venueView = require('app/js/View/VenuesListing/VenueView');
  template.addTemplate('PageVenuesListing', require('text!app/tmpl/VenuesListing/Page.mustache'));
  __private = {
    moduleName: function() {
      return 'view.PageVenuesListing';
    }
  };
  return abstractView.extend({
    el: '#pageVenuesListing',
    events: {
      'click .alphabetLink': 'scrollToLetter'
    },
    initialize: function() {
      sf.log([__private.moduleName(), '>>>', 'init']);
      _.bindAll(this, 'render', 'addAll', 'addOne');
      this.collection = base.getInstance('venuesCollection');
      return this.collection.on('add', this.addOne);
    },
    render: function() {
      this.$el.html(template.render('PageVenuesListing'));
      this.addAll();
      return this;
    },
    addAll: function() {
      this.currentLetter = '';
      return this.collection.each(this.addOne);
    },
    addOne: function(model) {
      var firstLetter, firstLetterClass, firstLetterLabel, name, view;
      name = model.getVo().getName().toLowerCase();
      name = name.replace(/[^0-9a-zäöüÄÖÜ]/gi, '');
      firstLetter = name.charAt(0);
      console.log([firstLetter, name]);
      if (firstLetter > this.currentLetter) {
        if (firstLetter >= 0 && firstLetter <= 9) {
          firstLetterClass = 'num';
          firstLetterLabel = '#';
        } else {
          firstLetterClass = firstLetter;
          firstLetterLabel = firstLetter;
        }
        this.$el.find('ul.alphabet').append('<li class="alphabetLink" data-container=' + firstLetterClass + '>' + firstLetterLabel + '</li>');
        this.$el.find('div.venues').append('<div class="' + firstLetterClass + '"></div>');
        this.currentLetter = firstLetter;
      }
      view = new venueView({
        model: model
      });
      this.$el.find("div." + firstLetterClass).append(view.render().el);
      if (this.collection.isLast(model)) {
        return pubsub.publish('PageVenuesListingView:ready');
      }
    },
    scrollToLetter: function(e) {
      var container, containerName, link, pos_y;
      sf.log([__private.moduleName(), 'scrollToLetter', this.$el.find(e.target).text()]);
      this.$el.find(".alphabetLink").removeClass("active");
      link = this.$el.find(e.target);
      link.addClass('active');
      containerName = link.data("container");
      container = this.$el.find('div.' + containerName);
      pos_y = container.offset().top;
      return $('html, body').animate({
        scrollTop: pos_y
      }, "fast");
    }
  });
});
