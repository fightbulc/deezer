define(function(require){

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
				console.log('loaded');
			}
		}
	});

  return DZ;

});