
define(function(require) {
  var abstractView, base, pubsub, sf, template, venueDto, __private;
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractView = require('abstractView');
  template = require('template');
  base = require('base');
  template.addTemplate('VenuesVenueListing', require('text!app/tmpl/VenuesListing/Venue.mustache'));
  venueDto = require('app/js/Dto/Venue/VenueEventsListingDto');
  __private = {
    moduleName: function() {
      return 'view.VenueVenuesListing';
    }
  };
  return abstractView.extend({
    tagName: 'div',
    className: 'venue',
    initialize: function() {
      _.bindAll(this, 'remove', 'render');
      this.model.on('change', this.render);
      return this.model.on('destroy', this.remove);
    },
    render: function() {
      var tmplData;
      tmplData = {
        venue: this.model.getVo()["export"](venueDto)
      };
      this.$el.html(template.render('VenuesVenueListing', tmplData));
      return this;
    }
  });
});
