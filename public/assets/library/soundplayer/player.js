soundcloudPlayer = {

  urlArtist:"https://api.soundcloud.com/users/{{userName}}/tracks.json",
  urlTrack:"https://api.soundcloud.com/users/{{userName}}/tracks/{{track}}.json",
  appId:null,
  url:null,
  currentArtistName:null,
  currentTrackId:null,
  currentTrackData:null,
  runThrough:0,


  // ############################################


  log:function (log) {
    // console.log(log);
  },


  // ############################################


  init:function (appId) {
    this.log(['scplayer', 'init', appId]);

    this.appId = appId;
    this.initEvents();
  },


  // ############################################


  initEvents:function () {
    that = this;
    that.log(['scplayer', 'initEvents']);

    // ###
    // play clicked song
    //
    $('.player .tracks li').live('click', function () {
      var $track = $(this);
      var data = $track.data('track');
      var playing = $track.is('.active');

      that.log(['scplayer', 'initEvents', 'li.click', data.id, data.stream_url]);
      $track.toggleClass('active').siblings('li').removeClass('active');

      if (playing) {
        soundManager.pause(that.currentTrackId);
      }
      else {
        that.loadTrack(data);
      }
    });

    // ###
    // play/pause toggle
    //
    $('.player .play, .pause').live('click', function () {
      if ($('.player .tracks li').hasClass('active') == true) {
        soundManager.togglePause('track_' + $('.player .tracks li.active').data('track').id);
      }
      else {
        $('.player .tracks li:first').click();
      }
    });

    // ###
    // play next song
    //
    $('.player .next').live('click', function () {
      soundcloudPlayer.nextTrack();
    });

    // ###
    // play prev song
    //
    $('.player .prev').live('click', function () {
      soundcloudPlayer.prevTrack();
    });

    // ###
    // jump to position
    //
    //
    $('.player .waveform, .player .title-bar').live('click', function (e) {
      $('.player .complete').addClass('loading');

      boxPos = e.pageX - $(this).offset().left;
      boxWidth = $(this).width()
      boxPosRel = boxPos * 100 / boxWidth;

      newPos = that.currentTrackData.duration * boxPosRel / 100;
      that.log(['setPos', newPos]);

      that.runThrough = 0;
      soundManager.setPosition(that.currentTrackId, newPos);
    });

    // ###
    // close player
    //
    $('.player .closePlayer').live('click', function (e) {
      e.preventDefault();

      that.destroyCurrentTrack();

      // push playerWidget out of visible area
      $('#soundcloudPlayer').animate({bottom:'-105px'}, 150);
      $('.resize-container').animate({bottom:0}, 150);
    });
  },


  // ############################################


  loadArtist:function (artistName, userName) {
    this.currentArtistName = artistName;
    userNameParts = userName.split('/');

    if (userNameParts.length > 1) {
      this.url = this.urlTrack.replace('{{userName}}', userNameParts[0]).replace('{{track}}', userNameParts[1]);
    }
    else {
      this.url = this.urlArtist.replace('{{userName}}', userName);
    }

    this.url = this.url + '?client_id=' + this.appId;
    this.log(['scplayer', 'loadArtist', userName, this.url]);

    // load playlist
    this.getPlaylist();
  },


  // ############################################


  getPlaylist:function () {
    that = this;

    $.getJSON(this.url, function (playlist) {
      that.resetPlaylist();
      that.log(['scplayer', 'getPlaylist', this.url]);

      // if only track response
      if (!playlist.length) {
        playlist = [playlist];
      }

      $.each(playlist, function (index, track) {
        if (track.streamable == true) {
          $('<li>' + track.title + '</li>').data('track', track).appendTo('.player .tracks');
        }
      });

      // initialise first track
      that.getFirstTrack().click();
    });
  },


  // ############################################


  getFirstTrack:function () {
    return $('.player .tracks li:first');
  },


  // ############################################


  resetPlaylist:function () {
    this.log(['scplayer', 'resetPlaylist']);
    $('.player .tracks').empty();
  },


  // ############################################


  setCurrentTrack:function (track) {
    this.runThrough = 0;
    this.currentTrackId = 'track_' + track.id;
    this.currentTrackData = track;
  },


  // ############################################


  getCurrentTrackIndex:function () {
    count = $('.player .tracks li').index($('.player .tracks li.active')) + 1;

    if (count < 10) {
      count = '0' + count;
    }

    return count;
  },


  // ############################################


  getTracksTotal:function () {
    count = $('.player .tracks li').length;

    if (count < 10) {
      count = '0' + count;
    }

    return count;
  },


  // ############################################


  destroyCurrentTrack:function () {
    if (this.currentTrackId) {
      that.log(['scplayer', 'destroyCurrentTrack', this.currentTrackId]);
      soundManager.destroySound(this.currentTrackId);
    }
  },


  // ############################################


  loadTrack:function (track) {
    that = this;

    that.log(['scplayer', 'loadTrack', track]);

    that.destroyCurrentTrack();
    that.setCurrentTrack(track);

    // set track stream url
    url = track.stream_url;
    (url.indexOf("secret_token") == -1) ? url = url + '?' : url = url + '&';
    url = url + 'consumer_key=' + this.appId;

    soundManager.createSound(
      {
        id:that.currentTrackId,

        url:url,

        autoPlay:true,

        onload:function () {
          that.log(['loadTrack', 'onLoad', this.duration]);
        },

        onbufferchange:function () {
          that.log(['loadTrack', 'onBufferChange', that.currentTrackId]);
        },

        onplay:function () {
          that.log(['loadTrack', 'onPlay']);

          $('.player').addClass('playing');
          $('.player .waveform .ran').css('width', '0%');
          $('.player .time .total').html(soundcloudPlayer.getTime(track.duration));

          $('.player .artistName').html(that.currentArtistName);
          $('.player .trackName').html(track.title.length > 50 ? track.title.substring(0, 50) + ' [...]' : track.title);

          currentTrackNumber = that.getCurrentTrackIndex();
          totalTrackNumber = that.getTracksTotal();
          $('.player .trackNumber').html(currentTrackNumber + ' / ' + totalTrackNumber);

          $('.player .waveform .wave').html('<img src="' + track.waveform_url + '">');
          $('.player .complete').addClass('loading');

          // push playerWidget into visible area
          $('#soundcloudPlayer').animate({bottom:0}, 150);
          $('.resize-container').animate({bottom:100}, 150);
        },

        whileplaying:function () {
          that.runThrough++;

          $('.player .time .ran').html(soundcloudPlayer.getTime(this.position));

          pos = this.position * 100 / that.currentTrackData.duration;
          $('.player .waveform .ran').css('width', pos + '%');

          if (that.runThrough == 2) {
            $('.player .complete').removeClass('loading');
          }

          // that.log(['loadTrack', 'whilePlaying', pos, that.runThrough]);
        },

        onresume:function () {
          that.log(['loadTrack', 'onResume']);
          $('.player').addClass('playing');
        },

        onpause:function () {
          that.log(['loadTrack', 'onPause']);
          $('.player').removeClass('playing');
        },

        onfinish:function () {
          that.log(['loadTrack', 'onFinish']);
          that.nextTrack();
        }
      });
  },


  // ############################################


  nextTrack:function () {
    soundManager.stopAll();

    if ($('.player .tracks li.active').next().click().length == 0) {
      $('.player .tracks li:first').click();
    }
  },


  // ############################################


  prevTrack:function () {
    soundManager.stopAll();

    if ($('.player .tracks li.active').prev().click().length == 0) {
      $('.player .tracks li:last').click();
    }
  },


  // ############################################


  getTime:function (milliseconds) {
    var nSec = Math.floor(milliseconds / 1000);
    var min = Math.floor(nSec / 60);
    var sec = nSec - (min * 60);
    return min + '.' + (sec < 10 ? '0' + sec : sec);
  }
}
