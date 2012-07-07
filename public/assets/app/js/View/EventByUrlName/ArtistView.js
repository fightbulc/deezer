
define(function(require) {
  var abstractView, artistDto, base, pubsub, sf, template, _, __private;
  _ = require('underscore');
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractView = require('abstractView');
  template = require('template');
  base = require('base');
  template.addTemplate('ArtistEventByUrlName', require('text!app/tmpl/EventByUrlName/Artist.mustache'));
  artistDto = require('app/js/Dto/Artist/ArtistEventByUrlNameDto');
  __private = {
    moduleName: function() {
      return 'view.ArtistEventByUrlName';
    }
  };
  return abstractView.extend({
    tagName: 'li',
    className: 'artist clearfix',
    events: {
      'click': 'getTracksData'
    },
    initialize: function() {
      var _this = this;
      sf.log([__private.moduleName(), 'init', this.options]);
      _.bindAll(this, 'remove', 'render');
      this.model.on('change', this.render);
      this.model.on('destroy', this.remove);
      this.soundplayerView = base.getInstance('soundplayerView');
      return pubsub.subscribe('PageEventByUrlName:artistTracksDataReady:' + this.model.getVo().getId(), function() {
        return _this.loadSoundplayer();
      });
    },
    render: function() {
      var tmplData;
      tmplData = this.model.getVo()["export"](artistDto);
      this.$el.html(template.render('ArtistEventByUrlName', tmplData));
      return this;
    },
    getTracksData: function(e) {
      var $clickedElm, blockedElmClassNames, className, loadTracks, _i, _len;
      sf.log([__private.moduleName(), 'getTracksData', $(e.target)]);
      $clickedElm = $(e.target);
      loadTracks = true;
      blockedElmClassNames = ['artist-soundcloud', 'artist-facebook', 'artist-favouriteBtn'];
      for (_i = 0, _len = blockedElmClassNames.length; _i < _len; _i++) {
        className = blockedElmClassNames[_i];
        if ($clickedElm.hasClass(className)) {
          loadTracks = false;
        }
      }
      if (loadTracks) {
        e.preventDefault();
        e.stopPropagation();
        this.soundplayerView._hidePlayer();
        this.$el.addClass('showLoadingStripes');
        return this.model.getTracksData();
      }
    },
    loadSoundplayer: function(tracks) {
      var artistUrlName, eventUrlName;
      sf.log([__private.moduleName(), 'loadSoundplayer']);
      eventUrlName = this.options.eventUrlName;
      artistUrlName = this.model.getVo().getUrlName();
      return this.soundplayerView.setPlayer(eventUrlName, artistUrlName);
    }
  });
});
