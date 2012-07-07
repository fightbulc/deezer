define(function(require){

	var $ = require('jquery');

	var deferred = $.Deferred();
	DZ.promisePlayerOnLoad = deferred.promise();
	DZ.init({
		appId  : '103961',
	    channelUrl : 'http://'+window.location.host+'/deezerChannel.php',
		player : {
			container : 'deezerPlayer',
			cover : true,
			playlist : true,
			width : 400,
			height : 100,
			format : 'vertical',
			onload : function(){
				deferred.resolve();
			}
		}
	});

  return DZ;

});