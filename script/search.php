<?php
if (!strpos($_SERVER['PHP_SELF'], "search.php")) {
include_once('ajax/aedit.php');

if (!nullif($_SESSION['login']) && proreq($_GET['page']) === 'search') {
	$log = $bdd->query("SELECT DISTINCT `tagname` FROM tags ORDER BY `tagname` ASC;");
	$tags = $log->fetchAll();
	$restag = "";
	foreach ($tags as $key => $value) {
		$restag .= "<option value='".$value['tagname']."'>".$value['tagname']."</option>";
	}
	?>
	<div id='profile' style='height:auto;'>
		<div id="searchbar">
			<input id='searchpeop' type="text" name="name" placeholder="Search People..."><span onClick="searchpeople();" id='searchbut'>Search!</span>
		</div>
		<div id='adsearch'>
			- Age between <input id='age1' type="number" name="name"value="18" min="18" max='100'> and <input id='age2' type="number" name="name" value="100" min="18" max='100'></br>
			- Popularity Score between <input id='pop1' type="number" name="name" value="0" min='0' max='1000'> and <input id='pop2' type="number" name="name" value="1000" min='0'></br>
			- Number of tags <input id='searchtag' type="number" name="name" min='0' max='5' value='0'><span id='nbrtag'></span></br>
			- Localisation (Postal Code) <input id='searchloc' type="text" name="name" value=""><input id='gps' type="image" src="resources/gps.png" name="gps" value="gps" onclick="geoloc('search');"></br>
			<input type="button" name="name" value="Advanced Search" onclick="search();">
		</div>
		<div>
		<select id='filtra'>
			<option selected value="Name">Name</option>
			<option value="Age">Age</option>
			<option value="Popularity Score">Popularity Score</option>
			<option value="Tags">Tags</option>
			<option value="Localisation">Localisation</option>
		</select>
		<select id='order'>
			<option selected value="ASC">Asc</option>
			<option value="DESC">Desc</option>
		</select>
	</div>
		<div id='result'></div>
		<div id="arrow">
			<input id='prev' type="button" name="Prev" value="Prev"> - <input id='next' type="button" name="Next" value="Next">
		</div>
	</div>
	<script src="js/search.js"></script>
	<script type="text/javascript">
	$('#searchtag').change(function () {
		var content = "<?php Print($restag); ?>";
		var i = 1;
		document.getElementById('nbrtag').innerHTML = "";
		while (i <= document.getElementById('searchtag').value) {
			document.getElementById('nbrtag').innerHTML += ' - <select id="tag'+i+'">'+content+'</select>';
			i++;
			if (i > 5) {
				break;
			}
		}
	});
	</script>
<?php
}
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}?>
