<?php
if (!strpos($_SERVER['PHP_SELF'], "validate.php")) {
include('database.php');
try
{
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->exec('CREATE DATABASE IF NOT EXISTS `db_matcha`;');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->exec('USE `db_matcha`;');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query("CREATE TABLE IF NOT EXISTS users (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, `login` VARCHAR(45) NOT NULL, `passwd` VARCHAR(129) NOT NULL,
	`mail` VARCHAR(45) NOT NULL, `surname` VARCHAR(45) NOT NULL, `name` VARCHAR(45) NOT NULL, `birthdate` DATE, `sexe` VARCHAR(16), `sexualor` VARCHAR(16), `bio` TEXT, `locali` TEXT, `postalcode` VARCHAR(7), `longitude` FLOAT, `latitude` FLOAT,
	 `popscore` FLOAT, `lastvis` DATETIME, `validate` INT NOT NULL, `pdp` LONGTEXT);");
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS pics (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, `login` VARCHAR(45) NOT NULL, `img` LONGTEXT NOT NULL, `ispdp` INT);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS tags (`tagname` TEXT NOT NULL, `login` VARCHAR(45) NOT NULL);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS visit (`visitor` VARCHAR(45) NOT NULL, `visited` VARCHAR(45) NOT NULL, `lastvis` DATETIME NOT NULL, `nbrvis` INT);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS `like` (`liker` VARCHAR(45) NOT NULL, `liked` VARCHAR(45) NOT NULL, `date` DATETIME NOT NULL);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS report (`reporter` VARCHAR(45) NOT NULL, `reported` VARCHAR(45) NOT NULL, `date` DATETIME NOT NULL);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS block (`blocker` VARCHAR(45) NOT NULL, `blocked` VARCHAR(45) NOT NULL, `date` DATETIME NOT NULL);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS chat (`people1` VARCHAR(45) NOT NULL, `people2` VARCHAR(45) NOT NULL, `texte` LONGTEXT);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
try {
	$bdd->query('CREATE TABLE IF NOT EXISTS notification (`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,`sender` VARCHAR(45) NOT NULL, `receiver` VARCHAR(45) NOT NULL, `content` LONGTEXT, `date` DATETIME NOT NULL);');
}
catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}
}
else {
	echo "<meta http-equiv='refresh' content='0;URL=../index.php'/>";
}
?>
