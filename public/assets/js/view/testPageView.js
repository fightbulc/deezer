define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');

  var template = hogan.compile(require('text!templates/testPage.mustache'));
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