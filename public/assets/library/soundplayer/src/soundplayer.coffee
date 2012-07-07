define (require) ->
  $ = require 'jquery'
  pubsub = require 'pubsub'
  soundManagerLib = require '/assets/vendor/soundmanager2/soundmanager2-amd.js'

  ###############################################

  _private =
    moduleName: ->
      'lib.soundplayer'

    # -------------------------------------------


  ###############################################

  _public =
    init: ->
      @SM2_DEFER = true
      window.soundManager = new soundManagerLib()
      soundManager.url = '/assets/vendor/soundmanager2/swf/'
      soundManager.beginDelayedInit()

    # -------------------------------------------

    initEventListeners: ->
      #
      # Play clicked song
      #
      $('#soundplayer .player .tracks li').on 'click', ->
        $track = $(this)
        data = $track.data('track')
        playing = $track.is('.active')

        sf.log [_private.moduleName(), 'initEventListeners', 'li.click', data.id, data.stream_url]
        $track.toggleClass('active').siblings('li').removeClass('active')

        if playing
          soundManager.pause that.currentTrackId
        else
          that.loadTrack data
