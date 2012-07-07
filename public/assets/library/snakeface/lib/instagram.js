
define(function(require) {
  var pubsub, sf, __private, __public;
  sf = require('snakeface');
  pubsub = require('pubsub');
  __private = {
    moduleName: function() {
      return 'lib.Instagram';
    }
  };
  return __public = {
    c_id: "be7ff209cdb34667af149a24c65d23d9",
    count: 0,
    picArray: [],
    numberOfPages: 5,
    getImagesByCoordinates: function(options) {
      var url;
      sf.log([__private.moduleName(), 'getImagesByCoordinats', options]);
      if ((options.lng != null) && (options.lat != null)) {
        this.count = 0;
        this.picArray = [];
        this.picArray.length = 0;
        if (options.distance == null) {
          options.distance = 50;
        }
        url = "https://api.instagram.com/v1/media/search?client_id=" + this.c_id + "&lat=" + options.lat + "&lng=" + options.lng + "&distance=" + options.distance;
        return this.getPics(url);
      } else {
        return sf.logError([__private.moduleName(), 'getImagesByCoordinates', 'missing lng or lat']);
      }
    },
    getImagesByTerm: function(term) {
      var terms, url;
      sf.log([__private.moduleName(), 'getImagesByTerm', term]);
      if (term) {
        this.count = 0;
        this.picArray = [];
        this.picArray.length = 0;
        term = term.replace("berlin_", "");
        terms = term.split("-");
        url = "https://api.instagram.com/v1/tags/" + terms[1] + "/media/recent?client_id=" + this.c_id;
        console.log([url]);
        return this.getPics(url);
      } else {
        return sf.logError([__private.moduleName(), 'getImagesByTag', 'missing search term']);
      }
    },
    getPics: function(url) {
      var _this = this;
      return $.ajax({
        type: "GET",
        dataType: "jsonp",
        url: url,
        success: function(response) {
          if (response.data != null) {
            _this.paginate(response);
          }
          if (response.meta.error_message != null) {
            return sf.logError([__private.moduleName(), 'getImagesByTag Error', response.meta.error_message]);
          }
        }
      });
    },
    paginate: function(response) {
      this.picArray = this.picArray.concat(response.data);
      if ((response.pagination != null) && (response.pagination.next_url != null) && this.count < this.numberOfPages) {
        this.getPics(response.pagination.next_url);
        return this.count++;
      } else {
        return pubsub.publish('instagram:gotPics', this.picArray);
      }
    }
  };
});
