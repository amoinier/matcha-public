<?php
if (!strpos($_SERVER['PHP_SELF'], "profile.php")) {
if ($_SESSION['login'] && proreq($_GET['page']) === 'profile') {
	if (!nullif($_GET['login'])) {
		$log = $bdd->prepare("SELECT * FROM users WHERE `login` LIKE :login;");
		$log->execute(array('login' => preg_replace('/%/', '', $_GET['login'])));
		$users = $log->fetch();
		if (!nullif($users) && !nullif($users['validate'])) {
			$log = $bdd->prepare("SELECT * FROM visit WHERE `visitor` LIKE :first AND `visited` LIKE :second;");
			$log->execute(array('first' => $_SESSION['login'], 'second' => $_GET['login']));
			$visit = $log->fetch();

		if ($users['login'] !== $_SESSION['login'] && nullif($visit)) {
			$log = $bdd->prepare('INSERT INTO visit (`visitor`, `visited`, `lastvis`, `nbrvis`) VALUES (:first, :second, "'.date("Y-m-d H:i:s").'", 1);');
			$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
			$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "visit", "'.date("Y-m-d H:i:s").'");');
			$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));

			$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liked` LIKE :first;");
			$log->execute(array('first' => $_GET['login']));
			$like = $log->fetchAll();
			$log = $bdd->prepare("SELECT `visitor` FROM `visit` WHERE `visited` LIKE :first;");
			$log->execute(array('first' => $_GET['login']));
			$visit = $log->fetchAll();
			$pop = (count($like) * 5 + count($visit) * 2);
			$log = $bdd->prepare("UPDATE users SET `popscore` = :score WHERE `login` LIKE :first;");
			$log->execute(array('first' => $_GET['login'], 'score' => intval($pop)));
		}

		else if ($users['login'] !== $_SESSION['login'] && !nullif($visit) && time() - strtotime($visit['lastvis']) >= 300){
			$nbr = intval($visit['nbrvis']) + 1;
			$log = $bdd->prepare("UPDATE visit SET `nbrvis` = :nbr WHERE `visitor` LIKE :first AND `visited` LIKE :second;");
			$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login'], 'nbr' => $nbr));
			$log = $bdd->prepare("UPDATE visit SET `lastvis` = :nbr WHERE `visitor` LIKE :first AND `visited` LIKE :second;");
			$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login'], 'nbr' => date('Y-m-d H:i:s')));
			$log = $bdd->prepare('INSERT INTO `notification` (`sender`, `receiver`, `content`, `date`) VALUES (:first, :second, "visit", "'.date("Y-m-d H:i:s").'");');
			$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
		}


		else if ($_SESSION['login'] === $users['login'] && !nullif($_POST['butt'])) {
			$_SESSION['error'] = "You can't like/report/block yourself!";
		}?>

			<div id="profile">
				<div id='name'>
					<?php
					echo $users['name']." ".$users['surname'];
					$log = $bdd->prepare("SELECT liker FROM `like` WHERE `liker` LIKE :first AND `liked` LIKE :second;");
					$log->execute(array('second' => $_SESSION['login'], 'first' => $users['login']));
					$like = $log->fetch();
					if (!nullif($like)) {?>
						<img class='iflike' src="resources/heart.png" alt="" />
					<?php } ?>
				</div>
				<div id='left'>
				<div id='pdp'>
					<?php
					$log = $bdd->prepare("SELECT `img` FROM pics WHERE `login` LIKE :second AND `ispdp` = 1;");
					$log->execute(array('second' => $users['login']));
					$pdp = $log->fetch();
					if (!nullif($pdp)) {?>
					<img id='pdpp' src="<?= $pdp['img'];?>" alt="" />
					<?php }
					else { ?>
						<img id='pdpp' src="resources/profil.png" alt="" />
					<?php }
					$log = $bdd->prepare("SELECT `id`, `img` FROM pics WHERE `login` LIKE :second;");
					$log->execute(array('second' => $users['login']));
					$pics = $log->fetchAll();
					foreach ($pics as $key => $val) {?>
						<img class='min' src="<?= $val['img'];?>" alt="pdp" name='pdp' onClick="zoompics('<?= $val['img'];?>');"/>
						<?php } ?>
					</div>
					<?php
					if (proreq($users['login']) !== $_SESSION['login']) {
						$log = $bdd->prepare("SELECT reporter FROM `report` WHERE `reporter` LIKE :first AND `reported` LIKE :second;");
						$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
						$report = $log->fetch();

						$log = $bdd->prepare("SELECT blocker FROM `block` WHERE `blocker` LIKE :first AND `blocked` LIKE :second;");
						$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
						$block = $log->fetch();

						$log = $bdd->prepare("SELECT liker FROM `like` WHERE `liker` LIKE :first AND `liked` LIKE :second;");
						$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
						$like = $log->fetch();
					}
					?>
						<div id='butuse'>
							<input id='report' type="submit" name="butt" value="<?php if (nullif($report)) {echo "Report";} else {echo "Unreport";}?>" onclick="report();">
							<input id='block' type="submit" name="butt" value="<?php if (nullif($block)) {echo "Block";} else {echo "Unblock";}?>" onclick="block();">
							<input id='likeb' type="submit" name="butt" value="<?php if (nullif($like)) {echo "Like";} else {echo "Unlike";}?>" onclick="like();">
						</div>
						<div id='info'>
							<div id='infott'>
								Information
							</div>
							<?php if (!nullif($users['birthdate'])) { ?>
								<div>Date of birth : <?= $users['birthdate'];?></div>
								<div>Age : <?php echo floor((time() - strtotime($users['birthdate'])) / 31556926);?></div>
						<?php }
						if (!nullif($users['locali'])) { ?>
							<div>Place : <?= $users['locali'];?></div>
						<?php }
						if (!nullif($users['sexe'])) { ?>
							<div>Sexe : <?php echo $users['sexe'];?></div>
						<?php }
						if (!nullif($users['sexualor'])) { ?>
							<div>Sexual Orientation: <?php echo $users['sexualor'];?></div>
						<?php }
						if (!nullif($users['popscore']) || intval($users['popscore']) == 0) { ?>
							<div id='propopu'>Popularity Score : <?php if (nullif($users['popscore'])) {echo "0";} else {echo $users['popscore'];}?></div>
						<?php }
						if (!nullif($users['lastvis'])) { ?>
							<div>Last Visit : <?php if (time() - strtotime($users['lastvis']) <= 300) {echo "Connected";} else {echo $users['lastvis'];}?></div>
						<?php } ?>
						</div>
					</div>
					<div id='biog'>
						<div>Biography</div></br>
						<textarea readonly rows=15 id='textbio'><?php echo $users['bio'];?></textarea>
					</div>
						<div id='tags'>
							<div>Tags</div></br>
							<?php
							$log = $bdd->prepare("SELECT `tagname` FROM tags WHERE `login` LIKE :second;");
							$log->execute(array('second' => $users['login']));
							$tags = $log->fetchAll();
							foreach ($tags as $key => $val) {
								if ($key < count($tags) - 1) {
									echo $val['tagname']." - ";
								}
								else {
									echo $val['tagname'];
								}
							}
							?>
						</div>
						<div id="people">
							<?php if ($_SESSION['login'] === $users['login']) {?>
								<div id="visit">
									<div>Visitors</div>
										<?php
										$log = $bdd->prepare("SELECT `visitor` FROM `visit` WHERE `visited` LIKE :second;");
										$log->execute(array('second' => $users['login']));
										$visit = $log->fetchAll();
										if (!nullif($visit)) {
											foreach ($visit as $key => $val) {?>
												<span>- <a href="index.php?page=profile&login=<?= $val['visitor'];?>"><?= $val['visitor'];?></a></span>
											<?php }
										}
										else {echo "Nobody has visited your profile.";}?>
								</div>
								<div id="visit">
								<div>Likers</div>
									<?php
									$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liked` LIKE :second;");
									$log->execute(array('second' => $users['login']));
									$like = $log->fetchAll();
									if (!nullif($like)) {
										foreach ($like as $key => $val) {?>
											<span>- <a href="index.php?page=profile&login=<?= $val['liker'];?>"><?= $val['liker'];?></a></span>
										<?php }
									}
									else {echo "Nobody has liked your profile.";}?>
								</div>
							<?php }
							else {
								$log = $bdd->prepare("SELECT * FROM `like` WHERE (`liker` LIKE :first AND `liked` LIKE :second) OR (`liked` LIKE :first AND `liker` LIKE :second);");
								$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
								$like1 = $log->fetchAll();
								if (count($like1) == 2) {
									$log = $bdd->prepare("SELECT * FROM `chat` WHERE (`people1` LIKE :first AND `people2` LIKE :second) OR (`people2` LIKE :first AND `people1` LIKE :second)");
									$log->execute(array('first' => $_SESSION['login'], 'second' => $users['login']));
									$t1 = $log->fetch();
									if (!nullif($t1)) {
										$disc = $t1['texte'];
									}
									else {
										$disc = "";
									}
									?>
									<div id='chat'>
										<textarea style="background-color: rgba(0, 0, 0, 0.2);" readonly rows=21 id='textchat'><?= $disc;?></textarea>
										<textarea id="sendmess" rows=2 id='textbio'></textarea><input type="submit" name="name" value="Send" onclick="chat();">
										<input id='hiddenlogin' type="hidden" name="name" value="<?php echo $users['login'];?>">
									</div>
									<script src='js/profile.js'></script>
									<?php }
								}?>
							</div>
						</div>
					<?php }
					else {
						?>
						<div class='login'><div id='error'>This account doesn't exists</div></br></div>
						<?php
					}
				}
				else {echo "<meta http-equiv='refresh' content='0;URL=index.php'/>";}
			}
		}
		else {
			echo "<meta http-equiv='refresh' content='0;URL=index.php'/>";
		}?>
