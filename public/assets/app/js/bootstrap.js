
require.config({
  baseUrl: "/assets",
  urlArgs: "bust=" + new Date().getTime(),
  paths: {
    jquery: 'vendor/jquery/jquery-1.7.2',
    text: 'vendor/requirejs/text',
    async: 'vendor/requirejs/plugins/async',
    underscore: 'vendor/backbone/underscore-1.3.3',
    backbone: 'vendor/backbone/backbone-0.9.2',
    pubsub: 'vendor/pubsubjs/pubsub',
    moment: 'vendor/momentjs/moment',
    snakeface: 'library/snakeface/snakeface',
    facebook: 'library/snakeface/lib/facebook',
    twitter: 'library/snakeface/lib/twitter',
    instagram: 'library/snakeface/lib/instagram',
    postoffice: 'library/snakeface/lib/postoffice',
    template: 'library/snakeface/lib/template',
    util: 'library/snakeface/lib/util',
    ga: 'library/snakeface/lib/ga',
    googleMaps: 'library/snakeface/lib/googleMaps',
    abstractManager: 'library/snakeface/abstract.manager',
    abstractCollection: 'library/snakeface/abstract.collection',
    abstractModel: 'library/snakeface/abstract.model',
    abstractVo: 'library/snakeface/abstract.vo',
    abstractDto: 'library/snakeface/abstract.dto',
    abstractView: 'library/snakeface/abstract.view',
    abstractRouter: 'library/snakeface/abstract.router',
    base: 'app/js/base'
  },
  shim: {
    backbone: {
      deps: ['underscore', 'jquery'],
      exports: 'Backbone'
    },
    underscore: {
      exports: '_'
    },
    sexytime: {
      deps: ['jquery', 'moment'],
      exports: '$.fn.sexytime'
    }
  }
});

require(['app/js/matrix'], function(matrix) {
  return matrix();
});
