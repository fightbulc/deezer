
define(function(require) {
  var $, abstractView, base, googleMaps, pubsub, sf, template, venueDto, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  googleMaps = require('googleMaps');
  abstractView = require('abstractView');
  base = require('base');
  template.addTemplate('PageMap', require('text!app/tmpl/Map/Page.mustache'));
  venueDto = require('app/js/Dto/Venue/VenueMapInfoWindowDto');
  __private = {
    moduleName: function() {
      return 'view.PageMap';
    }
  };
  return abstractView.extend({
    el: '#pageMap',
    mapOptions: {
      lat: "52.508594",
      lng: "13.426151",
      zoom: 13
    },
    customStyleOptions: [
      {
        featureType: "all",
        stylers: [
          {
            saturation: -80
          }
        ]
      }, {
        featureType: "poi.park",
        stylers: [
          {
            hue: "#ff0",
            saturation: 40
          }
        ]
      }, {
        featureType: "road",
        stylers: [
          {
            hue: "#00bbff",
            gamma: 1.4,
            saturation: -43,
            lightness: -18
          }
        ]
      }
    ],
    initialize: function() {
      var _this = this;
      sf.log([__private.moduleName(), '>>>', 'init']);
      this.collection = base.getInstance('venuesCollection');
      this.vID = null;
      this.markers = {};
      this.mapReady = false;
      pubsub.subscribe('googleMaps:ready', function() {
        _this.mapReady = true;
        return _this.render(_this.vID);
      });
      return googleMaps.init();
    },
    render: function(venueId) {
      var dto, lat, lng, model, vo,
        _this = this;
      sf.log([__private.moduleName(), '>>>', 'render', this.mapReady, venueId]);
      if (this.mapReady === true) {
        sf.log([__private.moduleName(), "Map is Ready"]);
        if (_.isUndefined(this.map)) {
          sf.log([__private.moduleName(), "Map is undefined"]);
          this.$el.html(template.render('PageMap'));
          this.map = googleMaps.addMapToCanvas("#map_canvas");
          googleMaps.addOptionsToMap(this.map, this.mapOptions);
          googleMaps.addCustomStyleToMap(this.map, this.customStyleOptions);
          this.collection.each(function(model) {
            return _this.addMarker(model);
          });
        } else {
          sf.log([__private.moduleName(), "Map is Defined", "closingInfoWindow"]);
          googleMaps.closeInfoWindow();
          googleMaps.zoomMapTo(this.map, this.mapOptions.zoom);
        }
        if (venueId != null) {
          sf.log([__private.moduleName(), "Show specific venue"]);
          model = this.collection.get(venueId);
          vo = model.getVo();
          dto = vo["export"](venueDto);
          lat = model.getVo().getLat();
          lng = model.getVo().getLng();
          googleMaps.hideAllMarkers(this.markers);
          googleMaps.recenterMap(this.map, lat, lng);
          googleMaps.showMarker(this.markers[venueId]);
          googleMaps.openInfoWindow(this.map, this.markers[venueId], dto);
        } else {
          sf.log([__private.moduleName(), "Show all venues"]);
          googleMaps.recenterMap(this.map, this.mapOptions.lat, this.mapOptions.lng);
          googleMaps.showAllMarkers(this.markers);
        }
        sf.log([__private.moduleName(), "Markers", this.markers]);
        return this;
      } else {
        return this.vID = venueId;
      }
    },
    addMarker: function(model) {
      var dto, marker, vo;
      vo = model.getVo();
      dto = vo["export"](venueDto);
      marker = googleMaps.addOneMarkerToMap(this.map, dto);
      googleMaps.addInfoWindowToMarker(this.map, dto, marker);
      return this.markers[vo.getId()] = marker;
    }
  });
});
