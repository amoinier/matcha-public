var stoppage = 0;

function capitalise(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

function seenotif() {
	if (!document.getElementById('seenoti').style.display || document.getElementById('seenoti').style.display == 'none') {
		document.getElementById('seenoti').style.display = 'block';
	}
	else {
		document.getElementById('seenoti').style.display = 'none';
	}
}

//--- Register Button ---

function regg() {
	$.post("request/request.php", {
		login: document.getElementById('login').value,
		pass: document.getElementById('pass').value,
		pass2: document.getElementById('pass2').value,
		mail: document.getElementById('mail').value,
		surname: document.getElementById('surname').value,
		name: document.getElementById('regname').value,
		submit: 'Register'
	}, function(result) {
			if (result == "Register successful! An email will sent you.") {
				document.getElementById('login').value = "";
				document.getElementById('pass').value = "";
				document.getElementById('pass2').value = "";
				document.getElementById('mail').value = "";
				document.getElementById('surname').value = "";
				document.getElementById('regname').value = "";
			}
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>" + result;
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
	});
}

//--- Login Button ---

function logg() {
	$.post("request/request.php", {
		login: document.getElementById('loglogin').value,
		pass: document.getElementById('logpass').value,
		submit: 'Connect'
	}, function(result){
		if (result) {
			if (result == "reussi") {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Login successful!";
			}
			else {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>" + result;
			}
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
			if (result == "reussi") {
				location.reload();
			}
		}
	});
}

//--- Logout Button ---

function logoutt() {
	$.post("request/request.php", {
		submit: 'Logout'
	}, function(result){
		if (result == "reussi") {
			window.location.href = "index.php";
		}
	});
}

//--- Report Button ---

function report() {
	var url = window.location.search;
	var acc = url.substring(url.lastIndexOf("=")+1);
	$.post("request/request.php", {
		visited: acc,
		butt: "Report"
	}, function(result){
		if (result == "report" || result == "unreport") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>You "+result+" this profile!";
			if (result == 'report') {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>You "+result+" this profile like a fake!"
			}
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
			if (result == "unreport") {
				document.getElementById('report').value = "Report";
			}
			else {document.getElementById('report').value = "Unreport";}
		}
	});
}

//--- Block Button ---

function block() {
	var url = window.location.search;
	var acc = url.substring(url.lastIndexOf("=")+1);
	$.post("request/request.php", {
		visited: acc,
		butt: "Block"
	}, function(result){
		if (result == "block" || result == "unblock") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>You "+result+" this profile!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
			if (result == "unblock") {
				document.getElementById('block').value = "Block";
			}
			else {document.getElementById('block').value = "Unblock";}
		}
	});
}

//--- Like Button ---

function like() {
	var url = window.location.search;
	var acc = url.substring(url.lastIndexOf("=")+1);
		$.post("request/request.php", {
			visited: acc,
			butt: "Like"
		}, function(result){
			var resplit = result.split(',');
			if (resplit[0] == "like" || resplit[0] == "unlike") {
				document.getElementById('propopu').innerHTML = 'Popularity Score : ' + resplit[1];
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>You "+resplit[0]+" this profile!";
				if (document.getElementById('errbox2')) {
					document.getElementById('errbox2').style.display = 'none';
				}
				$('#errbox').fadeIn(100);
				$('#errbox').delay(5000).fadeOut('slow');
				if (resplit[0] == "unlike") {
					document.getElementById('likeb').value = "Like";
				}
				else {
					document.getElementById('likeb').value = "Unlike";
				}
				if (resplit[2] == 'chat') {
					window.location.href = "index.php?page=profile&login="+acc;
				}
				else {
					if (document.getElementById('chat')) {
						document.getElementById('chat').style.display = 'none';
					}
				}
			}
			else {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>"+result;
				$('#errbox').fadeIn(100);
				$('#errbox').delay(5000).fadeOut('slow');
			}
		});
}

//--- Biography Button ---

function editbiog() {
	$.post("request/request.php", {
		bio: document.getElementById('edittext').value,
		submit: "biog"
	}, function(result){
		if (result == "reussi") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Biography was modified!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
		}
	});
}

//--- Name Button ---

function editname() {
	$.post("request/request.php", {
		surname: document.getElementById('inpsurname').value,
		name:document.getElementById('inpname').value,
		submit: "editname"
	}, function(result){
		if (result == "reussi") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Name was modified!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
		}
	});
}

//--- Pdp Button ---

function editpdp($id) {
	$.post("request/request.php", {
		idpic: document.getElementById('inp'+$id).value,
		submit: "editpdp"
	}, function(result) {
		if (result == "reussi") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Profil photo was modified!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
			document.getElementById('pdpp').src = document.getElementById('pdp'+$id).src;
		}
	});
}

//--- Delete Pics Button ---

function delpics($id) {
	$.post("request/request.php", {
		idpic: document.getElementById('inp'+$id).value,
		submit: "imgdel"
	}, function(result) {
		if (result == "reussi" || result == "reussipdp") {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Photo was deleted!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
			document.getElementById('pdp'+$id).style.display = 'none';
			document.getElementById('delpdp'+$id).style.display = 'none';
			if (result == "reussipdp") {
				document.getElementById('pdpp').src = 'resources/profil.png';
			}
		}
	});
}

//--- Info Edit Button ---

function editinfo() {
	var geoloc = "";
	var postalco = "";
	var lat = "";
	var lon = "";
	if (document.getElementById('placeedit').value) {
	$.get("https://maps.googleapis.com/maps/api/geocode/json?address="+document.getElementById('placeedit').value.replace(/ /g, "+")+"&region=fr&key=AIzaSyD0ZalqHjHUb6yFPuGuRJeuZXtSYyXqc98", function( data ) {
			geoloc = data.results[0].formatted_address;
			/([0-9]{5})/.exec(geoloc);
			postalco = RegExp.$1;
			lat = data.results[0].geometry.location.lat;
			lon = data.results[0].geometry.location.lng;
		$.post("request/request.php", {
			email: document.getElementsByName('email')[0].value,
			email2: document.getElementsByName('email2')[0].value,
			birthdate: document.getElementsByName('birthdate')[0].value,
			sexe: document.getElementsByName('sexe')[0].value,
			sexualor: document.getElementsByName('sexualor')[0].value,
			geoloc: geoloc,
			postalcode: postalco,
			lon: lon,
			lat: lat,
			submit: "editinfo"
		}, function(result){
			var resplit = result.split('+');
			if (resplit[0] && resplit[0] != "Mail already use!") {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/> Infos were modified!";
				document.getElementById('placeedit').value = data.results[0].formatted_address;
				if (document.getElementById('errbox2')) {
					document.getElementById('errbox2').style.display = 'none';
				}
				$('#errbox').fadeIn(100);
				$('#errbox').delay(5000).fadeOut('slow');
			}
			else {
				document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/> Error in field!";
				$('#errbox').fadeIn(100);
				$('#errbox').delay(5000).fadeOut('slow');
			}
		});
	});
}
else {
	document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/> Location can't be empty!";
	$('#errbox').fadeIn(100);
	$('#errbox').delay(5000).fadeOut('slow');
}
}

//--- Tags Edit Button ---

function edittags() {
	$.post("request/request.php", {
		newtag: document.getElementById('newtag').value,
		texttag: document.getElementById('text-tag').value,
		submit: "edittags"
	}, function(result){
		if (result != "error" && result != "") {
			document.getElementById('text-tag').value = "";
			document.getElementById('newtag').selectedIndex = 0;
			document.getElementById('taglist').innerHTML += "<input id='del" + result + "' type='image' src='resources/del.png' name='delete' value='del' onclick=\"deltag(\'"+ result +"\');\">";
			document.getElementById('taglist').innerHTML += "<span id='" + result +"'>" + result +" - </span>";
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Tags was added!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
		}
		else {
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Error in tags";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
		}
	});
}

//--- Tags Delete Button ---

function deltag($tag) {
	$.post("request/request.php", {
		tag: $tag,
		submit: "deltag"
	}, function(result){
		if (result == "reussi") {
			document.getElementById($tag).style.display = 'none';
			document.getElementById('del'+$tag).style.display = 'none';
			document.getElementById('errbox').innerHTML = "<img src='resources/imgdel.png' alt='imgdel'/>Tag was deleted!";
			if (document.getElementById('errbox2')) {
				document.getElementById('errbox2').style.display = 'none';
			}
			$('#errbox').fadeIn(100);
			$('#errbox').delay(5000).fadeOut('slow');
		}
	});
}

//--- Notification ---

function notification() {
	$.post("request/request.php", {
		submit: "notification"
	}, function(result){
		var resplit = result.split('+');
		if (resplit[1]) {
			document.getElementById('bulnot').innerHTML = resplit[1];
		}
		if (resplit[0]) {
			document.getElementById("bulnot").style.display = 'block';
			if (document.getElementById('seenoti').innerHTML != resplit[0]) {
				document.getElementById('seenoti').innerHTML = resplit[0];
			}
		}
		else {
			document.getElementById('seenoti').innerHTML = "<div class='notimess'>No Notification!</div>";
		}
	});
}

//--- Delete Notification ---

function deletenoti($idnoti) {
	document.getElementById('seenoti').style.display = 'block';
	$.post("request/request.php", {
		idnoti: $idnoti,
		content: $('#'+$idnoti).attr("name"),
		submit: "deletenoti"
	}, function(result){
		if (result == 'reussi') {
			document.getElementById('bulnot').innerHTML = parseInt(document.getElementById('bulnot').innerHTML) - 1;
			document.getElementById($idnoti).remove();
			document.getElementById('seenoti').style.display = 'block';
			if (document.getElementById('seenoti').innerHTML == "") {
				document.getElementById('seenoti').innerHTML = "<div class='notimess'>No Notification</div>";
				document.getElementById("bulnot").style.display = 'none';
			}
		}
	});
}

//--- Zoom Pics ---

function zoompics($img) {
	document.getElementById('fond2').style.opacity = 1;
	$('#fond2').css({zIndex: 10});
	document.getElementById('fond2').style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
	document.getElementById('zoomp').src = $img;
	document.getElementById('zoomp').style.width = '40vw';
	document.getElementById('zoomp').style.height = 'auto';
	$('#zoomp').css({display: 'block'});
	$('#zoomp').css({margin: '0 auto'});
	$('#zoomp').css('margin-top', '0');
	document.getElementById('fond2').style.display = 'block';
	document.getElementById("fond2").onclick = function fun() {
		document.getElementById('fond2').style.display = 'none';
   }
}

//--- Normal Search ---

function searchpeople() {
	$.post("request/request.php", {
		name: document.getElementById('searchpeop').value,
		submit: "normsearch"
	}, function(result){
		document.getElementById('result').innerHTML = result;
	});
}

//--- Advanced Search ---

function adsearch($filtre, $nbpage) {
	var $tag1 = document.getElementById('tag1') ? document.getElementById('tag1').value : "";
	var $tag2 = document.getElementById('tag2') ? document.getElementById('tag2').value : "";
	var $tag3 = document.getElementById('tag3') ? document.getElementById('tag3').value : "";
	var $tag4 = document.getElementById('tag4') ? document.getElementById('tag4').value : "";
	var $tag5 = document.getElementById('tag5') ? document.getElementById('tag5').value : "";
	$.post("request/request.php", {
		age1: document.getElementById('age1').value,
		age2: document.getElementById('age2').value,
		pop1: document.getElementById('pop1').value,
		pop2: document.getElementById('pop2').value,
		searchloc: document.getElementById('searchloc').value,
		nbrtag: document.getElementById('searchtag').value,
		filtre: $filtre,
		tag1: $tag1,
		tag2: $tag2,
		tag3: $tag3,
		tag4: $tag4,
		tag5: $tag5,
		nbpage: $nbpage,
		submit: "adsearch"
	}, function(result){
		var resplit = result.split('|');
		document.getElementById('result').innerHTML = resplit[0];
		document.getElementById('arrow').style.display = 'block';
		stoppage = resplit[1];
	});
}

//--- Discover ---

function discover($filtre, $nbpage) {
	var $tag1 = document.getElementById('tag1') ? document.getElementById('tag1').value : "";
	var $tag2 = document.getElementById('tag2') ? document.getElementById('tag2').value : "";
	var $tag3 = document.getElementById('tag3') ? document.getElementById('tag3').value : "";
	var $tag4 = document.getElementById('tag4') ? document.getElementById('tag4').value : "";
	var $tag5 = document.getElementById('tag5') ? document.getElementById('tag5').value : "";
	$.post("request/request.php", {
		age1: document.getElementById('age1').value,
		age2: document.getElementById('age2').value,
		pop1: document.getElementById('pop1').value,
		pop2: document.getElementById('pop2').value,
		searchloc: document.getElementById('searchloc').value,
		nbrtag: document.getElementById('searchtag').value,
		filtre: $filtre,
		tag1: $tag1,
		tag2: $tag2,
		tag3: $tag3,
		tag4: $tag4,
		tag5: $tag5,
		nbpage: $nbpage,
		submit: "discover"
	}, function(result){
		var resplit = result.split('|');
		$('#discopeo').ready(function() {
			document.getElementById('discopeo').innerHTML = resplit[0];
			document.getElementById('arrow').style.display = 'block';
			stoppage = resplit[1];
		});
	});
}

//--- Chat ---

function chat() {
	var url = window.location.search;
	var acc = url.substring(url.lastIndexOf("=")+1);
	var tt = document.getElementById('sendmess').value;
	if (tt && tt.trim()) {
		$.post("request/request.php", {
			message: document.getElementById('sendmess').value,
			submit: "sendmessage",
			convto: acc
		}, function(result){
			document.getElementById('textchat').value = result;
			document.getElementById('sendmess').value = "";
			$('#textchat').scrollTop($('#textchat')[0].scrollHeight);
		});
	}
}

//--- ActuChat ---

function actuchat($people) {
	$.post("request/request.php", {
		convto: $people,
		submit: "actuchat"
	}, function(result){
		document.getElementById('textchat').value = result;
		$('#textchat').scrollTop($('#textchat')[0].scrollHeight);
	});
}

//--- Close Error Box Button ---

function closeerr() {
	document.getElementById('errbox').style.display = 'none';
}

//--- Press Enter To Validate ---

$(document).ready(function() {
	if (document.getElementById('inpname')) {
		document.getElementById('inpname').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				editname();
			}
		});
	}
	if (document.getElementById('inpsurname')) {
		document.getElementById('inpsurname').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				editname();
			}
		});
	}
	if (document.getElementById('text-tags')) {
		document.getElementById('text-tag').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				edittags();
			}
		});
	}
	if (document.getElementById('loglogin')) {
		document.getElementById('loglogin').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				logg();
			}
		});
	}

	if (document.getElementById('logpass')) {
		document.getElementById('logpass').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				logg();
			}
		});
	}
	if (document.getElementById('name')) {
		document.getElementById('name').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				regg();
			}
		});
	}

	if (document.getElementById('pass')) {
		document.getElementById('pass').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				regg();
			}
		});
	}

	if (document.getElementById('searchpeop')) {
		document.getElementById('searchpeop').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				searchpeople();
			}
		});
	}

	if (document.getElementById('sendmess')) {
		document.getElementById('sendmess').addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				chat(document.getElementById('hiddenlogin').value);
				document.getElementById('sendmess').value = "";
			}
		});
	}
});
