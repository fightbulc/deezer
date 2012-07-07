define(function(require){

  var $ = require('jquery');
  var base = require('base');
  var homePageView = require('js/view/homePageView');

  return function(){

    base.set('homePageView', new homePageView);
    base.get('homePageView').render();

    console.log('woooo!');
  };

});