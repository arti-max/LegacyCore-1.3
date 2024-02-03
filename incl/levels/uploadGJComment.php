<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/Lib.php";
require_once "../lib/commands.php";

$Lib = new Lib();


$userName = $_POST["userName"];
$levelID = $_POST["levelID"];
$comment = $_POST["comment"];

$udid = $Lib->getIDFromPost();

$userID = $Lib->getUserID($udid, $userName);
$uploadDate = time();

if(Commands::Commands($udid, $comment, $levelID)){
	exit("-1");
}

if($udid != "" AND $comment != ""){
    $query = $db->prepare("INSERT INTO comments (userID, userName, levelID, timestamp, comment) VALUES (:userID, :userName, :levelID, :uploadDate, :comment)");
    $query->execute([':userID' => $userID, ':userName' => $userName, ':levelID' => $levelID, ':uploadDate' => $uploadDate, ':comment' => $comment]);
    echo "1";
} else {
    echo "-1";
}
?>
