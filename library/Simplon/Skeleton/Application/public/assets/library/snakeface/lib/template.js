
define(function(require) {
  var $, Engine, api, private, public;
  $ = require('jquery');
  Engine = require('/assets/vendor/hoganjs/hogan-1.0.5.js');
  private = {
    compilations: {},
    templates: {},
    partials: {}
  };
  public = {
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
      private.templates[tmplId] = tmplCode;
      private.compilations[tmplId] = this.compile(tmplCode);
      return private.partials[tmplId] = {};
    },
    addPartial: function(parentTmplId, partialTmplId) {
      partialTmplId = String(partialTmplId).replace(/[\{>\}\s]/g, '');
      return private.partials[parentTmplId][partialTmplId] = private.compilations[partialTmplId];
    },
    getTemplate: function(tmplId) {
      return private.templates[tmplId];
    },
    getPartials: function() {
      var code, i, id, name, results, _results;
      _results = [];
      for (id in templates) {
        code = templates[id];
        results = code.match(/\{\{>\s{0,1}\w+\}\}/g);
        _results.push((function() {
          var _results2;
          _results2 = [];
          for (i in results) {
            name = results[i];
            _results2.push(this.addPartial(id, name));
          }
          return _results2;
        }).call(this));
      }
      return _results;
    },
    getCompiledTemplate: function(tmplId) {
      if (!(tmplId in compilations)) {
        _.app().core.log([tmplId, 'template doesnt exist'], true);
      }
      return private.compilations[tmplId];
    },
    render: function(tmplId, data) {
      var tmplObject;
      tmplObject = this.getCompiledTemplate(tmplId);
      return tmplObject.render(data, private.partials[tmplId]);
    }
  };
  return api = {
    name: 'lib.Template',
    public: public
  };
});
