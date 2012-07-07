
define(function(require) {
  var $, api, sf, __public;
  $ = require('jquery');
  sf = require('snakeface');
  $.i18n = require('vendor/i18next/i18next-1.2.3');
  $.i18n.init({
    useLocalStorage: false,
    resGetPath: '/assets/locale/__lng__/__ns__.json',
    lng: 'en',
    fallbackLng: 'en'
  });
  __public = {
    setLng: function(lang) {
      sf.log([api.module, 'setLang', lang]);
      return $.i18n.setLng(lang);
    },
    get: function(key, vars) {
      var k, v;
      if (vars == null) {
        vars = {};
      }
      sf.log([api.module, 'get', key, vars]);
      try {
        for (k in vars) {
          v = vars[k];
          vars[k] = $.t(v);
        }
        return $.t(key, vars);
      } catch (error) {
        return sf.logError([api.module, 'key missing', key, error]);
      }
    }
  };
  return api = {
    module: 'lib.Locale',
    __public: __public
  };
});
