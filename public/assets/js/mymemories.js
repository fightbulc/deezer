define(function(require){

  var $ = require('jquery');
  var base = require('base');
  var router = require('js/router/router')

  return function(){

    base.set('router', new router);

  };

});