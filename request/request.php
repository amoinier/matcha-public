<?php
session_start();

date_default_timezone_set("Europe/Paris");
require_once('../config/setup.php');
require_once('../users.php');

//--- Register ---

if (nullif($_SESSION['login']) && $_POST['submit'] === 'Register') {
	create($_POST, $bdd);
	echo $_SESSION['error'];
	$_SESSION['error'] = "";
}

//--- Login ---

if (nullif($_SESSION['login']) && $_POST['submit'] === 'Connect') {
	if (login($_POST, $bdd)) {
		echo "reussi";
	}
	else {
		echo $_SESSION['error'];
		$_SESSION['error'] = "";
	}
}

//--- Logout ---

if (!nullif($_SESSION['login']) && $_POST['submit'] === 'Logout') {
	logout();
	echo "reussi";
}

//--- Edit Name And Surname ---

if (!nullif($_POST) && $_POST['submit'] === 'editname' && !nullif($_POST['name']) && !nullif($_POST['surname'])) {
	$log = $bdd->prepare("UPDATE users SET `name` = :second WHERE `users`.`login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'second' => htmlspecialchars($_POST['name'])));
	$log = $bdd->prepare("UPDATE users SET `surname` = :second WHERE `users`.`login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'second' => htmlspecialchars($_POST['surname'])));
	$_SESSION['name'] = proreq($_POST['name']);
	$_SESSION['surname'] = proreq($_POST['surname']);
	echo "reussi";
}

//--- Edit Biography ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'biog' && !nullif(proreq($_POST['bio']))) {
	$log = $bdd->prepare("UPDATE users SET `bio` = :biog WHERE `users`.`login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'biog' => proreq($_POST['bio'])));
	$_SESSION['bio'] = proreq($_POST['bio']);
	echo "reussi";
}

//--- Edit Tags ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'edittags' && (!nullif(proreq($_POST['newtag']) || !nullif(proreq($_POST['texttag']))))) {
	if (nullif(proreq($_POST['newtag']))) {
		$log = $bdd->prepare("SELECT `tagname` FROM tags WHERE `login` LIKE :first AND `tagname` LIKE :texttag");
		$ff = strchr($_POST['texttag'], '#');
		if (nullif($ff)) {
			$texttag = '#'.str_replace('"', '', str_replace("'", '', $_POST['texttag']));
		}
		else {
			$texttag = str_replace('"', '', str_replace("'", '', $_POST['texttag']));
		}
		$log->execute(array('first' => $_SESSION['login'], 'texttag' => $texttag));
		if (nullif($log->fetch())) {
			$log = $bdd->prepare('INSERT INTO tags (`tagname`, `login`) VALUES (:texttag, :first);');
			$log->execute(array('first' => $_SESSION['login'], 'texttag' => $texttag));
			echo $texttag;
		}
		else {echo "error";}
	}
	else if (nullif(proreq($_POST['texttag'])) && !nullif(proreq($_POST['newtag']))) {
		$log = $bdd->prepare("SELECT `tagname` FROM tags WHERE `login` LIKE :first AND `tagname` LIKE :newtag");
		$newtag = proreq($_POST['newtag']);
		$log->execute(array('first' => $_SESSION['login'], 'newtag' => $newtag));
		if (nullif($log->fetch())) {
			$log = $bdd->prepare('INSERT INTO tags (`tagname`, `login`) VALUES (:newtag, :first);');
			$newtag = proreq($_POST['newtag']);
			$log->execute(array('first' => $_SESSION['login'], 'newtag' => $newtag));
			echo $newtag;
		}
		else {echo "error";}
	}
	else {
		echo "error";
	}
}

//--- Delete Tags ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'deltag' && !nullif(proreq($_POST['tag']))) {
	$log = $bdd->prepare('DELETE FROM tags WHERE `tagname` LIKE :tag AND `login` LIKE :first;');
	$log->execute(array('first' => $_SESSION['login'], 'tag' => $_POST['tag']));
	echo "reussi";
}

//--- Edit Info (Like Birthdate) ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'editinfo') {
	$ok = 0;
	if (proreq($_POST['email']) !== $_SESSION['mail'] && proreq($_POST['email']) === proreq($_POST['email2']) && preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', htmlspecialchars($_POST['email']))) {
		$log = $bdd->prepare("SELECT `mail` FROM `users` WHERE `mail` LIKE :first AND `login` NOT LIKE :second");
		$log->execute(array('first' => $_POST['email'], 'second' => $_SESSION['login']));
		if (nullif($log->fetch())) {
			$log = $bdd->prepare("UPDATE users SET `mail` = :mail WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'mail' => $_POST['email']));
			$_SESSION['mail'] = proreq($_POST['email']);
			$ok = 1;
		}
		else {
			echo "Mail already use!+";
		}
	}
	if ($_POST['birthdate'] !== $_SESSION['birthdate'] && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST['birthdate'])) {
		$ddate = explode('-', $_POST['birthdate']);
		if ($ddate[1] <= 12 && $ddate[2] <= 31 && floor((time() - strtotime($_POST['birthdate'])) / 31556926) >= 18 && floor((time() - strtotime($_POST['birthdate'])) / 31556926) < 100) {
			$log = $bdd->prepare("UPDATE users SET `birthdate` = :birthdate WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'birthdate' => $_POST['birthdate']));
			$_SESSION['birthdate'] = proreq($_POST['birthdate']);
			$ok = 1;
		}
	}
	if ($_POST['sexe'] !== $_SESSION['sexe']) {
		if ($_POST['sexe'] === 'Male' || $_POST['sexe'] === 'Female' || nullif($_POST['sexe'])) {
			$log = $bdd->prepare("UPDATE users SET `sexe` = :sexe WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'sexe' => $_POST['sexe']));
			$_SESSION['sexe'] = $_POST['sexe'];
			$ok = 1;
		}
	}
	if (!nullif($_POST['geoloc']) && !nullif($_POST['postalcode']) && !nullif($_POST['lon']) && !nullif($_POST['lat'])) {
		if (is_numeric($_POST['postalcode']) && is_numeric($_POST['lon']) && is_numeric($_POST['lat'])) {
			$log = $bdd->prepare("UPDATE users SET `locali` = :pos WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'pos' => $_POST['geoloc']));
			$log = $bdd->prepare("UPDATE users SET `postalcode` = :postalcode WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'postalcode' => $_POST['postalcode']));
			$log = $bdd->prepare("UPDATE users SET `longitude` = :longitude WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'longitude' => $_POST['lon']));
			$log = $bdd->prepare("UPDATE users SET `latitude` = :latitude WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'latitude' => $_POST['lat']));
			$_SESSION['locali'] = $_POST['geoloc'];
			$_SESSION['postalcode'] = $_POST['postalcode'];
			$_SESSION['latitude'] = $_POST['lat'];
			$_SESSION['longitude'] = $_POST['lon'];
			$ok = 1;
		}
	}
	if ($_POST['sexualor'] !== $_SESSION['sexualor'] || nullif($_POST['sexualor'])) {
		if ($_POST['sexualor'] === 'Hetero' || $_POST['sexualor'] === 'Homo' || $_POST['sexualor'] === 'Bi') {
			$log = $bdd->prepare("UPDATE users SET `sexualor` = :sexualor WHERE `users`.`login` LIKE :first;");
			$log->execute(array('first' => $_SESSION['login'], 'sexualor' => $_POST['sexualor']));
			$_SESSION['sexualor'] = proreq($_POST['sexualor']);
			$ok = 1;
		}
	}
	if ($ok == 1) {
		echo "reussi";
	}
}

//--- Edit Localisation ---

if (!nullif($_POST['pos']) && !nullif($_POST['postalcode']) && !nullif($_POST['lon']) && !nullif($_POST['lat'])) {
	if ($_POST['submit'] === 'edit') {
		$log = $bdd->prepare("UPDATE users SET `locali` = :pos WHERE `users`.`login` LIKE :first;");
		$log->execute(array('first' => $_SESSION['login'], 'pos' => $_POST['pos']));
		$log = $bdd->prepare("UPDATE users SET `postalcode` = :postalcode WHERE `users`.`login` LIKE :first;");
		$log->execute(array('first' => $_SESSION['login'], 'postalcode' => $_POST['postalcode']));
		$log = $bdd->prepare("UPDATE users SET `longitude` = :longitude WHERE `users`.`login` LIKE :first;");
		$log->execute(array('first' => $_SESSION['login'], 'longitude' => $_POST['lon']));
		$log = $bdd->prepare("UPDATE users SET `latitude` = :latitude WHERE `users`.`login` LIKE :first;");
		$log->execute(array('first' => $_SESSION['login'], 'latitude' => $_POST['lat']));
		$_SESSION['locali'] = $_POST['pos'];
		$_SESSION['postalcode'] = $_POST['postalcode'];
		$_SESSION['latitude'] = $_POST['lat'];
		$_SESSION['longitude'] = $_POST['lon'];
		echo "reussi";
	}
	else if ($_POST['submit'] == 'search') {
		echo "postal";
	}
}

//--- Edit Profil photo ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'editpdp' && !nullif(proreq($_POST['idpic']))) {
	$log = $bdd->prepare("SELECT `id` FROM pics WHERE `login` LIKE :first AND `ispdp` = 1;");
	$log->execute(array('first' => $_SESSION['login']));
	$pdp = $log->fetch();
	if (!nullif($pdp)) {
		$log = $bdd->prepare("UPDATE pics SET `ispdp` = 0 WHERE `pics`.`login` LIKE :first AND `pics`.`id` LIKE :id;");
		$log->execute(array('first' => $_SESSION['login'], 'id' => $pdp['id']));
	}
	$log = $bdd->prepare("UPDATE pics SET `ispdp` = 1 WHERE `pics`.`login` LIKE :first AND `pics`.`id` = :id;");
	$log->execute(array('first' => $_SESSION['login'], 'id' => $_POST['idpic']));
	$log = $bdd->prepare("SELECT `img` FROM pics WHERE `login` LIKE :first AND `ispdp` = 1;");
	$log->execute(array('first' => $_SESSION['login']));
	$pdp = $log->fetch();
	$log = $bdd->prepare("UPDATE users SET `pdp` = :pdp WHERE `login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login'], 'pdp' => $pdp['img']));
	$_SESSION['pdp'] = $pdp['img'];
	echo "reussi";
}

//--- Delete Pics ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'imgdel' && !nullif(proreq($_POST['idpic']))) {
	$log = $bdd->prepare("SELECT `id` FROM pics WHERE `id` LIKE :idpic AND `ispdp` = 1;");
	$log->execute(array('idpic' => $_POST['idpic']));
	$pdp = $log->fetch();
	$log = $bdd->prepare('DELETE FROM pics WHERE `id` LIKE :idpic AND `login` LIKE :first;');
	$log->execute(array('first' => $_SESSION['login'], 'idpic' => $_POST['idpic']));
	if (!nullif($pdp)) {
		$log = $bdd->prepare("UPDATE users SET `pdp` = '' WHERE `login` LIKE :first;");
		$log->execute(array('first' => $_SESSION['login']));
	}
	if (nullif($pdp)) {
		echo "reussi";
	}
	else {
		echo "reussipdp";
	}
}
//--- Like, Block Or Report ---

if (!nullif($_POST['butt'])) {
	if ($_SESSION['login'] !== proreq($_POST['visited'])) {
		if (!nullif($_POST) && proreq($_POST['butt']) === 'Like') {
			$log = $bdd->prepare("SELECT `id` FROM `pics` WHERE `login` LIKE :first;");
			$log->execute(array('first' => $_POST['visited']));
			if (!nullif($log->fetch())) {
				$log = $bdd->prepare("SELECT `id` FROM `pics` WHERE `login` LIKE :first;");
				$log->execute(array('first' => $_SESSION['login']));
				if (!nullif($_SESSION['pdp'])) {
					$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liker` LIKE :first AND `liked` LIKE :second;");
					$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
					$req = $log->fetch();
					if (nullif($req)) {
						$log = $bdd->prepare('INSERT INTO `like` (`liker`, `liked`, `date`) VALUES (:first, :second, "'.date("Y-m-d H:i:s").'");');
						$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
						$log = $bdd->prepare("SELECT * FROM `like` WHERE `liked` LIKE :first AND `liker` LIKE :second;");
						$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
						$req = $log->fetch();
						echo "like";
						if (!nullif($req)) {
							$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "relike", "'.date("Y-m-d H:i:s").'");');
							$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
							$chat = 1;
						}
						else {
							$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "like", "'.date("Y-m-d H:i:s").'");');
							$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
						}
					}
					else {
						$log = $bdd->prepare('DELETE FROM `like` WHERE `liker` LIKE :first AND `liked` LIKE :second;');
						$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
						$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "unlike", "'.date("Y-m-d H:i:s").'");');
						$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
						echo "unlike";
					}

					$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liked` LIKE :second;");
					$log->execute(array('second' => $_POST['visited']));
					$like = $log->fetchAll();
					$log = $bdd->prepare("SELECT `visitor` FROM `visit` WHERE `visited` LIKE :second;");
					$log->execute(array('second' => $_POST['visited']));
					$visit = $log->fetchAll();
					$pop = (count($like) * 5 + count($visit) * 2);
					$log = $bdd->prepare("UPDATE users SET `popscore` = '".intval($pop)."' WHERE `login` LIKE :second;");
					$log->execute(array('second' => $_POST['visited']));
					echo ",".intval($pop);
					if ($chat == 1) {
						echo ",chat";
					}
				}
				else {
					echo "You can't like if you haven't got photo!";
				}
			}
			else {
				echo "You can't like/unlike a profile without photo!";
			}
		}
		if (!nullif($_POST) && proreq($_POST['butt']) === 'Block') {
			$log = $bdd->prepare("SELECT `blocker` FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;");
			$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
			$req = $log->fetch();
			if (nullif($req)) {
				$log = $bdd->prepare('INSERT INTO `block` (`blocker`, `blocked`, `date`) VALUES (:first, :second, "'.date("Y-m-d H:i:s").'");');
				echo "block";
			}
			else {
				$log = $bdd->prepare('DELETE FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;');
				echo "unblock";
			}
			$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
		}
		if (!nullif($_POST) && proreq($_POST['butt']) === 'Report') {
			$log = $bdd->prepare("SELECT `reporter` FROM `report` WHERE `reporter` LIKE :first AND `reported` LIKE :second;");
			$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
			$req = $log->fetch();
			if (nullif($req)) {
				$log = $bdd->prepare('INSERT INTO `report` (`reporter`, `reported`, `date`) VALUES (:first, :second, "'.date("Y-m-d H:i:s").'");');
				echo "report";
			}
			else {
				$log = $bdd->prepare('DELETE FROM `report` WHERE `reporter` LIKE :first AND `reported` LIKE :second;');
				echo "unreport";
			}
			$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['visited']));
		}
	}
	else {
		echo "You can't do that on your profile!";
	}
}

//--- Notification ---

if(!nullif($_POST) && proreq($_POST['submit']) === 'notification') {
	$log = $bdd->prepare("SELECT * FROM `notification` WHERE `receiver` LIKE :first ORDER BY `date` DESC;");
	$log->execute(array('first' => $_SESSION['login']));
	$noti = $log->fetchAll();
	foreach ($noti as $key => $value) {
		$log = $bdd->prepare("SELECT `blocker` FROM `block` WHERE `blocker` LIKE :first AND `blocked` like :second;");
		$log->execute(array('first' => $_SESSION['login'], 'second' => $value['sender']));
		if (nullif($log->fetch())) {
			$log = $bdd->prepare("SELECT `name` FROM `users` WHERE `login` LIKE :first");
			$log->execute(array('first' => $value['sender']));
			$name = $log->fetch()['name'];
			if ($value['content'] === 'like') {
				echo "<div class='notimess' id='like".$value['id']."' name='like'>- ".$name." liked your profile! <a href='index.php?page=profile&login=".$value['sender']."'>Click here</a> to see his profile
				<input class='notdel' type='image' src='resources/del.png' onclick='deletenoti(\"like".$value['id']."\");'></div>";
			}
			else if ($value['content'] === 'visit') {
				echo "<div class='notimess' id='visit".$value['id']."' name='visit'>- ".$name." visited your profile! <a href='index.php?page=profile&login=".$value['sender']."'>Click here</a> to see his profile
				<input class='notdel' type='image' src='resources/del.png' onclick='deletenoti(\"visit".$value['id']."\");'></div>";
			}
			else if ($value['content'] === 'message') {
				echo "<div class='notimess' id='message".$value['id']."' name='message'>- ".$name." sent you a message! <a href='index.php?page=profile&login=".$value['sender']."'>Click here</a> to see his profile
				<input class='notdel' type='image' src='resources/del.png' onclick='deletenoti(\"message".$value['id']."\");'></div>";
			}
			else if ($value['content'] === 'relike') {
				echo "<div class='notimess' id='relike".$value['id']."' name='relike'>- ".$name." liked your profile, you can talk with him now! <a href='index.php?page=profile&login=".$value['sender']."'>Click here</a> to see his profile
				<input class='notdel' type='image' src='resources/del.png' onclick='deletenoti(\"relike".$value['id']."\");'></div>";
			}
			else if ($value['content'] === 'unlike') {
				echo "<div class='notimess' id='unlike".$value['id']."' name='unlike'>- ".$name." unliked your profile! <a href='index.php?page=profile&login=".$value['sender']."'>Click here</a> to see his profile
				<input class='notdel' type='image' src='resources/del.png' onclick='deletenoti(\"unlike".$value['id']."\");'></div>";
			}
		}
		else {
			$log = $bdd->prepare('DELETE FROM `notification` WHERE `id` LIKE :first;');
			$log->execute(array('first' => $value['id']));
		}
	}
	echo "+".count($noti);
}

//--- Delete Notification ---

if(!nullif($_POST) && proreq($_POST['submit']) === 'deletenoti' && !nullif(proreq($_POST['content'])) && !nullif(proreq($_POST['idnoti']))) {
	$log = $bdd->prepare('DELETE FROM `notification` WHERE `receiver` LIKE :first AND `id` = :id AND `content` LIKE :content;');
	$id = substr(proreq($_POST['idnoti']), strlen(proreq($_POST['content'])));
	$log->execute(array('first' => $_SESSION['login'], 'id' => $id,'content' => $_POST['content']));

	echo "reussi";
}

//--- Normal Search ---

if (!nullif($_POST) && proreq($_POST['submit']) === 'normsearch' && !nullif($_POST['name'])) {
	$tab = explode(" ", $_POST['name']);
	if (!nullif($tab[1])) {
		$log = $bdd->prepare("SELECT * FROM users WHERE (`name` LIKE :first AND `surname` LIKE :second) OR (`name` LIKE :second AND `surname` LIKE :first);");
		$log->execute(array('first' => '%'.$tab[0].'%', 'second' => '%'.$tab[1].'%'));
	}
	else {
		$log = $bdd->prepare("SELECT * FROM users WHERE `name` LIKE :first OR `surname` LIKE :first;");
		$log->execute(array('first' => '%'.$tab[0].'%'));
	}
	$user1 = $log->fetchAll();
	$res = "";
	foreach ($user1 as $key => $value) {
		$log = $bdd->prepare("SELECT `blocked` FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;");
		$log->execute(array('first' => $_SESSION['login'], 'second' => $value['login']));
		if (nullif($log->fetch()) && !nullif($value['validate']) && !nullif($value['birthdate'])) {
			if ($value['login'] !== $_SESSION['login']) {
				$res = aff_pro($value, $res, $bdd);
			}
		}
	}
	echo $res;
}

//--- Advanced Search + Discover ---

if (!nullif($_POST) && (proreq($_POST['submit']) === 'adsearch' || proreq($_POST['submit']) === 'discover') && !nullif(proreq($_POST['filtre'])) && !nullif(proreq($_POST['age1'])) && !nullif(proreq($_POST['age2'])) && !nullif(proreq($_POST['pop2']))) {
	$j = 0;
	$res = "";
	$sex = "";
	$age1 = (date("Y") - $_POST['age1'])."-".date("m-d");
	$age2 = (date("Y") - $_POST['age2'])."-".date("m-d");

	if ($_POST['submit'] === 'discover') {
		if ($_SESSION['sexualor'] === 'Hetero') {
			$sex = '`sexe` NOT LIKE "'.$_SESSION['sexe'].'" AND';
		}
		else if ($_SESSION['sexualor'] === 'Homo') {
			$sex = '`sexe` LIKE "'.$_SESSION['sexe'].'" AND';
		}
	}

	$request = "SELECT * FROM users WHERE ".$sex." `login` NOT LIKE :login AND `birthdate` BETWEEN :aget AND :ageo AND `popscore` BETWEEN :popo AND :popt AND `postalcode` LIKE :locali ORDER BY ";

	if (strpos($_POST['filtre'], 'tags') || strpos($_POST['filtre'], 'postalcode') || $_POST['filtre'] === 'X') {
		$log = $bdd->prepare($request . "`name`;");
	}
	else {
		$log = $bdd->prepare($request.$_POST['filtre'].";");
	}
	$log->execute(array('login' => $_SESSION['login'],'ageo' => $age1, 'aget' => $age2, 'popo' => $_POST['pop1'], 'popt' => $_POST['pop2'], 'locali' => '%'.$_POST['searchloc'].'%'));
	$users = $log->fetchAll();
	if ($_POST['nbrtag'] > 0) {
		foreach ($users as $k => $value) {
			$nb = 0;
			$i = 0;
			if ($_SESSION !== $users[$k]['login']) {
				while ($i <= $_POST['nbrtag']) {
					$tag = 'tag'.$i;
					$log = $bdd->prepare("SELECT `login` FROM `tags` WHERE `login` LIKE :first AND `tagname` LIKE :second;");
					$log->execute(array('first' => $users[$k]['login'], 'second' => $_POST[$tag]));
					if (!nullif($log->fetch())) {
						$nb += 1;
					}
					$i++;
				}
				if ($nb == $_POST['nbrtag']) {
					$tab[$j]['login'] = $users[$k]['login'];
					$tab[$j]['surname'] = $users[$k]['surname'];
					$tab[$j]['name'] = $users[$k]['name'];
					$tab[$j]['birthdate'] = $users[$k]['birthdate'];
					$tab[$j]['sexe'] = $users[$k]['sexe'];
					$tab[$j]['sexualor'] = $users[$k]['sexualor'];
					$tab[$j]['locali'] = $users[$k]['locali'];
					$tab[$j]['postalcode'] = $users[$k]['postalcode'];
					$tab[$j]['longitude'] = $users[$k]['longitude'];
					$tab[$j]['latitude'] = $users[$k]['latitude'];
					$tab[$j]['popscore'] = $users[$k]['popscore'];
					$tab[$j]['lastvis'] = $users[$k]['lastvis'];
					$tab[$j]['pdp'] = $users[$k]['pdp'];
					$j++;
				}
			}
		}
		$users = $tab;
	}
	if (strpos($_POST['filtre'], 'tags') || strpos($_POST['filtre'], 'postalcode')) {
		if (strpos($_POST['filtre'], 'tags')) {
			$users = mod_tag($users, $bdd);
		}
		else {
			$users = mod_pos($users);
		}
		if (strpos($_POST['filtre'], 'DESC')) {
			$users = array_reverse(sort_tags($users, 0));
		}
		else {
			$users = sort_tags($users, 0);
		}
	}
	$countu = 0;
	if (proreq($_POST['submit']) === 'adsearch') {
		foreach ($users as $key => $value) {
			if ($key >= 5 * $_POST['nbpage']) {
				$log = $bdd->prepare("SELECT `blocked` FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;");
				$log->execute(array('first' => $_SESSION['login'], 'second' => $value['login']));
				if (nullif($log->fetch())) {
					$countu++;
					$res = aff_pro($value, $res, $bdd);
				}
			}
			if ($key >= 5 * ($_POST['nbpage']) + 4) {
				break;
			}
		}
		echo $res;
		echo "|".$countu / 5;
	}
	else {
		if ($_POST['filtre'] === 'X') {
			$users = mod_tag($users, $bdd);
			$users = mod_pos($users);
			$users = array_reverse(sort_tags($users, 1));
		}
		$res = "";
		foreach ($users as $key => $value) {
			if ($key >= 5 * $_POST['nbpage']) {
				$log = $bdd->prepare("SELECT `blocked` FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;");
				$log->execute(array('first' => $_SESSION['login'], 'second' => $value['login']));
				if(nullif($log->fetch()) && !nullif($value['birthdate'])) {
					$countu++;
					$res = aff_pro($value, $res, $bdd);
				}
			}
			if ($key >= 5 * ($_POST['nbpage']) + 4) {
				break;
			}
		}
		echo $res;
		echo "|".$countu / 5;
	}
}

//--- Chat ---

if (!nullif($_SESSION['login']) && !nullif($_POST) && proreq($_POST['submit']) === 'sendmessage' &&
	!nullif(proreq($_POST['message'])) && !nullif(proreq($_POST['convto'])) && $_POST['convto'] !== $_SESSION['login']) {
	$log = $bdd->prepare("SELECT * FROM `like` WHERE (`liker` LIKE :first AND `liked` LIKE :second) OR (`liked` LIKE :first AND `liker` LIKE :second);");
	$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['convto']));
	$like1 = $log->fetchAll();
	if (count($like1) == 2) {
		$log = $bdd->prepare("SELECT * FROM `chat` WHERE (`people1` LIKE :first AND `people2` LIKE :second) OR (`people2` LIKE :first AND `people1` LIKE :second);");
		$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['convto']));
		$t1 = $log->fetch();
		if (!nullif($t1)) {
			$content = $t1['texte'].$_SESSION['login']." [".date("H:i")."]: ".$_POST['message']."\n";
			$log = $bdd->prepare("UPDATE chat SET `texte` = :content WHERE (`people1` LIKE :first AND `people2` LIKE :second) OR (`people2` LIKE :first AND `people1` LIKE :second);");
		}
		else {
			$content = $_SESSION['login']." [".date("H:i")."]: ".$_POST['message']."\n";
			$log = $bdd->prepare('INSERT INTO `chat` (`people1`, `people2`, `texte`) VALUES (:first, :second, :content);');
		}
		$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['convto'], 'content' => $content));
		$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "message", "'.date("Y-m-d H:i:s").'");');
		$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['convto']));
		echo $content;
	}
}

//--- ActuChat ---

if (!nullif($_SESSION['login']) && !nullif($_POST) && proreq($_POST['submit']) === 'actuchat' && !nullif(proreq($_POST['convto'])) && $_POST['convto'] !== $_SESSION['login']) {
	$log = $bdd->prepare("SELECT * FROM `chat` WHERE (`people1` LIKE :first AND `people2` LIKE :second) OR (`people2` LIKE :first AND `people1` LIKE :second);");
	$log->execute(array('first' => $_SESSION['login'], 'second' => $_POST['convto']));
	$t1 = $log->fetch();
	if (!nullif($t1)) {
		echo $t1['texte'];
	}
	else {
		echo "";
	}
}

//--- FUNCTION ---

function sort_tags($array, $id) {
	$i = array();
	$j = array();
	$k = array();
	$tmp = array();

	$i = 0;
	$k = 0;
	while ($i < count($array))
	{
		$j = $i + 1;
		$tmp[0] = $array[$i][$id];
		while ($j < count($array))
		{
			if ($tmp[0] >= $array[$j][$id])
			{
				$tmp[0] = $array[$j][$id];
				$tmp[1] = $array[$j];
				$k = $j;
			}
			$j++;
		}
		if ($tmp[0] != $array[$i][$id])
		{
			$array[$k] = $array[$i];
			$array[$i] = $tmp[1];
		}
		$i++;
	}
	return($array);
}

function mod_tag($users, $bdd) {
	foreach ($users as $key => $value) {
		$log = $bdd->prepare("SELECT COUNT(*) AS `nbr` FROM (SELECT `tagname` FROM `tags` WHERE `login` LIKE :first) A INNER JOIN (SELECT `tagname` FROM `tags` WHERE `login` LIKE :second) B ON A.tagname = B.tagname;");
		$log->execute(array('first' => $_SESSION['login'], 'second' => $value['login']));
		$tags = $log->fetch();
		$users[$key]['0'] = $tags['nbr'];
		$users[$key]['1'] = 0 + $tags['nbr'] * 4 + ($users[$key]['popscore'] / 2);
	}
	return ($users);
}

function mod_pos($users) {
	foreach ($users as $key => $value) {
		$users[$key]['0'] = abs($_SESSION['latitude'] - $users[$key]['latitude']) + abs($_SESSION['longitude'] - $users[$key]['longitude']);
		$users[$key]['1'] = $users[$key]['1'] + (1 / ($users[$key]['0'] + 0.1) * 2);
	}
	return ($users);
}

function aff_pro($value, $res, $bdd) {
	$pdp = !nullif($value['pdp']) ? $value['pdp'] : "resources/profil.png";
	$log = $bdd->prepare("SELECT `tagname` FROM `tags` WHERE `login` LIKE :second;");
	$log->execute(array('second' => $value['login']));
	$tags = $log->fetchAll();
	$res .= "<div class='peo'>";
	$res .= "<div class='imagese'><a href='index.php?page=profile&login=".$value['login']."'><img src='".$pdp."'/><div class='namese'><span>".$value['name']." ".$value["surname"]."<span></div></a></div>";
	$res .= "<div class='infose'>Age: ".floor((time() - strtotime($value['birthdate'])) / 31556926)."</br>Birthdate: ".$value['birthdate']."</br>Sexe: ".$value['sexe']."</br>Sexual Orientation: ".$value['sexualor']."</br>";
	$res .= "Popularity Score: ".$value['popscore']."</br>Place: ".$value['locali']."</br>Last visit: ".$value['lastvis']."</br>Tags: ";
	foreach ($tags as $key => $val) {
		$res .= $val['tagname']." - ";
	}
	$res .= "</div>";
	$res .= "<div class=\"clear\"></div>";
	$res .= "</div>";
	return ($res);
}
?>
