
define(function(require) {
  var sf, __private, __public;
  sf = require('snakeface');
  __private = {
    moduleName: function() {
      return 'lib.GA';
    }
  };
  return __public = {
    track: function(options) {
      sf.log([__private.moduleName(), 'track', options.page]);
      return _gaq.push(['_trackPageview', "#!/" + options.page]);
    }
  };
});
