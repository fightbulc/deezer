define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');

  var template = hogan.compile(require('text!templates/homePage.mustache'));

  var bubbleView = require('js/view/bubbleView');

  // ##########################################

  var homePageView = abstractView.extend({
    el: '#homePage',

    // ----------------------------

    render: function(){
      this.$el.html(template.render());

      //this.renderBubbles();

      return this;
    },

    // ----------------------------

    renderBubbles: function(){
      var i, view;
      for(i=0; i<100; i+=1){
        view = new bubbleView;

        this.$('.bubbles').append(view.render().el);
      }
    },
  });

  return homePageView;

});