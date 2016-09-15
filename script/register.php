<?php if (!strpos($_SERVER['PHP_SELF'], "register.php")) { ?>
<div class='register'>
	<label for="login">Login :</label>
	<input type="text" id='login' name="login">
	<label for="password">Password :</label>
	<input type="password" id='pass' name="pass">
	<label for="password2">Retape your password :</label>
	<input type="password" id='pass2' name="pass2">
	<label for="email">Email :</label>
	<input type="email" id='mail' name="mail">
	<label for="login">Last name :</label>
	<input type="text" id='surname' name="surname">
	<label for="login">First name :</label>
	<input type="text" id="regname" name="name"><br/><br/><br/>
	<input type="submit" name="submit" value="Register" onClick="regg();">
</div>
<?php }
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
} ?>
