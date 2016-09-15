<?php
if (!strpos($_SERVER['PHP_SELF'], "edit.php")) {
include_once('ajax/aedit.php');

if (!nullif($_POST) && $_POST['upload'] === 'Upload') {
	$log = $bdd->prepare("SELECT `id` FROM pics WHERE `login` LIKE :first;");
	$log->execute(array('first' => $_SESSION['login']));
	$pdp = $log->fetchAll();
	if (count($pdp) < 5) {
		if (upload($_FILES)) {
			$imagedata = file_get_contents($_SESSION['upload']);
			$base64 = "data:image/jpeg;base64,".base64_encode($imagedata);
			if (count($pdp) == 0) {
				$log = $bdd->prepare('INSERT INTO pics (`login`, `img`, `ispdp`) VALUES (:first, :base, 1);');
				$log->execute(array('first' => $_SESSION['login'], 'base' => $base64));
				$log = $bdd->prepare("UPDATE users SET `pdp` = :base WHERE `login` LIKE :first;");
				$_SESSION['pdp'] = $base64;
			}
			else {
				$log = $bdd->prepare('INSERT INTO pics (`login`, `img`, `ispdp`) VALUES (:first, :base, 0);');
			}
			$log->execute(array('first' => $_SESSION['login'], 'base' => $base64));
			unlink($_SESSION['upload']);
			$base64 = "";
			$_SESSION['upload'] = "";
		}
		else {
			$_SESSION['error'] = "Upload fail";
		}
	}
	else {
		$_SESSION['error'] = "You have already 5 photos.";
	}
}

if ($_SESSION['login'] && $_GET['page'] === 'edit') {
	if (!nullif($_SESSION['login'])) {
		?>
			<div id="profile">
				<div id='name'>
					<input type="text" id="inpname" value="<?= $_SESSION['name'];?>">
					<input type="text" id="inpsurname" value="<?= $_SESSION['surname'];?>">
					<input type="submit" name="submit" value="edit name" onclick="editname();">
				</div>
				<div id='left'>
					<div id='pdp'>
					<?php
					$log = $bdd->prepare("SELECT `img` FROM pics WHERE `login` LIKE :first AND `ispdp` = 1;");
					$log->execute(array('first' => $_SESSION['login']));
					$pdp = $log->fetch();
					if (!nullif($pdp)) {?>
					<img id='pdpp' src="<?= $pdp['img'];?>" alt="" />
					<?php }
					else { ?>
						<img id='pdpp' src="resources/profil.png" alt="" />
					<?php }
					$log = $bdd->prepare("SELECT `id`, `img` FROM pics WHERE `login` LIKE :first;");
					$log->execute(array('first' => $_SESSION['login']));
					$pics = $log->fetchAll();
					foreach ($pics as $key => $val) {?>
							<input id='pdp<?= $val['id'];?>' type='image' class='min' src="<?= $val['img'];?>" name='pdp' value='changepdp' onclick="editpdp('<?= $val['id'];?>');">
							<input id='delpdp<?= $val['id'];?>' class='imgdel' type="image" src="resources/del.png" name="imgdel" value="del" onclick="delpics('<?= $val['id'];?>');">
							<input id='inp<?= $val['id'];?>' type="hidden" name="idpic" value="<?= $val['id'];?>">
						<?php } ?>
						</div>
						<div id='butuse'>
							<form method="POST" action="index.php?page=edit" enctype="multipart/form-data">
								<input type="file" name="upload-photo" value="Choose a file">
								<input type="submit" name="upload" value="Upload">
							</form>
						</div>
						<div id='info'>
							<div id='infott'>
								Information
							</div>
								<div>Email : <input type="text" name="email" value="<?= $_SESSION['mail'];?>"> Retape : <input type="text" name="email2" value="<?= $_SESSION['mail'];?>"></div>
								<div>Date of birth : <input type="date" name="birthdate" value="<?= $_SESSION['birthdate'];?>"> (yyyy-mm-dd)</div>
								<div>Place : <input id='placeedit' type="text" name="place" value="<?= $_SESSION['locali'];?>" style='width: 10vw;'><input id='gps' type="image" src="resources/gps.png" name="gps" value="gps" onclick="geoloc('edit');"></div>
								<div>Sexe :
									<select name="sexe">
										<option value="<?= $_SESSION['sexe'];?>"><?= $_SESSION['sexe'];?></option>
										<?php if ($_SESSION['sexe'] === 'Male') {echo "<option value='Female'>Female</option>";}
										else if (nullif($_SESSION['sexe'])){echo "<option value='Male'>Male</option><option value='Female'>Female</option>";}
										else {echo "<option value='Male'>Male</option>";}?>
									</select>
								</div>
								<div>Sexual Orientation:
									<select name="sexualor">
										<option value="<?= $_SESSION['sexualor'];?>"><?= $_SESSION['sexualor'];?></option>
										<?php if ($_SESSION['sexualor'] !== "Hetero") {echo "<option value='Hetero'>Hetero</option>";}
										if ($_SESSION['sexualor'] !== "Homo") {echo "<option value='Homo'>Homo</option>";}
										if ($_SESSION['sexualor'] !== "Bi") {echo "<option value='Bi'>Bi</option>";}?>
									</select>
								</div>
								<div>Popularity Score : <?= $_SESSION['popscore'];?></div>
								<input type="submit" name="submit" value="edit info" onclick="editinfo();">
						</div>
					</div>
					<div id='biog'>
						<div>Biography</div></br>
							<textarea name="bio" rows="15" id='edittext'><?php echo $_SESSION['bio'];?></textarea>
							<input type="submit" value="Edit biog" onclick="editbiog();">
					</div>
						<div id='tags'>
							<div>Tags</div></br>
							<?php
							$log = $bdd->prepare("SELECT `tagname` FROM tags WHERE `login` LIKE :first;");
							$log->execute(array('first' => $_SESSION['login']));
							$tags = $log->fetchAll();
							?><span id='taglist'><?php
							foreach ($tags as $key => $val) {
								?>
								<input id='del<?= $val['tagname'];?>' type="image" src="resources/del.png" name="delete" value="del" onclick="deltag('<?= $val['tagname'];?>');">
								<span id='<?= $val['tagname'];?>'><?PHP echo $val['tagname']." - ";?></span>
								<?php }?>
							</span></br>
								Select a tag : <select id="newtag" name="newtag">
									<option value=""></option><?php
									$log = $bdd->prepare("SELECT DISTINCT `tagname` FROM tags WHERE `login` NOT LIKE :first ORDER BY `tagname` ASC;");
									$log->execute(array('first' => $_SESSION['login']));
									$tags = $log->fetchAll();
									$log = $bdd->prepare("SELECT `tagname` FROM tags WHERE `login` LIKE :first;");
									$log->execute(array('first' => $_SESSION['login']));
									$mine = $log->fetchAll();
									foreach ($tags as $key => $val) {
										$i = 0;
										foreach ($mine as $key => $value) {
											if ($val['tagname'] === $value['tagname']) {$i = 1;}
										}
										if ($i == 0) {?>
										<option value="<?= $val['tagname'];?>"><?= $val['tagname'];?></option>
									<?php }
								}
								?></select>
								Or create your tag : <input id='text-tag' type="text" name="text-tag" value="">
								<input type="submit" name="submit" value="add tags" onclick="edittags();">
						</div>
						<div id="people">
							<div id="visit">
								<div>Visitors</div>
									<?php
									$log = $bdd->prepare("SELECT `visitor` FROM `visit` WHERE `visited` LIKE :first;");
									$log->execute(array('first' => $_SESSION['login']));
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
									$log = $bdd->prepare("SELECT `liker` FROM `like` WHERE `liked` LIKE :first;");
									$log->execute(array('first' => $_SESSION['login']));
									$like = $log->fetchAll();
									if (!nullif($like)) {
										foreach ($like as $key => $val) {?>
											<span>- <a href="index.php?page=profile&login=<?= $val['liker'];?>"><?= $val['liker'];?></a></span>
										<?php }
									}
									else {echo "Nobody has liked your profile.";}?>
								</div>
						</div>
					</div>
					<script src='js/edit.js'></script>
					<?php
				}
				else {echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";}
			}
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}?>
