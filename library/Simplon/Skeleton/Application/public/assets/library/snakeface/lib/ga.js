
define(function(require) {
  var api, private, pubsub, sf;
  sf = require('snakeface');
  pubsub = require('pubsub');
  pubsub.public.subscribe({
    channel: 'ga:track',
    callback: private.track
  });
  private = {
    track: function(options) {
      sf.public.log([api.module, 'track', options.page]);
      return _gaq.push(['_trackPageview', "#!/" + options.page]);
    }
  };
  return api = {
    module: 'lib.GA',
    public: public
  };
});
