define(function(require){

  var $ = require('jquery');
  var backbone = require('backbone');

  var abstractRouter = backbone.Router.extend({

    initialize: function(){
      this.$pages = $('#pages .page');
      backbone.history.start();
    },

    switchPage: function(id){
      this.$pages.hide();
      $('#'+id).show();
    },

    redirect: function(route){
      this.navigate('!/'+route, {trigger: true});
    }

  });

  return abstractRouter;
});