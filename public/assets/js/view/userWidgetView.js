define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');
  var DZ = require('js/deezer');

  var template = hogan.compile(require('text!templates/userWidget.mustache'));

  // ##########################################

  var userWidgetView = abstractView.extend({
    el: '#userWidget',

    // ----------------------------

    accessToken: null,

    // ----------------------------

    events: {
      'click button.login': 'login',
      'click button.logout': 'logout'
    },

    // ----------------------------

    render: function(){

      var templateVars = {};
      var that = this;

      DZ.getLoginStatus(function(response){
        if(response.authResponse){
          that.accessToken = response.authResponse.accessToken;
          //console.log(response);
          templateVars.logged = true;
        }
        that.$el.html(template.render(templateVars));
      });

      return this;
    },

    // ----------------------------

    login: function(){
      var that = this;
      DZ.login(function(response) {
        if (response.authResponse) {
          that.accessToken = response.authResponse.accessToken;
          that.$el.html(template.render({logged: true}));
        }
      });
    },

    // ----------------------------

    logout: function(){
      DZ.logout();
      this.accessToken = null;
      this.$el.html(template.render());
    }
  });

  return userWidgetView;

});