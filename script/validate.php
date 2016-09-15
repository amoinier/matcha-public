<?php
if (!strpos($_SERVER['PHP_SELF'], "validate.php")) {
if (nullif($_SESSION['login']) && proreq($_GET['page']) === 'valid' && !nullif(proreq($_GET['id'])) && !nullif(proreq($_GET['login']))) {
	if (validateacc($_GET, $bdd)) {
		$message = 'Your account is realised. You will be redirected in 5 secondes ...';
	}
	else {
		$message = "The link is dead or this account is already activate ...";
	}
	?>
	<div class='login'>
		<div id='error'><?php echo $message; unset($message);?></br></br>
		</div>
		<meta http-equiv="refresh" content='5;URL=index.php'/>
	</div><?php
}
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}?>
