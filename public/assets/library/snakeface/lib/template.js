
define(function(require) {
  var $, Engine, sf, __private, __public;
  $ = require('jquery');
  sf = require('snakeface');
  Engine = require('vendor/hoganjs/hogan-2.0.0.amd');
  __private = {
    moduleName: function() {
      return 'lib.Template';
    },
    compilations: {},
    templates: {},
    partials: {}
  };
  __public = {
    init: function() {
      this.initialTemplateFetch();
      return this.getPartials();
    },
    initialTemplateFetch: function() {
      var elm, scriptElements, _i, _len, _results;
      scriptElements = $('script[type="text/html"]');
      _results = [];
      for (_i = 0, _len = scriptElements.length; _i < _len; _i++) {
        elm = scriptElements[_i];
        _results.push(this.addTemplate($(elm).attr('id').replace('template-', ''), $(elm).html()));
      }
      return _results;
    },
    compile: function(mustache) {
      return Engine.compile(mustache);
    },
    addTemplate: function(tmplId, tmplCode) {
      __private.templates[tmplId] = tmplCode;
      __private.compilations[tmplId] = this.compile(tmplCode);
      return __private.partials[tmplId] = {};
    },
    addPartial: function(parentTmplId, partialTmplId) {
      partialTmplId = String(partialTmplId).replace(/[\{>\}\s]/g, '');
      return __private.partials[parentTmplId][partialTmplId] = __private.compilations[partialTmplId];
    },
    getTemplate: function(tmplId) {
      return __private.templates[tmplId];
    },
    getPartials: function() {
      var code, i, id, name, results, _results;
      _results = [];
      for (id in templates) {
        code = templates[id];
        results = code.match(/\{\{>\s{0,1}\w+\}\}/g);
        _results.push((function() {
          var _results1;
          _results1 = [];
          for (i in results) {
            name = results[i];
            _results1.push(this.addPartial(id, name));
          }
          return _results1;
        }).call(this));
      }
      return _results;
    },
    getCompiledTemplate: function(tmplId) {
      if (!(tmplId in __private.compilations)) {
        sf.log([__private.moduleName(), tmplId, 'template doesnt exist'], true);
      }
      return __private.compilations[tmplId];
    },
    render: function(tmplId, data) {
      var tmplObject;
      tmplObject = this.getCompiledTemplate(tmplId);
      return tmplObject.render(data, __private.partials[tmplId]);
    }
  };
  return __public;
});
