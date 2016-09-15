<?php
if (!strpos($_SERVER['PHP_SELF'], "req_add.php")) {
	$gi = geoip_open(realpath("geoloc/GeoLiteCity.dat"),GEOIP_STANDARD);
	$record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']);
 ?>

<script type="text/javascript">

var lat = '<?php echo $record->latitude;?>';
var lon = '<?php echo $record->longitude;?>';
var i = 0;

if (navigator.geolocation) {
	i = 1;
	navigator.geolocation.getCurrentPosition(function(position) {
		$.get( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+","+position.coords.longitude+"&key=AIzaSyD0ZalqHjHUb6yFPuGuRJeuZXtSYyXqc98", function( data ) {
			var txt = data.results[0].formatted_address;
			/([0-9]{5})/.exec(txt);
			var postalco = RegExp.$1;
			$.post("request/request.php", {pos: data.results[0].formatted_address, postalcode: postalco, lat: position.coords.latitude, lon: position.coords.longitude, submit: 'edit'}, function(result){
				if (document.getElementById('textgps')) {
					document.getElementById('textgps').value = data.results[0].formatted_address;
				}
			});
		});
	});
}
else if (lat && lon && i == 0) {
	i = 1;
	$.get( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lon+"&key=AIzaSyD0ZalqHjHUb6yFPuGuRJeuZXtSYyXqc98", function( data ) {
		var txt = data.results[0].formatted_address;
		/([0-9]{5})/.exec(txt);
		var postalco = RegExp.$1;
		$.post("request/request.php", {pos: data.results[0].formatted_address, postalcode: postalco, lat: lat, lon: lon, submit: 'edit'}, function(result){
			if (document.getElementById('textgps')) {
				document.getElementById('textgps').value = data.results[0].formatted_address;
			}
		});
	});
}
</script>
<?php geoip_close($gi);
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}?>
