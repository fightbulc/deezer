
define(function(require) {
  var $, api, private, public, sf;
  $ = require('jquery');
  sf = require('snakeface');
  private = {
    cache: {}
  };
  public = {
    subscribe: function(options) {
      sf.public.log([api.module, 'subscribe', options.channel, options]);
      if (!private.cache[options.channel]) private.cache[options.channel] = [];
      private.cache[options.channel].push(options.callback);
      return [options.channel, options.callback];
    },
    publish: function(options) {
      sf.public.log([api.module, 'publish', options.channel, options]);
      return private.cache[options.channel] && $.each(private.cache[options.channel], function() {
        return this.apply($, options.data || []);
      });
    },
    unsubscribe: function(handle) {
      var t;
      sf.public.log([api.module, 'unsubscribe', handle]);
      t = handle[0];
      return private.cache[t] && $.each(private.cache[t], function(idx) {
        if (this === handle[1]) return private.cache[t].splice(idx, 1);
      });
    }
  };
  return api = {
    module: 'lib.PubSub',
    public: public
  };
});
