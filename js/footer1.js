var imgArr = new Array( // relative paths of images
	'resources/wallpapers/tower.jpg',
	'resources/wallpapers/peches.jpg',
	'resources/wallpapers/lum.jpg',
	'resources/wallpapers/bat.jpg',
	'resources/wallpapers/city.jpg',
	'resources/wallpapers/lac.jpg',
	'resources/wallpapers/cities.jpg',
	'resources/wallpapers/neige.jpg',
	'resources/wallpapers/paname.jpg',
	'resources/wallpapers/sol.jpg');

	var preloadArr = new Array();
	var i;

	/* preload images */
	for(i=0; i < imgArr.length; i++){
		preloadArr[i] = new Image();
		preloadArr[i].src = imgArr[i];
	}
	var currImg
	if (document.cookie.indexOf("val") == 0) {
		currImg = document.cookie.split(";")[0].split("=")[1];
		currImg = currImg%preloadArr.length;
	}
	else {
		currImg = 1;
	}
	if (currImg == 0) {currImg = 1;}
	$(document).ready(function() {
		$('#fond').css('background','url(' + imgArr[(currImg - 1)%preloadArr.length] +') no-repeat center center fixed');
		$('#fond').css('-webkit-background-size', 'cover');
		$('#fond').css('-moz-background-size', 'cover');
		$('#fond').css('-o-background-size', 'cover');
		$('#fond').css('background-size', 'cover');
	document.cookie = "val=" + currImg;

	currImg = currImg + 1;
});
