
define(function(require) {
  var $, facebook, pubsub, sf;
  $ = require('jquery');
  sf = require('snakeface');
  pubsub = require('pubsub');
  facebook = require('/assets/library/snakeface/lib/facebook.js');
  return function() {
    sf.public.log('app matrix...loaded');
    sf.public.pageStateLoading();
    facebook.public.init();
    return pubsub.public.subscribe({
      channel: 'facebook:ready',
      callback: function(data) {
        sf.public.log(['facebook:ready', data]);
        sf.public.pageStateLoaded();
        $('#facebookLogin').on('click', function(e) {
          e.preventDefault();
          return pubsub.public.publish({
            channel: 'facebook:loginViaPopup',
            data: []
          });
        });
        $('#facebookLogout').on('click', function(e) {
          e.preventDefault();
          return pubsub.public.publish({
            channel: 'facebook:logout',
            data: []
          });
        });
        pubsub.public.subscribe({
          channel: 'facebook:authNoSession',
          callback: function(data) {
            return $('.facebookState span').hide().fadeIn().text('No session');
          }
        });
        pubsub.public.subscribe({
          channel: 'facebook:authNotAuthorized',
          callback: function(data) {
            return $('.facebookState span').hide().fadeIn().text('Not authorized for app');
          }
        });
        return pubsub.public.subscribe({
          channel: 'facebook:authHasSession',
          callback: function(data) {
            $('.facebookState span').hide().fadeIn().text('Connected');
            return sf.public.jsonRequest({
              api: 'Open.FooServices.getBar',
              params: {
                token: '123456'
              }
            });
          }
        });
      }
    });
  };
});
