define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');

  var template = hogan.compile(require('text!templates/homePage.mustache'));
  // ##########################################

  var testPageView = abstractView.extend({
    el: '#testPage',

    // ----------------------------

    render: function(){
      this.$el.html(template.render());

      return this;
    }
  });

  return testPageView;

});