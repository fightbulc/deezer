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
          //console.log('Welcome!  Fetching your information.... ');
          //DZ.api('/user/me', function(response) {
          //  console.log('Good to see you, ' + response.name + '.');
          //});
        }
      });
    },

    // ----------------------------

    logout: function(){
      DZ.logout();
      this.$el.html(template.render());
    }
  });

  return userWidgetView;

});