$('#textchat').ready(function() {
	$('#textchat').scrollTop($('#textchat')[0].scrollHeight);
});

$('#chat').ready(function() {
	var url = window.location.search;
	var acc = url.substring(url.lastIndexOf("=")+1);
	setInterval(function() {
		actuchat(acc);
	}, 4000);
});
