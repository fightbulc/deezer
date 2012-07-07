define(function(require){

  var hogan = require('hogan');
  var abstractView = require('abstractView');

  var template = hogan.compile(require('text!templates/bubble.mustache'));

  var bubbleView = abstractView.extend({

    tagName: 'div',
    className: 'bubble',

    timer: 0, // helper for setTimeouts

    _randomInt: function(cap){
      return Math.floor(Math.random() * cap);
    },

    render: function(){
      this.viewport = {
        width: $(window).width()-200,
        height: $(window).height()-200
      };

      this.style = {
        left: this._randomInt(this.viewport.width),
        top: this._randomInt(this.viewport.height),
        opacity: Math.random()
      };

      this.setZindex();

      this.$el.css(this.style);
      this.$el.addClass('bbl' + (this._randomInt(5)+1));
      this.$el.html(template.render());

      this.fade();

      return this;
    },

    fade: function(){
      var that = this;
      clearTimeout(this.timer);
      this.timer = setTimeout(function(){ that.animate(); }, Math.floor(Math.random() * 10000));
    },

    animate: function(){
      var that = this;

      this.style.left = this.style.left + this._randomInt(100)-50;
      this.style.top = this.style.top + this._randomInt(100)-50;
      this.style.opacity = Math.random();

      if(this.style.left < 0){
        this.style.left = 0;
      }else if(this.style.left > this.viewport.width){
        this.style.left = this.viewport.width;
      }

      if(this.style.top < 0){
        this.style.top = 0;
      }else if(this.style.top > this.viewport.height){
        this.style.top = this.viewport.height;
      }

      this.setZindex();

      this.$el.animate(this.style, Math.floor(Math.random() * 5000)+2000, function(){ that.fade(); });
    },

    setZindex: function(){
      this.style['z-index'] = Math.round(this.style.opacity * 100)
    }

  });

  return bubbleView;
});