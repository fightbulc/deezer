
define(function(require) {
  var $, abstractView, base, pubsub, sf, template, __private;
  $ = require('jquery');
  sf = require('snakeface');
  pubsub = require('pubsub');
  abstractView = require('abstractView');
  template = require('template');
  base = require('base');
  template.addTemplate('PageSoundplayer', require('text!app/tmpl/Soundplayer/Player.mustache'));
  __private = {
    moduleName: function() {
      return 'view.soundplayer';
    }
  };
  return abstractView.extend({
    el: '#soundplayer',
    events: {
      'click .player .tracks li': 'playTrack',
      'click .player .play, .pause': 'playPauseToggle',
      'click .player .next': 'loadNextTrack',
      'click .player .prev': 'loadPrevTrack',
      'click .player .waveform, .player .title-bar': 'jumpToPos',
      'click .player .closePlayer': 'closePlayer',
      'click .eventUrl': 'goToEvent'
    },
    initialize: function() {
      sf.log([__private.moduleName(), 'init']);
      if (!(typeof soundManager !== "undefined" && soundManager !== null)) {
        throw new Error('SoundManager cannot be initialised! Try again. Danke');
      }
      this.eventModel = null;
      this.artistModel = null;
      this.currentTrackId = null;
      this.trackIsRunning = false;
      this.eventsCollection = base.getInstance('eventsCollection');
      this.artistsCollection = base.getInstance('artistsCollection');
      this.soundsCollection = base.getInstance('soundsCollection');
      return this.$el.html(template.render('PageSoundplayer'));
    },
    setPlayer: function(eventUrlName, artistUrlName) {
      this.currentTrackId = null;
      this.eventModel = this.eventsCollection.get(eventUrlName);
      sf.log(['EVENTDATA', this.eventModel]);
      this.artistModel = this.artistsCollection.get(artistUrlName);
      sf.log(['ARTISTDATA', this.artistModel]);
      this.soundsCollection.reset(this.artistModel.getVo().getTracks());
      sf.log(['SOUNDSCOLLECTION', this.soundsCollection.length]);
      return this.initPlayer();
    },
    initPlayer: function() {
      sf.log([__private.moduleName(), 'initPlayer']);
      return this.loadTrack(this.getNextTrack());
    },
    loadNextTrack: function() {
      sf.log([__private.moduleName(), 'loadNextTrack']);
      return this.loadTrack(this.getNextTrack());
    },
    loadPrevTrack: function() {
      sf.log([__private.moduleName(), 'loadPrevTrack']);
      return this.loadTrack(this.getPrevTrack());
    },
    playTrack: function() {
      if (this.isTrackRunning()) {
        return soundManager.pause(this.getCurrentTrackId());
      } else {
        return that.loadTrack(data);
      }
    },
    playPauseToggle: function() {
      return soundManager.togglePause(this.getCurrentTrackId());
    },
    jumpToPos: function(e) {
      var boxPos, boxPosRel, boxWidth, newPos;
      sf.log([__private.moduleName(), 'jumpToPos']);
      this.skipThroughRuns = 0;
      this._showLoader();
      boxPos = e.pageX - this.$el.offset().left;
      boxWidth = this.$el.width();
      boxPosRel = boxPos * 100 / boxWidth;
      newPos = this.getCurrentTrack().getVo().getDurationMs() * boxPosRel / 100;
      return soundManager.setPosition(this.getCurrentTrackId(), newPos);
    },
    closePlayer: function(e) {
      sf.log([__private.moduleName(), 'CLOSEPLAYER']);
      e.preventDefault();
      e.stopPropagation();
      this.destroyCurrentTrack();
      return this._hidePlayer();
    },
    setPlaylist: function(tracks) {
      return this.collection.reset(tracks);
    },
    renderPlaylist: function() {
      this.resetPlaylist();
      this.collection.each(function(track) {
        return this.$el.find('.playlist .tracks').append($('<li>' + track.title + '</li>').data('track', track));
      });
      return this.getFirstTrack().click();
    },
    getCurrentTrackId: function() {
      return this.currentTrackId;
    },
    setCurrentTrackId: function(id) {
      return this.currentTrackId = id;
    },
    isTrackRunning: function() {
      return this.trackIsRunning;
    },
    setTrackIsRunning: function(state) {
      return this.trackIsRunning = state;
    },
    getCurrentTrack: function() {
      return this.getTrackModelById(this.getCurrentTrackId());
    },
    getTrackModelById: function(trackId) {
      return this.soundsCollection.get(trackId);
    },
    getFirstTrack: function() {
      return this.soundsCollection.first();
    },
    getLastTrack: function() {
      return this.soundsCollection.last();
    },
    getTracksTotal: function() {
      var count;
      count = this.soundsCollection.length;
      if (count < 10) {
        count = '0' + count;
      }
      return count;
    },
    getNextTrack: function() {
      var newTrackModel, trackModel;
      sf.log([__private.moduleName(), 'getNextTrack', 'currentTrackId =', this.getCurrentTrackId()]);
      this.stopAll();
      newTrackModel = false;
      if (this.getCurrentTrackId() != null) {
        trackModel = this.getTrackModelById(this.currentTrackId);
        newTrackModel = this.soundsCollection.next(trackModel);
      }
      if (newTrackModel === false) {
        newTrackModel = this.getFirstTrack();
      }
      sf.log(['NEXT', newTrackModel]);
      this.setCurrentTrackId(newTrackModel.getVo().getId());
      return newTrackModel;
    },
    getPrevTrack: function() {
      var newTrackModel, trackModel;
      sf.log([__private.moduleName(), 'getPrevTrack', 'currentTrackId =', this.getCurrentTrackId()]);
      this.stopAll();
      newTrackModel = false;
      if (this.getCurrentTrackId() != null) {
        trackModel = this.getTrackModelById(this.currentTrackId);
        newTrackModel = this.soundsCollection.prev(trackModel);
      }
      if (newTrackModel === false) {
        newTrackModel = this.getLastTrack();
      }
      this.setCurrentTrackId(newTrackModel.getVo().getId());
      return newTrackModel;
    },
    stopAll: function() {
      return soundManager.stopAll();
    },
    destroyCurrentTrack: function() {
      if (this.currentTrackId != null) {
        return soundManager.destroySound(this.currentTrackId);
      }
    },
    loadTrack: function(trackModel) {
      var artistVo, eventVo, that, trackIndex, trackVo;
      sf.log([__private.moduleName(), 'loadTrack', trackModel, 'currentTrackId =', this.getCurrentTrackId()]);
      this.destroyCurrentTrack();
      that = this;
      that.skipThroughRuns = 0;
      trackIndex = (this.soundsCollection.indexOf(trackModel)) + 1;
      eventVo = this.eventModel.getVo();
      artistVo = this.artistModel.getVo();
      trackVo = trackModel.getVo();
      sf.log(['trackVo', trackVo.getStreamUrl()]);
      this._setWaveform(trackVo.getWaveformUrl());
      this.setTrackIsRunning(true);
      return soundManager.createSound({
        id: trackVo.getId(),
        url: trackVo.getStreamUrl(),
        autoPlay: true,
        onload: function() {
          sf.log(['loadTrack', 'onLoad', trackVo.getId(), this.duration]);
          return $('#artistsContainer .showLoadingStripes').removeClass('showLoadingStripes');
        },
        onbufferchange: function() {
          return sf.log(['loadTrack', 'onBufferChange', this.sID]);
        },
        onplay: function() {
          var currentTrackNumber, durationMs, totalTrackNumber;
          sf.log(['loadTrack', 'onPlay', this.sID]);
          that.$el.find('.player').addClass('playing');
          that.$el.find('.player .waveform .ran').css('width', '0%');
          that.$el.find('.player .artistName').html(artistVo.getName());
          durationMs = that._getTime(trackVo.getDurationMs());
          that.$el.find('.player .time .total').html(durationMs);
          that.$el.find('.player .trackName').html(trackVo.getTitle());
          that.$el.find('.player .eventUrl a').attr('href', "/#!/event/" + (eventVo.getUrlName()));
          currentTrackNumber = trackIndex;
          totalTrackNumber = that.getTracksTotal();
          that.$el.find('.player .trackNumber').html(currentTrackNumber + ' / ' + totalTrackNumber);
          return that._showPlayer();
        },
        whileplaying: function() {
          var currentMs, pos;
          if (that.skipThroughRuns === 1) {
            that._hideLoader();
          }
          that.skipThroughRuns++;
          currentMs = that._getTime(this.position);
          that.$el.find('.player .time .ran').html(currentMs);
          pos = this.position * 100 / trackVo.getDurationMs();
          return that.$el.find('.player .waveform .ran').css('width', pos + '%');
        },
        onresume: function() {
          return that.$el.find('.player').addClass('playing');
        },
        onpause: function() {
          return that.$el.find('.player').removeClass('playing');
        }
      });
    },
    goToEvent: function(e) {
      return e.stopPropagation();
    },
    _showPlayer: function() {
      return this.$el.animate({
        bottom: 0
      }, 150);
    },
    _hidePlayer: function() {
      return this.$el.animate({
        bottom: -105
      }, 150);
    },
    _setWaveform: function(waveformUrl) {
      this._showLoader();
      return this.$el.find('.player .waveform .wave').html('<img src="' + waveformUrl + '">');
    },
    _showLoader: function() {
      return this.$el.find('.player .complete').addClass('loading');
    },
    _hideLoader: function() {
      return this.$el.find('.player .complete').removeClass('loading');
    },
    _getTime: function(milliseconds) {
      var min, nSec, sec;
      nSec = Math.floor(milliseconds / 1000);
      min = Math.floor(nSec / 60);
      sec = nSec - (min * 60);
      if (sec < 10) {
        sec = '0' + sec;
      }
      return min + '.' + sec;
    }
  });
});
