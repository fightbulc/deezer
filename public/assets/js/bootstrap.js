require.config({

  baseUrl: "/assets",
  urlArgs: "bust=" + new Date().getTime(),
  priority: ['jquery'],
  paths: {
    jquery: 'vendor/jquery/jquery-1.7.2',
    text: 'vendor/requirejs/text',
    underscore: 'vendor/backbone/underscore-amd-1.3.2',
    backbone: 'vendor/backbone/backbone-amd-0.9.2',
  }
});

require(['js/mymemories'], function(mymemories){ mymemories(); });