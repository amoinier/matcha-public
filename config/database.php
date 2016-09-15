<?php
if (!strpos($_SERVER['PHP_SELF'], "database.php")) {
	$DB_DSN = 'mysql:host=amoinier.fr:4040;charset=utf8';
	$DB_USER = '';
	$DB_PASSWORD = '';
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}
?>
