
define(function(require) {
  var $, abstractView, base, eventView, momentjs, pubsub, sexytime, sf, template, util, _, __private;
  $ = require('jquery');
  _ = require('underscore');
  sf = require('snakeface');
  util = require('util');
  pubsub = require('pubsub');
  template = require('template');
  abstractView = require('abstractView');
  momentjs = require('moment');
  sexytime = require('vendor/momentjs/sexytime');
  base = require('base');
  eventView = require('app/js/View/EventsListing/EventView');
  template.addTemplate('PageEventsListing', require('text!app/tmpl/EventsListing/Page.mustache'));
  template.addTemplate('DayEventsListing', require('text!app/tmpl/EventsListing/Day.mustache'));
  __private = {
    moduleName: function() {
      return 'view.PageEventsListing';
    }
  };
  return abstractView.extend({
    el: '#pageEventsListing',
    initialize: function() {
      sf.log([__private.moduleName(), '>>>', 'init']);
      _.bindAll(this, 'render', 'addAll', 'addOne');
      this.collection = base.getInstance('eventsCollection');
      this.collection.on('add', this.addOne);
      return sf.log(["now", util.getNow()]);
    },
    render: function() {
      var tmplData;
      tmplData = {
        total: this.collection.length
      };
      this.$el.html(template.render('PageEventsListing', tmplData));
      this.addAll();
      this.$el.sexytime();
      return this;
    },
    addAll: function() {
      return this.collection.each(this.addOne);
    },
    addOne: function(model) {
      var vo;
      vo = model.getVo();
      if (vo.getIsRunning()) {
        this.addRunningEvent(model);
      }
      if (vo.getIsUpcoming()) {
        this.addUpcomingEvent(model);
      }
      if (this.collection.isLast(model)) {
        return pubsub.publish('PageEventsListingView:ready');
      }
    },
    addRunningEvent: function(model) {
      var dayContainer, dayId;
      dayId = "Running";
      dayContainer = " #" + dayId;
      if (!this.$el.find(dayContainer).length) {
        this.addDayContainer(dayId, dayId);
      }
      return this.injectEvent(model, dayContainer);
    },
    addUpcomingEvent: function(model) {
      var dayContainer, dayId, formattedDate, startDate;
      startDate = new Date(model.getVo().getDateStart());
      formattedDate = this.formatDate(startDate);
      dayId = formattedDate.replace(/\s/g, "");
      dayContainer = " #" + dayId;
      if (!this.$el.find(dayContainer).length) {
        this.addDayContainer(formattedDate, dayId);
      }
      return this.injectEvent(model, dayContainer);
    },
    injectEvent: function(model, container) {
      var view;
      view = new eventView({
        model: model
      });
      return this.$el.find(container).append(view.render().el);
    },
    addDayContainer: function(title, dayId) {
      var tmplData;
      tmplData = {
        day: title,
        id: dayId
      };
      return this.$el.find("#eventsContainer").append(template.render('DayEventsListing', tmplData));
    },
    formatDate: function(date) {
      var daysFromStartOfDay, formattedDate, startOfDay;
      startOfDay = util.getStartOfDay();
      daysFromStartOfDay = ~~((date - startOfDay) / 86400000);
      switch (daysFromStartOfDay) {
        case 0:
          formattedDate = "Today";
          break;
        case 1:
          formattedDate = "Tomorrow";
          break;
        default:
          formattedDate = momentjs(date).format("dddd Do");
      }
      return formattedDate;
    }
  });
});
