require.config({
  baseUrl: "/assets",
  urlArgs: "bust=" + new Date().getTime(),
  paths: {
    jquery: 'vendor/jquery/jquery-1.7.2',
    text: 'vendor/requirejs/text',
    underscore: 'vendor/backbone/underscore-1.3.3',
    backbone: 'vendor/backbone/backbone-0.9.2',

    abstractModel: 'js/abstract/abstractModel',
    abstractCollection: 'js/abstract/abstractCollection',
    abstractView: 'js/abstract/abstractView'
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

require(['js/mymemories'], function(mymemories){ mymemories(); });