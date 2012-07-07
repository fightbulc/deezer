define (require) ->
  $ = require 'jquery'
  sf = require 'snakeface'
  pubsub = require 'pubsub'
  facebook = require '/assets/library/snakeface/lib/facebook.js'

  ###############################################

  ->
    sf.public.log 'app matrix...loaded'

    sf.public.pageStateLoading()
    facebook.public.init()

    pubsub.public.subscribe
      channel: 'facebook:ready'
      callback: (data) ->
        sf.public.log ['facebook:ready', data]

        sf.public.pageStateLoaded()

        # ---------

        $('#facebookLogin').on 'click', (e) ->
          e.preventDefault()

          pubsub.public.publish
            channel: 'facebook:loginViaPopup'
            data: []

        # ---------

        $('#facebookLogout').on 'click', (e) ->
          e.preventDefault()

          pubsub.public.publish
            channel: 'facebook:logout'
            data: []

        # ---------

        pubsub.public.subscribe
          channel: 'facebook:authNoSession'
          callback: (data) ->
            $('.facebookState span').hide().fadeIn().text('No session')

        # ---------

        pubsub.public.subscribe
          channel: 'facebook:authNotAuthorized'
          callback: (data) ->
            $('.facebookState span').hide().fadeIn().text('Not authorized for app')

        # ---------

        pubsub.public.subscribe
          channel: 'facebook:authHasSession'
          callback: (data) ->
            $('.facebookState span').hide().fadeIn().text('Connected')

            # call backend
            sf.public.jsonRequest
              api: 'Open.FooServices.getBar'
              params:
                token: '123456'
