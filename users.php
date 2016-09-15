<?php
include ('config/setup.php');

//CREATE-----------------------------------------------------------------------------------------------------------------------

function create($post, $bdd) {
	$pass1 = proreq($post['pass']);
	$pass2 = proreq($post['pass2']);
	$login = proreq($post['login']);
	$mail = proreq($post['mail']);
	$surname = proreq($post['surname']);
	$name = proreq($post['name']);

	$log = $bdd->prepare("SELECT `login` FROM users WHERE `login` LIKE :login;");
	$log->execute(array('login' => $login));
	$result = $log->fetch();
	if (!nullif($pass1) && !nullif($pass2) && !nullif($login) && !nullif($mail) && !nullif($surname) && !nullif($name)) {
		if ($result['login'] !== $login) {
			if ($pass1 === $pass2 && strlen($pass1) >= 6) {
				$log = $bdd->prepare("SELECT `mail` FROM users WHERE `mail` LIKE :mail;");
				$log->execute(array('mail' => $mail));
				$result = $log->fetch();
				if ($result['mail'] !== $mail && preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $mail)) {
					if ($login === $post['login'] && $name === $post['name'] && $surname === $post['surname']) {
						$log = $bdd->prepare('INSERT INTO users (`login`, `passwd`, `mail`, `surname`, `name`, `popscore`, `validate`) VALUES (:login, :pass, :mail, :surname, :name, "0", "0");');
						$log->execute(array('login' => $login, 'pass' => hash(whirlpool, $pass1), 'mail' => $mail, 'surname' => $surname, 'name' => $name));
						$headers = 'From: SoulMate' . "\r\nMIME-version: 1.0\nContent-type: text/html; charset= iso-8859-1\n";
						$code = hash(whirlpool, $mail);
						$message = "<html><body><a href='http://localhost:8080".substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF']) - strlen(strstr(substr($_SERVER['PHP_SELF'], 1), "/")))."/index.php?page=valid&login=".$login."&id=".$code."'>Activation</a></html></body>";
						mail($mail, "Activate your account", $message, $headers);
						$_SESSION['error'] = "Register successful! An email will sent you.";
						return (true);
					}
					else {
						$_SESSION['error'] = "Characters error.";
						return (false);
					}
				}
				else {
					$_SESSION['error'] = "This email is already uses or it didn't work.";
					return (false);
				}
			}
			else {
				$_SESSION['error'] = "Passwords don't match or too short (min. 6 characters).";
				return (false);
			}
		}
		else {
			$_SESSION['error'] = "This account already exists.";
			return (false);
		}
	}
	else {
		$_SESSION['error'] = "A field is empty.";
		return (false);
	}
}

//VALIDATEACC-----------------------------------------------------------------------------------------------------------------------

function validateacc($get, $bdd) {
	$log = $bdd->prepare("SELECT `mail`, `validate` FROM `users` WHERE `login` LIKE :login;");
	$log->execute(array('login' => $get['login']));
	$result = $log->fetch();
	if (!nullif($result) && hash(whirlpool, htmlspecialchars($result['mail'])) === htmlspecialchars($get['id']) && !$result['validate']) {
		$log = $bdd->prepare("SELECT * FROM `users` WHERE `mail` LIKE :mail");
		$log->execute(array('mail' => htmlspecialchars($result['mail'])));
		$ok = $log->fetch();
		$log = $bdd->prepare("UPDATE `users` SET `validate` = '1' WHERE `users`.`id` = :id");
		$log->execute(array('id' => $ok['id']));
		$_SESSION['login'] = $ok['login'];
		$_SESSION['mail'] = $ok['mail'];
		$_SESSION['surname'] = $ok['surname'];
		$_SESSION['name'] = $ok['name'];
		$_SESSION['sexe'] = $ok['sexe'];
		$_SESSION['sexualor'] = $ok['sexualor'];
		$_SESSION['birthdate'] = $ok['birthdate'];
		$_SESSION['locali'] = $ok['locali'];
		$_SESSION['popscore'] = $ok['popscore'];
		$_SESSION['lastvis'] = $ok['lastvis'];
		$_SESSION['bio'] = $ok['bio'];
		return (true);
	}
	else {
		return (false);
	}
}

//MAILRECOVERY-----------------------------------------------------------------------------------------------------------------------

function mailrecovery($post, $bdd) {
	$mail = $post['mail'];

	$log = $bdd->prepare("SELECT `login`, `mail` FROM users WHERE `mail` LIKE :mail;");
	$log->execute(array('mail' => $mail));
	$result = $log->fetch();
	if ($result['mail'] === $mail) {
		$headers = 'From: SoulMate' . "\r\nMIME-version: 1.0\nContent-type: text/html; charset= iso-8859-1\n";
		$message = "<html><body><a href='http://localhost:8080".substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF']) - strlen(strrchr($_SERVER['PHP_SELF'], "/")))."/index.php?page=rec&login=".$result['login']."&id=".hash(whirlpool, $mail)."'>Recovery</a></html></body>";
		mail($mail, "Change your password", $message, $headers);
		$_SESSION['error'] = "An email will sent you to change your password.";
		return (true);
	}
	else {
		$_SESSION['error'] = "This account doesn't exists.";
		return (false);
	}
}

//PASSRECOVERY-----------------------------------------------------------------------------------------------------------------------

function passrecovery($post, $bdd) {
	$mail = htmlspecialchars($post['mail']);
	$submit = htmlspecialchars($post['submit']);
	$pass = htmlspecialchars($post['pass']);
	$pass2 = htmlspecialchars($post['pass2']);


	if ($submit === 'Change') {
		if ($pass === $pass2 && !strchr($pass, "'") && strlen($pass) >= 6) {
			$log = $bdd->prepare("SELECT `id` FROM `users` WHERE `mail` LIKE :mail");
			$log->execute(array('mail' => $mail));
			$ok = $log->fetch();
			$log = $bdd->prepare("UPDATE `users` SET `passwd` = :passwd WHERE `users`.`id` = :id");
			$log->execute(array('passwd' => hash(whirlpool, $pass), 'id' => $ok['id']));
			return (true);
		}
		else {
			$_SESSION['error'] = "A field is empty, fields don't match or password have less than 6 characters.";
			return (false);
		}
	}
}

//LOGIN-----------------------------------------------------------------------------------------------------------------------

function login($post, $bdd) {
	$login = htmlspecialchars($post['login']);
	$pass = htmlspecialchars($post['pass']);
	$submit = htmlspecialchars($post['submit']);

	if ($submit === 'Connect' && !nullif($login) && !nullif($pass)) {
		$log = $bdd->prepare("SELECT * FROM users WHERE `login` LIKE :login;");
		$log->execute(array('login' => $login));
		$result = $log->fetch();
		if ($result['login'] === $login)
		{
			if (hash(whirlpool, $pass) === $result['passwd']) {
				if ($result['validate'] == 1) {
					$_SESSION['id'] = $result['id'];
					$_SESSION['login'] = $login;
					$_SESSION['mail'] = $result['mail'];
					$_SESSION['surname'] = $result['surname'];
					$_SESSION['name'] = $result['name'];
					$_SESSION['sexe'] = $result['sexe'];
					$_SESSION['sexualor'] = $result['sexualor'];
					$_SESSION['birthdate'] = $result['birthdate'];
					$_SESSION['locali'] = $result['locali'];
					$_SESSION['postalcode'] = $result['postalcode'];
					$_SESSION['popscore'] = $result['popscore'];
					$_SESSION['lastvis'] = $result['lastvis'];
					$_SESSION['bio'] = $result['bio'];
					$_SESSION['pdp'] = $result['pdp'];
					$_SESSION['longitude'] = $result['longitude'];
					$_SESSION['latitude'] = $result['latitude'];
					return (true);
				}
				else {
					$_SESSION['error'] = "Your account isn't validate.";
					return (false);
				}
			}
			else {
				$_SESSION['error'] = "Your password doesn't match with your login. <a id='forget' href='index.php?page=rec'>Forget password ?</a>";
				return (false);
			}
		}
		else {
			$_SESSION['error'] = "This account doesn't exists.";
			return (false);
		}
	}
	else {
		$_SESSION['error'] = "A field is empty.";
		return (false);
	}
}

//LOGOUT-----------------------------------------------------------------------------------------------------------------------

function logout() {
	if (!nullif($_SESSION['login'])) {
		session_destroy();
	}
}

//UPLOAD-----------------------------------------------------------------------------------------------------------------------

function upload($files) {
	if (!file_exists("upload"))
		mkdir("upload");
	$fichier = basename($files['upload-photo']['name']);
	$extensions = array('.png', '.jpg', '.jpeg', '.JPG');
	$extension = strrchr($files['upload-photo']['name'], '.');
	if(in_array($extension, $extensions)) {
		if(filesize($files['upload-photo']['tmp_name']) < 1000000){
			$fichier = strtr($fichier,
			'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
			'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
			$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
			$name = "upload/".time().$fichier;
			if(move_uploaded_file($files['upload-photo']['tmp_name'], $name)) {
				$_SESSION['upload'] = $name;
				return (true);
			}
			else {
				return (false);
			}
		}
		else {
			return (false);
		}
	}
	else {
		return (false);
	}
}

//POPULARITY SCORE----------------------------------------------------------------------------------------------------------

function pop_score($bdd) {
	$log = $bdd->prepare("UPDATE users SET `lastvis` = :dates WHERE `login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'dates' => date('Y-m-d H:i:s')));
	$_SESSION['lastvis'] = date('Y-m-d H:i:s');
	$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liked` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login']));
	$like = $log->fetchAll();
	$log = $bdd->prepare("SELECT `visitor` FROM `visit` WHERE `visited` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login']));
	$visit = $log->fetchAll();
	$pop = (count($like) * 5 + count($visit) * 2);
	$log = $bdd->prepare("UPDATE users SET `popscore` = :score WHERE `login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'score' => intval($pop)));
	$_SESSION['popscore'] = intval($pop);
}

//OTHERS-----------------------------------------------------------------------------------------------------------------------

function proreq($str) {
	return (htmlspecialchars($str));
}

function nullif($var) {
	if (!isset($var) || is_null($var) || empty($var)) {
		return (true);
	}
	else {
		return (false);
	}
}
?>
