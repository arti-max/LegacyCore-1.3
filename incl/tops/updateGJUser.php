<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/Lib.php";
$gs = new Lib();

if(!isset($_POST["userName"]) OR !isset($_POST["secret"]) OR !isset($_POST["stars"]) OR !isset($_POST["demons"]) OR !isset($_POST["icon"]) OR !isset($_POST["color1"]) OR !isset($_POST["color2"])){
	exit("-1");
}

$userName = $_POST["userName"];
$stars  = $_POST["stars"];
$demons = $_POST["demons"];
$icon = $_POST["icon"];
$ship = $_POST["ship"];
$color1 = $_POST["color1"];
$color2 = $_POST["color2"];

$udid = $gs->getIDFromPost();
$userID = $gs->getUserID($udid, $userName);

$query = $db->prepare("SELECT stars, demons FROM users WHERE userID=:userID LIMIT 1");
$query->execute([':userID' => $userID]);
$old = $query->fetch();


$query = $db->prepare("UPDATE users SET userName=:userName, stars=:stars, demons=:demons, icon=:icon, color1=:color1, color2=:color2 WHERE userID=:userID");
$query->execute([':stars' => $stars, ':demons' => $demons, ':icon' => $icon, ':color1' => $color1, ':color2' => $color2, ':userName' => $userName, ':userID' => $userID]);

echo $userID;
?>
