<?php
//error_reporting(0);
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/Lib.php";
$Lib = new Lib();
require_once "../lib/Lib.php";
$gs = new Lib();


$gameVersion = $_POST["gameVersion"];
$userName = $_POST["userName"];
$levelID = $_POST["levelID"];
$levelName = $_POST["levelName"];
$levelDesc = $_POST["levelDesc"];
$levelVersion = $_POST["levelVersion"];
$levelLength = $_POST["levelLength"];
$audioTrack = $_POST["audioTrack"];
$secret = $_POST["secret"];
$levelString = $_POST["levelString"];

$levelName = str_replace("?", "", $levelName);
$levelName = str_replace("}", "", $levelName);
$levelName = str_replace("{", "", $levelName);
$levelName = str_replace(")", "", $levelName);
$levelName = str_replace("(", "", $levelName);
$levelName = str_replace("/", "", $levelName);
$levelName = str_replace(".", "", $levelName);
$levelName = str_replace(":", "", $levelName);
$levelName = str_replace(";", "", $levelName);


$id = $gs->getIDFromPost();
$userID = $Lib->getUserID($id, $userName);
$uploadDate = time();
$query = $db->prepare("SELECT count(*) FROM levels WHERE uploadDate > :time AND (userID = :userID)");
$query->execute([':time' => $uploadDate - 60, ':userID' => $userID]);
if($query->fetchColumn() > 0){
	exit("-1");
}
$query = $db->prepare("INSERT INTO levels (levelName, levelDesc, userName, levelVersion, gameVersion, audioTrack, levelLength, userID, secret, levelString, udid, uploadDate)
VALUES (:levelName, :levelDesc, :userName, :levelVersion, :gameVersion, :audioTrack, :levelLength, :userID, :secret, :levelString, :udid, :uploadDate)");


if($levelString != "" AND $levelName != ""){
	$querye=$db->prepare("SELECT levelID FROM levels WHERE levelName = :levelName AND userID = :userID");
	$querye->execute([':levelName' => $levelName, ':userID' => $userID]);
	$levelID = $querye->fetchColumn();
	$lvls = $querye->rowCount();
	if($lvls==1){
		$query = $db->prepare("UPDATE levels SET levelName=:levelName, gameVersion=:gameVersion, userName=:userName, levelDesc=:levelDesc, levelVersion=:levelVersion, levelLength=:levelLength, audioTrack=:audioTrack, levelString=:levelString, secret=:secret WHERE levelName=:levelName AND udid=:udid");	
		$query->execute([':levelName' => $levelName, ':levelDesc' => $levelDesc, ':userName' => $userName, ':levelVersion' => $levelVersion, ':gameVersion' => $gameVersion, ':audioTrack' => $audioTrack, ':levelLength' => $levelLength, ':userID' => $userID, ':secret' => $secret, ':levelString' => "", ':udid' => $id]);
		file_put_contents("../../data/$levelID",$levelString);
		echo $levelID;
	}else{
		$query->execute([':levelName' => $levelName, ':levelDesc' => $levelDesc, ':userName' => $userName, ':levelVersion' => $levelVersion, ':gameVersion' => $gameVersion, ':audioTrack' => $audioTrack, ':levelLength' => $levelLength, ':userID' => $userID, ':secret' => $secret, ':levelString' => "", ':udid' => $id, ':uploadDate' => $uploadDate - 60]);
		$levelID = $db->lastInsertId();
		file_put_contents("../../data/$levelID",$levelString);
		echo $levelID;
	}
}else{
	echo -1;
}
?>
