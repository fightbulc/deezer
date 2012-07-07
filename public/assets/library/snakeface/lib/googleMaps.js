
define(function(require) {
  var $, pubsub, sf, template, _, __private, __public;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  template = require('template');
  template.addTemplate('InfoWindow', require('text!app/tmpl/Map/InfoWindow.mustache'));
  __private = {
    moduleName: function() {
      return 'lib.GoogleMaps';
    }
  };
  return __public = {
    init: function() {
      sf.log([__private.moduleName(), 'init']);
      return this.loadSDK();
    },
    loadSDK: function() {
      var _this = this;
      sf.log([__private.moduleName(), 'loadSDK']);
      return require(['async!http://maps.google.com/maps/api/js?sensor=false'], function() {
        return pubsub.publish('googleMaps:ready');
      });
    },
    addMapToCanvas: function(ele) {
      var map;
      sf.log([__private.moduleName(), 'addMapToCanvas', ele]);
      map = new google.maps.Map($(ele)[0]);
      return map;
    },
    addOptionsToMap: function(map, options) {
      var mapOptions;
      sf.log([__private.moduleName(), 'addOptionsToMap', map, options]);
      mapOptions = {
        center: new google.maps.LatLng(options.lat, options.lng),
        zoom: options.zoom,
        mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
          mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'beatguideMap'],
          position: google.maps.ControlPosition.TOP_RIGHT
        },
        panControl: true,
        panControlOptions: {
          position: google.maps.ControlPosition.TOP_RIGHT
        },
        zoomControl: true,
        zoomControlOptions: {
          style: google.maps.ZoomControlStyle.LARGE,
          position: google.maps.ControlPosition.TOP_RIGHT
        },
        scaleControl: true,
        scaleControlOptions: {
          position: google.maps.ControlPosition.BOTTOM_RIGHT
        },
        streetViewControl: true,
        streetViewControlOptions: {
          position: google.maps.ControlPosition.TOP_RIGHT
        }
      };
      map.setOptions(mapOptions);
      return this.addInfoWindowToMap();
    },
    addCustomStyleToMap: function(map, options) {
      var customStyle;
      sf.log([__private.moduleName(), 'addCustomStyleToMap', map, options]);
      customStyle = new google.maps.StyledMapType(options, {
        name: "Beatguide"
      });
      map.mapTypes.set('beatguideMap', customStyle);
      return map.setMapTypeId('beatguideMap');
    },
    zoomMapTo: function(map, level) {
      sf.log([__private.moduleName(), 'zoomMapTo', level]);
      return map.setZoom(level);
    },
    recenterMap: function(map, lat, lng) {
      return map.panTo(new google.maps.LatLng(lat, lng));
    },
    addOneMarkerToMap: function(map, obj) {
      var marker;
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(obj.lat, obj.lng),
        title: obj.name,
        clickable: true,
        map: map
      });
      return marker;
    },
    hideAllMarkers: function(markers) {
      var marker, markerId, _results;
      sf.log([__private.moduleName(), 'hideAllMarkers', markers]);
      _results = [];
      for (markerId in markers) {
        marker = markers[markerId];
        _results.push(this.hideMarker(marker));
      }
      return _results;
    },
    showAllMarkers: function(markers) {
      var marker, markerId, _results;
      sf.log([__private.moduleName(), 'showAllMarkers', markers]);
      _results = [];
      for (markerId in markers) {
        marker = markers[markerId];
        _results.push(this.showMarker(marker));
      }
      return _results;
    },
    hideMarker: function(marker) {
      return marker.setVisible(false);
    },
    showMarker: function(marker) {
      return marker.setVisible(true);
    },
    addInfoWindowToMap: function() {
      return this.infoWindow = new google.maps.InfoWindow;
    },
    addInfoWindowToMarker: function(map, dto, marker) {
      var _this = this;
      return google.maps.event.addListener(marker, 'click', function() {
        return _this.openInfoWindow(map, marker, dto);
      });
    },
    openInfoWindow: function(map, marker, dto) {
      var tmplData;
      tmplData = {
        venue: dto
      };
      this.infoWindow.setContent(template.render('InfoWindow', tmplData));
      return this.infoWindow.open(map, marker);
    },
    closeInfoWindow: function() {
      return this.infoWindow.close();
    }
  };
});
