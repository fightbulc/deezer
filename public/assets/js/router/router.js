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
      "!/tag/:tag": "tag",
      "!/memory/:tag": "memory",
      "*default": "default"
    },

    home: function(){
      if(!base.has('homePageView')){ base.set('homePageView', new homePageView); }
      this.switchPage();
      base.get('homePageView').render();
    },

    track: function(id){
      if(!base.has('trackPageView')){ base.set('trackPageView', new trackPageView); }
      this.switchPage();
      base.get('trackPageView').render(id);
    },

    tag: function(tag){
      if(!base.has('trackPageView')){ base.set('trackPageView', new trackPageView); }
      this.switchPage();
      base.get('trackPageView').render(id, tag);
    },

    test: function(){
      if(!base.has('testPageView')){ base.set('testPageView', new testPageView); }
      this.switchPage();
      base.get('testPageView').render();
    },

    memory: function(){

    },

    default: function(){
      this.redirect('home');
    },

    switchPage: function(){
      this.$pages.hide();
      if(base.has('trackPageView')){
        base.get('trackPageView').stop();
      }
    }
  });

  return router;

});