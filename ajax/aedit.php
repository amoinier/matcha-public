<?php
if (!strpos($_SERVER['PHP_SELF'], "aedit.php")) {
$gi = geoip_open(realpath("geoloc/GeoLiteCity.dat"),GEOIP_STANDARD);
$record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']); ?>

<script type="text/javascript">

//--- GeoLoc Button ---

function geoloc($type) {
	var lat = '<?php echo $record->latitude;?>';
	var lon = '<?php echo $record->longitude;?>';
	var i = 0;

	if (navigator.geolocation) {
		i = 1;
		document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation loading ...";
		document.getElementById('errbox').style.display = 'block';
		$('#errbox').delay(5000).fadeOut('slow');
		navigator.geolocation.getCurrentPosition(function(position) {
			i = 1;
			$('#errbox').delay(5000).fadeOut('slow');
			$.get( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+","+position.coords.longitude+"&key=AIzaSyD0ZalqHjHUb6yFPuGuRJeuZXtSYyXqc98", function( data ) {
				var txt = data.results[0].formatted_address;
				/([0-9]{5})/.exec(txt);
				var postalco = RegExp.$1;
				$.post("request/request.php", {pos: data.results[0].formatted_address, postalcode: postalco, lat: position.coords.latitude, lon: position.coords.longitude, submit: $type}, function(result){
					if (result == "reussi") {
						if (document.getElementById('placeedit')) {
							document.getElementById('placeedit').value = data.results[0].formatted_address;
						}
						document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation change!";
						if (document.getElementById('errbox2')) {
							document.getElementById('errbox2').style.display = 'none';
						}
						document.getElementById('errbox').style.display = 'block';
						$('#errbox').delay(5000).fadeOut('slow');
					}
					else if (result == "postal") {
						if (document.getElementById('searchloc')) {
							document.getElementById('searchloc').value = postalco;
							document.getElementById("searchloc").focus();
						}
						document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation Ok!";
						if (document.getElementById('errbox2')) {
							document.getElementById('errbox2').style.display = 'none';
						}
					}
					else {
						document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation fail!";
						if (document.getElementById('errbox2')) {
							document.getElementById('errbox2').style.display = 'none';
						}
						document.getElementById('errbox').style.display = 'block';
						$('#errbox').delay(5000).fadeOut('slow');
					}
				});
			});
		});
	}
	else if (lat && lon && i == 0) {
		i = 1;
		document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation loading ...";
		document.getElementById('errbox').style.display = 'block';
		$('#errbox').delay(5000).fadeOut('slow');
		$.get( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lon+"&key=AIzaSyD0ZalqHjHUb6yFPuGuRJeuZXtSYyXqc98", function( data ) {
			var txt = data.results[0].formatted_address;
			/([0-9]{5})/.exec(txt);
			var postalco = RegExp.$1;
			$.post("request/request.php", {pos: data.results[0].formatted_address, postalcode: postalco, lat: lat, lon: lon}, function(result){
				if (result == "reussi") {
					if (document.getElementById('placeedit')) {
						document.getElementById('placeedit').value = data.results[0].formatted_address;
					}
					document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation change!";
					if (document.getElementById('errbox2')) {
						document.getElementById('errbox2').style.display = 'none';
					}
					document.getElementById('errbox').style.display = 'block';
					$('#errbox').delay(5000).fadeOut('slow');
				}
				else if (result == "postal") {
					if (document.getElementById('searchloc')) {
						document.getElementById('searchloc').value = postalco;
						document.getElementById("searchloc").focus();
					}
					document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation Ok!";
					if (document.getElementById('errbox2')) {
						document.getElementById('errbox2').style.display = 'none';
					}
				}
				else {
					document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation fail!";
					if (document.getElementById('errbox2')) {
						document.getElementById('errbox2').style.display = 'none';
					}
					document.getElementById('errbox').style.display = 'block';
					$('#errbox').delay(5000).fadeOut('slow');
				}
			});
		});
	}
	else {
		document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Localisation fail!";
		if (document.getElementById('errbox2')) {
			document.getElementById('errbox2').style.display = 'none';
		}
		document.getElementById('errbox').style.display = 'block';
		$('#errbox').delay(5000).fadeOut('slow');
	}
}


</script>
<?php geoip_close($gi);
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}?>
