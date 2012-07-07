
define(function(require) {
  var $, pubsub, soundManagerLib, _private, _public;
  $ = require('jquery');
  pubsub = require('pubsub');
  soundManagerLib = require('/assets/vendor/soundmanager2/soundmanager2-amd.js');
  _private = {
    moduleName: function() {
      return 'lib.soundplayer';
    }
  };
  return _public = {
    init: function() {
      this.SM2_DEFER = true;
      window.soundManager = new soundManagerLib();
      soundManager.url = '/assets/vendor/soundmanager2/swf/';
      return soundManager.beginDelayedInit();
    },
    initEventListeners: function() {
      return $('#soundplayer .player .tracks li').on('click', function() {
        var $track, data, playing;
        $track = $(this);
        data = $track.data('track');
        playing = $track.is('.active');
        sf.log([_private.moduleName(), 'initEventListeners', 'li.click', data.id, data.stream_url]);
        $track.toggleClass('active').siblings('li').removeClass('active');
        if (playing) {
          return soundManager.pause(that.currentTrackId);
        } else {
          return that.loadTrack(data);
        }
      });
    }
  };
});
