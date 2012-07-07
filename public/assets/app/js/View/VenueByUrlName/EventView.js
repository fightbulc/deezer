
define(function(require) {
  var abstractView, base, eventDto, pubsub, sf, template, venueDto, __private;
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractView = require('abstractView');
  template = require('template');
  base = require('base');
  template.addTemplate('VenuesEvent', require('text!app/tmpl/VenueByUrlName/Event.mustache'));
  eventDto = require('app/js/Dto/Event/EventEventsListingDto');
  venueDto = require('app/js/Dto/Venue/VenueEventsListingDto');
  __private = {
    moduleName: function() {
      return 'view.VenueEventsListing';
    }
  };
  return abstractView.extend({
    tagName: 'div',
    className: 'event',
    events: {
      'click': 'redirectToEvent'
    },
    initialize: function() {
      _.bindAll(this, 'remove', 'render');
      this.venuesCollection = base.getInstance('venuesCollection');
      this.model.on('change', this.render);
      return this.model.on('destroy', this.remove);
    },
    render: function() {
      var tmplData, venueModel, vo;
      vo = this.model.getVo();
      venueModel = this.venuesCollection.get(vo.getVenueUrlName());
      tmplData = {
        event: vo["export"](eventDto),
        venue: venueModel.getVo()["export"](venueDto)
      };
      this.$el.html(template.render('VenuesEvent', tmplData));
      return this;
    },
    redirectToEvent: function(e) {
      e.preventDefault();
      return pubsub.publish('router:redirect', 'event/' + this.model.getVo().getUrlName());
    }
  });
});
