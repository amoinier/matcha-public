$('#text-tag').change(function () {
	document.getElementById('newtag').selectedIndex = 0;
});

$('#newtag').change(function () {
	document.getElementById('text-tag').value = "";
});
