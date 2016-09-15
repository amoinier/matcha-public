<?php
if (!strpos($_SERVER['PHP_SELF'], "recovery.php")) {
$ok = 0;
if (nullif($_SESSION['login']) && proreq($_POST['submit']) === 'Reinitialise') {
	mailrecovery($_POST, $bdd);
}
else if (nullif($_SESSION['login']) && proreq($_POST['submit']) === 'Change') {
	if (passrecovery($_POST, $bdd)) {
		$ok = 1;
		?>
		<div class='login'>
			<div id='error'>Your password is modified. You will be redirectd in 5 seconds ...</br></br>
			</div>
			<meta http-equiv="refresh" content='5;URL=index.php'/>
		</div>
		<?php
	}
}

if (nullif($_SESSION['login']) && proreq($_GET['page']) === 'rec' && (nullif(proreq($_GET['login'])) || nullif(proreq($_GET['id'])))) {?>
	<div class='login'>
		<form action="index.php?page=rec" method="post">
			<label for="login">Email :</label>
			<input type="text" name="mail" value=""></br></br>
			<input type="submit" name="submit" value="Reinitialise">
		</form>
		<div id="error"><?php echo $_SESSION['error'];
		$_SESSION['error'] = "";?></div>
	</div><?php
}
else if (nullif($_SESSION['login']) && proreq($_GET['page']) === 'rec' && !nullif(proreq($_GET['login'])) && !nullif(proreq($_GET['id'])) && $ok == 0) {
	$log = $bdd->prepare("SELECT `login`, `mail` FROM `users` WHERE `login` LIKE :login");
	$log->execute(array('login' => $_GET['login']));
	$res = $log->fetch();
	if (hash(whirlpool, proreq($res['mail'])) === proreq($_GET['id'])) { ?>
		<div class='login'>
			<form action="index.php?page=rec&login=<?= proreq($_GET['login'])?>&id=<?= proreq($_GET['id'])?>" method="post">
				<label for="login"><?= $res['login']; ?></label>
				<label for="password"><br />Password :</label>
				<input type="password" id='pass' name="pass" value="">
				<label for="password">Retape password :</label>
				<input type="password" id='pass2' name="pass2" value="">
				<input type="hidden" id='mail' name='mail' value=<?php echo $res['mail'];?>></br></br>
				<input type="submit" name="submit" value="Change">
			</form>
			<?php if ($_SESSION['error']) { ?>
				<div id="error"><?php echo $_SESSION['error'];
				$_SESSION['error'] = "";?></div></div>
		<?php }
		}
	}
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}
?>
