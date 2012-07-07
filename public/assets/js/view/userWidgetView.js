define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');

  var template = hogan.compile(require('text!templates/userWidget.mustache'));

  // ##########################################

  var userWidgetView = abstractView.extend({
    el: '#userWidget',

    // ----------------------------

    events: {
      'click button': 'login'
    },

    // ----------------------------

    render: function(){

      var templateVars = {};

      this.$el.html(template.render());

      //this.renderBubbles();

      return this;
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

  return userWidgetView;

});