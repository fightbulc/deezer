define(function(require){

  var $ = require('jquery');
  var base = require('base');
  var router = require('js/router/router')
  var DZ = require('js/deezer');

  var userWidgetView = require('js/view/userWidgetView')

  return function(){

    base.set('userWidgetView', new userWidgetView);
    base.get('userWidgetView').render();

    base.set('router', new router);
  };

});