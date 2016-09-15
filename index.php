<?php $title = "SoulMate";
include('header.php');
include('config/setup.php');
include("geoloc/geoipcity.inc");
include("geoloc/geoipregionvars.php");
date_default_timezone_set("Europe/Paris");

if (nullif($_SESSION['login'])) {
	if (nullif($_GET['page']))
		require_once('script/login.php');
	if ($_GET['page'] === 'reg')
		require_once('script/register.php');
	if ($_GET['page'] === 'valid')
		require_once('script/validate.php');
	if ($_GET['page'] === 'rec')
		require_once('script/recovery.php');
}
else {
	if (nullif($_SESSION['locali'])) {
		require_once('script/req_add.php');
	}
	pop_score($bdd);
	if ($_GET['page'] === 'logout')
		require_once('script/logout.php');
	if ($_GET['page'] === 'discover')
		require_once('script/discover.php');
	if ($_GET['page'] === 'search')
		require_once('script/search.php');
	if ($_GET['page'] === 'profile')
		require_once('script/profile.php');
	if ($_GET['page'] === 'edit')
		require_once('script/edit.php');
}

?>
<div id='errbox' onclick="closeerr();"></div>
<?php if(!nullif($_SESSION['login']) && nullif($_GET)) {
	$log = $bdd->prepare("SELECT `reported` FROM `report` WHERE `reported` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login']));
	$report = $log->fetchAll();
	if (count($report) >= 5) {
		?>
		<div id='profile' style="height: auto; color: white; padding-left: 20px; line-height: 1.5">
			Hi <?=$_SESSION['name'];?>,</br> you are a bad user.</br>
			More of 5 users has report you like a fake account. You will ban of this website.
		</div>
		<?php
		$log = $bdd->prepare('DELETE FROM users WHERE `login` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM visit WHERE `visitor` LIKE :first OR `visited` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM block WHERE `blocker` LIKE :first OR `blocked` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM `like` WHERE `liker` LIKE :first OR `liked` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM report WHERE `reporter` LIKE :first OR `reported` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM pics WHERE `login` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM notification WHERE `sender` LIKE :first OR `receiver` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM chat WHERE `people1` LIKE :first OR `people2` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		$log = $bdd->prepare('DELETE FROM tags WHERE `login` LIKE :first;');
		$log->execute(array('first' => $_SESSION['login']));
		session_destroy();
	}
	else {
		if (nullif($_SESSION['sexe']) || nullif($_SESSION['sexualor']) || nullif($_SESSION['birthdate'])) {?>
			<div id='profile' style="height: auto; color: white; padding-left: 20px; line-height: 1.5">
				Hi <?=$_SESSION['name'];?>,</br> you are a new user.</br>
				Please, let's go <a href="index.php?page=edit">here</a> to complete your profile! ( if you don't do that, you can't be find by other users )
			</div>
			<?php
		}
	}
}
if (!nullif($_SESSION['login'])) { ?>
	<script src="js/index.js">
	</script>
	<?php
}

if (!nullif($_SESSION['login']) && !nullif($_SESSION['error'])) { ?>
<div id='errbox2'><img src='resources/imgdel.png' alt='imgdel'/><?= $_SESSION['error'];?></div>
<?php $_SESSION['error'] = ""; }

include('footer.php');?>
