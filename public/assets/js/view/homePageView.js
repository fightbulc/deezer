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

    events: {
      'click button': 'login'
    },

    // ----------------------------

    render: function(){
      this.$el.html(template.render());

      this.renderBubbles();

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

    // ----------------------------

    login: function(){
      DZ.login(function(response) {
        if (response.authResponse) {
          console.log('Welcome!  Fetching your information.... ');
          DZ.api('/user/me', function(response) {
            console.log('Good to see you, ' + response.name + '.');
          });
        } else {
          console.log('User cancelled login or did not fully authorize.');
        }
      });
    }
  });

  return homePageView;

});