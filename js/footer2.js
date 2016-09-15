$(document).ready(function(){
	$('#fond').css('background','url(' + imgArr[(currImg - 2)%preloadArr.length] +') no-repeat center center fixed');
	$('#fond').css('-webkit-background-size', 'cover');
	$('#fond').css('-moz-background-size', 'cover');
	$('#fond').css('-o-background-size', 'cover');
	$('#fond').css('background-size', 'cover');
		setTimeout(changeImg, 3000);
		/* image rotator */
		function changeImg(){
			$('#fond').animate({opacity: 0}, 1000);
			$('#fond2').css('background','url(' + preloadArr[currImg++%preloadArr.length].src +') no-repeat center center fixed');
			$('#fond2').css('-webkit-background-size', 'cover');
			$('#fond2').css('-moz-background-size', 'cover');
			$('#fond2').css('-o-background-size', 'cover');
			$('#fond2').css('background-size', 'cover');
			document.cookie = "val=" + currImg;
			$('#fond2').animate({opacity: 1}, 1000);
			setTimeout(changeImg2, 5000);
		}

		function changeImg2(){
			$('#fond2').animate({opacity: 0}, 1000);
			$('#fond').css('background','url(' + preloadArr[currImg++%preloadArr.length].src +') no-repeat center center fixed');
			$('#fond').css('-webkit-background-size', 'cover');
			$('#fond').css('-moz-background-size', 'cover');
			$('#fond').css('-o-background-size', 'cover');
			$('#fond').css('background-size', 'cover');
			document.cookie = "val=" + currImg;
			$('#fond').animate({opacity: 1}, 1000);
			setTimeout(changeImg, 5000);
		}
	});
