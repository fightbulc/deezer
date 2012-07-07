define(function(require){

  var base = require('base');
  var abstractRouter = require('abstractRouter');

  var homePageView = require('js/view/homePageView');
  var trackPageView = require('js/view/trackPageView');
  var testPageView = require('js/view/testPageView');
  var trackPageView = require('js/view/trackPageView');

  var router = abstractRouter.extend({

    routes: {
      "!/home": "home",
      "!/test": "test",
      "!/track/:id": "track",
      "*default": "default"
    },

    home: function(){
      if(!base.has('homePageView')){ base.set('homePageView', new homePageView); }
      base.get('homePageView').render();
      this.switchPage('homePage');
    },

    track: function(id){
      if(!base.has('trackPageView')){ base.set('trackPageView', new trackPageView); }
      base.get('trackPageView').render(id);
      base.get('trackPageView').playTrack(id);
      this.switchPage('trackPageView');
    },

    test: function(){
      if(!base.has('testPageView')){ base.set('testPageView', new testPageView); }
      base.get('testPageView').render();
      this.switchPage('testPage');
    },

    default: function(){
      this.redirect('home');
    }
  });

  return router;

});