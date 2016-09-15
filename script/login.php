<?php if (!strpos($_SERVER['PHP_SELF'], "login.php")) { ?>
<div class="login">
	<label for="login">Login :</label>
	<input id="loglogin" type="text" name="login" value=""></br>
	<label for="pass">Password :</label>
	<input id="logpass" type="password" name="pass" value=""></br></br></br>
	<input type="submit" name="submit" value="Connect" onClick="logg();">
</div>
<?php }
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
} ?>
