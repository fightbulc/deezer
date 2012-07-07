define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');

  var template = hogan.compile(require('text!templates/trackPage.mustache'));
  // ##########################################

  var trackPageView = abstractView.extend({
    el: '#trackPage',

    // ----------------------------

    render: function(){
      console.log('routed to track page');
      this.$el.html(template.render());

      return this;
    }
  });

  return trackPageView;

});